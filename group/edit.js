function remove(event) {
    const time = document.getElementById("del_event_time").value;
    const date = document.getElementById("del_event_date").value; 
    const token= document.getElementById("token").value;   
    // Make a URL-encoded string for passing POST data:
    const data = { 'time':time,'date': date, 'token': token};
    console.log(JSON.stringify(data));
    fetch("rm_event.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        }).then(res=>res.json())
        .then(data => console.log(data))//data.success ? "You have remove the event!" : `remove not succeed ${data.message}`))
        .catch(error => console.error('Error:',error))
    updateCalendar();

}

document.getElementById("remove_event").addEventListener("click",remove,false);

function insert(event) {
    const time = document.getElementById("event_time").value;
    const date = document.getElementById("event_date").value; 
    const title = document.getElementById("event_title").value; 
    const description= document.getElementById("event_descript").value;
    const token= document.getElementById("token").value;   
    // Make a URL-encoded string for passing POST data:
    const data = { 'time':time,'date': date, 'title': title, 'description':description, 'token': token};

    fetch("edit_event.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        }).then(res=>res.json())
        .then(data => console.log(data.success ? "You have insert event!" : `You did not insert ${data.message}`))
        .catch(error => console.error('Error:',error))
    updateCalendar();

}

document.getElementById("insert_event").addEventListener("click",insert,false);