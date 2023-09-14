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
 
        <?php if(isset($_SESSION) && $_SESSION['stato'] == 'sospeso' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
            <title>access denied</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php }else if ( (!isset($_SESSION['username'])) || (isset($_SESSION) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')) ){ ?>
            <title>LocalCommunity - Discussione</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php } ?>
    </head>

    <?php if(isset($_SESSION) && $_SESSION['stato'] == 'sospeso' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
        <body>
            <h1>access denied</h1>
        </body>
    <?php }else if(!isset($_SESSION['username'])){ ?>
        <body style="background-repeat: no-repeat; background-size: cover;" background="img/project.jpg">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="row" style="width: 110%;">
                    <div class="col-sm-12">
                        <a class="navbar-brand" href="index.php">LocalCommunity - Home</a>
                    </div>
                </div>
            </nav>

            <?php
                $xmlString = "";
                foreach ( file("xml/progetti.xml") as $node ) {
                $xmlString .= trim($node);
                }
                $progettiXml = new DOMDocument();
                if (!$progettiXml->loadXML($xmlString)) {
                die ("Parsing error\n");
                }

                $xpath=new DOMXpath($progettiXml);
                $idProgettoValue=(int)$_GET['id'];
                $progetto=$xpath->query("//progetto[@id='".$idProgettoValue."']")->item(0);
                $titoloProgetto=$progetto->firstChild->nextSibling->nextSibling;
                $titoloProgettoValue=$titoloProgetto->textContent;
                $idDiscussione=$progetto->firstChild->nextSibling;
                $idDiscussioneValue=$idDiscussione->textContent;


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
                $titoloDiscussione=$discussione->firstChild->nextSibling;
                $titoloDiscussioneValue=$titoloDiscussione->textContent;
                $descrizione=$titoloDiscussione->nextSibling;
                $descrizioneValue=$descrizione->textContent;
                $numCommenti=$descrizione->nextSibling;
                $numCommentiValue=$numCommenti->textContent;
            ?>
            <div align="center">
                <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 82rem; position: relative; top: 1rem;" >
                    <div class="card-body">
                        <h5 class="card-header" style="background-color: white; color: black">
                            Discussione
                        </h5>

                        <div class="row" style="width: 102.35%;">
                            <div class="col-sm-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span style="width: 5.5rem;" class="input-group-text" id="inputGroup-sizing-default">Progetto</span>
                                    </div>
                                    <input type="text" class="form-control" name="progettoValue" value="<?php echo $titoloProgettoValue ?>" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span style="width: 7rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                    </div>
                                    <input type="text" class="form-control" name="titoloDiscussioneValue" value="<?php echo $titoloDiscussioneValue ?>" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span style="width: 7rem;" class="input-group-text" id="inputGroup-sizing-default">n° Commenti</span>
                                    </div>
                                    <input type="text" class="form-control" name="numCommentiValue" value="<?php echo $numCommentiValue ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span style="width: 11rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione</span>
                            </div>
                            <textarea type="text" class="form-control" name="descrizioneValue" disabled><?php echo $descrizioneValue ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <br />

            <div align="center">
                <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 82rem; position: relative; top: 1rem;" >
                    <div class="card-body">
                        <h5 class="card-header" style="background-color: white; color: black">
                            Commenti
                        </h5>

                        <?php
                            $xmlString = "";
                            foreach ( file("xml/commenti.xml") as $node ) {
                                $xmlString .= trim($node);
                            }
                            $commentiXml = new DOMDocument();
                            if (!$commentiXml->loadXML($xmlString)) {
                                die ("Parsing error\n");
                            }
                            $commenti = $commentiXml->documentElement->childNodes;

                            for ($i=$commenti->length-1; $i>=0; $i--) {
                                $commento = $commenti->item($i);
                                $idCommento = $commento->getAttribute('id');

                                $idUtente = $commento->firstChild;
                                $idUtenteValue = $idUtente->textContent;
                                $usernameUtente=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT username FROM utente WHERE id='".$idUtenteValue."'"));
                                $usernameUtenteValue=$usernameUtente['username'];

                                $idDiscussioneCommento = $idUtente->nextSibling;
                                $idDiscussioneCommentoValue = $idDiscussioneCommento->textContent;

                                $testo = $idDiscussioneCommento->nextSibling;
                                $testoValue = $testo->textContent;
                                $dataOra = $testo->nextSibling;
                                $dataOraValue = $dataOra->textContent;
                                $votoUtilitaMedia = $dataOra->nextSibling;
                                $votoUtilitaMediaValue = $votoUtilitaMedia->textContent;
                                $votoAffinitaMedia = $votoUtilitaMedia->nextSibling->nextSibling;
                                $votoAffinitaMediaValue = $votoAffinitaMedia->textContent;

                                if ($idDiscussioneCommentoValue == $idDiscussioneValue){
                                ?>
                                    <div class="row" style="width: 100%;">
                                        <div class="col-sm-6">

                                            <div class="row" style="width: 105%;">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Utente</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="usernameUtenteValue" value="<?php echo $usernameUtenteValue ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Data e ora</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="dataOraValue" value="<?php echo $dataOraValue ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="width: 105%;">
                                                <div class="col-sm-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 7.5rem;" class="input-group-text" id="inputGroup-sizing-default">ID Commento</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="idCommento" value="<?php echo $idCommento ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 7.5rem;" class="input-group-text" id="inputGroup-sizing-default">Utilità media</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="votoAffinitaMediaValue" value="<?php echo $votoAffinitaMediaValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 7.5rem;" class="input-group-text" id="inputGroup-sizing-default">Affinità media</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="votoUtilitaMediaValue" value="<?php echo $votoAffinitaMediaValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group mb-3" style="height: 5.75rem;">
                                                <div class="input-group-prepend">
                                                    <span style="width: 4rem;" class="input-group-text" id="inputGroup-sizing-default">Testo</span>
                                                </div>
                                                <textarea type="text" class="form-control" name="testoValue" disabled><?php echo $testoValue ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </body>
    <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
        <body style="background-repeat: no-repeat; background-size: cover;" background="img/project.jpg">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="row" style="width: 110%;">
                    <div class="col-sm-12">
                        <a class="navbar-brand" href="index.php">LocalCommunity - Home</a>
                    </div>
                </div>
            </nav>

            <?php
                $xmlString = "";
                foreach ( file("xml/progetti.xml") as $node ) {
                $xmlString .= trim($node);
                }
                $progettiXml = new DOMDocument();
                if (!$progettiXml->loadXML($xmlString)) {
                die ("Parsing error\n");
                }

                $xpath=new DOMXpath($progettiXml);
                $idProgettoValue=(int)$_GET['id'];
                $progetto=$xpath->query("//progetto[@id='".$idProgettoValue."']")->item(0);
                $titoloProgetto=$progetto->firstChild->nextSibling->nextSibling;
                $titoloProgettoValue=$titoloProgetto->textContent;
                $idDiscussione=$progetto->firstChild->nextSibling;
                $idDiscussioneValue=$idDiscussione->textContent;
                $idResponsabile=$progetto->firstChild;
                $idResponsabileValue=$idResponsabile->textContent;


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
                $titoloDiscussione=$discussione->firstChild->nextSibling;
                $titoloDiscussioneValue=$titoloDiscussione->textContent;
                $descrizione=$titoloDiscussione->nextSibling;
                $descrizioneValue=$descrizione->textContent;
                $numCommenti=$descrizione->nextSibling;
                $numCommentiValue=$numCommenti->textContent;


                if(isset($_POST['join'])){
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

                    $numTentativi = 0;
                    for ($i=0; $i<$autorizzazioni->length; $i++) {
                        $autorizzazione = $autorizzazioni->item($i);
                        $idUten = $autorizzazione->firstChild;
                        $idUtenValue = $idUten->textContent;
                        $idDiscus = $idUten->nextSibling->nextSibling;
                        $idDiscusValue = $idDiscus->textContent;
                        $esito = $idDiscus->nextSibling;
                        $esitoValue = $esito->textContent;
                        if ($idDiscusValue == $idDiscussioneValue && $_SESSION['id'] == $idUtenValue){ 
                            if ($esitoValue == 'positivo'){
                                $msg['error']="Partecipi già alla discussione<br />";
                                $partecipaGia="yes";
                                break;
                            }else if ($esitoValue == 'negativo'){
                                $numTentativi += 1;
                                if(isset($msg['error'])){ 
                                    $msg['error'].="Autorizzazione negata: tentativo ". $numTentativi ."<br />";
                                }
                                else{
                                    $msg['error']="Autorizzazione negata: tentativo ". $numTentativi ."<br />";
                                }
                                if ($numTentativi <= 3) {
                                    #tutto ok
                                }else{
                                    if(isset($msg['error'])){ 
                                        $msg['error'].="Troppi tentativi negati, non è possibile avanzare altre richieste<br />";
                                    }
                                    else{
                                        $msg['error']="Troppi tentativi negati, non è possibile avanzare altre richieste<br />";
                                    }
                                    break;
                                }
                            } else {
                                $richiestaInAttesa="yes";
                                if(isset($msg['error'])){ 
                                    $msg['error'].="Autorizzazione già richiesta in stato di attesa<br />";
                                }
                                else{
                                    $msg['error']="Autorizzazione già richiesta in stato di attesa<br />";
                                } 
                                break;
                            }
                        }else{
                            $nonCiSonoRichieste="yes";
                        }
                    }

                    if(($idResponsabileValue != $_SESSION['id']) && !$partecipaGia && !$richiestaInAttesa && ($nonCiSonoRichieste || $numTentativi<=3)) { 
                        for ($l=0; $l<$autorizzazioni->length; $l++) {
                            $clearance = $autorizzazioni->item($l);
                            $id_cle = $clearance->getAttribute('id');
                        } 
                        $last_clearance=$clearance; 
                        $lastClearance = (int)$id_cle; 
                        $newClearanceValue=$lastClearance+1; 
                        $new_clearance = $autorizzazioniXml->createElement("autorizzazione"); 
                        $new_clearance->setAttribute("id", $newClearanceValue);
                        $docElem->appendChild($new_clearance);
                        
                        $newidUtenteValue=(int)$_SESSION['id'];
                        $new_idUten = $autorizzazioniXml->createElement("idUtente");
                        $new_idUten_text = $autorizzazioniXml->createTextNode($newidUtenteValue);
                        $new_idUten->appendChild($new_idUten_text);
                        $new_clearance->appendChild($new_idUten);

                        $newIdResponsabileValue=(int)$idResponsabileValue;
                        $new_idResp = $autorizzazioniXml->createElement("idResponsabile"); 
                        $new_idResp_text = $autorizzazioniXml->createTextNode($newIdResponsabileValue);
                        $new_idResp->appendChild($new_idResp_text);
                        $new_clearance->appendChild($new_idResp);

                        $newidDiscussioneValue=$idDiscussioneValue;
                        $new_idDisc = $autorizzazioniXml->createElement("idDiscussione");
                        $new_idDisc_text = $autorizzazioniXml->createTextNode($newidDiscussioneValue);
                        $new_idDisc->appendChild($new_idDisc_text);
                        $new_clearance->appendChild($new_idDisc);

                        $new_esito = $autorizzazioniXml->createElement("esito");
                        $new_clearance->appendChild($new_esito);

                        $newDataOraValue=date('Y-m-d', time()) . "T" . date('H:i:s', time());
                        $new_dataOr = $autorizzazioniXml->createElement("dataOra");
                        $new_dataOr_text = $autorizzazioniXml->createTextNode($newDataOraValue);
                        $new_dataOr->appendChild($new_dataOr_text);
                        $new_clearance->appendChild($new_dataOr);

                        if (!($autorizzazioniXml->save("xml/autorizzazioni.xml"))) {
                            if(isset($msg['error'])){
                              $msg['error'].="Salvataggio dati fallito<br />";
                            }
                            else{
                              $msg['error']="Salvataggio dati fallito<br />";
                            }
                        }

                        if(!isset($msg['error'])){
                            if(isset($msg['success'])){
                                $msg['success'].="Richiesta inviata con successo<br />";
                            }
                            else{
                                $msg['success']="Richiesta inviata con successo<br />";
                            }
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
                <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 82rem; position: relative; top: 1rem;" >
                    <div class="card-body">
                        <h5 class="card-header" style="background-color: white; color: black">
                            Discussione
                        </h5>

                        <div class="row" style="width: 102.35%;">
                            <div class="col-sm-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span style="width: 5.5rem;" class="input-group-text" id="inputGroup-sizing-default">Progetto</span>
                                    </div>
                                    <input type="text" class="form-control" name="progettoValue" value="<?php echo $titoloProgettoValue ?>" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">    
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span style="width: 7rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                    </div>
                                    <input type="text" class="form-control" name="titoloDiscussioneValue" value="<?php echo $titoloDiscussioneValue ?>" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span style="width: 7rem;" class="input-group-text" id="inputGroup-sizing-default">n° Commenti</span>
                                    </div>
                                    <input type="text" class="form-control" name="numCommentiValue" value="<?php echo $numCommentiValue ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span style="width: 11rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione</span>
                            </div>
                            <textarea type="text" class="form-control" name="descrizioneValue" disabled><?php echo $descrizioneValue ?></textarea>
                        </div>
                        

                        <?php if(($_SESSION['ruolo'] == 'utente interno') && ($idResponsabileValue != $_SESSION['id'])){ ?>
                            <form method="post" <?php echo 'action="discussion.php?id='. $idProgettoValue . '"' ?>>
                                <input type="submit" style="float: center;" class="btn btn-dark" name="join" value="Partecipa">
                            </form>
                        <?php } ?>

                    </div>
                </div>
            </div>
            <br />
            
            <div align="center">
                <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 82rem; position: relative; top: 1rem;" >
                    <div class="card-body">
                        <h5 class="card-header" style="background-color: white; color: black">
                            Commenti
                        </h5>

                        <?php
                            $xmlString = "";
                            foreach ( file("xml/commenti.xml") as $node ) {
                                $xmlString .= trim($node);
                            }
                            $commentiXml = new DOMDocument();
                            if (!$commentiXml->loadXML($xmlString)) {
                                die ("Parsing error\n");
                            }
                            $commenti = $commentiXml->documentElement->childNodes;

                            for ($i=$commenti->length-1; $i>=0; $i--) {
                                $commento = $commenti->item($i);
                                $idCommento = $commento->getAttribute('id');

                                $idUtente = $commento->firstChild;
                                $idUtenteValue = $idUtente->textContent;
                                $usernameUtente=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT username FROM utente WHERE id='".$idUtenteValue."'"));
                                $usernameUtenteValue=$usernameUtente['username'];

                                $idDiscussioneCommento = $idUtente->nextSibling;
                                $idDiscussioneCommentoValue = $idDiscussioneCommento->textContent;

                                $testo = $idDiscussioneCommento->nextSibling;
                                $testoValue = $testo->textContent;
                                $dataOra = $testo->nextSibling;
                                $dataOraValue = $dataOra->textContent;
                                $votoUtilitaMedia = $dataOra->nextSibling;
                                $votoUtilitaMediaValue = $votoUtilitaMedia->textContent;
                                $votoAffinitaMedia = $votoUtilitaMedia->nextSibling->nextSibling;
                                $votoAffinitaMediaValue = $votoAffinitaMedia->textContent;

                                if ($idDiscussioneCommentoValue == $idDiscussioneValue){
                                ?>
                                    <div class="row" style="width: 100%;">
                                        <div class="col-sm-6">

                                            <div class="row" style="width: 105%;">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Utente</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="usernameUtenteValue" value="<?php echo $usernameUtenteValue ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Data e ora</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="dataOraValue" value="<?php echo $dataOraValue ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="width: 105%;">
                                                <div class="col-sm-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 7.5rem;" class="input-group-text" id="inputGroup-sizing-default">ID Commento</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="idCommento" value="<?php echo $idCommento ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 7.5rem;" class="input-group-text" id="inputGroup-sizing-default">Utilità media</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="votoAffinitaMediaValue" value="<?php echo $votoUtilitaMediaValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 7.5rem;" class="input-group-text" id="inputGroup-sizing-default">Affinità media</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="votoUtilitaMediaValue" value="<?php echo $votoAffinitaMediaValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group mb-3" style="height: 5.75rem;">
                                                <div class="input-group-prepend">
                                                    <span style="width: 4rem;" class="input-group-text" id="inputGroup-sizing-default">Testo</span>
                                                </div>
                                                <textarea type="text" class="form-control" name="testoValue" disabled><?php echo $testoValue ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>

        </body>
    <?php } ?>

</html>