<?php include "head.php" ?>

<?php
if(empty($_SESSION['username'])){
?>
<script>setTimeout(function(){window.location = "index.php"}, 0);</script>
<?php
    exit();
}?>
<title>Hi, <?php echo $_SESSION['username']?></title>

<link rel="stylesheet" href="css/all_books.css" media="all" type="text/css">

</head>

<body>
    <?php include "header.php" ?>

    <main class='container py-5'>


        <form action="" method="POST">

            <nav class="nav navbar-expand mb-3">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <h4>Hi, <?php echo $_SESSION['username']?></h4>
                    </li>

                </ul>

                <ul class='navbar-nav ml-auto'>

                    <?php if( $_SESSION['is_admin'] == 'admin' ){  ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-success" href="add_book.php">Add new book</a>
                    </li>
                    <?php  }  ?>

                    <li class="nav-item">
                        <button class="nav-link btn btn-danger" type="submit" name="log_out">log out</button>
                    </li>
                </ul>
            </nav>
        </form>
        <h4>Your books</h4>
        <div class="row allbooks">

            <?php

                $username = $_SESSION['username'];

                $getOrders =  "SELECT * FROM `orders` WHERE user = '$username'"; 

                $order_query = mysqli_query($conn, $getOrders);

                while($row = mysqli_fetch_array($order_query)){

                    $id = $row["id"];
                    $book = $row["book"];
                    $v = explode(",", $book);

                     foreach( $v as $e){

                         $getBook =  "SELECT * FROM `books` WHERE id = '$e'"; 

                         $book_query = mysqli_query($conn, $getBook);

                         while($row = mysqli_fetch_array($book_query)){

                            $id = $row["id"];
                            $book = $row["books"];
                            $author = $row["authors"];
                            $genre = $row["genres"];
                            $img = $row["img"];
                            ?>


                            <!--<div class="book border d-flex col-12 col-md-6 col-xl-4">-->

                                
                                 <a class="col-6 col-md-3 col-xl-2" href="book.php?id=<?php echo $id?>">
                                    <img src="img/<?php echo $img?>" alt="">
                                    <p class="text-dark"><?php echo $book?></p>
                                </a>
                            <!--</div>-->


                        <?php

                        }
                    }
                }
            ?>
        </div>
    </main>

    <?php include "footer.php" ?>
</body>

</html>