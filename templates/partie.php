<?php
if (!valider("partie")){
    header("Location:./index.php");
    die("");
}
$id_user=valider("id_user","SESSION");

if ($id_partie=valider("partie"))
$id_partie=valider("partie");
else {
    $id_partie=valider("id_partie");
}

$partie=getPartieById($id_partie)[0]; 
if(valider("id_user","SESSION")==$partie["id_utilisateur1"])
{
  $id_adversaire=$partie["id_utilisateur2"];
}
else
{
  $id_adversaire=$partie["id_utilisateur1"];
}
?>

<style type="text/css">
    iframe{
        margin-right: auto;
        margin-left: auto;
        position: relative;
        float: left;
    }
    #chat{
        margin-bottom: 10px;
    }
</style>

<iframe id="grille"
    title="Inline Frame Example"
    height="650"
    width="450"
    scrolling="no"
    src="./parti.php?partie=<?php echo $id_partie; ?>&id_user=<?php echo $id_user; ?>"  >
</iframe>

  



<script>
        window.setInterval(function() {
            test2();
            test();
        }, 500);
    function test2(){
        //console.log('reloading..');
        // issue an AJAX request with HTTP post to your server side page. 
        //Here I used an aspx page in which the update login is written
        
        $.get("./controleur.php", { id_partie: <?php echo $id_partie; ?>,
                            id_user: <?php echo $id_user; ?>,
                            action: "testNouveau" },
            function(data){
                // callack function gets executed
                if (data=="refreshGrille")
                {
                    document.getElementById('grille').contentWindow.location.reload();
                }
        });
        // to prevent the default action
        return false;
    }
</script>




<iframe id="chat"
    title="Inline Frame Example"
    height="720"
    width="450"
    scrolling="yes"
    src="./chat.php?id_adversaire=<?php echo $id_adversaire; ?>&id_user=<?php echo $id_user; ?>"  >
</iframe>

<script>
    window.addEventListener('load', () => {
        //on souhaite que le scroll soit automatique
        document.getElementById('chat').contentWindow.scrollTo(0, document.getElementById('chat').contentWindow.document.body.scrollHeight);
        })
    function test(){
        //console.log('reloading..');
        // issue an AJAX request with HTTP post to your server side page. 
        //Here I used an aspx page in which the update login is written
        $.get("./controleur.php", { id_adversaire: <?php echo $id_adversaire; ?>,
                            id_user: <?php echo $id_user; ?>,
                            action: "testStatut" },
            function(data){
                // callack function gets executed
                if (data=="refreshChat")
                {
                    document.getElementById('chat').contentWindow.location.reload();
                    console.log('reloaded');
                }
        });
    }
</script>