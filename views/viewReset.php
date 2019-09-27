<div class="reset">
        <?php if ($message): ?>
            <div class="alert">
                <h4><?= $message?></h4>
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
            </div>
        <?php endif; ?>
    <?php if ($status): ?>
    <form>
        <label for="new_password"><b>Set your new password</b></label>
        <br>
        <input id="new_password" type="password" placeholder="New password..." name="new_password" required>
        <br>
        <button type="button" onclick="checkNewPassword()">Reset your password</button>
    </form>
    <?php else: ?>
    <form action="<?= URL ?>?url=reset&email=ok" method="post" >
        <label for="email"><i class="fas fa-paper-plane"></i><b>Enter your email</b></label>
        <br>
        <input type="email" placeholder="Email..." name="email" required>
        <br>
    <button type="submit">Reset your password</button>
    </form>
    <?php endif; ?>
</div>
<script type="text/javascript" src="../public/js/resetPassword.js"></script>