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
            <title>LocalCommunity - Indice discussioni autorizzate</title>
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
                <div class="row" style="width: 110%;">
                    <div class="col-sm-12">
                        <a class="navbar-brand" href="index.php">LocalCommunity - Home</a>
                    </div>
                </div>
            </nav>

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
                <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 50rem; position: relative; top: 1rem;" >
                    <div class="card-body">
                        <h5 class="card-header" style="background-color: white; color: black">
                            Discussioni
                        </h5>

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

                        <div class="input-group mb-3">
                            <?php echo '<output type="text" class="form-control" name="titoloDiscussioneValue" readonly><a href="manage_discussions.php?id='. $idProgetto .'"> '. $titoloDiscussioneValue .'</a></output>'; ?>  
                        </div>
                        <?php
                            }
                            /* In tal modo controllo se vi è l'autorizzazione per ogni discussione in elenco */
                            $autorizzato=NULL;
                            $responsabile=NULL;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <br />

        </body>
    
    <?php
        }
    ?>

</html>