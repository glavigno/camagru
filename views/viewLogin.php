<div class="login">
    <?php if ($notification != null): ?>
    <div class="alert">
        <p><?= $notification ?></p>
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
    </div>
    <?php endif; ?>
    <form action="<?= URL ?>?url=login&submit=ok" method="post" >
        <label for="login"><i class="fas fa-user-circle"></i><b>Login</b></label>
        <input type="text" placeholder="Login..." name="login" required>
        <label for="password"><i class="fas fa-lock"></i><b>Password</b></label>
        <input type="password" placeholder="Password..." name="password" required>
        <button type="submit">Login</button>
    </form>
    <a href="<?= URL ?>?url=reset">Forgot your password ?</a>
</div>