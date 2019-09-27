<?php session_start(); ?>
<?php if (!isset($_SESSION['user'])): ?> 
    <div class="alert">
        <h4>Log in or register to like and comment this picture</h4>
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
    </div>
<?php endif; ?>
<div class="post">
    <div class="info">
        <p>Posted by <span><?= $login ?></span> on <span><?= date("F j, Y, g:i a", $date)?></span></p> 
        <img src="<?= $picture ?>" alt="">
        <br>
        <?php if (isset($_SESSION['user']) && $_SESSION['user'] == $login): ?>
            <a href="<?= URL ?>?url=post&id=<?= strval($_GET['id']) ?>&del=ok"><i class="fas fa-trash-alt fa-3x"></i></a>
        <?php endif; ?>
        <?php if (isset($_SESSION['user'])): ?>
            <?php if ($liked_or_not): ?>
            <i id="heart" class="fas fa-heart fa-3x" onclick="unlikePost()"></i>
            <?php else: ?>
            <i id="heart" class="far fa-heart fa-3x" onclick="likePost()"></i>
            <?php endif; ?>
        <?php endif; ?>
        <p><span id="bottom">Number of likes : <span id="likes_number"><?= strval($nb_likes) ?></span></span></p>
        <p><span id="bottom">Number of comments : <span id="comments_number"><?= count($comments) ?></span></span></p>
    </div>
    <div class="comments">
        <?php if (isset($_SESSION['user'])): ?>
        <form>
        <textarea id="commentContent" name="content" placeholder="Write something..."></textarea>
        <br>
        <button type="button" onclick="leaveComment('<?= $_SESSION['user'] ?>')">Leave a comment <i class="far fa-comments fa-2x" ></i></button>
        </form>
        <?php endif; ?>
        <?php if ($comments): ?>
        <p id="title">Previous comments</p>
        <div class="previous">
            <?php foreach($comments as $comment): ?>
            <div>
                <p id="content"><?= $comment['content'] ?></p>
                <div id="author">
                    <p>by <span id="bottom"><?= $comment['author'] ?></strong></span>
                    <?php if ($_SESSION['user'] == $comment['author']): ?>
                    <a href="<?= URL ?>?url=post&id=<?= $id ?>&commid=<?= strval($comment['id']) ?>"><i class="fas fa-trash-alt"></i></a>
                    </p>
                    <?php endif; ?>
                </div>
            </div>      
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript" src="../public/js/postMgmt.js"></script>