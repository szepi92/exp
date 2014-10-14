<?php
	require_once 'includes/env.php';
	$worked = require_once 'endpoints/session-handler.php';	// this find the $quiz_session, $user, and $quiz
	if (!$worked || !isset($quiz_session)) die("Session id doesn't exist. Try a different one.");
	require_once 'endpoints/generate-results.php';
	require_once 'includes/algo.php';
	
	$language = 0;
	if (isset($_REQUEST["language"])) {
		$language = intval($_REQUEST["language"]);
	}
	
	if ($language >= $quiz->numLanguages()) {
		die("Language must be between 0 and " . ($quiz->numLanguages() - 1));
	}
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
	
	<!-- HACK: CSS here -->
	<style>
		table,th,td { border:1px solid black; border-collapse:collapse; }
		th,td { padding:5px; }
		th { text-align:left; }
		
		span {
			font-size: 175%;
			width: 7%;
		}
		
		h3 {
			font-size: 225%;
		}
	</style>
	
	<!-- HACK: JS here -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script>
		window.startTime = 0;
		function increment($el) {
			curTime = Date.now();
			diff = (curTime - startTime);
			maxValue = $el.data("count");
			$el.text(diff % maxValue);
		}
		
		$(function(){
			window.startTime = Date.now();
			
			var f = function($el) {
				setInterval(function() { increment($el); }, 10);
			};
			
			$(".timer").each(function(index,elem){
				$el = $(elem);
				f($el);
			});
		});
	</script>
	
  </head>
  <body>
    <div id="result-title">
		<h1>Reaction Timers</h1>
	</div>
	
	<div class="container">
		<?php $result_list = $results_by_language[$language]; ?>
		<div class="timer-group">
			<h3><?=$result_list->language?></h3>
			<?php foreach ($result_list->data as $question) { ?>
			<span style="display: inline-block; min-width: 47px;" class="timer" data-count="<?= $question->reactionTime()  ?>">0</span>
			<?php } ?>
		</div>
	</div>
	
    <!-- Library javascript files -->
    <script src="js/lib/bootstrap.min.js"></script>
	<script src="js/lib/underscore-min.js"></script>
	<script src="js/lib/backbone-min.js"></script>
  </body>
</html>