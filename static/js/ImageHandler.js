
var ImageHandler = (function () {

	var		data,
			image = null,
			filter = null,
			saved_images = null,
			csrf = null;

	function retrieveToken(func, data) {
		var token_req = new XMLHttpRequest();

			token_req.open('post', 'http://localhost/camagru/call/call_token.php', true);
			token_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			token_req.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					csrf.value = this.responseText;
					func(data);
				}
			};
			token_req.send('send=ok&token_name=csrf_lab');
	}

	function delRequest(array) {
		var req = new XMLHttpRequest();

		req.open('POST', 'http://localhost/camagru/call/call_image2', true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				getImages();
				// console.log(this.responseText);
			}
		}
		req.send('images=delete&id=' + array.join('.') + '&csrf_token=' + csrf.value);
	}

	function getImages() {
		var req = new XMLHttpRequest(),
			img, label, ret, check, del_button,
			flag = false;

			req.open('POST', 'http://localhost/camagru/call/call_image2', true);
			req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			req.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					if (this.responseText.substring(0, 6) == 'Image:') {
						if (saved_images != null) {
							cleanImages();
							ret = this.responseText.split(';');
							for (id in ret) {
								flag = true
								label = document.createElement('label');
								check = document.createElement('input');
								check.type = 'checkbox';

								img = document.createElement('img');
								img.src = 'data:image/png;base64,' + ret[id].substr(7);
								img.width = 320;
								img.height = 240;
								saved_images.appendChild(img);

								label.appendChild(check);
								label.appendChild(img);
								label.className = 'checkbox_label';

								saved_images.appendChild(label);
							}
							if (flag) {
								del_button = document.createElement('button');
								del_button.innerHTML = 'Delete selected images';
								del_button.addEventListener('click', function () {
									var labels = document.querySelectorAll('.checkbox_label'),
										i = 0,
										array = [];

									for ( ; i < labels.length; i++) {
										if (labels[i].children[0].checked) {
											array.push(i);
										}
									}
									retrieveToken(delRequest, array);
									// delRequest(array);
								});
								saved_images.appendChild(del_button);
							}
						}
					} else
						cleanImages();
					// console.log(this.responseText);
				}
			};
			req.send('images=get&csrf_token=' + csrf.value);
	}

	function cleanImages() {
		if (saved_images != null) {
			while (saved_images.firstChild)
				saved_images.removeChild(saved_images.firstChild);
		}
	}

	document.addEventListener("DOMContentLoaded", function () {
		image = document.querySelector('#image');
		filter = document.querySelector('#camera_filter');
		saved_images = document.querySelector('#saved_images');
		csrf = document.querySelector('input[name="csrf_token"]');
		getImages();
	});

	return {
		saveImage: function (url) {
			retrieveToken(function (url) {
				var req = new XMLHttpRequest();

				req.open('post', url, true);
				req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				req.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						if (this.responseText.substring(0, 6) == 'Error:')
							ErrorHandler.printError(this.responseText.substring(7));
						else
							getImages();
					}
				};

				data = ['save=ok'];
				if (image != null && filter != null) {
					data.push('image=' + encodeURIComponent(image.src));
					data.push('filter=' + encodeURIComponent(filter.toDataURL('image/png')));
					data.push('csrf_token=' + encodeURIComponent(csrf.value));

					req.send(data.join('&'));
				} else {
					console.log('DOM not loaded.');
				}
			}, url);
		},
	};

}) ();
