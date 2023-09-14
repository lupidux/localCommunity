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
 
        <?php if(!isset($_SESSION['id']) || (isset($_SESSION['id']) && $_SESSION['ruolo'] == 'admin')){ ?>
            <title>access denied</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php }else if ( isset($_SESSION['id']) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore') ){ ?> 
            <title>LocalCommunity - Report</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php } ?>
        
    </head>

    <?php if(!isset($_SESSION['id']) || (isset($_SESSION['id']) && $_SESSION['ruolo'] == 'admin')){ ?>
        <body>
            <h1>access denied</h1>
        </body>
    <?php } else if (isset($_SESSION['id']) && $_SESSION['stato'] == 'attivo' && $_SESSION['ruolo'] == 'utente interno') { ?> 
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
                    if(isset($_POST['idOggettoDaRiportare']) && !empty($_POST['idOggettoDaRiportare'])){
                        if(isset($_POST['tipologiaValue']) && !empty($_POST['tipologiaValue']) && $_POST['tipologiaValue']!="Scegli..."){
                            if($_POST['tipologiaValue']=='progetto'){
                                $xmlString = "";
                                foreach ( file("xml/progetti.xml") as $node ) {
                                $xmlString .= trim($node);
                                }
                                $progettiXml = new DOMDocument();
                                if (!$progettiXml->loadXML($xmlString)) {
                                die ("Parsing error\n");
                                }
                                $progetti = $progettiXml->documentElement->childNodes;
                                
                                $xpath=new DOMXpath($progettiXml);
                                $idProgettoPreinserito=$xpath->query("//progetto[@id='".(int)$_POST['idOggettoDaRiportare']."']")->item(0);
                                $idOggettoError="no";
                                if (!$idProgettoPreinserito){
                                    if(isset($msg['error'])){
                                        $msg['error'].="Il progetto che si vuole riportare è inesistente<br />";
                                    }
                                    else{
                                        $msg['error']="Il progetto che si vuole riportare è inesistente<br />";
                                    }
                                    $idOggettoError="yes";
                                }
                            }else if($_POST['tipologiaValue']=='commento'){
                                $xmlString = "";
                                foreach ( file("xml/commenti.xml") as $node ) {
                                $xmlString .= trim($node);
                                }
                                $commentiXml = new DOMDocument();
                                if (!$commentiXml->loadXML($xmlString)) {
                                die ("Parsing error\n");
                                }
                                $commenti = $commentiXml->documentElement->childNodes;
                                
                                $xpath=new DOMXpath($commentiXml);
                                $idCommentoPreinserito=$xpath->query("//commento[@id='".(int)$_POST['idOggettoDaRiportare']."']")->item(0);
                                $idOggettoError="no";
                                if (!$idCommentoPreinserito){
                                    if(isset($msg['error'])){
                                        $msg['error'].="Il commento che si vuole riportare è inesistente<br />";
                                    }
                                    else{
                                        $msg['error']="Il commento che si vuole riportare è inesistente<br />";
                                    }
                                    $idOggettoError="yes";
                                }
                            }
                        }else{
                            if(isset($msg['error'])){ 
                            $msg['error'].="La tipologia del report è obbligatoria<br />";
                            }
                            else{
                            $msg['error']="La tipologia del report è obbligatoria<br />";
                            }
                        }
                    }else{
                        if(isset($msg['error'])){
                        $msg['error'].="L'id dell'oggetto da riportare è obbligatorio<br />";
                        }
                        else{
                        $msg['error']="L'id dell'oggetto da riportare è obbligatorio<br />";
                        }
                    }
                
                    if(isset($_POST['spiegazioneReportValue']) && !empty($_POST['spiegazioneReportValue'])){
                        #tutto ok
                    }else{
                        if(isset($msg['error'])){
                        $msg['error'].="La spiegazione è obbligatoria<br />";
                        }
                        else{
                        $msg['error']="La spiegazione è obbligatoria<br />";
                        }
                    }
                
                    if(isset($_POST['naturaValue']) && !empty($_POST['naturaValue'])){
                        #tutto ok
                    }else{
                        if(isset($msg['error'])){
                        $msg['error'].="La natura del report è obbligatoria<br />";
                        }
                        else{
                        $msg['error']="La natura del report è obbligatoria<br />";
                        }
                    }
                    
                    if(!isset($msg['error'])){ 
                        $xmlString = "";
                        foreach ( file("xml/reports.xml") as $node ) {
                            $xmlString .= trim($node);
                        }
                        $reportsXml = new DOMDocument();
                        if (!$reportsXml->loadXML($xmlString)) {
                            die ("Parsing error\n");
                        }
                        $docElem = $reportsXml->documentElement;
                        $reports = $docElem->childNodes;

                        for ($l=0; $l<$reports->length; $l++) {
                            $report = $reports->item($l);
                            $id_rep = $report->getAttribute('id');
                        }
                        $last_report=$report;
                        $lastReportId = (int)$id_rep;
                        $newIdReportValue=$lastReportId+1;
                        $new_report = $reportsXml->createElement("report");
                        $new_report->setAttribute("id", $newIdReportValue);
                        $docElem->appendChild($new_report);
                        
                        $newidUtenteValue=(int)$_SESSION['id'];
                        $new_idUten = $reportsXml->createElement("idUtente");
                        $new_idUten_text = $reportsXml->createTextNode($newidUtenteValue);
                        $new_idUten->appendChild($new_idUten_text);
                        $new_report->appendChild($new_idUten);

                        $numModeratori=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT max(id) as numModeratori FROM utente WHERE ruolo='moderatore'"));
                        $numModeratori=$numModeratori['numModeratori'];
                        for ($e=1; $e<=$numModeratori ; $e++){
                            $idModeratori[$e]=mysqli_fetch_array(mysqli_query($mysqliConnection,"SELECT id FROM utente WHERE ruolo='moderatore' AND id=". $e));
                        }
                        shuffle($idModeratori);
                        $newidModeratoreValue=$idModeratori[0]; 
                        $new_idModer = $reportsXml->createElement("idModeratore");
                        $new_idModer_text = $reportsXml->createTextNode($newidModeratoreValue);
                        $new_idModer->appendChild($new_idModer_text);
                        $new_report->appendChild($new_idModer);
                        
                        $idOggettoDaRiportareValue=(int)$_POST['idOggettoDaRiportare'];
                        $new_idOggettoDaRiport = $reportsXml->createElement("idOggetto");
                        $new_idOggettoDaRiport_text = $reportsXml->createTextNode($idOggettoDaRiportareValue);
                        $new_idOggettoDaRiport->appendChild($new_idOggettoDaRiport_text);
                        $new_report->appendChild($new_idOggettoDaRiport);
                        
                        $newTipologiaValue=$_POST['tipologiaValue'];
                        $new_tipologia = $reportsXml->createElement("tipologia");
                        $new_tipologia_text = $reportsXml->createTextNode($newTipologiaValue);
                        $new_tipologia->appendChild($new_tipologia_text);
                        $new_report->appendChild($new_tipologia);

                        $newSpiegazioneReportValue=$_POST['spiegazioneReportValue'];
                        $new_Spiegaz = $reportsXml->createElement("spiegazione");
                        $new_Spiegaz_text = $reportsXml->createTextNode($newSpiegazioneReportValue);
                        $new_Spiegaz->appendChild($new_Spiegaz_text);
                        $new_report->appendChild($new_Spiegaz);
                
                        $newNaturaValue=$_POST['naturaValue'];
                        $new_natura = $reportsXml->createElement("natura");
                        $new_natura_text = $reportsXml->createTextNode($newNaturaValue);
                        $new_natura->appendChild($new_natura_text);
                        $new_report->appendChild($new_natura);
                
                        if (!($reportsXml->save("xml/reports.xml"))) {
                            if(isset($msg['error'])){ 
                                $msg['error'].="Salvataggio dati fallito<br />";
                            }
                            else{ 
                                $msg['error']="Salvataggio dati fallito<br />";
                            }
                        } 
                    }
                    if(!isset($msg['error'])){
                        if(isset($msg['success'])){ 
                            $msg['success'].="Salvataggio effettuato con successo<br />";
                        }
                        else{ 
                            $msg['success']="Salvataggio effettuato con successo<br />";
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
                <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 40rem; position: relative; top: 2rem;" >
                    <div class="card-body">
                            <h5 class="card-header" style="background-color: white; color: black">
                                Fai un report
                            </h5>

                            <form method="post" action="report.php">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label style="width: 12rem;" class="input-group-text" for="inputGroupSelect01">Tipologia report</label>
                                    </div>
                                        <select class="custom-select" id="inputGroupSelect01" name="tipologiaValue">
                                        <option selected>Scegli...</option>
                                        <option value="progetto">Progetto</option>
                                        <option value="commento">Commento</option>
                                    </select>
                                </div>
                                <?php if($idOggettoError=="yes"){ ?>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span style="width: 12rem;" class="input-group-text" id="inputGroup-sizing-default">ID Oggetto da riportare</span> 
                                        </div>
                                        <input type="text" class="form-control" style="opacity:80%; border:solid 1px red; color:red;" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="idOggettoDaRiportare" value="<?php echo $_POST['idOggettoDaRiportare'] ?>">
                                    </div>
                                <?php }else{ ?>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span style="width: 12rem;" class="input-group-text" id="inputGroup-sizing-default">ID Oggetto da riportare</span> 
                                        </div>
                                        <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="idOggettoDaRiportare" value="<?php echo $_POST['idOggettoDaRiportare'] ?>">
                                    </div>
                                <?php } ?>
                                <div class="input-group mb-3" style="height: 11rem;">
                                    <div class="input-group-prepend">
                                        <span style="width: 12rem;" aria-label="Default" aria-describedby="inputGroup-sizing-default" class="input-group-text" id="inputGroup-sizing-default">Spiegazione</span>
                                    </div>
                                    <textarea type="text" class="form-control" name="spiegazioneReportValue"><?php echo $_POST['spiegazioneReportValue'] ?></textarea>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label style="width: 12rem;" class="input-group-text" for="inputGroupSelect01">Natura</label>
                                    </div>
                                    <input type="text" list="natura" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="naturaValue" value="<?php echo $_POST['naturaValue'] ?>"></input>
                                    <datalist id="natura" >
                                        <option value="offensiva">Offensiva</option>
                                        <option value="plagiaria">Plagiaria</option>
                                    </datalist>
                                </div>
                                <input type="submit" style="float: center;" class="btn btn-dark" name="confirm" value="Conferma">
                            </form>
                        </div>
                    </div>
                <br />
            </div>
        </body>
    <?php } else if (isset($_SESSION['id']) && $_SESSION['stato'] == 'attivo' && $_SESSION['ruolo'] == 'moderatore') { ?>
        <body style="background-repeat: no-repeat; background-size: cover;" background="img/project.jpg">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="row" style="width: 100%;">
                    <div class="col-sm-12">
                        <a class="navbar-brand" href="index.php">LocalCommunity - Home</a>
                    </div>
                </div>
            </nav>

            <div class="row" style="width: 100%;">
                <div class="col-sm-6">
                    <?php
                        $xmlString = "";
                        foreach ( file("xml/reports.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $reportsXml = new DOMDocument();
                        if (!$reportsXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }
                        $reports = $reportsXml->documentElement->childNodes;
                        
                        for ($i=$reports->length-1; $i>=0; $i--) {
                            $report = $reports->item($i);
                            $idReportValue = $report->getAttribute('id');
                            
                            $idUtente = $report->firstChild;
                            $idUtenteValue = $idUtente->textContent;
                            $idModeratore = $idUtente->nextSibling;
                            $idModeratoreValue = $idModeratore->textContent;
                            $idOggetto = $idModeratore->nextSibling;
                            $idOggettoValue = $idOggetto->textContent;
                            $tipologia = $idOggetto->nextSibling;
                            $tipologiaValue = $tipologia->textContent;
                            $spiegazione = $tipologia->nextSibling;
                            $spiegazioneValue = $spiegazione->textContent;
                            $natura = $spiegazione->nextSibling;
                            $naturaValue = $natura->textContent;

                            $reporter=mysqli_fetch_array(mysqli_query($mysqliConnection,"SELECT nome, cognome, username FROM utente WHERE id='".$idUtenteValue."'")); 
                            $reporterValue=$reporter['nome']." ".$reporter['cognome'];
                            $usernameReporterValue=$reporter['username'];

                            $xmlString = "";
                            foreach ( file("xml/progetti.xml") as $node ) {
                            $xmlString .= trim($node);
                            }
                            $progettiXml = new DOMDocument();
                            if (!$progettiXml->loadXML($xmlString)) {
                            die ("Parsing error\n");
                            }
                            $progetti = $progettiXml->documentElement->childNodes;

                            for ($k=0; $k<$progetti->length; $k++) { 
                                $progetto = $progetti->item($k);
                                $idProgetto = $progetto->getAttribute('id');
                                if ($idOggettoValue==$idProgetto){
                                    $idResponsabile = $progetto->firstChild;
                                    $idResponsabileValue = $idResponsabile->textContent;
                                    $titolo = $idResponsabile->nextSibling->nextSibling;
                                    $titoloValue = $titolo->textContent;
                                    $nomeResponsabile=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT nome, cognome FROM utente WHERE id='".$idResponsabileValue."'"));

                                    $titoloProgettoRiportatoValue=$titoloValue;
                                    $responsabileProgettoRiportatoValue=$nomeResponsabile['nome']." ".$nomeResponsabile['cognome'];
                                    break;
                                }
                            }
                    ?>  
                    <?php if ($tipologiaValue == 'progetto') { ?> 
                        <div align="left">
                            <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 40rem; position: relative; left: 2%; top: 1rem;" >
                                <div class="card-body">
                                    <h5 class="card-header" style="background-color: white; color: black">
                                        Report progetto
                                    </h5>

                                    <div class="row" style="width: 105%;">
                                        <div class="col-sm-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">ID Report</span>
                                                </div>
                                                <input type="text" class="form-control" name="idReportValue" value="<?php echo $idReportValue ?>" disabled>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">Reporter</span>
                                                </div>
                                                <input type="text" class="form-control" name="reporterValue" value="<?php echo $reporterValue ?>" disabled>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">U/N Reporter</span>
                                                </div>
                                                <input type="text" class="form-control" name="usernameReporterValue" value="<?php echo $usernameReporterValue ?>" disabled>
                                            </div>
                                            <hr />
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">ID Progetto</span>
                                                </div>
                                                <input type="text" class="form-control" name="idOggettoValue" value="<?php echo $idOggettoValue ?>" disabled>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">Progetto</span>
                                                </div>
                                                <?php
                                                    echo '<output type="text" class="form-control" name="titoloValue" readonly><a href="steps.php?id='. $idOggettoValue .'&progetto='. $titoloProgettoRiportatoValue .'"> '. $titoloProgettoRiportatoValue .'</a></output>';
                                                ?> 
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">Responsabile</span>
                                                </div>
                                                <input type="text" class="form-control" name="responsabileProgettoRiportatoValue" value="<?php echo $responsabileProgettoRiportatoValue ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group mb-3" style="height: 16.9rem;">
                                                <div class="input-group-prepend">
                                                    <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">Spiegazione</span>
                                                </div>
                                                <textarea type="text" class="form-control" name="spiegazioneValue" disabled><?php echo $spiegazioneValue ?></textarea>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">Natura</span>
                                                </div>
                                                <input type="text" class="form-control" name="naturaValue" value="<?php echo $naturaValue ?>" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                        </div>
                    <?php } 
                        } 
                    ?>
                </div>
                <div class="col-sm-6"> 
                    <?php
                        $xmlString = "";
                        foreach ( file("xml/reports.xml") as $node ) {
                        $xmlString .= trim($node);
                        }
                        $reportsXml = new DOMDocument();
                        if (!$reportsXml->loadXML($xmlString)) {
                        die ("Parsing error\n");
                        }
                        $reports = $reportsXml->documentElement->childNodes;
                        
                        for ($i=$reports->length-1; $i>=0; $i--) {
                            $report = $reports->item($i);
                            $idReportValue = $report->getAttribute('id');
                            
                            $idUtente = $report->firstChild;
                            $idUtenteValue = $idUtente->textContent;
                            $idModeratore = $idUtente->nextSibling;
                            $idModeratoreValue = $idModeratore->textContent;
                            $idOggetto = $idModeratore->nextSibling;
                            $idOggettoValue = $idOggetto->textContent;
                            $tipologia = $idOggetto->nextSibling;
                            $tipologiaValue = $tipologia->textContent;
                            $spiegazione = $tipologia->nextSibling;
                            $spiegazioneValue = $spiegazione->textContent;
                            $natura = $spiegazione->nextSibling;
                            $naturaValue = $natura->textContent;

                            $reporter=mysqli_fetch_array(mysqli_query($mysqliConnection,"SELECT nome, cognome, username FROM utente WHERE id='".$idUtenteValue."'")); 
                            $reporterValue=$reporter['nome']." ".$reporter['cognome'];
                            $usernameReporterValue=$reporter['username'];

                            $xmlString = "";
                            foreach ( file("xml/commenti.xml") as $node ) {
                            $xmlString .= trim($node);
                            }
                            $commentiXml = new DOMDocument();
                            if (!$commentiXml->loadXML($xmlString)) {
                            die ("Parsing error\n");
                            }
                            $commenti = $commentiXml->documentElement->childNodes;

                            for ($k=0; $k<$commenti->length; $k++) { 
                                $commento = $commenti->item($k);
                                $idCommento = $commento->getAttribute('id');
                                if ($idOggettoValue==$idCommento){ 
                                    $idDiscussione = $commento->firstChild->nextSibling;
                                    $idDiscussioneValue = $idDiscussione->textContent; 

                                    $xmlString = "";
                                    foreach ( file("xml/discussioni.xml") as $node ) {
                                    $xmlString .= trim($node);
                                    }
                                    $discussioniXml = new DOMDocument();
                                    if (!$discussioniXml->loadXML($xmlString)) {
                                    die ("Parsing error\n");
                                    }
                                    $discussioni = $discussioniXml->documentElement->childNodes;
                                    for ($j=0; $j<$discussioni->length; $j++) { 
                                        $discussione = $discussioni->item($j);
                                        $idDiscuss = $discussione->getAttribute('id'); 

                                        if($idDiscuss == $idDiscussioneValue){ 
                                            $idProgetto = $discussione->firstChild;
                                            $idProgettoValue = $idProgetto->textContent;
                                            $xmlString = "";
                                            foreach ( file("xml/progetti.xml") as $node ) {
                                            $xmlString .= trim($node);
                                            }
                                            $progettiXml = new DOMDocument();
                                            if (!$progettiXml->loadXML($xmlString)) {
                                            die ("Parsing error\n");
                                            }
                                            $progetti = $progettiXml->documentElement->childNodes;
                                            for ($p=0; $p<$progetti->length; $p++) { 
                                                $progetto = $progetti->item($p);
                                                $idProget = $progetto->getAttribute('id');
                                                if($idProgettoValue == $idProget){
                                                    $idResponsabile = $progetto->firstChild;
                                                    $idResponsabileValue = $idResponsabile->textContent;
                                                    $nomeResponsabile=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT nome, cognome FROM utente WHERE id='".$idResponsabileValue."'"));
                                                    break;
                                                }
                                            }
                                                
                                            $titoloDiscussione=$discussione->firstChild->nextSibling;
                                            $titoloDiscussioneValue=$titoloDiscussione->textContent;
                                            $titoloDiscussioneRiportataValue=$titoloDiscussioneValue;
                                            break;
                                        }
                                    }

                                    $responsabileOggettoRiportatoValue=$nomeResponsabile['nome']." ".$nomeResponsabile['cognome'];
                                    break;
                                }
                            }
                    ?>  
                    <?php if ($tipologiaValue == 'commento') { ?>
                        <div align="right">
                            <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 40rem; position: relative; right: 2%; top: 1rem;" >
                                <div class="card-body">
                                        <h5 class="card-header" style="background-color: white; color: black">
                                            Report commento
                                        </h5>

                                        <div class="row" style="width: 105%;">
                                            <div class="col-sm-6">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">ID Report</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="idReportValue" value="<?php echo $idReportValue ?>" disabled>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">Reporter</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="reporterValue" value="<?php echo $reporterValue ?>" disabled>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">U/N Reporter</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="usernameReporterValue" value="<?php echo $usernameReporterValue ?>" disabled>
                                                </div>
                                                <hr />
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">ID Commento</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="idOggettoValue" value="<?php echo $idOggettoValue ?>" disabled>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                                    </div>
                                                    <?php
                                                        echo '<output type="text" class="form-control" name="titoloValue" readonly><a href="discussion.php?id='. $idDiscussioneValue .'"> '. $titoloDiscussioneRiportataValue .'</a></output>';
                                                    ?> 
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">Responsabile</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="responsabileProgettoRiportatoValue" value="<?php echo $responsabileOggettoRiportatoValue ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group mb-3" style="height: 16.9rem;">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">Spiegazione</span>
                                                    </div>
                                                    <textarea type="text" class="form-control" name="spiegazioneValue" disabled><?php echo $spiegazioneValue ?></textarea>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 7.2rem;" class="input-group-text" id="inputGroup-sizing-default">Natura</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="naturaValue" value="<?php echo $naturaValue ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <br />
                        </div>
                    <?php }
                        } 
                    ?>
                </div>
            </div>
        </body>
    <?php } ?>

</html>