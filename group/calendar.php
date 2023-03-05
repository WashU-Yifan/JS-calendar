
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
    <title>Calendar</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
        session_start();
        $_SESSION['token']=bin2hex(random_bytes(32));
    ?>
    <input type="hidden" id ="token" value ="<?php echo $_SESSION["token"];?>"/>
    <input type="text" id="username_login" placeholder="Username" />
    <input type="password" id="password_login" placeholder="Password" />
    <button id="login_btn">Log In</button>

    <button id="logout_btn">Log Out</button><br>

    <input type="text" id="username_register" placeholder="Username" />
    <input type="password" id="password_register1" placeholder="Password" />
    <input type="password" id="password_register2" placeholder="Password again" />
    <button id="register_btn">register</button><br>

    <button id="prev_month_btn">previous</button>
    <button id="next_month_btn">next</button>
    <input type="date" id ="jump_event_date"/> 
    <button id="jump_month">jump to</button>
    <h2 id="month">Janurary</h2>
    <table class ="table", id = "table">
        <tr height ="10%">
            <th>Sun</th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
        </tr>
    </table>
        title<br><input type="text" id="event_title"  /><br>
        event<br><textarea id="event_descript" rows="15" cols="60"></textarea><br>
        date <input type="date" id ="event_date"/><input type="time" id="event_time"  /><br>
        <input type="text" placeholder="share with someone" id="share_user" />
        <button id="insert_event">insert</button>
        
        <p>Select a date to remove an event.</p>
        <input type="date" id ="del_event_date"/>
        <input type="time" id="del_event_time"/><br>
        <button id="remove_event">remove</button>

    <script type="text/javascript" src="register.js"></script>
    <script type="text/javascript" src="login.js"></script> <!-- load the JavaScript file -->
    <script type="text/javascript" src="date.js"></script> <!-- load the JavaScript file -->
    <script type="text/javascript" src="edit.js"></script>

</body>

</html>
