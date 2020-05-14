<?php
include("head.php");
echo "thank you";
echo "<br>";
echo "click on image to start download ";

foreach($_SESSION["cart"] as $img){
    $img_select = "SELECT * FROM `books` WHERE id = '$img'";
    $img_query = mysqli_query($conn, $img_select);
    while($row = mysqli_fetch_array($img_query)){
?>
<a href='<?php echo "img/".$row['img']; ?>'  download='<?php echo $row['book'] ?>'>

  <img src="img/<?php echo $row['img']?>" alt="W3Schools" width="104" height="142">
  
</a>
<?php

    }
}
 $_SESSION["cart"] = array();
?>
<a href="index.php">go back</a>