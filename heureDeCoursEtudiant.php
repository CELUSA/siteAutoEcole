<?php
/********************************************************Connexion a la base de données************************************************************/
function afficherHeureDeCoursEtudiant($WhereAdresseMail){

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=auto_ecole_ppe', 'root', '');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

/********************************************************Construction requete sql***********************************************************/

$reponse = $bdd->query("select date_lecon, heure_lecon, moniteur.prenom_moniteur, moniteur.nom_moniteur from lecon, moniteur, client where lecon.id_client = client.id_client AND lecon.id_moniteur=moniteur.id_moniteur AND client.adresse_mail LIKE '".$WhereAdresseMail."' AND DATE_LECON > CURDATE() ORDER BY DATE_LECON ASC");

$count = $bdd-> query("select count(*) as nbligne from lecon, moniteur, client where lecon.id_client = client.id_client AND lecon.id_moniteur=moniteur.id_moniteur AND client.adresse_mail LIKE '".$WhereAdresseMail."' AND DATE_LECON > CURDATE() ORDER BY DATE_LECON ASC");

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
			avec <?php echo $donnees['prenom_moniteur'];?>
				<?php echo $donnees['nom_moniteur'];?></br></br>
<?php	
	}

	$ligneleconEtudiant=$count->fetch();
		if ($ligneleconEtudiant['nbligne'] == 0 )
			{
				echo "Pas d'heure de conduite programmée.";
			}

	
	


	
	
}  
?>