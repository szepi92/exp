<?php
	require_once 'includes/env.php';
	require_once 'endpoints/session-redirect.php';	// this find the $quiz_session, $user, and $quiz
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
	
	<!-- Custom javascript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script>var QUIZ_SESSION_ID = "<?=$quiz_session->id()?>";</script>
	<script src="js/request.js"></script>
	
	<?php
		$firstLanguage  = $quiz->language(0);
		$secondLanguage = $quiz->language(1);
		$num_questions  = $quiz->questionsPerLang();
	?>
	
  </head>
  <body>
  
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
	
	
	
    <!-- Library javascript files -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/lib/underscore-min.js"></script>
	<script src="js/lib/backbone-min.js"></script>
  </body>
</html>