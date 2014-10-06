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
	
	
  </head>
  <body>
  
    <div id="title">
		<h1>Hybrids of Canada: Language Experiment</h1>
	</div>
	
	<form id="form" action="instructions.php">
		<span class="form-name">First Name:</span> <input class="input-box form-control" type="text" name="FirstName"> 
		<span class="form-name">Last Name:</span> <input class="input-box form-control" type="text" name="LastName">
		<span class="form-name">Date of Birth:</span> <input class="input-box form-control" type="date" name="Birthday" placeholder="mm/dd/yyyy"> 
		<span class="form-name">Country of Origin:</span> <input class="input-box form-control" type="text" name="Country"> 
		<span class="form-name">Date of Relocation:</span> <input class="input-box form-control" type="date" name="Relocation" placeholder="mm/dd/yyyy"> 
		<span class="form-name">E-Mail Address:</span> <input class="input-box form-control" type="email" name="Email"> 
		<span class="form-name">First Language:</span> <input class="input-box form-control" type="text" name="FirstLanguage">
		<span class="form-name">Second Language:</span> <input class="input-box form-control" type="text" name="SecondLanguage">
		<input class="btn" id="submit-button" type="submit" value="Submit">
	</form>
	
	
	
    <!-- Library javascript files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/lib/underscore-min.js"></script>
	<script src="js/lib/backbone-min.js"></script>
  </body>
</html>