<h1> Nos Prestations </h1>
<?php
$sql="SELECT * from prestation";
if(!$connexion->query($sql)) echo "Pb d'accès aux prestations";
else{
    echo "<div id='listPrestations'>";
    foreach ($connexion->query($sql) as $row){
        echo "<div class='itemPrestation' onclick='displayDescription(this)'>".
                "<div class='titlePrestation'>".
                    "<h2>".$row['name']."</h2>".
                    "<h3>".$row['prix']."€</h3>".
                "</div>".
                "<div class='contentPrestation'>".
                    "<p>".$row['description']."</p>".
                    "<p> temps: ".$row['temps']."min</p>".
                "</div>".
            "</div>";
    }
    echo "</div>";
}
?>
<script>
function displayDescription(obj){
    let allContents= document.getElementsByClassName("contentPrestation");
    for(var i=0; i<allContents.length;i++){
        allContents[i].style.display="none";
    }
    let divContent= obj.getElementsByClassName("contentPrestation")[0];
    if(divContent.style.display=="none"){
        divContent.style.display="flex";
        divContent.classList.add("fadeIn");
    }else{
    
        divContent.classList.remove("fadeIn");
        divContent.style.display="none";
    }
}
</script>