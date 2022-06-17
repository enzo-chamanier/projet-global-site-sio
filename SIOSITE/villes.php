<?php
	$__TITLE = "Les villes";
	include "pageStart.php";
	$CO_villes = new PDO('mysql:dbname=villes;host=bts-sio.com', 'sio', 'sio');
		
	// Traitements AJAX
	if (isset($_GET['dep'])) {
		// Créer la liste des Villes
		$dep = intval($_GET['dep']);
		$rs_villes = myQuery("SELECT * FROM ville
			WHERE ville_departement = $dep
			ORDER BY ville_population_2012 DESC", $CO_villes);
		$tab_villes = $rs_villes->fetchAll(PDO::FETCH_ASSOC);
		$listeVilles = "<br><table class=LstDep border><tr><th>Nom<th>Population\n";
		foreach($tab_villes as $ville) {
			$nom = utf8_encode($ville['ville_nom_reel']);
			$pop = $ville['ville_population_2012'];
			$listeVilles .= "<tr><td>$nom<td>$pop\n";
		}
		$listeVilles .= "</table>\n";
		echo $listeVilles;
		exit;
	}
	
	include "pageStartDisplay.php";?>
	
<style>
    .LstDep {
		background-color:white;
        display: table;
        align-items: center ;
        margin: 0 auto;
    }

</style>



<?php

	// Affichages
	// Créer la liste des départements
	$rs_departements = myQuery("SELECT * FROM departement ORDER BY dep_id;", $CO_villes);
	$tab_departements = $rs_departements->fetchAll(PDO::FETCH_ASSOC);
	$listeDépartements = "
    <h1 style=text-align:center>Recherche les villes d'un département </h1>
    <hr style=width:500px>
    <h2 style=text-align:center> Les départements : </h2>
    <select class=LstDep name=listeDep onchange=searchVilles()>
    \n";
	foreach($tab_departements as $departement) {
		$id = $departement['dep_id'];
		$nom = utf8_encode($departement['dep_nom']);
		$listeDépartements .= "<option value=$id>$id - $nom\n";
	}
	$listeDépartements .= "</select>\n";
	
	echo $listeDépartements;
	echo "<div id=listeVilles></div>";
	
	include "pageEnd.php";
?>
<script>
	function searchVilles() {
		dep = $('select[name=listeDep]').val()
		$.ajax( {
			data : 'dep=' + dep,
			success : function (result) {
				$('#listeVilles').html(result)
			}
		})
	}
</script>




