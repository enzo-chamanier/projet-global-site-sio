<?php include "pageStart.php";

// Traitement du formulaire : envoi du message par mail 
$nom= isset($_POST['nom'])?htmlspecialchars($_POST['nom']):'';
$email= isset($_POST['email'])?htmlspecialchars($_POST['email']):'';
$message= isset($_POST['message'])?htmlspecialchars($_POST['message']):'';
$message_erreur='';
if (isset($_GET['message']))
{
    $nom= $_GET['nom'];
    $email= $_GET['email'];
    $message= $_GET['message'];

    if (strlen($nom) == 0)
        $message_erreur .='Entrez un nom valide <br>';
    if (strlen($email) == 0)
        $message_erreur .='Entrez un mail valide <br>' ;
    if (strlen($message) == 0)
        $message_erreur .='Entrez un message <br>' ;
    // Pas d'erreur : on envoie le mail
    if (empty($message_erreur))
    {
        $subject = "[{$_SERVER['HTTP_HOST']}] Nouveau message";
        $headers = "Reply-To:$email\r\n";
        $result = mail('chamen.13140@gmail.com', $subject, $message,$headers);
        echo $result?1:0;
    }
    else
        echo 0;
    exit;
}

?>

<?php include "pageStartDisplay.php"; ?>

<meta charset="utf-8" >

<div class=formulaire>
    <h1 style=text-align:center;padding-top:10px;>Vous voulez nous contacter ?</h1>
    <h3 style=text-align:center;>Écrivez-nous. Nous vous répondrons très bientôt.</h3>
        <table>
            <tr><td><td>Nom* :
            <tr><td><td><input name=nom autocomplete=off style="min-width:800px; max-width: 100%;" required>
            <tr><td><td>
            <tr><td><td>
            <tr><td><td>Mail* :
            <tr><td><td><input name=email type=email style="min-width:800px; max-width: 100%;" required>
            <tr><td><td>
            <tr><td><td>
            <tr><td><td>Message* :
            <tr><td><td><textarea  name=message style="min-height:200px ; max-height: 300px; ; min-width:800px; max-width: 800px;" placeholder="Ici, vous pouvez écrire votre message"></textarea>
            <tr><td><td><button width:500px;  class=theButtonEnvoyer onclick=sendMessage()>Envoyer mon message</button>
            <tr><td><td style=font-size:80% colspan=2>* : champ obligatoire
            <tr><td colspan=2 id=error class=error>        
        </table>
</div>





<?php include "pageEnd.php"; 

?>




<script>

    function sendMessage() {
        $('#error').css('display:table-cell')
        nom = encodeURIComponent($('input[name=nom]').val())
        email = encodeURIComponent($('input[name=email]').val())
        message = encodeURIComponent($('textarea[name=message]').val())
        if (nom == '' || email == '' || message == '')
            $('#error').text('Vous devez remplir tous les champs').css('color','red').fadeOut(5000)
        else{
            $.ajax( {
                data: "nom=" + nom +"&email=" + email + "&message=" + message,
                success: function(result){
                    if(result==1)
                        $('#error').text('Votre message a bien été envoyé').css('color','green').fadeOut(5000)
                    else
                        $('#error').text('Erreur à l\'envoi du message').css('color','red').fadeOut(5000)
                }
            } )
        }
    }



</script>

<style>

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


</style>