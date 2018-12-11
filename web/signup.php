<?php
session_start();
$conn = mysqli_connect("ec2-54-243-150-10.compute-1.amazonaws.com", "ncppfvwqgcsxvk", "ab8b66b5920573c30adac4f8546e904c4f35d07c4768ebe7bce60eeedd2f55dd", "d6m4gmpavkoiff");
if(mysqli_connect_errno()) {
	echo "Failed to connect: " . mysqli_connect_errno();
}

$fname = "";
$em = "";
$em2 = "";
$password = "";
$password2 = "";
$date = "";
$error_array = array();

if(isset($_POST['signup-button'])) {
    $fname = strip_tags($_POST['reg_fname']);
    $fname = str_replace(' ', '', $fname);
    $fname = ucfirst(strtolower($fname));
    $_SESSION['reg_fname'] = $fname;


    $em = strip_tags($_POST['reg_email']);
    $em = str_replace(' ', '', $em);
    $em = ucfirst(strtolower($em));
    $_SESSION['reg_email'] = $em;

    $em2 = strip_tags($_POST['reg_email2']);
    $em2 = str_replace(' ', '', $em2);
    $em2 = ucfirst(strtolower($em2));
   

    $password = strip_tags($_POST['reg_password']);
    $password2 = strip_tags($_POST['reg_password2']);

    $date = date('Y-m-d');

    if($em == $em2) {
        if(filter_var($em, FILTER_VALIDATE_EMAIL)) {
               $em = filter_var($em, FILTER_VALIDATE_EMAIL);

               $e_check = mysqli_query($conn, "SELECT email FROM users WHERE email='$em'");
               $num_rows = mysqli_num_rows($e_check);
               if($num_rows > 0) {
                   array_push($error_array,"Email already used.<br>");
               }
        }
        else {
            array_push($error_array, "Invalid format<br>");
        }

    }
    else {
        array_push($error_array, "Emails don't match");
    }

    if(strlen($fname) > 25 || strlen($fname) < 2)  {
        array_push($error_array, "First name must be between 2 and 25 characters");
    }

    if($password != $password2) {
        array_push($error_array, "Passwords do not match");
    }
    else {
        if(preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Your password can only contain english characters or numbers");
        }
    }
    if(strlen($password > 30 || strlen($password) < 5)) {
        array_push($error_array, "Your password must be between 5 and 30 characters");
    }
    if(empty($error_array)) {
        $password = md5($password); //Encrypt password before sending to database

        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");
        $i = 0;
        //if username exists add number to username
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++;
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");
        }
        $profile_pic = "";
    }
   
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Talented Signup</title>
    <link rel="stylesheet" href="/stylesheets/signUp.css">
    <link rel="icon" href="/assets/talented.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <style>
      body {
        background-color: #182848;
      }
    </style>
    
  </head>
  <body>
    <div class="content">
      <div class="login-section">
        <img src="/assets/talented.png" class="pic">
        <div class="left-box">
          <h1>Sign Up</h1>
         <form action='signup.php' method="POST">
          <input type="text" name="reg_fname" placeholder="First Name" value="<?php
          if(isset($_SESSION['reg_fname'])) {
            echo $_SESSION['reg_fname'];
          }
          ?>" required>                                                                    
          <input type="text" name="reg_email" placeholder="E-mail" value="<?php
           if(isset($_SESSION['reg_email'])) {
             echo $_SESSION['reg_email'];
           }
           ?>" required>  
           <input type='text' name='reg_email2' placeholder='Confirm E-mail' value="<?php
            if(isset($_SESSION['reg_email2'])) {
              echo $_SESSION['reg_email2'];
             } ?>"required>
          <input type="password" name="reg_password" placeholder="Password" required>
          <input type="password" name="reg_password2" placeholder="Confirm Password" required>
          <input type="submit" name="signup-button" value="Sign Up">
        </div>
          </form>
        <div class="right-box">
          <span class="signupwith">Sign In with<br/>Social Network</span>
          <button class="social facebook">Facebook</button>
          <button class="social twitter">Twitter</button>
          <button class="social github">Google</button>
        </div>
        <div class="or">OR</div>
      </div>
      <footer id="footer">
      Powered by: © Talented Technologies
    </footer>
    </div>
  </body>
</html>
