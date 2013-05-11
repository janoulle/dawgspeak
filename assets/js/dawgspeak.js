$(document).ready(function(){
	var obj, value;
	var typeahead = $('#wordInput');
	//When a change is detected in the typeahead box
	typeahead.change(function(){
		//parse value
		$('html, body').css("cursor", "wait");
		$('#wordExplanation').empty();
		value = typeahead.val();
		//Only make ajax call if the typed word is in the list of words
		if (words.indexOf(value) > -1){
			$('#messages').empty();
			found = true;
			$.ajax({
				type: "POST",
				url: 'includes/controllers/route.php',
				data: {word: value},
				success: function(response){
					$('html, body').css("cursor", "auto");
					if (response == -1){
						$('#messages').removeClass("hidden");
					}else{
						$('#wordExplanation').attr("style","");
						obj = jQuery.parseJSON(response);
						Object.keys(obj).forEach(function(key){
							$('#wordExplanation').removeClass("hidden");
							$('#wordExplanation').append(obj[key].definition+"<br>").animate({
								width: "90%",
								color: "#cfcfcf",
								marginLeft: "0.6in",
								lineHeight: "0.8em",
								fontSize: "3em",
								borderWidth: "10px"
							  }, { duration: 800, queue: false } );
						});
					}
				}
			});//end ajax
			//Get google image
			$('html, body').css("cursor", "wait");
			$.ajax({
				type: "POST",
				url: 'includes/controllers/route.php',
				data: { gword: value},
				success: function(response){
					$('html, body').css("cursor", "auto");
					//console.log(response);
					$('#wordExplanation').append(response);
				}
			});
		}else{
			$('#messages').append("Please choose a word in the dictionary.");
		}
	});
});
