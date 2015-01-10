<?
	require_once 'includes/env.php';
	require_once 'endpoints/new-quiz.php';	// this will parse any submitted info (the variables are now in scope)
	require_once 'endpoints/session-redirect.php';	// this will redirect if session id is given
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
	
	<!-- Library javascript files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
	<script src="js/lib/underscore-min.js"></script>
	<script src="js/lib/backbone-min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<!-- This code handles "multiple languages" -->
	<script>
		<? if (empty($languages)) $languages = array();?>
		
		language = _.map(<?= json_encode($languages)	 ?>, function(v){
			if (_.isObject(v)) return v.name;
			else return v;
		});
		
		function resetLanguages() {
			var i;
			var numLanguages = parseInt($("#NumLanguages").val());
			
			var good = !!numLanguages;
			if (!numLanguages || numLanguages < 2) {
				numLanguages = 2;
			}
			
			if (!!numLanguages && numLanguages > 5) {
				numLanguages = 5;
			}
			
			if (good) $("#NumLanguages").val(numLanguages);
			
			if (language.length < numLanguages) {
				for(i = language.length; i < numLanguages; ++i) language[i] = '';
			}
			
			$f = $("#language-fields");
			$f.empty();
			for (i = 0; i<numLanguages; ++i) {
				$f.append('<span class="form-name">Language '+(i+1)+':</span> <input class="input-box form-control" type="text" name="Language'+(i+1)+'" value="'+language[i]+'">');
			}
		}
		
		$(document).ready(function(){
			if (_.isEmpty($("#NumLanguages").val())) $("#NumLanguages").val(2);
			$("#NumLanguages").bind('keyup mouseup', function(e,f,g){
				resetLanguages();
			});
			resetLanguages();
		
		});
		
	</script>
  </head>
  <body>
	<?php if (isset($ERROR) and !empty($ERROR)) { ?>
	<!-- Alert box (see Bootstrap) -->
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert">
			<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
		</button>
		<strong>Oops.</strong>
		<?php if ($DEBUG) echo $ERROR->exception->getMessage() . " "; echo $ERROR->friendly_message; ?>
	</div>
	<?php } ?>
	
	<div id="title">
		<h1>Hybrids of Canada: Language Experiment</h1>
	</div>
	
	<form id="form" action="index.php" method="post">
		<span class="form-name">First Name:</span> <input class="input-box form-control" type="text" name="FirstName" value="<?=$first_name?>">
		<span class="form-name">Last Name:</span> <input class="input-box form-control" type="text" name="LastName" value="<?=$last_name?>">
		<span class="form-name">Date of Birth:</span> <input class="input-box form-control" type="text" name="Birthday" value="<?=$birthday?>" placeholder="mm/dd/yyyy"> 
		<span class="form-name">Country of Origin:</span> <input class="input-box form-control" type="text" name="Country" value="<?=$country?>"> 
		<span class="form-name">Date of Relocation:</span> <input class="input-box form-control" type="text" name="Relocation" value="<?=$relocation?>" placeholder="mm/dd/yyyy"> 
		<span class="form-name">E-Mail Address:</span> <input class="input-box form-control" type="text" name="Email" value="<?=$email?>"> 
		<span class="form-name">Number of Languages:</span> <input class="input-box form-control" type="number" min="2" max="5" name="NumLanguages" id="NumLanguages" value="<?=$num_languages?>"> 
		<div id="language-fields">
		</div>
		<input class="btn" id="submit-button" type="submit" value="Submit">
	</form>
  </body>
</html>