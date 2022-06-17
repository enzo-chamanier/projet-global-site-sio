<?php include "pageStart.php";
// Traitement AJAX : suppression d'un message
if (isset($_GET['suppMessId']) && is_admin()){
    myQuery("DELETE FROM livredor WHERE ldor_id = {$_GET['suppMessId']}");

    exit();

}

// Traitement AJAX : envoyer les prochains messages
if(isset($_GET['indexMessages'])){
   
$listeMessages='';
$rs_messages = myPrepareExecute("SELECT *
    FROM livredor ORDER BY ldor_date DESC LIMIT {$_GET['indexMessages']},5");

while ($messages=$rs_messages->fetch(PDO::FETCH_ASSOC))
{
    $id =$messages['ldor_id'];
    $nom = htmlspecialchars($messages['ldor_nom']);
    $date = $messages['ldor_date'];
    $lienSuppMsg= is_admin()?"<span onclick=suppMessage($id) class=cross><img style=width:10px;height:auto;padding-bottom:20px; src='https://cdn-icons-png.flaticon.com/128/594/594864.png'></span>":'';
    $message = htmlspecialchars($messages['ldor_message']);
        $listeMessages.="
        <div class=Message id=msg$id>
            <div>
                <h2>De <span style=max-width:60px;color:blue;>$nom </span><span style=font-size:0.6em;><br>le $date</span></h2>
                <hr>
                $message
            </div>
            <br>
            $lienSuppMsg

        </div>";
    }


    echo nl2br($listeMessages);
    exit;
}


$nom= isset($_POST['nom'])?htmlspecialchars($_POST['nom']):'';
$email= isset($_POST['email'])?htmlspecialchars($_POST['email']):'';
$message= isset($_POST['message'])?htmlspecialchars($_POST['message']):'';
$messageOK='';
$message_erreur='';
if (isset($_POST['message']))
{
    $nom= $_POST['nom'];
    $email= $_POST['email'];
    $message= $_POST['message'];

    if (strlen($nom) == 0)
        $message_erreur .='Entrez un nom valide <br>';
    if (strlen($email) == 0)
        $message_erreur .='Entrez un mail valide <br>' ;
    if (strlen($message) == 0)
        $message_erreur .='Entrez un message <br>' ;
    if (empty($message_erreur))
    {
        $sql = "INSERT INTO livredor (ldor_nom, ldor_email, ldor_message) VALUES (:nom,:email,:message)";
        myPrepareExecute($sql, array(':nom'=>$nom,':email'=>$email, ':message'=>$message ));
        $messageOK="<p style=text-align:center;>Votre message a bien été posté dans notre livre d'or, merci !</p>";
    }
}

?>

<?php include "pageStartDisplay.php"; ?>

<meta charset="utf-8" >

<div class=formulaire>
    <h1 style=text-align:center;padding-top:10px;>Voici le livre d'or du site</h1>
    <h3 style=text-align:center;>Vous pouvez donner un avis sur notre site.</h3>
    <form method=post style="min-width:800px; max-width:auto ; max-height:500px">
        <table>
            <tr><td colspan=2 class=messageOK>
                <?= isset($messageOK) ?$messageOK: ''; ?>
            <tr><td><td>Nom* :
            <tr><td><td><input name=nom autocomplete=off style="min-width:800px; max-width: 100%;"
                <?= isset($_POST['login'])?"value={$_POST['login']}":""; ?>>
            <tr><td><td>
            <tr><td><td>
            <tr><td><td>Mail* :
            <tr><td><td><input name=email type=email style="min-width:800px; max-width: 100%;" required>
            <tr><td><td>
            <tr><td><td>
            <tr><td><td>Message* :
            <tr><td><td><textarea  name=message style="min-height:200px ; max-height: 300px; ; min-width:800px; max-width: 800px;" placeholder="Ici, vous pouvez écrire votre message"></textarea>
            <tr><td><td><input width:500px;  class=theButtonEnvoyer type=submit value="Envoyer mon message">
            <tr><td><td style=font-size:80% colspan=2>* : champ obligatoire
            <tr><td colspan=2 id=error class=error>
                <?= isset($message_erreur)?$message_erreur:''; ?>
                
        </table>
    </form>
</div>

    <div id=listeMessages>
            
    </div>

</div>


<?php include "pageEnd.php"; 

?>




<script>

    var indexMessages
    indexMessages = 0
    allowAJAX=true
    
    $(document).ready(searchMessages);

    function suppMessage(mess_id){      // Modération/Suppression des messages
        if (confirm('MODÉRATION :\nÊtes-vous sûr de vouloir\n supprimer ce message?')){


        $.ajax({

            data: 'suppMessId=' + mess_id,
        })
        // effacer le div
        $('#msg'+mess_id).toggle(500)
    }

    }

    window.onscroll = function() 

    {

        topFenetre = $(window).scrollTop()      // Jquery
        hauteurFenetre = window.innerHeight     // javascript natif
        hauteurDocument = $(window).height()    // Jquery
        
        if (topFenetre + 1.3*hauteurFenetre > hauteurDocument)
            searchMessages()

    }



    function searchMessages() {
        if(allowAJAX){
            allowAJAX=false
            $.ajax( {

                data: "indexMessages=" + indexMessages,
                success: function(result){
                    $('#listeMessages').append(result)
                    indexMessages+=5
                    allowAJAX=true

                }
            })
        }
    }



</script>

<style>

.cross
{
    cursor:pointer;
    color:#900;
}

.theButtonEnvoyer {
	margin-left:10px;
	margin-right:10px;
	margin-top:3px;
	width: 160px;
	height:27px;
    align-items: center;
    background-color: #fff;
    border-radius: 12px;
	transition:1s;
}

.theButtonEnvoyer:hover{
	background-color: #bedef7;

}

.TitreLo
{
    border-bottom:1px solid black;
    width:100%;
    padding:5px;
    font-size:2.1em;
    margin: 0 auto;
}
.formulaire
{

    display: table;
    align-items: center ;
    margin: 0 auto;
    border: thick inset  white;

}

#listeMessages
{
    padding-top:10px;
    left:center;
    display:table;
    margin:0 auto;
    justify-content : center ; 
    align-items:center ; 
    text-align:center;
    word-wrap: break-word; /* Retourne à la ligne */

}


.Message
{
    min-width:700px ; 
    max-width:700px ; 
    border: 2mm ridge rgba(89, 173, 248, 0.8);
    margin-bottom:5px;
    padding-left:50px;
    padding-right:50px;

}

.envoiLo
{
    color:blue;
    width:130px;
    height:55px;
    font-size:1.2em;
    border-radius:20px;
    border:1px solid black;

}
.envoiLo a
{
    display: inline-block;

}

</style>