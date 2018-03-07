function fetchFriends(id) {
	var xmlhttp = new XMLHttpRequest();
	var obj = {"id":id};	
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var friends = JSON.parse(this.responseText);
			var txt = '';
			for (friend in friends) {
				txt += '<div class = "row my-2">';
				txt += '<div class = "border border-success rounded bg-light offset-0 col-12 offset-xl-1 col-xl-7 py-1 pl-2 pr-1">';
				txt += '<div onclick="fetchMessages('+id+','+friends[friend].id+')" class="pointer d-flex justify-content-start p-1" data-toggle="collapse" data-target="#mc'+friends[friend].id+'" aria-expanded="false" aria-controls="collapseExample">';
				txt += '<img class="align-self-start mr-3" width="32" height="32" src="'+friends[friend].avatar+'" alt="Error 404">';
				txt += '<div>'+friends[friend].username+'</div>';
				txt += '</div>';
				txt += '<div class="collapse" id="mc'+friends[friend].id+'">';
				txt += '<div class="border border-dark rounded mt-3 bg-white p-2">';
				txt += '<div id="m'+friends[friend].id+'"></div>';
				txt += '</div>';
				txt += '<div class="d-flex justify-content-start mt-2">';
				txt += '<input class="form-control mr-1" type="text" id="'+friends[friend].id+'" placeholder="Message...">';
				txt += '<button onclick="sendMessage('+id+','+friends[friend].id+')" class="btn btn-outline-success">Send</button>';
				txt += '</div>';
				txt += '</div>';
				txt += '</div>';
				txt += '</div>';
			}
			document.getElementById("friends_list").innerHTML = txt;
		}
	};
	xmlhttp.open("POST", "php/fetchFriends.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);
}

function friendRequests(id, username) {
	var xmlhttp = new XMLHttpRequest();
	var obj = {"id":id};
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var request = JSON.parse(this.responseText);
			var txt = '';
			for (req in request) {
				txt += '<div class = "row my-2">';
				txt += '<div class = "border border-success rounded bg-light offset-0 col-12 offset-xl-1 col-xl-7 py-1 pl-2 pr-1">';
				txt += '<div class="d-flex justify-content-between">';
				txt += '<a class="btn btn-outline-dark" href="profile.php?id='+request[req].id_from+'">'+request[req].username_from+'</a>';
				txt += '<div>';
				txt += '<button onclick="acceptRequest('+request[req].id_from+','+request[req].id_to+",'"+username+"'"+')" class="btn btn-outline-primary ml-md-2 my-2 my-md-0">Accept</button>';
				txt += '<button onclick="declineRequest('+request[req].id_from+','+request[req].id_to+')" class="btn btn-outline-danger ml-md-2 my-2 my-md-0">Decline</button>';
				txt += '</div>';
				txt += '</div>';
				txt += '</div>';
				txt += '</div>';							
			}
			document.getElementById('requests').innerHTML = txt;
		}
	};
	xmlhttp.open("POST", "php/fetchRequests.php", true);	
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");				
	xmlhttp.send("x="+json);
}

function acceptRequest(id_from, id_to, username) {
	var xmlhttp = new XMLHttpRequest();
	var obj = {"id_from":id_from,"id_to":id_to,"username":username};
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			friendRequests(id_to);
			location.reload(true);
		}
	};
	xmlhttp.open("POST", "php/acceptRequest.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);	
}

function declineRequest(id_from, id_to) {
	var xmlhttp = new XMLHttpRequest();
	var obj = {"id_from":id_from,"id_to":id_to};
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			friendRequests(obj.id_to);
			location.reload(true);
		}
	};
	xmlhttp.open("POST", "php/declineRequest.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);	
}

function sendRequest(id_from, username, id_to) {
	var xmlhttp = new XMLHttpRequest();
	var obj = {"id_from":id_from,"username":username,"id_to":id_to};
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var res = JSON.parse(this.responseText);
			var txt = '<div class="d-flex justify-content-start align-items-center">';
			txt += '<div class="text-success p-1">'+res.message+'</div>';
			txt += '<button onclick="cancelRequest('+id_from +",'"+username+"',"+id_to+')" class="btn btn-outline-danger ml-2">Cancel</button>';
			txt += '</div>'
			document.getElementById("friendRequestStatus").innerHTML = txt;			
		}		
	};
	xmlhttp.open("POST", "php/sendRequest.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);
}

function cancelRequest(id_from, username, id_to) {
	var xmlhttp = new XMLHttpRequest();
	var obj = {"id_from":id_from, "id_to":id_to};
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var txt = '<button onclick="sendRequest('+id_from +",'"+username+"',"+id_to+')" class="btn btn-outline-success my-2">Add Friend</button>';
			document.getElementById("friendRequestStatus").innerHTML = txt;
		}		
	};
	xmlhttp.open("POST", "php/cancelRequest.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);
}