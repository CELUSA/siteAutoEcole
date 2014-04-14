<?php
/********************************************************Connexion a la base de données************************************************************/
function afficherHeureDeCoursMoniteur($WhereAdresseMail){

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=auto_ecole_ppe', 'root', '');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

/********************************************************Construction requete sql************************************************************/

$reponse = $bdd->query("SELECT distinct date_lecon, heure_lecon, id_vehicule, client.prenom_client, client.nom_client from lecon, moniteur, client WHERE moniteur.id_moniteur = lecon.id_moniteur AND moniteur.adresse_mail LIKE '".$WhereAdresseMail."' AND DATE_LECON > CURDATE() ORDER BY DATE_LECON ASC");

$count = $bdd-> query("SELECT count(*) as nbligne from lecon, moniteur, client WHERE moniteur.id_moniteur = lecon.id_moniteur AND moniteur.adresse_mail LIKE '".$WhereAdresseMail."' AND DATE_LECON > CURDATE() ORDER BY DATE_LECON ASC");


/********************************************************Construction de la page************************************************************/

echo "<strong>Vos prochaines heures de cours plannifiées:</strong></br></br>";
while($donnees=$reponse->fetch())
	{
?>

		
		* Leçon le  <?php echo $dateJour = substr($donnees['date_lecon'],8, 10); ?>
								<?php $dateMois = substr($donnees['date_lecon'],5, 7); ?>
									<?php $dateMois = substr($dateMois,0, 2); 	if ($dateMois == '01') {$dateMois = 'Janvier';}
																															if ($dateMois == '02') {$dateMois = 'Février';}
																															if ($dateMois == '03') {$dateMois = 'Mars';}
																															if ($dateMois == '04') {$dateMois = 'Avril';}
																															if ($dateMois == '05') {$dateMois = 'Mai';}
																															if ($dateMois == '06') {$dateMois = 'Juin';}
																															if ($dateMois == '07') {$dateMois = 'Juillet';}
																															if ($dateMois == '08') {$dateMois = 'Aout';}
																															if ($dateMois == '09') {$dateMois = 'Septembre';}
																															if ($dateMois == '10') {$dateMois = 'Octobre';}
																															if ($dateMois == '11') {$dateMois = 'Novembre';}
																															if ($dateMois == '12') {$dateMois = 'Décembre';}?>
										<?php echo $dateMois; ?>
																															
								<?php echo $dateAnne = substr($donnees['date_lecon'],0, 4); ?>
			à <?php echo $heureLecon = substr($donnees['heure_lecon'],0, 5); ?> 
			avec <?php echo $donnees['prenom_client'];?>
						<?php echo $donnees['nom_client'];?></br></br>
<?php	
	}
	
	$ligneleconMoniteur=$count->fetch();
		if ($ligneleconMoniteur['nbligne'] == 0 )
		{
			echo "Pas d'heure de conduite programmée.";
		}

}
	
	
  
?>