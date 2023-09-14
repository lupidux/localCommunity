<?php
  require_once("connect.php");

  $numUtenti=mysqli_query($mysqliConnection,"SELECT max(id) as numUtenti FROM utente");
  $numUtenti=mysqli_fetch_assoc($numUtenti);
  $numUtenti=$numUtenti['numUtenti'];
  for ($i=1; $i<=$numUtenti ; $i++){
    $utenti[$i]=mysqli_query($mysqliConnection,"SELECT * FROM utente WHERE id=".$i);
    $utenti[$i]=mysqli_fetch_array($utenti[$i]);
  }

  if($_SESSION['ruolo'] == 'utente interno'){
    if(isset($_POST['confirm'])){
      if(isset($_POST['username']) && !empty($_POST['username'])){
        if($_POST['username'] != $_SESSION['username']){
          $username=$_POST['username'];
          $check_username=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT username FROM utente WHERE username='".$username."'"));
          $check_username=$check_username['username'];

          if($check_username==$username){
            if(isset($msg['error'])){
              $msg['error'].="L'username non e' disponibile<br />";
            }
            else{
              $msg['error']="L'username non e' disponibile<br />";
            }
          }else if($check_username!=$username){
            $result=mysqli_query($mysqliConnection,"UPDATE utente SET username='".$_POST['username']."' WHERE id=".$_SESSION['id']);
            if ($result){
              $_SESSION['username']=$_POST['username'];
              $modificato1="yes";
            }else{
              $query_error1="yes";
            }
          }
        }else{
          $modificato1="no";
        }
      }

      if(isset($_POST['password']) && !empty($_POST['password'])){
        if(isset($_POST['cpassword']) && !empty($_POST['cpassword'])){
          if($_POST['password']==$_POST['cpassword']){
            if($_POST['password'] != $_SESSION['password']){
              $result=mysqli_query($mysqliConnection,"UPDATE utente SET password='".$_POST['password']."' WHERE id=".$_SESSION['id']);
              if ($result){
                $_SESSION['password']=$_POST['password'];
                $modificato2="yes";
              }else{
                $query_error2="yes";
              }
            }else{
              $modificato2="no";
            }
          }
          else{
            if(isset($msg['error'])){
              $msg['error'].="Le password non corrispondono<br />";
            }
            else{
              $msg['error']="Le password non corrispondono<br />";
            }
          }
        }
        else{
          if(isset($msg['error'])){
            $msg['error'].="La conferma password e' obbligatoria<br />";
          }
          else{
            $msg['error']="La conferma password e' obbligatoria<br />";
          }
        }
      }

      if(isset($_POST['nome']) && !empty($_POST['nome'])){
        if($_POST['nome'] != $_SESSION['nome']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET nome='".$_POST['nome']."' WHERE id=".$_SESSION['id']);
          if ($result){
            $_SESSION['nome']=$_POST['nome'];
            $modificato3="yes";
          }else{
            $query_error3="yes";
          }
        }else{
          $modificato3="no";
        }
      }

      if($_POST['cognome'] != $_SESSION['cognome']){
        $result=mysqli_query($mysqliConnection,"UPDATE utente SET cognome='".$_POST['cognome']."' WHERE id=".$_SESSION['id']);
        if ($result){
          $_SESSION['cognome']=$_POST['cognome'];
          $modificato4="yes";
        }else{
          $query_error4="yes";
        }
      }else{
        $modificato4="no";
      }

      if($_POST['indirizzo'] != $_SESSION['indirizzo']){
        $result=mysqli_query($mysqliConnection,"UPDATE utente SET indirizzo='".$_POST['indirizzo']."' WHERE id=".$_SESSION['id']);
        if ($result){
          $_SESSION['indirizzo']=$_POST['indirizzo'];
          $modificato5="yes";
        }else{
          $query_error5="yes";
        }
      }else{
        $modificato5="no";
      }
      
      if(isset($_POST['email']) && !empty($_POST['email'])){
        if($_POST['email'] != $_SESSION['email']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET email='".$_POST['email']."' WHERE id=".$_SESSION['id']);
          if ($result){
            $_SESSION['email']=$_POST['email'];
            $modificato6="yes";
          }else{
            $query_error6="yes";
          }
        }else{
          $modificato6="no";
        }
      }

      if($_POST['emailDiRecupero'] != $_SESSION['emailDiRecupero']){
        $result=mysqli_query($mysqliConnection,"UPDATE utente SET emailDiRecupero='".$_POST['emailDiRecupero']."' WHERE id=".$_SESSION['id']);
        if ($result){
          $_SESSION['emailDiRecupero']=$_POST['emailDiRecupero'];
          $modificato7="yes";
        }else{
          $query_error7="yes";
        }
      }else{
        $modificato7="no";
      }
      
      if($modificato1=="no" && $modificato2=="no" && $modificato3=="no" && $modificato4=="no" && $modificato5=="no" && $modificato6=="no" && $modificato7=="no"){
        if(isset($msg['error'])){
          $msg['error'].="Nessuna modifica effettuata<br />";
        }
        else{
          $msg['error']="Nessuna modifica effettuata<br />";
        }
      }else{
        if(!isset($msg['error'])){
          if(isset($msg['success'])){
            $msg['success'].="Salvataggio effettuato con successo<br />";
          }
          else{
            $msg['success']="Salvataggio effettuato con successo<br />";
          }
        } else if (isset($msg['error']) || ($query_error1=="yes" || $query_error2=="yes" || $query_error3=="yes" || $query_error4=="yes" || $query_error5=="yes" || $query_error6=="yes" || $query_error7=="yes")){
          if(isset($msg['error'])){
            $msg['error'].="Salvataggio dati fallito<br />";
          }
          else{
            $msg['error']="Salvataggio dati fallito<br />";
          }
        }
      }
    }
    if(isset($_POST['delete'])){
      if(isset($_POST['passwordDelete']) && !empty($_POST['passwordDelete'])){
        if(isset($_POST['cpasswordDelete']) && !empty($_POST['cpasswordDelete'])){
          if($_POST['passwordDelete']==$_POST['cpasswordDelete']){
            if ($_POST['passwordDelete'] == $_SESSION['password']){
              $result=mysqli_query($mysqliConnection,"DELETE FROM utente WHERE id=".$_SESSION['id']);
              if ($result){
              session_destroy();
              header("Location: index.php");
              }else{
                if(isset($msg['error'])){
                  $msg['error'].="Operazione fallita<br />";
                }
                else{
                  $msg['error']="Operazione fallita<br />";
                }
              }
            }else{
              if(isset($msg['error'])){
                $msg['error'].="La password non è corretta<br />";
              }
              else{
                $msg['error']="La password non è corretta<br />";
              }
            }
          }
          else{
            if(isset($msg['error'])){
              $msg['error'].="Le password non corrispondono<br />";
            }
            else{
              $msg['error']="Le password non corrispondono<br />";
            }
          }
        }
        else{
          if(isset($msg['error'])){
            $msg['error'].="La conferma password e' obbligatoria<br />";
          }
          else{
            $msg['error']="La conferma password e' obbligatoria<br />";
          }
        }
      }else{
        if(isset($msg['error'])){
          $msg['error'].="La password e' obbligatoria<br />";
        }
        else{
          $msg['error']="La password e' obbligatoria<br />";
        }
      }
    }
  }else if($_SESSION['ruolo'] == 'moderatore'){

    $k=$_GET['index'];
    if($_GET['act']=='next' && $k<$numUtenti){
      $k++;
    }else if($_GET['act']=='previous' && $k>1){
      $k--;
    }

    if($_SESSION['id']==$utenti[$k]['id']){
      if(isset($_POST['confirm'])){
        if(isset($_POST['username']) && !empty($_POST['username'])){
          if($_POST['username'] != $_SESSION['username']){
            $username=$_POST['username'];
            $check_username=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT username FROM utente WHERE username='".$username."'"));
            $check_username=$check_username['username'];

            if($check_username==$username){
              if(isset($msg['error'])){
                $msg['error'].="L'username non e' disponibile<br />";
              }
              else{
                $msg['error']="L'username non e' disponibile<br />";
              }
            }else if($check_username!=$username){
              $result=mysqli_query($mysqliConnection,"UPDATE utente SET username='".$_POST['username']."' WHERE id=".$_SESSION['id']);
              if ($result){
                $_SESSION['username']=$_POST['username'];
                $modificato1="yes";
              }else{
                $query_error1="yes";
              }
            }
          }else{
            $modificato1="no";
          }
        }

        if(isset($_POST['password']) && !empty($_POST['password'])){
          if(isset($_POST['cpassword']) && !empty($_POST['cpassword'])){
            if($_POST['password']==$_POST['cpassword']){
              if($_POST['password'] != $_SESSION['password']){
                $result=mysqli_query($mysqliConnection,"UPDATE utente SET password='".$_POST['password']."' WHERE id=".$_SESSION['id']);
                if ($result){
                  $_SESSION['password']=$_POST['password'];
                  $modificato2="yes";
                }else{
                  $query_error2="yes";
                }
              }else{
                $modificato2="no";
              }
            }
            else{
              if(isset($msg['error'])){
                $msg['error'].="Le password non corrispondono<br />";
              }
              else{
                $msg['error']="Le password non corrispondono<br />";
              }
            }
          }
          else{
            if(isset($msg['error'])){
              $msg['error'].="La conferma password e' obbligatoria<br />";
            }
            else{
              $msg['error']="La conferma password e' obbligatoria<br />";
            }
          }
        }

        if(isset($_POST['nome']) && !empty($_POST['nome'])){
          if($_POST['nome'] != $_SESSION['nome']){
            $result=mysqli_query($mysqliConnection,"UPDATE utente SET nome='".$_POST['nome']."' WHERE id=".$_SESSION['id']);
            if ($result){
              $_SESSION['nome']=$_POST['nome'];
              $modificato3="yes";
            }else{
              $query_error3="yes";
            }
          }else{
            $modificato3="no";
          }
        }

        if($_POST['cognome'] != $_SESSION['cognome']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET cognome='".$_POST['cognome']."' WHERE id=".$_SESSION['id']);
          if ($result){
            $_SESSION['cognome']=$_POST['cognome'];
            $modificato4="yes";
          }else{
            $query_error4="yes";
          }
        }else{
          $modificato4="no";
        }

        if($_POST['indirizzo'] != $_SESSION['indirizzo']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET indirizzo='".$_POST['indirizzo']."' WHERE id=".$_SESSION['id']);
          if ($result){
            $_SESSION['indirizzo']=$_POST['indirizzo'];
            $modificato5="yes";
          }else{
            $query_error5="yes";
          }
        }else{
          $modificato5="no";
        }
        
        if(isset($_POST['email']) && !empty($_POST['email'])){
          if($_POST['email'] != $_SESSION['email']){
            $result=mysqli_query($mysqliConnection,"UPDATE utente SET email='".$_POST['email']."' WHERE id=".$_SESSION['id']);
            if ($result){
              $_SESSION['email']=$_POST['email'];
              $modificato6="yes";
            }else{
              $query_error6="yes";
            }
          }else{
            $modificato6="no";
          }
        }

        if($_POST['emailDiRecupero'] != $_SESSION['emailDiRecupero']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET emailDiRecupero='".$_POST['emailDiRecupero']."' WHERE id=".$_SESSION['id']);
          if ($result){
            $_SESSION['emailDiRecupero']=$_POST['emailDiRecupero'];
            $modificato7="yes";
          }else{
            $query_error7="yes";
          }
        }else{
          $modificato7="no";
        }
        
        if($modificato1=="no" && $modificato2=="no" && $modificato3=="no" && $modificato4=="no" && $modificato5=="no" && $modificato6=="no" && $modificato7=="no"){
          if(isset($msg['error'])){
            $msg['success'].="Nessuna modifica effettuata<br />";
          }
          else{
            $msg['success']="Nessuna modifica effettuata<br />";
          }
        }else{
          if(!isset($msg['error'])){
            if(isset($msg['error'])){
              $msg['success'].="Salvataggio effettuato con successo<br />";
            }
            else{
              $msg['success']="Salvataggio effettuato con successo<br />";
            }
          } else if (isset($msg['error']) || ($query_error1=="yes" || $query_error2=="yes" || $query_error3=="yes" || $query_error4=="yes" || $query_error5=="yes" || $query_error6=="yes" || $query_error7=="yes")){
            if(isset($msg['error'])){
              $msg['success'].="Salvataggio dati fallito<br />";
            }
            else{
              $msg['success']="Salvataggio dati fallito<br />";
            }
          }
        }
      }
    }else{
      if(isset($_POST['confirm'])){
        if(isset($_POST['stato']) && !empty($_POST['stato'])){
          if($_POST['stato'] != $utenti[$k]['stato']){
            $result=mysqli_query($mysqliConnection,"UPDATE utente SET stato='".$_POST['stato']."' WHERE id=".$utenti[$k]['id']);
            if ($result){
              $utenti[$k]['stato']=$_POST['stato'];
              $modificato="yes";
            }else{
              $query_error="yes";
            }
          }else{
            $modificato="no";
          }
        }
        
        if($modificato=="no"){
          if(isset($msg['error'])){
            $msg['error'].="Nessuna modifica effettuata<br />";
          }
          else{
            $msg['error']="Nessuna modifica effettuata<br />";
          }
        }else{
          if(!isset($msg['error'])){
            if(isset($msg['success'])){
              $msg['success'].="Salvataggio effettuato con successo<br />";
            }
            else{
              $msg['success']="Salvataggio effettuato con successo<br />";
            }
          } else if (isset($msg['error']) || ($query_error=="yes")){
            if(isset($msg['error'])){
              $msg['error'].="Salvataggio dati fallito<br />";
            }
            else{
              $msg['error']="Salvataggio dati fallito<br />";
            }
          }
        }
      }
    }
    
    if(isset($_POST['delete'])){
      if(isset($_POST['passwordDelete']) && !empty($_POST['passwordDelete'])){
        if(isset($_POST['cpasswordDelete']) && !empty($_POST['cpasswordDelete'])){
          if($_POST['passwordDelete']==$_POST['cpasswordDelete']){
            if ($_POST['passwordDelete'] == $_SESSION['password']){
              $result=mysqli_query($mysqliConnection,"DELETE FROM utente WHERE id=".$_SESSION['id']);
              if ($result){
              session_destroy();
              header("Location: index.php");
              }else{
                if(isset($msg['error'])){
                  $msg['error'].="Operazione fallita<br />";
                }
                else{
                  $msg['error']="Operazione fallita<br />";
                }
              }
            }else{
              if(isset($msg['error'])){
                $msg['error'].="La password non è corretta<br />";
              }
              else{
                $msg['error']="La password non è corretta<br />";
              }
            }
          }
          else{
            if(isset($msg['error'])){
              $msg['error'].="Le password non corrispondono<br />";
            }
            else{
              $msg['error']="Le password non corrispondono<br />";
            }
          }
        }
        else{
          if(isset($msg['error'])){
            $msg['error'].="La conferma password e' obbligatoria<br />";
          }
          else{
            $msg['error']="La conferma password e' obbligatoria<br />";
          }
        }
      }else{
        if(isset($msg['error'])){
          $msg['error'].="La password e' obbligatoria<br />";
        }
        else{
          $msg['error']="La password e' obbligatoria<br />";
        }
      }
    }
  }else if($_SESSION['ruolo'] == 'admin'){

    $k=$_GET['index'];
    if($_GET['act']=='next' && $k<$numUtenti){
      $k++;
    }else if($_GET['act']=='previous' && $k>1){
      $k--;
    }

    if(isset($_POST['confirm'])){
      if(isset($_POST['id']) && !empty($_POST['id'])){
        if($_POST['id'] != $utenti[$k]['id']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET id='".$_POST['id']."' WHERE id=".$utenti[$k]['id']);
          if ($result){
            $utenti[$k]['id']=$_POST['id'];
            $modificato1="yes";
          }else{
            $query_error1="yes";
          }
        }else{
          $modificato1="no";
        }
      }

      if(isset($_POST['username']) && !empty($_POST['username'])){
        if($_POST['username'] != $utenti[$k]['username']){
          $username=$_POST['username'];
          $check_username=mysqli_fetch_assoc(mysqli_query($mysqliConnection,"SELECT username FROM utente WHERE username='".$username."'"));
          $check_username=$check_username['username'];

          if($check_username==$username){
            if(isset($msg['error'])){
              $msg['error'].="L'username non e' disponibile<br />";
            }
            else{
              $msg['error']="L'username non e' disponibile<br />";
            }
          }else if($check_username!=$username){
            $result=mysqli_query($mysqliConnection,"UPDATE utente SET username='".$_POST['username']."' WHERE id=".$utenti[$k]['id']);
            if ($result){
              $utenti[$k]['username']=$_POST['username']; 
              $modificato2="yes";
            }else{
              $query_error2="yes";
            }
          }
        }else{
          $modificato2="no";
        }
      }

      if(isset($_POST['password']) && !empty($_POST['password'])){
        if(isset($_POST['cpassword']) && !empty($_POST['cpassword'])){
          if($_POST['password']==$_POST['cpassword']){
            if($_POST['password'] != $utenti[$k]['password']){
              $result=mysqli_query($mysqliConnection,"UPDATE utente SET password='".$_POST['password']."' WHERE id=".$utenti[$k]['id']);
              if ($result){
                $utenti[$k]['password']=$_POST['password'];
                $modificato3="yes";
              }else{
                $query_error3="yes";
              }
            }else{
              $modificato3="no";
            }
          }
          else{
            if(isset($msg['error'])){
              $msg['error'].="Le password non corrispondono<br />";
            }
            else{
              $msg['error']="Le password non corrispondono<br />";
            }
          }
        }
        else{
          if(isset($msg['error'])){
            $msg['error'].="La conferma password e' obbligatoria<br />";
          }
          else{
            $msg['error']="La conferma password e' obbligatoria<br />";
          }
        }
      }

      if(isset($_POST['nome']) && !empty($_POST['nome'])){
        if($_POST['nome'] != $utenti[$k]['nome']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET nome='".$_POST['nome']."' WHERE id=".$utenti[$k]['id']);
          if ($result){
            $utenti[$k]['nome']=$_POST['nome'];
            $modificato4="yes";
          }else{
            $query_error4="yes";
          }
        }else{
          $modificato4="no";
        }
      }

      if($_POST['cognome'] != $utenti[$k]['cognome']){
        $result=mysqli_query($mysqliConnection,"UPDATE utente SET cognome='".$_POST['cognome']."' WHERE id=".$utenti[$k]['id']);
        if ($result){
          $utenti[$k]['cognome']=$_POST['cognome'];
          $modificato5="yes";
        }else{
          $query_error5="yes";
        }
      }else{
        $modificato5="no";
      }

      if($_POST['indirizzo'] != $utenti[$k]['indirizzo']){
        $result=mysqli_query($mysqliConnection,"UPDATE utente SET indirizzo='".$_POST['indirizzo']."' WHERE id=".$utenti[$k]['id']);
        if ($result){
          $utenti[$k]['indirizzo']=$_POST['indirizzo'];
          $modificato6="yes";
        }else{
          $query_error6="yes";
        }
      }else{
        $modificato6="no";
      }
      
      if(isset($_POST['email']) && !empty($_POST['email'])){
        if($_POST['email'] != $utenti[$k]['email']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET email='".$_POST['email']."' WHERE id=".$utenti[$k]['id']);
          if ($result){
            $utenti[$k]['email']=$_POST['email'];
            $modificato7="yes";
          }else{
            $query_error7="yes";
          }
        }else{
          $modificato7="no";
        }
      }

      if($_POST['emailDiRecupero'] != $utenti[$k]['emailDiRecupero']){
        $result=mysqli_query($mysqliConnection,"UPDATE utente SET emailDiRecupero='".$_POST['emailDiRecupero']."' WHERE id=".$utenti[$k]['id']);
        if ($result){
          $utenti[$k]['emailDiRecupero']=$_POST['emailDiRecupero'];
          $modificato8="yes";
        }else{
          $query_error8="yes";
        }
      }else{
        $modificato8="no";
      }

      if(isset($_POST['reputazione']) && !empty($_POST['reputazione'])){
        if($_POST['reputazione'] != $utenti[$k]['reputazione']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET reputazione='".$_POST['reputazione']."' WHERE id=".$utenti[$k]['id']);
          if ($result){
            $utenti[$k]['reputazione']=$_POST['reputazione'];
            $modificato9="yes";
          }else{
            $query_error9="yes";
          }
        }else{
          $modificato9="no";
        }
      }

      if(isset($_POST['livelloAutorizzazione']) && !empty($_POST['livelloAutorizzazione'])){
        if($_POST['livelloAutorizzazione'] != $utenti[$k]['livelloAutorizzazione']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET livelloAutorizzazione='".$_POST['livelloAutorizzazione']."' WHERE id=".$utenti[$k]['id']);
          if ($result){
            $utenti[$k]['livelloAutorizzazione']=$_POST['livelloAutorizzazione'];
            $modificato10="yes";
          }else{
            $query_error10="yes";
          }
        }else{
          $modificato10="no";
        }
      }

      if(isset($_POST['ruolo']) && !empty($_POST['ruolo'])){
        if($_POST['ruolo'] != $utenti[$k]['ruolo']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET ruolo='".$_POST['ruolo']."' WHERE id=".$utenti[$k]['id']);
          if ($result){
            $utenti[$k]['ruolo']=$_POST['ruolo'];
            $modificato11="yes";
          }else{
            $query_error11="yes";
          }
        }else{
          $modificato11="no";
        }
      }

      if(isset($_POST['stato']) && !empty($_POST['stato'])){
        if($_POST['stato'] != $utenti[$k]['stato']){
          $result=mysqli_query($mysqliConnection,"UPDATE utente SET stato='".$_POST['stato']."' WHERE id=".$utenti[$k]['id']);
          if ($result){
            $utenti[$k]['stato']=$_POST['stato'];
            $modificato12="yes";
          }else{
            $query_error12="yes";
          }
        }else{
          $modificato12="no";
        }
      }
      
      if($modificato1=="no" && $modificato2=="no" && $modificato3=="no" && $modificato4=="no" && $modificato5=="no" && $modificato6=="no" && $modificato7=="no" && $modificato8=="no" && $modificato9=="no" && $modificato10=="no" && $modificato11=="no" && $modificato12=="no"){
        if(isset($msg['error'])){
          $msg['error'].="Nessuna modifica effettuata<br />";
        }
        else{
          $msg['error']="Nessuna modifica effettuata<br />";
        }
      }else{
        if(!isset($msg['error'])){
          if(isset($msg['success'])){
            $msg['success'].="Salvataggio effettuato con successo<br />";
          }
          else{
            $msg['success']="Salvataggio effettuato con successo<br />";
          }
        } else if (isset($msg['error']) || ($query_error1=="yes" || $query_error2=="yes" || $query_error3=="yes" || $query_error4=="yes" || $query_error5=="yes" || $query_error6=="yes" || $query_error7=="yes" || $query_error8=="yes" || $query_error9=="yes" || $query_error10=="yes" || $query_error11=="yes" || $query_error12=="yes")){
          if(isset($msg['error'])){
            $msg['error'].="Salvataggio dati fallito<br />";
          }
          else{
            $msg['error']="Salvataggio dati fallito<br />";
          }
        }
      }
    }
    if(isset($_POST['delete'])){
      if(isset($_POST['passwordDelete']) && !empty($_POST['passwordDelete'])){
        if(isset($_POST['cpasswordDelete']) && !empty($_POST['cpasswordDelete'])){
          if($_POST['passwordDelete']==$_POST['cpasswordDelete']){
            if ($_POST['passwordDelete'] == $_SESSION['password']){
              $result=mysqli_query($mysqliConnection,"DELETE FROM utente WHERE id=".$utenti[$k]['id']);
              if ($result){
                if ($_SESSION['id']==$utenti[$k]['id']){
                  session_destroy();
                  header("Location: index.php");
                }else{
                  if(isset($msg['error'])){
                    $msg['success'].="Salvataggio effettuato con successo<br />";
                  }
                  else{
                    $msg['success']="Salvataggio effettuato con successo<br />";
                  }
                }
              }else{
                if(isset($msg['error'])){
                  $msg['error'].="Operazione fallita<br />";
                }
                else{
                  $msg['error']="Operazione fallita<br />";
                }
              }
            }else{
              if(isset($msg['error'])){
                $msg['error'].="La password non è corretta<br />";
              }
              else{
                $msg['error']="La password non è corretta<br />";
              }
            }
          }
          else{
            if(isset($msg['error'])){
              $msg['error'].="Le password non corrispondono<br />";
            }
            else{
              $msg['error']="Le password non corrispondono<br />";
            }
          }
        }
        else{
          if(isset($msg['error'])){
            $msg['error'].="La conferma password e' obbligatoria<br />";
          }
          else{
            $msg['error']="La conferma password e' obbligatoria<br />";
          }
        }
      }else{
        if(isset($msg['error'])){
          $msg['error'].="La password e' obbligatoria<br />";
        }
        else{
          $msg['error']="La password e' obbligatoria<br />";
        }
      }
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
        <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'attivo' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
            <title>LocalCommunity - Profili</title>
            <link rel="icon" type="image/x-icon" href="img/localcom_logo.png">
        <?php } ?>
    </head>
    
    <?php if(isset($_SESSION) && $_SESSION['stato'] == 'sospeso' && ($_SESSION['ruolo'] == 'utente interno' || $_SESSION['ruolo'] == 'moderatore' || $_SESSION['ruolo'] == 'admin')){ ?>
        <body>
            <h1>access denied</h1>
        </body>
    <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'attivo' && $_SESSION['ruolo'] == 'utente interno'){?>
      <body style="background-repeat: no-repeat; background-size: cover;" background="img/project.jpg">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="row" style="width: 100%;">
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

        <div class="row" style="width: 100%;">
          <div class="col-sm-7">
            <div align="center">
              <div class="card text-center" style="opacity:85%; border:solid 2px black; width: 50rem; height: 29rem; position: relative; left: 5%; top: 3rem;" >
                  <div class="card-body">
                      <h5 class="card-header" style="background-color: white; color: black">
                          Modifica il tuo profilo
                      </h5>

                      <form method="post" action="profile.php">
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 3rem;" class="input-group-text" id="inputGroup-sizing-default">ID</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="id" value="<?php echo $_SESSION['id'] ?>" disabled>
                            </div>
                          </div>
                          <div class="col-sm-9">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Username</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="username" value="<?php echo $_SESSION['username'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Password</span>
                                </div>
                                <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="password" value="<?php echo $_SESSION['password'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Conferma password</span>
                                </div>
                                <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cpassword" value="<?php echo $_SESSION['password'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 4rem;" class="input-group-text" id="inputGroup-sizing-default">Nome</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="nome" value="<?php echo $_SESSION['nome'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Cognome</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cognome" value="<?php echo $_SESSION['cognome'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Indirizzo</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="indirizzo" value="<?php echo $_SESSION['indirizzo'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Email 1</span>
                                </div>
                                <input type="email" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="email" value="<?php echo $_SESSION['email'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Email 2</span>
                                </div>
                                <input type="email" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="emailDiRecupero" value="<?php echo $_SESSION['emailDiRecupero'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 9rem;" class="input-group-text" id="inputGroup-sizing-default">Reputazione</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="reputazione" value="<?php echo $_SESSION['reputazione'] ?>" disabled>
                            </div>
                          </div>
                          <div class="col-sm-8">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Livello di autorizzazione</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="livelloAutorizzazione" value="<?php echo $_SESSION['livelloAutorizzazione'] ?>" disabled>
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Ruolo</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="ruolo" value="<?php echo $_SESSION['ruolo'] ?>" disabled>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Stato</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="stato" value="<?php echo $_SESSION['stato'] ?>" disabled>
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 127.5%;">
                          <div class="col-sm-6">
                            <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                <label class="form-check-label" for="invalidCheck2">
                                  Sono sicuro di voler operare tali modifiche
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <input type="submit" style="float: center;" class="btn btn-dark" name="confirm" value="Conferma">
                          </div>
                        </div>
                      </form>
                  </div>
              </div>
            </div>
          </div>
          <div class="col-sm-5">
            <div align="center">
              <div class="card text-center" style="opacity:85%; border:solid 2px black; width: 28rem; height: 29rem; position: relative; left: 5%; top: 3rem;" >
                <div class="card-body">
                    <h5 class="card-header" style="background-color: white; color: black">
                        Elimina il tuo profilo
                    </h5>

                    <br />
                    <form method="post" action="profile.php">                   
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Password</span>
                          </div>
                          <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="passwordDelete">
                      </div>
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Conferma password</span>
                          </div>
                          <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cpasswordDelete">
                      </div>
                      <br />
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                          <label class="form-check-label" for="invalidCheck2">
                            Confermo di voler cancellare definitivamente il mio account e tutti i dati collegati ad esso
                          </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                          <label class="form-check-label" for="invalidCheck2">
                            Sono consapevole che questa operazione cancellerà il mio profilo e tutti i dati sensibili associati ad esso
                          </label>
                        </div>
                      </div>
                      <br />
                      <input type="submit" style="float: center;" class="btn btn-dark" name="delete" value="Elimina">
                    </form>
                </div>
              </div>
              <br />
            </div>
          </div>
        </div>
      </body>
    <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'attivo' && $_SESSION['ruolo'] == 'moderatore'){?>
      <body style="background-repeat: no-repeat; background-size: cover;" background="img/project.jpg">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="row" style="width: 100%;">
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

        <div class="row" style="width: 100%;">
          <div class="col-sm-7">
            <div align="center">
              <div class="card text-center" style="opacity:85%; border:solid 2px black; width: 50rem; height: 32rem; position: relative; left: 5%; top: 1.5rem;" >
                  <div class="card-body">
                      <h5 class="card-header" style="background-color: white; color: black">
                          Modifica i profili
                      </h5>

                      <form method="post" action="profile.php?index=<?php echo $utenti[$k]['id'] ?>">
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 3rem;" class="input-group-text" id="inputGroup-sizing-default">ID</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="id" value="<?php echo $utenti[$k]['id'] ?>" disabled>
                            </div>
                          </div>
                      <?php if($_SESSION['id']==$utenti[$k]['id']){ ?>
                          <div class="col-sm-9">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Username</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="username" value="<?php echo $utenti[$k]['username'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Password</span>
                                </div>
                                <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="password" value="<?php echo $utenti[$k]['password'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Conferma password</span>
                                </div>
                                <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cpassword" value="<?php echo $utenti[$k]['password'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 4rem;" class="input-group-text" id="inputGroup-sizing-default">Nome</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="nome" value="<?php echo $utenti[$k]['nome'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Cognome</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cognome" value="<?php echo $utenti[$k]['cognome'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Indirizzo</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="indirizzo" value="<?php echo $utenti[$k]['indirizzo'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Email 1</span>
                                </div>
                                <input type="email" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="email" value="<?php echo $utenti[$k]['email'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Email 2</span>
                                </div>
                                <input type="email" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="emailDiRecupero" value="<?php echo $utenti[$k]['emailDiRecupero'] ?>">
                            </div>
                          </div>
                        </div>
                      <?php }else{ ?>
                          <div class="col-sm-9">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Username</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="username" value="<?php echo $utenti[$k]['username'] ?>" disabled>
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Password</span>
                                </div>
                                <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="password" value="<?php echo $utenti[$k]['password'] ?>" disabled>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Conferma password</span>
                                </div>
                                <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cpassword" value="<?php echo $utenti[$k]['password'] ?>" disabled>
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 4rem;" class="input-group-text" id="inputGroup-sizing-default">Nome</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="nome" value="<?php echo $utenti[$k]['nome'] ?>" disabled>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Cognome</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cognome" value="<?php echo $utenti[$k]['cognome'] ?>" disabled>
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Indirizzo</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="indirizzo" value="<?php echo $utenti[$k]['indirizzo'] ?>" disabled>
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Email 1</span>
                                </div>
                                <input type="email" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="email" value="<?php echo $utenti[$k]['email'] ?>" disabled>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Email 2</span>
                                </div>
                                <input type="email" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="emailDiRecupero" value="<?php echo $utenti[$k]['emailDiRecupero'] ?>" disabled>
                            </div>
                          </div>
                        </div>
                      <?php } ?>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 9rem;" class="input-group-text" id="inputGroup-sizing-default">Reputazione</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="reputazione" value="<?php echo $utenti[$k]['reputazione'] ?>" disabled>
                            </div>
                          </div>
                          <div class="col-sm-8">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Livello di autorizzazione</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="livelloAutorizzazione" value="<?php echo $utenti[$k]['livelloAutorizzazione'] ?>" disabled>
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Ruolo</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="ruolo" value="<?php echo $utenti[$k]['ruolo'] ?>" disabled>
                            </div>
                          </div>
                          <div class="col-sm-6">
                      <?php if($utenti[$k]['ruolo'] == 'admin'){ ?>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Stato</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="stato" value="<?php echo $utenti[$k]['stato'] ?>" disabled>
                            </div>
                          </div>
                        </div>
                      <?php }else{ ?>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default"><a href="" title="I valori riconosciuti dal sistema sono 'attivo' e 'sospeso'">Stato</a></span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="stato" value="<?php echo $utenti[$k]['stato'] ?>">
                            </div>
                          </div>
                        </div>
                      <?php } ?>
                        <div class="row" style="width: 127.5%;">
                          <div class="col-sm-6">
                            <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                <label class="form-check-label" for="invalidCheck2">
                                  Sono sicuro di voler operare tali modifiche
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <input type="submit" style="float: center;" class="btn btn-dark" name="confirm" value="Conferma">
                          </div>
                        </div>
                      </form>

                      <br />
                      <?php
                          echo '<a class="btn btn-dark mr" href="profile.php?index='. $k .'&act=previous" style="float: left;">Precedente</a>
                                <a class="btn btn-dark mr" href="profile.php?index='. $k .'&act=next" style="float: right;">Successivo</a>';
                      ?> 
                      
                  </div>
              </div>
            </div>
          </div>
          <div class="col-sm-5">
            <div align="center">
              <div class="card text-center" style="opacity:85%; border:solid 2px black; width: 28rem; height: 32rem; position: relative; left: 5%; top: 1.5rem;" >
                <div class="card-body">
                    <h5 class="card-header" style="background-color: white; color: black">
                        Elimina il tuo profilo
                    </h5>

                    <br />
                    <form method="post" action="profile.php?index=<?php echo $_SESSION['id'] ?>">                   
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Password</span>
                          </div>
                          <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="passwordDelete">
                      </div>
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Conferma password</span>
                          </div>
                          <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cpasswordDelete">
                      </div>
                      <br />
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                          <label class="form-check-label" for="invalidCheck2">
                            Confermo di voler cancellare definitivamente il mio account e tutti i dati collegati ad esso
                          </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                          <label class="form-check-label" for="invalidCheck2">
                            Sono consapevole che questa operazione cancellerà il mio profilo e tutti i dati sensibili associati ad esso
                          </label>
                        </div>
                      </div>
                      <br />
                      <input type="submit" style="float: center;" class="btn btn-dark" name="delete" value="Elimina">
                    </form>
                </div>
              </div>
              <br />
            </div>
          </div>
        </div>
      </body>
    <?php }else if(isset($_SESSION) && $_SESSION['stato'] == 'attivo' && $_SESSION['ruolo'] == 'admin'){?>
      <body style="background-repeat: no-repeat; background-size: cover;" background="img/project.jpg">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="row" style="width: 100%;">
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

        <div class="row" style="width: 100%;">
          <div class="col-sm-7">
            <div align="center">
              <div class="card text-center" style="opacity:85%; border:solid 2px black; width: 50rem; height: 32rem; position: relative; left: 5%; top: 1.5rem;" >
                  <div class="card-body">
                      <h5 class="card-header" style="background-color: white; color: black">
                          Modifica i profili
                      </h5>

                      <form method="post" action="profile.php?index=<?php echo $utenti[$k]['id'] ?>">
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 3rem;" class="input-group-text" id="inputGroup-sizing-default">ID</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="id" value="<?php echo $utenti[$k]['id'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-9">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Username</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="username" value="<?php echo $utenti[$k]['username'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Password</span>
                                </div>
                                <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="password" value="<?php echo $utenti[$k]['password'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default">Conferma password</span>
                                </div>
                                <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cpassword" value="<?php echo $utenti[$k]['password'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 4rem;" class="input-group-text" id="inputGroup-sizing-default">Nome</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="nome" value="<?php echo $utenti[$k]['nome'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 6rem;" class="input-group-text" id="inputGroup-sizing-default">Cognome</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cognome" value="<?php echo $utenti[$k]['cognome'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Indirizzo</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="indirizzo" value="<?php echo $utenti[$k]['indirizzo'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Email 1</span>
                                </div>
                                <input type="email" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="email" value="<?php echo $utenti[$k]['email'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 5rem;" class="input-group-text" id="inputGroup-sizing-default">Email 2</span>
                                </div>
                                <input type="email" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="emailDiRecupero" value="<?php echo $utenti[$k]['emailDiRecupero'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 104%;">
                          <div class="col-sm-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 9rem;" class="input-group-text" id="inputGroup-sizing-default"><a href="" title="È un valore incrementabile automaticamente e compreso tra 1 e 1000">Reputazione</a></span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="reputazione" value="<?php echo $utenti[$k]['reputazione'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-8">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default"><a href="" title="I valori riconosciuti dal sistema sono 'novizio', 'principiante', 'intermedio', 'specialista' e 'innovatore'">Livello di autorizzazione</a></span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="livelloAutorizzazione" value="<?php echo $utenti[$k]['livelloAutorizzazione'] ?>">
                            </div>
                          </div>
                        </div>

                        <div class="row" style="width: 104%;">
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default"><a href="" title="I valori riconosciuti dal sistema sono 'utente interno', 'moderatore' e 'admin'">Ruolo</a></span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="ruolo" value="<?php echo $utenti[$k]['ruolo'] ?>">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span style="width: 12.3rem;" class="input-group-text" id="inputGroup-sizing-default"><a href="" title="I valori riconosciuti dal sistema sono 'attivo' e 'sospeso'">Stato</a></span>
                                </div>
                                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="stato" value="<?php echo $utenti[$k]['stato'] ?>">
                            </div>
                          </div>
                        </div>
                        <div class="row" style="width: 127.5%;">
                          <div class="col-sm-6">
                            <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                <label class="form-check-label" for="invalidCheck2">
                                  Sono sicuro di voler operare tali modifiche
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <input type="submit" style="float: center;" class="btn btn-dark" name="confirm" value="Conferma">
                          </div>
                        </div>
                      </form>

                      <br />
                      <?php
                          echo '<a class="btn btn-dark mr" href="profile.php?index='. $k .'&act=previous" style="float: left;">Precedente</a>
                                <a class="btn btn-dark mr" href="profile.php?index='. $k .'&act=next" style="float: right;">Successivo</a>';
                      ?> 
                      
                  </div>
                </div>
            </div>
          </div>
          <div class="col-sm-5">
            <div align="center">
              <div class="card text-center" style="opacity:85%; border:solid 2px black; width: 28rem; height: 32rem; position: relative; left: 5%; top: 1.5rem;" >
                <div class="card-body">
                    <h5 class="card-header" style="background-color: white; color: black">
                        Elimina profili
                    </h5>

                    <br />
                    <form method="post" action="profile.php?index=<?php echo $utenti[$k]['id'] ?>">                   
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Password</span>
                          </div>
                          <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="passwordDelete">
                      </div>
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span style="width: 10.5rem;" class="input-group-text" id="inputGroup-sizing-default">Conferma password</span>
                          </div>
                          <input type="password" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="cpasswordDelete">
                      </div>
                      <br />
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                          <label class="form-check-label" for="invalidCheck2">
                            Confermo di voler cancellare definitivamente tale account e tutti i dati collegati ad esso
                          </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                          <label class="form-check-label" for="invalidCheck2">
                            Sono consapevole che questa operazione cancellerà questo profilo e tutti i dati sensibili associati ad esso
                          </label>
                        </div>
                      </div>
                      <br />
                      <input type="submit" style="float: center;" class="btn btn-dark" name="delete" value="Elimina">
                    </form>
                </div>
              </div>
              <br />
            </div>
          </div>
        </div>
      </body>
    <?php } ?>
    
</html>