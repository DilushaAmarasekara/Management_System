<?php session_start();?>
<?php require_once('include/connection.php'); ?>
<?php require_once('include/function.php'); ?>


<?php
 
 if(!isset($_SESSION['user_id'])){
    header('Location : index.php');
  }

   $error = array();
   $user_id = '';
   $first_name = '';
   $last_name ='';
   $email = '';
   

   if(isset($_GET['user_id'])){
       //getting the user information
       $user_id = mysqli_real_escape_string($connection,$_GET['user_id']);
       $query = "SELECT * FROM user WHERE id={$user_id} LIMIT 1";
       $result_set = mysqli_query($connection,$query);

       if($result_set){
           if(mysqli_num_rows($result_set) == 1){
               //user found
               $result = mysqli_fetch_assoc($result_set); 
               $first_name = $result['first_name'];
               $last_name = $result['last_name'];
               $email =  $result['email'];
           }else{
               //user not found
               header('Location:users.php?err=users not found');
           }
       }else{
           //query unsuccessful
           header('Location:users.php?err=query_failed');
       }
   }


// checking space  
   if(isset($_POST['submit'])){
     $user_id = $_POST['user_id'];
     $first_name = $_POST['first_name'];
     $last_name = $_POST['last_name'];
     $email = $_POST['email'];
     

      // check fields
      $require_field = array('user_id','first_name','last_name','email');
      $error = array_merge($error,check_fields($require_field));

   }
// checking length
 
if(isset($_POST['submit'])){

    $max_len_field = array('first_name' =>50 ,'last_name' =>50 ,'email' =>100);
    $error = array_merge($error, check_max_length($max_len_field));
    

     //check the valid email
    if(!is_valid($_POST['email'])){
        $error[] = 'This email address is not valid';
    } 

    //checking the email address is already exist
     $email = mysqli_real_escape_string($connection,$_POST['email']);
     $query = "SELECT * FROM user WHERE email = '{$email}' AND id != {$user_id}  LIMIT 1"; 
    
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
        

        $query = " UPDATE user SET";
        $query.=" first_name = '{$first_name}',";
        $query.=" last_name ='{$last_name}',";
        $query.=" email ='{$email}'";
        $query.=" WHERE id={$user_id} LIMIT 1";

        $result = mysqli_query($connection,$query);

        if($result){
            header('Location:users.php?user_modified=true');
        }else{
            $error[] = ' Modified is failed ';
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
    <title>View and MOdify</title>
</head>
<body>
<header>
   <div class="manegement">User Manegement System</div>
   <div class="user">Welcome <?php  echo $_SESSION['first_name'];  ?> ! <a href="logout.php">Logout</a></div>
</header>

      <div class="class1">
      <h1>View/Modify Password <span><a href="users.php">< Back To User List</a></span></h1>
    
      <?php 
       if(!empty($error)){
         // checking required fields 
          display_errors($error);
       } 
      ?>

      </div>

      <form action="modify_user.php" method="post" class = "formaction">
      <div class="add_user">
       <p>
           <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
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
       <span>**********</span> | <a href="change-password.php?user_id=<?php echo $user_id;?>">Change Password</a>
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