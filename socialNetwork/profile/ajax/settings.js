function updateAvatar(id, src) {
	var xmlhttp = new XMLHttpRequest();
	var pattern = /^avatar(\/)av(0|1)([0-9])(.)(jpg|png)$/g;
	var bool = pattern.test(src);
	if (!bool) return;
	var obj = {"id":id,"avatar":src};	
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {			
			document.getElementById("avatar").src = src;
		}
	};
	xmlhttp.open("POST", "php/updateAvatar.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);
}