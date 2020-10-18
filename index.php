<?php session_start();?>
<?php require_once('include/connection.php')?>
<?php require_once('include/function.php') ?>

<?php
//check for the submission
    if(isset($_POST['submit'])){
        $error = array();
       //check for the username and password
       if(!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1){
           $error[] = 'username is invalid ';
       }

       if(!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1){
        $error[] = 'password is invalid ';
      }
      // check whether any errors in the form 
      if(empty($error)){
          //save username and password into the variable
          $email = mysqli_real_escape_string($connection,$_POST['email']);
          $password = mysqli_real_escape_string($connection,$_POST['password']);
          $hased_password = sha1($password);

          //prepare database query
          $query = "SELECT * FROM user WHERE 
                    password ='{$hased_password}' AND email ='{$email}' ";

          $result_set = mysqli_query($connection,$query);
          
          verify_query($result_set);
              //query success
              if(mysqli_num_rows($result_set) == 1){
                  //valid youser found
                  $user = mysqli_fetch_assoc($result_set);
                  $_SESSION['user_id'] = $user['id'];
                  $_SESSION['first_name'] = $user['first_name'];
                 // loging date and time
                 $query ="UPDATE user SET last_loging_date = NOW() ";
                 $query .= "WHERE  id={$_SESSION['user_id']} LIMIT 1";

                 $result_set = mysqli_query($connection,$query);

                  verify_query($result_set);
                  
                  //redirect to users.php
                     header('Location: users.php');
              } else {
                  //username and password invalid
                  $error[] = 'invalid user';
                }

          }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Home</title>
</head>
<body>
    <div class="login">
       <form action="index.php" method="POST">
         <fieldset>
           <legend><h1>Log In</h1></legend>


           <?php
           if(isset($error) && !empty($error)){
            echo' <p class="error">Invalid Password or Username</p>';
           }
           ?>
         
          <?php 
              if(isset($_GET['logout'])){
                echo' <p class="info">You have successfully logout from the system </p>';   
              } 
           ?>

           <p>
            <label for="">Username</label>
            <input type="text" name="email" id="" placeholder="username">   
            </p>
            <p>
            <label for="">Password</label>
            <input type="password" name="password" id="" placeholder="password">   
            </p>
            <p>
            <button type="submit" name="submit">Log In</button>
            </p>
         </fieldset>
       </form>   
    </div>
</body>
</html>
<?php mysqli_close($connection);?>