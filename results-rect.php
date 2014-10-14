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
	
	// Ratios
	$CM_PER_MS = 1.0/100.0;
	$PX_PER_CM = 50.0/2.5;
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
	</style>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  </head>
  <body>
    <div id="result-title">
		<h1>Results (Painting Preview)</h1>
	</div>

	<div class="container">
		<?php $result_list = $results_by_language[$language]; ?>
		<h3><?=$result_list->language?></h3>
		
		<?php
			// Assign the colours, and create a function mapping primes to colour
			$prime_lists = array();
			foreach ($result_list->data as $question) {
				$primes = Algo::Primes($question->reactionTime());
				$prime_lists[] = $primes;
			}
			
			$assignment = Colour::AssignColours(array_keys(Colour::$COLOUR_MAP), $prime_lists);
			$prime_map = $assignment[0];
			$colour_map = $assignment[1];
			
			$prime_mapper = function($prime) {
				global $prime_map;
				return $prime_map[$prime];
			};
		?>
		
		<canvas id="myCanvas" width="1000" height="2000" style="border:1px solid #c3c3c3;">
		Your browser does not support the HTML5 canvas tag.
		</canvas>
	</div>
	
	<script>
		var c = document.getElementById("myCanvas");
		var ctx = c.getContext("2d");
		var grd;
		
		<?php
			$COLUMNS = 20;
			$COLUMN_WIDTH = 2.5 * $PX_PER_CM;
			$n = count($result_list->data);
			
			$tops = array();
			for($c=0;$c<$COLUMNS;++$c) $tops[] = 0;
			for($i=0;$i<$n;$i++) $tops[$i % $COLUMNS] += $result_list->data[$i]->reactionTime() * $CM_PER_MS * $PX_PER_CM;
			$max_height = max($tops);
			for($c=0;$c<$COLUMNS;++$c) $tops[$c] = 0;
		?>
		
		$("#myCanvas").attr("height",<?=$max_height?>);
		<?php for($i=0;$i<$n;$i++) { ?>
			<?php
				$question = $result_list->data[$i];
				$c = $i % $COLUMNS;
				$height = $question->reactionTime() * $CM_PER_MS * $PX_PER_CM;
				$width = $COLUMN_WIDTH;	// some default (2.5 cm)
				
				$primes = Algo::Primes($question->reactionTime());
				$my_colours = array_map($prime_mapper, $primes);
			?>
			
			grd = ctx.createLinearGradient(0,<?=$tops[$c]?>,0,<?=$tops[$c]+$height?>);
			grd.addColorStop(0, "<?=Colour::$COLOUR_MAP[$my_colours[0]]?>");
			<?php for($s=1;$s<count($my_colours); ++$s) { ?>
			grd.addColorStop(<?= $s*1.0/(count($my_colours) - 1)  ?>, "<?=Colour::$COLOUR_MAP[$my_colours[$s]]?>");
			<?php } ?>

			// Fill with gradient
			ctx.fillStyle = grd;
			ctx.fillRect(<?= $COLUMN_WIDTH*$c ?>,<?=$tops[$c]?>,<?=$width?>,<?=$height?>);
			<?php
				$tops[$c] += $height;
			?>
		<?php } ?>
	</script>

	
    <!-- Library javascript files -->
    <script src="js/lib/bootstrap.min.js"></script>
	<script src="js/lib/underscore-min.js"></script>
	<script src="js/lib/backbone-min.js"></script>
  </body>
</html>