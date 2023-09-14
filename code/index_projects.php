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
 
        <?php if(isset($_SESSION) && $_SESSION['stato'] == 'sospeso' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
            <title>access denied</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php }else if ( (!isset($_SESSION['username'])) || (isset($_SESSION) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')) ){ ?>
            <title>LocalCommunity - Indice progetti</title>
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
                    <div class="col-sm-6">
                        <a class="navbar-brand" href="index.php">LocalCommunity - Home</a>
                    </div>
                    <div class="col-sm-6">
                        <form method="post" action="index_projects.php">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="categoriaChosen" id="inlineRadio1" value="rinnovabile">
                                <label class="form-check-label" for="inlineRadio1">Rinnovabile</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="categoriaChosen" id="inlineRadio2" value="smaltimento">
                                <label class="form-check-label" for="inlineRadio2">Smaltimento</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="categoriaChosen" id="inlineRadio3" value="salvaguardia">
                                <label class="form-check-label" for="inlineRadio3">Salvaguardia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="categoriaChosen" id="inlineRadio4" value="spaziale">
                                <label class="form-check-label" for="inlineRadio4">Spaziale</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="categoriaChosen" id="inlineRadio5" value="gadget">
                                <label class="form-check-label" for="inlineRadio5">Gadget</label>
                            </div>
                            <input type="submit" style="float: right;" class="btn btn-dark" name="filter" value="Filtra">
                        </form>
                    </div>
                </div>
            </nav>

            <?php
                $filtro = 'off';
                if (isset($_POST['categoriaChosen'])){
                    $filtro = 'on';
                    $categoriaChosen = $_POST['categoriaChosen'];
                }

                $xmlString = "";
                foreach ( file("xml/progetti.xml") as $node ) {
                $xmlString .= trim($node);
                }
                $progettiXml = new DOMDocument();
                if (!$progettiXml->loadXML($xmlString)) {
                die ("Parsing error\n");
                }
                $progetti = $progettiXml->documentElement->childNodes;
                
                echo '<div class="row" style="width: 100%;">';

                /* Per peso rivoluzionario */
                for ($i=$progetti->length-1; $i>=0; $i--) {
                    $progetto = $progetti->item($i);
                    $idProgetto = $progetto->getAttribute('id');
                    
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

                    if ($pesoValue == 'rivoluzionario') {
                        if ($statoValue == 'attivo' && $livelloAutorizzazioneRichiestoValue == 'novizio') {
                            if ($filtro == 'off' || ($filtro == 'on' && $categoriaValue == $categoriaChosen)) {
                                ?>
                                <div class="col-sm-4">
                                    <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 25rem; position: relative; left: 6%; top: 2rem;" >
                                        <div class="card-body">
                                            <h5 class="card-header" style="background-color: white; color: black">
                                                <?php echo '<a href="projects.php?id='. $idProgetto .'&progetto='. $titoloValue .'" title="Questo è il titolo del progetto, fai click per visionarlo"> '. $titoloValue .'</a>' ?>
                                            </h5>

                                            <?php 
                                            $searchString = " ";
                                            $replaceString = "";
                                            $originalString = $titoloValue; 
                                            
                                            $outputString = str_replace($searchString, $replaceString, $originalString, $count); 
                                            ?> 
                                            <?php
                                            $pathFotoRisultato = 'img/fotoRisultato/'. strtolower($outputString) .'Risultato.jpeg';
                                            file_put_contents($pathFotoRisultato, base64_decode($fotoRisultatoValue));
                                            ?>
                                            <img src=<?php echo $pathFotoRisultato ?> alt="foto del risultato" height="250px" width="250px">
                                            
                                            <br /><br />
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione</span>
                                                </div>
                                                <textarea type="text" class="form-control" name="descrizioneValue" disabled><?php echo $descrizioneValue ?></textarea>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                                </div>
                                                <?php
                                                    echo '<output type="text" class="form-control" name="titoloDiscussioneValue" readonly><a href="discussion.php?id='. $idProgetto .'"> '. $titoloDiscussioneValue .'</a></output>';
                                                ?>  
                                            </div>
                                            <div class="row" style="width: 108.3%;">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Difficoltà</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="livelloDifficoltaValue" value="<?php echo $livelloDifficoltaValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 4.5rem;" class="input-group-text" id="inputGroup-sizing-default">Tempo</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="livelloTempoRichiestoValue" value="<?php echo $livelloTempoRichiestoValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="width: 108.3%;">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Voto medio</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="giudizioProjMedioValue" value="<?php echo $giudizioProjMedioValue . " &#47" . " 10" ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
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
                        }
                    }
                }

                /* Per peso popolare */
                for ($i=$progetti->length-1; $i>=0; $i--) {
                    $progetto = $progetti->item($i);
                    $idProgetto = $progetto->getAttribute('id');
                    
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

                    if ($pesoValue == 'popolare') {
                        if ($statoValue == 'attivo' && $livelloAutorizzazioneRichiestoValue == 'novizio') {
                            if ($filtro == 'off' || ($filtro == 'on' && $categoriaValue == $categoriaChosen)) {
                                ?>
                                <div class="col-sm-4">
                                    <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 25rem; position: relative; left: 6%; top: 2rem;" >
                                        <div class="card-body">
                                            <h5 class="card-header" style="background-color: white; color: black">
                                                <?php echo '<a href="projects.php?id='. $idProgetto .'&progetto='. $titoloValue .'" title="Questo è il titolo del progetto, fai click per visionarlo"> '. $titoloValue .'</a>' ?>
                                            </h5>

                                            <?php 
                                            $searchString = " ";
                                            $replaceString = "";
                                            $originalString = $titoloValue; 
                                            
                                            $outputString = str_replace($searchString, $replaceString, $originalString, $count); 
                                            ?> 
                                            <?php
                                            $pathFotoRisultato = 'img/fotoRisultato/'. strtolower($outputString) .'Risultato.jpeg';
                                            file_put_contents($pathFotoRisultato, base64_decode($fotoRisultatoValue));
                                            ?>
                                            <img src=<?php echo $pathFotoRisultato ?> alt="foto del risultato" height="250px" width="250px">
                                            
                                            <br /><br />
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione</span>
                                                </div>
                                                <textarea type="text" class="form-control" name="descrizioneValue" disabled><?php echo $descrizioneValue ?></textarea>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                                </div>
                                                <?php
                                                    echo '<output type="text" class="form-control" name="titoloDiscussioneValue" readonly><a href="discussion.php?id='. $idProgetto .'"> '. $titoloDiscussioneValue .'</a></output>';
                                                ?>  
                                            </div>
                                            <div class="row" style="width: 108.3%;">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Difficoltà</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="livelloDifficoltaValue" value="<?php echo $livelloDifficoltaValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 4.5rem;" class="input-group-text" id="inputGroup-sizing-default">Tempo</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="livelloTempoRichiestoValue" value="<?php echo $livelloTempoRichiestoValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="width: 108.3%;">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Voto medio</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="giudizioProjMedioValue" value="<?php echo $giudizioProjMedioValue . " &#47" . " 10" ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
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
                        }
                    }
                }

                /* Per peso comune */
                for ($i=$progetti->length-1; $i>=0; $i--) {
                    $progetto = $progetti->item($i);
                    $idProgetto = $progetto->getAttribute('id');
                    
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

                    if ($pesoValue == 'comune') {
                        if ($statoValue == 'attivo' && $livelloAutorizzazioneRichiestoValue == 'novizio') {
                            if ($filtro == 'off' || ($filtro == 'on' && $categoriaValue == $categoriaChosen)) {
                                ?>
                                <div class="col-sm-4">
                                    <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 25rem; position: relative; left: 6%; top: 2rem;" >
                                        <div class="card-body">
                                            <h5 class="card-header" style="background-color: white; color: black">
                                                <?php echo '<a href="projects.php?id='. $idProgetto .'&progetto='. $titoloValue .'" title="Questo è il titolo del progetto, fai click per visionarlo"> '. $titoloValue .'</a>' ?>
                                            </h5>

                                            <?php 
                                            $searchString = " ";
                                            $replaceString = "";
                                            $originalString = $titoloValue; 
                                            
                                            $outputString = str_replace($searchString, $replaceString, $originalString, $count); 
                                            ?> 
                                            <?php
                                            $pathFotoRisultato = 'img/fotoRisultato/'. strtolower($outputString) .'Risultato.jpeg';
                                            file_put_contents($pathFotoRisultato, base64_decode($fotoRisultatoValue));
                                            ?>
                                            <img src=<?php echo $pathFotoRisultato ?> alt="foto del risultato" height="250px" width="250px">
                                            
                                            <br /><br />
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione</span>
                                                </div>
                                                <textarea type="text" class="form-control" name="descrizioneValue" disabled><?php echo $descrizioneValue ?></textarea>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                                </div>
                                                <?php
                                                    echo '<output type="text" class="form-control" name="titoloDiscussioneValue" readonly><a href="discussion.php?id='. $idProgetto .'"> '. $titoloDiscussioneValue .'</a></output>';
                                                ?>  
                                            </div>
                                            <div class="row" style="width: 108.3%;">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Difficoltà</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="livelloDifficoltaValue" value="<?php echo $livelloDifficoltaValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 4.5rem;" class="input-group-text" id="inputGroup-sizing-default">Tempo</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="livelloTempoRichiestoValue" value="<?php echo $livelloTempoRichiestoValue . " &#47" . " 5" ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="width: 108.3%;">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Voto medio</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="giudizioProjMedioValue" value="<?php echo $giudizioProjMedioValue . " &#47" . " 10" ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
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
                        }
                    }
                }

                echo '</div>';
            ?>

        </body>
    <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
        <body style="background-repeat: no-repeat; background-size: cover;" background="img/project.jpg">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="row" style="width: 100%;">
                    <div class="col-sm-6">
                        <a class="navbar-brand" href="index.php">LocalCommunity - Home</a>
                    </div>
                    <div class="col-sm-6">
                        <form method="post" action="index_projects.php">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="categoriaChosen" id="inlineRadio1" value="rinnovabile">
                                <label class="form-check-label" for="inlineRadio1">Rinnovabile</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="categoriaChosen" id="inlineRadio2" value="smaltimento">
                                <label class="form-check-label" for="inlineRadio2">Smaltimento</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="categoriaChosen" id="inlineRadio3" value="salvaguardia">
                                <label class="form-check-label" for="inlineRadio3">Salvaguardia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="categoriaChosen" id="inlineRadio4" value="spaziale">
                                <label class="form-check-label" for="inlineRadio4">Spaziale</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="categoriaChosen" id="inlineRadio5" value="gadget">
                                <label class="form-check-label" for="inlineRadio5">Gadget</label>
                            </div>
                            <input type="submit" style="float: right;" class="btn btn-dark" name="filter" value="Filtra">
                        </form>
                    </div>
                </div>
            </nav>

            <?php
                $filtro = 'off';
                if (isset($_POST['categoriaChosen'])){
                    $filtro = 'on';
                    $categoriaChosen = $_POST['categoriaChosen'];
                }

                $xmlString = "";
                foreach ( file("xml/progetti.xml") as $node ) {
                $xmlString .= trim($node);
                }
                $progettiXml = new DOMDocument();
                if (!$progettiXml->loadXML($xmlString)) {
                die ("Parsing error\n");
                }
                $progetti = $progettiXml->documentElement->childNodes;

                echo '<div class="row" style="width: 100%;">';
                
                /* Per peso rivoluzionario (caso utente loggato) */
                for ($i=$progetti->length-1; $i>=0; $i--) {
                    $progetto = $progetti->item($i);
                    $idProgetto = $progetto->getAttribute('id');
                    
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

                        if ($pesoValue == 'rivoluzionario') {
                            if ($statoValue == 'attivo') {
                                if ($filtro == 'off' || ($filtro == 'on' && $categoriaValue == $categoriaChosen)) {
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

                                    <div class="col-sm-4">
                                        <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 25rem; position: relative; left: 6%; top: 2rem;" >
                                            <div class="card-body">
                                                <h5 class="card-header" style="background-color: white; color: black">
                                                    <?php echo '<a href="projects.php?id='. $idProgetto .'&progetto='. $titoloValue .'" title="Questo è il titolo del progetto, fai click per visionarlo"> '. $titoloValue .'</a>' ?>
                                                </h5>

                                                <?php 
                                                $searchString = " ";
                                                $replaceString = "";
                                                $originalString = $titoloValue; 
                                                
                                                $outputString = str_replace($searchString, $replaceString, $originalString, $count); 
                                                ?> 
                                                <?php
                                                $pathFotoRisultato = 'img/fotoRisultato/'. strtolower($outputString) .'Risultato.jpeg';
                                                file_put_contents($pathFotoRisultato, base64_decode($fotoRisultatoValue));
                                                ?>
                                                <img src=<?php echo $pathFotoRisultato ?> alt="foto del risultato" height="250px" width="250px">
                                                
                                                <br /><br />
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione</span>
                                                    </div>
                                                    <textarea type="text" class="form-control" name="descrizioneValue" disabled><?php echo $descrizioneValue ?></textarea>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                                    </div>
                                                    <?php
                                                        echo '<output type="text" class="form-control" name="titoloDiscussioneValue" readonly><a href="discussion.php?id='. $idProgetto .'"> '. $titoloDiscussioneValue .'</a></output>';
                                                    ?>  
                                                </div>
                                                <div class="row" style="width: 108.3%;">
                                                    <div class="col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Difficoltà</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="livelloDifficoltaValue" value="<?php echo $livelloDifficoltaValue . " &#47" . " 5" ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span style="width: 4.5rem;" class="input-group-text" id="inputGroup-sizing-default">Tempo</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="livelloTempoRichiestoValue" value="<?php echo $livelloTempoRichiestoValue . " &#47" . " 5" ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="width: 108.3%;">
                                                    <div class="col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Voto medio</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="giudizioProjMedioValue" value="<?php echo $giudizioProjMedioValue . " &#47" . " 10" ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
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
                            }
                        }
                    }
                }

                /* Per peso popolare (caso utente loggato) */
                for ($i=$progetti->length-1; $i>=0; $i--) {
                    $progetto = $progetti->item($i);
                    $idProgetto = $progetto->getAttribute('id');
                    
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

                        if ($pesoValue == 'popolare') {
                            if ($statoValue == 'attivo') {
                                if ($filtro == 'off' || ($filtro == 'on' && $categoriaValue == $categoriaChosen)) {
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

                                    <div class="col-sm-4">
                                        <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 25rem; position: relative; left: 6%; top: 2rem;" >
                                            <div class="card-body">
                                                <h5 class="card-header" style="background-color: white; color: black">
                                                    <?php echo '<a href="projects.php?id='. $idProgetto .'&progetto='. $titoloValue .'" title="Questo è il titolo del progetto, fai click per visionarlo"> '. $titoloValue .'</a>' ?>
                                                </h5>

                                                <?php 
                                                $searchString = " ";
                                                $replaceString = "";
                                                $originalString = $titoloValue; 
                                                
                                                $outputString = str_replace($searchString, $replaceString, $originalString, $count); 
                                                ?> 
                                                <?php
                                                $pathFotoRisultato = 'img/fotoRisultato/'. strtolower($outputString) .'Risultato.jpeg';
                                                file_put_contents($pathFotoRisultato, base64_decode($fotoRisultatoValue));
                                                ?>
                                                <img src=<?php echo $pathFotoRisultato ?> alt="foto del risultato" height="250px" width="250px">
                                                
                                                <br /><br />
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione</span>
                                                    </div>
                                                    <textarea type="text" class="form-control" name="descrizioneValue" disabled><?php echo $descrizioneValue ?></textarea>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                                    </div>
                                                    <?php
                                                        echo '<output type="text" class="form-control" name="titoloDiscussioneValue" readonly><a href="discussion.php?id='. $idProgetto .'"> '. $titoloDiscussioneValue .'</a></output>';
                                                    ?>  
                                                </div>
                                                <div class="row" style="width: 108.3%;">
                                                    <div class="col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Difficoltà</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="livelloDifficoltaValue" value="<?php echo $livelloDifficoltaValue . " &#47" . " 5" ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span style="width: 4.5rem;" class="input-group-text" id="inputGroup-sizing-default">Tempo</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="livelloTempoRichiestoValue" value="<?php echo $livelloTempoRichiestoValue . " &#47" . " 5" ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="width: 108.3%;">
                                                    <div class="col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Voto medio</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="giudizioProjMedioValue" value="<?php echo $giudizioProjMedioValue . " &#47" . " 10" ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
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
                            }
                        }
                    }
                }

                /* Per peso comune (caso utente loggato) */
                for ($i=$progetti->length-1; $i>=0; $i--) {
                    $progetto = $progetti->item($i);
                    $idProgetto = $progetto->getAttribute('id');
                    
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

                        if ($pesoValue == 'comune') {
                            if ($statoValue == 'attivo') {
                                if ($filtro == 'off' || ($filtro == 'on' && $categoriaValue == $categoriaChosen)) {
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

                                    <div class="col-sm-4">
                                        <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 25rem; position: relative; left: 6%; top: 2rem;" >
                                            <div class="card-body">
                                                <h5 class="card-header" style="background-color: white; color: black">
                                                    <?php echo '<a href="projects.php?id='. $idProgetto .'&progetto='. $titoloValue .'" title="Questo è il titolo del progetto, fai click per visionarlo"> '. $titoloValue .'</a>' ?>
                                                </h5>

                                                <?php 
                                                $searchString = " ";
                                                $replaceString = "";
                                                $originalString = $titoloValue; 
                                                
                                                $outputString = str_replace($searchString, $replaceString, $originalString, $count); 
                                                ?> 
                                                <?php
                                                $pathFotoRisultato = 'img/fotoRisultato/'. strtolower($outputString) .'Risultato.jpeg';
                                                file_put_contents($pathFotoRisultato, base64_decode($fotoRisultatoValue));
                                                ?>
                                                <img src=<?php echo $pathFotoRisultato ?> alt="foto del risultato" height="250px" width="250px">
                                                
                                                <br /><br />
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione</span>
                                                    </div>
                                                    <textarea type="text" class="form-control" name="descrizioneValue" disabled><?php echo $descrizioneValue ?></textarea>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span style="width: 6.5rem;" class="input-group-text" id="inputGroup-sizing-default">Discussione</span>
                                                    </div>
                                                    <?php
                                                        echo '<output type="text" class="form-control" name="titoloDiscussioneValue" readonly><a href="discussion.php?id='. $idProgetto .'"> '. $titoloDiscussioneValue .'</a></output>';
                                                    ?>  
                                                </div>
                                                <div class="row" style="width: 108.3%;">
                                                    <div class="col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Difficoltà</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="livelloDifficoltaValue" value="<?php echo $livelloDifficoltaValue . " &#47" . " 5" ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span style="width: 4.5rem;" class="input-group-text" id="inputGroup-sizing-default">Tempo</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="livelloTempoRichiestoValue" value="<?php echo $livelloTempoRichiestoValue . " &#47" . " 5" ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="width: 108.3%;">
                                                    <div class="col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Voto medio</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="giudizioProjMedioValue" value="<?php echo $giudizioProjMedioValue . " &#47" . " 10" ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
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
                            }
                        }
                    }
                }

                echo '</div>';
            ?>

        </body>
    <?php } ?>

</html>