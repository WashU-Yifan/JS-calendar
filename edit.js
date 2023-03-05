function insert(event) {
    const date = document.getElementById("event_date").value; // Get the username from the form
    const title = document.getElementById("event_title").value; // Get the password from the form
    const description= document.getElementById("event_descript").value;
    const token= document.getElementById("token").value;  
    // Make a URL-encoded string for passing POST data:
    const data = { 'date': date, 'title': title, 'description':description, 'token': token};

    fetch("edit_event.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        }).then(res=>res.json())
        .then(data => console.log(data.success ? "You have insert event!" : `You did not insert ${data.message}`))
        .catch(err => console.error(err))
        updateCalendar();

}

document.getElementById("insert_event").addEventListener("click",insert,false);