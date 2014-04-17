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
<div class="container"

<div class="content">

<div class="post-entry">

<h3><br><br>RÉCUPÉRATION DE POINTS<br></h3>

<div>
<p><img alt="" src="images/recuppoints.png"></p>
</div>
<div style="text-align:justify">
<p>Un stage permis à points dure systématiquement 2 jours et doit être éffectué dans un centre agréé. La présence du stagiaire est impérative pendant les 2 jours. Ce stage permis à points ou stage de récupération de points permet de récupérer jusqu’à 4 points. Un délai de un an minimum doit être observé entre deux stages.</p>
<p><strong>En cas de perte totale des points</strong>, le permis de conduire est invalidé pour une période de 6 mois. Il n’est plus possible de suivre un stage et l’examen du code la route doit être repassé.</p>
<p><strong>Durée d’un stage</strong>&nbsp;: 2 jours consécutifs.<br>
Public : Tous les conducteurs ayant perdu des points ou condamnés dans le cadre d’un délit routier. Le suivi d’un stage peut être obligatoire dans certains cas.</p>
</div>

<div>
<hr>

<h4>Les prochains stages de récupération de points organisés par l’auto école Driver</h4>
<div></div>
<h4>Stage Permis à Points</h4>
<table border="1"; align="center">
<tbody>
<tr>
<th height="23">
<h5>Dates</h5>
</th>
<th>
<h5>Lieu</h5>
</th>
<th>
<h5> Prix </h5>
</th>
<th>
<h5> Se renseigner </h5>
</th>
</tr>

<?php

//Connexion base de données

mysql_connect ("localhost","root","") or die ('ERREUR '.mysql_error("ERREUR DE CONNEXION A LA BASE DE DONNEES"));
mysql_select_db ("auto_ecole_ppe") or die ('ERREUR '.mysql_error("ERREUR LORS DE LA SELECTION DE LA BASE DE DONNEES"));

//Création de la requête

$requete = "SELECT * FROM stage_recup_points";
$resultat = mysql_query ($requete);

//Recuperations des données et création des lignes du tableau

while ($ligne = mysql_fetch_assoc($resultat)) {
	
	//TRANSFORMATION DATE debut US -> FR
?>
	<?php  $dateJourDebut = substr($ligne['stage_date_debut'],8, 10); ?>
	<?php $dateMoisDebut = substr($ligne['stage_date_debut'],5, 7); ?>
			<?php $dateMoisDebut = substr($dateMoisDebut,0, 2); if ($dateMoisDebut == '01') {$dateMoisDebut = 'Janvier';} if ($dateMoisDebut == '02') {$dateMoisDebut = 'Février';} if ($dateMoisDebut == '03') {$dateMoisDebut = 'Mars';} if ($dateMoisDebut == '04') {$dateMoisDebut = 'Avril';} if ($dateMoisDebut == '05') {$dateMoisDebut = 'Mai';} if ($dateMoisDebut == '06') {$dateMoisDebut = 'Juin';} if ($dateMoisDebut == '07') {$dateMoisDebut = 'Juillet';} if ($dateMoisDebut == '08') {$dateMoisDebut = 'Aout';} if ($dateMoisDebut == '09') {$dateMoisDebut = 'Septembre';} if ($dateMoisDebut == '10') {$dateMoisDebut = 'Octobre';} if ($dateMoisDebut == '11') {$dateMoisDebut = 'Novembre';} if ($dateMoisDebut == '12') {$dateMoisDebut = 'Décembre';}?>
	<?php  $dateAnneeDebut = substr($ligne['stage_date_debut'],0, 4);?>

	
	<?php  $dateJourFin = substr($ligne['stage_date_fin'],8, 10); ?>
	<?php $dateMoisFin = substr($ligne['stage_date_fin'],5, 7); ?>
			<?php $dateMoisFin = substr($dateMoisFin,0, 2); 	if ($dateMoisFin == '01') {$dateMoisFin = 'Janvier';} if ($dateMoisFin == '02') {$dateMoisFin = 'Février';} if ($dateMoisFin == '03') {$dateMoisFin = 'Mars';} if ($dateMoisFin == '04') {$dateMoisFin = 'Avril';} if ($dateMoisFin == '05') {$dateMoisFin = 'Mai';} if ($dateMoisFin == '06') {$dateMoisFin = 'Juin';} if ($dateMoisFin == '07') {$dateMoisFin = 'Juillet';} if ($dateMoisFin == '08') {$dateMoisFin = 'Aout';} if ($dateMoisFin == '09') {$dateMoisFin = 'Septembre';} if ($dateMoisFin == '10') {$dateMoisFin = 'Octobre';} if ($dateMoisFin == '11') {$dateMoisFin = 'Novembre';} if ($dateMoisFin == '12') {$dateMoisFin = 'Décembre';}?>
	<?php  $dateAnneeFin = substr($ligne['stage_date_fin'],0, 4);?>

	
	<?php	
	echo "<tr>";
		echo'<td> Du '.$dateJourDebut.' '.$dateMoisDebut.' '.$dateAnneeDebut.' <br> au '.$dateJourFin.' '.$dateMoisFin.' '.$dateAnneeFin.'</td>';
		echo'<td>'.$ligne["stage_lieu"].'</td>';
		echo'<td>'.$ligne["stage_prix"].' €</td>';
		echo'<td>	<form><a title="Contact" href="http://81.64.83.238:8081/Site_PPE/Contacts.php">Se Renseigner</a></form>	</td>';
	echo "</tr>";
	
	
}
?>

<!-- TABLEAU EN DUR 

		<tr>
		<td>Du Vendredi 20 Juin au&nbsp<br>
		Samedi 21 Juin</td>
		<td>Auto Ecole Driver<br>

		21 RUE DE RIVOLI<br>

		75001 PARIS</td>
		<td>260.00 €</td>
		<td>

		<form><a title="Contact" href="http://81.64.83.238:8081/Site_PPE/Contacts.php">Se Renseigner</a></form>
		</td>
		</tr>
	
		
		<tr>
		<td>Du Vendredi 18 Juillet au&nbsp<br>
		Samedi 19 Juillet</td>
		<td>Auto Ecole Driver<br>
		21 RUE DE RIVOLI<br>
		75001 PARIS</td>
		<td>260.00 €</td>
		<td>
		<a title="Contact" href="http://81.64.83.238:8081/Site_PPE/Contacts.php">Se Renseigner</a></form>
		<form></form>
		</td>
		</tr> -->
		
		
</tbody>
</table>
</div></div>
</div>
<br>
  <!-- end .container --></div>
</body>

<footer>

<!--footer-->
<?php require 'footer.html'; ?>

</footer>
</html>

