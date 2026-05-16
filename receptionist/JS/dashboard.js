function fetchAvailableRooms(roomType, checkin, checkout, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../Control/ajaxAvailableRooms.php?room_type=" + roomType + "&checkin_date=" + checkin + "&checkout_date=" + checkout, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            callback(data);
        } else {
            console.error("AJAX error:", xhr.status);
        }
    };
    xhr.send();
}

// Example usage
document.getElementById("checkRoomsBtn").addEventListener("click", function() {
    var roomType = document.getElementById("roomType").value;
    var checkin = document.getElementById("checkinDate").value;
    var checkout = document.getElementById("checkoutDate").value;

    fetchAvailableRooms(roomType, checkin, checkout, function(rooms){
        var list = document.getElementById("availableRoomsList");
        list.innerHTML = "";
        if(rooms.length > 0){
            rooms.forEach(function(r){
                var li = document.createElement("li");
                li.textContent = r.room_number + " (ID:" + r.room_id + ")";
                list.appendChild(li);
            });
        } else {
            list.innerHTML = "<li>No available rooms</li>";
        }
    });
});