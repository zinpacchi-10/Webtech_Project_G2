function fetchAvailableRooms(roomType, checkin, checkout, callback) {
    if(!roomType || !checkin || !checkout){
        alert("Please select room type and dates.");
        return;
    }

    // Show loader if exists
    var list = document.getElementById("availableRoomsList");
    if(list) list.innerHTML = "<li>Loading...</li>";

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../Control/ajaxAvailableRooms.php?room_type=" + encodeURIComponent(roomType) + 
                          "&checkin_date=" + encodeURIComponent(checkin) + 
                          "&checkout_date=" + encodeURIComponent(checkout), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var data = JSON.parse(xhr.responseText);
                callback(data);
            } catch (e) {
                console.error("Invalid JSON response", e);
                if(list) list.innerHTML = "<li>Error loading rooms</li>";
            }
        } else {
            console.error("AJAX error:", xhr.status);
            if(list) list.innerHTML = "<li>Error loading rooms</li>";
        }
    };
    xhr.onerror = function() {
        console.error("AJAX request failed");
        if(list) list.innerHTML = "<li>Request failed</li>";
    };
    xhr.send();
}

// Event listener for Check Rooms button
var checkBtn = document.getElementById("checkRoomsBtn");
if(checkBtn){
    checkBtn.addEventListener("click", function() {
        var roomType = document.getElementById("roomType").value;
        var checkin = document.getElementById("checkinDate").value;
        var checkout = document.getElementById("checkoutDate").value;

        fetchAvailableRooms(roomType, checkin, checkout, function(rooms){
            var list = document.getElementById("availableRoomsList");
            if(!list) return;
            list.innerHTML = "";

            if(Array.isArray(rooms) && rooms.length > 0){
                rooms.forEach(function(r){
                    var li = document.createElement("li");
                    li.textContent = r.room_number + " (ID:" + r.room_id + ")";
                    li.classList.add("available-room-item");
                    list.appendChild(li);
                });
            } else if(rooms.error){
                list.innerHTML = "<li>" + rooms.error + "</li>";
            } else {
                list.innerHTML = "<li>No available rooms</li>";
            }
        });
    });
}

// Optional: enter key search support
var checkinInput = document.getElementById("checkinDate");
var checkoutInput = document.getElementById("checkoutDate");
if(checkinInput && checkoutInput){
    [checkinInput, checkoutInput].forEach(function(el){
        el.addEventListener("keypress", function(e){
            if(e.key === "Enter"){
                e.preventDefault();
                if(checkBtn) checkBtn.click();
            }
        });
    });
}