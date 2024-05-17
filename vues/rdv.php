<h1>Prendre Rendez-vous</h1>
<?php if(!empty($_POST)){
    if(isset($_POST["saveRdv"])){
        //Enregistrement du rdv
        try{
            $stmt = $connexion->prepare("INSERT INTO `rendez_vous`
                                        (`debut`, `fin`, `prix`, `client`)
                                        VALUES (:debut, :fin, :prix, :client);");
            $dateDebutRdv= str_replace("T", " ", $_POST["debutRdv"]).":00";
            $stmt->execute(array("debut"=> $dateDebutRdv,
                                        "fin"=>($dateDebutRdv." interval ".$_POST["totalTime"]." Minute"),
                                        "prix"=>$_POST["total"],
                                        "client"=>$_POST["userId"])); 
           
            $idRdv= $connexion->lastInsertId();
            foreach($_POST["prestations"] as $key=>$value){
                $stmt = $connexion->prepare("INSERT INTO `rendez_vous_prestations`
                                            (`rendez_vous`, `prestation`, `quantity`)
                                            VALUES (:rendez_vous, :prestation, :quantity);");
                $stmt->execute(array("rendez_vous"=> $idRdv,
                                     "prestation"=>$value,
                                     "quantity"=>$_POST["quantities"][$key])); 

            }
        }
        catch(PDOException $e){
            printf("Erreur lors de l'ajout d'un coiffeur : %s\n", $e->getMessage());
            exit();
        }finally{
            
            echo "<p style='color:green'>Enregistrement réussie!</p>";
        }
    }
}
?>
<form action="#" method="POST" id="formRDV" onsubmit="return validateForm()">
    <div>
        <span>Date/Heure:</span>
        <input type="datetime-local" id="debutRdv" name="debutRdv"/>
    </div>
    <h3>Choix des prestations:</h3>
    <div>
        <select name="prestation" id="selectPrestation">
            <option value="">Choississez une prestation</option>
            <?php
                $sql="SELECT * from prestation";
                $listPrestations= array();
                foreach ($connexion->query($sql) as $row){
                    $listPrestations[]=$row;
                    echo "<option value='".$row["id"]."'>".$row["name"]." ".$row["prix"]."€ - ".$row["temps"]."min</option>";
                }
            ?>
        </select>
        <span>quantitée:</span>
        <input type="number" name="quantity" id="quantityPrest" value="1" style="width:40px" />
        <button onclick="addPrestation();return false;">Ajouter</button>
    </div>
    <div id="listPrestation">
    </div>
    <div id="divPriceTotal">
        <span>Total:</span><span id="priceTotal">0€</span>
    </div>
    <h3>Choix du coiffeur:</h3>
    <select name="coiffeur" id="idCoiffeur">
        <option value="">Choississez un coiffeur</option>
        <?php
            $sql="SELECT * from user where type=2";
            foreach ($connexion->query($sql) as $row){
                echo "<option value='".$row["id"]."'>".$row["firstname"]." ".$row["lastname"]."</option>";
            }
        ?>
    </select>
    
    <input type="hidden" name="total" id="inputTotal" value="0"/>
    <input type="hidden" name="totalTime" id="inputTotalTime" value="0"/>
    <input type="hidden" name="userId" value="<?php echo $_SESSION["user"]["id"] ?>"/>
    <input type="submit" name="saveRdv" value="Valider" />
</form>
<script>
let listPrestations= eval('<?php echo json_encode($listPrestations);?>');
function addPrestation(){
    let idPrestation= document.getElementById("selectPrestation").value;
    let quantity= document.getElementById("quantityPrest").value;
    if(idPrestation!="" && quantity>0 && !alreadySelect(idPrestation)){;
        let prestation= listPrestations.find(ob=>ob.id==idPrestation);
        let prestationInputs="<input type='hidden' class='inputPrest' name='prestations[]' value='"+prestation.id+"'/>";
        prestationInputs+="<input type='hidden' class='inputQuantity' name='quantities[]' value='"+quantity+"'/>";
       
        let prestationHtml="<div onclick='remove(this)'><span>"+prestation.name+
                                "</span><span>"+"quantitée: "+quantity+"</span><span>"+
                                "</span><span>"+"prix: "+(quantity*prestation.prix).toFixed(2)+"€</span>"+
                                prestationInputs+"</div>";
    
        document.getElementById("listPrestation").innerHTML+= prestationHtml;
        reloadPrice();
    }
}

function alreadySelect(id){
    let prestations= document.getElementsByClassName("inputPrest");
    for(var i=0; i< prestations.length;i++){
        if(prestations[i].value==id)return true;
    }
    return false;
}

function reloadPrice(){
    let prestations= document.getElementsByClassName("inputPrest");
    let quantities=  document.getElementsByClassName("inputQuantity");
    let total=0;
    let totalTime=0;
    for(var i=0; i< prestations.length;i++){
        let prestation= listPrestations.find(ob=>ob.id==prestations[i].value)
        let price= (prestation.prix * quantities[i].value);
        let time= (prestation.temps * quantities[i].value);
        total+= price;
        totalTime+=time;
    }
    document.getElementById("inputTotal").value= total.toFixed(2);
    document.getElementById("inputTotalTime").value= totalTime;
    document.getElementById("priceTotal").innerHTML= total.toFixed(2)+"€";
}

function remove(obj){
    obj.replaceChildren();
    document.getElementById("listPrestation").removeChild(obj)
    reloadPrice();
}

function validateForm(){
    let dateDebut= document.getElementById("debutRdv").value;
    let prestations= document.getElementsByClassName("inputPrest");
    let coiffeur= document.getElementById("idCoiffeur").value;
    if(dateDebut==""||coiffeur==""||prestations.length==0){
        alert("Vous devez renseigner tous les champs!!!");
        return false;
    }else{
        return true;
    }
}
</script>