<?php
    $firstname="";
    $lastname="";
    $email="";
    $avatar="";
    $description="";

    if(isset($_GET["id"])){
        $stmt = $connexion->prepare("SELECT * FROM user WHERE id=? LIMIT 1"); 
        $stmt->execute(array($_GET['id'])); 
        $row = $stmt->fetch();
        $firstname= $row["firstname"];
        $lastname= $row["lastname"];
        $email= $row["email"];
        $avatar= "data:image/jpg;base64,". base64_encode($row['avatar']);
        $description= $row["description"];
    }

    if(isset($_POST["addCoiffeur"])){
        if(empty($_POST["lastname"]) ||
            empty($_POST["firstname"])||
            empty($_POST["email"])||
            empty($_POST["password"])||
            empty($_FILES["avatar"]["tmp_name"])){
            echo "<p>Tous les champs sont obligatoires!!!</p>";
        }else if($_POST["password"]!=$_POST["confirmation"]){
            echo "<p style='color:red'>Mot de passe et confirmation différentes!!!</p>";
        }else{
            //Enregistrement du coiffeur
            try{
                $stmt = $connexion->prepare("INSERT INTO `user`
                                            (`firstname`, `lastname`, `email`, `password`, `avatar`,`description`,`type`)
                                            VALUES (:firstname, :lastname, :email, :password, :avatar, :description, :type);"); 
                $stmt->execute(array("firstname"=> $_POST["firstname"],
                                     "lastname"=>$_POST["lastname"],
                                     "email"=>$_POST["email"],
                                     "password"=>password_hash($_POST["password"], PASSWORD_DEFAULT),
                                     "avatar"=>file_get_contents($_FILES["avatar"]["tmp_name"]),
                                     "description"=>$_POST["description"],
                                     "type"=>2)); 
            }
            catch(PDOException $e){
                printf("Erreur lors de l'ajout d'un coiffeur : %s\n", $e->getMessage());
                exit();
            }finally{
                echo "<p style='color:green'>Enregistrement réussie!</p>";
            }
        }
    
    }else if(isset($_POST["updateCoiffeur"])){
        $firstname=$_POST["firstname"];
        $lastname=$_POST["lastname"];
        $email=$_POST["email"];
        $description=$_POST["description"];
        if(empty($_POST["lastname"]) ||
            empty($_POST["firstname"])||
            empty($_POST["email"])){
            echo "<p style='color:red'>Remplir les champs obligatoires!!!</p>";
        }else if($_POST["password"]!=$_POST["confirmation"]){
            echo "<p style='color:red'>Mot de passe et confirmation différentes!!!</p>";
        }else{
            $requete= "UPDATE `user` SET `lastname` = :lastname,
                        `firstname`= :firstname, `email`=:email, `description`=:description";
            $requeteValues= array("firstname"=> $_POST["firstname"],
                                  "lastname"=>$_POST["lastname"],
                                  "email"=>$_POST["email"],
                                  "description"=>$_POST["description"],
                                  "id"=>$_GET["id"]);
            if(!empty($_FILES["avatar"]["tmp_name"])){
                $requete.= ", avatar=:avatar";
                $avatar= "data:image/jpg;base64,". base64_encode(file_get_contents($_FILES["avatar"]["tmp_name"]));
                $requeteValues["avatar"]=file_get_contents($_FILES["avatar"]["tmp_name"]);
            }
            if(!empty($_POST["password"])){
                $requete.= ", password=:password";
                $requeteValues["password"]=password_hash($_POST["password"], PASSWORD_DEFAULT);
            }
            $requete.=" WHERE `user`.`id` = :id";
            //Enregistrement du coiffeur
            try{
                $stmt = $connexion->prepare($requete); 
                $stmt->execute($requeteValues); 
            }
            catch(PDOException $e){
                printf("Erreur lors de la modification d'un coiffeur : %s\n", $e->getMessage());     
            }finally{
                echo "<p style='color:green'>Enregistrement réussie!</p>";
            }
        }
    }

   

    
?>

<form action="#" method="POST" id="formUser" enctype="multipart/form-data">
    <img src="<?php echo $avatar;?>" id="userAvatar"/>
    <div>
        <span>Nom:</span>
        <input type="text" name="lastname" value="<?php echo $lastname;?>"/>
    </div>
    <div>
        <span>Prénom:</span>
        <input type="text" name="firstname"  value="<?php echo $firstname;?>"/>
    </div>
    <div>
        <span>Email:</span>
        <input type="text" name="email" value="<?php echo $email;?>" />
    </div>
    <div>
        <span>Mot de pass:</span>
        <input type="password" name="password" />
    </div>
    <div>
        <span>Confirmation:</span>
        <input type="password" name="confirmation" />
    </div>
    <div>
        <span>Avatar:</span>
        <input type="file" accept="image/*"  name="avatar" onchange="onChangeAvatar(this)"/>
    </div>
    <div>
        <span>Description:</span>
        <textarea name="description"><?php echo $description;?></textarea>
    </div>
    <?php 
      if(isset($_GET["id"])){
        echo  "<input type='submit' name='updateCoiffeur' value='Valider'/>";
      }else{
        echo  "<input type='submit' name='addCoiffeur' value='Valider'/>";
      }
    ?>
</form>
<script>
function onChangeAvatar(obj){
    var reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('userAvatar').src= e.target.result;
    }
    reader.readAsDataURL(obj.files[0]);
}    
</script>