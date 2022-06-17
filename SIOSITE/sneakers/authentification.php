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
			$message_erreur = "<p style=text-align:center>Login ou mot de passe incorrect.</p>";
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
    color:white;
	padding-top:50px;
    display: flex;
    align-items: center ;
	justify-content:center;
	width:100%;

}
    
.cursor-text-connexion:hover {
	padding:10px;
	background-color:white;
	color:black;
	border-radius: 15px 50px 30px;
}

.cursor-text-inscription:hover {
	padding:10px;
	background-color:white;
	color:black;
	border-radius: 15px 50px 30px;
}

::placeholder{
	color:white;
}

#seConnecter:hover{
	cursor:pointer;
}

</style>


<div class=formAuth>
	<form method=post>
		<h2 style=text-align:center;><a href="authentification.php" class="cursor-text-connexion">Connexion</a> | <a href="inscription.php" class="cursor-text-inscription">Inscription</a></h2>
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
			<tr><td><td><input name=login id=login placeholder="LOGIN / MAIL*" autocomplete=off style="width:300px;height:20px;border-radius: 15px 50px 30px;text-align:center;padding-left:10px;background-color:rgba(0, 0, 0, 0.5);color:white;border-color:white;";
				<?= isset($_POST['login'])?"value={$_POST['login']}":""; ?>>
				<tr><td><td>
				<tr><td><td>
			<tr><td><td><input type=password id=password name=pwd placeholder="MOT DE PASSE" style="width:300px;height:20px;border-radius: 15px 50px 30px;text-align:center;padding-left:10px;background-color:rgba(0, 0, 0, 0.5);color:white;border-color:white;"> 
			<tr><td><td style=text-align:center;><input type=checkbox name=remember
				<?= isset($_POST['remember'])?"checked":""; ?>> Se souvenir de moi
			<tr><td><td style=text-align:center;><input type=submit id=seConnecter value="Se connecter" style="width:150px;height:20px;border-radius: 15px 50px 30px;text-align:center;padding-left:10px;background-color:rgba(0, 0, 0, 0.5);color:white;border-color:white;">
			<tr><td><td style=text-align:center;><a href=mdpo.php style=color:white;>Mot de passe oublié ?</a>
			<tr><td><td style=font-size:80%;text-align:center; colspan=2>* : champ obligatoire
			<tr><td colspan=2 id=error class=error>
				<?= isset($message_erreur)?$message_erreur:''; ?>
		</table>
	</form>
</div>

<?php include "pageEnd.php"; ?>