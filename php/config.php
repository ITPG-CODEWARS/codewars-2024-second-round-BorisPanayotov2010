<?php

  //CREATING A CONNECTION FROM THE CODE TO THE DATABASE 
  $conn = mysqli_connect("localhost" , "root" , "" , "url");
  if(!$conn){
    echo "Database conected" .mysqli_connect_error();
  }
?>