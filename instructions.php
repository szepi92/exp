<?php
	require_once 'includes/env.php';
	require_once 'endpoints/session-redirect.php';	// this find the $quiz_session, $user, and $quiz
?>
<script>var QUIZ_SESSION_ID = "<?=$quiz_session->id()?>";</script>
<?php
	$num_questions = $quiz->questionsPerLang();
?>

<div class="instruction-box sub-title">
	<h3>Please read the following instructions carefully</h3>
</div>

<div class="instruction-box">
	<p>
		This experiment measures how fast you think in <?= $quiz->language(0) ?> and <?= $quiz->language(1) ?>. The program records your response and analyzes the amplitudes to measure the time elapsed between the picture appearing on the screen and the start of your response. 
	</p>
	<p>
		You will see an image on the screen and all you have to do is say what it is as fast as you can. After you see your reaction time appear under the picture, press the spacebar to load the next image. 
	</p>
	<p>
		First, you will go through a set of <?=$num_questions?> images in <?= $quiz->language(0) ?>, then another set of <?=$num_questions?> images in <?= $quiz->language(1) ?>. 
	</p>
	<p>
		You can take a break whenever you like while completing the experiment - just press the spacebar when you are ready to continue.
	</p>
	<?php $URL = "$ABS_ROOT/quiz.php?session=" . $quiz_session->id(); ?>
	<p>
		<strong>To avoid having to start the experiment over in case you close this window or your computer crashes, copy this link now and use it to continue where you left off later: <a href="<?=$URL?>"> <?=$URL?> </a> </strong>
	</p>
</div>

<div class="instruction-box sub-title">
	<h3>Example</h3>
</div>

<div class="instruction-box">
	<img id="example-img" src="images/tomato.jpg">
	<div class="picture-label"> <b>Image:</b> 1/250 </div>
	<div class="picture-label"> <b>Language:</b> English </div>
	<div class="picture-label"> <b>Reaction Time:</b> 3452 ms </div>
	<br/>
	<p>
		If this was your image, you would say 'tomato', then wait for your reaction time to appear under the image and press the spacebar when you are ready to continue. 
	</p>
	<br/>
	<br/>
	<p>
		<h4>Get ready! Respond to the following <?=$num_questions?> images in <?= $quiz->language(0) ?>.</h4>
	</p>
	
	<!-- The actual action of this is set in javascript (request.js) -->
	<a id="begin" class="btn" onclick="startNextLanguage(0); return false"> Begin Experiment </a>
</div>
