function publishPost(id) {
	var xmlhttp = new XMLHttpRequest();
	var post = document.getElementById("post").value;
	document.getElementById("post").value = '';
	if (post.trim() == '') return;
	var obj = {"id":id,"post":post};
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			fetchPosts(obj.id, obj.id);
		}
	};
	xmlhttp.open("POST", "php/insertPost.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);
}

function fetchPosts(visitors_id, owners_id) {
	var xmlhttp = new XMLHttpRequest();
	var obj = {"owners_id":owners_id,"visitors_id":visitors_id};
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var posts = JSON.parse(this.responseText);
			var txt = '';
			for (post in posts) {
				txt += '<div class = "row mb-2">';
				txt += '<div class = "border border-success rounded bg-light col-12 py-1">';
				txt += '<div class="d-flex justify-content-between">';
				txt += '<div class="wrap">'+posts[post].post+'</div>';
				if (visitors_id === owners_id) {
					txt += '<button onclick="deletePost('+posts[post].post_id+','+posts[post].user_id+')" class="btn btn-dark align-self-start ml-2">X</button>';
				}
				txt += '</div>';
				txt += '<div class="d-flex justify-content-end mt-2">';
				if (posts[post].reaction == null) {
					txt += '<button onclick="updateReactionCount('+posts[post].post_id+','+visitors_id+','+posts[post].user_id+',1)" class="mr-2 btn btn-outline-primary">Likes: '+posts[post].likes+'</button>';
					txt += '<button onclick="updateReactionCount('+posts[post].post_id+','+visitors_id+','+posts[post].user_id+',-1)" class="btn btn-outline-danger">Dislikes: '+posts[post].dislikes+'</button>';
				} else {
					if (posts[post].reaction == 1) {
						txt += '<button onclick="updateReactionCount('+posts[post].post_id+','+visitors_id+','+posts[post].user_id+',1)" class="mr-2 btn btn-primary">Likes: '+posts[post].likes+'</button>';
						txt += '<button onclick="updateReactionCount('+posts[post].post_id+','+visitors_id+','+posts[post].user_id+',-1)" class="btn btn-outline-danger">Dislikes: '+posts[post].dislikes+'</button>';
					} else {
						txt += '<button onclick="updateReactionCount('+posts[post].post_id+','+visitors_id+','+posts[post].user_id+',1)" class="mr-2 btn btn-outline-primary">Likes: '+posts[post].likes+'</button>';
						txt += '<button onclick="updateReactionCount('+posts[post].post_id+','+visitors_id+','+posts[post].user_id+',-1)" class="btn btn-danger">Dislikes: '+posts[post].dislikes+'</button>';
					}
				}
				txt += '</div>';
				txt += '</div>';
				txt += '</div>';
			}
			var postArea = document.getElementById("postArea");
			if (postArea != null) postArea.innerHTML = txt;
		}
	};
	xmlhttp.open("POST", "php/fetchPosts.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);
}

function deletePost(post_id, user_id) {
	var xmlhttp = new XMLHttpRequest();
	var obj = {"id":post_id};
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			fetchPosts(user_id, user_id);
		}
	};
	xmlhttp.open("POST", "php/deletePost.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);
}

function updateReactionCount(post_id, visitors_id, owners_id, reaction) {
	var xmlhttp = new XMLHttpRequest();
	var obj = {"post_id":post_id,"user_id":visitors_id,"reaction":reaction};
	var json = JSON.stringify(obj);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			fetchPosts(visitors_id, owners_id)
		}
	};
	xmlhttp.open("POST", "php/updateReactions.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x="+json);	
}