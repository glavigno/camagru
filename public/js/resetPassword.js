var passwd = document.getElementById('new_password');

function checkNewPassword() {
    if (passwd.value.match(/^.*(?=.{8,})((?=.*[\W]){1})(?=.*\d)((?=.*[a-z]){1})((?=.*[A-Z]){1}).*$/)) {
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", location.origin + "/?url=reset&done=ok", true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.send("new_password=" + passwd.value);
        window.location.href = location.origin + "/?url=login";
    }
    else {
        if (document.getElementsByClassName('alert')[0] == null || document.getElementsByClassName('alert')[0].style.display == 'none')
            addMessage('Failure, password is too weak');
    }
}

function addMessage(message) {
    var mainDiv = document.getElementsByClassName('reset')[0];
    var alertDiv = document.createElement('div');
    alertDiv.className = 'alert';
    var alertContent = document.createElement('h4');
    var spanButton = document.createElement('span'); 
    alertContent.innerText = message;
    alertDiv.appendChild(alertContent);
    alertDiv.appendChild(spanButton);
    alertDiv.innerHTML += '<span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>';
    mainDiv.insertBefore(alertDiv, mainDiv.firstChild);
}