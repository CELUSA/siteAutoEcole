<?php

require_once("C:\wamp\apps\phpmailer2.0.4/class.phpmailer.php"); 
require_once("C:\wamp\apps\phpmailer2.0.4/class.smtp.php");
 
if(isset($_POST['email'])) {
 
     
 
    // EDIT THE 2 LINES BELOW AS REQUIRED
 
    $email_to = "ppe.celusa@gmail.com";
 
    $email_subject = "Email de votre formulaire";
 
     
 
     
 
  /*  function died($error) {
 
        // your error code can go here
 
        echo "Désolé, mais il y a une(des) erreur(s).";
 
        echo "Voici le(s) détail(s) :<br /><br />";
 
        echo $error."<br /><br />";
 
        echo "Réglez l'(les) erreur(s) SVP...<br /><br />";
 
        die();
 
    }
	*/
 
     
 
    // validation expected data exists
 
    if(!isset($_POST['Prénom']) ||
 
        !isset($_POST['Nom de famille']) ||
 
        !isset($_POST['email']) ||
 
        !isset($_POST['telephone']) ||
 
        !isset($_POST['comments'])) {

     //   died('Désolé, mais il y a une(des) erreur(s).');       
 
    }


    if(!isset($_POST['choices'])) {

$selected_radio = $_POST['choices'];
print $selected_radio;

}

    if(!isset($_POST['contact'])) {

$selected_radio = $_POST['contact'];
print $selected_radio;

}

	


     
 
    $Prenom = $_POST['Prénom']; // required
 
    $NomDeFamille = $_POST['Nomdefamille']; // required
 
    $email_from = $_POST['email']; // required
 
    $telephone = $_POST['telephone']; // not required
 
    $comments = $_POST['comments']; // required

    $choices = $_POST['choices']; // not required

    $contact = $_POST['contact']; // not required
	
	     
 
    $error_message = "";
 
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
 
  if(!preg_match($email_exp,$email_from)) {
 
    $error_message .= "L'adresse que vous avez entrée n'est pas valide.<br />";
 
  }
 
    $string_exp = "/^[A-Za-z .'-éèàù]+$/";
 
  if(!preg_match($string_exp,$Prenom)) {
 
    $error_message .= "Le prénom que vous avez entré n'est pas valide.<br />";
 
  }
 
  if(!preg_match($string_exp,$NomDeFamille)) {
 
    $error_message .= "Le nom que vous avez entré n'est pas valide.<br />";
 
  }
 
  if(strlen($comments) < 2) {
 
    $error_message .= "Les commentaires que vous avez entrés ne sont pas valide.<br />";
 
  }
 
 /* if(strlen($error_message) > 0) {
 
    died($error_message);
 
  }
 */
    $email_message = "<html>". $Prenom ." ". $NomDeFamille ." souhaiterait être recontacté. <br>Voici ses coodronnées : <br><br>";
 
     
 
    function clean_string($string) {
 
      $bad = array("content-type","bcc:","to:","cc:","href");
 
      return str_replace($bad,"",$string);
 
    }
 
     
 
    $email_message .= "Prénom : ".clean_string($Prenom)."<br>";
 
    $email_message .= "Nom de famille : ".clean_string($NomDeFamille)."<br>";
 
    $email_message .= "Email : ".clean_string($email_from)."<br>";
 
    $email_message .= "Téléphone : ".clean_string($telephone)."<br><br>";
 
    $email_message .= "Commentaires : ".clean_string($comments)."<br><br>";
 
    $email_message .= "A découvert l'auto-école Driver grâce à : ".clean_string($choices)."<br>";
 
    $email_message .= "Souhaite être contacté par : ".clean_string($contact)."<br></html>";
 
     
 
     
 
// create email headers
 
//$headers = "From: ".$email_from."\r\n".
 
//"Reply-To: ".$email_from."\r\n" .
 
//"X-Mailer: PHP/" . phpversion();
 
//mail($email_to, $email_subject, $email_message, $headers);  

$body = file_get_contents("mail-form.php");
$body = preg_replace("/[\t]/i"," ",$body);

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";
$mail->SMTPAuth = true;
$mail->Host = "smtp.gmail.com"; // "ssl://smtp.gmail.com" n'a pas fonctionné
$mail->Port = "465";
$mail->SMTPSecure = "ssl";
// or try these settings (worked on XAMPP and WAMP):
// $mail->Port = 587;
// $mail->SMTPSecure = 'tls';
 
 
$mail->Username = "ppe.celusa@gmail.com";
$mail->Password = "cedriclucassamuel";
 
$mail->IsHTML(true); // if you are going to send HTML formatted emails
$mail->SingleTo = true; // if you want to send a same email to multiple users. multiple emails will be sent one-by-one.
 
$mail->From = $email_from;
$mail->FromName = $email_from;
 
$mail->addAddress($email_to);
 
$mail->Subject = "Demande de contact de la part de ". $Prenom. " ". $NomDeFamille;
$mail->Body = $email_message;

if(!$mail->Send())
    {//echo "Message non envoye <br />PHPMailer Error: " . $mail->ErrorInfo;

?>
 
<html>
	<meta http-equiv="refresh" content="0; url=Error.php" />
</html>

<?php
}
elseif($mail->Send())
    {//echo "Message bien envoye!";
 
?>
 
<html>
	<meta http-equiv="refresh" content="0; url=success.php" />
</html>
 
<?php
} 
}
 
?>