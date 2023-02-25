<!DOCTYPE HTML>

<html>
<head>
    <meta charset="UTF-8">
    
    <title>Calculator</title>
</head>
<body>
    <?php
        session_start();
        $_SESSION['token'] = bin2hex(random_bytes(32));
    ?>
    <input type="text" id="username_login" placeholder="Username" />
    <input type ="hidden" id = "token" value="<?php echo $_SESSION['token'];?>">
    <input type="password" id="password_login" placeholder="Password" /><br>

    <button id="login_btn">Log In</button><br>
    <input type="text" id="username_register" placeholder="Username" />
    <input type="password" id="password_register1" placeholder="Password" />
    <input type="password" id="password_register2" placeholder="Repeat Password" /><br>
    <input type ="hidden" id = "token" value="<?php echo $_SESSION['token'];?>">
    <button id="register_btn">Register</button>
    <button id="prev_month">Previous Month</button>
    <button id="next_month">Next Month</button>
    
    <table styele="width:100%">
        <tr>
            <th>SUN</th>
            <th>MON</th>
            <th>TUE</th>
            <th>WED</th>
            <th>THU</th>
            <th>FRI</th>
            <th>SAT</th>
        </tr>
    </table>


    <script type="text/javascript" src="login.js"></script> 
    <script type="text/javascript" src="register.js"></script> 

    </body>

</html>
