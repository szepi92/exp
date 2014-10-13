<?php
	require_once 'includes/env.php';
	require_once 'includes/quiz-session.php';
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
		table,th,td { border:1px solid black; border-collapse:collapse; margin-bottom: 100px; margin-left: auto; margin-right: auto; }
		th,td { padding:5px; }
		th { text-align:left; }
	</style>
	
	
  </head>
  <body>
    <div id="result-title">
		<h1>List of all Complete Sessions</h1>
	</div>
	
	<?php
		$all_sessions = QuizSession::LookUpAll();
	?>
	
	<div class="container">
		<table>
			<tr><th>Name</th> <th>Date</th> <th>Session ID</th> <th>URL</th> </tr>
			<?php foreach ($all_sessions as $detail) { ?>
			<tr>
				<td><?=$detail["FirstName"] . " " . $detail["LastName"]?></td>
				<td><?=date('l, F d Y g:ia', $detail["StartTime"])?></td>
				<td><?=$detail["ID"]?></td>
				<td><? $url = $ABS_ROOT . "/results.php?session=" . $detail["ID"]; ?>
					<a href="<?= $url ?>"><?= $url ?></a>
				</td>
			</tr>
			<?php } ?>
		</table>
	</div>
	
    <!-- Library javascript files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
	<script src="js/lib/underscore-min.js"></script>
	<script src="js/lib/backbone-min.js"></script>
  </body>
</html>