<?php 
    require_once("connect.php");

    $nomeCognomeSessione=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT nome, cognome FROM utente WHERE id='".$_SESSION['id']."'"));
    $nomeCognomeSessione=$nomeCognomeSessione['nome']." ".$nomeCognomeSessione['cognome'];

    if(isset($_POST['vote'])){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $xmlString = "";
            foreach ( file("xml/progetti.xml") as $node ) {
            $xmlString .= trim($node);
            }
            $progettiXml = new DOMDocument();
            if (!$progettiXml->loadXML($xmlString)) {
            die ("Parsing error\n");
            }
            $docElem = $progettiXml->documentElement;
            $progetti = $docElem->childNodes;
            
            $xpath=new DOMXpath($progettiXml);
            $idProgettoEsistente=$xpath->query("//progetto[@id='".(int)$_GET['id']."']")->item(0);
            if (!$idProgettoEsistente){
                if(isset($msg['error'])){
                    $msg['error'].="Il progetto che si intende valutare non esiste<br />";
                }
                else{
                    $msg['error']="Il progetto che si intende valutare non esiste<br />";
                }
            }
        }else{
            if(isset($msg['error'])){
            $msg['error'].="L'id del progetto non esiste<br />";
            }
            else{
            $msg['error']="L'id del progetto non esiste<br />";
            }
        }
        
        if(isset($_POST['votoValue']) && !empty($_POST['votoValue'])){
            $xmlString = "";
            foreach ( file("xml/giudiziProgetti.xml") as $node ) {
            $xmlString .= trim($node);
            }
            $giudiziProgettiXml = new DOMDocument();
            if (!$giudiziProgettiXml->loadXML($xmlString)) {
            die ("Parsing error\n");
            }
            $docElem = $giudiziProgettiXml->documentElement;
            $giudiziProgetti = $docElem->childNodes;

            for ($c=0; $c<$giudiziProgetti->length; $c++) {
                $giudizioProgetto = $giudiziProgetti->item($c);
                $id_giuP = $giudizioProgetto->getAttribute('id');
                $idProgettoFromGiudizio = $giudizioProgetto->firstChild;
                $idProgettoFromGiudizioValue = $idProgettoFromGiudizio->textContent;
                if((int)$_GET['id']==$idProgettoFromGiudizioValue){
                    $idUtenteFromGiudizio = $idProgettoFromGiudizio->nextSibling;
                    $idUtenteFromGiudizioValue = $idUtenteFromGiudizio->textContent;
                    if($_SESSION['id']==$idUtenteFromGiudizioValue){
                        $votoProgettoGiaEspresso="yes";
                        break;
                    }
                }
            }
            if ($votoProgettoGiaEspresso){
                if(isset($msg['error'])){
                    $msg['error'].="Il voto è già stato espresso<br />";
                }
                else{
                    $msg['error']="Il voto è già stato espresso<br />";
                }
            }
        }else{
            if(isset($msg['error'])){
            $msg['error'].="Il voto è obbligatorio<br />";
            }
            else{
            $msg['error']="Il voto è obbligatorio<br />";
            }
        }

        if(isset($_POST['testoValue']) && !empty($_POST['testoValue'])){
            #tutto ok
        }else{
            if(isset($msg['error'])){
            $msg['error'].="La testo è obbligatorio<br />";
            }
            else{
            $msg['error']="La testo è obbligatoroa<br />";
            }
        }

        if(!isset($msg['error']) && $idProgettoEsistente && !$votoProgettoGiaEspresso){ 
            $xmlString = "";
            foreach ( file("xml/giudiziProgetti.xml") as $node ) {
            $xmlString .= trim($node);
            }
            $giudiziProgettiXml = new DOMDocument();
            if (!$giudiziProgettiXml->loadXML($xmlString)) {
            die ("Parsing error\n");
            }
            $docElem = $giudiziProgettiXml->documentElement;
            $giudiziProgetti = $docElem->childNodes;

            for ($l=0; $l<$giudiziProgetti->length; $l++) {
                $giudizioProgetto = $giudiziProgetti->item($l);
                $id_giuP = $giudizioProgetto->getAttribute('id');
            }
            $last_judgment=$giudizioProgetto;
            $lastGiudizioProgettoId = (int)$id_giuP;
            $idGiudizioProgettoValue=$lastGiudizioProgettoId+1;
            $new_judgment = $giudiziProgettiXml->createElement("giudizioProgetto");
            $new_judgment->setAttribute("id", $idGiudizioProgettoValue);
            $docElem->appendChild($new_judgment);
            
            $idProgettoValue=(int)$_GET['id'];
            $idProget = $giudiziProgettiXml->createElement("idProgetto");
            $idProget_text = $giudiziProgettiXml->createTextNode($idProgettoValue);
            $idProget->appendChild($idProget_text);
            $new_judgment->appendChild($idProget);

            $idUtenteValue=(int)$_SESSION['id'];
            $idUten = $giudiziProgettiXml->createElement("idUtente");
            $idUten_text = $giudiziProgettiXml->createTextNode($idUtenteValue);
            $idUten->appendChild($idUten_text);
            $new_judgment->appendChild($idUten);

            $votoValue=(int)$_POST['votoValue'];
            $voto = $giudiziProgettiXml->createElement("voto");
            $voto_text = $giudiziProgettiXml->createTextNode($votoValue);
            $voto->appendChild($voto_text);
            $new_judgment->appendChild($voto);

            $testoValue=$_POST['testoValue'];
            $testo = $giudiziProgettiXml->createElement("testo");
            $testo_text = $giudiziProgettiXml->createTextNode($testoValue);
            $testo->appendChild($testo_text);
            $new_judgment->appendChild($testo);


            for ($i=0; $i<$progetti->length; $i++) { 
                $progetto = $progetti->item($i);
                $id_progetto = $progetto->getAttribute('id'); 

                if ($idProgettoValue == $id_progetto){ 
                    $sum=0;
                    $newNumGiudiziProjValue=0;
                    for ($n=0; $n<$giudiziProgetti->length; $n++) { 
                        $giudizioProgetto = $giudiziProgetti->item($n);
                        $idProgettoFromGiudizio = $giudizioProgetto->firstChild;
                        $idProgettoFromGiudizioValue = $idProgettoFromGiudizio->textContent; 
                        if ($idProgettoFromGiudizioValue == $idProgettoValue){ 
                            $idResponsabile = $progetto->firstChild;
                            $idResponsabileValue = $idResponsabile->textContent;

                            $votoForGiudizioMedio = $idProgettoFromGiudizio->nextSibling->nextSibling;
                            $votoForGiudizioMedioValue = $votoForGiudizioMedio->textContent;
                            $sum+=(int)$votoForGiudizioMedioValue;
                            $newNumGiudiziProjValue+=1;
                            /* la cosa si ripete per ogni giudizio espresso */
                        }
                    }
                    $newGiudizioProjMedioValue = $sum/$newNumGiudiziProjValue; 
                    if($newNumGiudiziProjValue==1){
                        $newGiudizioProjMedioValue=$votoForGiudizioMedioValue;
                    }

                    $nodo_giudizioProjMedio = $progetto->firstChild->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling;
                    $nodo_giudizioProjMedioValue = $nodo_giudizioProjMedio->nodeValue = $newGiudizioProjMedioValue; 

                    $nodo_numGiudiziProj = $nodo_giudizioProjMedio->nextSibling; 
                    $nodo_numGiudiziProjValue = $nodo_numGiudiziProj->nodeValue = $newNumGiudiziProjValue; 
                }
            }

            $reputazione=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT reputazione FROM utente WHERE id=".(int)$idResponsabileValue));
            $reputazione=(float)$reputazione['reputazione'];
            if ($votoValue==1){
                $influenzaVoto=-1;
            }else if ($votoValue==2){
                $influenzaVoto=-0.75;
            }else if ($votoValue==3){
                $influenzaVoto=-0.5;
            }else if ($votoValue==4){
                $influenzaVoto=-0.25;
            }else if ($votoValue==5){
                $influenzaVoto=0;
            }else if ($votoValue==6){
                $influenzaVoto=0.25;
            }else if ($votoValue==7){
                $influenzaVoto=0.5;
            }else if ($votoValue==8){
                $influenzaVoto=0.75;
            }else if ($votoValue==9){
                $influenzaVoto=1;
            }else if ($votoValue==10){
                $influenzaVoto=1.25;
            }
            $newReputazione=$reputazione+$influenzaVoto; 
            if($newReputazione>=0 && $newReputazione<=1000){
                $result=mysqli_query($mysqliConnection,"UPDATE utente SET reputazione=".$newReputazione." WHERE id=".$idResponsabileValue);
            }else{
                $result="ok";
            }

            if (!($giudiziProgettiXml->save("xml/giudiziProgetti.xml") && $progettiXml->save("xml/progetti.xml") && $result)) {
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
 
        <?php if(isset($_SESSION) && $_SESSION['stato'] == 'sospeso' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
            <title>access denied</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php }else if ( (!isset($_SESSION['username'])) || (isset($_SESSION) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')) ){ ?>
            <title>LocalCommunity - Progetti</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php } ?> 
    </head>

    <?php if(isset($_SESSION) && $_SESSION['stato'] == 'sospeso' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
        <body>
            <h1>access denied</h1>
        </body>
    <?php }else if(!isset($_SESSION['id'])) { ?>
        <body style="background-repeat: no-repeat; background-size: cover;" background="img/project.jpg">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="row" style="width: 100%;">
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
                $progetti = $progettiXml->documentElement->childNodes;
                
                for ($i=0; $i<$progetti->length; $i++) {
                    $progetto = $progetti->item($i);
                    $idProgetto = $progetto->getAttribute('id');

                    if($_GET['id']==$idProgetto){
                        $idResponsabile = $progetto->firstChild;
                        $idResponsabileValue = $idResponsabile->textContent;
                        $idDiscussione = $idResponsabile->nextSibling;
                        $idDiscussioneValue = $idDiscussione->textContent;
                        $titolo = $idDiscussione->nextSibling;
                        $titoloValue = $titolo->textContent;
                        $descrizione = $titolo->nextSibling;
                        $descrizioneValue = $descrizione->textContent;
                        $categoria = $descrizione->nextSibling;
                        $categoriaValue = $categoria->textContent;
                        $fotoComponenti = $categoria->nextSibling;
                        $fotoComponentiValue = $fotoComponenti->textContent;
                        $fotoRisultato = $fotoComponenti->nextSibling;
                        $fotoRisultatoValue = $fotoRisultato->textContent;
                        $livelloDifficolta = $fotoRisultato->nextSibling;
                        $livelloDifficoltaValue = $livelloDifficolta->textContent;
                        $livelloTempoRichiesto = $livelloDifficolta->nextSibling;
                        $livelloTempoRichiestoValue = $livelloTempoRichiesto->textContent;
                        $livelloAutorizzazioneRichiesto = $livelloTempoRichiesto->nextSibling;
                        $livelloAutorizzazioneRichiestoValue = $livelloAutorizzazioneRichiesto->textContent;
                        $giudizioProjMedio = $livelloAutorizzazioneRichiesto->nextSibling;
                        $giudizioProjMedioValue = $giudizioProjMedio->textContent;
                        $numGiudiziProj = $giudizioProjMedio->nextSibling;
                        $numGiudiziProjValue = $numGiudiziProj->textContent;
                        $peso = $numGiudiziProj->nextSibling;
                        $pesoValue = $peso->textContent;
                        $stato = $progetto->lastChild;
                        $statoValue = $stato->textContent;

                        $nomeResponsabile=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT nome, cognome FROM utente WHERE id='".$idResponsabileValue."'"));
                        $nomeResponsabile=$nomeResponsabile['nome']." ".$nomeResponsabile['cognome'];

                        $xmlString = "";
                        foreach ( file("xml/discussioni.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $discussioniXml = new DOMDocument();
                        if (!$discussioniXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }

                        $xpath=new DOMXpath($discussioniXml);
                        $idDiscussioneValue=(int)$idDiscussioneValue;
                        $discussione=$xpath->query("//discussione[@id='".$idDiscussioneValue."']")->item(0);
                        $titoloDiscussione=$discussione->firstChild->nextSibling;
                        $titoloDiscussioneValue=$titoloDiscussione->textContent;

                        if ($statoValue == 'attivo' && $livelloAutorizzazioneRichiestoValue == 'novizio') {
                            ?>
                            <div align="center">
                                <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 81.4rem; position: relative; top: 2rem;" >
                                    <div class="card-body">
                                        <h5 class="card-header" style="background-color: white; color: black">
                                            <?php echo '<a href="" title="Titolo del progetto">'.$titoloValue.'</a>' ?>
                                        </h5>

                                        <?php 
                                        $searchString = " ";
                                        $replaceString = "";
                                        $originalString = $titoloValue; 
                                        
                                        $outputString = str_replace($searchString, $replaceString, $originalString, $count); 
                                        ?> 
                                        <?php
                                        $pathFotoComponenti = 'img/fotoComponenti/'. strtolower($outputString) .'Componenti.jpeg';
                                        file_put_contents($pathFotoComponenti, base64_decode($fotoComponentiValue));
                                        ?>
                                        <img src=<?php echo $pathFotoComponenti ?> alt="foto delle componenti" height="300px" width="300px">
                                        <span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
                                        <?php
                                        $pathFotoRisultato = 'img/fotoRisultato/'. strtolower($outputString) .'Risultato.jpeg';
                                        file_put_contents($pathFotoRisultato, base64_decode($fotoRisultatoValue));
                                        ?>
                                        <img src=<?php echo $pathFotoRisultato ?> alt="foto del risultato" height="300px" width="300px">
                                        
                                        <br /><br />
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend" style="height: 6rem;">
                                                <span style="width: 6.7rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione</span>
                                            </div>
                                            <textarea type="text" class="form-control" style="height: 6rem;" name="descrizioneValue" disabled><?php echo $descrizioneValue ?></textarea>
                                        </div>
                                        <div class="row" style="width: 102.5%;">
                                            <div class="col-sm-1">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <?php echo '<span style="width: 2.2rem;" class="input-group-text" id="inputGroup-sizing-default"><a href="" title="ID del progetto">ID</a></span>'; ?>
                                                    </div>
                                                    <input type="text" class="form-control" name="idProgetto" value="<?php echo $idProgetto ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 5.5rem;" class="input-group-text" id="inputGroup-sizing-default">Categoria</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="categoriaValue" value="<?php echo $categoriaValue ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 7.5rem;" class="input-group-text" id="inputGroup-sizing-default">Responsabile</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="nomeResponsabile" value="<?php echo $nomeResponsabile ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 7rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                                    </div>
                                                    <?php
                                                        echo '<output type="text" class="form-control" name="titoloDiscussioneValue" readonly><a href="discussion.php?id='. $idProgetto .'"> '. $titoloDiscussioneValue .'</a></output>';
                                                    ?>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="width: 102.5%;">
                                            <div class="col-sm-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Difficoltà</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="livelloDifficoltaValue" value="<?php echo $livelloDifficoltaValue . " &#47" . " 5" ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 4.2rem;" class="input-group-text" id="inputGroup-sizing-default">Tempo</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="livelloTempoRichiestoValue" value="<?php echo $livelloTempoRichiestoValue . " &#47" . " 5" ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <?php echo '<span style="width: 5.7rem;" class="input-group-text" id="inputGroup-sizing-default"><a href="" title="È il livello di autorizzazione richiesto dal responsabile per poter visionare il progetto">Clearance</a></span>'; ?>
                                                    </div>
                                                    <input type="text" class="form-control" name="livelloAutorizzazioneRichiestoValue" value="<?php echo $livelloAutorizzazioneRichiestoValue ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 6.2rem;" class="input-group-text" id="inputGroup-sizing-default">Voto medio</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="giudizioProjMedioValue" value="<?php echo $giudizioProjMedioValue . " &#47" . " 10" ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 4.2rem;" class="input-group-text" id="inputGroup-sizing-default">n° Voti</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="numGiudiziProjValue" value="<?php echo $numGiudiziProjValue ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 3.5rem;" class="input-group-text" id="inputGroup-sizing-default">Peso</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="pesoValue" value="<?php echo $pesoValue ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <br />
                            </div>
                            <?php
                        }
                        break;
                    }
                }
            ?>

            <?php
                $idProgettoValue=$_GET['id']; 
                $titoloProgettoValue=$_GET['progetto'];

                $xmlString = "";
                foreach ( file("xml/steps.xml") as $node ) {
                $xmlString .= trim($node);
                }
                $stepsXml = new DOMDocument();
                if (!$stepsXml->loadXML($xmlString)) {
                die ("Parsing error\n");
                }
                $steps = $stepsXml->documentElement->childNodes;

                echo '<div class="row" style="width: 100%;">';
                
                $k=1;
                for ($i=0; $i<$steps->length; $i++) {
                    $step = $steps->item($i);
                    $idStep = $step->getAttribute('id');

                    $idProgettoStep = $step->firstChild;
                    $idProgettoStepValue = $idProgettoStep->textContent;
                    $testo = $step->firstChild->nextSibling;
                    $testoValue = $testo->textContent;
                    $immagine = $testo->nextSibling;
                    $immagineValue = $immagine->textContent;

                    
                    if ($idProgettoValue == $idProgettoStepValue) {
                    ?>
                        <div class="col-sm-4">
                            <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 25rem; position: relative; left: 6%; top: 2rem;" >
                                <div class="card-body">
                                    <h5 class="card-header" style="background-color: white; color: black">
                                        Step <?php echo $k ?>
                                    </h5>

                                    <?php 
                                    $searchString = " ";
                                    $replaceString = "";
                                    $originalString = $titoloProgettoValue; 
                                    
                                    $outputString = str_replace($searchString, $replaceString, $originalString, $count); 
                                    ?> 
                                    <?php
                                    $pathImmagineStep = 'img/immaginiSteps/'. strtolower($outputString) .'Step'. $idStep .'.jpeg';
                                    file_put_contents($pathImmagineStep, base64_decode($immagineValue));
                                    ?>
                                    <img src=<?php echo $pathImmagineStep ?> alt="foto step" height="250px" width="250px">
                                    
                                    <br /><br />
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span style="width: 4rem; height: 20rem" class="input-group-text" id="inputGroup-sizing-default">Testo</span>
                                        </div>
                                        <textarea type="text" class="form-control" name="testoValue" disabled><?php echo $testoValue ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <br />
                        </div>
                    <?php
                        $k++;
                    }
                }
                echo '</div>';
            ?>
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
                $xmlString = "";
                foreach ( file("xml/progetti.xml") as $node ) {
                $xmlString .= trim($node);
                }
                $progettiXml = new DOMDocument();
                if (!$progettiXml->loadXML($xmlString)) {
                die ("Parsing error\n");
                }
                $progetti = $progettiXml->documentElement->childNodes;
                
                for ($i=0; $i<$progetti->length; $i++) {
                    $progetto = $progetti->item($i);
                    $idProgetto = $progetto->getAttribute('id');

                    if($_GET['id']==$idProgetto){
                        $idResponsabile = $progetto->firstChild;
                        $idResponsabileValue = $idResponsabile->textContent;
                        $idDiscussione = $idResponsabile->nextSibling;
                        $idDiscussioneValue = $idDiscussione->textContent;
                        $titolo = $idDiscussione->nextSibling;
                        $titoloValue = $titolo->textContent;
                        $descrizione = $titolo->nextSibling;
                        $descrizioneValue = $descrizione->textContent;
                        $categoria = $descrizione->nextSibling;
                        $categoriaValue = $categoria->textContent;
                        $fotoComponenti = $categoria->nextSibling;
                        $fotoComponentiValue = $fotoComponenti->textContent;
                        $fotoRisultato = $fotoComponenti->nextSibling;
                        $fotoRisultatoValue = $fotoRisultato->textContent;
                        $livelloDifficolta = $fotoRisultato->nextSibling;
                        $livelloDifficoltaValue = $livelloDifficolta->textContent;
                        $livelloTempoRichiesto = $livelloDifficolta->nextSibling;
                        $livelloTempoRichiestoValue = $livelloTempoRichiesto->textContent;
                        $livelloAutorizzazioneRichiesto = $livelloTempoRichiesto->nextSibling;
                        $livelloAutorizzazioneRichiestoValue = $livelloAutorizzazioneRichiesto->textContent;
                        $giudizioProjMedio = $livelloAutorizzazioneRichiesto->nextSibling;
                        $giudizioProjMedioValue = $giudizioProjMedio->textContent;
                        $numGiudiziProj = $giudizioProjMedio->nextSibling;
                        $numGiudiziProjValue = $numGiudiziProj->textContent;
                        $peso = $numGiudiziProj->nextSibling;
                        $pesoValue = $peso->textContent;
                        $stato = $progetto->lastChild;
                        $statoValue = $stato->textContent;

                        $nomeResponsabile=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT nome, cognome FROM utente WHERE id='".$idResponsabileValue."'"));
                        $nomeResponsabile=$nomeResponsabile['nome']." ".$nomeResponsabile['cognome'];

                        $xmlString = "";
                        foreach ( file("xml/discussioni.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $discussioniXml = new DOMDocument();
                        if (!$discussioniXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }

                        $xpath=new DOMXpath($discussioniXml);
                        $idDiscussioneValue=(int)$idDiscussioneValue;
                        $discussione=$xpath->query("//discussione[@id='".$idDiscussioneValue."']")->item(0);
                        $titoloDiscussione=$discussione->firstChild->nextSibling;
                        $titoloDiscussioneValue=$titoloDiscussione->textContent;

                        if (($_SESSION['livelloAutorizzazione'] == 'novizio' && $livelloAutorizzazioneRichiestoValue == 'novizio')
                            || ($_SESSION['livelloAutorizzazione'] == 'principiante' && ($livelloAutorizzazioneRichiestoValue == 'novizio' || $livelloAutorizzazioneRichiestoValue == 'principiante'))
                            || ($_SESSION['livelloAutorizzazione'] == 'intermedio' && ($livelloAutorizzazioneRichiestoValue == 'novizio' || $livelloAutorizzazioneRichiestoValue == 'principiante' || $livelloAutorizzazioneRichiestoValue == 'intermedio'))
                            || ($_SESSION['livelloAutorizzazione'] == 'specialista' && ($livelloAutorizzazioneRichiestoValue == 'novizio' || $livelloAutorizzazioneRichiestoValue == 'principiante' || $livelloAutorizzazioneRichiestoValue == 'intermedio' || $livelloAutorizzazioneRichiestoValue == 'specialista'))
                            || ($_SESSION['livelloAutorizzazione'] == 'innovatore' && ($livelloAutorizzazioneRichiestoValue == 'novizio' || $livelloAutorizzazioneRichiestoValue == 'principiante' || $livelloAutorizzazioneRichiestoValue == 'intermedio' || $livelloAutorizzazioneRichiestoValue == 'specialista' || $livelloAutorizzazioneRichiestoValue == 'innovatore'))
                            || ($_SESSION['ruolo'] == 'moderatore') || ($_SESSION['ruolo'] == 'admin')){

                            if ($statoValue == 'attivo') {
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
                                    <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 81.4rem; position: relative; top: 2rem;" >
                                        <div class="card-body">
                                            <h5 class="card-header" style="background-color: white; color: black">
                                                <?php echo '<a href="" title="Titolo del progetto">'.$titoloValue.'</a>' ?>
                                            </h5>

                                            <?php 
                                            $searchString = " ";
                                            $replaceString = "";
                                            $originalString = $titoloValue; 
                                            
                                            $outputString = str_replace($searchString, $replaceString, $originalString, $count); 
                                            ?> 
                                            <?php
                                            $pathFotoComponenti = 'img/fotoComponenti/'. strtolower($outputString) .'Componenti.jpeg';
                                            file_put_contents($pathFotoComponenti, base64_decode($fotoComponentiValue));
                                            ?>
                                            <img src=<?php echo $pathFotoComponenti ?> alt="foto delle componenti" height="300px" width="300px">
                                            <span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
                                            <?php
                                            $pathFotoRisultato = 'img/fotoRisultato/'. strtolower($outputString) .'Risultato.jpeg';
                                            file_put_contents($pathFotoRisultato, base64_decode($fotoRisultatoValue));
                                            ?>
                                            <img src=<?php echo $pathFotoRisultato ?> alt="foto del risultato" height="300px" width="300px">
                                            
                                            <br /><br />
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend" style="height: 6rem;">
                                                    <span style="width: 6.7rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione</span>
                                                </div>
                                                <textarea type="text" class="form-control" style="height: 6rem;" name="descrizioneValue" disabled><?php echo $descrizioneValue ?></textarea>
                                            </div>
                                            <div class="row" style="width: 102.5%;">
                                                <div class="col-sm-1">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <?php echo '<span style="width: 2.2rem;" class="input-group-text" id="inputGroup-sizing-default"><a href="" title="ID del progetto">ID</a></span>'; ?>
                                                        </div>
                                                        <input type="text" class="form-control" name="idProgetto" value="<?php echo $idProgetto ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 5.5rem;" class="input-group-text" id="inputGroup-sizing-default">Categoria</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="categoriaValue" value="<?php echo $categoriaValue ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 7.5rem;" class="input-group-text" id="inputGroup-sizing-default">Responsabile</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="nomeResponsabile" value="<?php echo $nomeResponsabile ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 7rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                                        </div>
                                                        <?php
                                                            echo '<output type="text" class="form-control" name="titoloDiscussioneValue" readonly><a href="discussion.php?id='. $idProgetto .'"> '. $titoloDiscussioneValue .'</a></output>';
                                                        ?>  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="width: 102.5%;">
                                                <div class="col-sm-2">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Difficoltà</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="livelloDifficoltaValue" value="<?php echo $livelloDifficoltaValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 4.2rem;" class="input-group-text" id="inputGroup-sizing-default">Tempo</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="livelloTempoRichiestoValue" value="<?php echo $livelloTempoRichiestoValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <?php echo '<span style="width: 5.7rem;" class="input-group-text" id="inputGroup-sizing-default"><a href="" title="È il livello di autorizzazione richiesto dal responsabile per poter visionare il progetto">Clearance</a></span>'; ?>
                                                        </div>
                                                        <input type="text" class="form-control" name="livelloAutorizzazioneRichiestoValue" value="<?php echo $livelloAutorizzazioneRichiestoValue ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 6.2rem;" class="input-group-text" id="inputGroup-sizing-default">Voto medio</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="giudizioProjMedioValue" value="<?php echo $giudizioProjMedioValue . " &#47" . " 10" ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 4.2rem;" class="input-group-text" id="inputGroup-sizing-default">n° Voti</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="numGiudiziProjValue" value="<?php echo $numGiudiziProjValue ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 3.5rem;" class="input-group-text" id="inputGroup-sizing-default">Peso</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="pesoValue" value="<?php echo $pesoValue ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            

                                            <hr />
                                            <form method="post" <?php echo 'action="projects.php?id='. $idProgetto .'&progetto='. $titoloValue .'"' ?>>
                                                <div class="row" style="width: 102.5%;">
                                                    <div class="col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span style="width: 9rem;" class="input-group-text" id="inputGroup-sizing-default">Testo valutazione</span>
                                                            </div>
                                                            <textarea type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="exampleFormControlTextarea1" name="testoValue" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <label style="width: 3.5rem;" class="input-group-text" for="inputGroupSelect01">Voto</label>
                                                            </div>
                                                            <fieldset class="rating">
                                                                <input class="form-check-input" type="radio" name="votoValue" id="star10" value="10" />
                                                                <label class="form-check-label" for="star10" >10</label>
                                                                <input class="form-check-input" type="radio" name="votoValue" id="star9" value="9" />
                                                                <label class="form-check-label" for="star9" >9</label>
                                                                <input class="form-check-input" type="radio" name="votoValue" id="star8" value="8" />
                                                                <label class="form-check-label" for="star8" >8</label>
                                                                <input class="form-check-input" type="radio" name="votoValue" id="star7" value="7" />
                                                                <label class="form-check-label" for="star7" >7</label>
                                                                <input class="form-check-input" type="radio" name="votoValue" id="star6" value="6" />
                                                                <label class="form-check-label" for="star6" >6</label>
                                                                <input class="form-check-input" type="radio" name="votoValue" id="star5" value="5" />
                                                                <label class="form-check-label" for="star5" >5</label>
                                                                <input class="form-check-input" type="radio" name="votoValue" id="star4" value="4" />
                                                                <label class="form-check-label" for="star4" >4</label>
                                                                <input class="form-check-input" type="radio" name="votoValue" id="star3" value="3" />
                                                                <label class="form-check-label" for="star3" >3</label>
                                                                <input class="form-check-input" type="radio" name="votoValue" id="star2" value="2" />
                                                                <label class="form-check-label" for="star2" >2</label>
                                                                <input class="form-check-input" type="radio" name="votoValue" id="star1" value="1" />
                                                                <label class="form-check-label" for="star1" >1</label>
                                                            </fieldset>
                                                        </div>
                                                        <div class="row" style="width: 106.5%;">
                                                            <div class="col-sm-10">
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span style="width: 4.5rem;" class="input-group-text" id="inputGroup-sizing-default">Autore</span>
                                                                    </div>
                                                                    <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="autoreGiudizioValue" value="<?php echo $nomeCognomeSessione ?>" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <input type="submit" style="float: center;" class="btn btn-dark" name="vote" value="Vota">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <br />
                                </div>
                                <?php
                            } else if ($statoValue == "sospeso") {
                                echo '
                                <div class="card text-center" style="opacity:85%; border:solid 2px black; width: 30rem; position: relative; left: 32%; top: 7.5rem;" >
                                    <div class="card-body">
                                    <h5 class="card-header" style="background-color: white; color: black">
                                        Progetto sospeso
                                    </h5>
                        
                                    <br />
                                    <h1>Potrai visualizzare nuovamente il tuo progetto una volta riattivato.</h1>
                                    
                                    </div>
                                </div>
                                <br /><br /><br /><br />';
                            }
                        }
                        break;
                    }
                }
            ?>

            <?php
                $idProgettoValue=$_GET['id']; 
                $titoloProgettoValue=$_GET['progetto'];

                $xmlString = "";
                foreach ( file("xml/steps.xml") as $node ) {
                $xmlString .= trim($node);
                }
                $stepsXml = new DOMDocument();
                if (!$stepsXml->loadXML($xmlString)) {
                die ("Parsing error\n");
                }
                $steps = $stepsXml->documentElement->childNodes;

                echo '<div class="row" style="width: 100%;">';
                
                $k=1;
                for ($i=0; $i<$steps->length; $i++) {
                    $step = $steps->item($i);
                    $idStep = $step->getAttribute('id');

                    $idProgettoStep = $step->firstChild;
                    $idProgettoStepValue = $idProgettoStep->textContent;
                    $testo = $step->firstChild->nextSibling;
                    $testoValue = $testo->textContent;
                    $immagine = $testo->nextSibling;
                    $immagineValue = $immagine->textContent;

                    
                    if ($idProgettoValue == $idProgettoStepValue) {
                    ?>
                        <div class="col-sm-4">
                            <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 25rem; position: relative; left: 6%; top: 2rem;" >
                                <div class="card-body">
                                    <h5 class="card-header" style="background-color: white; color: black">
                                        Step <?php echo $k ?>
                                    </h5>

                                    <?php 
                                    $searchString = " ";
                                    $replaceString = "";
                                    $originalString = $titoloProgettoValue; 
                                    
                                    $outputString = str_replace($searchString, $replaceString, $originalString, $count); 
                                    ?> 
                                    <?php
                                    $pathImmagineStep = 'img/immaginiSteps/'. strtolower($outputString) .'Step'. $idStep .'.jpeg';
                                    file_put_contents($pathImmagineStep, base64_decode($immagineValue));
                                    ?>
                                    <img src=<?php echo $pathImmagineStep ?> alt="foto step" height="250px" width="250px">
                                    
                                    <br /><br />
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span style="width: 4rem; height: 20rem" class="input-group-text" id="inputGroup-sizing-default">Testo</span>
                                        </div>
                                        <textarea type="text" class="form-control" name="testoValue" disabled><?php echo $testoValue ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <br />
                        </div>
                    <?php
                        $k++;
                    }
                }
                echo '</div>';
            ?>
        </body>
    <?php } ?>

</html>