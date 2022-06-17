<?php include "pagestart.php";
// Traitement AJAX : suppression d'un message
if (isset($_GET['suppMessId']) && is_admin()){
    myQuery("DELETE FROM livre_d_or WHERE lor_id = {$_GET['suppMessId']}");

    exit();

}



// Traitement AJAX : envoyer les prochains messages
if(isset($_GET['indexMessages'])){
   
$listeMessages='';
$rs_messages = myPrepareExecute("SELECT *
    FROM livre_d_or ORDER BY lor_date DESC LIMIT {$_GET['indexMessages']},5");

while ($messages=$rs_messages->fetch(PDO::FETCH_ASSOC))
{
    $id =$messages['lor_id'];
    $nom = htmlspecialchars($messages['lor_nom']);
    $date = $messages['lor_date'];
    $lienSuppMsg= is_admin()?"<span onclick=suppMessage($id) class=cross>X</span>":'';
    $message = htmlspecialchars($messages['lor_message']);
        $listeMessages.="
        <div class=Message id=msg$id>
            <div>
                <h2>De <span style=max-width:60px;>$nom </span><span style=font-size:0.6em;><br>le $date </span></h2>
                $message
            </div>
            <br>
            $lienSuppMsg
        </div>";
    }


    echo $listeMessages;
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
        $sql = "INSERT INTO livre_d_or (lor_nom, lor_email, lor_message) VALUES (:nom,:email,:message)";
        myPrepareExecute($sql, array(':nom'=>$nom,':email'=>$email, ':message'=>$message ));
        $messageOK="Votre message a bien été posté dans notre livre d'or, merci !";
    }
}

?>

<?php include "pagestartDisplay.php"; ?>

<div class="corpsLor">

    <div style=display:flex;flex-wrap:wrap class=formulaire>
        <form method=post>
            <h2 style=text-align:center; class=TitreLo> Livre d'or </h2>
            <h3 style=font-size:1.3em;text-align:center;> Donnez votre avis sur le site ! </h3>
            <table>
            <p class="messageOK" id=error><?= $messageOK; ?></p>
                <tr><td>Nom: <td> <input name=nom size=51 placeholder="Nom" required>
                <tr><td>Email: <td> <input name=email type=email size=51 placeholder="Email" required>
                    <td rowspan = 6 class="error" id=error><?= $message_erreur; ?>
                <tr><td>Message: <td><textarea name=message placeholder="Entrez votre message" cols=50 rows=20></textarea>
                <tr><td><td><input class=envoiLo type=submit value=Envoyer>

            </table>
        </form>



    </div>

    <div id=listeMessages>

            
    </div>

</div>


<?php include "pageend.php"; 

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
    position:relative;
    border: 2px solid black;
    margin: 0 auto;
    padding:25px;
    border-radius:25px;
    margin-top:5px;
    background:linear-gradient(0.5turn, rgb(173, 197, 224),white,rgb(173, 197, 224));
    width:550px;
    text-align:center;

}

#listeMessages
{
    border: solid black 2px;
    width:70%;
    padding:50px;
    margin-top:30px;
    border-radius:25px;
    margin-left:10%;
    background:linear-gradient(0.5turn, rgb(173, 197, 224),white,rgb(173, 197, 224));
    word-wrap: break-word;

}


.Message
{
    min-width:500px ; 
    max-width:700px ;
    border:blue 1px solid;
    width:55%;
    padding:30px;
    border-radius:25px;
    margin-bottom:10px;
    background:linear-gradient(0.5turn, rgb(173, 200, 231),rgb(173, 200, 231));
    margin: 0 auto;
    padding-left:50px;
    padding-right:50px;
    word-break:break-all;


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
.corpsLor
{
width:100%;
border:red solid 1px;

}

</style>