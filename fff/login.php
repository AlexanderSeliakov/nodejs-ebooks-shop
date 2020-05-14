<?php include "head.php" ?>

<?php
if(!empty($_SESSION['username'])){
?>
<script>
setTimeout(function()
{window.location = "user.php"}, 0);
</script>
<?php
    die;
}?>

<title>All books</title>

<link rel="stylesheet" href="css/all_books.css" media="all" type="text/css">

</head>

<body>
    <?php include "header.php"?>
    
    <div class="logging">

        <form action="" method="POST">

                <h1 class="h3 my-5 font-weight-normal">Please sign in</h1>

            <div class="inpt">
                
                <label for="login">Username</label>
                <input class="form-control" id="username" name="username" type="text" required autofocus>
            </div>

            <div class="inpt mt-3">
                <label for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password"  autocomplete="on" required>
            </div>

            <button class="btn mt-4 btn-outline-info" type="submit" name="log_in">log in</button>

            <a href="registration.php" class="text-secondary ml-auto">Register</a>

        </form>

    </div>

    <?php include "footer.php" ?>
</body>

</html>