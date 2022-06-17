<?php include "pageStartDisplay.php"; 
    // Si on n'est pas connecté, on vire à l'accueil 
    if (!is_authentified()) { header('location:index.php'); exit; }


    // Traitement de changement de mot de passe
    if (isset($_POST['oldPwd'])) {
        $oldPwd = myHash($_POST['oldPwd'], 'PWD');
        $newPwd1 = myHash($_POST['newPwd1'], 'PWD');
        $newPwd2 = myHash($_POST['newPwd2'], 'PWD');
        // Gestion des erreurs
        $message_erreur_mdp = "";
        $sql = "SELECT usr_id FROM user WHERE usr_id = {$_COOKIE['user_id']} AND usr_password='$oldPwd' ";
		if (myQuery($sql)->rowCount() == 0) // Ancien mot de passe mal saisi
			$message_erreur_mdp .= "Le mot de passe actuel est incorrect";
		if ($newPwd1 != $newPwd2) // Mot de passe est mal confirmé
			$message_erreur_mdp .= "Le nouveau mot de passe est mal confirmé";
		if (strlen($newPwd1) < 6)
			$message_erreur_mdp .= "Le nouveau mot de passe est trop court (6 car. min.)";
		if (empty($message_erreur_mdp)) {
			$sql = "UPDATE user SET usr_password= '$newPwd1'
				WHERE usr_id = {$_COOKIE['user_id']}";
			myQuery($sql);
            echo  '<p style=text-align:center;font-size:16px; class="messageOK">Le mot de passe a bien été modifié.</p>';

        }

        
    }

    // Traitement du formulaire de mise à jour des données des infos(UPDATE)
	if (isset($_POST['pseudo'])) {
		// récupérer les valeurs du formulaire
		$pseudo = $_POST['pseudo'];
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		// Si aucun sexe n'est coché, $_POST['sexe'] n'existe pas => on met null.
		// On ne peut pas mettre '' (chaine vide) pour une date, alors on met null.
		$adressePostale = $_POST['adressePostale'];
		$telephone = $_POST['telephone'];
		// Traitement des erreurs
			// par exemple : téléphone pas au bon format
			// par exemple : pseudo non autorisé (car. interdits, trop courts, modération)
		// Effectuer la modification


		$sql = "UPDATE user SET
			usr_pseudo = :pseudo,
			usr_nom = :nom,
			usr_prenom = :prenom,
			usr_adresse_postale = :adressePostale,
			usr_telephone = :telephone
			WHERE usr_id = :usr_id";
            myPrepareExecute($sql,array(            
            'pseudo' => $pseudo,
            'nom' => $nom,
            'prenom' => $prenom,
            'adressePostale' => $adressePostale,
            'telephone' => $telephone,
            'usr_id' => $_COOKIE['user_id']));

		$messageOK = "Votre compte a bien été modifié."; 
    }


    // Affichage
    $user = myQuery("SELECT * FROM user WHERE usr_id = {$_COOKIE['user_id']}")->fetch(PDO::FETCH_ASSOC);
	$pseudo = htmlspecialchars($user['usr_pseudo'], ENT_QUOTES);
	$nom = htmlspecialchars($user['usr_nom'], ENT_QUOTES);
	$prenom = htmlspecialchars($user['usr_prenom'], ENT_QUOTES);
	$adressePostale = htmlspecialchars($user['usr_adresse_postale'], ENT_QUOTES);
	$telephone = htmlspecialchars($user['usr_telephone'], ENT_QUOTES);



?>

<style>
    table {
        align-items: center ;
        margin: 0 auto;
    }
    form {
        color:white;
        border:solid 3px white ;
        border-radius:10px;
        margin:10px;
        align-items: center ;
        margin: 0 auto;
        margin-bottom:5px;
        width:45%;
        background-color:rgba(51,51,51,0.9)
    }

    h1 {
        color:black;
        font-size:1.5em;
        margin-top:0;
        text-align:center;
        border-bottom:solid 1px gray;
        background-color:#d3d3d3;

    }

    .divEntier {
        align-items: center ;
        margin: 0 auto;
    }

    .BTModif:hover {
        cursor:pointer;
    }

</style>

<p class=messageOK style=font-size:16px;text-align:center;><?= isset($messageAvatarOK) ?$messageAvatarOK: ''; ?></p>


<div class=divEntier> <!--justify-content:center -->
    <div class=modifInfos>
        <form method=post>
            <h1>Modifier vos informations </h1>
            <table>
                <tr><td colspan=2 class=messageOK>
                    <?= isset($messageOK)?$messageOK:'  '; ?>
                <tr><td>Pseudo :<td><input name=pseudo value="<?= $pseudo; ?>" >
                <tr><td>Nom :<td><input name=nom value="<?= $nom; ?>" >
                <tr><td>Prénom :<td><input name=prenom value="<?= $prenom; ?>" >
                <tr><td>Adresse postale :<td><textarea style="min-height:50px ; max-height: 200px; ; min-width:165px; max-width: 165px;" name=adressePostale><?= $adressePostale; ?></textarea>
                <tr><td>N° de téléphone :<td><input name=telephone value="<?= $telephone; ?>" >
                <tr><td><td><input type=submit value="Modifier les informations" class="BTModif" style="font-size:12px;margin:0 auto; align-items:center; display:table;width:155px;height:20px;border-radius: 15px 50px 30px;text-align:center;padding-left:10px;background-color:rgba(0, 0, 0, 0.5);color:white;border-color:white;">
                <tr><td colspan=2 id=error class=error>
                    <?= isset($message_erreur)?$message_erreur:''; ?>
            </table>
        </form>
    </div>
    <div class=modifMdp>
        <form method=post onsubmit="return verif(this)" onkeyup="verif(this)">
            <h1>Modifier votre mot de passe </h1>
            <table>
                <tr><td colspan=2 class=messageOK>
                    <?= isset($messageOK)?$messageOK:' '; ?>
                <tr><td>Ancien mot de passe :<td><input type=password name=oldPwd>
                <tr><td>Nouveau mot de passe :<td><input type=password name=newPwd1>
                <tr><td>Confirmation du mot de passe :<td><input type=password name=newPwd2>
                <tr><td><td><input type=submit value="Modifier le mot de passe" class="BTModif" style="font-size:12px;margin:0 auto; align-items:center; display:table;width:155px;height:20px;border-radius: 15px 50px 30px;text-align:center;padding-left:10px;background-color:rgba(0, 0, 0, 0.5);color:white;border-color:white;">
                <tr><td colspan=2 id=error class=error>
                    <?=isset($message_erreur_mdp)?$message_erreur_mdp:''; ?>
            </table>
        </form>
    </div>
</div>

<script>
    function verif(form) {
        noError = true
		error.innerHTML = ''
		form.newPwd1.style.boxShadow = '0px 0px 4px green'
		form.newPwd2.style.boxShadow = '0px 0px 4px green'
        if (form.newPwd1.value.length < 6) {
			error.innerHTML += 'Le mot de passe doit faire 6 caractères minimum<br>'
			noError = false
			form.newPwd1.style.boxShadow = '0px 0px 4px red'
        }
        if (form.newPwd1.value != form.newPwd2.value) {
			error.innerHTML += 'Erreur dans la confirmation du mot de passe<br>'
			noError = false
			form.newPwd2.style.boxShadow = '0px 0px 4px red'
        }

        return noError


    }


</script>


<?php include "pageEnd.php";?>