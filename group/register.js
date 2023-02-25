function register(event) {
    const username = document.getElementById("username_register").value; // Get the username from the form
    const password = document.getElementById("password_register1").value; // Get the password from the form
    const password2= document.getElementById("password_register2").value;
    const token= document.getElementById("token").value;  
    // Make a URL-encoded string for passing POST data:
    const data = { 'username': username, 'password1': password, 'password2':password2, 'token': token};

    fetch("register.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => console.log(data.success ? "You have registered!" : `You were not registered ${data.message}`))
        .catch(err => console.error(err));
}

document.getElementById("register_btn").addEventListener("click", register, false); // Bind the AJAX call to button click