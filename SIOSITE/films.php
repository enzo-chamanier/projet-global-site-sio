<?php 
	$__TITLE = "Les films";
	include "pageStart.php";
	
	$COFilms = new PDO('mysql:dbname=films;host=bts-sio.com', 'sio', 'sio');
	if(isset($_GET['search'])) {
		//intval pour protéger de l'injection SQL : on attend un entier, on vire le restart
		$start = intval($_GET['start']); // on veut récupérer les films a partir du start(ième)
		$result = '';
		$rs_films = myPrepareExecute('SELECT film_id, film_titre, film_url_affiche, film_anneeproduction, film_synopsis FROM film WHERE film_titre
										LIKE :search ORDER BY film_anneeproduction DESC LIMIT '.$start.', 8', 
			array('search' => "%{$_GET['search']}%"), 
			$COFilms);
		$tab_films = $rs_films->fetchAll(PDO::FETCH_ASSOC);
		foreach ($tab_films as $film){
			$id = $film['film_id'];
			$titre = htmlspecialchars($film['film_titre']);
			$url_affiche = $film['film_url_affiche'];
			$anneeproduction = $film['film_anneeproduction'];
			$synopsis = $film['film_synopsis']; 
			if (preg_match("~(\W|^){$_GET['search']}(\W|s|$)~i", $titre)) { 
				$result .= "
                    <div class=film>
                    <h2 class=film>$titre ($anneeproduction)</h2>
                        <div class=image>
                            <a href=https://www.allocine.fr/film/fichefilm_gen_cfilm=$id.html target=_blank>
                                <img src=$url_affiche width = 150>
                            </a>
                        </div>
                    <div>
                        <p class=synopsis>$synopsis</p>
                </div>
        </div>";
			}
		}
		echo $result;
		exit;
	}
	
	include "pagestartDisplay.php";
?>

    <div class=formFilms>	
        <h1 style=text-align:center>Recherche de films</h1>
        <table>
            <tr><td>Le titre contient* :<td><input name=search
            <?php if(isset($_GET['search'])) echo "value={$_GET['search']}"; ?>>
            <tr><td><td><button onclick=searchLaunch()>Chercher !</button>
            <tr><td style=font-size:80% colspan=2>* : champ obligatoire
            <tr><td colspan=2 id=error class=error>
                <?= isset($message_erreur)?$message_erreur:''; ?>  
        </table>
    </div>

<h1 style=text-align:center;>Résultat(s) de la recherche</h1>
<div id=listeFilms></div>

<style>
.formFilms {
	padding:50px;
    display: table;
    align-items: center ;
    margin: 0 auto;
}

.image {
    width:auto;
    height:auto;
    
}

.film {
        
        margin-top : 5px;
        width:auto;
        background-color:white;
        border:solid 1px #888;
        padding :5px;
    }


    .film img {width:100px;}

    .film div {margin : 0 0 0 10px; }

    .film h2 {
        margin-top:0;
        font-size:120%;
    }    
      


</style>

<?php include "pageend.php"; ?>

<script>
	var indexFilm
	
	var allowSearch = true
	
	function searchLaunch() { // va chercher les premiers résultats de la recherche
		indexFilm = 0
		searchAJAX()
	}
	
	function searchAJAX() {
		if(allowSearch) {
			allowSearch = false
			search = $('input[name="search"]').val()
			$.ajax({
				'data' : 'search=' + encodeURIComponent(search)
					+ '&start=' + indexFilm,
				'success' : function(result) {
					if (indexFilm) // Poursuite d'une recherche existante : append
						$('#listeFilms').append(result)
					else // Nouvelle recherche : html (remplace les anciens résultats)
						$('#listeFilms').html(result)
						
					indexFilm += 8
					allowSearch = true
				}
			})
		}
	}
	
	window.onscroll = function() {
		topFenetre = $(window).scrollTop()
		hauteurFenetre = window.innerHeight
		hauteurDocument = $(window).height()
		if (topFenetre + 1.5 * hauteurFenetre > hauteurDocument)
			searchAJAX()
	}
	
</script>
<div id=monitor style="position:fixed;top:0;left:0;background-color:#FFA;border:solid 1px #AA0">

</div>