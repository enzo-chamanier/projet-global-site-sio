<?php 
    include "pageStart.php";

    // Traitement AJAX
    if (isset($_GET['checklogin'])) {
        $rs = myPrepareExecute('SELECT usr_id FROM user WHERE usr_login = :login ', array('login' => $_GET['checklogin']));
        echo $rs ->rowCount();
        exit;
    }
    if (isset($_GET['checkmail'])) {
        $rs = myPrepareExecute('SELECT usr_id FROM user WHERE usr_mail = :mail ', array('mail' => $_GET['checkmail']));
        echo $rs ->rowCount();
        exit;
    }



    // Validation du mail
    if (isset($_GET['valide'])) {
        $usr_id = $_GET['valide'];
        // Est-ce que le "valide" a été truqué ?
        if (myHash($usr_id, 'VMAIL') != $_GET['valide_hash'])
            $message_erreur = "Tentative de piratage détecté !";
        else {
            myQuery("UPDATE user SET usr_mail_valide = 1 WHERE usr_id = $usr_id");
            header("location:authentification.php?valide=");
        }
    }



    // Traitement de l'inscription
    $message_erreur = '';
    if (isset($_POST['login'])) {
        // Récupère les variables du formulaire
        $login = $_POST['login'];
        $mail = $_POST['mail'];
        $pwd1 = $_POST['pwd1'];
        $pwd2 = $_POST['pwd2'];
        $ip = $_SERVER['REMOTE_ADDR'];
        // On vérifie si le login ou le mail sont déjà pris
        $loginExists = myQuery("SELECT usr_id FROM user WHERE usr_login='$login'")->rowCount();
        $mailExists = myQuery("SELECT usr_id FROM user WHERE usr_mail='$mail'")->rowCount();
        // On vérifie chaque erreur possible


        if ($loginExists)
            $message_erreur .= 'Ce login est déjà pris<br>';
        if (strlen($login) < 4) // Login trop court
            $message_erreur .= 'Ce login est trop court (4 caractères minimum)<br>';
        if ($mailExists)
            $message_erreur .= 'Cette adresse mail est déjà utlisé.
            <a href=mdpo.php> Mot de passe oublié ?</a><br>';
        if ($pwd1 != $pwd2) // Mot de passe mal confirmé 
            $message_erreur .= 'Erreur dans la confirmation du mot de passe';
        if (strlen($pwd1) < 6) // Mot de passe trop court
            $message_erreur .= 'Le mot de passe doit faire au moins 6 caractères <br>';
        if (strpos($pwd1,$login) !== false) // Si on trouve le login dans le mot de passe 
            $message_erreur .= 'Le mot de passe ne doit pas contenir le login<br>';

        if (empty($message_erreur)){    // On réalise effectivement l'inscription
            $pwd1=myHash($pwd1 , 'PWD');
            $sql = "INSERT INTO user (usr_login, usr_mail, usr_password, usr_ip_inscription) VALUES ('$login','$mail','$pwd1','$ip')";
            myQuery($sql);
            echo "<p style=color:white;text-align:center;>Tout c'est bien passé, vous êtes bien inscrit.<br>
            Cliquez sur le lien reçu par mail pour confirmer votre adresse électronique.</p>";

            // Préparation du mail à envoyer puis l'envoie du mail 
            $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
            $usr_id = $CO->lastInsertId();
            $usr_id_hash = myHash($usr_id,'VMAIL'); 

            $body = "Bonjour,
Merci de vous être inscrit sur notre site.
Cliquez ici pour valider votre adresse email :
            $url?valide=$usr_id&valide_hash=$usr_id_hash";
            $subject = "[{$_SERVER['HTTP_HOST']}] Validation mail";
            mail($mail, $subject, $body);

        }
    }
    include "pageStartDisplay.php"; 
?>

<style>

.formInscr {
    color:white;
    padding:50px;
    display: table;
    align-items: center ;
    margin: 0 auto;
}

#mentions {
    text-align:center;
    padding-bottom:50px;
    padding-left:500px;
    padding-right:500px;
    display:none;
}
.mentions_legales {
    cursor:pointer;
}

.mentions_legales_form {
    cursor:pointer;
    text-decoration:underline;
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

::placeholder {
    color:white;
}

#sinscrire:hover{
    cursor:pointer;
}



</style>

<div class=formInscr>
    <form method="post" onsubmit="return verif(this)" onkeyup="verif(this)">
        <h2 style=text-align:center;><a href="authentification.php" class="cursor-text-connexion">Connexion</a> | <a href="inscription.php" class="cursor-text-inscription">Inscription</a></h2>
        <table>
            <tr><td><td>
            <tr><td><td>
            <tr><td><td><input name=login autocomple=off style="width:300px;height:20px;border-radius: 15px 50px 30px;text-align:center;padding-left:10px;background-color:rgba(0, 0, 0, 0.5);color:white;border-color:white;" placeholder="LOGIN*">
                <td rowspan=6 class=error id=errorLogin>
            <tr><td><td>
            <tr><td><td>
            <tr><td><td><input name=mail type=email autocomplete=off style="width:300px;height:20px;border-radius: 15px 50px 30px;text-align:center;padding-left:10px;background-color:rgba(0, 0, 0, 0.5);color:white;border-color:white;" placeholder="MAIL*">
                <td rowspan=6 class=error id=errorMail>
            <tr><td><td>
            <tr><td><td>
            <div style=display:flex;>
                <div style=positon:absolute;><tr><td><td><input type=password name=pwd1 style="width:300px;height:20px;border-radius: 15px 50px 30px;text-align:center;padding-left:10px;background-color:rgba(0, 0, 0, 0.5);color:white;border-color:white;" placeholder="PASSWORD*" onkeyup="calculComplex(this)"></div>
                <div style=positon:absolute;><tr><td><td><div id=complexBar style="  float:right;width:30px;height:15px;border:2px solid black;"></div><div>
            </div>
                <br><span id=complex></span>
            <tr><td><td><input type=password name=pwd2 style="width:300px;height:20px;border-radius: 15px 50px 30px;text-align:center;padding-left:10px;background-color:rgba(0, 0, 0, 0.5);color:white;border-color:white;" placeholder="CONFIRM PASSWORD*">
            <tr><td><td><input id=sinscrire style="margin:0 auto; align-items:center; display:table;width:150px;height:20px;border-radius: 15px 50px 30px;text-align:center;padding-left:10px;background-color:rgba(0, 0, 0, 0.5);color:white;border-color:white;" type=submit value="S'inscrire"><br>
            <tr><td><td class=mentions_legales_form style=font-size:80%;text-align:center;color:white>Consulter les mentions légales
            <tr><td><td style=font-size:80%;text-align:center;color:white>* : champ obligatoire
        </table>
    </form>
</div>

<div  id=mentions>
    <h2 class=mentions_legales>Mentions légales</h2>
    <div>
        <p>Les données traitées dans ce formulaire font l’objet d’un traitement informatique dont RGPD-Experts®AC2R est responsable de traitement. La base légale du traitement est notre intérêt légitime hors inscription à la newsletter pour lequel votre consentement est requis. La finalité est prendre contact avec vous pour répondre à vos sollicitations Les données sont conservées 3 ans à compter du dernier contact émanent du prospect. Conformément à la loi Informatique et libertés du 6 janvier 1978 modifiée et au règlement général sur la protection des données, vous disposez d’un droit d’accès, d’interrogation, de limitation, de portabilité, d’effacement, de modification et de rectification des informations vous concernant. Vous disposez également d’un droit d’opposition pour motif légitime au traitement de vos données à caractère personnel. Enfin, vous disposez du droit de définir des directives générales et particulières définissant la manière dont vous entendez que soient exercés, après votre décès, ces droits. Vous pouvez exercer ces droits à l’adresse suivante : enzo.chamanier.pro@gmail.com ou par courrier postal à Lycée Théodore Aubanel, 14 Rue Palapharnerie, 84025 Avignon. En cas de doute sur votre identité, il pourrait vous être demandé une copie d’un titre d’identité signé. Enfin, vous avez le droit d’introduire une réclamation auprès de la Commission nationale de l’informatique et des libertés, autorité de contrôle en charge du respect des obligations en matière de protection des données à caractère personnel si vous estimez que nous n’avons pas traité vos données conformément à la règlementation en vigueur.</p>
    </div>
</div>

<script>
        // Affichage des mentions légales d'une page
        $(document).ready(function(){
             $('.mentions_legales_form').click(function() {
				$('#mentions').toggle("slide");
             });
        });
</script>
<script>
        // Calcul de la complexité du mot de passe
        function calculComplex() {
            mdp = $('input[name=pwd1]').val()
            min = 0
            maj = 0
            nbr =0
            spe = 0
            spe128 = 0
            for (iCar = 0 ; iCar < mdp.length ; iCar ++)
                if (mdp[iCar] >= 'a' && mdp[iCar] <= 'z') min = 1
                else if(mdp[iCar] >= 'A' && mdp[iCar] <= 'Z') maj = 1
                else if(mdp[iCar] >= '0' && mdp[iCar] <= '9') nbr = 1
                else if(mdp[iCar] >= ' ' && mdp[iCar] <= '⌂') spe = 1
                else spe128 = 1
            jeuCar = min * 26 + maj * 26 + nbr * 10 + spe * 24 + spe128 * 160
            lisseur = 0.1
            complexite = (jeuCar ** mdp.length)**lisseur 
            complexite = Math.min(complexite,96)/96*100
            if (complexite < 1.05){
                 $('#complexBar').css({ 'background': 'none' });
            } else
            if (complexite <= 5){
                 $('#complexBar').css({ 'background': 'red' });
            } else
            if (complexite <= 15){
                 $('#complexBar').css({ 'background': '#ff5900' });
            } else
            if (complexite <= 40){
                 $('#complexBar').css({ 'background': '#ff9f63' });
            } else
            if (complexite <= 80){
                 $('#complexBar').css({ 'background': '#9aff75' });
            } else
            if (complexite = 100){
                 $('#complexBar').css({ 'background': '#44ff00' });
            }

        



        }


        // Test en AJAX de la disponibilité du login (appel AJAX)
        var checkLogin = 1;
        $("input[name='login']").on('keyup', function () {
        $.ajax( {
            // 'type': 'get' // valeur par défaut
            // 'url' : 'page.php' // par défaut : rappelle la même page
            // 'asynch' : 'true' // par défaut (JS continue à s'exécuter en attendant)
            'data' : 'checklogin=' + encodeURIComponent($(this).val()),
            'success' : function (result) {// plannifie la réaction à la réponse du serveur 
                checkLogin = parseInt(result)
                if(result ==0)
                    $('#errorLogin').text('Login disponible').css('color','green')
                else 
                    $('#errorLogin').text('Ce login est déjà utlisé').css('color','red')
            }
        })
    })

         // Test en AJAX de la saisie du mail (appel AJAX)
        var checkMail = 1;
        $("input[name='mail']").on('keyup', function () {
        $.ajax( {
            // 'type': 'get' // valeur par défaut
            // 'url' : 'page.php' // par défaut : rappelle la même page
            // 'asynch' : 'true' // par défaut (JS continue à s'exécuter en attendant)
            'data' : 'checkmail=' + encodeURIComponent($(this).val()),
            'success' : function (result) {// plannifie la réaction à la réponse du serveur 
                checkMail = parseInt(result)
                if(result ==0)
                    $('#errorMail').text('Ce mail est valide').css('color','green')
                else 
                    $('#errorMail').text('Ce mail est déjà utilisé').css('color','red')
            }
        })
    })


    function verif(form) {
		noError = true
		error.innerHTML = ''
		form.login.style.boxShadow = '0px 0px 4px green'
		form.mail.style.boxShadow = '0px 0px 4px green'
		form.pwd1.style.boxShadow = '0px 0px 4px green'
		form.pwd2.style.boxShadow = '0px 0px 4px green'

        if (checkLogin == 0) {
			noError = false
			form.login.style.boxShadow = '0px 0px 4px green'
        }
        if (checkLogin == 1) {
			noError = false
			form.login.style.boxShadow = '0px 0px 4px red'
        }
        if (checkMail == 0) {
			noError = false
			form.mail.style.boxShadow = '0px 0px 4px green'
        }
        if (checkMail == 1) {
			noError = false
			form.mail.style.boxShadow = '0px 0px 4px red'
        }
		if (!/^[a-zA-Z0-9_-]+$/.test(form.login.value)) {
			error.innerHTML += 'Le login contient des caractères interdits<br>'
			noError = false
			form.login.style.boxShadow = '0px 0px 4px red'
		}
		if (form.pwd1.value != form.pwd2.value) {
			error.innerHTML += 'Erreur dans la confirmation du mot de passe<br>'
			noError = false
			form.pwd2.style.boxShadow = '0px 0px 4px red'
		}
		if (form.pwd1.value.length < 6) {
			error.innerHTML += 'Le mot de passe doit faire 6 caractères minimum<br>'
			noError = false
			form.pwd1.style.boxShadow = '0px 0px 4px red'
		}
		if (form.pwd1.value.indexOf(form.login.value) != -1) {
			error.innerHTML += 'Le mot de passe ne doit pas contenir le login<br>'
			noError = false
			form.pwd1.style.boxShadow = '0px 0px 4px red'
		}
		if (form.login.value.length < 4) {
			error.innerHTML += 'Le login doit faire 4 caractères minimum<br>'
			noError = false
			form.login.style.boxShadow = '0px 0px 4px red'
		}
		if (form.mail.value.length == 0) {
			error.innerHTML += 'Vous devez saisir une adresse mail<br>'
			noError = false
			form.mail.style.boxShadow = '0px 0px 4px red'
		}
		return noError
	}
</script>


<?php include "pageEnd.php";?>
