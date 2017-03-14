
(function () {
	var	image = document.querySelector('#image'),
		csrf = document.querySelector('input[name=csrf_token]');

	function retrieveToken(token_name, func, url, data) {
		var token_req = new XMLHttpRequest();

			token_req.open('post', 'http://localhost/camagru/call/call_token.php', true);
			token_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			token_req.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					csrf.value = this.responseText;
					data.push('csrf_token=' + csrf.value);
					func(url, data);
				}
			};
			token_req.send('send=ok&token_name=' + token_name);
	}

	function submit(url, data) {
		let req = new XMLHttpRequest();

		req.open('post', url, true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				if (this.responseText.substring(0, 6) == 'Error:')
					console.log(this.responseText);
				else
					showComments();
				// console.log(this.responseText);
			}
		};
		req.send(data.join('&'));
	};

	function setReply() {
		var replies = document.querySelectorAll('.replyLink');

		for (let i = 0 ; i < replies.length; i++) {
			replies[i].onclick = function () {
				let parent = this.parentNode,
					input = document.createElement('input'),
					ucname = parent.firstElementChild.querySelector('.ucname'),
					ucomment = parent.firstElementChild.querySelector('.ucomment');

				if (parent.lastChild.tagName != 'INPUT') {
					input.type = 'text';
					input.className = 'comment';
					input.onkeydown = function (event) {
						if (event.key == 'Enter') {
							let data = [
								'send=ok',
								'image_name=' + image.alt,
								'value=' + this.value,
								'target=' + ucname.innerHTML + ':' + ucomment.innerHTML,
							];

							this.value = '';
							retrieveToken('csrf_comment', submit, 'http://localhost/camagru/call/call_comment.php', data);
						}
					}
					parent.appendChild(input);
				}
				return false;
			};
		}
	}

	function setDelete() {
		var deletes = document.querySelectorAll('.deleteLink');

		for (let i = 0 ; i < deletes.length; i++) {
			deletes[i].onclick = function () {
				let parent = this.parentNode,
				ucname = parent.firstElementChild.querySelector('.ucname'),
				ucomment = parent.firstElementChild.querySelector('.ucomment'),
				data = [
					'delete=ok',
					'image_name=' + image.alt,
					'target=' + ucname.innerHTML + ':' + ucomment.innerHTML,
				];

				retrieveToken('csrf_comment', submit, 'http://localhost/camagru/call/call_comment.php', data);
				return false;
			};
		}
	}

	function setLikes() {
		var likes = document.querySelectorAll('.likeLink');

		for (let i = 0 ; i < likes.length; i++) {
			likes[i].onclick = function () {
				let parent = this.parentNode,
					ucname = parent.firstElementChild.querySelector('.ucname'),
					ucomment = parent.firstElementChild.querySelector('.ucomment'),
					data = [
						'like=ok',
						'image_name=' + image.alt,
					];
					if (parent.parentNode.className != 'bcomment')
						data.push('target=' + image.alt);
					else
						data.push('target=' + ucname.innerHTML + ':' + ucomment.innerHTML);
					// console.log(data);
					retrieveToken('csrf_like', submit, 'http://localhost/camagru/call/call_like.php', data);
				return false;
			};
		}
	}

	function setImageInput() {
		icinput = document.querySelector('.icinput');

		icinput.addEventListener('keydown', function (event) {
			if (event.key == 'Enter') {
				var cblock = document.querySelector('.comments'),
					data = [
					'send=ok',
					'image_name=' + image.alt,
					'value=' + this.value,
					'target=' + image.alt,
				];

				icinput.value = '';
				retrieveToken('csrf_comment', submit, 'http://localhost/camagru/call/call_comment.php', data);
			}
		});
	}

	function showComments() {
		var req = new XMLHttpRequest(),
			image = document.querySelector('#image'),
			comments = document.querySelector('.comments');

		req.open('get', 'http://localhost/camagru/call/call_comment.php?image_name=' + image.alt, true);
		req.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				if (this.responseText.substring(0, 6) == 'Error:')
					console.log(this.responseText);
				else if (this.responseText.substring(0, 8) == 'Comment:')
				{
					let data = this.responseText.substring(9);

					comments.innerHTML = data;
					setImageInput();
					setLikes();
					setReply();
					setDelete();
				}
				// console.log(this.responseText);
			}
		};
		req.send();
	};
	
	if (image)
		showComments();

}) ();
