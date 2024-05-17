<h1> Nôtre équipes </h1>
<?php
$sql="SELECT * from user where type=2";
if(!$connexion->query($sql)) echo "Pb d'accès aux coiffeurs";
else{
    echo "<div id='listUser'>";
    foreach ($connexion->query($sql) as $row){
        echo "<div class='itemUser' >".
                "<div class='infoUser'>".
                    "<img src='data:image/jpg;base64,". base64_encode($row['avatar'])."'alt='avatar'>".
                    "<div class='nameUser'>".
                        "<span>Nom :".$row['lastname']."</span>".
                        "<span>Prénom: <strong>".$row['firstname']."</strong></span>".
                    "</div>".
                "</div>".
                "<p>".$row['description']."</p>".
                "<a href='index.php?page=formuser&id=".$row["id"]."'>Modifier</a>".
            "</div>";
    }
    echo "</div>";

    echo "<a id='buttonUser' href='index.php?page=formuser'>Ajouter un coiffeur</a>";
}
?>