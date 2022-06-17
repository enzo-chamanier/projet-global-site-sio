<?php 
	include "pageStartDisplay.php";

	// Étape 4 : traitement du formulaire de réinitialisation du mot de passe
	if (isset($_POST['newPwd1'])) {
		$message_erreur = "";
		$newPwd1 = myHash($_POST['newPwd1'], 'PWD');
		$newPwd2 = myHash($_POST['newPwd2'], 'PWD');
		$usr_id = $_GET['mdpo_usr_id'];
		$time = $_GET['time'];
		// Tentative de piratage du usr_id ?
		if (myHash($usr_id, 'MDPO') != $_GET['mdpo_usr_id_hash'])
			die("Tentative de piratage du usr_id détectée");
		// Tentative de piratage du time ?
		if (myHash($time, 'MDPOT') != $_GET['time_hash'])
			die("Tentative de piratage du time détectée");
		// Est-ce que le lien a expiré ?
		if (time() > $time + 5 * 60)
			$message_erreur = "Ce lien est périmé, <a href=mdpo.php>redemander un lien</a>";
		if ($message_erreur=="") { // Pas d'erreur : on modifie le mot de passe
			myQuery("UPDATE user SET usr_password = '$newPwd1'
				WHERE usr_id = $usr_id");
			header("location:authentification.php?reinit=");
		}
	}
	
	// Étape 2  : traitement du 1er formulaire
	if (isset($_POST['loginOuEmail'])) {
		$loginOuEmail = $_POST['loginOuEmail'];
		$sql = "SELECT usr_id, usr_mail FROM user
			WHERE usr_login = '$loginOuEmail'
			OR usr_mail = '$loginOuEmail'  ";
		$rs_user = myQuery($sql);
		// Pas de correspondance trouvées ni dans les logins ni dans les emails
		if ($rs_user->rowCount() == 0)
			$message_erreur = "Pas de correspondance trouvées ni dans les logins ni dans les emails";
		else {
			$user = $rs_user->fetch(PDO::FETCH_ASSOC);
			// Préparation du mail à envoyer puis envoi du mail
			$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].
				$_SERVER['SCRIPT_NAME'];
			$usr_id = $user['usr_id'];
			$mail = $user['usr_mail'];
			$usr_id_hash = myHash($usr_id, 'MDPO');
			$time = time();
			$time_hash = myHash($time, 'MDPOT');
			$body = "Bonjour,

Vous avez demandé à réinitialiser votre mot de passe.
Si vous n'être pas à l'origine de cette demande : pas d'inquiétude, la sécurité de votre compte n'est pas compromise.

Si c'est bien vous, cliquez sur ce lien pour choisir un nouveau mdp :
$url?mdpo_usr_id=$usr_id&mdpo_usr_id_hash=$usr_id_hash&time=$time&time_hash=$time_hash";
			$subject = "[{$_SERVER['HTTP_HOST']}] Réinit mdp";
			mail($mail, $subject, $body);
			$messageOK = "Un lien vous a été envoyé par mail. Cliquez dessus pour réinitialiser votre mot de passe.";
		}
	}
?>
<!-- Étape 3 : formulaire de réinitialisation du mot de passe -->

<?php
	if (isset($_GET['mdpo_usr_id'])) {
		formulaireÉtape3(); // réinit password
	}
	else {
		formulaireÉtape1(); // Saisie login ou email
	}

	function formulaireÉtape3() {
		global $message_erreur; ?>
		<!-- Étape 1 : formulaire de réinitialisation du mot de passe -->
		
		<form method=post>
			<h1>Réinitialiser votre mot de passe</h1>
			<table>
				<tr><td>Nouveau mot de passe* :<td><input type=password name=newPwd1>
				<tr><td>Confirmation du mot de passe* :<td><input type=password name=newPwd2>
				<tr><td><td><input type=submit value=Modifier>
				<tr><td style=font-size:80% colspan=2>* : champ obligatoire
				<tr><td colspan=2 id=errorMdp class=error>
					<?= isset($message_erreur)?$message_erreur:''; ?>
			</table>
		</form>
		<?php
	}
	
	function formulaireÉtape1() { 
		global $message_erreur, $messageOK; ?>
		<!-- Étape 1 : formulaire de saisie du login ou de l'email -->
	<div class=formEtape1>
		<form method=post>
			<h1>Oubli du mot de passe</h1>
			<table>
				<tr><td colspan=2 class=messageOK>
					<?= isset($messageOK)?$messageOK:'  '; ?>
				<tr><td>Login ou email* :<td><input name=loginOuEmail
					<?= isset($loginOuEmail)?"value=$loginOuEmail":""; ?>>
				<tr><td><td><input type=submit>
				<tr><td style=font-size:80% colspan=2>* : champ obligatoire
				<tr><td colspan=2 id=error class=error>
					<?= isset($message_erreur)?$message_erreur:'  '; ?>
			</table>
		</form>
	</div>

	<style>
	.formEtape1 {
		display: table;
    	align-items: center ;
    	margin: 0 auto;
	}
	</style>






	<?php } ?>

<?php include "pageend.php" ?>
