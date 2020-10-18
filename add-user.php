<?php session_start();?>
<?php require_once('include/connection.php'); ?>
<?php require_once('include/function.php'); ?>


<?php
 
 if(!isset($_SESSION['user_id'])){
    header('Location : index.php');
  }

   $error = array();
   $first_name = '';
   $last_name ='';
   $email = '';
   


        // checking space  
   if(isset($_POST['submit'])){

     $first_name = $_POST['first_name'];
     $last_name = $_POST['last_name'];
     $email = $_POST['email'];
     

      // check fields
      $require_field = array('first_name','last_name','email');
      $error = array_merge($error,check_fields($require_field));

   }
          // checking length
 
    if(isset($_POST['submit'])){

    $max_len_field = array('first_name' =>50 ,'last_name' =>50 ,'email' =>100 );
    $error = array_merge($error, check_max_length($max_len_field));
    

     //check the valid email
    if(!is_valid($_POST['email'])){
        $error[] = 'This email address is not valid';
    } 

    //checking the email address is already exist
     $email = mysqli_real_escape_string($connection,$_POST['email']);
     $query = "SELECT * FROM user WHERE email = '{$email}' LIMIT 1"; 
    
    $result_set = mysqli_query($connection,$query);

    if($result_set){
        if(mysqli_num_rows($result_set) == 1){
            $error[] = 'This email is already exist in the database';
        }
    }

    //enter new record to the database
    if(empty($error)){
      
        $first_name = mysqli_real_escape_string($connection,$_POST['first_name']);
        $last_name = mysqli_real_escape_string($connection,$_POST['last_name']);
        

        $query = " INSERT INTO user (";
        $query.=" first_name,last_name,email,is_deleted,password)";
        $query.=" VALUES (";
        $query.=" '{$first_name}','{$last_name}','{$email}',0,'{$hased_password}')";

        $result = mysqli_query($connection,$query);

        if($result){
            header('Location:users.php?add-user=true');
        }else{
            $error[] = ' Insert your details failed ';
        }

    }
 }



 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/add-user.css">
    <title>Users</title>
</head>
<body>
<header>
   <div class="manegement">User Manegement System</div>
   <div class="user">Welcome <?php  echo $_SESSION['first_name'];  ?> ! <a href="logout.php">Logout</a></div>
</header>

      <div class="class1">
      <h1>Add New User <span><a href="users.php">< Back To User List</a></span></h1>
    
      <?php 

       if(!empty($error)){
    // checking required fields 
          display_errors($error);
       } 

          ?>

      </div>

       

      <form action="add-user.php" method="post" class = "formaction">
      <div class="add_user">
       <p>
       <label>First name :</label>
       <input type="text" name="first_name" <?php echo 'value ="' . $first_name . ' "' ?> >
       </p>

       <p>
       <label>Last name :</label>
       <input type="text" name="last_name" <?php echo 'value ="' . $last_name . ' "' ?> >
       </p>

       <p>
       <label>Email :</label>
       <input type="text" name="email" <?php echo 'value ="' . $email . ' "' ?> >
       </p>

       <p>
       <label>Password :</label>
       <input type="password" name="password" >
       </p>

       <p>
       <label>&nbsp;</label>
       <button type="submit" name="submit" >Save</button>
       </p>

</div>
      </form>
    
</body>
</html>
<?php mysqli_close($connection); ?>