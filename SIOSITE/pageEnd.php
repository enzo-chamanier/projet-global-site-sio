	</div>
	</div>



	<div id=pied>
		Pied de page - <a href="mentions_legales.php">Mention légales et confidentialité des données</a>
	</div>
	
	
	
	
	
	<!--
	<div id=popup1>
		<div id=popup2>Ma pop-up !<br>Acceptez-vous les cookies<br><button>Oui et je ferme ma gueule</button></div>
	</div>
	-->
<style>




body{
    background-color:#5588a5;
}

.placementBoutons {
	display: table;
    align-items: center ;
    margin: 0 auto;
}

.error {
    font-size:80%;
    font-weight:bold;
    color:#A00;
}

.messageOK {
		font-size:80%;
		font-weight:bold;
		color:green;
	}

td, th { 
    vertical-align:top;
}

#popup1 {
	size:50%;
	position:fixed;
	top:0;
	left:0;
	width:99vw;
	height:100vh;
	display:flex;
	justify-content:center;
	align-items:center;
	border:solid 10px red;
	justify-content:center;
	text-align:center;
	}
		

.avatar {
	border:solid 3px white; 
	border-radius:25px;
	max-height:60px;
	width: 10%;
	margin-right:40px;
	transition:1s;
}

.avatar:hover {
	transform: scale(1.05); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}

.leftImage {
	margin-left:0;
	width:10%;
}


.separation{
	clear: both;
	position: absolute;
	margin-top :10px;
	margin-left: 250px;
	height: 200px;
	width : 5px;
	background: black;
	}

#popup2 {
	background-color:rgba(255,255,255,0,0.9);
	border: none;
	border-radius:10px;
	padding:10px;
	background-color:yellow;
	} 

@font-face {
	font-family:logo;
	src : url("fonts/Amore.otf");
	}
	
	
#imgFord {
	width:50%;
    margin:20px;
	transition: transform 0.5s;
	}
		
#imgFord:hover {
	transform:scale(1.5);
	}
		
#imgAuth {
	width:20%;
    margin: 20px;
	transition: transform 0.5s;
    }

#imgAuth:hover {
	transform:scale(1.5);
}
	
	
#banniere {
	height:10%;
	border : solid 1px white ;
	display : flex;
	justify-content : space-between;
	align-items : center;
	background-image:url(images/background.jpg);
	background-size:cover;
	background-position:center;
	margin-bottom:5px;
	padding:5px;
	border: none;
	color:white;
	font-size:30px;		
	}
		
#logo {
	text-align: center;
	border : none;
	font-family:logo;
	font-size:2em;
	text-shadow:  5px 5px 5px black;
	background-color : none;
	margin-left:5px;
	}
	
#menuH {
	border : none ;
	margin-left: 40%;
    left: 40%;
	}
		
#menuH, #menuV, #contenu, #pied{
	border: none;
	padding:2px;
	margin:2px;
	background: linear-gradient(0.1turn,#bad6ff,white,#bad6ff);

	}
		
		
		
#main {
	border : none ;
	display:flex;

	}

.theButton {
	cursor:pointer;
	margin-left:10px;
	margin-right:10px;
	margin-top:3px;
	width: 110px;
	height:27px;
    align-items: center;
    background-color: #fff;
    border-radius: 12px;
	transition:1s;
}

.theButtonMenu {
	cursor:pointer;
	justify-content:space-between;
	margin:3px;
	width: 110px;
	height:27px;
    align-items: center;
    background-color: #fff;
    border-radius: 12px;
	transition:1s;
}

.theButton:hover{
	background-color: #bedef7;

}

.theButtonMenu:hover{
	background-color: #bedef7;

}


#menuV {
	display:none;
	text-align: center;
	border : none ;	
	}
		
		
#contenu {
	min-height:3550px;
	position:relative;
	border : none  ;
	width:100%;
	}

.jeux_nombre_cache{
    text-align:center;
}
		
#pied {
	border-top : none ;
	text-align:center;
	position:relative;
	bottom:0;
    left:0;
    right:0;
	width:99.5%;
	height: 25px;
	}



@media screen and (max-width: 1000.40px) {
	#logo {
		font-size: 1.5em;
	}
}

@media screen and (max-width: 600.40px) {
	#logo {
		font-size: 0.7em;
	}
}

</style>
	
</body>
</html>
