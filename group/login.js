function login(event) {
    
    const username = document.getElementById("username_login").value; // Get the username from the form
    const password = document.getElementById("password_login").value; // Get the password from the form
    const token= document.getElementById("token").value;  
    // Make a URL-encoded string for passing POST data:
    const data = { 'username': username, 'password': password, 'token': token};
    console.log(JSON.stringify(data));
    fetch("login.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {console.log((JSON.stringify(data)));
        alert(JSON.stringify(data))});   //data.success ? "You've been logged in!" : `You were not logged in ${data.message}`))
        //.catch(err => console.error(err));
    
}

document.getElementById("login_btn").addEventListener("click", login, false); // Bind the AJAX call to button click