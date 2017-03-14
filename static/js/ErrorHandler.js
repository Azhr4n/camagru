
var ErrorHandler = (function () {

	var		error_field = null;

	document.addEventListener("DOMContentLoaded", function () {
		error_field = document.querySelector('#error_field');
	});

	return {
		printError: function (message) {
			if (error_field != null)
				error_field.innerHTML = message;
		}
	};

}) ();
