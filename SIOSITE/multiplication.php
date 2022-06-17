<?php 
    include "pageStart.php"; 
    include "pageStartDisplay.php";
?>

<div>
<h1 style=text-align:center;> Voici un jeu qui te permet de réviser tes tables de multiplication </h1>
<?php


    // Tirer une nouvelle réponse
    $nb1 = rand(2,10);
    $nb2 = rand(2,10);
    echo "<form style=text-align:center>
        $nb1 x $nb2 = <input type=text name=reponse autofocus placeholder='Votre réponse'>
        <input type=hidden name=nb1 value=$nb1>
        <input type=hidden name=nb2 value=$nb2>
        <input type=submit>
        </form>";

    if (isset($_GET['reponse'])){
        // vérifier la reponse
        $nb1 =$_GET['nb1'];
        $nb2 =$_GET['nb2'];
        $rep = $_GET['reponse'];
        if ($rep == $nb1 * $nb2)
            echo "<p id=bravo style=text-align:center;color:green;>Bravo !</p>";
        else
            echo "<p style=text-align:center><span style=color:red;>Faux !</span> Va réviser tes maths ! $nb1 x $nb2 = ".($nb1*$nb2)."</p>";
    }

?>

</div>

<script>
    $('#bravo').fadeOut(2000); //Hide children by default
</script>
<?php include "pageEnd.php" ;?>  