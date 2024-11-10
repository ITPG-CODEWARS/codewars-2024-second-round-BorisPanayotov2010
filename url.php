<?php

  //system for creating custom urls

  include "php/config.php";
  $new_url = "";
  if (isset($_GET)){

    //finding what is the changable part of the short url via $key
    
    foreach($_GET as $key=> $val){
       $u = mysqli_real_escape_string($conn, $key); 
       $new_url = str_replace('/', '', $u);
    }
     $sql = mysqli_query($conn, "SELECT full_url FROM url WHERE shorten_url= '{$new_url}'"); //updating the database with the new url
     if(mysqli_num_rows($sql) > 0){
       $count_query = mysqli_query($conn , "UPDATE url SET clicks = clicks + 1 WHERE shorten_url= '{$u}'"); //updating the database clicks section
       if($count_query){
         $full_url =mysqli_fetch_assoc($sql);
         header("Location:" .$full_url['full_url']); //the custom url still leading to the original url
      }
     }
  }
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Link Shrink</title>
    <link rel="stylesheet" href="styling/main.css" />
    <!-- The fonta awsome link -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
  </head>
  <body>
      <!-- The cosmetic section AKA the stars -->
      <div class="night">
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
      </div>
    <main>

      <div class="name">
        <h1>LinkShrink</h1>
        <h2>Worlds first absolutely free<br>url shortening service from Bulgaria</h2>
      </div>
      <div class="wrapper">
        <form action="#">
          <i class="fa-solid fa-link"></i>
          <input
            type="text"
            name= "full-url"
            placeholder="Write or paste youre long url here"
            required
          />
          <button>Shorten</button>
        </form>
          <?php
            $sql2 = mysqli_query($conn, "SELECT * FROM url ORDER BY id DESC");
            if(mysqli_num_rows($sql2) > 0){
          ?>
              <div class="count">
                <?php

                  //making the clicks data dynamic

                  $sql3 = mysqli_query($conn, "SELECT COUNT(*) FROM url ");
                  $res = mysqli_fetch_assoc($sql3);

                  $sql4 = mysqli_query($conn, "SELECT clicks FROM url ");
                  $total = 0;

                  while($c = mysqli_fetch_assoc($sql4)){
                    $total = $c['clicks'] + $total;
                  }
                ?>
                <span>Total Links:<span><?php echo end($res); ?></span> & Total clicks:<span><?php echo $total; ?></span></span>
                <a href="php/delete.php?delete=all">Clear all</a>
              </div>

                <!-- the title of each column in the url area -->

              <div class="urls-area">
                <div class="title">
                  <li>Shorten URL</li>
                  <li>Original URL</li>
                  <li>Clicks</li>
                  <li>Action</li>
                </div>
                <?php  
              while($row = mysqli_fetch_assoc($sql2)){
                ?>

                <!-- Displaying the short url -->
                <div class="data">
                  <li>
                    <a href="http://localhost/url.php?<?php echo $row['shorten_url']?>" target="_blank"> 
                    <?php 
                      if('localhost/url.php?' . strlen($row['shorten_url']) > 50){
                        echo 'localhost/url.php?'.substr($row['shorten_url'], 0 , 50);
                      }else{
                        echo 'localhost/url.php?'.$row['shorten_url'];
                      }
                    ?>
                  </a>
                  </li>
                  <li>
                    <?php 

                      // Displaying the long url

                      if(strlen($row['full_url']) > 65){
                        echo substr($row['full_url'], 0 , 65). '...';
                      }else{
                        echo $row['full_url'];
                      }
                    ?>
                    </li>
                  <li><?php echo $row['clicks']?></li>

                      <!-- The delete action -->
                  <li><a href="php/delete.php?id=<?php echo $row['shorten_url'] ?>">Delete</a></li>
                </div>
                <?php
              }
            }
            ?>
              </div>
      </div>

            <!-- The popup box effect and structure -->

      <div class="blur-effect"></div>
      <div class="popup-box">
        <div class="info-box error">
          Your short link is ready. Feel free to copy and use it as you wish.
        </div>
        <form action="#">
          <input id="short" type="text" spellcheck="false"  />
          <i class="fa-regular fa-copy"></i>
          <button>Save</button>
        </form>
      </div>
    </main>

    <script>

      // ALL VARIABLES IN JS

      const form = document.querySelector(".wrapper form");
      fullURL = form.querySelector("input");
      shortenBtn = form.querySelector("button");
      blurEffect = document.querySelector(".blur-effect");
      popupBox = document.querySelector(".popup-box");
      shortenURL = document.querySelector("#short");
      saveBtn = popupBox.querySelector("button");
      form2 = popupBox.querySelector("form");
      copyBtn = popupBox.querySelector(".fa-regular");
      infoBox = popupBox.querySelector(".info-box");

      form.onsubmit = (e) => {
        e.preventDefault;
      };

      shortenBtn.onclick = () => {
        // Starting ajax
        let xhr = new XMLHttpRequest(); //creating a xhr object
        xhr.open("POST", "php/url-controll.php", true);
        xhr.onload = () => {
          if (xhr.readyState == 4 && xhr.status == 200) {
            let data = xhr.response;
            if (data.length <= 5) {
              blurEffect.style.display = "block";
              popupBox.classList.add("show");

              console.log(data);

              let domain = "localhost/url/"; //declaring the mandatory url that has to be in the short url section
              shortenURL.value = domain + data; //the visiualization of the short url
              copyBtn.onclick = ()=>{ // what happens when you click on the copy icon
                shortenURL.select();
                document.execCommand("copy");
              }
              form2.onsubmit = (e) => {
                e.preventDefault();
              }

              //saving the new url and updating the database

              saveBtn.onclick = ()=>{
                let xhr2 = new XMLHttpRequest(); 
                xhr2.open("POST", "php/save-url.php", true);
                xhr2.onload = () => {
                  if (xhr2.readyState == 4 && xhr2.status == 200) {
                    let data = xhr2.response;
                    if(data == "success"){
                      location.reload();
                    }else{
                      infoBox.innerText = data;
                      infoBox.classList.add("error");
                    }
                  }
                }
                  let short_url = shortenURL.value;
                  let hidden_url = data;
                  xhr2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                  xhr2.send("shorten_url="+short_url+"&hidden_url="+hidden_url); //some of the code about query strings in the short urls
              }
            }else{
              alert(data);
            }
          }
        }
        let formData = new FormData(form);
        xhr.send(formData);
      }
    </script>
  </body>
</html>
