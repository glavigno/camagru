<div class="register">
    <form action="<?= URL ?>?url=register&submit=ok" method="post" >
        <label for="login"><i class="fas fa-user-circle"></i><b>Login</b></label>
        <input type="text" placeholder="Login..." name="login" required>
        <label for="email"><i class="fas fa-paper-plane"></i><b>Email</b></label>
        <input type="email" placeholder="Email..." name="email" required>
        <label for="firstName"><i class="fas fa-user"></i><b>First name</b></label>
        <input type="text" placeholder="First name..." name="firstName" required>
        <label for="lastName"><i class="fas fa-signature"></i><b>Last name</b></label>
        <input type="text" placeholder="Last name..." name="lastName" required>
        <label for="password"><i class="fas fa-lock"></i><b>Password</b></label>
        <input id="passwd" type="password" placeholder="Password..." name="password"  value="" required>
        <div class="box">
            <ul id="list">
                <li id="lowercase">The password must contain at least 1 lowercase alphabetical character</li>
                <li id="uppercase">The password must contain at least 1 uppercase alphabetical character</li>
                <li id="numeric">The password must contain at least 1 numeric character</li>
                <li id="special">The password must contain at least 1 special character</li>
                <li id="length">The password must be 8 characters or longer</li>
            </ul>
            <i id ="mood" class="far fa-frown fa-5x"></i>
        </div>
        <button id="register_button" type="submit">Register</button>
    </form>
</div>
<script type="text/javascript" src="../public/js/passwordBar.js"></script>