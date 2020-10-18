<?php session_start();?>
<?php require_once('include/connection.php') ?>
<?php require_once('include/function.php') ?>

<?php
  if(!isset($_SESSION['user_id'])){
      header('Location : index.php');
  }

   $user_list = '';
  //getting the list of users
  $query = "SELECT * FROM user WHERE is_deleted = 0 ORDER BY first_name ";
  $users = mysqli_query($connection,$query);

  verify_query($users);

      while($user = mysqli_fetch_assoc($users)){
          $user_list.="<tr>";
          $user_list.="<td>{$user['first_name']}</td>";
          $user_list.="<td>{$user['last_name']}</td>";
          $user_list.="<td>{$user['last_loging_date']}</td>";
          $user_list.="<td><a href=\"modify_user.php?user_id={$user['id']}\">Edit</a></td>";
          $user_list.="<td><a href=\"delete-user.php?user_id={$user['id']}\" onclick=\"delete(\"Do you want to delete it !\")\">Delete</a></td>";
          $user_list.="</tr>";
      }


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user.css">
    <title>Users</title>
    <script type="text/javascript>">
     function delete(t){
          confirm(t);
     }
    </script>
</head>
<body>
<header>
   <div class="manegement">User Manegement System</div>
   <div class="user">Welcome <?php  echo $_SESSION['first_name'];  ?> ! <a href="logout.php">Logout</a></div>
</header>

      <div class="class1">
      <h1>Users <span><a href="add-user.php"> Add New</a></span></h1>
     <table class="masterlist">
        <tr class="thead">
            <th> First name </th>
            <th> Last name </th>
            <th> Last login </th>
            <th >Edit </th>
            <th> Delete </th>
        </tr>
      <?php  echo $user_list; ?>

     </table>
      </div>
    
</body>
</html>
<?php mysqli_close($connection); ?>