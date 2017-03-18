$(document).ready(function () {
	$("#navbar img").each(function() {
		var oldUrl = $(this).attr("src");
		var newUrl = $(this).attr("id");
		
		var rolloverImage = new Image();
		rolloverImage.src = newUrl;
		
		$(this).hover(
		function() {
			$(this).attr("src", newUrl);
			},
		function() {
			$(this).attr("src", oldUrl);
			}
		);
	});

});