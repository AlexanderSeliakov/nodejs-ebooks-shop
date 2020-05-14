<nav class="navbar-light bg-light navbar navbar-expand-md">

    <a href="index.php" class="navbar-brand">My Shop</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarBookContent2"
        aria-controls="navbarBookContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse navbarContent" id="navbarBookContent2">
        <form class="input-group input-group-sm search-form w-50" action="all_books.php" method="POST">
            <input name="search_input" type="text" class="form-control" aria-describedby="search-btn"
                placeholder="insert name of a book or autor">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" id="search-btn"
                    name="search-btn">Search</button>
            </div>
        </form>

        <ul class="navbar-nav ml-auto">

            <?php 
                if(empty($_SESSION['username'])){

                echo
                '<li class="nav-item log-in dropdown">
                    <a href="user.php" class="nav-link dropdown-toggle" id="navbarDropdown2" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="outline: 0">Sign in
                    </a>
                        
                    
                    <div class="dropdown-menu log_in dropdown-menu-right" aria-labelledby="navbarDropdown2">

                        <form action="" method="POST">

                        <h6 class="">'.$_SESSION["message"].'</h6>

                        <div class="inpt">
                            <label for="login">Username</label>
                            <input class="form-control" id="username" name="username" type="text" required>
                        </div>
            
                        <div class="inpt mt-3">
                            <label for="password">Password</label>
                            <input class="form-control" id="password" name="password" type="password"  required autocomplete="on" >
                        </div>

                            <button class="btn mt-4 btn-outline-info" type="submit" name="log_in">log in</button>

                            <a href="registration.php" class="ml-auto" style="color:black">Register</a>

                        </form>

                        

                    </div>

                </li>';
                
                }else{
                    
                    echo '<li class="nav-item">
                            <a href="user.php" class="nav-link" >Hi, '.$_SESSION['username'].' </a>
                            </li>';
                }
                ?>

            <li class="nav-item ">
                <a href="cart.php" class="nav-link"><i class="fas fa-shopping-cart ">(<?php echo $amount;?>)</i></a>
            </li>

        </ul>
    </div>

</nav>

<nav class=" nav navbar-expand px-2  navbar-dark bg-dark">

    <div class="nav-scroller navbarContent" id="navbarBookContent">

        <ul class="navbar-nav">

            <li class="nav-item dropdown">
                <a href="sections_books.php?section=authors" class="nav-link">Autors</a>
            </li>

            <li class="nav-item dropdown">
                <a href="sections_books.php?section=genres" class="nav-link">Genres</a>
            </li>

            <li class="nav-item dropdown">
                <a href="sections_books.php?section=series" class="nav-link">Series</a>
            </li>

            <li class="nav-item ">
                <a href="contacts.php" class="nav-link ">Contact Us</a>
            </li>

        </ul>
    </div>
</nav>