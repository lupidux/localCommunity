<?php 
    require_once("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
 
        <?php if(!isset($_SESSION['id'])){ ?>
            <title>access denied</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php }else if ( isset($_SESSION['id']) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin') ){ ?> 
            <title>LocalCommunity - Richieste di autorizzazione</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php } ?>
        
    </head>

    <?php if(!isset($_SESSION['id'])){ ?>
        <body>
            <h1>access denied</h1>
        </body>
    <?php } else if ( isset($_SESSION['id']) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin') ) { ?> 
        <body style="background-repeat: no-repeat; background-size: cover;" background="img/project.jpg">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="row" style="width: 100%;">
                    <div class="col-sm-12">
                        <a class="navbar-brand" href="index.php">LocalCommunity - Home</a>
                    </div>
                </div>
            </nav>

            <?php
                if(isset($_POST['confirm'])){ 
                    if(isset($_POST['idAutorizzazione']) && !empty($_POST['idAutorizzazione'])){
                        foreach ( file("xml/autorizzazioni.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $autorizzazioniXml = new DOMDocument();
                        if (!$autorizzazioniXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }
                        $autorizzazioni = $autorizzazioniXml->documentElement->childNodes;
                        
                        for ($a=0; $a<$autorizzazioni->length; $a++) { 
                            $autorizzazione = $autorizzazioni->item($a);
                            $id_Auto = $autorizzazione->getAttribute('id');
                            $autorizzazioneEsistente="no"; 
                            if($_POST['idAutorizzazione']==$id_Auto){ 
                                $idRespFromAutoriz = $autorizzazione->firstChild->nextSibling;
                                $idRespFromAutorizValue = $idRespFromAutoriz->textContent;
                                $autorizzazioneEsistente="yes";
                                $sonoIlRespDellAutorizzazione="no";
                                if($_SESSION['id']==$idRespFromAutorizValue){
                                    $sonoIlRespDellAutorizzazione="yes";
                                    break;
                                }
                            }
                        }
                        if ($autorizzazioneEsistente=="no"){
                            if(isset($msg['error'])){
                                $msg['error'].="La richiesta è inesistente<br />";
                            }
                            else{
                                $msg['error']="La richiesta è inesistente<br />";
                            }
                        }
                        if ($sonoIlRespDellAutorizzazione=="no"){
                            if(isset($msg['error'])){
                                $msg['error'].="Non hai i permessi su questa richiesta<br />";
                            }
                            else{
                                $msg['error']="Non hai i permessi su questa richiesta<br />";
                            }
                        }
                    
                    }else{
                        if(isset($msg['error'])){
                        $msg['error'].="L'id della richiesta è obbligatorio<br />";
                        }
                        else{
                        $msg['error']="L'id della richiesta è obbligatorio<br />";
                        }
                    }

                    if(isset($_POST['esitoValue']) && !empty($_POST['esitoValue'])  && $_POST['esitoValue']!="Scegli..."){
                        #tutto ok
                    }else{
                        if(isset($msg['error'])){
                        $msg['error'].="L'esito della richiesta è obbligatorio<br />";
                        }
                        else{
                        $msg['error']="L'esito della richiesta è obbligatorio<br />";
                        }
                    }
                    
                    if(!isset($msg['error']) && $autorizzazioneEsistente=="yes" && $sonoIlRespDellAutorizzazione=="yes"){
                        $xmlString = "";
                        foreach ( file("xml/autorizzazioni.xml") as $node ) {
                            $xmlString .= trim($node);
                        }
                        $clearancesXml = new DOMDocument();
                        if (!$clearancesXml->loadXML($xmlString)) {
                            die ("Parsing error\n");
                        }
                        $docElem = $clearancesXml->documentElement;
                        $clearances = $docElem->childNodes; 

                        for ($i=0; $i<$clearances->length; $i++) { 
                            $clearance = $clearances->item($i);
                            $idClearance = $clearance->getAttribute('id'); 

                            if ($_POST['idAutorizzazione'] == $idClearance){
                                $nodo_esito = $clearance->firstChild->nextSibling->nextSibling->nextSibling;
                                $nodo_esitoValue = $nodo_esito->nodeValue = $_POST['esitoValue']; 
                                break;
                            }
                        }

                        if (!($clearancesXml->save("xml/autorizzazioni.xml"))) {
                            if(isset($msg['error'])){ 
                                $msg['error'].="Operazione non effettuata<br />";
                            }
                            else{ 
                                $msg['error']="Operazione non effettuata<br />";
                            }
                        } 
                    }
                    if(!isset($msg['error'])){
                        if(isset($msg['success'])){ 
                            $msg['success'].="Operazione effettuata con successo<br />";
                        }
                        else{ 
                            $msg['success']="Operazione effettuata con successo<br />";
                        }
                    }
                }
            ?>

            <?php if(isset($msg['error'])){?>
            <div class="card text-center" style="opacity:80%; border:solid 1px red; width: 20rem; position: relative; left: 76.4%;" >
                <?php echo $msg['error']; ?> 
            </div>
            <?php } ?>
            <?php if(isset($msg['success'])){?>
            <div class="card text-center" style="opacity:80%; border:solid 1px red; width: 20rem; position: relative; left: 76.4%;" >
                <?php echo $msg['success']; ?> 
            </div>
            <?php } ?>

            <div align="center">
                <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 84rem; position: relative; top: 1rem;" >
                    <div class="card-body">
                            <h5 class="card-header" style="background-color: white; color: black">
                                Richieste di autorizzazione
                            </h5>

                            <?php 
                                $xmlString = "";
                                foreach ( file("xml/autorizzazioni.xml") as $node ) {
                                    $xmlString .= trim($node);
                                }
                                $autorizzazioniXml = new DOMDocument();
                                if (!$autorizzazioniXml->loadXML($xmlString)) {
                                    die ("Parsing error\n");
                                }
                                $docElem = $autorizzazioniXml->documentElement;
                                $autorizzazioni = $docElem->childNodes;
                
                                for ($k=$autorizzazioni->length-1; $k>=0; $k--) { 
                                    $autorizzazione = $autorizzazioni->item($k);
                                    $idAutorizzazione = $autorizzazione->getAttribute('id');
                
                                    $idUtente=$autorizzazione->firstChild; 
                                    $idUtenteValue=$idUtente->textContent; 
                                    $idResponsabile=$idUtente->nextSibling; 
                                    $idResponsabileValue=$idResponsabile->textContent; 
                                    $idDiscussione=$idResponsabile->nextSibling; 
                                    $idDiscussioneValue=$idDiscussione->textContent; 
                                    $esito=$idDiscussione->nextSibling; 
                                    $esitoValue=$esito->textContent; 
                                    $dataOra=$esito->nextSibling; 
                                    $dataOraValue=$dataOra->textContent; 
                
                                    $datiRichiedente=mysqli_fetch_array(mysqli_query($mysqliConnection,"SELECT nome, cognome, username FROM utente WHERE id=".$idUtenteValue));
                                    $nomeRichiedenteValue=$datiRichiedente['nome']. " " .$datiRichiedente['cognome'];
                                    $usernameRichiedenteValue=$datiRichiedente['username'];
                
                                    $xmlString = "";
                                    foreach ( file("xml/discussioni.xml") as $node ) {
                                        $xmlString .= trim($node);
                                    }
                                    $discussioniXml = new DOMDocument();
                                    if (!$discussioniXml->loadXML($xmlString)) {
                                        die ("Parsing error\n");
                                    }
                                    $xpath=new DOMXpath($discussioniXml);
                                    $discussione=$xpath->query("//discussione[@id='".$idDiscussioneValue."']")->item(0);
                                    $idProgetto=$discussione->firstChild;
                                    $idProgettoValue=$idProgetto->textContent;
                                    $titoloDiscussione=$discussione->firstChild->nextSibling;
                                    $titoloDiscussioneValue=$titoloDiscussione->textContent;

                                    if($idResponsabileValue==$_SESSION['id'] && $esitoValue!="positivo" && $esitoValue!="negativo"){
                            ?>

                                <form method="post" action="manage_requests.php">
                                    <div class="row" style="width: 113.8%;">
                                        <div class="col-sm-3">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 8.4rem;" class="input-group-text" id="inputGroup-sizing-default">U/N Richiedente</span>
                                                </div>
                                                <input type="text" class="form-control" name="usernameRichiedenteValue" value="<?php echo $usernameRichiedenteValue ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 6.6rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                                </div>
                                                <?php
                                                    echo '<output type="text" class="form-control" name="titoloDiscussioneValue" readonly><a href="discussion.php?id='. $idProgettoValue .'"> '. $titoloDiscussioneValue .'</a></output>';
                                                ?>  
                                            </div>
                                        </div>

                                        <div class="input-group mb-3" hidden>
                                            <div class="input-group-prepend">
                                                <span style="width: 2.2rem;" class="input-group-text" id="inputGroup-sizing-default">ID</span>
                                            </div>
                                            <input type="text" class="form-control" name="idAutorizzazione" value="<?php echo $idAutorizzazione ?>">
                                        </div>
                                        <div class="col-sm-1.5">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label style="width: 3.5rem;" class="input-group-text" for="inputGroupSelect01">Esito</label>
                                                </div>
                                                <select class="custom-select" id="inputGroupSelect01" name="esitoValue">
                                                    <option selected>Scegli...</option>
                                                    <option value="positivo">Positivo</option>
                                                    <option value="negativo">Negativo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-1.5">
                                            <input type="submit" style="float: center; width: 2.5rem;" class="btn btn-dark" name="confirm" value="&#10004">
                                        </div>
                                    </div>
                                </form>

                            <?php
                                    }
                                } 
                            ?>

                        </div>
                    </div>
                <br />
            </div>
        </body>

    <?php } ?>

</html>