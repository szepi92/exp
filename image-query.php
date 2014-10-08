<?php
	require_once 'includes/env.php';
	require_once 'endpoints/session-redirect.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Response App - by R&eacute;ka Szepesv&aacute;ri</title>

    <!-- CSS Files -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open Sans">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script>
		var QUIZ_SESSION_ID = "<?=$quiz_session->id()?>";
		var QUIZ_CURRENT_LANGUAGE = <?=$quiz_session->currentLanguage()?>;	// integer current language,question
		var QUIZ_CURRENT_QUESTION = <?=$quiz_session->currentQuestion()?>;
	</script>
	<script src="js/request.js"></script>
	<script src="js/recording.js"></script>
	<script>
		// Interface with recording.js and request.js
		var enable_continue = false;
		
		$(document).ready(function(){
			
			// When a voice is heard, remember the reaction time
			window.voiceHeard = function() {
				var LAG_TIME = 600;	// There's 600ms of computer lag (I'm guessing)
				if (!window.reactionTime) {
					window.reactionTime = Math.max(Date.now() - window.recordingStarted - LAG_TIME, 100);
					console.log("Reaction Time: ", window.reactionTime);
					$("#reaction-time").text(window.reactionTime);
					$("#reaction-label").show();
					$("#current-status").html("<b>Recording...</b>");
				}
			}
			
			// When the user is done speaking.
			window.voiceDone = function() {
				if (!window.resultRecorded) {
					window.resultRecorded = true;
					recordResult(window.reactionTime, savedCallback);
					$("#current-status").html("<b>Saving...</b> ");
				}
			}
			
			// When the result is successfully saved to the back-end
			function savedCallback() {
				$("#current-status").html("<b>Result recorded. Press spacebar for next image.</b> ");
				enable_continue = true;
			}
		
			initMedia();
		});
	
		// Space bar press to continue
		$(document).keypress(function(event){
			var SPACE_KEY = 32;
			if (event.which == SPACE_KEY && enable_continue) {
				window.location.href = REDIRECT_URL;
			}
		});
	</script>
	
  </head>
  <body>
	<?php
		$lang = $quiz_session->currentLanguage();
		$question = $quiz_session->currentQuestion();
		$image_url = $quiz->image($lang, $question);
		
		$total_questions = $quiz->questionsPerLang();
		$language = $quiz->language($lang);
	?>
	<div class="img-query">
		<img id="experiment-img" src="<?= Image::DEFAULT_DIRECTORY . "/$image_url"?>">
		<div class="picture-label"> <b>Image:</b> <?=$question+1?>/<?=$total_questions?> </div>
		<div class="picture-label"> <b>Language:</b> <?= (string)$language?> </div>
		<div id="reaction-label" style="display:none" class="picture-label"> <b>Reaction Time: </b> <span id="reaction-time">0</span> ms</div>
		<div id="current-status" class="picture-label"> <b>Speak!</b> </div>
	</div>
	
	
    <!-- Library javascript files -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/lib/underscore-min.js"></script>
	<script src="js/lib/backbone-min.js"></script>
  </body>
</html>