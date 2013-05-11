<?php
require_once("../../creds/flickr.inc");
require_once("./includes/phpflickr/phpFlickr.php");
$asseturl = "assets";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>UGA Slang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<?php require_once("includes/resources.inc"); ?>
    <script type="text/javascript">
		var result;
	</script>
    <style>
		body {
			padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
		}
    </style>
	<script type="text/javascript">
	/* Charles Lawrence - Feb 16, 2012. Free to use and modify.
	 * Please attribute back to @geuis if you find this useful.
	 *  Twitter Bootstrap Typeahead doesn't support remote data querying.
	 *  This is an expected feature in the future. In the meantime,
	 *  others have submitted patches to the core bootstrap component that allow it.
	 *  The following will allow remote autocompletes *without* modifying any officially
	 *  released core code. If others find ways to improve this, please share.*/
		$(document).ready(function(){
			var autocomplete = $('#wordInput').typeahead()
			.on('keyup', function(ev){

				ev.stopImmediatePropagation();
				ev.stopPropagation();
				ev.preventDefault();
				//filter out up/down, tab, enter, and escape keys
				//40,38,9,13,27
				if( $.inArray(ev.keyCode,[40,38,9,13,27]) === -1 ){

					var self = $(this);

					//set typeahead source to empty
					self.data('typeahead').source = [];

					//active used so we aren't triggering duplicate keyup events
					if( !self.data('active') && self.val().length > 0){

						self.data('active', true);

						//Do data request. Insert your own API logic here.
						$.getJSON("assets/json/words.json", function(data) {
							//set this to true when your callback executes
							self.data('active',true);

							//Filter out your own parameters. Populate them into an array, since this is what typeahead's source requires
							var arr = [], i=data.length;
							while(i--){
								arr[i] = data[i];
							}

							//set your results into the typehead's source
							self.data('typeahead').source = arr;

							//trigger keyup on the typeahead to make it search
							self.trigger('keyup');

							//All done, set to false to prepare for the next remote query.
							self.data('active', false);

						});

					}
				}
			});
		});
	</script>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="http://apps.janeullah.com/dawgspeak/" title="Dawg Dictionary">Dawg Dictionary</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="http://apps.janeullah.com/dawgspeak/" title="Dawg Dictionary">Home</a></li>
              <li><a href="#aboutModal" data-toggle="modal">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
		<h1>Enter word:</h1>
		<input id="wordInput" data-provide="typeahead" class="typeahead" type="text" placeholder="E.g. crunk" autocomplete="off" spellcheck="false" dir="auto">
		<span id="messages" class="hidden"></span>
		<div class="row">
			<div id="wordExplanation" class="span4 hidden">
				Here
			</div>
			<div id="wordImage" class="span4 hidden">
				Here
			</div>
		</div> <!-- row-->
    </div> <!-- /container -->

	<!-- About Image Modal -->
	<div class="modal hide fade" id="aboutModal" tabindex="-1" role="dialog" aria-labelledby="aboutModalLabel" aria-hidden="true">
		<div class="modal-header">
			<h3 id="aboutModalLabel">About</h3>
		</div>
		<div class="modal-body">
			All content comes from <a href="http://www.english.uga.edu/def/">http://www.english.uga.edu/def/</a>. Website thrown together by Jane Ullah and powered by Twitter Bootstrap, PHP, jQuery and javaScript.
		</div>
		<div class="modal-footer">
			<a class="btn" data-dismiss="modal" aria-hidden="true">Close</a>
		</div>
	</div>
  </body>
</html>
