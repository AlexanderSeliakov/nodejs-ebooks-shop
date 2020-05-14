<?php include "head.php" ?>

<title>Adding new books</title>
<link rel="stylesheet" href="css/all_books.css" media="all" type="text/css">
</head>

<body>

    <?php include "header.php" ?>

    <main class="container my-5">

        <div class="row mb-2">

            <div class="card col-sm-12 flex-sm-row">

                <?php 
            if (isset($_GET['id'])){
                $product = $_GET['id'];
                $get_product = "SELECT * FROM books where id = $product";
                $showProduct = mysqli_query($conn, $get_product);
                while($row = mysqli_fetch_array($showProduct)){
                    $id = $row["id"];
                    $book = $row["books"];
                    $author = $row["authors"];
                    $genre = $row["genres"];
                    $series = $row["series"];
                    $price = $row["price"];
                    $description = $row["description"];
                    $img = $row["img"];
                ?>

                <div class="mx-auto my-3"><img class="book_cover" src="img/<?php echo $img?>"
                        alt="Card image cap"></div>

                <form action="" method="POST">

                    <div class="card-body d-flex flex-column align-items-start">

                        <h3 class="mb-0"><?php echo $book?></h3>

                        <a class="text-info" href="all_books.php?section=authors&subsection=<?php echo $author?>">
                            <?php echo $author?>
                        </a>

                        <div><?php echo $series ?></div>

                        <p class=" mt-3 mb-auto"><?php echo $description ?></p>

                <?php 

                    if(isset($_SESSION["cart"])){

                        if(in_array($_GET['id'],$_SESSION["cart"])){
                            echo '<button class="btn mt-3 btn-success" value = '.$id.' name= "delete_from_cart">added</button>';
                        }
                        else{
                            echo '<button class="btn mt-3 btn-outline-info" value = '.$id.' name= "add_to_cart">buy for '.$price.'$</button>';
                        }

                    }else{
                        echo '<button class="btn mt-3 btn-outline-info" value = '.$id.' name= "add_to_cart">buy for '.$price.'$</button>';
                    }

                    if (isset($_POST['add_to_cart'])){
                    
                            if(in_array($_POST['add_to_cart'],$_SESSION["cart"])){
                    
                                array_push($_SESSION["price"], $price);
                        
                            }
                    }

                }
            }?>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php include "footer.php" ?>
</body>

</html>