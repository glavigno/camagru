var canvas = document.getElementById('canvasMain');
var context = canvas.getContext('2d');
var canvasTop = document.getElementById('canvasTop');
var contextTop = canvasTop.getContext('2d');
var video = document.getElementById('video');
var snapButton = document.getElementById('snapButton');
var uploadButton = document.getElementById('uploadButton');
var saveButton = document.getElementById('saveButton');
var retryButton = document.getElementById('retryButton');
var clearButton = document.getElementById('clearButton');
var upld = document.querySelector('input[type="file"]');
var buttons = document.getElementsByClassName('boothButtons');

buttons[3].style.display = 'none';
buttons[4].style.display = 'none';

var img = null;
var data = null;
var filter = null;
var drawing = false;
var uploadPic = false;
var pic_info;

var currentX = canvas.width/2;
var currentY = canvas.height/2;
var isDraggable = false;

var coordX = null;
var coordY = null;
var final = null;


function clearFilter() {
    if (filter) {
        filter = null;
        drawing = null;
        context.clearRect(0, 0, canvas.width, canvas.height);
    }
}

function addFilter(src)
{
    filter = src;
    drawing = new Image();
    drawing.src = filter;
    drawing.onload = function() {
        moveListener();
    }   
}

function drawFilter() {
    if (drawing)
        context.drawImage(drawing, currentX - (drawing.width/2), currentY - (drawing.height/2))
};

function drawFilterUpload() {
    if (drawing)
        contextTop.drawImage(drawing, currentX - (drawing.width/2), currentY - (drawing.height/2))
};

function clearCanvas() {
    context.clearRect(0, 0, canvas.width, canvas.height);
}

function clearCanvasTop() {
    contextTop.clearRect(0, 0, canvas.width, canvas.height);
}

function mouseEvents() {
    canvas.onmousedown = function(e) {   
        var mouseX = e.pageX - this.offsetLeft;
        var mouseY = e.pageY - this.offsetTop;
    
        if ((mouseX >= (currentX - 100) && mouseX <= (currentX + 100)) && 
            (mouseY >= (currentY - 100) && mouseY <= (currentY + 100))) {
                isDraggable = true;
        }
    };
    canvas.onmouseup = function(e) {
      isDraggable = false;
    };
    canvas.onmouseout = function(e) {
      isDraggable = false;
    };
    canvas.onmousemove = function(e) {
      if (isDraggable) {
            currentX = e.pageX - this.offsetLeft;
            currentY = e.pageY - this.offsetTop - 50;
       }
    };
}

function moveListener() {
    mouseEvents();
    var inter = setInterval(function() {
        clearCanvas();
        drawFilter();
    }, 1000/30);
    snapButton.addEventListener('click', function() {
        clearInterval(inter);
    });
}

if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
        video.srcObject = stream;
        video.play();
    });
}

function hideButtons() {
    buttons[0].style.display = 'none';
    buttons[1].style.display = 'none';
    buttons[2].style.display = 'none';
    buttons[3].style.display = 'initial';
    buttons[4].style.display = 'initial';
}

function displayButtons() {
    buttons[0].style.display = 'initial';
    buttons[1].style.display = 'initial';
    buttons[2].style.display = 'initial';
    buttons[3].style.display = 'none';
    buttons[4].style.display = 'none';
}

function snapPicture() {
    hideButtons();
    if (!uploadPic) {
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        data = canvas.toDataURL('image/png');
    } else {
        contextTop.drawImage(img, 0, 0, canvas.width, canvas.height);
        data = canvasTop.toDataURL('image/png');
    }
    if (filter) {
        drawing = new Image();
        drawing.src = filter;
        coordX = currentX - (drawing.width / 2);
        coordY = currentY - (drawing.height / 2);
        context.drawImage(drawing, coordX, coordY);
        final = canvas.toDataURL('image/png');
    }
}

function prependNewImg(pic_info) {
    var url;
    var preview = document.getElementsByClassName('preview')[0];
    var firstAnchor = preview.getElementsByTagName('a')[0];
    if(firstAnchor) {
        url = new URL(firstAnchor.href);
        url.searchParams.set('id', pic_info);
    } else {
        url = location.host + '/?url=post&id=' + pic_info;
    }
    var anchor = document.createElement('a');
    anchor.href = url;
    var element = document.createElement("img");
    if (uploadPic)
    {
        drawFilterUpload();
        element.src = canvasTop.toDataURL('image/png');
    }
    else
        element.src = canvas.toDataURL('image/png');
    anchor.appendChild(element);
    if (firstAnchor) {
        preview.insertBefore(anchor, preview.firstChild);
    } else {
        preview.appendChild(anchor);
    }
}

function savePicture() {
    displayButtons();
    if (data) {
        var xhttp = new XMLHttpRequest();
        video.play();
        xhttp.open("POST", "?url=main&save=ok", true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                    prependNewImg(this.responseText);
                    if (uploadPic)
                        clearCanvasTop();
                    clearCanvas();
            }
        }; 
        xhttp.send("picture=" + data + "&filter=" + filter + "&x=" + coordX + "&y=" + coordY);
    }
}

function retrySnap() {
    displayButtons();
    uploadPic = false;
    context.clearRect(0, 0, canvas.width, canvas.height);
    contextTop.clearRect(0, 0, canvas.width, canvas.height);
    video.play();
    filter = null;
}

uploadButton.addEventListener('click', function () {
    upld.addEventListener('change', function () {
        var reader = new FileReader();
        reader.onload = function () {
            img = new Image();
            img.onload = function () {
                uploadPic = true;
                video.pause();
                contextTop.drawImage(img, 0, 0, canvas.width, canvas.height);
                if (filter) {
                    moveListener();
                }
            }
            img.src = reader.result;
            data = img.src;
        }
        reader.readAsDataURL(upld.files[0]);
    })
})