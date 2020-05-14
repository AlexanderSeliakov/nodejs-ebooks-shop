<?php include "head.php" ?>

<title>All books</title>

<link rel="stylesheet" href="css/all_books.css" media="all" type="text/css">

</head>

<body>
    <?php include "header.php" ?>

    <main class="container my-3">

        <?php

            if(isset($_GET["subsection"])){

                $section = $_GET['section'];

                $subsection = $_GET["subsection"];

                $getSection =  "SELECT * FROM `books` WHERE $section = '$subsection'"; 

                $section_query = mysqli_query($conn, $getSection);
        ?>

        <h3><?php echo $subsection?></h3>

        <div class="row allbooks">

            <?php
                while($row = mysqli_fetch_array($section_query)){

                    $id = $row["id"];
                    $img = $row["img"];
                    $book = $row["books"];
                    $price = $row["price"];
            ?>

            <a class="col-6 col-md-3 col-xl-3 text-center" href="book.php?id=<?php echo $id?>">
                <img class="" src="img/<?php echo $img?>" alt="">
                <p class="text-dark"><?php echo $book?></p>
            </a>

            <?php }?>

        </div>

        <?php }else if(isset($_POST['search-btn'])){ ?>

        <div class="row allbooks">

            <?php
            $search = mysqli_real_escape_string($conn, $_POST['search_input']);

            $search_query = "SELECT * FROM books WHERE books LIKE '%$search%' OR authors LIKE '%$search%' OR genres LIKE '%$search%' ";

            $search_insert = mysqli_query($conn, $search_query);

            if(mysqli_num_rows($search_insert)>=1){

                while($row = mysqli_fetch_array($search_insert)){

                    $id = $row["id"];
                    $book = $row["books"];
                    $img = $row['img']
            ?>

            <a class="col-6 col-md-3 col-xl-2 text-center" href="book.php?id=<?php echo $id?>">
                <img class="" src="img/<?php echo $img?>" alt="">
                <p class="text-dark"><?php echo $book?></p>
            </a>

            <?php }}else{ echo "<h3>Nothing....:(</h3>";} ?>

        </div>

        <?php }?>

    </main>

    <?php include "footer.php" ?>
    
</body>

</html>