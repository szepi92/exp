<?php
	require_once 'includes/env.php';
	require_once 'endpoints/session-redirect.php';	// this find the $quiz_session, $user, and $quiz
?>
	<script>var QUIZ_SESSION_ID = "<?=$quiz_session->id()?>";</script>
	<?php
		$cur_language = $quiz_session->currentLanguage();
		$num_questions  = $quiz->questionsPerLang();
	?>

<div class="instruction-box">
	<div id="center-title" ><h3>Congratulations!</h4> </div>
	<h4>You've just completed <?=$num_questions?> images in '<?=$quiz->language($cur_language-1)?>'!</h4>
</div>

<div class="instruction-box">
	<p>
		<h4>Get ready! Respond to the following <?=$num_questions?> images in '<?=$quiz->language($cur_language)?>'.</h4>
	</p>
	<a id="begin" class="btn" onclick="startNextLanguage(<?= $cur_language ?>); return false"> Continue </a>
</div>