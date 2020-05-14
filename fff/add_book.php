<?php include "head.php" ?>

    <?php
    if( $_SESSION['is_admin'] != 'admin' ){
    ?>
        <script>window.location = "user.php"</script>
    <?php
        die;
    }
    ?>

    <title>Adding new books</title>

</head>

<?php include "header.php" ?>
<?php include "add_book_func.php" ?>

<body>
    <form action="" method="POST" enctype="multipart/form-data" class="insert-form container my-5">

    <?php echo $suscess ?>

        <div class="form-group">
            <label for="add_book">Name of a Book</label>
            <input type="text" class="form-control" id="add_book" name="book">
        </div>

        <div class="form-group">
            <label for="add_author">Author</label>
            <input id="add_author" list="choose_author"  class="form-control" name="author"/>
            <datalist id="choose_author">
                <?php getItem ("authors") ?>
            </datalist>
        </div>

        <div class="form-group">
                <label for="add_ganre">Ganre</label>
                <input id="add_ganre" list="choose_ganre"  class="form-control" name="genre"/>
                <datalist id="choose_ganre">
                    <?php getItem ("genres") ?>
                </datalist>
        </div>

        <div class="form-group">
                <label for="add_series">Series</label>
                <input id="add_series" list="choose_series"  class="form-control" name="series"/>
                <datalist id="choose_series">
                    <?php getItem ("series") ?>
                </datalist>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" id="description"></textarea>
        </div>


        <div class="form-group">
            <label for="add_price">Price</label>
            <input type="text" class="form-control" name="price" id="add_price">
        </div>

        <div class="form-group">
                <label for="add_img">Add cover</label>
                <input type="file" name="img" class="form-control-file"id="add_img">
            </div>

        <button class="btn btn-outline-dark mt-2" type="submit" name="submit">Insert Now</button>

    </form>

    <?php include "footer.php" ?>
</body>

</html>