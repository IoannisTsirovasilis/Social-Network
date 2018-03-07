function searchUser(hint) {
	var xmlhttp = new XMLHttpRequest();
	if (hint.trim() == '') {
		document.getElementById("hints").innerHTML = '';
		return;
	}
	var obj = {"hint":hint};	
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var users = JSON.parse(this.responseText);
			var txt = '';
			for (user in users) {
				txt += '<a class="dropdown-item" href="profile.php?id='+users[user].id+'">';
				txt += '<img width="32" height="32" src="'+users[user].avatar+'">';
				txt += '<span class="ml-2">'+users[user].username+'</span>';
				txt += '</a>';
			}
			document.getElementById("hints").innerHTML = txt;
		}
	};
	xmlhttp.open("POST", "php/searchUser.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);
}