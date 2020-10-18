<?php

  function verify_query($result_set){
    global $connection;

    if(!$result_set){

        die('Database is not working' . mysqli_error($connection));
    }

  }

  function check_fields($require_field){
 
     $error = array(); 

    foreach($require_field as $field){
      if(empty(trim($_POST[$field]))){
          $error[] = $field .' is required';
      } 
    }
    return $error;

  }

  function check_max_length($max_len_field){
 
    $error = array(); 
  
    foreach($max_len_field as $field => $max_len){
      if(strlen(trim($_POST[$field])) > $max_len){
          $error[] = $field  . ' must be less than ' . $max_len . ' character';
      } 
    }
   
   return $error;

 }

  function display_errors($error){

    echo '<div class="error">';
    echo '<b>There were error(s) in your form<b><br>';
     foreach($error as $errors) {
     $errors = ucfirst(str_replace("_"," ",$errors));
      echo '-'.$errors.'<br>';
     }
      echo '</div>';

  }

  function is_valid($email){

    return (preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',$email));
  }

?>