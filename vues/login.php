<?php
///////////////////////////
//Gestion inscription/////
//////////////////////////
if(isset($_POST["inscription"])){
    if(empty($_POST["firstname"])
        ||empty($_POST["lastname"])
        ||empty($_POST["email"])
        ||empty($_POST["password"])){
        echo "<p style='color:red'>Tous les champs sont obligatoires!</p>";
    }else{
        try{
            $stmt = $connexion->prepare("INSERT INTO `user`
                                        (`firstname`, `lastname`, `email`, `password`, `type`)
                                        VALUES (:firstname, :lastname, :email, :password, :type);"); 
            $stmt->execute(array("firstname"=> $_POST["firstname"],
                                    "lastname"=>$_POST["lastname"],
                                    "email"=>$_POST["email"],
                                    "password"=>password_hash($_POST["password"], PASSWORD_DEFAULT),
                                    "type"=>3)); 
        }
        catch(PDOException $e){
            printf("Erreur lors de l'inscription : %s\n", $e->getMessage());
            exit();
        }finally{
            echo "<p style='color:green'>Inscription réussite!</p>";
        }
    }
}
///////////////////////////
//Gestion connexion   /////
//////////////////////////
if(isset($_POST["connexion"])){
    if(empty($_POST["email"]) || empty($_POST["password"])){
        echo "<p style='color:red'>Tous les champs sont obligatoires!</p>";
    }else{
        $stmt = $connexion->prepare("SELECT * FROM user WHERE email=:email"); 
        $stmt->execute(array("email"=>$_POST['email'])); 
        $row = $stmt->fetch();
        if(password_verify($_POST['password'], $row["password"])){
            $_SESSION["user"]= $row;
            header("Refresh: 2");
        }else{
            echo "<p style='color:red'>Identifiants incorrect!</p>";
        }
    }
}
?>

<div id="LoginForms">
    <form id="inscForm" method="POST" action="#" >
        <h3>S'inscrire</h3>
        <input type="text" name="lastname" placeholder="Nom"/>
        <input type="text" name="firstname" placeholder="Prénom"/>
        <input type="text" name="email" placeholder="Email"/>
        <input type="password" name="password" placeholder="mot de passe"/>
        <input type="submit" name="inscription" value="Valider">
    </form> 
    <form id="loginForm" method="POST" action="#">
        <h3>Se connecter</h3>
        <input type="text" name="email" placeholder="Email" />
        <input type="password" name="password" placeholder="mot de passe"/>
        <input type="submit" name="connexion" value="Valider">
    </form>
</div>