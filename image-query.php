<?php
	require_once 'includes/env.php';
	require_once 'endpoints/session-redirect.php';
?>

<!-- The functionality for this page -->
<script>
var QUIZ_CURRENT_LANGUAGE = <?=$quiz_session->currentLanguage()?>;	// integer current language,question
var QUIZ_CURRENT_QUESTION = <?=$quiz_session->currentQuestion()?>;

// Reset variables (used in recording.js and request.js)
window.enable_continue = false;
window.reactionTime = undefined;
window.resultRecorded = undefined;
window.recordingStarted = Date.now();

// When a voice is heard, remember the reaction time
window.voiceHeard = function() {
	if (!window.reactionTime && !!window.recordingStarted) {
		var LAG_TIME = 300;	// HACK: There's 600ms of computer lag (I'm guessing)
		window.reactionTime = Math.max(Date.now() - window.recordingStarted - LAG_TIME, 2);
		$("#reaction-time").text(window.reactionTime);
		$("#reaction-label").show();
		$("#current-status").html("<b>Recording...</b>");
	}
}

// When the user is done speaking.
window.voiceDone = function() {
	if (!window.resultRecorded && window.reactionTime) {
		window.resultRecorded = true;
		recordResult(window.reactionTime, function (data){
			$("#current-status").html("<b>Result recorded. Press spacebar for next image.</b> ");
			window.enable_continue = true;
		});
		$("#current-status").html("<b>Saving...</b> ");
	}
}

// Override the key-handler
window.keyHandler = function(event) {
	var SPACE_KEY = 32;
	if (event.which == SPACE_KEY && window.enable_continue) {
		customRedirect(REDIRECT_URL);
	}
}
</script>

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