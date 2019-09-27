var notifButton = document.getElementById('notifButton');
var forms = document.getElementsByTagName('form');

notifButton.onclick = function() {
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "?url=admin&notification=ok", true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send();
}

function updateLogin() {
    var new_login = document.getElementsByName('new_login')[0].value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "?url=admin&login=ok", true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            addMessage(this.responseText);
            forms[0].reset();
        }
    }; 
    xhttp.send("new_login=" + new_login);
}

function updatePassword() {
    var old_pw = document.getElementsByName('old_password')[0].value;
    var new_pw = document.getElementsByName('new_password')[0].value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "?url=admin&passwd=ok", true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            addMessage(this.responseText);
            forms[1].reset();
        }
    }; 
    xhttp.send("old_password=" + old_pw + "&new_password=" + new_pw);
}

function updateEmail() {
    var email = document.getElementsByName('email')[0].value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "?url=admin&email=ok", true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            addMessage(this.responseText);
            forms[2].reset();
        }
    }; 
    xhttp.send("email=" + email);
}

function addMessage(message) {
    if (document.getElementsByClassName('alert')[0] == null || 
        document.getElementsByClassName('alert')[0].getElementsByClassName.display == 'none') {
        var mainDiv = document.getElementsByClassName('admin')[0];
        var alertDiv = document.createElement('div');
        alertDiv.className = 'alert';
        var alertContent = document.createElement('h4');
        alertContent.className = 'textInside'
        var spanButton = document.createElement('span'); 
        alertContent.innerText = message;
        alertDiv.appendChild(alertContent);
        alertDiv.appendChild(spanButton);
        alertDiv.innerHTML += '<span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>';
        mainDiv.insertBefore(alertDiv, mainDiv.firstChild);
    }
    else {
        var alertText = document.getElementsByClassName('textInside')[0];
        alertText.innerText = message;
    }
}