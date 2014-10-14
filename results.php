<?php
	require_once 'includes/env.php';
	require_once 'endpoints/session-handler.php';	// this find the $quiz_session, $user, and $quiz
	require_once 'endpoints/generate-results.php';
	require_once 'includes/algo.php';
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
	
	
  </head>
  <body>
    <div id="result-title">
		<h1>Results</h1>
	</div>
	
	<div id="experiment-data">
		<div class="data">
			<div class="data-title"> Summary </div>
			<ul>
				<li><b>faster language:</b> <?=$summary["faster_language"] ?> </li>
				<li><b>fastest word:</b> <?=$summary["fastest_word"] ?> </li>
				<li><b>slowest word:</b> <?=$summary["slowest_word"] ?> </li>
			</ul>
		</div>
		<?php foreach ($results_by_language as $result) { ?>
		<div class="data">
			<div class="data-title"> <?= $result->language ?> </div>
			<ul>
				<li><b>average speed:</b> <?= $result->average_speed ?> ms</li>
				<li><b>slowest speed:</b> <?= $result->slowest_speed ?> ms</li>
				<li><b>slowest word:</b> <?= $result->slowest_word ?></li>
				<li><b>fastest speed:</b> <?= $result->fastest_speed ?> ms</li>
				<li><b>fastest word:</b> <?= $result->fastest_word ?></li>
			</ul>
		</div>
		<?php } ?>
	</div>
	
	<div class="results">
		<?php foreach ($results_by_language as $result_list) { ?>
		<div class="data-column object">
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
			<table>
				<colgroup><col width="151"><col width="86"><col width="86"><col width="302"></colgroup>
				<tr><th>Word</th> <th>Reaction Time (ms)</th> <th>Prime Factors</th> <th>Assigned Colours</th> </tr>
				<?php foreach ($result_list->data as $question) { ?>

				<tr>
					<td><?=Util::StripExtension($quiz->image($question->language(), $question->question()))?></td>
					<td><?=$question->reactionTime()?></td>
					<?php
						$primes = Algo::Primes($question->reactionTime());
					?>
					<td><?= implode(", ", $primes); ?></td>
					<td><?= implode(", ", array_map($prime_mapper, $primes)); ?></td>
				</tr>
				<?php } ?>
			</table>
		</div>
		<?php } ?>
	</div>
	
    <!-- Library javascript files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
	<script src="js/lib/underscore-min.js"></script>
	<script src="js/lib/backbone-min.js"></script>
  </body>
</html>