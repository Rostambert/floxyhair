<?php

class UserRepository{

    public $bdd;

    public function __construct($connection){
        $this->bdd= $connection;
    }

    public function searchUserFromEmail($email){
        $stmt = $this->bdd->prepare("SELECT * FROM user WHERE email=:email"); 
        $stmt->execute(array("email"=>$email)); 
        $row = $stmt->fetch();
        return $row;
    }

    public function insertUser($data,  $typeUser){
        $stmt = $this->bdd->prepare("INSERT INTO `user`
                                    (`firstname`, `lastname`, `email`, `password`, `type`)
                                    VALUES (:firstname, :lastname, :email, :password, :type);"); 
        return $stmt->execute(array("firstname"=> $data["firstname"],
                                    "lastname"=>$data["lastname"],
                                    "email"=>$data["email"],
                                    "password"=>password_hash($data["password"], PASSWORD_DEFAULT),
                                    "type"=>$typeUser)); 
    }

    public function getCoiffeurs(){
        $sql="SELECT * from user where type=2";
        return $this->bdd->query($sql);
    }

    public function getFromId($id){
        $stmt = $this->bdd->prepare("SELECT * FROM user WHERE id=? LIMIT 1"); 
        $stmt->execute(array($id)); 
        $row = $stmt->fetch();
        return $row;
    }

    public function insert($data, $file, $typeUser){
        $stmt = $this->bdd->prepare("INSERT INTO `user`
                                            (`firstname`, `lastname`, `email`, `password`, `avatar`,`description`,`type`)
                                            VALUES (:firstname, :lastname, :email, :password, :avatar, :description, :type);"); 
        return $stmt->execute(array("firstname"=> $data["firstname"],
                                        "lastname"=>$data["lastname"],
                                        "email"=>$data["email"],
                                        "password"=>password_hash($data["password"], PASSWORD_DEFAULT),
                                        "avatar"=>file_get_contents($file),
                                        "description"=>$data["description"],
                                        "type"=>$typeUser));    
    }

    public function update($id, $data, $file){
        $requete= "UPDATE `user` SET `lastname` = :lastname,
                        `firstname`= :firstname, `email`=:email, `description`=:description";
        $requeteValues= array("firstname"=> $data["firstname"],
                                "lastname"=>$data["lastname"],
                                "email"=>$data["email"],
                                "description"=>$data["description"],
                                "id"=>$id);
        if(!empty($file)){
            $requete.= ", avatar=:avatar";
            $avatar= "data:image/jpg;base64,". base64_encode(file_get_contents($file));
            $requeteValues["avatar"]=file_get_contents($file);
        }
        if(!empty($data["password"])){
            $requete.= ", password=:password";
            $requeteValues["password"]=password_hash($_POST["password"], PASSWORD_DEFAULT);
        }
        $requete.=" WHERE `user`.`id` = :id";
        $stmt = $this->bdd->prepare($requete); 
        return $stmt->execute($requeteValues); 
    }
}