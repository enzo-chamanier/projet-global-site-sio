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
            <option value=0".($droit==0?' selected':'').">Simple mortel
            <option value=1".($droit==1?' selected':'').">Utilisateur prenium
            <option value=2".($droit==2?' selected':'').">Modérateur
            <option value=3".($droit==3?' selected':'').">Administrateur
        </select>
            <td><input type=checkbox name=valide['$id']".($mail_valide?' checked disabled':'').">";
    }
    $liste_users.="</table>";
    include "pageStartDisplay.php";
?>

<style>

.users_panel{
    padding-top:50px;
    display: table;
    align-items: center ;
    margin: 0 auto;
    border:2px solid white;
}

</style>


<form method=post class=users_panel>
    <h2>Panel d'administration</h2>
    <?= $liste_users; ?>
    <input type=submit>
</form>



<?php include "pageEnd.php";