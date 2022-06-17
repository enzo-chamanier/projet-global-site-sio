<?php include "pageStartDisplay.php"; 

	// Traitement de l'authentification
	if (isset($_POST['login'])) {
		// Récupérer les valeurs du formulaire
		$login = $_POST['login'];
		$pwd = myHash($_POST['pwd'], 'PWD');
		$remember = isset($_POST['remember']);
		// Vérifier et effectuer l'authentification
		$rs_user = myQuery("SELECT usr_id, usr_mail_valide FROM user
			WHERE (usr_login = '$login' OR usr_mail='$login')
			AND usr_password = '$pwd' "); //OR usr_mail='$login'
        
		if ($rs_user->rowCount() == 0)
			$message_erreur = "Login ou mot de passe incorrect.";
		else {
			$user = $rs_user->fetch(PDO::FETCH_ASSOC);
			if (!$user['usr_mail_valide'])
				$message_erreur = "Vous devez valider votre mail pour pouvoir vous authentifier.";
			else {
				$expire = $remember?time()+3600*24*30*13:0;
				setcookie('user_id', $user['usr_id'], $expire);
				setcookie('user_id_hash', myHash($user['usr_id'], 'CO'), $expire);
				header('location:index.php');
			}
		}
        	
	}
	
?>

<style>

.formAuth {
	padding-top:50px;
    display: table;
    align-items: center ;
    margin: 0 auto;
}

</style>


<div class=formAuth>
	<form method=post>
		<h2 style=text-align:center;>Authentifiez-vous</h2>
		<table>
			<tr><td colspan=2 style=color:green;font-weight:bold>
				<?php if (isset($_GET['valide']))
						echo "Votre mail a bien été validé. Maintenant vous pouvez vous authentifier";
				?>
				<?php if (isset($_GET['reinit']))
						echo "Votre mot de passe a bien été réinitialisé. Maintenant vous pouvez vous authentifier";
				?>
			<tr><td><td>
			<tr><td><td>
			<tr><td><td>Login ou mail* :
			<tr><td><td><input name=login autocomplete=off style=width:600px;
				<?= isset($_POST['login'])?"value={$_POST['login']}":""; ?>>
				<tr><td><td>
				<tr><td><td>
				<tr><td><td>Password* : 
			<tr><td><td><input type=password name=pwd style=width:600px;> 
			<tr><td><td><input type=checkbox name=remember
				<?= isset($_POST['remember'])?"checked":""; ?>> Se souvenir de moi
			<tr><td><td><input type=submit>
			<tr><td><td><a href=mdpo.php>Mot de passe oublié ?</a>
			<tr><td><td style=font-size:80% colspan=2>* : champ obligatoire
			<tr><td colspan=2 id=error class=error>
				<?= isset($message_erreur)?$message_erreur:''; ?>
		</table>
	</form>
</div>

<?php include "pageEnd.php"; ?>