<?php include "head.php" ?>
<?php
if(!empty($_SESSION['username'])){
?>
<script>
setTimeout(function()
{window.location = "user.php"}, 0);
</script>
<?php
    exit();
}

if(isset($_POST['register'])){
    
    $username = $_POST['login'];
    $mail = $_POST['mail'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];


    $check_user =  "SELECT * FROM `users` WHERE mail = '$mail' OR name = '$username'"; 

    $check_query = mysqli_query($conn, $check_user);

     if(mysqli_num_rows($check_query)==0){

        if($password == $password2){

            $password = md5($password);

            $user = "INSERT INTO `users` (`name`, `mail`, `password`, `admin`) VALUES ('$username', '$mail', '$password', 'user');";;

            mysqli_query($conn, $user);

            $_SESSION['message'] = "you are logged in";
            $_SESSION['username'] = $username;

            // header("Location: index.php");
            ?>
            <script>
            setTimeout(function()
            {window.location = "index.php"}, 0);
            </script>
            <?php

        }else{
            $_SESSION['message'] = '<p class="alert alert-warning mt-3">passwords do mot match</p>';
        }
    }else{
        $_SESSION['message'] = '<p class="alert alert-warning mt-3">username or email already exist</p>';
    }
}

?>
<title>All books</title>

<link rel="stylesheet" href="css/all_books.css" media="all" type="text/css">

</head>
<body>

    <?php include "header.php" ?>

    <div class="logging my-3">

        <form action="" method="POST" id = "registration">
            <?php echo $_SESSION['message']?>
                <h1 class="h3 mb-4 mt-2 font-weight-normal">Please sign in</h1>

            <div class="inpt mt-3">
                
                <label for="login">Email adsress</label>
                <input class="form-control" id="mail" name="mail" type="text" required autofocus>
            </div>

            <div class="inpt mt-3">
                
                <label for="login">Login</label>
                <input class="form-control" id="login" name="login" type="text" required>
            </div>

            <div class="inpt mt-3">
                <label for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password"  autocomplete="on" required>
            </div>

            <div class="inpt mt-3">
                <label for="password">Repeat password</label>
                <input class="form-control" id="password2" name="password2" type="password"  autocomplete="on" required>
            </div>

            <button class="btn mt-5 btn-outline-info" type="submit" name="register">Register</button>

        </form>

    </div>

    <?php include "footer.php";
    $_SESSION['message'] = ''?>
</body>

</html>