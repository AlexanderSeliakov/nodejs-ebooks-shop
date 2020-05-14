<?php include "head.php" ?>

<?php 
if(isset($_POST['check_out_as_guest'])){


    $order = implode( ",", $_SESSION["cart"]);

    $name = $_POST['guest_mail'];
        
    $cart_insert = "INSERT INTO `orders` (`user`, `book`) VALUES ('$name', '$order')";

    mysqli_query($conn, $cart_insert);

    $_SESSION['message'] = "";
        
   ?>
   <script>window.location = "thankyou.php"</script>
   <?php
    die;
}

if(isset($_POST['log_in2'])){

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
        ?>
        <script>window.location = "cart.php"</script>
        <?php
        die;

    }else{
        $_SESSION['message'] = "The username or password is incorrect";
    }
}
}
?>

<title>Check out</title>

<link rel="stylesheet" href="css/all_books.css" media="all" type="text/css">

</head>

<body>
    <?php include "header.php" ?>

    <main class='check container row py-5'>
    
        <div class="log col-12 col-md-6">

            <form action="" method="POST">

                <h1 class="mt-5 font-weight-normal">Sign in</h1>
                <h4 class="mb-5"> <?php print_r($_SESSION['message'])?></h4>

                <div class="inpt">

                    <label for="username2">Username</label>
                    <input class="form-control" id="username2" name="username" type="text" required>
                </div>

                <div class="inpt mt-3">
                    <label for="password2">Password</label>
                    <input class="form-control" id="password2" name="password" type="password" required>
                </div>

                <button class="btn mt-4 btn-outline-info" type="submit" name="log_in2">log in</button>

                <a href="registration.php" class="text-secondary ml-auto">Register</a>

            </form>

        </div>

        <div class="sing_in col-12 col-md-6">

            <form action="" method="POST">

                <h1 class="my-5 font-weight-normal">Or buy as a guest</h1>

                <div class="inpt">

                    <label for="mail2">Enter mail</label>
                    <input class="form-control" id="mail2" name="guest_mail" type="mail" required>
                </div>

                <button class="btn mt-4 mb-5 btn btn-outline-success" name="check_out_as_guest">check out</button>

            </form>

        </div>
    </main>

    <?php include "footer.php" ?>
</body>

</html>
