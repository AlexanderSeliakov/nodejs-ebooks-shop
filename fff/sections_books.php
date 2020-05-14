<?php include "head.php" ?>

<title>all books</title>

<link rel="stylesheet" href="css/all_books.css" media="all" type="text/css">

</head>

<body>

    <?php include "header.php" ?>

    <main class="container">


        <?php 

            if(isset($_GET["section"])){

            $section = $_GET['section'];

            $getSection =  "SELECT * FROM `$section` ORDER BY '$section'"; 

            $section_query = mysqli_query($conn, $getSection);

                while($row = mysqli_fetch_array($section_query)){

                    $id = $row["id"];
                    $column = $row[$section];

        ?>

        <div class="section my-5">

            <div class=" mx-auto">
                <h3 class="col-12">
                    <a class="text-dark" href="all_books.php?section=<?php echo $section?>&subsection=<?php echo $column?>"><?php echo $column?></a>
                </h3>
            </div>

            <div class="nav-link nav-scroller p-0 col-12">

                <ul class="list-group list-group-horizontal">
                    <?php 

                        $getBook =  "SELECT * FROM `books` WHERE $section = '$column'"; 

                        $book_query = mysqli_query($conn, $getBook);

                            while($row = mysqli_fetch_array($book_query)){

                                $id = $row["id"];
                                $book = $row["books"];
                                $img = $row['img']
                    
                    ?>

                    <a class="" data-toggle="tooltip" data-placement="auto" title="<?php echo $book?>"
                        href="book.php?id=<?php echo $id?>">
                        <img class="list-group-item" src="img/<?php echo $img?>" alt="">
                    </a>

                    <?php }?>

                </ul>

            </div>

        </div>

        <?php }}?>

    </main>

    <?php include "footer.php" ?>
</body>

</html>