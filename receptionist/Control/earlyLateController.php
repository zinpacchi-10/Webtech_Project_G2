<?php
session_start();
require_once("../Model/db.php");

if(!isset($_SESSION["receptionist_logged_in"])){
    header("Location: receptionistlogin.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["request_id"], $_POST["status"])){
    $request_id = intval($_POST["request_id"]);
    $status = trim($_POST["status"]);

    $valid_status = ["approved","declined"];
    if(!in_array($status,$valid_status)){
        $_SESSION['earlylate_error']="Invalid action!";
        header("Location: ../View/early_late_requests.php");
        exit();
    }

    $db = new db();
    $conn = $db->openConn();

    $sql_request = "SELECT elr.booking_id, elr.room_id, elr.request_type, elr.request_date,
                           b.checkin_date, b.checkout_date
                    FROM early_late_requests elr
                    JOIN bookings b ON elr.booking_id = b.booking_id
                    WHERE elr.id=? LIMIT 1";
    $stmt_request = $conn->prepare($sql_request);
    $stmt_request->bind_param("i", $request_id);
    $stmt_request->execute();
    $request = $stmt_request->get_result()->fetch_assoc();

    if(!$request){
        $_SESSION['earlylate_error']="Request not found!";
        header("Location: ../View/early_late_requests.php");
        exit();
    }

    if($status == "approved"){
        $new_checkin = $request["checkin_date"];
        $new_checkout = $request["checkout_date"];

        if($request["request_type"] == "early_checkin"){
            $new_checkin = $request["request_date"];
        } else if($request["request_type"] == "late_checkout"){
            $new_checkout = $request["request_date"];
        }

        $sql_conflict = "SELECT COUNT(*) AS conflict FROM bookings
                         WHERE room_id=? AND booking_id!=? AND bookout_date IS NULL
                         AND (checkin_date < ? AND checkout_date > ?)";
        $stmt_conflict = $conn->prepare($sql_conflict);
        $stmt_conflict->bind_param("iiss", $request["room_id"], $request["booking_id"], $new_checkout, $new_checkin);
        $stmt_conflict->execute();
        $conflict = $stmt_conflict->get_result()->fetch_assoc()["conflict"];

        if($conflict > 0){
            $_SESSION['earlylate_error']="Room is not available for the requested time.";
            header("Location: ../View/early_late_requests.php");
            exit();
        }

        $sql_booking = "UPDATE bookings SET checkin_date=?, checkout_date=? WHERE booking_id=?";
        $stmt_booking = $conn->prepare($sql_booking);
        $stmt_booking->bind_param("ssi", $new_checkin, $new_checkout, $request["booking_id"]);
        $stmt_booking->execute();
        $stmt_booking->close();
        $stmt_conflict->close();
    }

    $sql = "UPDATE early_late_requests SET status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",$status,$request_id);
    $stmt->execute();

    $stmt_request->close();
    $stmt->close();
    $db->closeConn($conn);

    $_SESSION['earlylate_success']="Request ".$status." successfully!";
    header("Location: ../View/early_late_requests.php");
    exit();
}else{
    $_SESSION['earlylate_error']="Invalid request!";
    header("Location: ../View/early_late_requests.php");
    exit();
}
?>
