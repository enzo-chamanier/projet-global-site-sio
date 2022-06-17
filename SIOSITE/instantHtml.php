<?php include "pageStartDisplay.php"; ?>

<h1> Voici un HTML Instant, tapez du code HTML afin qu'il s'exécute automatiquement </h1>
<textarea id= text onkeyup=plan() style=position:static;min-width:1000px;max-width:1000px></textarea><br>
<iframe id=resultat style=position:static;min-width:1000px;max-width:1000px></iframe>
<script>

    var id 
    function plan()
    {
        clearTimeout(id)
        id = setTimeout(maj,1000 )
    }

    function maj()
    {
        resultat.srcdoc=text.value
    }
</script> 

<?php include "pageEnd.php"; ?>