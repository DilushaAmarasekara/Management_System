<?php
 
 $connection = mysqli_connect('localhost','root','','project'); 
 
 if(mysqli_connect_errno()){
     die('database connection is faild'. mysqli_connect_errno());
 }
?> 