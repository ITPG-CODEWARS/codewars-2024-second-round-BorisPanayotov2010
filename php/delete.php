<?php


  // DELETING THE UN-WANTED URLS
  include "config.php";
  if(isset($_GET['id'])){
    $dlt_id = mysqli_real_escape_string($conn, $_GET['id']); //deleting a specific url via id system in the database
    $sql = mysqli_query($conn , "DELETE FROM url WHERE shorten_url = '{$dlt_id}'"); // deleting the url via id
    if($sql){
      header("Location: http://localhost/url.php");
    }else{
      header("Location: http://localhost/url.php");
    }
  }elseif($_GET['delete']){
    $sql2 = mysqli_query($conn , "DELETE FROM url");
    if($sq2){
      header("Location: http://localhost/url.php");
    }else{
      header("Location: http://localhost/url.php");
    }
  }else{
    header("Location: http://localhost/url.php");
  }
?>