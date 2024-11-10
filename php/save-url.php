<?php
  include "config.php"; //making a connection to the databse

  $og_url = mysqli_real_escape_string($conn, $_POST['shorten_url']); // the original url with a random combination
  $full_url = str_replace('', '', $og_url);
  $hidden_url = mysqli_real_escape_string($conn, $_POST['hidden_url']); //the new custom url
  
  if(!empty($full_url)){
    $domain = "localhost"; // the mandatory part of the url



    if(preg_match("/{$domain}/i" , $full_url) && preg_match("/\//" , $full_url)){ //if there is a match with the mandatory parts of the url and the custom url
      $explodeURL = explode('/', $full_url);
      $short_url = end($explodeURL);
      if($short_url != ""){
        $sql = mysqli_query($conn, "SELECT shorten_url FROM url WHERE shorten_url = '{$short_url}' && shorten_url != '{$hidden_url}'"); // replacing the random combination with the custom one
        if(mysqli_num_rows($sql) == 0){
          $sql2 = mysqli_query($conn , "UPDATE url SET shorten_url = '{$short_url}' WHERE shorten_url = '{$hidden_url}'"); //updating the database

          //all the possible errors that can happen
          if($sql2){
            echo "success"; 
          }else{
            "Something went wrong";
          }
        }else{
          echo "Error-This Url already exists";
        }
      }else{
        echo "Error - enter short url";
      }
    }else{
      echo "Invalid URL";
    }
  }else{
    echo "Error - enter short url";
  }
?>