function loadRoomStatus(){
    var xhr = new XMLHttpRequest();
    xhr.open("GET","../Control/ajaxRoomStatus.php",true);
    xhr.onload=function(){
        if(xhr.status===200){
            var rooms=JSON.parse(xhr.responseText);
            var board=document.getElementById("roomStatusBoard");
            board.innerHTML="";
            rooms.forEach(function(r){
                var li=document.createElement("li");
                li.textContent=r.room_number+" ("+r.room_type+") - "+r.notes;
                li.className="room-item "+r.notes.replace(/ /g,"-");
                board.appendChild(li);
            });
        }
    };
    xhr.send();
}

loadRoomStatus();
setInterval(loadRoomStatus,5000);