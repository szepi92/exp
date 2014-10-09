<?php
	require_once 'includes/env.php';
	require_once 'endpoints/session-redirect.php';	// this find the $quiz_session, $user, and $quiz
?>
<script>var QUIZ_SESSION_ID = "<?=$quiz_session->id()?>";</script>
<? $name = $user->FirstName(); ?>
<div id="result-title">
	<h1>Thanks, <?=$name?>!</h1>
</div>

<div class="results" style="height:auto;">
	<p>
	The experiment is now complete. Your responses are currently being collected and your results will be emailed to you in the next few days.
	</p>
	
	<p>
	You may now close the browser or page. Or, <a href="<?=$ABS_ROOT?>">click here</a> to return home.
	</p>
	
	<p>
	Yours Truly, <br />
	R&eacute;ka Szepesv&aacute;ri
	</p>
</div>