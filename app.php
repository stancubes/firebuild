<!doctype html>

<html lang="de">
<head>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

	<title>Static Website Builder & CMS - FireBuild</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="Florian Blaum - F&A IT">
	<link rel="stylesheet" href="css/styles.css?v=0.1" />
	
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/scripts.js"></script>

	<script src="https://use.fontawesome.com/1bfefb0350.js"></script>
	

</head>

<body>


	<div id="mainContainer"></div>


	<div id="myBar">
		
		<img src="img/favicon.png" onclick="openDialog('Nur ein kleiner Test', 'Dies hier ist lediglich ein kleiner Test ob auch alles funktioniert...<br><br>Ich erhebe keinen Anspruch auf Vollständigkeit =)');">

	</div>



	<input type="hidden" id="websites" value="1">
	<input type="hidden" id="pages" value="1">



	<div id="contentblock" onclick="closeDialog();"></div>
	<div id="dialog"><div id="dheader"><div id="dheadercontent"></div><div id="dheaderbutton"><i class="fa fa-times fa-fw fa-1x" title="schließen" onclick="closeDialog();"></i></div></div><div id="dcontent"></div></div>


	<script type="text/javascript">
		
		$(function() {
		
			displayFireSite();

		});

	</script>

</body>
</html>