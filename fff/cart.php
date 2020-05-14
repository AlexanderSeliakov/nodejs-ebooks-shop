<?php include "head.php";

if(isset($_POST['check_out'])){

    if(!empty($_SESSION["username"])){

        $order = implode( ", ", $_SESSION["cart"]);

        $name = $_SESSION['username'];
        
        $cart_insert = "INSERT INTO `orders` (`user`, `book`) VALUES ('$name', '$order')";

        mysqli_query($conn, $cart_insert);

        $_SESSION['message'] = "";

        if(!empty($_SESSION['username'])){
            ?>
            <script>window.location = "thankyou.php"</script>
            <?php
            
             exit();
        }    
    }else{
    
        if(empty($_SESSION['username'])){
            ?>
            <script>window.location = "check_out.php"</script>
            <?php
            exit();
        } 
    }
} 
?>
<title>all books</title>

<link rel="stylesheet" href="css/all_books.css" media="all" type="text/css">

</head>

<body>

    <?php include "header.php" ?>

    <main class="container my-5">
        <form action="" method="post" class="">

            <div class="d-flex mb-3">

                <?php 
                if(!empty($_SESSION['cart'])){

                    $r = array_sum($_SESSION['price']);?>

                <h4 class='mb-0'>You have <?php echo count($_SESSION['cart'])?> books for <?php echo $r?>$</h4>

                <button class=" ml-auto btn btn-outline-success" name="check_out">check out</button>

            </div>

            <div class="row cart p-3">

                <?php

                    $customer_cart = array();

                    foreach ($_SESSION['cart'] as $value){

                        $cart_id = $value;
                                    
                        $cart_select = "SELECT * FROM `books` WHERE id = $cart_id";

                        $cart_query = mysqli_query($conn, $cart_select);
                            
                        while($row= mysqli_fetch_array($cart_query)){
                    
                            $id = $row["id"];
                            $book = $row["books"];
                            $author = $row["authors"];
                            $genre = $row["genres"];
                            $price = $row["price"];
                            $description = $row["description"];
                            $img = $row["img"];
                ?>

                <div class="book border d-flex col-12 col-md-6 col-xl-4">

                    <a href="book.php?id=<?php echo $id?>"><img src="img/<?php echo $img ?>" alt=""></a>

                    <div class="card-body p-3">
                        <p><?php echo $book?></p>
                        <a class="text-primary" href="all_books.php?section=authors&subsection=<?php echo $author?>">
                            <p><?php echo $author?></p>
                        </a>
                        <p class=""><b><?php echo $price?>$</b></p>
                        <button class="btn btn-outline-danger" value=<?php echo $id?>
                            name="delete_from_cart">remove</button>

                    </div>

                </div>

                <?php 
                        }
                    }
                }else{
                    echo '<h4 class="">You have no books yet!</h4>';
                }
                ?>
            </div>
        </form>
    </main>
    <?php include "footer.php" ?>
</body>

</html>