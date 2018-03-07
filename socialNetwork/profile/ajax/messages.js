function fetchMessages(id_from, id_to) {
	var xmlhttp = new XMLHttpRequest();
	var obj = {"id_from":id_from,"id_to":id_to};	
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var messages = JSON.parse(this.responseText);
			var txt = '';
			for (msg in messages) {
				if (messages[msg].id_from == id_from) {
					txt += '<h6 class="w-75 border border-primary bg-primary  text-white rounded ml-auto mb-2 p-2">'+messages[msg].message+'</h6>';
				} else {
					txt += '<h6 class="w-75 border border-secondary bg-light rounded mr-auto mb-2 p-2">'+messages[msg].message+'</h6>';
				}
			}
			document.getElementById("m"+id_to).innerHTML = txt;
		}
	};
	xmlhttp.open("POST", "php/fetchMessages.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);	
}

function sendMessage(id_from, id_to) {
	var xmlhttp = new XMLHttpRequest();
	var msg = document.getElementById(id_to).value;
	if (msg.trim() == '') {
		document.getElementById(id_to).value = '';
		return;
	}
	document.getElementById(id_to).value = ''
	var obj = {"id_from":id_from,"id_to":id_to,"msg":msg};	
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			fetchMessages(id_from, id_to);
		}
	};
	xmlhttp.open("POST", "php/insertMessage.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);
}