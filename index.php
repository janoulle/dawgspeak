<?php
?>
<!DOCTYPE html>
<html lang="en">
  	<head>
	    <meta charset="utf-8">
	    <title>UGA Slang</title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="description" content="">
	    <meta name="author" content="">
	   	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	   	<link href="assets/css/dawgspeak.css" rel="stylesheet">
	    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	    <![endif]-->
    
    	<script src="assets/js/jquery-1.10.2.min.js" type="text/javascript"></script>
    	<script src="assets/js/jquery.highlight.js" type="text/javascript"></script>
    	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    	<script src="assets/js/typeahead.min.js" type="text/javascript"></script>
    	<script src="assets/js/hogan.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(function(){
				$('#wordInput').typeahead({
					name: 'slanguage',
					limit: 10,
					prefetch: 'assets/json/results.json',                                     
					template: [                                                                 
						'<p class="tt-word">{{word}} ({{type}})</p>'                    
					].join(''),                                                                 
					engine: Hogan                
				})
				.on('typeahead:selected',function(obj,datum){
					var word  = datum.value;
                    $('#wordExplanation')
                    	.html("")
                    	.removeClass("hidden")
                    	.append(datum.definition)
						.show()						
                    	.animate({
							width: "90%",
							color: "#cfcfcf",
							marginLeft: "0.6in",
							lineHeight: "0.9em",
							fontSize: "3em",
							borderWidth: "10px"
						}, { duration: 800, queue: false });
						//highlight the word in the definition
						$('#wordExplanation').highlight(word);
						urbanDefinition(word);
						wordnikDefinition(word);
						getTweets(word);
				});
			});
			
			/**
			 * get the first explanation from urban dictionary
			 */
			function urbanDefinition(word){
				$.getJSON('http://api.urbandictionary.com/v0/define?term=' + word, function(data){
					//console.log(data.list[0]);
					//console.log(data.tags);
					
					var msg2 = "<h4>Top Urban Dictionary Definition</h4>";
					msg2 += "<p>" + data.list[0].definition + "</p>";
						
					$('#urbanDictionary-definition')
						.html("")
						.removeClass("hidden")
						.addClass("urbandictionary")
						.append(msg2);
				})
				.done(function(json){
					console.log("Success!");
				})
				.fail(function(jqxhr, textStatus, error ){
					console.log("Error getting Urban Dictionary results: " + textStatus);
				});				
			}
			
			/**
			 * Get the top example from wordnik
			 */
			function wordnikDefinition(word){
				$.ajax({
                	type: "POST",
                    url: 'classes/controllers/wordcontroller.php',
                    data: { action : "useWordnik", word : word},
                    dataType: "json"
                })
                .done(function(msg){
                    $('body').css('cursor', 'auto');
                    //console.log(msg);
                    if (msg.errorMessage.length == 0){
                    	if (msg.word !== null){
	                    	var msgs = "<h4>Top Wordnik Example</h4>";
							msgs += "<p>" + msg.word + "</p>";
	                    	$('#wordNik-example')
	                    		.html("")
	                    		.removeClass("hidden")
	                    		.addClass("wordnik")
	                    		.append(msgs)
	                    		.show();
                    	}
                    }else{
                    	var msgs = "<h4>Top Wordnik Example</h4>";
						msgs += "<p>" + msg.errorMessage + "</p>";
                    	$('#wordNik-example')
                    		.html("")
                    		.removeClass("hidden")
                    		.addClass("wordnik")
                    		.append(msgs)
                    		.show();
                    }
                })
                .fail(function(msg){
                    $('body').css('cursor', 'auto');
                    var msgs = "<h4>Top Wordnik Example</h4>";
					msgs += "<p>" + msg.responseText + "</p>";
                    $('#wordNik-example')
                    	.html("")                    	
                   		.removeClass("hidden")
                    	.append(msgs)
						.addClass("wordnik")
                    	.show();
                    setTimeout(function(){ 
                    	$('#wordNik-example').hide("slow",function(){}); 
                    }, 4000);
                   console.log(msg.responseText);
                });
			}
			
			function getTweets(word){
				//https://api.twitter.com/1.1/search/tweets.json?q=%23freebandnames
				//get the access token
				$.getJSON( "includes/getToken.php", { action : "getTweets", word : word }
				)
				.done(function(json) {
					//console.log(json);
					if (json.errorMessage){
						//console.log("Fail");
						$('#twitterExamples')
	                    	.html("")                    	
	                   		.removeClass("hidden")
							.addClass("twitterExamples")
	                    	.append(json.errorMessage)
	                    	.show();						
					}else{
						//console.log("Success");
						var msg = "<h4>Twitter Example</h4>";
						msg += "<ol>";
						var statuses = json.statuses;
						var l = statuses.length >= 5 ? 5:statuses.length;
						for (var i = 0; i < l; i++){
							msg += "<li><em>" + statuses[i].text + "</em></li>";
						}
						msg += "</ol>";
						$('#twitterExamples')
	                    	.html("")                    	
	                   		.removeClass("hidden")
							.addClass("twitterExamples")
	                    	.append(msg)
	                    	.show();
					}
				})
				.fail(function(jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error;
				    console.log( "Request Failed: " + err );
				});
			}
		</script>
  	</head>
	
	<body>
		<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="./">DawgSpeak</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li class="active"><a href="./">Home</a></li>
						<li><a href="#aboutModal" data-toggle="modal" id="about">About</a></li>
						<li><a href="#contactModal" data-toggle="modal" id="contact">Contact</a></li>
					</ul>				  
				</div><!-- /.nav-collapse -->
			</div><!-- /.container -->
		</div><!-- /.navbar -->


	    <div class="container">
			<div class="row">
				<ul class="nav nav-pills">
					<li class="active"><a href="#searchVocab" data-toggle="tab">Search Vocab</a></li>
					<li><a href="#browseVocab"  data-toggle="tab">Browse Vocab</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="searchVocab">
						<h1>Enter word:</h1>
						<input id="wordInput" name="wordInput" placeholder="E.g. crunk" >
						<span id="messages" class="hidden"></span>
						<div id="wordExplanation" class="span4 hidden">
							Here
						</div>
						<div id="wordNik-example" class="hidden">
							<hr/>
							Here
						</div>
						<div id="urbanDictionary-definition" class="hidden">
							<hr/>
							Here
						</div>
						<div id="twitterExamples" class="hidden">
							<hr/>
							Here
						</div>
					</div>
					<div class="tab-pane" id="browseVocab">
						<div data-target=".navbar">
	
						</div>
						<div data-spy="scroll" data-target=".navbar">
	
						</div>
					</div>
				</div>
			</div> <!-- row-->
	    </div> <!-- /container -->


        <!-- About Modal -->
        <div class="modal fade" id="aboutModal" tabindex="-1" role="dialog" aria-labelledby="aboutModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="aboutModalLabel">About Course Picker!</h4>
                                        </div>
                                        <div class="modal-body">
                                                <p>
                                                       All content comes from <a href="http://www.english.uga.edu/def/">http://www.english.uga.edu/def/</a>. Website thrown together by <a href="http://janeullah.com" title="Jane Ullah">Jane Ullah</a> and powered by Twitter Bootstrap, PHP, jQuery and javaScript.
                                                </p>
                                        </div>
                                </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->	


		<!-- Contact Modal -->
        <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="contactModalLabel">About Course Picker!</h4>
                                        </div>
                                        <div class="modal-body">
                                                <p>
                                                	Site by: <a href="http://janeullah.com" title="Jane Ullah">Jane Ullah</a><br>
													Twitter: <a href="https://twitter.com/janetalkstech" title="@janetalkstech on Twitter">@janetalkstech</a> 
												</p>
                                        </div>
                                </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->	
        
		<?php include 'includes/analyticstracking.inc'; ?>
  	</body>
</html>
