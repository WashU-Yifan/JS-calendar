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
    .then(res => res.json())
    .then (data => console.log(data.success ? "You've been logged in!" : `You were not logged in ${data.message}`))
    .catch(error => console.error('Error:',error))
    updateCalendar();
}

document.getElementById("login_btn").addEventListener("click", login, false); // Bind the AJAX call to button click

function logout(event){
    const token= document.getElementById("token").value;  
    // Make a URL-encoded string for passing POST data:
    const data = { 'token': token};
    console.log(JSON.stringify(data));
    fetch("logout.php", {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(res => res.json())
    .then (data => console.log(data.success ? "You've logged out!" : `logout not success ${data.message}`))
    .catch(error => console.error('Error:',error))
    updateCalendar();
}
document.getElementById("logout_btn").addEventListener("click", logout, false); // Bind the AJAX call to button click
