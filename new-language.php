<?php
	require_once 'includes/env.php';
	require_once 'endpoints/session-redirect.php';	// this find the $quiz_session, $user, and $quiz
?>
	<script>var QUIZ_SESSION_ID = "<?=$quiz_session->id()?>";</script>
	<?php
		$firstLanguage  = $quiz->language(0);
		$secondLanguage = $quiz->language(1);
		$num_questions  = $quiz->questionsPerLang();
	?>

<div class="instruction-box">
	<div id="center-title" ><h3>Congratulations, you are halfway done!</h4> </div>
	<h4>You've just completed <?=$num_questions?> images in '<?=$firstLanguage?>'!</h4>
</div>

<div class="instruction-box">
	<p>
		<h4>Get ready! Respond to the following <?=$num_questions?> images in '<?=$secondLanguage?>'.</h4>
	</p>
	<a id="begin" class="btn" onclick="startNextLanguage(1); return false"> Continue </a>
</div>