<?php 
session_start();

 //$conn = mysqli_connect("localhost", "root", "", "ecommerce");
$conn = mysqli_connect("localhost", "alexander", "MariaPugacheva23", "alexander_tasks");


//add to cart

empty($_SESSION["cart"]);
empty($_SESSION["price"]);
empty($_SESSION["order"]);

if (isset($_POST['add_to_cart'])){

    if (empty($_SESSION["cart"])){
        $_SESSION["cart"] = array();
        $_SESSION['price'] = array();
    }

    if(!in_array($_POST['add_to_cart'],$_SESSION["cart"])){

        array_push($_SESSION["cart"], $_POST['add_to_cart']);

    }else{
        $added_book = array_search($_POST['add_to_cart'], $_SESSION['cart']);
        unset($_SESSION['cart'][$added_book]);
    }
}
//end 

//delete from cart
if(isset($_POST['delete_from_cart'])){
    $added_book = array_search($_POST['delete_from_cart'], $_SESSION['cart']);
    unset($_SESSION['price'][$added_book]);
    unset($_SESSION['cart'][$added_book]);
}
//end 

//amount of books in the cart 
if(empty($_SESSION["cart"])){
    $amount = 0;
}else{
    $amount = count($_SESSION['cart']);  
}


//log in

if(isset($_POST['log_in'])){

    if(isset($_SESSION['username'])==0 ){

    $username = $_POST['username'];

    $password = $_POST['password'];

    $password = md5($password);

    $user2 = "SELECT * FROM users WHERE name = '$username' AND password = '$password'";

    $result = mysqli_query($conn, $user2);
    if(mysqli_num_rows($result) == 1){
        //new
        $_SESSION['is_admin'] = mysqli_fetch_array($result)['admin'];

        $_SESSION['message'] = "you are logged in";
        $_SESSION['username'] = $username;
        if( $_SERVER['REQUEST_URI'] == 'http:alexander-seliakov.com/check_out.php'){
              ?>
   <script>window.location = "cart.php"</script>
   <?php
        }else{
            header("Refresh:0");
        }


        die;

    }else{
        $_SESSION['message'] = "The username or password is incorrect";
    }
}
}else{
    $_SESSION['message'] = "";
}
//log out
if(isset($_POST['log_out'])){
    session_destroy();
    $_SESSION['username'] = "";
    $_SESSION['message'] = "";
    header("Location: index.php");
    die;
}
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="css/main.css" media="all" type="text/css">

    <script src="https://kit.fontawesome.com/96df2bb0f4.js"></script>