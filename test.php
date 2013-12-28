<html>
	<head>    
		<title>Test</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<!-- Bootstrap -->
    	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
    	<style type="text/css">
    	
	    	body {
			  padding-top: 60px;
			  padding-bottom: 40px;
			}

    		.typeahead,
			.tt-query,
			.tt-hint {
			  height: 50px;
			  padding: 8px 12px;
			  font-size: 24px;
			  line-height: 30px;
			  border: 2px solid #ccc;
			  -webkit-border-radius: 8px;
			     -moz-border-radius: 8px;
			          border-radius: 8px;
			  outline: none;
			}
			
			.typeahead {
			  background-color: #F0F0F0;
			}
			
			.typeahead:focus {
			  border: 2px solid #0097cf;
			}
			
			.tt-query {
			  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
			     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
			          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
			}
			
			.tt-hint {
			  color: #fff;
			}
			
			.tt-dropdown-menu {
			  width: 100%;
			  margin-top: 12px;
			  padding: 8px 0;
			  background-color: #fff;
			  border: 1px solid #ccc;
			  border: 1px solid rgba(0, 0, 0, 0.2);
			  -webkit-border-radius: 8px;
			     -moz-border-radius: 8px;
			          border-radius: 8px;
			  -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
			     -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
			          box-shadow: 0 5px 10px rgba(0,0,0,.2);
			}
			
			.tt-suggestion {
			  padding: 3px 20px;
			  font-size: 18px;
			  line-height: 24px;
			}
			
			.tt-suggestion.tt-is-under-cursor {
			  color: #F0F0F0;
			  background-color: #004A61;
			  border: 2px solid black;
			}
			
			.tt-suggestion p {
			  margin: 0;
			}
			
			/* Turn off hints which overlays*/
			.tt-hint {
			    font-size: 0 !important;
			    visibility:hidden;
			}
    	</style>

	    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	    <![endif]-->
    
    	<script src="assets/js/jquery-1.10.2.min.js" type="text/javascript"></script>
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
                    console.log(word);
                    console.log(obj);
                    console.log(datum);
				});
			});
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
					<a class="navbar-brand" href="./">CoursePicker</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li class="active"><a href="./">Home</a></li>
						<li><a href="#aboutModal" data-toggle="modal" id="about">About</a></li>
						<li><a href="#howtoModal" data-toggle="modal" id="howto">How To</a></li>
					</ul>				  
				</div><!-- /.nav-collapse -->
			</div><!-- /.container -->
		</div><!-- /.navbar -->

		<div class="container">
			<input id="wordInput" name="wordInput" class="form-control" type="text" placeholder="E.g. crunk">
		</div>
		
		

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
	</body>
</html>
