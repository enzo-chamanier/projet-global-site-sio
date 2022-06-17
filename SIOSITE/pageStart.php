<?php
	$CO = new PDO("mysql:dbname=echamanier_db;host=bts-sio.com",'echamanier','echamanier');
	$gds='<fjazopgjve f7zrf54zr56ez68eddazd>';

	// Truquage du cookie ?
	if (isset($_COOKIE['user_id']))
		if (myHash($_COOKIE['user_id'], 'CO') != $_COOKIE['user_id_hash'])
			die("Tentative de piratage détecté");

	function is_authentified() {
		return isset($_COOKIE['user_id']); 
	}

	$is_admin=is_authentified()?null:false;
	function is_admin() {
		global $is_admin;
		if (is_null($is_admin)) { // C'est la première fois qu'on demande
			$req = myQuery("SELECT usr_droit FROM user WHERE usr_id = {$_COOKIE['user_id']}");
			$user = $req->fetch(PDO::FETCH_ASSOC);
			$is_admin = $user['usr_droit'] ==3 ;
		}
		return $is_admin;
	}


		
	function displayAvatar($usr_id =null, $class = ' ') {
		if (is_null($usr_id))
			$usr_id = is_authentified()?$_COOKIE['user_id'] : '0';
		if (!file_exists("avatar/$usr_id.jpg")) $usr_id = 0;
		echo "<img src=avatar/$usr_id.jpg class='avatar' style=width:65px;min-height:65px;max-height:65px>";
	}





	function myQuery($sql, $PDO=null) {
		global $CO;
		if (is_null($PDO)) $PDO=$CO;
		$result = $PDO->query($sql);
		if ($PDO->errorInfo()[1] != 0) {
			var_dump($PDO->errorInfo());
		}
		return $result;
	}

	function myPrepareExecute($sql, $param = null, $PDO=null) {
		global $CO;
		if (is_null($PDO)) $PDO = $CO; // Travaille sur la connexion par défaut
		$req = $PDO->prepare($sql);
		$result = $req->execute($param);
		if (!$result) {
			var_dump($req->errorInfo());
			echo $sql;
		}
		return $req;
	}



	function myHash($s, $diff) 
	{
		global $gds;
		return hash('sha256', $s . $gds . $diff);
	}



	


	
	$__PAGESTART__=true;
?>



