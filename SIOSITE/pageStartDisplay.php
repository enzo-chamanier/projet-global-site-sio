<?php if(!isset($__PAGESTART__)) include "pageStart.php"; ?>

<!--CDN Jquery-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title><?php echo isset($__TITLE)?$__TITLE: "Accueil";?></title>
</head>

<style>
	.imageIconMenu {
		cursor: pointer;
		margin-left:40px;
		margin-top:13px;
		margin-right:9.5%;
		top:0;
		left:0;
		width:60px;
		height:auto;
		transition:0.2s;
	}


	.imageIconMenu:hover {
		transform: scale(1.01); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */

	}

	.rightImage {
	margin-left:11.5%;

}
</style>

<body>
	<script>
        $(document).ready(function(){
             $('.imageIconMenu').click(function() {
				$('#menuV').toggle("slide");
             });
        });
    </script>


	<div id=banniere style=height:160px> 
		<img src="images/menu_icone.png" class=imageIconMenu>	

		<p id=logo style=position:relative;text-align:center;>Welcome to Enzo's Company</p>
		
		<div class=rightImage style=text-align:right;>
			<a href=compte.php><div style=text-align:right;><?php displayAvatar();?></div></a>
		</div>
	</div>

	<div id=menuH> 
		<div class=placementBoutons>
			<a style=text-decoration:none;color:black href="index.php"><button class=theButtonMenu>Accueil</button></a>
			<a style=text-decoration:none;color:black href="contact.php"><button class=theButtonMenu>Nous contacter</button></a>
		</div>	
	</div>

	<div id=main>
	
		<div id=menuV>
			<?php if (is_admin()) { 
					echo "<a style=text-decoration:none;color:black;  href=admin.php><button class=theButton style=background-color:#ff6161;><b>Panel d'admin</b></button></a><br>";
			} ?>
			
			<?php if (is_authentified()) { ?>
				<a style=text-decoration:none;color:black  href=compte.php><button class=theButton>Mon compte</button></a><br>
				<a style=text-decoration:none;color:black  href=deconnexion.php><button class=theButton>Déconnexion</button></a><br><hr>
			<?php } else { ?>
				<a style=text-decoration:none;color:black href=inscription.php><button class=theButton>Inscription</button></a><br>
				<a style=text-decoration:none;color:black href=authentification.php><button class=theButton>Connexion</button></a><br>
			<?php } ?>

			<hr>
			 Collection <br>Concessionnaires<br>Réparateurs<br>
			 <hr>
			 <u>Fonctions :</u><br>
			
			 <a style=text-decoration:none;color:black href=jeu_nombre_ini.php id="jeux_nombre_cache"><button class=theButton>Nombre caché</button></a><br>
			 <a style=text-decoration:none;color:black href=multiplication.php id="jeux_multiplication"><button class=theButton>Multiplier</button></a><br>
			 <a style=text-decoration:none;color:black href=instantHtml.php id="instant_html"><button class=theButton>HTML Instant</button></a><br>
			 <a style=text-decoration:none;color:black href=films.php id="lesFilms"><button class=theButton>Les films</button></a><br>
			 <a style=text-decoration:none;color:black href=villes.php id="lesVilles"><button class=theButton>Les villes</button></a><br>
			 <hr>
			 <a style=text-decoration:none;color:black href=livredor.php id="livre_dor"><button class=theButton>Livre d'or</button></a><br>
		</div>
            
		<div id=contenu>