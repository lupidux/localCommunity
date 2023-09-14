<?php 
    require_once("connect.php");

    if($_GET['registration']=="success"){
      $msg['benvenuto']="Benvenuto nella tua area riservata ".$_SESSION['nome']."<br />";
    }else if($_GET['login']=="success"){
      $msg['bentornato']="Bentornato nella tua area riservata ".$_SESSION['nome']."<br />";
    }

    if(isset($_GET['logout']) && !empty($_GET['logout'])){
      session_destroy();
      header("Location: index.php");
    }

    if(isset($_SESSION['username'])){
      $reputazione=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT reputazione FROM utente WHERE id=".(int)$_SESSION['id']));
      $reputazione=(float)$reputazione['reputazione'];
      $oldLivelloAutorizzazione=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT livelloAutorizzazione FROM utente WHERE id=".(int)$_SESSION['id']));
      $oldLivelloAutorizzazione=$oldLivelloAutorizzazione['livelloAutorizzazione'];
      if($reputazione>=0 && $reputazione<=200){
        $newLivelloAutorizzazione="novizio";
      }else if($reputazione>200 && $reputazione<=400){ 
        $newLivelloAutorizzazione="principiante";
      }else if($reputazione>400 && $reputazione<=600){
        $newLivelloAutorizzazione="intermedio";
      }else if($reputazione>600 && $reputazione<=800){
        $newLivelloAutorizzazione="specialista";
      }else if($reputazione>800 && $reputazione<=1000){
        $newLivelloAutorizzazione="innovatore";
      }
      if($oldLivelloAutorizzazione==$newLivelloAutorizzazione){ 
        #tutto ok
      }else{
        $result=mysqli_query($mysqliConnection,"UPDATE utente SET livelloAutorizzazione='".$newLivelloAutorizzazione."' WHERE id=".$_SESSION['id']);
        $_SESSION['livelloAutorizzazione']=$newLivelloAutorizzazione;
      }

      $reputazione=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT reputazione FROM utente WHERE id=".(int)$_SESSION['id']));
      $reputazione=(float)$reputazione['reputazione'];
      if($reputazione>=1 && $reputazione <=333){
        $newPesoProgetti="comune";
      }else if($reputazione>333 && $reputazione <=666){
        $newPesoProgetti="popolare";
      }else if($reputazione>666 && $reputazione <=1000){
        $newPesoProgetti="rivoluzionario";
      }
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
      for ($j=0; $j<$progetti->length; $j++) {
        $progetto = $progetti->item($j);
        $id_Prog = $progetto->getAttribute('id');
        $idResponsabile=$progetto->firstChild;
        $idResponsabileValue=$idResponsabile->textContent;
        if($idResponsabileValue==$_SESSION['id']){
          $nodo_peso=$progetto->lastChild->previousSibling;
          $oldPeso=$nodo_peso->textContent;
          if($newPesoProgetti==$oldPeso){ 
            #tutto ok
          }else{
            $nodo_pesoValue = $nodo_peso->nodeValue = $newPesoProgetti; 
            /* questo si ripete per ogni progetto di cui l'utente è responsabile */
          }
        }
      }
      $progettiXml->save("xml/progetti.xml");
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">

    <title>LocalCommunity - Home</title>
    <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="row" style="width: 110%;">
        <div class="col-sm-1">
          <a class="navbar-brand" href="index.php">LocalCommunity - Home</a>
        </div>
        <?php if(!isset($_SESSION['username'])){ ?>
          <div class="col-sm-11 text-right">
            <a class="btn btn-dark mr" href="index_projects.php">Progetti</a>
            <a class="btn btn-dark mr" href="signup.php">Registrati</a>
            <a class="btn btn-dark mr" href="login.php">Login</a>
          </div>
        <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'attivo' && $_SESSION['ruolo'] == 'utente interno'){ ?>
          <div class="col-sm-11  text-right">        
            <a class="btn btn-dark mr" href="index_projects.php">Progetti</a>
            <a class="btn btn-dark mr" href="manage_projects.php">Gestisci i tuoi progetti</a>
            <a class="btn btn-dark mr" href="index_manage_discussions.php">Partecipa alle discussioni</a> 
            <a class="btn btn-dark mr" href="manage_requests.php">Gestisci richieste</a>
            <a class="btn btn-dark mr" href="report.php">Fai un report</a>
            <a class="btn btn-dark mr" href="profile.php">Profilo</a>
            <a class="btn btn-dark mr" href="index.php?logout=true">Logout</a>
          <div>
        <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'attivo' && $_SESSION['ruolo'] == 'moderatore'){ ?> 
          <div class="col-sm-11  text-right"> 
            <a class="btn btn-dark mr" href="index_projects.php">Progetti</a>
            <a class="btn btn-dark mr" href="manage_projects.php">Gestisci progetti</a> 
            <a class="btn btn-dark mr" href="index_manage_discussions.php">Partecipa alle discussioni</a> 
            <a class="btn btn-dark mr" href="manage_requests.php">Gestisci richieste</a>
            <a class="btn btn-dark mr" href="report.php">Visualizza report</a>
            <a class="btn btn-dark mr" href="profile.php?index=1">Gestisci profili</a>
            <a class="btn btn-dark mr" href="index.php?logout=false">Logout</a>
          </div>
        <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'attivo' && $_SESSION['ruolo'] == 'admin'){ ?>
          <div class="col-sm-11  text-right"> 
            <a class="btn btn-dark mr" href="index_projects.php">Progetti</a>
            <a class="btn btn-dark mr" href="manage_projects.php">Gestisci progetti</a> 
            <a class="btn btn-dark mr" href="index_manage_discussions.php">Partecipa alle discussioni</a> 
            <a class="btn btn-dark mr" href="manage_requests.php">Gestisci richieste</a>
            <a class="btn btn-dark mr" href="profile.php?index=1">Gestisci profili</a>
            <a class="btn btn-dark mr" href="index.php?logout=false">Logout</a>
          </div> 
        <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'sospeso' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
          <div class="col-sm-11  text-right"> 
            <a class="btn btn-dark mr" href="index.php?logout=false">Logout</a>
          </div> 
        <?php } ?>
      </div>
    </nav>

      <?php if (!isset($_SESSION['username'])){?>
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="position: relative">
          <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
          </ol>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img class="d-block w-100" src="img/index_1.jpg" alt="First slide" height="600px">
            </div>
            <div class="carousel-item">
              <img class="d-block w-100" src="img/index_2.jpg" alt="Second slide" height="600px">
            </div>
            <div class="carousel-item">
              <img class="d-block w-100" src="img/index_3.jpg" alt="Third slide" height="600px">
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        <section class="sbg-light text-center" style="position: absolute; top: 13.5rem; left: 7rem">
          <div align="center" class="container">
            <div class="card text-center" style="opacity: 65%; width: 68rem; height: 16rem; border-radius: 10rem;">
              <div class="row" style="position: relative; top: 2.5rem;">
                <div class="col-lg-4">
                  <div style="padding: 1rem; position: relative; left: 3.5rem;">
                    <i class="bi bi-broadcast-pin"></i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-broadcast-pin" viewBox="0 0 16 16">
                      <path d="M3.05 3.05a7 7 0 0 0 0 9.9.5.5 0 0 1-.707.707 8 8 0 0 1 0-11.314.5.5 0 0 1 .707.707zm2.122 2.122a4 4 0 0 0 0 5.656.5.5 0 1 1-.708.708 5 5 0 0 1 0-7.072.5.5 0 0 1 .708.708zm5.656-.708a.5.5 0 0 1 .708 0 5 5 0 0 1 0 7.072.5.5 0 1 1-.708-.708 4 4 0 0 0 0-5.656.5.5 0 0 1 0-.708zm2.122-2.12a.5.5 0 0 1 .707 0 8 8 0 0 1 0 11.313.5.5 0 0 1-.707-.707 7 7 0 0 0 0-9.9.5.5 0 0 1 0-.707zM6 8a2 2 0 1 1 2.5 1.937V15.5a.5.5 0 0 1-1 0V9.937A2 2 0 0 1 6 8z"/>
                    </svg>
                    <h3><b>Connettiti</b></h3>
                    <p class="lead mb-0">Ad una vasta rete di persone con la quale lavorare assieme.</p>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div style="padding: 1rem">
                    <i class="bi bi-door-open-fill"></i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-door-open-fill" viewBox="0 0 16 16">
                      <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15H1.5zM11 2h.5a.5.5 0 0 1 .5.5V15h-1V2zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>
                    </svg>
                    <h3><b>Entra</b></h3>
                    <p class="lead mb-0">In mondo dinamico e avanguardistico da protagonista.</p>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div style="padding: 1rem; position: relative; right: 3.5rem;">
                    <i class="bi bi-cloud-upload-fill"></i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-cloud-upload-fill" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 4.095 0 5.555 0 7.318 0 9.366 1.708 11 3.781 11H7.5V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11h4.188C14.502 11 16 9.57 16 7.773c0-1.636-1.242-2.969-2.834-3.194C12.923 1.999 10.69 0 8 0zm-.5 14.5V11h1v3.5a.5.5 0 0 1-1 0z"/>
                    </svg>
                    <h3><b>Ispira</b></h3>
                    <p class="lead mb-0">Una comunità internazionale con le tue idee e progetti.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

      <?php }else if(isset($_SESSION) && $_SESSION['ruolo'] == 'utente interno' && $_SESSION['stato'] == 'attivo'){ ?> 
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
          </ol>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img class="d-block w-100" src="img/ut_interno_1.jpg" alt="First slide" height="600px">
            </div>
            <div class="carousel-item">
              <img class="d-block w-100" src="img/ut_interno_2.jpg" alt="Second slide" height="600px">
            </div>
            <div class="carousel-item">
              <img class="d-block w-100" src="img/ut_interno_3.jpg" alt="Third slide" height="600px">
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        <section class="sbg-light text-center" style="position: absolute; top: 13.5rem; left: 7rem">
          <div align="center" class="container">
            <div class="card text-center" style="opacity: 65%; width: 68rem; height: 16rem; border-radius: 10rem;">
              <div class="row" style="position: relative; top: 2.5rem;">
                <div class="col-lg-4">
                  <div style="padding: 1rem; position: relative; left: 3.5rem;">
                    <i class="bi bi-eyeglasses"></i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-eyeglasses" viewBox="0 0 16 16">
                      <path d="M4 6a2 2 0 1 1 0 4 2 2 0 0 1 0-4zm2.625.547a3 3 0 0 0-5.584.953H.5a.5.5 0 0 0 0 1h.541A3 3 0 0 0 7 8a1 1 0 0 1 2 0 3 3 0 0 0 5.959.5h.541a.5.5 0 0 0 0-1h-.541a3 3 0 0 0-5.584-.953A1.993 1.993 0 0 0 8 6c-.532 0-1.016.208-1.375.547zM14 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/>
                    </svg>
                    <h3><b>Visualizza</b></h3>
                    <p class="lead mb-0">I progetti più interessanti in campo di innovazione ecologica.</p>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div style="padding: 1rem">
                    <i class="bi bi-stickies-fill"></i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-stickies-fill" viewBox="0 0 16 16">
                      <path d="M0 1.5V13a1 1 0 0 0 1 1V1.5a.5.5 0 0 1 .5-.5H14a1 1 0 0 0-1-1H1.5A1.5 1.5 0 0 0 0 1.5z"/>
                      <path d="M3.5 2A1.5 1.5 0 0 0 2 3.5v11A1.5 1.5 0 0 0 3.5 16h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 16 9.586V3.5A1.5 1.5 0 0 0 14.5 2h-11zm6 8.5a1 1 0 0 1 1-1h4.396a.25.25 0 0 1 .177.427l-5.146 5.146a.25.25 0 0 1-.427-.177V10.5z"/>
                    </svg>
                    <h3><b>Posta</b></h3>
                    <p class="lead mb-0">I tuoi progetti facendo la differenza nel mondo di domani.</p>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div style="padding: 1rem; position: relative; right: 3.5rem;">
                    <i class="bi bi-pencil-square"></i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                    <h3><b>Partecipa</b></h3>
                    <p class="lead mb-0">Valutando e commentando le discussioni dei tuoi progetti preferiti.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

      <?php }else if(isset($_SESSION) && $_SESSION['ruolo'] == 'moderatore' && $_SESSION['stato'] == 'attivo'){ ?> 
          <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img class="d-block w-100" src="img/moderator_1.jpg" alt="First slide" height="600px">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="img/moderator_2.jpg" alt="Second slide" height="600px">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="img/moderator_3.jpg" alt="Third slide" height="600px">
              </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
          <section class="sbg-light text-center" style="position: absolute; top: 13.5rem; left: 7rem">
            <div align="center" class="container">
              <div class="card text-center" style="opacity: 65%; width: 68rem; height: 16rem; border-radius: 10rem;">
                <div class="row" style="position: relative; top: 2.5rem;">
                  <div class="col-lg-4">
                    <div style="padding: 1rem; position: relative; left: 3.5rem;">
                      <i class="bi bi-person-check-fill"></i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-check-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                        <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                      </svg>
                      <h3><b>Controlla</b></h3>
                      <p class="lead mb-0">I commenti di ciascuna discussione.</p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div style="padding: 1rem">
                      <i class="bi bi-flag-fill"></i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-flag-fill" viewBox="0 0 16 16">
                        <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001"/>
                      </svg>
                      <h3><b>Ricevi</b></h3>
                      <p class="lead mb-0">Report su commenti e progetti da parte degli utenti.</p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div style="padding: 1rem; position: relative; right: 3.5rem;">
                      <i class="bi bi-shield-fill-plus"></i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-shield-fill-plus" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zm-.5 5a.5.5 0 0 1 1 0v1.5H10a.5.5 0 0 1 0 1H8.5V9a.5.5 0 0 1-1 0V7.5H6a.5.5 0 0 1 0-1h1.5V5z"/>
                      </svg>
                      <h3><b>Garantisci</b></h3>
                      <p class="lead mb-0">L'avanzamento dei progetti gestendo gli spammer.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

      <?php }else if(isset($_SESSION) && $_SESSION['ruolo'] == 'admin' && $_SESSION['stato'] == 'attivo'){ ?>
          <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img class="d-block w-100" src="img/admin_1.jpg" alt="First slide" height="600px">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="img/admin_2.jpg" alt="Second slide" height="600px">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="img/admin_3.jpg" alt="Third slide" height="600px">
              </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
          <section class="sbg-light text-center" style="position: absolute; top: 13.5rem; left: 7rem">
            <div align="center" class="container">
              <div class="card text-center" style="opacity: 65%; width: 68rem; height: 16rem; border-radius: 10rem;">
                <div class="row" style="position: relative; top: 2.5rem;">
                  <div class="col-lg-4">
                    <div style="padding: 1rem; position: relative; left: 3.5rem;">
                      <i class="bi bi-gear-wide-connected"></i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                        <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434l.071-.286zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5zm0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78h4.723zM5.048 3.967c-.03.021-.058.043-.087.065l.087-.065zm-.431.355A4.984 4.984 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8 4.617 4.322zm.344 7.646.087.065-.087-.065z"/>
                      </svg>
                      <h3><b>Amministra</b></h3>
                      <p class="lead mb-0">Il portale LocalCommunity con strumenti unici.</p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div style="padding: 1rem">
                      <i class="bi bi-file-earmark-code-fill"></i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-file-earmark-code-fill" viewBox="0 0 16 16">
                        <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM6.646 7.646a.5.5 0 1 1 .708.708L5.707 10l1.647 1.646a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708l2-2zm2.708 0 2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L10.293 10 8.646 8.354a.5.5 0 1 1 .708-.708z"/>
                      </svg>
                      <h3><b>Esegui</b></h3>
                      <p class="lead mb-0">Qualsiasi operazione su commenti, discussioni e progetti.</p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div style="padding: 1rem; position: relative; right: 3.5rem;">
                      <i class="bi bi-person-bounding-box"></i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-bounding-box" viewBox="0 0 16 16">
                        <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5z"/>
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                      </svg>
                      <h3><b>Gestisci</b></h3>
                      <p class="lead mb-0">Tutti le tipologie di utenti.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        
      <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'sospeso'){ ?>
        <body style="background-repeat: no-repeat; background-size: cover;" background="img/index_3.jpg">
          <div class="card text-center" style="opacity:85%; border:solid 2px black; width: 30rem; position: relative; left: 32%; top: 8.5rem;" >
            <div class="card-body">
              <h5 class="card-header" style="background-color: white; color: black">
                Account sospeso
              </h5>

              <br />
              <h1>Potrai riutilizzare il tuo account una volta riattivato.</h1>
              
            </div>
          </div>
          <br /><br /><br /><br />
        </body>
      <?php } ?>

    <?php if(isset($msg['benvenuto'])){?>
      <div class="card text-center" style="opacity:80%; border:solid 1px green; width: 20rem; position: absolute; top: 3.5rem; left: 76.5%;" >
        <?php echo $msg['benvenuto']; ?> 
      </div>
    <?php } ?>
    <?php if(isset($msg['bentornato'])){?>
      <div class="card text-center" style="opacity:80%; border:solid 1px green; width: 20rem; position: absolute; top: 3.5rem; left: 76.5%;" >
        <?php echo $msg['bentornato']; ?> 
      </div>
    <?php } ?>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  
  </body>
</html>