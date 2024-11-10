<?php
  include "config.php"; //connectiong to the database
  $full_url = mysqli_real_escape_string($conn, $_POST['full-url'] ); //adding the full url to the database

  if(!empty($full_url) && filter_var($full_url, FILTER_VALIDATE_URL)){ //if the url is valid
    $ran_url = substr(md5(microtime()), rand(0, 26), 5); // generating the short url after string wich can handle over 22milion combinations
    $sql = mysqli_query($conn, "SELECT shorten_url FROM url WHERE shorten_url = '{$ran_url}' "); //adding the random combination to the databse
    if (mysqli_num_rows($sql) > 0){
      echo "Something went wrong. Please regenerate url again!"; //if there was a glitch in the system
    }else{
      $sql2 = mysqli_query($conn, "INSERT INTO url (shorten_url , full_url, clicks )
                                  VALUES('{$ran_url}', '{$full_url}', '0')"); //inserting all the info into the database
      if($sql2){
          $sql3 = mysqli_query($conn, "SELECT shorten_url FROM url WHERE shorten_url = '{$ran_url}' ");
          if(mysqli_num_rows($sql3) > 0){
            $shorten_url = mysqli_fetch_assoc($sql3);
            echo $shorten_url['shorten_url'];
          }
        }else{
        echo "something went wrong! Please try again.";
      }
    }
  }else{
    echo "$full_url - This is not a valid URL"; //if the long url is not valid
  }
?>