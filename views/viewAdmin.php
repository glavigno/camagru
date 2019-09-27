<div class="admin">
    <form>
        <label for="new_login"><i class="fas fa-user-edit"></i><b>New login</b></label>
        <input type="text" placeholder="Login..." name="new_login" required>
        <br>
        <button type="button" onclick="updateLogin()">Update</button>
    </form>

    <form>
        <label for="old_password"><i class="fas fa-lock-open"></i><b>Old password</b></label>
        <input type="password" placeholder="Old password..." name="old_password" required>
        <br>
        <label for="new_password"><i class="fas fa-lock"></i><b>New password</b></label>
        <input type="password" placeholder="New password..." name="new_password" required>
        <br>
        <button type="button" onclick="updatePassword()">Update</button>
    </form>

    <form>
        <label for="email"><i class="fas fa-paper-plane"></i><b>New email</b></label>
        <input type="email" placeholder="New email..." name="email" required>
        <br>
        <button type="button" onclick="updateEmail()">Update</button>
    </form>

    <div class="notif_box">
        <p>Notification On / Off</p>
        <label class="switch">
            <?php if ($notifStatus): ?>
            <input id="notifButton" type="checkbox" checked>
            <span class="slider round"></span>
            <?php else: ?>
            <input id="notifButton" type="checkbox">
            <span class="slider round"></span>
            <?php endif; ?>
        </label>
    </div>

    <a href="<?= URL ?>?url=admin&del_account=ok">
        <button type="submit">Delete my account</button>
    </a>
</div>

<script type="text/javascript" src="../public/js/adminDashboard.js"></script>