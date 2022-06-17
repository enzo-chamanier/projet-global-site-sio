<?php if(!isset($__PAGESTART__)) include "pageStart.php"; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title><?php echo isset($__TITLE)?$__TITLE: "Accueil";?></title>
</head>

<body>
	<video autoplay muted loop="infinite" id="myVideo">
		<source src="pictures/video_fond.mp4" type="video/mp4">
	</video>
	<script>
		var myVideo = document.getElementById("bgvid");

		function playVid() {
		myVideo.play();
		}

		function pauseVid() {
		myVideo.pause();
		}
	</script>

	<div id=menuH> 
			<div class="navbar">
				<div class="menuH-gauche">
					<?php if(is_admin()) {
						echo "<a href=admin.php style=background:#b3a16d;><b>Panel d'admin</b></a>";
					} ?> 
					<?php if (is_authentified()) { ?>
						<a href=index.php>Home</a>
						<a href=boutique.php>Boutique</a>
						<a href=contact.php>Contact</a>
						<a href=compte.php>Mon compte</a>
						<a href=deconnexion.php>Déconnexion</a>
					<?php } else { ?>
						<a href=index.php>Home</a>
						<a href=boutique.php>Boutique</a>
						<a href=contact.php>Contact</a>
					<?php } ?>
				</div>

				<div class="menuH-droite" style=float:right;>
					<?php if (is_authentified()) { ?>
						<a href="pannier.php" style=display:inline-block><img style=width:19.5px; src="pictures/pannier.png"></a>
					<?php } else { ?>
						<a href="authentification.php" style=display:inline-block><img style=width:20px; src="pictures/auth.png"></a>
						<a href="pannier.php" style=display:inline-block><img style=width:19.5px; src="pictures/pannier.png"></a>
					<?php } ?>
				</div>
			</div>
	</div>

	<div id=main>
		<div id=contenu>