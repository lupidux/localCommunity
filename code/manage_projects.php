<?php
  require_once("connect.php");

  if(isset($_POST['choose'])){
    if(isset($_POST['numeroStep']) && !empty($_POST['numeroStep']) && $_POST['numeroStep']!="Scegli..."){
      setcookie("numeroStep",(int)$_POST['numeroStep'], time()+3600*24);
    }else{
      if(isset($msg['error'])){
        $msg['error'].="Il numero di step &egrave obbligatorio<br />";
      }
      else{
        $msg['error']="Il numero di step &egrave obbligatorio<br />";
      }
    }
  }

  if(isset($_POST['confirm'])){ 
    if(isset($_POST['modifica']) && !empty($_POST['modifica']) && $_POST['modifica']!="Scegli..."){ 
      if($_POST['modifica']=="posta"){
        if(isset($_COOKIE['numeroStep'])){
          for ($k=1 ; $k<=$_COOKIE['numeroStep'] ; $k++) {
            if(isset($_POST['testoStep'.$k.'Value']) && !empty($_POST['testoStep'.$k.'Value'])){
              #tutto ok
            }else{
              if(isset($msg['error'])){
                $msg['error'].="Il testo dello step $k è obbligatorio<br />";
              }
              else{
                $msg['error']="Il testo dello step $k è obbligatorio<br />";
              }
            }

            if(isset($_FILES['immagineStep'.$k.'Value']['name']) && !empty($_FILES['immagineStep'.$k.'Value']['name'])){
              if($_FILES['immagineStep'.$k.'Value']['error'] == 1){
                if(isset($msg['error'])){
                  $msg['error'].="Errore nel caricamento dell'immagine dello step $k<br />";
                }
                else{
                  $msg['error']="Errore nel caricamento dell'immagine dello step $k<br />";
                }
              }else{
                if ($_FILES['immagineStep'.$k.'Value']["size"] > 2097152) {
                  if(isset($msg['error'])){
                    $msg['error'].="L'immagine dello step $k non può superare i 2MB<br />";
                  }
                  else{
                    $msg['error']="L'immagine dello step $k non può superare i 2MB<br />";
                  }
                } 
                if ($_FILES['immagineStep'.$k.'Value']["type"] != "image/jpeg") {
                  if(isset($msg['error'])){
                    $msg['error'].="L'immagine dello step $k deve essere in formato JPEG<br />";
                  }
                  else{
                    $msg['error']="L'immagine dello step $k deve essere in formato JPEG<br />";
                  }
                } 
              }
            }else{
              if(isset($msg['error'])){
                $msg['error'].="L'immagine dello step $k è obbligatoria<br />";
              }
              else{
                $msg['error']="L'immagine dello step $k è obbligatoria<br />";
              }
            }
          }
        } else if (!isset($_SESSION['numeroStep'])){
          if(isset($msg['error'])){
            $msg['error'].="Il numero di step &egrave obbligatorio<br />";
          }
          else{
            $msg['error']="Il numero di step &egrave obbligatorio<br />";
          }
        }

        if(isset($_POST['newTitoloValue']) && !empty($_POST['newTitoloValue'])){
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
          $titoloProgettoPreinserito=$xpath->query("//titolo[text()='".$_POST['newTitoloValue']."']")->item(0);
          if ($titoloProgettoPreinserito){
            if(isset($msg['error'])){
              $msg['error'].="Il titolo è già stato utilizzato<br />";
            }
            else{
              $msg['error']="Il titolo è già stato utilizzato<br />";
            }
          }
        }else{
          if(isset($msg['error'])){
            $msg['error'].="Il titolo è obbligatorio<br />";
          }
          else{
            $msg['error']="Il titolo è obbligatorio<br />";
          }
        }
        
        if(isset($_POST['newDescrizioneValue']) && !empty($_POST['newDescrizioneValue'])){ 
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

          for ($i=0; $i<$progetti->length; $i++) {
            $progetto = $progetti->item($i);
            $descrizione = $progetto->firstChild->nextSibling->nextSibling->nextSibling;
            $descrizioneValue = $descrizione->textContent;
            if ($descrizioneValue == $_POST['newDescrizioneValue']){
              $descrizioneProgettoPreinserita="yes";
              break;
            }
          }
          
          if ($descrizioneProgettoPreinserita){
            if(isset($msg['error'])){
              $msg['error'].="La descrizione è già stata utilizzata<br />";
            }
            else{
              $msg['error']="La descrizione è già stata utilizzata<br />";
            }
          }
        }else{
          if(isset($msg['error'])){
            $msg['error'].="La descrizione è obbligatoria<br />";
          }
          else{
            $msg['error']="La descrizione è obbligatoria<br />";
          }
        }
      
        if(isset($_POST['newCategoriaValue']) && !empty($_POST['newCategoriaValue']) && $_POST['newCategoriaValue']!="Scegli..."){
          #tutto ok
        }else{
          if(isset($msg['error'])){
            $msg['error'].="La categoria è obbligatoria<br />";
          }
          else{
            $msg['error']="La categoria è obbligatoria<br />";
          }
        }

        if(isset($_FILES['newFotoComponentiValue']['name']) && !empty($_FILES['newFotoComponentiValue']['name'])){
          if($_FILES['newFotoComponentiValue']['error'] == 1){
            if(isset($msg['error'])){
              $msg['error'].="Errore nel caricamento della foto delle componenti<br />";
            }
            else{
              $msg['error']="Errore nel caricamento della foto delle componenti<br />";
            }
          }else{
            if ($_FILES['newFotoComponentiValue']["size"] > 2097152) {
              if(isset($msg['error'])){
                $msg['error'].="La foto delle componenti non può superare i 2MB<br />";
              }
              else{
                $msg['error']="La foto delle componenti non può superare i 2MB<br />";
              }
            } 
            if ($_FILES['newFotoComponentiValue']["type"] != "image/jpeg") {
              if(isset($msg['error'])){
                $msg['error'].="La foto delle componenti deve essere in formato JPEG<br />";
              }
              else{
                $msg['error']="La foto delle componenti deve essere in formato JPEG<br />";
              }
            } 
          }
        }else{
          if(isset($msg['error'])){
            $msg['error'].="La foto delle componenti è obbligatoria<br />";
          }
          else{
            $msg['error']="La foto delle componenti è obbligatoria<br />";
          }
        }

        if(isset($_FILES['newFotoRisultatoValue']['name']) && !empty($_FILES['newFotoRisultatoValue']['name'])){
          if($_FILES['newFotoRisultatoValue']['error'] == 1){
            if(isset($msg['error'])){
              $msg['error'].="Errore nel caricamento della foto del risultato<br />";
            }
            else{
              $msg['error']="Errore nel caricamento della foto del risultato<br />";
            }
          }else{
            if ($_FILES['newFotoRisultatoValue']["size"] > 2097152) {
              if(isset($msg['error'])){
                $msg['error'].="La foto del risultato non può superare i 2MB<br />";
              }
              else{
                $msg['error']="La foto del risultato non può superare i 2MB<br />";
              }
            } 
            if ($_FILES['newFotoRisultatoValue']["type"] != "image/jpeg") {
              if(isset($msg['error'])){
                $msg['error'].="La foto del risultato deve essere in formato JPEG<br />";
              }
              else{
                $msg['error']="La foto del risultato deve essere in formato JPEG<br />";
              }
            } 
          }
        }else{
          if(isset($msg['error'])){
            $msg['error'].="La foto del risultato è obbligatoria<br />";
          }
          else{
            $msg['error']="La foto del risultato è obbligatoria<br />";
          }
        }

        if(isset($_POST['newLivelloDifficoltaValue']) && !empty($_POST['newLivelloDifficoltaValue']) && $_POST['newLivelloDifficoltaValue']!="Scegli..."){
          #tutto ok
        }else{
          if(isset($msg['error'])){
            $msg['error'].="Il livello di difficoltà è obbligatorio<br />";
          }
          else{
            $msg['error']="Il livello di difficoltà è obbligatorio<br />";
          }
        }

        if(isset($_POST['newLivelloTempoRichiestoValue']) && !empty($_POST['newLivelloTempoRichiestoValue']) && $_POST['newLivelloTempoRichiestoValue']!="Scegli..."){
          #tutto ok
        }else{
          if(isset($msg['error'])){
            $msg['error'].="Il livello di tempo è obbligatorio<br />";
          }
          else{
            $msg['error']="Il livello di tempo è obbligatorio<br />";
          }
        }

        if(isset($_POST['newLivelloAutorizzazioneRichiestoValue']) && !empty($_POST['newLivelloAutorizzazioneRichiestoValue']) && $_POST['newLivelloAutorizzazioneRichiestoValue']!="Scegli..."){
          if (($_SESSION['livelloAutorizzazione'] == 'novizio' && $_POST['newLivelloAutorizzazioneRichiestoValue'] == 'novizio')
              || ($_SESSION['livelloAutorizzazione'] == 'principiante' && ($_POST['newLivelloAutorizzazioneRichiestoValue'] == 'novizio' || $_POST['newLivelloAutorizzazioneRichiestoValue'] == 'principiante'))
              || ($_SESSION['livelloAutorizzazione'] == 'intermedio' && ($_POST['newLivelloAutorizzazioneRichiestoValue'] == 'novizio' || $_POST['newLivelloAutorizzazioneRichiestoValue'] == 'principiante' || $_POST['newLivelloAutorizzazioneRichiestoValue'] == 'intermedio'))
              || ($_SESSION['livelloAutorizzazione'] == 'specialista' && ($_POST['newLivelloAutorizzazioneRichiestoValue'] == 'novizio' || $_POST['newLivelloAutorizzazioneRichiestoValue'] == 'principiante' || $_POST['newLivelloAutorizzazioneRichiestoValue'] == 'intermedio' || $_POST['newLivelloAutorizzazioneRichiestoValue'] == 'specialista'))
              || ($_SESSION['livelloAutorizzazione'] == 'innovatore' && ($_POST['newLivelloAutorizzazioneRichiestoValue'] == 'novizio' || $_POST['newLivelloAutorizzazioneRichiestoValue'] == 'principiante' || $_POST['newLivelloAutorizzazioneRichiestoValue'] == 'intermedio' || $_POST['newLivelloAutorizzazioneRichiestoValue'] == 'specialista' || $_POST['newLivelloAutorizzazioneRichiestoValue'] == 'innovatore'))
              ){
                #tutto ok
            }else{
              if(isset($msg['error'])){
                $msg['error'].="Non è consentito richiedere un livello di autorizzazione maggiore del proprio<br />";
              }
              else{
                $msg['error']="Non è consentito richiedere un livello di autorizzazione maggiore del proprio<br />";
              }
            }
        }else{
          if(isset($msg['error'])){
            $msg['error'].="Il livello di autorizzazione è obbligatorio<br />";
          }
          else{
            $msg['error']="Il livello di autorizzazione è obbligatorio<br />";
          }
        }

        if(isset($_POST['newTitoloDiscussioneValue']) && !empty($_POST['newTitoloDiscussioneValue'])){
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
          
          $xpath=new DOMXpath($discussioniXml);
          $discussionePreinserita=$xpath->query("//titolo[text()='".$_POST['newTitoloDiscussioneValue']."']")->item(0);
          if ($discussionePreinserita){
            if(isset($msg['error'])){
              $msg['error'].="Il titolo della discussione è già stato utilizzato<br />";
            }
            else{
              $msg['error']="Il titolo della discussione è già stato utilizzato<br />";
            }
          }
        }else{
          if(isset($msg['error'])){
            $msg['error'].="Il titolo della discussione è obbligatorio<br />";
          }
          else{
            $msg['error']="Il titolo della discussione è obbligatorio<br />";
          }
        }
        
        if(isset($_POST['newDescrizioneDiscussioneValue']) && !empty($_POST['newDescrizioneDiscussioneValue'])){ 
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
            $descrizioneDiscussione = $discussione->firstChild->nextSibling->nextSibling;
            $descrizioneDiscussioneValue = $descrizioneDiscussione->textContent;
            if ($descrizioneDiscussioneValue == $_POST['newDescrizioneDiscussioneValue']){
              $descrizioneDiscussioneProgettoPreinserita="yes";
              break;
            }
          }
          
          if ($descrizioneDiscussioneProgettoPreinserita){
            if(isset($msg['error'])){
              $msg['error'].="La descrizione della discussione è già stata utilizzata<br />";
            }
            else{
              $msg['error']="La descrizione della discussione è già stata utilizzata<br />";
            }
          }
        }else{
          if(isset($msg['error'])){
            $msg['error'].="La descrizione della discussione è obbligatoria<br />";
          }
          else{
            $msg['error']="La descrizione della discussione è obbligatoria<br />";
          }
        }

        if(!isset($msg['error']) && !($titoloProgettoPreinserito && $descrizioneProgettoPreinserita)){
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

          for ($l=0; $l<$progetti->length; $l++) {
              $progetto = $progetti->item($l);
              $id_pro = $progetto->getAttribute('id');
          }
          $last_project=$progetto;
          $id_last_project = (int)$id_pro;
          $idProgettoValue=$id_last_project+1;
          $new_project = $progettiXml->createElement("progetto");
          $new_project->setAttribute("id", $idProgettoValue);
          $docElem->appendChild($new_project); 
                
          $newIdResponsabileValue=(int)$_SESSION['id'];
          $new_idResp = $progettiXml->createElement("idResponsabile");
          $new_idResp_text = $progettiXml->createTextNode($newIdResponsabileValue);
          $new_idResp->appendChild($new_idResp_text);
          $new_project->appendChild($new_idResp);
          
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

          for ($l=0; $l<$discussioni->length; $l++) {
              $discussione = $discussioni->item($l);
              $id_dis = $discussione->getAttribute('id');
          }
          $last_discussion=$discussione;
          $id_last_discussion = (int)$id_dis;
          $idDiscussioneValue=$id_last_discussion+1;
          $new_idDiscuss = $progettiXml->createElement("idDiscussione");
          $new_idDiscuss_text = $progettiXml->createTextNode($idDiscussioneValue);
          $new_idDiscuss->appendChild($new_idDiscuss_text);
          $new_project->appendChild($new_idDiscuss);

          $newTitoloValue=$_POST['newTitoloValue'];
          $new_titolo = $progettiXml->createElement("titolo");
          $new_titolo_text = $progettiXml->createTextNode($newTitoloValue);
          $new_titolo->appendChild($new_titolo_text);
          $new_project->appendChild($new_titolo);

          $newDescrizioneValue=$_POST['newDescrizioneValue'];
          $new_Descriz = $progettiXml->createElement("descrizione");
          $new_Descriz_text = $progettiXml->createTextNode($newDescrizioneValue);
          $new_Descriz->appendChild($new_Descriz_text);
          $new_project->appendChild($new_Descriz);

          $newCategoriaValue=$_POST['newCategoriaValue'];
          $new_categoria = $progettiXml->createElement("categoria");
          $new_categoria_text = $progettiXml->createTextNode($newCategoriaValue);
          $new_categoria->appendChild($new_categoria_text);
          $new_project->appendChild($new_categoria);

          $newFotoComponentiValue=base64_encode(file_get_contents($_FILES['newFotoComponentiValue']['tmp_name'])); 
          $new_fotoComponenti = $progettiXml->createElement("fotoComponenti");
          $new_fotoComponenti_text = $progettiXml->createTextNode($newFotoComponentiValue);
          $new_fotoComponenti->appendChild($new_fotoComponenti_text);
          $new_project->appendChild($new_fotoComponenti);

          $newFotoRisultatoValue=base64_encode(file_get_contents($_FILES['newFotoRisultatoValue']['tmp_name'])); 
          $new_fotoRisultato = $progettiXml->createElement("fotoRisultato");
          $new_fotoRisultato_text = $progettiXml->createTextNode($newFotoRisultatoValue);
          $new_fotoRisultato->appendChild($new_fotoRisultato_text);
          $new_project->appendChild($new_fotoRisultato);

          $newLivelloDifficoltaValue=(int)$_POST['newLivelloDifficoltaValue'];
          $new_livelloDifficolta = $progettiXml->createElement("livelloDifficolta");
          $new_livelloDifficolta_text = $progettiXml->createTextNode($newLivelloDifficoltaValue);
          $new_livelloDifficolta->appendChild($new_livelloDifficolta_text);
          $new_project->appendChild($new_livelloDifficolta);

          $newLivelloTempoRichiestoValue=(int)$_POST['newLivelloTempoRichiestoValue'];
          $new_livelloTempoRichiesto = $progettiXml->createElement("livelloTempoRichiesto");
          $new_livelloTempoRichiesto_text = $progettiXml->createTextNode($newLivelloTempoRichiestoValue);
          $new_livelloTempoRichiesto->appendChild($new_livelloTempoRichiesto_text);
          $new_project->appendChild($new_livelloTempoRichiesto);

          $newLivelloAutorizzazioneRichiestoValue=$_POST['newLivelloAutorizzazioneRichiestoValue'];
          $new_livelloAutorizzazioneRichiesto = $progettiXml->createElement("livelloAutorizzazioneRichiesto");
          $new_livelloAutorizzazioneRichiesto_text = $progettiXml->createTextNode($newLivelloAutorizzazioneRichiestoValue);
          $new_livelloAutorizzazioneRichiesto->appendChild($new_livelloAutorizzazioneRichiesto_text);
          $new_project->appendChild($new_livelloAutorizzazioneRichiesto);

          $new_giudizioProjMedio = $progettiXml->createElement("giudizioProjMedio");
          $new_project->appendChild($new_giudizioProjMedio);

          $new_numGiudiziProj = $progettiXml->createElement("numGiudiziProj");
          $new_project->appendChild($new_numGiudiziProj);

          $newPesoValue="comune";
          $new_peso = $progettiXml->createElement("peso");
          $new_peso_text = $progettiXml->createTextNode($newPesoValue);
          $new_peso->appendChild($new_peso_text);
          $new_project->appendChild($new_peso);

          $newStatoValue="attivo";
          $new_stato = $progettiXml->createElement("stato");
          $new_stato_text = $progettiXml->createTextNode($newStatoValue);
          $new_stato->appendChild($new_stato_text);
          $new_project->appendChild($new_stato);

          $xmlString = "";
          foreach ( file("xml/steps.xml") as $node ) {
            $xmlString .= trim($node);
          }
          $stepsXml = new DOMDocument();
          if (!$stepsXml->loadXML($xmlString)) {
            die ("Parsing error\n");
          }
          $docElem = $stepsXml->documentElement;
          $steps = $docElem->childNodes;
          for ($k=1 ; $k<=$_COOKIE['numeroStep'] ; $k++) {
            $increaser=0;

            for ($l=0; $l<$steps->length; $l++) {
                $step = $steps->item($l);
                $id_ste = $step->getAttribute('id');
            }
            $last_step=$step;
            $id_last_step = (int)$id_ste;
            $idStepValue=$id_last_step+1 + $increaser;
            $new_step = $stepsXml->createElement("step");
            $new_step->setAttribute("id", $idStepValue);
            $docElem->appendChild($new_step); 
            
            $idProget = $stepsXml->createElement("idProgetto");
            $idProget_text = $stepsXml->createTextNode($idProgettoValue);
            $idProget->appendChild($idProget_text);
            $new_step->appendChild($idProget);

            $testoStepValue=$_POST['testoStep'.$k.'Value']; 
            $testoSte = $stepsXml->createElement("testo");
            $testoSte_text = $stepsXml->createTextNode($testoStepValue); 
            $testoSte->appendChild($testoSte_text); 
            $new_step->appendChild($testoSte);

            $immagineStepValue=base64_encode(file_get_contents($_FILES['immagineStep'.$k.'Value']['tmp_name'])); 
            $immagineSte = $stepsXml->createElement("immagine");
            $immagineSte_text = $stepsXml->createTextNode($immagineStepValue); 
            $immagineSte->appendChild($immagineSte_text); 
            $new_step->appendChild($immagineSte);

            $increaser += 1;
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

          for ($l=0; $l<$discussioni->length; $l++) {
              $discussione = $discussioni->item($l);
              $id_dis = $discussione->getAttribute('id');
          }
          $last_discussion=$discussione;
          $id_last_discussion = (int)$id_dis;
          $idDiscussioneValue=$id_last_discussion+1;
          $new_discussion = $discussioniXml->createElement("discussione");
          $new_discussion->setAttribute("id", $idDiscussioneValue);
          $docElem->appendChild($new_discussion); 
                
          $new_idProgDisc = $discussioniXml->createElement("idProgetto");
          $new_idProgDisc_text = $discussioniXml->createTextNode($idProgettoValue);
          $new_idProgDisc->appendChild($new_idProgDisc_text);
          $new_discussion->appendChild($new_idProgDisc);

          $newTitoloDiscussioneValue=$_POST['newTitoloDiscussioneValue'];
          $new_titoloDisc = $discussioniXml->createElement("titolo");
          $new_titoloDisc_text = $discussioniXml->createTextNode($newTitoloDiscussioneValue);
          $new_titoloDisc->appendChild($new_titoloDisc_text);
          $new_discussion->appendChild($new_titoloDisc);

          $newDescrizioneDiscussioneValue=$_POST['newDescrizioneDiscussioneValue'];
          $new_DescrizDisc = $discussioniXml->createElement("descrizione");
          $new_DescrizDisc_text = $discussioniXml->createTextNode($newDescrizioneDiscussioneValue);
          $new_DescrizDisc->appendChild($new_DescrizDisc_text);
          $new_discussion->appendChild($new_DescrizDisc);

          $new_numCommenti = $discussioniXml->createElement("numCommenti");
          $new_discussion->appendChild($new_numCommenti);

          if (!($progettiXml->save("xml/progetti.xml") && $stepsXml->save("xml/steps.xml") && $discussioniXml->save("xml/discussioni.xml"))) {
            if(isset($msg['error'])){
              $msg['error'].="Salvataggio dati fallito<br />";
            }
            else{
              $msg['error']="Salvataggio dati fallito<br />";
            }
          }
        }
      }else if($_POST['modifica']=="elimina"){
        if(isset($_POST['newTitoloValue']) && !empty($_POST['newTitoloValue'])){
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
          for ($i=0; $i<$progetti->length; $i++) {
              $progetto = $progetti->item($i);
              $idProg = $progetto->getAttribute('id');
              $projectTitle = $progetto->firstChild->nextSibling->nextSibling;
              $projectTitleValue = $projectTitle->textContent;
              $responsibleId = $progetto->firstChild;
              $responsibleIdValue = $responsibleId->textContent;
              if($_POST['newTitoloValue']==$projectTitleValue){
                $titoloProgettoEsistente="yes";
                if($_SESSION['id']==$responsibleIdValue){
                  $discussionId = $progetto->firstChild->nextSibling;
                  $discussionIdValue = $discussionId->textContent;
                  $sonoIlResponsabile="yes";
                  break;
                }
              }
          }
          if (!$titoloProgettoEsistente){
            if(isset($msg['error'])){
              $msg['error'].="Il progetto da eliminare non esiste<br />";
            }
            else{
              $msg['error']="Il progetto da eliminare non esiste<br />";
            }
          }else{
            if(!$sonoIlResponsabile && $_SESSION['ruolo']!="admin"){
              if(isset($msg['error'])){
                $msg['error'].="Non puoi eliminare un progetto del quale non sei responsabile<br />";
              }
              else{
                $msg['error']="Non puoi eliminare un progetto del quale non sei responsabile<br />";
              }
            }
          }
        }else{
          if(isset($msg['error'])){
            $msg['error'].="Il titolo è obbligatorio<br />";
          }
          else{
            $msg['error']="Il titolo è obbligatorio<br />";
          }
        }

        if(!isset($msg['error']) && ($titoloProgettoEsistente && ($sonoIlResponsabile || $_SESSION['ruolo']=="admin"))){
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
          for ($p=0; $p<$progetti->length; $p++) {
            $progetto = $progetti->item($p);
            $thisIdProg = $progetto->getAttribute('id');
            if($idProg==$thisIdProg){
              $progetto->parentNode->removeChild($progetto);
              break;
            }
          } 

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
          for ($pr=0; $pr<$giudiziProgetti->length; $pr++) {
            $giudizioProgetto = $giudiziProgetti->item($pr);
            $id_giuProg = $giudizioProgetto->getAttribute('id');
            $idProgettoFromGiudizioProgetto = $giudizioProgetto->firstChild;
            $idProgettoFromGiudizioProgettoValue = $idProgettoFromGiudizioProgetto->textContent;
            if($idProg==$idProgettoFromGiudizioProgettoValue){
                $giudizioProgetto->parentNode->removeChild($giudizioProgetto);
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
          for ($d=0; $d<$discussioni->length; $d++) {
            $discussione = $discussioni->item($d);
            $thisIdDisc = $discussione->getAttribute('id');
            if($discussionIdValue==$thisIdDisc){
              $discussione->parentNode->removeChild($discussione);
              break;
            }
          } 

          $xmlString = "";
          foreach ( file("xml/steps.xml") as $node ) {
            $xmlString .= trim($node);
          }
          $stepsXml = new DOMDocument();
          if (!$stepsXml->loadXML($xmlString)) {
            die ("Parsing error\n");
          }
          $docElem = $stepsXml->documentElement;
          $steps = $docElem->childNodes;
          for ($s=0; $s<$steps->length; $s++) {
            $step = $steps->item($s);
            $idProgFromStep = $step->firstChild;
            $idProgFromStepValue = $idProgFromStep->textContent;
            if($idProg==$idProgFromStepValue){
              $step->parentNode->removeChild($step);
              /* questo si ripete per ogni step del progetto da eliminare */
            }
          }

          if (!($progettiXml->save("xml/progetti.xml") && $giudiziProgettiXml->save("xml/giudiziProgetti.xml") && $stepsXml->save("xml/steps.xml") && $discussioniXml->save("xml/discussioni.xml"))) {
            if(isset($msg['error'])){
              $msg['error'].="Salvataggio dati fallito<br />";
            }
            else{
              $msg['error']="Salvataggio dati fallito<br />";
            }
          }
        }
      } 
    }else{
      if(isset($msg['error'])){
        $msg['error'].="Il tipo di modifica &egrave obbligatorio<br />";
      }
      else{
        $msg['error']="Il tipo di modifica &egrave obbligatorio<br />";
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

        <?php if((!isset($_SESSION['username'])) || (isset($_SESSION) && $_SESSION['stato'] == 'sospeso' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin'))){ ?>
            <title>access denied</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php }else if (isset($_SESSION) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
            <title>LocalCommunity - Gestisci i progetti</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php } ?>
    </head>
    
    <?php if((!isset($_SESSION['username'])) || (isset($_SESSION) && $_SESSION['stato'] == 'sospeso' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin'))){ ?>
      <body>
          <h1>access denied</h1>
      </body>
    <?php }else if (isset($_SESSION) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
        <body style="background-repeat: no-repeat; background-size: cover;" background="img/project.jpg">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="row" style="width: 100%;">
                    <div class="col-sm-6">
                        <a class="navbar-brand" href="index.php">LocalCommunity - Home</a>
                    </div>
                    <div class="col-sm-6">
                        <form method="post" action="manage_projects.php">
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
                <div  class="card text-center" style="opacity:85%; border:solid 2px black; width: 81.4rem; position: relative; top: 1rem;" >
                    <div class="card-body">
                        <h5 class="card-header" style="background-color: white; color: black">
                            Gestisci i progetti
                        </h5>

                        <form method="post" action="manage_projects.php">
                          <div class="row" style="width: 102.5%;">
                            <div class="col-sm-11">
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <label style="width: 9rem;" class="input-group-text" for="inputGroupSelect01">Numero step</label>
                                </div>
                                <select class="custom-select" id="inputGroupSelect01" name="numeroStep">
                                  <option selected>Scegli...</option>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                                  <option value="11">11</option>
                                  <option value="12">12</option>
                                  <option value="13">13</option>
                                  <option value="14">14</option>
                                  <option value="15">15</option>
                                  <option value="16">16</option>
                                  <option value="17">17</option>
                                  <option value="18">18</option>
                                  <option value="19">19</option>
                                  <option value="20">20</option>
                                  <option value="21">21</option>
                                  <option value="22">22</option>
                                  <option value="23">23</option>
                                  <option value="24">24</option>
                                  <option value="25">25</option>
                                  <option value="26">21</option>
                                  <option value="27">27</option>
                                  <option value="28">28</option>
                                  <option value="29">29</option>
                                  <option value="30">30</option>
                                  <option value="31">31</option>
                                  <option value="32">32</option>
                                  <option value="33">33</option>
                                  <option value="34">34</option>
                                  <option value="35">35</option>
                                  <option value="36">36</option>
                                  <option value="37">37</option>
                                  <option value="38">38</option>
                                  <option value="39">39</option>
                                  <option value="40">40</option>
                                  <option value="41">41</option>
                                  <option value="42">42</option>
                                  <option value="43">43</option>
                                  <option value="44">44</option>
                                  <option value="45">45</option>
                                  <option value="46">46</option>
                                  <option value="47">47</option>
                                  <option value="48">48</option>
                                  <option value="49">49</option>
                                  <option value="50">50</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <input type="submit" style="float: center;" class="btn btn-dark" name="choose" value="Scegli">
                            </div>
                          </div>  
                        </form>

                        <form method="post" action="manage_projects.php" enctype="multipart/form-data"> 
                          <?php /* adjust - poi fare controlli per il tipo jpeg */
                            if (isset($_POST['numeroStep']) && !empty($_POST['numeroStep'])){
                                for ($k=1 ; $k<=$_POST['numeroStep'] ; $k++) {
                                  echo '<div class="input-group mb-3">
                                          <label style="width: 9rem" class="input-group-text" for="inputGroupFile01">Immagine step '.$k.'</label>
                                          <input type="file" class="form-control" id="inputGroupFile01" name="immagineStep'.$k.'Value">
                                        </div>
                                        <div class="input-group mb-3">
                                          <div class="input-group-prepend" style="height: 5.5rem;">
                                              <span style="width: 9rem;" class="input-group-text" id="inputGroup-sizing-default">Testo step '.$k.'</span>
                                          </div>
                                          <textarea type="text" class="form-control" style="height: 5.5rem;" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="exampleFormControlTextarea1" name="testoStep'.$k.'Value" rows="3"></textarea>
                                        </div> 
                                        <hr />' ;
                                  }
                              }
                          ?>

                          <div class="row" style="width: 102.5%;">
                            <div class="col-sm-3">
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <label style="width: 9rem;" class="input-group-text" for="inputGroupSelect01">Tipo di modifica</label>
                                </div>
                                <select class="custom-select" id="inputGroupSelect01" name="modifica">
                                  <option selected>Scegli...</option>
                                  <option value="posta">Posta progetto</option>
                                  <option value="elimina">Elimina progetto</option>
                                </select>
                              </div>
                            </div> 
                            <div class="col-sm-6"> 
                              <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                      <span style="width: 4rem;" class="input-group-text" id="inputGroup-sizing-default">Titolo</span>
                                  </div>
                                  <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="newTitoloValue" value="<?php echo $_POST['newTitoloValue'] ?>">
                              </div>
                            </div>
                            <div class="col-sm-3"> 
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <label style="width: 6rem;" class="input-group-text" for="inputGroupSelect01">Categoria</label>
                                </div>
                                <select class="custom-select" id="inputGroupSelect01" name="newCategoriaValue">
                                  <option selected>Scegli...</option>
                                  <option value="rinnovavile">Rinnovabile</option>
                                  <option value="smaltimento">Smaltimento</option>
                                  <option value="salvaguardia">Salvaguardia</option>
                                  <option value="spaziale">Spaziale</option>
                                  <option value="gadget">Gadget</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend" style="height: 4rem;">
                                  <span style="width: 9rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione</span>
                              </div>
                              <textarea type="text" class="form-control" style="height: 4rem;" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="exampleFormControlTextarea1" name="newDescrizioneValue" rows="2"><?php echo $_POST['newDescrizioneValue'] ?></textarea>
                          </div>
                          <div class="row" style="width: 102.5%;">
                            <div class="col-sm-6">
                              <div class="input-group mb-3">
                                <label style="width: 9rem" class="input-group-text" for="inputGroupFile01">Foto componenti</label>
                                <input type="file" class="form-control" id="inputGroupFile01" name="newFotoComponentiValue" accept="image/jpeg">
                              </div>
                            </div> 
                            <div class="col-sm-6">
                              <div class="input-group mb-3">
                                <label style="width: 9.7rem" class="input-group-text" for="inputGroupFile01">Foto risultato</label>
                                <input type="file" class="form-control" id="inputGroupFile01" name="newFotoRisultatoValue" accept="image/jpeg">
                              </div>
                            </div>
                          </div>
                          <div class="row" style="width: 102.5%;">
                            <div class="col-sm-4">
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <label style="width: 9rem;" class="input-group-text" for="inputGroupSelect01">Liv. di difficoltà</label>
                                </div>
                                <fieldset class="rating">
                                  <input class="form-check-input" type="radio" name="newLivelloDifficoltaValue" id="star5" value="5" />
                                  <label class="form-check-label" for="star5" >5</label>
                                  <input class="form-check-input" type="radio" name="newLivelloDifficoltaValue" id="star4" value="4" />
                                  <label class="form-check-label" for="star4" >4</label>
                                  <input class="form-check-input" type="radio" name="newLivelloDifficoltaValue" id="star3" value="3" />
                                  <label class="form-check-label" for="star3" >3</label>
                                  <input class="form-check-input" type="radio" name="newLivelloDifficoltaValue" id="star2" value="2" />
                                  <label class="form-check-label" for="star2" >2</label>
                                  <input class="form-check-input" type="radio" name="newLivelloDifficoltaValue" id="star1" value="1" />
                                  <label class="form-check-label" for="star1" >1</label>
                                </fieldset>
                              </div>
                            </div> 
                            <div class="col-sm-4"> 
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <label style="width: 9.7rem;" class="input-group-text" for="inputGroupSelect01">Liv. di tempo</label>
                                </div>
                                <fieldset class="rating">
                                  <input class="form-check-input" type="radio" name="newLivelloTempoRichiestoValue" id="star10" value="5" />
                                  <label class="form-check-label" for="star10" >5</label>
                                  <input class="form-check-input" type="radio" name="newLivelloTempoRichiestoValue" id="star9" value="4" />
                                  <label class="form-check-label" for="star9" >4</label>
                                  <input class="form-check-input" type="radio" name="newLivelloTempoRichiestoValue" id="star8" value="3" />
                                  <label class="form-check-label" for="star8" >3</label>
                                  <input class="form-check-input" type="radio" name="newLivelloTempoRichiestoValue" id="star7" value="2" />
                                  <label class="form-check-label" for="star7" >2</label>
                                  <input class="form-check-input" type="radio" name="newLivelloTempoRichiestoValue" id="star6" value="1" />
                                  <label class="form-check-label" for="star6" >1</label>
                                </fieldset>
                              </div>
                            </div>
                            <div class="col-sm-4"> 
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <label style="width: 9.7rem;" class="input-group-text" for="inputGroupSelect01">Liv. Autorizzazione</label>
                                  </div>
                                  <select class="custom-select" id="inputGroupSelect01" name="newLivelloAutorizzazioneRichiestoValue">
                                    <option selected>Scegli...</option>
                                    <option value="novizio">Novizio</option>
                                    <option value="principiante">Principiante</option>
                                    <option value="intermedio">Intermedio</option>
                                    <option value="specialista">Specialista</option>
                                    <option value="innovatore">Innovatore</option>
                                  </select>
                                </div>
                            </div>
                          </div>

                          <hr />
                          <div class="row" style="width: 102.5%;">
                            <div class="col-sm-6">
                              <div class="input-group mb-3">
                                <div class="input-group-prepend" style="height: 4rem;">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Titolo discussione</span>
                                </div>
                                <input type="text" class="form-control" style="height: 4rem;" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="newTitoloDiscussioneValue" value="<?php echo $_POST['newTitoloDiscussioneValue'] ?>">
                              </div>
                            </div> 
                            <div class="col-sm-6">
                              <div class="input-group mb-3">
                                <div class="input-group-prepend" style="height: 4rem;">
                                  <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Descrizione discussione</span>
                                </div>
                                <textarea type="text" class="form-control" style="height: 4rem;" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="exampleFormControlTextarea1" name="newDescrizioneDiscussioneValue" rows="2"><?php echo $_POST['newDescrizioneDiscussioneValue'] ?></textarea>
                              </div>
                            </div> 
                          </div>
                          
                          <input type="submit" style="float: center;" class="btn btn-dark" name="confirm" value="Conferma">
                        </form>
                    </div>
                </div>
                <br />
            </div>

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

                if ($idResponsabileValue == $_SESSION['id']) {
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

              echo '</div>';
            ?>
            
        </body>
    <?php } ?>

</html>