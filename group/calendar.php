
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?php
        session_start();
        $_SESSION['token']=bin2hex(random_bytes(32));
    ?>
    <title>Calendar</title>
    <body>
    <input type="text" id="username_login" placeholder="Username" />
    <input type="password" id="password_login" placeholder="Password" />
    <input type="hidden" id ="token" value ="<?php echo $_SESSION["token"];?>"/>
    <button id="login_btn">Log In</button>
    <input type="text" id="username_register" placeholder="Username" />
    <input type="password" id="password_register1" placeholder="Password" />
    <input type="password" id="password_register2" placeholder="Password again" />
    <input type="hidden" id ="token" value ="<?php echo $_SESSION["token"];?>"/>
    <button id="register_btn">register</button>


    <script type="text/javascript" src="register.js"></script>
    <script type="text/javascript" src="login.js"></script> <!-- load the JavaScript file -->
</body>

</html>
