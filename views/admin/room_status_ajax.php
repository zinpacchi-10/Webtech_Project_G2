<?php include('../header.php'); ?>

<h1>Room Status Board <span style="color: green;">(Real-time AJAX)</span></h1>

<div id="rooms-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
    <p>Loading rooms...</p>
</div>

<script>
function loadRooms() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../ajax/update_room_status.php', true);
    xhr.onload = function() {
        if(xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if(response.success) {
                displayRooms(response.rooms);
            }
        }
    };
    xhr.send();
}

function displayRooms(rooms) {
    var container = document.getElementById('rooms-container');
    var html = '';
    
    for(var i = 0; i < rooms.length; i++) {
        var room = rooms[i];
        var statusColor = room.status == 'available' ? 'green' : (room.status == 'occupied' ? 'red' : 'orange');
        
        html += '<div style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">';
        html += '<h3>Room ' + room.room_number + '</h3>';
        html += '<p>Type: ' + room.room_type + '</p>';
        html += '<p>Price: $' + room.price + '</p>';
        html += '<p style="color: ' + statusColor + '; font-weight: bold;">Status: ' + room.status + '</p>';
        html += '<select onchange="updateStatus(' + room.room_id + ', this.value)" style="width: 100%; padding: 5px;">';
        html += '<option value="available" ' + (room.status == 'available' ? 'selected' : '') + '>Available</option>';
        html += '<option value="occupied" ' + (room.status == 'occupied' ? 'selected' : '') + '>Occupied</option>';
        html += '<option value="maintenance" ' + (room.status == 'maintenance' ? 'selected' : '') + '>Maintenance</option>';
        html += '<option value="dirty" ' + (room.status == 'dirty' ? 'selected' : '') + '>Dirty</option>';
        html += '</select>';
        html += '</div>';
    }
    
    container.innerHTML = html;
}

function updateStatus(roomId, newStatus) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../ajax/update_room_status.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if(xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if(response.success) {
                alert('Status updated!');
                loadRooms();
            } else {
                alert('Error: ' + response.message);
            }
        }
    };
    xhr.send('room_id=' + roomId + '&status=' + newStatus);
}

// Auto refresh every 5 seconds
loadRooms();
setInterval(loadRooms, 5000);
</script>

<br>
<a href="../controllers/AdminController.php?action=dashboard" style="background: #666; color: white; padding: 8px 15px; text-decoration: none;">← Back to Dashboard</a>

<?php include('../footer.php'); ?>