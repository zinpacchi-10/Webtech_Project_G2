function updateServiceStatus(requestId, status) {
    if(!requestId || !status) return;

    var xhr = new XMLHttpRequest();
    xhr.open("POST","../Control/updateServiceRequest.php",true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.onload = function(){
        if(xhr.status===200){
            location.reload();
        }else{
            console.error("AJAX error:",xhr.status);
        }
    };
    xhr.send("request_id="+requestId+"&status="+status);
}

document.querySelectorAll(".service-select").forEach(function(sel){
    sel.addEventListener("change", function(){
        var requestId = this.dataset.requestId;
        var status = this.value;
        updateServiceStatus(requestId,status);
    });
});
