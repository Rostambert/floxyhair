<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floxy Hair</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <?php include("connexion.php"); ?>
    <div id="header">
        <h1>Floxy Hair</h1>
    </div>
    <!-- bar de navigation -->
    <div id="navbar">
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="index.php?page=prestations">Nos prestations</a></li>
            <li><a href="index.php?page=equipes">Notres Ã©quipes</a></li>
            <li><a href="#">Contacts</a></li>
            <li><a href="index.php?page=profil">Mon profil</a></li>

        </ul>
        <?php
    if(!isset($_SESSION['user'])){
        echo "<a href='login.php'>Se connecter</a>";
    }else{
        echo "<a href='logout.php'>";
        echo $_SESSION ['user'] ["firstname"]."".$_SESSION ['user'] ["lastname"]. "<br/>";
        echo"deconnexion";
        echo "</a>";
    
    }
    ?>
    </div>
    <div id="content">
        <?php if(!isset($_GET["page"])) { 
            echo "<img src='./images/salon.png' alt='imageSalon'/>";
            echo "<a href='index.php?page=rdv' id='RDVButton'>Prendre Rendez-vous</a>";
         }else{
            if($_GET["page"]=="login"){
                include("login.php");
            }else if($_GET["page"]=="rdv"){
                if(isset($_SESSION['user'])){
                    include("rdv.php");
                }else{
                    include("login.php");
                }
            }else if($_GET["page"]=="prestations"){
                include("prestations.php");
            }else if($_GET["page"]=="equipes"){
                include("equipes.php");
            }else if($_GET["page"]=="formuser"){
                include("formuser.php");
            }else if($_GET["page"]=="profil"){
                include("profil.php");
            }else{
                echo "Erreur 404! Page non existante.";
            }
        }?>
    </div>
    <div id="footer">    
    </div>
</body>
</html>