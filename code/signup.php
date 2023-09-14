<?php
  require_once("connect.php");

  if(isset($_POST['register'])){

    if(isset($_POST['username']) && !empty($_POST['username'])){
      $username=$_POST['username'];
      $check_username=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT username FROM utente WHERE username='".$username."'"));
      $check_username=$check_username['username'];

      if($check_username==$username){
        $msg['error']="L'username non e' disponibile<br />";
        $usernameError="yes";
      }else if($check_username!=$username){
        $query="INSERT INTO utente (username,password,nome,cognome,indirizzo,email,emailDiRecupero)
        VALUES ("."'".$_POST['username']."',";
        $usernameError="no";
      }
    }else{
      if(isset($msg['error'])){
        $msg['error'].="L'username e' obbligatorio<br />";
      }
      else{
        $msg['error']="L'username e' obbligatorio<br />";
      }
      $usernameError="yes";
    }

    if(isset($_POST['password']) && !empty($_POST['password'])){
      if(isset($_POST['cpassword']) && !empty($_POST['cpassword'])){
        if($_POST['password']==$_POST['cpassword']){
          $query.="'".$_POST['password']."',";
          $passwordError="no";
        }
        else{
          if(isset($msg['error'])){
            $msg['error'].="Le password non corrispondono<br />";
          }
          else{
            $msg['error']="Le password non corrispondono<br />";
          }
          $passwordError="yes";
        }
      }
      else{
        if(isset($msg['error'])){
          $msg['error'].="La conferma password e' obbligatoria<br />";
        }
        else{
          $msg['error']="La conferma password e' obbligatoria<br />";
        }
        $passwordError="yes";
      }
    }
    else{
      if(isset($msg['error'])){
        $msg['error'].="La password e' obbligatoria<br />";
      }
      else{
        $msg['error']="La password e' obbligatoria<br />";
      }
      $passwordError="yes";
    }

    if(isset($_POST['name']) && !empty($_POST['name'])){
      $query.="'".$_POST['name']."'".",";
      $nameError="no";
    }else{
      if(isset($msg['error'])){
        $msg['error'].="Il nome e' obbligatorio<br />";
      }
      else{
        $msg['error']="Il nome e' obbligatorio<br />";
      }
      $nameError="yes";
    }

    if(isset($_POST['surname']) && !empty($_POST['surname'])){
      $query.="'".$_POST['surname']."'".",";
    }else{
      $query.="NULL,";
    }

    if(isset($_POST['address']) && !empty($_POST['address'])){
      $query.="'".$_POST['address']."'".",";
    }else{
      $query.="NULL,";
    }

    if(isset($_POST['email']) && !empty($_POST['email'])){
      $query.="'".$_POST['email']."'".",";
      $emailError="no";
    }else{
      if(isset($msg['error'])){
        $msg['error'].="L'email e' obbligatoria<br />";
      }
      else{
        $msg['error']="L'email e' obbligatoria<br />";
      }
      $emailError="yes";
    }

    if(isset($_POST['recoveryEmail']) && !empty($_POST['recoveryEmail'])){
      $query.="'".$_POST['recoveryEmail']."'".");";
    }else{
      $query.="NULL);";
    }

    if(!isset($msg['error'])){
      $result=mysqli_query($mysqliConnection,$query);
      if($result){
        $userData=mysqli_query($mysqliConnection,"SELECT * FROM utente WHERE username='".$_POST['username']."' AND password='".$_POST['password']."'");
        $userData=mysqli_fetch_array($userData);
        $_SESSION=$userData;
        header("Location: index.php?registration=success");
      } else {
        $msg['error']="Registrazione non effettuata<br />";
      }
    }

  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>LocalCommunity - Sign Up</title>
    <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
  </head>

  <body style="background-repeat: no-repeat; background-size: cover;" background="img/login.jpg">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="row" style="width: 110%;">
        <div class="col-sm-8">
          <a class="navbar-brand" href="index.php">LocalCommunity - Home</a>
        </div>
      </div>
    </nav>

    <?php if(isset($msg['error'])){?>
      <div class="card text-center" style="opacity:80%; border:solid 1px red; width: 20rem; position: relative; left: 77%;" >
        <?php echo $msg['error']; ?> 
      </div>
    <?php } ?>
    <?php if(isset($msg['success'])){?>
      <div class="card text-center" style="opacity:80%; border:solid 1px red; width: 20rem; position: relative; left: 77%;" >
        <?php echo $msg['success']; ?> 
      </div>
    <?php } ?>

    <div class="card text-center" style="opacity:80%; border:solid 2px black; width: 30rem; height: 37rem; position: relative; left: 31%; top: 1rem;" >
      <div class="card-body">
        <h5 class="card-header" style="background-color: white; color: black">
            Effettua la Registrazione
        </h5>

        <form method="post" action="signup.php"> 
            <?php if($usernameError=="yes"){ ?>
              <div class="input-group mb-3">
                  <div class="input-group-prepend">
                      <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Username</span>
                  </div>
                  <input type="text" class="form-control" style="opacity:80%; border:solid 1px red; color:red;" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="username" value="<?php echo $_POST['username'] ?>">
              </div>
            <?php }else{ ?>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Username</span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="username" value="<?php echo $_POST['username'] ?>">
              </div>
            <?php } ?>
            <?php if($passwordError=="yes"){ ?>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Password</span>
                </div>
                <input type="password" class="form-control" style="opacity:80%; border:solid 1px red; color:red;" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="password" value="<?php echo $_POST['password'] ?>">
              </div>
              <div class="input-group mb-3">
                  <div class="input-group-prepend">
                      <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Conferma password</span>
                  </div>
                  <input type="password" class="form-control" style="opacity:80%; border:solid 1px red; color:red;" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cpassword" value="<?php echo $_POST['cpassword'] ?>">
              </div>
            <?php }else{ ?>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Password</span>
                </div>
                <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="password" value="<?php echo $_POST['password'] ?>">
              </div>
              <div class="input-group mb-3">
                  <div class="input-group-prepend">
                      <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Conferma password</span>
                  </div>
                  <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cpassword" value="<?php echo $_POST['cpassword'] ?>">
              </div>
            <?php } ?>
            <?php if($nameError=="yes"){ ?>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Nome</span>
                </div>
                <input type="text" class="form-control" style="opacity:80%; border:solid 1px red; color:red;" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="name" value="<?php echo $_POST['name'] ?>">
              </div>
            <?php }else{ ?>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Nome</span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="name" value="<?php echo $_POST['name'] ?>">
              </div>
            <?php } ?>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Cognome</span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="surname" value="<?php echo $_POST['surname'] ?>">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Indirizzo</span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="address" value="<?php echo $_POST['address'] ?>">
            </div>
            <?php if($emailError=="yes"){ ?>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Email</span>
                </div>
                <input type="email" class="form-control" style="opacity:80%; border:solid 1px red; color:red;" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="email" value="<?php echo $_POST['email'] ?>">
              </div>
            <?php }else{ ?>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Email</span>
                </div>
                <input type="email" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="email" value="<?php echo $_POST['email'] ?>">
              </div>
            <?php } ?>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Email di recupero</span>
                </div>
                <input type="email" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="recoveryEmail" value="<?php echo $_POST['recoveryEmail'] ?>">
            </div>

            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                <label class="form-check-label" for="invalidCheck2">
                  Agree to <a href="terms_and_conditions.html">terms and conditions</a>
                </label>
              </div>
            </div>

            <button type="submit" style="float: right;" class="btn btn-dark" name="register">Iscriviti</button>
            <a style="float: left;" href="login.php">Effettua il Login</a>
            <br />
        </form>
      </div>
    </div>
      
  </body>
</html>