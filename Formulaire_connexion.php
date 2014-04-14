<<<<<<< HEAD
﻿<!DOCTYPE HTML>
=======
<!DOCTYPE HTML>
>>>>>>> 7a6551eabbc6249f59e7ff4749f89001bb2cd3b6
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Auto-Ecole Driver</title>
<META NAME="description" CONTENT="Auto-Ecole Paris"> 
<META NAME="keywords" CONTENT="Auto, école, conduite,  Paris, Paris 1e ">
        <meta name="robots" content="index,follow" />
        <meta property="og:title" content="Driver, Paris 1er"/>
        <meta property="og:type" content="autoecole" />
        <meta property="og:latitude" content="2.358404"/>
        <meta property="og:longitude" content="48.856022"/>
        <meta property="og:street-address" content="24 rue de Rivoli"/>
        <meta property="og:locality" content="Paris 1ème"/>
        <meta property="og:region" content="Île-de-France"/>
        <meta property="og:postal-code" content="75001"/>
        <meta property="og:country-name" content="France"/>
		
		<!-- Include Main jQuery library for slider -->
        <script type="text/Javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <!-- Include Main Easy Slides library & default css file-->
        <script type="text/Javascript" src="jQuery.easySlides/js/jquery.easyslides.min.v1.1.js"></script>
        <link rel="stylesheet" type="text/css" href="jQuery.easySlides/css/easySlides.default.min.css" />
        <!-- Include own Javascript/jQuery & CSS file for Slider -->
        <script type="text/Javascript" src="Examples/Example 1/example_1.js"></script>
        <link rel="stylesheet" type="text/css" href="Examples/Example 1/styles.css" />
		<!-- Include CSS stylesheet for this page -->
        <link rel="stylesheet" type="text/css" href="index.css" />
        

</head>

<header>

<!--header-->
<?php require 'header.html'; ?>

</header>

<body class="page">
<div class="container">


  <div class="content"; style="text-align:center">
  

<h1> Vos heures de cours programmées </h1>
		<form method="post" name="insc" action="http://81.64.83.238:8081/Site_PPE/AfficherHeure.php">
			Adresse mail :<input type="text" name="adresseMail"> <br>
			Mot de passe :<input type="password" name="password"> <br>
			<input type="submit" value="OK">
		</form>

  </div>
  <!-- end .content -->
  


  <!-- end .container --></div>

</body>
<footer>

<!--footer-->
<?php require 'footer.html'; ?>

</footer>
</html>


