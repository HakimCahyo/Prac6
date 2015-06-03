function timer(count) {
    var timeout = function() {
        if (count >= 0) {
            document.title = formatTime(count);
            count--;
        } else {
            clearInterval();
            window.location = "createLogTimer.php";
        }
    }
    setInterval(timeout, 1000);
}

//function to format seconds to human-readable time
function formatTime(sec) {
    var hours = Math.floor(sec / (60 * 60));
    var divisorForMins = sec % (60 * 60);
    var minutes = Math.floor(divisorForMins / 60);
    var divisorForSecs = divisorForMins % 60;
    var seconds = Math.ceil(divisorForSecs);
    var obj = hours + ":" + minutes + ":" + seconds;
    return obj;
}
