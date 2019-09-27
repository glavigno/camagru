<div class="gallery">
    <div class="alert">
        <h4>Hello <?= $_SESSION['user'] ?>, hope you are doing well today</h4>
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
    </div>
    <div class="filters">
        <img src="public/filters/cocktail.png" onclick="addFilter(this.src)">
        <img src="public/filters/bloody_mary.png" onclick="addFilter(this.src)">
        <img src="public/filters/beer.png" onclick="addFilter(this.src)">
        <img src="public/filters/cigarette.png" onclick="addFilter(this.src)">
        <img src="public/filters/cap.png" onclick="addFilter(this.src)">
        <img src="public/filters/hat.png" onclick="addFilter(this.src)">
        <img src="public/filters/football.png" onclick="addFilter(this.src)">
        <img src="public/filters/beachball.png" onclick="addFilter(this.src)">
        <img src="public/filters/saxo.png" onclick="addFilter(this.src)">
        <img src="public/filters/burger.png" onclick="addFilter(this.src)">
        <img src="public/filters/hotdog.png" onclick="addFilter(this.src)">
        <img src="public/filters/apple.png" onclick="addFilter(this.src)">
        <img src="public/filters/watermelon.png" onclick="addFilter(this.src)">
        <img src="public/filters/ice_cream.png" onclick="addFilter(this.src)">
        <img src="public/filters/ice_stick.png" onclick="addFilter(this.src)">
        <img src="public/filters/bandage.png" onclick="addFilter(this.src)">
        <img  src="public/filters/parrot.png" onclick="addFilter(this.src)">
        <img  src="public/filters/red_parrot.png" onclick="addFilter(this.src)">
    </div>
    <div class="booth">
        <video id="video" width="640" height="480"></video>
        <canvas id="canvasMain" width="640" height="480"></canvas>
        <canvas id="canvasTop" width="640" height="480"></canvas>
    </div>
            <div class="buttons">
                <div class="boothButtons">
                    <i id="snapButton" class="fas fa-camera-retro fa-3x" onclick="snapPicture()"></i>
                    <p style="font-size: 14px;">Snap</p>
                </div>
                <div class="boothButtons">
                <i id="clearButton" class="fas fa-times fa-3x" onclick="clearFilter()"></i>
                <p style="font-size: 14px;">Clear</p>
                </div>
                <div class="boothButtons">
                    <label for="file-input">
                        <i id="uploadButton" class="fas fa-cloud-upload-alt fa-3x"></i>
                    </label>
                    <p style="font-size: 14px;">Upload</p>
                    <input style="display: none;" id="file-input" type="file" accept=".png"/>
                </div>
                <div class="boothButtons">
                    <i id="saveButton" class="fas fa-save fa-3x" onclick="savePicture()"></i>
                    <p style="font-size: 14px;">Save</p>                
                </div>
                <div class="boothButtons">
                    <i id="retryButton" class="fas fa-redo fa-3x" onclick="retrySnap()"></i>
                    <p style="font-size: 14px;">Retry</p>                
                </div>
            </div>
    <div class="preview">
        <?php foreach($pictures as $pic): ?>
        <a href="<?= URL ?>?url=post&id=<?= strval($pic['id']) ?>"><img src="<?= $pic['source'] ?>"></a>
        <?php endforeach ?>
    </div>
</div>
<script type="text/javascript" src="../public/js/takeSnap.js"></script>