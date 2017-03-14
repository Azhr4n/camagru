function setSize(object, width, height) {
	if (Array.isArray(object)) {
		for (id in object) {
			object[id].style.width = width + 'px';
			object[id].style.height = height + 'px';
		}
	} else {
		object.style.width = width + 'px';
		object.style.height = height + 'px';
	}
};

(function () {

	var	streaming = false,
		video = document.querySelector('#camera'),
		canvas = document.querySelector('#camera_filter'),
		buffer = document.createElement('canvas'),
		cbutton = document.querySelector('#camera_button'),
		image = document.querySelector('#image'),
		save = document.querySelector('#save'),
		imageRect = image.getBoundingClientRect(),
		width = 640,
		height = 480;

	buffer.style.display = 'none';
	canvas.style.left = imageRect.left + 'px';
	setSize([buffer, canvas, image, video], width, height);

	navigator.getMedia = (navigator.getUserMedia ||
		navigator.webkitGetUserMedia ||
		navigator.mozGetUserMedia ||
		navigator.msGetUserMedia);

	navigator.getMedia({
			video: true,
			audio: false
		},
		function(stream) {
			if (navigator.mozGetUserMedia) {
				video.mozSrcObject = stream;
			} else {
				var vendorURL = window.URL || window.webkitURL;
				video.src = vendorURL.createObjectURL(stream);
			}
			video.play();
		},
		function(err) {
			console.log("An error occured ! " + err);
		}
	);

	video.addEventListener('canplay', function (ev) {
		if (!streaming) {
			cbutton.disabled = false;
			streaming = true;
		}
	}, false);

	document.querySelector('#import_button').onchange = function (ev)
	{
		var files = this.files;

		if (FileReader && files && files.length)
		{
			var reader = new FileReader();
			reader.onload = function () {
				image.setAttribute('src',  this.result);
				if (save.disabled)
					save.disabled = false;
			};
			reader.readAsDataURL(this.files[0]);
		}
	};

	cbutton.onclick = function () {
		var tmp;
		
		if (save.disabled)
			save.disabled = false;
		buffer.getContext('2d').drawImage(video, 0, 0, buffer.width, buffer.height);
		tmp = buffer.toDataURL('image/png');
		image.src = tmp;
	};

}) ();

(function () {

	var	canvas = document.querySelector('#camera_filter'),
		context = canvas.getContext('2d'),
		filters = document.querySelectorAll('input[name=filter]'),
		value = 'none',
		i = 0;

	for ( ; i < filters.length; i++)
	{
		filters[i].onclick = function ()
		{
			if (value != this.value)
			{
				context.clearRect(0, 0, canvas.width, canvas.height);
				value = this.value;
				if (value != 'none') {
					context.drawImage(this.nextElementSibling, 0, 0, canvas.width, canvas.height);
				}
			}
		};
	}

}) ();
