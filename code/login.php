<?php 
  require_once("connect.php");

  if(isset($_POST['login'])){
    if(isset($_POST['username']) && !empty($_POST['username'])){
      $username=$_POST['username'];
      $check_username=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT username FROM utente WHERE username='".$username."'"));
      $check_username=$check_username['username'];

      if($check_username==$username){
        $userData=mysqli_query($mysqliConnection,"SELECT * FROM utente WHERE username='".$_POST['username']."' AND password='".$_POST['password']."'");
        $userData=mysqli_fetch_array($userData);
        $usernameError="no";
      }else if($check_username!=$username){
        if(isset($msg['error'])){
          $msg['error'].="L'username e' inesistente<br />";
        }
        else{
          $msg['error']="L'username e' inesistente<br />";
        }
        $usernameError="yes";
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
      if($userData['username']==$_POST['username'] && $userData['password']==$_POST['password']){
        $_SESSION=$userData;
        header("Location: index.php?login=success");
      }
      else{
        if(isset($msg['error'])){
          $msg['error'].="La password è errata<br />";
        }
        else{
          $msg['error']="La password è errata<br />";
        }
        $passwordError="yes";
      }
    }else{
      if(isset($msg['error'])){
        $msg['error'].="La password e' obbligatoria<br />";
      }
      else{
        $msg['error']="La password e' obbligatoria<br />";
      }
      $passwordError="yes";
    }
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>LocalCommunity - Log In</title>
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
    
    <div class="card text-center" style="opacity:80%; border:solid 2px black; width: 30rem; position: relative; left: 32.4%; top: 8.7rem;" >
      <div class="card-body">
        <h5 class="card-header" style="background-color: white; color: black">
          Effettua il Login
        </h5>

        <form method="post" action="login.php"> 
          <?php if($usernameError=="yes"){ ?>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Username</span>
              </div>
              <input type="text" class="form-control" style="opacity:80%; border:solid 1px red; color:red;" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="username" value="<?php echo $_POST['username'] ?>">
            </div>
          <?php }else{ ?>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Username</span>
              </div>
              <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="username" value="<?php echo $_POST['username'] ?>">
            </div>
          <?php } ?>
          <?php if($passwordError=="yes"){ ?>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Password</span>
              </div>
              <input type="password" class="form-control" style="opacity:80%; border:solid 1px red; color:red;" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="password" value="<?php echo $_POST['password'] ?>">
            </div>
          <?php }else{ ?>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Password</span>
              </div>
              <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="password" value="<?php echo $_POST['password'] ?>">
            </div>
          <?php } ?>
          
          <a style="float: left;" href="signup.php">Non ti sei ancora iscritto? Registrati</a>
          <br />
          <input type="submit" style="float: right;" class="btn btn-dark" name="login" value="Login">
        </form>
      </div>
    </div>

  </body>
</html>