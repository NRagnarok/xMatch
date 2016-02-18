<?php
$c = mysqli_connect("localhost", "xmatch", "xmatch", "xmatch");
$a = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
$m = mysqli_query($c, "INSERT INTO profils (lien, vote) VALUES ('".$a."', '0')");
// Copie dans le repertoire du script avec un nom
// incluant l'heure a la seconde pres 
$repertoireDestination = dirname(__FILE__)."/profils/".$a."/";
mkdir($repertoireDestination);
$nomDestination        = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).".jpg";

if (is_uploaded_file($_FILES["monfichier"]["tmp_name"])) {
    if (rename($_FILES["monfichier"]["tmp_name"],
                   $repertoireDestination.$nomDestination)) {
        header('Location:add_profile.php');
		exit();
    } else {
        echo "Le déplacement du fichier temporaire a échoué".
                " vérifiez l'existence du répertoire ".$repertoireDestination;
    }          
} else {
    echo "Le fichier n'a pas été uploadé (trop gros ?)";
}
?>