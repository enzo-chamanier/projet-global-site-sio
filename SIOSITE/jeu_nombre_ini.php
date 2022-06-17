<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeux nombre ini</title>
</head>
<body>
    
<?php include "pageStart.php"; 
    include "pageStartDisplay.php";
    if (!isset($_POST['reponse'])){
        $nbrTirer = rand(1,100);
        $nbEssaie = 0;
        setcookie("nbEssaie",$nbEssaie);
        setcookie("nbrTirer",$nbrTirer);
        }

    else 
    {
        $nbrChoisi = $_POST['reponse'];
        $nbrTirer = $_COOKIE['nbrTirer'];
        $nbEssaie = $_COOKIE['nbEssaie'];
        
        if ($nbrTirer == $nbrChoisi)
        {
            $nbEssaie++;
            echo "<p style=text-align:center;><b><span style=color:green;>Bravo !</span></b> Vous l'avez trouvé au bout de {$nbEssaie} essaie(s)</p>";
            echo "<p style=text-align:center;><a style=text-decoration:none;color:black;text-align:center;  href=jeu_nombre_ini.php><button style=background-color:#54ff82; class=theButton>Recommencer</button></a></p>";
            setcookie('nbrEssaie',0);
        }

        else 
        {
            if ($nbrTirer > $nbrChoisi)
            {
                $nbEssaie++;
                echo "<p style=text-align:center> Le nombre caché est plus grand ! Réessayez !</p>";
            }
            else {
                $nbEssaie++;
                echo "<p style=text-align:center>Le nombre caché est plus petit ! Réessayez !</p>";
                
            } 
        }
        setcookie('nbEssaie',$nbEssaie);
    
    }


    
    // Tirer une nouvelle réponse
    echo "<form method=post style=text-align:center;padding-bottom:50px;>
        <h1>Devine le nombre caché</h1>
        <h2>J'ai choisi un nombre entre 1 et 100, essayez de le deviner : </h2>
        <input type=text name=reponse ".(isset ($_POST['reponse']) ? "placeholder={$_POST['reponse']}":'')." autofocus>
        <input type=submit value='Essayer !'>
        </form>";




include "pageEnd.php";
?>

</body>
</html>