function updateDateTime() {
    var now = new Date();
    var datetime = now.toLocaleString();
    document.getElementById("datetime").innerHTML = datetime;
}

setInterval(updateDateTime, 1000);
