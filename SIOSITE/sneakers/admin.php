<?php
/* 0 => simple mortel
    1 => utilisateur prenium
    2 => modérateur
    3 => Dieu l'Administrateur
*/

    include "pageStart.php" ;
    if (!is_admin()) {
        header('location:index.php'); exit();}

    if (isset($_POST['droit'])) // Met à jour les droits
        foreach ($_POST['droit'] as $usr_id=>$droit)
            myQuery("UPDATE user SET usr_droit = $droit WHERE usr_id=$usr_id");
    if (isset($_POST['valide'])) // Valide les mails
        foreach ($_POST['valide'] as $usr_id=>$valide)
            myQuery("UPDATE user SET usr_mail_valide = 1 WHERE usr_id=$usr_id");




    // Construction de la liste des utilisateurs
    $rs_users = myQuery('SELECT usr_id, usr_droit, usr_login, usr_mail_valide FROM user ORDER BY usr_droit DESC, usr_login');
    $liste_users='<table><tr><th>Pseudo<th>Droit<th>Mail valide ?';
    while ($user = $rs_users->fetch(PDO::FETCH_ASSOC)) {
        $id = $user['usr_id'];
        $droit = $user['usr_droit'];
        $pseudo = $user['usr_login'];
        $mail_valide = $user['usr_mail_valide'];
        $liste_users.= "<tr><td>$pseudo : <td><select name=droit[$id] data-oldDroit=$droit>
            <option value=0".($droit==0?' selected':'').">Simple utilisateur
            <option value=1".($droit==1?' selected':'').">Utilisateur prenium
            <option value=2".($droit==2?' selected':'').">Modérateur
            <option value=3".($droit==3?' selected':'').">Administrateur
        </select>
            <td><input type=checkbox name=valide['$id']".($mail_valide?' checked disabled':'').">";
    }
    $liste_users.="</table>";

    $marque= isset($_POST['marque'])?htmlspecialchars($_POST['marque']):'';
    $nom= isset($_POST['nom'])?htmlspecialchars($_POST['nom']):'';
    $couleur= isset($_POST['color'])?htmlspecialchars($_POST['color']):'';
    $prix= isset($_POST['prix'])?htmlspecialchars($_POST['prix']):'';
    $devise= isset($_POST['devise'])?htmlspecialchars($_POST['devise']):'';
    $stock= isset($_POST['stock'])?htmlspecialchars($_POST['stock']):'';
    $description= isset($_POST['description'])?htmlspecialchars($_POST['description']):'';
    $messageOK='';
    $message_erreur='';
    if (isset($_POST['description']))
    {
        $marque=$_POST['marque'];
        $nom= $_POST['nom'];
        $couleur= $_POST['color'];
        $prix= $_POST['prix'];
        $devise= $_POST['devise'];
        $stock= $_POST['stock'];
        $description= $_POST['description'];
        
        
        if (strlen($nom) == 0)
            $message_erreur .='Entrez un nom<br>';
        if (intval($prix) == 0)
            $message_erreur .='Entrez un prix<br>' ;
        if (strlen($devise) == 0)
            $message_erreur .='Entrez une devise<br>' ;
        if (intval($stock) == 0)
            $message_erreur .='Entrez un stock<br>' ;
        if (strlen($description) == 0)
            $message_erreur .='Entrez une description<br>' ;
        if (empty($message_erreur))
        {
            $sql = "INSERT INTO produit (prod_desc, prod_marque, prod_nom, prod_couleur, prod_prix, prod_devise, prod_stock) VALUES (:description,:marque,:nom,:color,:prix,:devise,:stock)";
            myPrepareExecute($sql, array(':description'=>$description,':marque'=>$marque, ':color'=>$couleur, ':nom'=>$nom, ':prix'=>$prix, ':devise'=>$devise, ':stock'=>$stock));
            $messageOK="<p style=text-align:center;>Votre produit a été ajouté à la base de données.</p>";
        }

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
    }

    

    include "pageStartDisplay.php";
?>

<style>

.pannelUsers{
    text-align:center;
    
}

table {
        align-items: center ;
        margin: 0 auto;
}

form {
    color:white;
    border:solid 3px white ;
    border-radius:10px;
    align-items: center ;
    margin: 0 auto;
    margin-bottom:5px;
    width:45%;
    background-color:rgba(51,51,51,0.9);
    display:table;


}

h1 {
    color:black;
    font-size:1.5em;
    margin-top:0;
    text-align:center;
    border-bottom:solid 1px gray;
    background-color:#d3d3d3;
}

select {
    height:21.9px;
}

.divEntier {
    align-items: center ;
    margin: 0 auto;
}

.pannelUsers {
}

</style>




<div class=divEntier> <!--justify-content:center -->
    <div class=pannelUsers style=padding-top:10px;>
        <form method=post style=padding-bottom:10px;width:685px;>
            <h1>Panel d'administration</h1>
                <?= $liste_users; ?>
            <input type=submit value="Mettre à jour" style=text-align:center;>
        </form>
    </div>
    <div class=ajoutProduit>
        <form method=post onsubmit="return verif(this)" onkeyup="verif(this)" style=text-align:center;width:685px;>
            <h1>Ajout d'un produit </h1>
            <table>
                <tr><td colspan=2 class=messageOK>
                    <?= isset($messageOK) ?$messageOK: ''; ?>
                <tr><td>Marque du produit* :<td>Nom du produit* :<td>Couleur principale* :
                <tr><td><select name="marque" style=text-align:center; required>
                                    <option value="">-Sélectionner une marque-</option>
                                    <option value="NIKE">Nike</option>
                                    <option value="ADIDAS">Adidas</option>
                                    <option value="VANS">Vans</option>
                                    <option value="REBOOK">Rebook</option>
                                    <option value="TOMMY HILFIGER">Tommy Hilfiger</option>
                                    <option value="CALVIN KLEIN">Calvin Klein</option>
                                    <option value="AUTRE MARQUE">Autre...</option>
                                </select>
                    <td><input name=nom autocomplete=off style="min-width:269px; max-width: 100%;text-align:center;box-sizing:border-box;" placeholder="Nommer le produit"
                        <?= isset($_POST['nom_prod'])?"value={$_POST['nom_prod']}":""; ?>>
                    <td><input type=color name=color style="width:150px;height:23px;">
            </table>    
            <table>
                <tr><td><td>
                <tr><td><td>
                <tr><td>Prix du produit* :<td>Devise* :
                <tr><td><input type=number name=prix  style="min-width:422px; max-width: 100%;text-align:center;" min="0" value="0"step=".01" required>
                <td><select name="devise" style=text-align:center; required>
                                    <option value="">-Sélectionner une devise-</option>
                                    <option value="EUR(€)">EUR(€)</option>
                                    <option value="USD($)">USD($)</option>
                                    <option value="YEN(¥)">YEN(¥)</option>
                                    <option value="GBP(£)">GBP(£)</option>
                                    <option value="AUD($A)">AUD($A)</option>
                                    <option value="CAD($C)">CAD($C)</option>
                                    <option value="CHF(CH₣)">CHF(CH₣)</option>
                    </select>
            </table>
            <table>
                <tr><td><td>
                <tr><td><td>
                <tr><td><td>Stock* :
                <tr><td><td><input  type=number name=stock style="min-width:600px; max-width: 100%;text-align:center;" min="0" value="0" required>
                <tr><td><td>
                <tr><td><td>
                <tr><td><td>Sa description* :
                <tr><td><td><textarea  name=description style="min-height:30px ; max-height: 300px; ; min-width:600px; max-width: 800px;text-align:center;" placeholder="Décrivez votre produit"></textarea>
                <tr><td><td><input width:500px;  class=theButtonEnvoyer type=submit value="Ajouter le produit">
                <tr><td><td style=font-size:80% colspan=2>* : champ obligatoire
                <tr><td colspan=2 id=error class=error>
                    <?= isset($message_erreur)?$message_erreur:''; ?>  
            </table>
        </form>
    </div>
</div>


<?php include "pageEnd.php";