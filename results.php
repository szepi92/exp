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
  
    <div id="result-title">
		<h1>Results</h1>
	</div>
	
	<div id="experiment-data">
		<div class="data">
			<div class="data-title"> Summary </div>
			<ul>
				<li><b>faster language:</b> Second Language </li>
				<li><b>fastest word:</b> First Language </li>
				<li><b>slowest word:</b> First Language </li>
			</ul>
		</div>
		<div class="data">
			<div class="data-title"> First Language </div>
			<ul>
				<li><b>average speed:</b> 3423 ms</li>
				<li><b>slowest speed:</b> 313413 ms</li>
				<li><b>slowest word:</b> butterfly</li>
				<li><b>fastest speed:</b> 2313 ms</li>
				<li><b>fastest word:</b> grass</li>
			</ul>
		</div>
		<div class="data">
			<div class="data-title"> Second Language </div>
			<ul>
				<li><b>average speed:</b> 3445 ms</li>
				<li><b>slowest speed:</b> 313413 ms</li>
				<li><b>slowest word:</b> elephant</li>
				<li><b>fastest speed:</b> 1234 ms</li>
				<li><b>fastest word:</b> apple</li>
			</ul>
		</div>
	</div>
	
	<div class="results">
		<div class="data-column">
			<h3>First Language</h3>
			<object data="docs/sample-data-hungarian.html"> </object>
		</div>
		<div class="data-column">
			<h3>Second Language</h3>
			<object data="docs/sample-data-english.html"> </object>
		</div>
	</div>
	
	
	
    <!-- Library javascript files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/lib/underscore-min.js"></script>
	<script src="js/lib/backbone-min.js"></script>
  </body>
</html>