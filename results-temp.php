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
		$name = $user->firstName();
	?>
  </head>
  <body>
  
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
	
    <!-- Library javascript files -->
	<script src="js/lib/bootstrap.min.js"></script>
	<script src="js/lib/underscore-min.js"></script>
	<script src="js/lib/backbone-min.js"></script>
  </body>
</html>