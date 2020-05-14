<?php include "head.php" ?>

    <title>e-book store</title>

    <link rel="stylesheet" href="css/index.css" media="all" type="text/css">


</head>

<body>

    <?php include "header.php";?>

    <main>
        <div class="row">

            <?php 

            $getBook =  "SELECT * FROM `books` ORDER BY RAND() LIMIT 9"; 

            $book_query = mysqli_query($conn, $getBook);

                while($row = mysqli_fetch_array($book_query)){

                    $id = $row["id"];
                    $book = $row["books"];
                    $author = $row["authors"];
                    $genre = $row["genres"];
                    $price = $row["price"];
                    $description = $row["description"];
                    $e = substr($description, 0, 170);   
                    $img = $row["img"];

            ?>

            <div class="book">

                <a href="book.php?id=<?php echo $id?>"><img src="img/<?php echo $img?>" alt=""></a>
                <div class="details">
                    <p><?php echo $book?></p>
                    <a href="all_books.php?section=authors&subsection=<?php echo $author?>" class="text-info"><?php echo $author?></a>
                    <div class="buy">
                        <span><?php echo $price?>$</span>
                        <a href="book.php?id=<?php echo $id?>"class="btn btn-outline-info">read more</a>
                    </div>
                </div>

            </div>

            <?php }?>

        </div>

    </main>

    <?php include "footer.php" ?>

</body>

</html>