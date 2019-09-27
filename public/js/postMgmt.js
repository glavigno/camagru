var likes = document.getElementById('likes_number')
var comments = document.getElementById('comments_number');
var heart = document.getElementById('heart');

function likePost() {
    heart.className = 'fas fa-heart fa-3x';
    likes.innerText = parseInt(likes.innerText) + 1;
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", location.search + "&like=ok", true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send();
    heart.setAttribute('onclick', 'unlikePost()');
}

function unlikePost() {
    heart.className = 'far fa-heart fa-3x';
    likes.innerText = parseInt(likes.innerText) - 1;
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", location.search + "&like=ok", true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send();
    heart.setAttribute('onclick', 'likePost()');
}

function prependNewComment(author, content, id) {
    var status;
    var  post = document.getElementsByClassName('comments')[0]
    if(document.getElementsByClassName('previous')[0] == null) {
        var previous = document.createElement('div');
        previous.className = 'previous';
        post.appendChild(previous);
        status = 1;
    }
    else {
        var previous = document.getElementsByClassName('previous')[0];
        status = 0;
    }
    var mainDiv = document.createElement('div');
    var commContent = document.createElement("p");
    var secondDiv = document.createElement('div');
    var from = document.createElement("p");
    commContent.id = 'content';
    commContent.innerText = content;
    mainDiv.appendChild(commContent);
    mainDiv.appendChild(secondDiv);
    secondDiv.appendChild(from);
    secondDiv.id = 'author';
    var url = new URL(location.href);
    url.searchParams.set('commid', id);
    from.innerHTML += 'by <span id="bottom">' + author + '</span>';
    from.innerHTML += '<a href="' + url + '"><i class="fas fa-trash-alt"></i></a>';
    if (status) {
        previous.appendChild(mainDiv);
    }
    else {
        previous.insertBefore(mainDiv, previous.firstChild);
    }
}

function leaveComment(author) {
    var content = document.getElementById("commentContent").value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", location.search + "&comment=ok", true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            comments.innerText = parseInt(comments.innerText) + 1;
            prependNewComment(author, content, this.responseText);
        }
    };
    xhttp.send("content=" + content);
}