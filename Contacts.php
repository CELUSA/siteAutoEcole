<!DOCTYPE HTML>
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

  <div class="content">
  

<h2>Formulaire de contact</h2>
	
   <form style="text-align:left" name="contactform"  method="post" action="mail-form.php">
<table width="450px">
<tr>
 <td valign="top">
  <label for="Prénom">Prénom *</label>
 </td>
 <td valign="top">
  <input  type="text" name="Prénom" maxlength="50" size="30">
 </td>
</tr>
<tr>
 <td valign="top"">
  <label for="Nomdefamille">Nom de famille *</label>
 </td>
 <td valign="top">
  <input  type="text" name="Nomdefamille" maxlength="50" size="30">
 </td>
</tr>
<tr>
 <td valign="top">
  <label for="email">Addresse Email *</label>
 </td>
 <td valign="top">
  <input  type="text" name="email" maxlength="80" size="30">
 </td>
</tr>
<tr>
 <td valign="top">
  <label for="telephone">Numéro de téléphone</label>
 </td>
 <td valign="top">
  <input  type="text" name="telephone" maxlength="30" size="30">
 </td>
</tr>
<tr>
 <td valign="top">
  <label for="comments">Message *</label>
 </td>
 <td valign="top">
  <textarea  name="comments" maxlength="1000" cols="25" rows="6"></textarea>
 </td>
</tr>
<tr>
 <td valign="top">
  <label for="choices">Comment avez vous trouvé le site ? *</label>
 </td>
 <td valign="top">
  <input type="radio" name="choices" value="Internet">Internet<br>
  <input type="radio" name="choices" value="Ami(e)">Ami(e) <br>
  <input type="radio" name="choices" value="Publicité">Publicité(e) <br> 
 <br> </td>
</tr>
<br>
<tr>
 <td valign="top">
  <label for="contact">Options *</label>
 </td>
 <td valign="top">
  <input type="radio" name="contact" value="Téléphone">Téléphone<br>
 <input type="radio" name="contact" value="Email">Email
 </td>
</tr>
<tr>
 <td colspan="2" style="text-align:center"> <br>
  <input type="submit" value="Envoyer" align="center">
 </td>
</tr>
</table>
</form>
</div>

</div>




  </div>
  <!-- end .content -->

  <!-- end .container --></div>
</body>

<footer>

<!--footer-->
<?php require 'footer.html'; ?>

</footer>
</html>
