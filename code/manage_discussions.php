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
 
        <?php if((!isset($_SESSION['username'])) || (isset($_SESSION) && $_SESSION['stato'] == 'sospeso' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin'))){ ?>
            <title>access denied</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
            <title>LocalCommunity - Gestisci discussioni</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php } ?>
    </head>

    <?php if((!isset($_SESSION['username'])) || (isset($_SESSION) && $_SESSION['stato'] == 'sospeso' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin'))){ ?>
        <body>
            <h1>access denied</h1>
        </body>
    <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
        <body style="background-repeat: no-repeat; background-size: cover;" background="img/project.jpg">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="row" style="width: 100%;">
                    <div class="col-sm-12">
                        <a class="navbar-brand" href="index.php">LocalCommunity - Home</a>
                    </div>
                </div>
            </nav>

            <?php
                if(isset($_POST['post'])){
                    if(isset($_POST['newTestoValue']) && !empty($_POST['newTestoValue'])){
                        #tutto ok
                    }else{
                        if(isset($msg['error'])){
                        $msg['error'].="Il testo è obbligatorio<br />";
                        }
                        else{
                        $msg['error']="Il testo è obbligatorio<br />";
                        }
                    }
                    
                    if(!isset($msg['error'])){
                        $xmlString = "";
                        foreach ( file("xml/commenti.xml") as $node ) {
                            $xmlString .= trim($node);
                        }
                        $commentiXml = new DOMDocument();
                        if (!$commentiXml->loadXML($xmlString)) {
                            die ("Parsing error\n");
                        }
                        $docElem = $commentiXml->documentElement;
                        $commenti = $docElem->childNodes;
                
                        for ($l=0; $l<$commenti->length; $l++) {
                            $commento = $commenti->item($l);
                            $id_com = $commento->getAttribute('id');
                        }
                        $last_comment=$commento;
                        $id_last_comment = (int)$id_com;
                        $idCommentoValue=$id_last_comment+1;
                        $new_comment = $commentiXml->createElement("commento");
                        $new_comment->setAttribute("id", $idCommentoValue);
                        $docElem->appendChild($new_comment); 
                                
                        $newIdUtenteValue=(int)$_SESSION['id'];
                        $new_idUten = $commentiXml->createElement("idUtente");
                        $new_idUten_text = $commentiXml->createTextNode($newIdUtenteValue);
                        $new_idUten->appendChild($new_idUten_text);
                        $new_comment->appendChild($new_idUten);

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
                        $idDiscussione=$progetto->firstChild->nextSibling;
                        $idDiscussioneValue=$idDiscussione->textContent;
                        $newIdDiscussioneValue=$idDiscussioneValue;
                        $new_idDisc = $commentiXml->createElement("idDiscussione");
                        $new_idDisc_text = $commentiXml->createTextNode($newIdDiscussioneValue);
                        $new_idDisc->appendChild($new_idDisc_text);
                        $new_comment->appendChild($new_idDisc);

                        $newTestoValue=$_POST['newTestoValue'];
                        $new_testo = $commentiXml->createElement("testo");
                        $new_testo_text = $commentiXml->createTextNode($newTestoValue);
                        $new_testo->appendChild($new_testo_text);
                        $new_comment->appendChild($new_testo); 

                        $newDataOraValue=date('Y-m-d', time()) . "T" . date('H:i:s', time());
                        $new_dataOra = $commentiXml->createElement("dataOra");
                        $new_dataOra_text = $commentiXml->createTextNode($newDataOraValue);
                        $new_dataOra->appendChild($new_dataOra_text);
                        $new_comment->appendChild($new_dataOra);

                        $new_votoUtilitaMedia = $commentiXml->createElement("votoUtilitaMedia");
                        $new_comment->appendChild($new_votoUtilitaMedia); 
                        
                        $new_numVotiUtilita = $commentiXml->createElement("numVotiUtilita");
                        $new_comment->appendChild($new_numVotiUtilita);

                        $new_votoAffinitaMedia = $commentiXml->createElement("votoAffinitaMedia");
                        $new_comment->appendChild($new_votoAffinitaMedia);

                        $new_numVotiAffinita = $commentiXml->createElement("numVotiAffinita");
                        $new_comment->appendChild($new_numVotiAffinita);


                        $xmlString = "";
                        foreach ( file("xml/discussioni.xml") as $node ) {
                            $xmlString .= trim($node);
                        } 
                        $discussioniXml = new DOMDocument();
                        if (!$discussioniXml->loadXML($xmlString)) {
                            die ("Parsing error\n");
                        }
                        $docElem = $discussioniXml->documentElement;
                        $discussioni = $docElem->childNodes; 
                        for ($i=0; $i<$discussioni->length; $i++) { 
                            $discussione = $discussioni->item($i); 
                            $id_discussione = $discussione->getAttribute('id'); 
                            if ($idDiscussioneValue == $id_discussione){
                                $nodo_numCommenti = $discussione->firstChild->nextSibling->nextSibling->nextSibling;
                                $nodo_numCommenti_text = $nodo_numCommenti->textContent;
                                $newNumCommentiValue = (int)$nodo_numCommenti_text + 1;
                                $nodo_numCommentiValue = $nodo_numCommenti->nodeValue = $newNumCommentiValue;  
                                break;
                            }
                        } 
            
                        if (!($commentiXml->save("xml/commenti.xml") && $discussioniXml->save("xml/discussioni.xml"))) {
                            if(isset($msg['error'])){
                                $msg['error'].="Salvataggio dati fallito<br />";
                            }
                            else{
                                $msg['error']="Salvataggio dati fallito<br />";
                            }
                        }
                    }
                } 

                if(isset($_POST['voteConfirm'])){ 
                    if((isset($_POST['votoUtilitaValue']) && !empty($_POST['votoUtilitaValue'])) && (isset($_POST['votoAffinitaValue']) && !empty($_POST['votoAffinitaValue']))){
                        $xmlString = "";
                        foreach ( file("xml/giudiziCommenti.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $giudiziCommentiXml = new DOMDocument();
                        if (!$giudiziCommentiXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }
                        $docElem = $giudiziCommentiXml->documentElement;
                        $giudiziCommenti = $docElem->childNodes;

                        for ($c=0; $c<$giudiziCommenti->length; $c++) {
                            $giudizioCommento = $giudiziCommenti->item($c);
                            $id_giuC = $giudizioCommento->getAttribute('id');
                            $idCommentoFromGiudizio = $giudizioCommento->firstChild;
                            $idCommentoFromGiudizioValue = $idCommentoFromGiudizio->textContent;
                            if($_POST['idCommentoValue']==$idCommentoFromGiudizioValue){
                                $idUtenteFromGiudizio = $idCommentoFromGiudizio->nextSibling;
                                $idUtenteFromGiudizioValue = $idUtenteFromGiudizio->textContent;
                                if($_SESSION['id']==$idUtenteFromGiudizioValue){
                                    $votiCommentiGiaEspressi="yes";
                                    break;
                                }
                            }
                        }
                        if ($votiCommentiGiaEspressi){
                            if(isset($msg['error'])){
                                $msg['error'].="I voti sono già stati espressi<br />";
                            }
                            else{
                                $msg['error']="I voti sono già stati espressi<br />";
                            }
                        }
                    }else{
                        if(isset($msg['error'])){
                        $msg['error'].="Entrambi i voti sono obbligatori<br />";
                        }
                        else{
                        $msg['error']="Entrambi i voti sono obbligatori<br />";
                        }
                    }

                    if(!isset($msg['error']) && !$votiCommentiGiaEspressi){
                        $xmlString = "";
                        foreach ( file("xml/giudiziCommenti.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $giudiziCommentiXml = new DOMDocument();
                        if (!$giudiziCommentiXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }
                        $docElem = $giudiziCommentiXml->documentElement;
                        $giudiziCommenti = $docElem->childNodes;

                        for ($l=0; $l<$giudiziCommenti->length; $l++) {
                            $giudizioCommento = $giudiziCommenti->item($l);
                            $id_giuC = $giudizioCommento->getAttribute('id');
                        }
                        $last_judgment=$giudizioCommento;
                        $lastGiudizioCommentoId = (int)$id_giuC;
                        $idGiudizioCommentoValue=$lastGiudizioCommentoId+1;
                        $new_judgment = $giudiziCommentiXml->createElement("giudizioCommento");
                        $new_judgment->setAttribute("id", $idGiudizioCommentoValue);
                        $docElem->appendChild($new_judgment);
                        
                        $idCommentoValue=(int)$_POST['idCommentoValue'];
                        $idCommen = $giudiziCommentiXml->createElement("idCommento");
                        $idCommen_text = $giudiziCommentiXml->createTextNode($idCommentoValue);
                        $idCommen->appendChild($idCommen_text);
                        $new_judgment->appendChild($idCommen);

                        $idUtenteValue=(int)$_SESSION['id'];
                        $idUten = $giudiziCommentiXml->createElement("idUtente");
                        $idUten_text = $giudiziCommentiXml->createTextNode($idUtenteValue);
                        $idUten->appendChild($idUten_text);
                        $new_judgment->appendChild($idUten);

                        $votoUtilitaValue=(int)$_POST['votoUtilitaValue'];
                        $votoUtilita = $giudiziCommentiXml->createElement("votoUtilita");
                        $votoUtilita_text = $giudiziCommentiXml->createTextNode($votoUtilitaValue);
                        $votoUtilita->appendChild($votoUtilita_text);
                        $new_judgment->appendChild($votoUtilita);

                        $votoAffinitaValue=(int)$_POST['votoAffinitaValue'];
                        $votoAffinita = $giudiziCommentiXml->createElement("votoAffinita");
                        $votoAffinita_text = $giudiziCommentiXml->createTextNode($votoAffinitaValue);
                        $votoAffinita->appendChild($votoAffinita_text);
                        $new_judgment->appendChild($votoAffinita); 

                        $xmlString = "";
                        foreach ( file("xml/commenti.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $commentiXml = new DOMDocument();
                        if (!$commentiXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }
                        $docElem = $commentiXml->documentElement;
                        $commenti = $docElem->childNodes;
                        for ($i=0; $i<$commenti->length; $i++) { 
                            $commento = $commenti->item($i);
                            $id_commento = $commento->getAttribute('id');
                            
                            if ($idCommentoValue == $id_commento){ 
                                $idResponsabileCommento = $commento->firstChild;
                                $idResponsabileCommentoValue = $idResponsabileCommento->textContent;

                                $sumUtilita=0;
                                $sumAffinita=0;
                                $newNumVotiUtilitaValue=0;
                                $newNumVotiAffinitaValue=0;
                                for ($n=0; $n<$giudiziCommenti->length; $n++) {
                                    $giudizioCommento = $giudiziCommenti->item($n);
                                    $idCommentoFromGiudizio = $giudizioCommento->firstChild;
                                    $idCommentoFromGiudizioValue = $idCommentoFromGiudizio->textContent;
                                    if ($idCommentoFromGiudizioValue == $idCommentoValue){
                                        $votoForVotoUtilitaMedia = $idCommentoFromGiudizio->nextSibling->nextSibling;
                                        $votoForVotoUtilitaMediaValue = $votoForVotoUtilitaMedia->textContent;
                                        $sumUtilita+=(int)$votoForVotoUtilitaMediaValue; 
                                        $newNumVotiUtilitaValue+=1;

                                        $votoForVotoAffinitaMedia = $votoForVotoUtilitaMedia->nextSibling;
                                        $votoForVotoAffinitaMediaValue = $votoForVotoAffinitaMedia->textContent;
                                        $sumAffinita+=(int)$votoForVotoAffinitaMediaValue; 
                                        $newNumVotiAffinitaValue+=1;
                                        /* questo va ripetuto per ottennere i voti di tutti i giudizi sul commento in questione */
                                    }
                                }
                                $newVotoUtilitaMediaValue = $sumUtilita/$newNumVotiUtilitaValue;
                                if($newNumVotiUtilitaValue==1){
                                    $newVotoUtilitaMediaValue=$votoForVotoUtilitaMediaValue;
                                }
                                $newVotoAffinitaMediaValue = $sumAffinita/$newNumVotiAffinitaValue;
                                if($newNumVotiAffinitaValue==1){
                                    $newVotoAffinitaMediaValue=$votoForVotoAffinitaMediaValue;
                                }

                                $nodo_votoUtilitaMedia = $commento->firstChild->nextSibling->nextSibling->nextSibling->nextSibling;
                                $nodo_votoUtilitaMediaValue = $nodo_votoUtilitaMedia->nodeValue = $newVotoUtilitaMediaValue; 

                                $nodo_numVotiUtilita = $nodo_votoUtilitaMedia->nextSibling; 
                                $nodo_numVotiUtilitaValue = $nodo_numVotiUtilita->nodeValue = $newNumVotiUtilitaValue; 

                                $nodo_votoAffinitaMedia = $nodo_numVotiUtilita->nextSibling;
                                $nodo_votoAffinitaMediaValue = $nodo_votoAffinitaMedia->nodeValue = $newVotoAffinitaMediaValue; 

                                $nodo_numVotiAffinita = $nodo_votoAffinitaMedia->nextSibling; 
                                $nodo_numVotiAffinitaValue = $nodo_numVotiAffinita->nodeValue = $newNumVotiAffinitaValue; 
                            }
                        }

                        $votoValue=($votoUtilitaValue+$votoAffinitaValue)/2;
                        $reputazione=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT reputazione FROM utente WHERE id=".(int)$idResponsabileCommentoValue));
                        $reputazione=(float)$reputazione['reputazione'];
                        if ($votoValue==1){
                            $influenzaVoto=-0.2;
                        }else if ($votoValue>1 && $votoValue<=2){
                            $influenzaVoto=-0.1;
                        }else if ($votoValue>2 && $votoValue<=3){
                            $influenzaVoto=0;
                        }else if ($votoValue>3 && $votoValue<=4){
                            $influenzaVoto=0.1;
                        }else if ($votoValue>4 && $votoValue<=5){
                            $influenzaVoto=0.2;
                        }
                        $newReputazione=$reputazione+$influenzaVoto;
                        if($newReputazione>=0 && $newReputazione<=1000){
                            $result=mysqli_query($mysqliConnection,"UPDATE utente SET reputazione=".$newReputazione." WHERE id=".$idResponsabileCommentoValue);
                        }else{
                            $result="ok";
                        }

                        if (!($giudiziCommentiXml->save("xml/giudiziCommenti.xml") && $commentiXml->save("xml/commenti.xml") && $result)) {
                            if(isset($msg['error'])){
                                $msg['error'].="Salvataggio dati fallito<br />";
                            }
                            else{
                                $msg['error']="Salvataggio dati fallito<br />";
                            }
                        }
                    }

                    if(!isset($msg['error'])){
                        $msg['success']="Salvataggio effettuato con successo";
                    }
                }

                if(isset($_POST['modDiscussion'])){ 
                    if(isset($_POST['titoloDiscussioneValue']) && !empty($_POST['titoloDiscussioneValue'])){
                        $xmlString = "";
                        foreach ( file("xml/discussioni.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $discussioniXml = new DOMDocument();
                        if (!$discussioniXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }
                        $docElem = $discussioniXml->documentElement;
                        $discussioni = $docElem->childNodes;
                        for ($j=0; $j<$discussioni->length; $j++) {
                            $discussione = $discussioni->item($j);
                            $id_Disc = $discussione->getAttribute('id'); 

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
                            $idDiscussione=$progetto->firstChild->nextSibling;
                            $idDiscussioneValue=$idDiscussione->textContent;
                            if($idDiscussioneValue == $id_Disc){
                                $titleDisc = $discussione->firstChild->nextSibling;
                                $titleDiscValue = $titleDisc->textContent;
                                if($_POST['titoloDiscussioneValue']==$titleDiscValue){
                                    $titleMod="no";
                                    break;
                                }else{
                                    $nodo_titoloDiscussione = $discussione->firstChild->nextSibling;
                                    $nodo_titoloDiscussioneValue = $titleDisc->nodeValue = $_POST['titoloDiscussioneValue'];
                                    $discussioniXml->save("xml/discussioni.xml"); /* Se questa istruzione la tolgo, non mi salva una eventuale modifica del titolo della discussione. Questo poiché il salvataggio di sotto non mi copre quanto scritto alla riga sopra. */
                                    break;
                                }
                            }
                        }
                    }
                    
                    if(isset($_POST['descrizioneValue']) && !empty($_POST['descrizioneValue'])){ 
                        $xmlString = "";
                        foreach ( file("xml/discussioni.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $discussioniXml = new DOMDocument();
                        if (!$discussioniXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }
                        $docElem = $discussioniXml->documentElement;
                        $discussioni = $docElem->childNodes;
                        for ($j=0; $j<$discussioni->length; $j++) { 
                            $discussione = $discussioni->item($j);
                            $id_Disc = $discussione->getAttribute('id'); 

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
                            $idDiscussione=$progetto->firstChild->nextSibling;
                            $idDiscussioneValue=$idDiscussione->textContent;
                            if($idDiscussioneValue == $id_Disc){ 
                                $descriptionDisc = $discussione->firstChild->nextSibling->nextSibling; 
                                $descriptionDiscValue = $descriptionDisc->textContent; 
                                if($_POST['descrizioneValue']==$descriptionDiscValue){ 
                                    $descriptionMod="no";
                                    break;
                                }else{ 
                                    $nodo_descrizioneDiscussione = $discussione->firstChild->nextSibling->nextSibling;
                                    $nodo_descrizioneDiscussioneValue = $descriptionDisc->nodeValue = $_POST['descrizioneValue'];  
                                    break;
                                }
                            }
                        }
                    }

                    if($titleMod=="no" && $descriptionMod=="no"){
                        if(isset($msg['error'])){
                          $msg['error'].="Nessuna modifica effettuata<br />";
                        }
                        else{
                          $msg['error']="Nessuna modifica effettuata<br />";
                        }
                    }else{
                        if(!isset($msg['error']) && ($discussioniXml->save("xml/discussioni.xml"))){
                          if(isset($msg['success'])){
                            $msg['success'].="Salvataggio effettuato con successo<br />";
                          }
                          else{
                            $msg['success']="Salvataggio effettuato con successo<br />";
                          }
                        } else {
                          if(isset($msg['error'])){
                            $msg['error'].="Salvataggio dati fallito<br />";
                          }
                          else{
                            $msg['error']="Salvataggio dati fallito<br />";
                          }
                        }
                    }   
                }

                if(isset($_POST['modComment'])){ 
                    if(isset($_POST['idCommentoValue']) && !empty($_POST['idCommentoValue'])){
                        $xmlString = "";
                        foreach ( file("xml/commenti.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $commentiXml = new DOMDocument();
                        if (!$commentiXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }
                        $docElem = $commentiXml->documentElement;
                        $commenti = $docElem->childNodes;

                        $xpath=new DOMXpath($commentiXml); 
                        $commentoEsiste=$xpath->query("//commento[@id='".(int)$_POST['idCommentoValue']."']")->item(0);
                        if (!$commentoEsiste){
                            if(isset($msg['error'])){
                                $msg['error'].="Il commento selezionato non esiste<br />";
                            }
                            else{
                                $msg['error']="Il commento selezionato non esiste<br />";
                            }
                        }
                    }else{
                        if(isset($msg['error'])){
                        $msg['error'].="L'id del commento è obbligatorio<br />";
                        }
                        else{
                        $msg['error']="L'id del commento è obbligatorio<br />";
                        }
                    }

                    if(isset($_POST['newTestoValue']) && !empty($_POST['newTestoValue'])){
                        #tutto ok
                    }else{
                        if(isset($msg['error'])){
                        $msg['error'].="Il testo è obbligatorio<br />";
                        }
                        else{
                        $msg['error']="Il testo è obbligatorio<br />";
                        }
                    }
                    
                    if(!isset($msg['error'])){
                        $xmlString = "";
                        foreach ( file("xml/commenti.xml") as $node ) {
                            $xmlString .= trim($node); 
                        }
                        $commentiXml = new DOMDocument(); 
                        if (!$commentiXml->loadXML($xmlString)) { 
                            die ("Parsing error\n");
                        }
                        $docElem = $commentiXml->documentElement; 
                        $commenti = $docElem->childNodes;
                
                        for ($i=0; $i<$commenti->length; $i++) {
                            $commento = $commenti->item($i);
                            $id_com = $commento->getAttribute('id');
                            if($_POST['idCommentoValue']==$id_com){
                                $nodo_testoCommento = $commento->firstChild->nextSibling->nextSibling; 
                                $nodo_testoCommentoValue = $nodo_testoCommento->nodeValue = $_POST['newTestoValue'];  
                                break;
                            }
                        }
            
                        if (!($commentiXml->save("xml/commenti.xml"))) {
                            if(isset($msg['error'])){
                                $msg['error'].="Salvataggio dati fallito<br />";
                            }
                            else{
                                $msg['error']="Salvataggio dati fallito<br />";
                            }
                        }
                    }
                }

                if(isset($_POST['deleteComment'])){ 
                    if(isset($_POST['idCommentoValue']) && !empty($_POST['idCommentoValue'])){ 
                        $xmlString = "";
                        foreach ( file("xml/commenti.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $commentiXml = new DOMDocument();
                        if (!$commentiXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }
                        $docElem = $commentiXml->documentElement;
                        $commenti = $docElem->childNodes; 

                        $xpath=new DOMXpath($commentiXml);
                        $commentoEsiste=$xpath->query("//commento[@id='".(int)$_POST['idCommentoValue']."']")->item(0);
                        if (!$commentoEsiste){ 
                            if(isset($msg['error'])){
                                $msg['error'].="Il commento selezionato non esiste<br />";
                            }
                            else{
                                $msg['error']="Il commento selezionato non esiste<br />";
                            }
                        }
                    }else{
                        if(isset($msg['error'])){
                        $msg['error'].="L'id del commento è obbligatorio<br />";
                        }
                        else{
                        $msg['error']="L'id del commento è obbligatorio<br />";
                        }
                    }
                    
                    if(!isset($msg['error'])){
                        $xmlString = "";
                        foreach ( file("xml/commenti.xml") as $node ) {
                            $xmlString .= trim($node);
                        }
                        $commentiXml = new DOMDocument();
                        if (!$commentiXml->loadXML($xmlString)) {
                            die ("Parsing error\n");
                        }
                        $docElem = $commentiXml->documentElement;
                        $commenti = $docElem->childNodes;
                        for ($i=0; $i<$commenti->length; $i++) {
                            $commento = $commenti->item($i);
                            $id_com = $commento->getAttribute('id');
                            if($_POST['idCommentoValue']==$id_com){
                                $commento->parentNode->removeChild($commento);
                                break;
                            }
                        }

                        $xmlString = "";
                        foreach ( file("xml/giudiziCommenti.xml") as $node ) {
                            $xmlString .= trim($node);
                        }
                        $giudiziCommentiXml = new DOMDocument();
                        if (!$giudiziCommentiXml->loadXML($xmlString)) {
                            die ("Parsing error\n");
                        }
                        $docElem = $giudiziCommentiXml->documentElement;
                        $giudiziCommenti = $docElem->childNodes;
                        for ($g=0; $g<$giudiziCommenti->length; $g++) {
                            $giudizioCommento = $giudiziCommenti->item($g);
                            $id_giuCom = $giudizioCommento->getAttribute('id');
                            $idCommentoFromGiudizioCommento = $giudizioCommento->firstChild;
                            $idCommentoFromGiudizioCommentoValue = $idCommentoFromGiudizioCommento->textContent;
                            if($_POST['idCommentoValue']==$idCommentoFromGiudizioCommentoValue){
                                $giudizioCommento->parentNode->removeChild($giudizioCommento);
                                break;
                            }
                        }
            
                        $xmlString = "";
                        foreach ( file("xml/discussioni.xml") as $node ) {
                            $xmlString .= trim($node);
                        } 
                        $discussioniXml = new DOMDocument();
                        if (!$discussioniXml->loadXML($xmlString)) {
                            die ("Parsing error\n");
                        }
                        $docElem = $discussioniXml->documentElement;
                        $discussioni = $docElem->childNodes; 
                        for ($i=0; $i<$discussioni->length; $i++) { 
                            $discussione = $discussioni->item($i); 
                            $id_discussione = $discussione->getAttribute('id'); 
                            if ($idDiscussioneValue == $id_discussione){
                                $nodo_numCommenti = $discussione->firstChild->nextSibling->nextSibling->nextSibling;
                                $nodo_numCommenti_text = $nodo_numCommenti->textContent;
                                $newNumCommentiValue = (int)$nodo_numCommenti_text - 1;
                                $nodo_numCommentiValue = $nodo_numCommenti->nodeValue = $newNumCommentiValue;  
                                break;
                            }
                        } 
            
                        if (!($commentiXml->save("xml/commenti.xml") && $giudiziCommentiXml->save("xml/giudiziCommenti.xml") && $discussioniXml->save("xml/discussioni.xml"))) {
                            if(isset($msg['error'])){
                                $msg['error'].="Salvataggio dati fallito<br />";
                            }
                            else{
                                $msg['error']="Salvataggio dati fallito<br />";
                            }
                        }
                    }
                }


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
                    $idDiscussioneFromAutoriz=$idResponsabile->nextSibling;
                    $idDiscussioneFromAutorizValue=$idDiscussioneFromAutoriz->textContent; 
                    if($idDiscussioneValue==$idDiscussioneFromAutorizValue){
                        if($_SESSION['id']==$idUtenteValue){
                            $esito=$idDiscussioneFromAutoriz->nextSibling; 
                            $esitoValue=$esito->textContent;
                            if($esitoValue=="positivo"){
                                $autorizzato = "yes";
                                break;
                            }
                        }else if($_SESSION['id']==$idResponsabileValue){ 
                            $responsabile="yes";
                            break;
                        }
                    }
                } 
                if($autorizzato || $responsabile || $_SESSION['ruolo']=="moderatore" || $_SESSION['ruolo']=="admin"){
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

            <!-- È fondamentale che questa istruzione vada prima,
            poiché in tal modo se l'utente è moderatore e anche
            responsabile del progetto, viene prima considerato
            come responsabile del progetto. -->
            <?php if($responsabile || $_SESSION['ruolo']=="admin"){ ?> 
                <div align="center">
                    <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 82rem; position: relative; top: 1rem;" >
                        <div class="card-body">
                            <h5 class="card-header" style="background-color: white; color: black">
                                Modifica la discussione
                            </h5>

                            <form method="post" <?php echo 'action="manage_discussions.php?id='. $idProgettoValue . '"' ?>>
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
                                            <input type="text" class="form-control" name="titoloDiscussioneValue" value="<?php echo $titoloDiscussioneValue ?>">
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
                                    <textarea type="text" class="form-control" name="descrizioneValue"><?php echo $descrizioneValue ?></textarea>
                                </div>
                                
                                <input type="submit" style="float: center;" class="btn btn-dark" name="modDiscussion" value="Conferma modifiche">
                            </form>

                        </div>
                    </div>
                </div>
                <br />
            <?php }else if($autorizzato || $_SESSION['ruolo']=="moderatore"){ ?> 
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
            <?php } ?>

            <?php if($_SESSION['ruolo']=="moderatore" || $_SESSION['ruolo']=="admin"){ ?> 
                <div class="row" style="width: 100%;">
                    <div class="col-sm-6">
                        <div align="center">
                                <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 40rem; position: relative; top: 1rem; left: 2.7%" >
                                    <div class="card-body">
                                        <h5 class="card-header" style="background-color: white; color: black">
                                            Modifica un commento
                                        </h5>

                                        <form method="post" <?php echo 'action="manage_discussions.php?id='. $idProgettoValue . '"' ?>>
                                            <div class="input-group mb-3" style="height: 5.5rem;">
                                                <div class="input-group-prepend">
                                                    <span style="width: 4rem;" class="input-group-text" id="inputGroup-sizing-default">Testo</span>
                                                </div>
                                                <textarea type="text" class="form-control" name="newTestoValue"></textarea>
                                            </div>
                                            <div class="row" style="width: 128%;">
                                                <div class="col-sm-2">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 2.5rem;" class="input-group-text" id="inputGroup-sizing-default">ID</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="idCommentoValue">
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                                        <label class="form-check-label" for="invalidCheck2">
                                                            Sono sicuro della modifica da effettuare al commento.
                                                        </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2.5">
                                                    <input type="submit" style="float: center; width: 5.3rem;" class="btn btn-dark" name="modComment" value="Modifica">
                                                </div>
                                                <div class="col-sm-2.5">
                                                    <input type="submit" style="float: center; width: 5rem;" class="btn btn-dark" name="deleteComment" value="Elimina">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <br />
                            </div>
                        </div>
                    <div class="col-sm-6">
                        <div align="center">
                            <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 40rem; height: 16.25rem; position: relative; top: 1rem; left: 2%" >
                                <div class="card-body">
                                    <h5 class="card-header" style="background-color: white; color: black">
                                        Posta un commento
                                    </h5>

                                    <form method="post" <?php echo 'action="manage_discussions.php?id='. $idProgettoValue . '"' ?>>
                                        <div class="input-group mb-3" style="height: 5.5rem;">
                                            <div class="input-group-prepend">
                                                <span style="width: 4rem;" class="input-group-text" id="inputGroup-sizing-default">Testo</span>
                                            </div>
                                            <textarea type="text" class="form-control" name="newTestoValue"></textarea>
                                        </div>
                                        <input type="submit" style="float: center; width: 5rem;" class="btn btn-dark" name="post" value="Posta">
                                    </form>
                                </div>
                            </div>
                            <br />
                        </div>
                    </div>
                </div>
            <?php }else if($_SESSION['ruolo']=="utente interno"){ ?> 
                <div align="center">
                    <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 82rem; position: relative; top: 1rem;" >
                        <div class="card-body">
                            <h5 class="card-header" style="background-color: white; color: black">
                                Posta un commento
                            </h5>

                            <form method="post" <?php echo 'action="manage_discussions.php?id='. $idProgettoValue . '"' ?>>
                                <div class="input-group mb-3" style="height: 5.5rem;">
                                    <div class="input-group-prepend">
                                        <span style="width: 4rem;" class="input-group-text" id="inputGroup-sizing-default">Testo</span>
                                    </div>
                                    <textarea type="text" class="form-control" name="newTestoValue"></textarea>
                                </div>
                                <input type="submit" style="float: center; width: 5rem;" class="btn btn-dark" name="post" value="Posta">
                            </form>
                        </div>
                    </div>
                    <br />
                </div>
            <?php } ?> 

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
                                    $idCommentoValue = $commento->getAttribute('id');

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
                                                            <input type="text" class="form-control" name="idCommentoValue" value="<?php echo $idCommentoValue ?>" disabled>
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

                                                <form method="post" <?php echo 'action="manage_discussions.php?id='. $idProgettoValue . '"' ?>>
                                                    <div class="row" style="width: 108.2%;">
                                                        <div class="input-group mb-3" hidden>
                                                            <div class="input-group-prepend">
                                                                <span style="width: 7.5rem;" class="input-group-text" id="inputGroup-sizing-default">ID Commento</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="idCommentoValue" value="<?php echo $idCommentoValue ?>">
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend"> <!-- ha ha! ho scoperto che il problema stava nell'id delle star-->
                                                                <label style="width: 4rem;" class="input-group-text" for="inputGroupSelect01">Utilità</label>
                                                                </div>
                                                                <fieldset class="rating">
                                                                    <?php
                                                                        if(!$upperbound && !$lowerbound){
                                                                            $upperbound=5;
                                                                            $lowerbound=1;
                                                                        }
                                                                    
                                                                        for ($num_stelle=$upperbound; $num_stelle>=$lowerbound; ) {
                                                                            echo '<input class="form-check-input" type="radio" name="votoUtilitaValue" id="star'.$num_stelle.'" value="5" />
                                                                            <label class="form-check-label" for="star'.$num_stelle.'" >'.$num_stelle.'</label>';
                                                                            $num_stelle--;
                                                                            echo '<input class="form-check-input" type="radio" name="votoUtilitaValue" id="star'.$num_stelle.'" value="4" />
                                                                            <label class="form-check-label" for="star'.$num_stelle.'" >'.$num_stelle.'</label>';
                                                                            $num_stelle--;
                                                                            echo '<input class="form-check-input" type="radio" name="votoUtilitaValue" id="star'.$num_stelle.'" value="3" />
                                                                            <label class="form-check-label" for="star'.$num_stelle.'" >'.$num_stelle.'</label>';
                                                                            $num_stelle--;
                                                                            echo '<input class="form-check-input" type="radio" name="votoUtilitaValue" id="star'.$num_stelle.'" value="2" />
                                                                            <label class="form-check-label" for="star'.$num_stelle.'" >'.$num_stelle.'</label>';
                                                                            $num_stelle--;
                                                                            echo '<input class="form-check-input" type="radio" name="votoUtilitaValue" id="star'.$num_stelle.'" value="1" />
                                                                            <label class="form-check-label" for="star'.$num_stelle.'" >'.$num_stelle.'</label>';
                                                                            $num_stelle--;
                                                                        } 
                                                                        $upperbound+=5;
                                                                        $lowerbound+=5;
                                                                    ?>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                <label style="width: 4.3rem;" class="input-group-text" for="inputGroupSelect01">Affinità</label>
                                                                </div>
                                                                <fieldset class="rating">
                                                                    <?php
                                                                        for ($num_stelle=$upperbound; $num_stelle>=$lowerbound; ) {
                                                                            echo '<input class="form-check-input" type="radio" name="votoAffinitaValue" id="star'.$num_stelle.'" value="5" />
                                                                            <label class="form-check-label" for="star'.$num_stelle.'" >'.$num_stelle.'</label>';
                                                                            $num_stelle--;
                                                                            echo '<input class="form-check-input" type="radio" name="votoAffinitaValue" id="star'.$num_stelle.'" value="4" />
                                                                            <label class="form-check-label" for="star'.$num_stelle.'" >'.$num_stelle.'</label>';
                                                                            $num_stelle--;
                                                                            echo '<input class="form-check-input" type="radio" name="votoAffinitaValue" id="star'.$num_stelle.'" value="3" />
                                                                            <label class="form-check-label" for="star'.$num_stelle.'" >'.$num_stelle.'</label>';
                                                                            $num_stelle--;
                                                                            echo '<input class="form-check-input" type="radio" name="votoAffinitaValue" id="star'.$num_stelle.'" value="2" />
                                                                            <label class="form-check-label" for="star'.$num_stelle.'" >'.$num_stelle.'</label>';
                                                                            $num_stelle--;
                                                                            echo '<input class="form-check-input" type="radio" name="votoAffinitaValue" id="star'.$num_stelle.'" value="1" />
                                                                            <label class="form-check-label" for="star'.$num_stelle.'" >'.$num_stelle.'</label>';
                                                                            $num_stelle--;
                                                                        } 
                                                                        $upperbound+=5;
                                                                        $lowerbound+=5;
                                                                    ?>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="submit" style="float: center; width: 2.5rem;" class="btn btn-dark" name="voteConfirm" value=&#10004>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group mb-3" style="height: 9.2rem;">
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
    <?php 
            }else{
    ?>
        <div class="card text-center" style="opacity:85%; border:solid 2px black; width: 30rem; position: relative; left: 32%; top: 8.5rem;" >
            <div class="card-body">
              <h5 class="card-header" style="background-color: white; color: black">
                Account non autorizzato
              </h5>

              <br />
              <h1>Non sei autorizzato a partecipare a questa discussione.</h1>
            </div>
        </div>
          <br /><br /><br /><br />
    <?php
            }
        }
    ?>

</html>