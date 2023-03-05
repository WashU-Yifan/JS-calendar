(function(){Date.prototype.deltaDays=function(c){return new Date(this.getFullYear(),this.getMonth(),this.getDate()+c)};Date.prototype.getSunday=function(){return this.deltaDays(-1*this.getDay())}})();
function Week(c){this.sunday=c.getSunday();this.nextWeek=function(){return new Week(this.sunday.deltaDays(7))};this.prevWeek=function(){return new Week(this.sunday.deltaDays(-7))};this.contains=function(b){return this.sunday.valueOf()===b.getSunday().valueOf()};this.getDates=function(){for(var b=[],a=0;7>a;a++)b.push(this.sunday.deltaDays(a));return b}}
function Month(c,b){this.year=c;this.month=b;this.nextMonth=function(){return new Month(c+Math.floor((b+1)/12),(b+1)%12)};this.prevMonth=function(){return new Month(c+Math.floor((b-1)/12),(b+11)%12)};this.getDateObject=function(a){return new Date(this.year,this.month,a)};this.getWeeks=function(){var a=this.getDateObject(1),b=this.nextMonth().getDateObject(0),c=[],a=new Week(a);for(c.push(a);!a.contains(b);)a=a.nextWeek(),c.push(a);return c}};

// For our purposes, we can keep the current month in a variable in the global scope
let currentMonth = new Month(2023, 3); // October 2017
const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
// Change the month when the "next" button is pressed
document.getElementById("next_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.nextMonth(); // Previous month would be currentMonth.prevMonth()
	updateCalendar(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
	alert("The new month is "+currentMonth.month+" "+currentMonth.year);
}, false);

document.getElementById("prev_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.prevMonth(); // Previous month would be currentMonth.prevMonth()
	updateCalendar(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
	alert("The new month is "+currentMonth.month+" "+currentMonth.year);
}, false);


// This updateCalendar() function only alerts the dates in the currently specified month.  You need to write
// it to modify the DOM (optionally using jQuery) to display the days and weeks in the current month.
function updateCalendar(){
	document.getElementById("month").innerHTML=month[currentMonth.month];
	let weeks = currentMonth.getWeeks();
	document.getElementById("table").innerHTML="";
	let html="<tr>";
	//html+="<th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";
	update_first_row(document.getElementById("table"))
	for(let w in weeks){
		let days = weeks[w].getDates();
		
		weektable=document.getElementById("table");
		let html="<tr>";
	
		
		for(let d in days){

			let date =days[d].toISOString().split("T")[0];//get the date of the days. i.e 2023-03-01
			const data = { 'date': date};
			if(w==0&&date>7)
				html+="<td style='background: #c4c2c2;'>"
			else if(w==weeks.length-1&&date<7)
				html+="<td style='background: #c4c2c2;'>"
			else
				html+="<td>";
			html+=date.split("-")[2];+"</td>";//get the day of the date. i.e. 01
			
            fetch("event.php", {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: { 'content-type': 'application/json' }
                })
                .then(response => response.json())
                .then(function(data){
                    if(data.success){
                        update_event(data,parseInt(w)+1,d);//table's first row display days, Sun, Mon... etc. So we need
						//to add 1 to the row 
                    }
					else{
						console.log(`Message:${data.message}`);
					}
                })
                .catch(err => console.error(err));  
		}
		html+="</tr>";
		weektable.innerHTML+=html;
		//document.getElementById("table").appendChild(Node);
	}
}


function jump_month(event){
	const date = document.getElementById("jump_event_date").value; 
	console.log(date);
	let month= parseInt(date.split("-")[1]);
	let year= parseInt(date.split("-")[0]);
	console.log(month);
	console.log(year);
	currentMonth=new Month(year, month).prevMonth();
	updateCalendar();
}

document.getElementById("jump_month").addEventListener("click", jump_month, false);


function update_event(event,w,d){
	//insert the event tile and description in to the specificdate
	if(!event.event){
		return ;
	}
	let table=document.getElementById("table");
	let html="<p>"+event.time+"</p>";
	html+="<strong>"+event.title+"</strong>";
	html+="<p>"+event.descript+"</p>";
	table.rows[w].cells[d].innerHTML+=html;
	
}

function update_first_row(table){
	let row= document.createElement("tr");
	let th=document.createElement("th");
	let day=document.createTextNode("Sun");
	th.appendChild(day);
	row.appendChild(th);

	th=document.createElement("th");
	day=document.createTextNode("Mon");
	th.appendChild(day);
	row.appendChild(th);

	th=document.createElement("th");
	day=document.createTextNode("Tue");
	th.appendChild(day);
	row.appendChild(th);

	th=document.createElement("th");
	day=document.createTextNode("Wed");
	th.appendChild(day);
	row.appendChild(th);

	th=document.createElement("th");
	day=document.createTextNode("Thu");
	th.appendChild(day);
	row.appendChild(th);

	th=document.createElement("th");
	day=document.createTextNode("Fri");
	th.appendChild(day);
	row.appendChild(th);
	
	th=document.createElement("th");
	day=document.createTextNode("Sat");
	th.appendChild(day);
	row.appendChild(th);

	table.appendChild(row);
}