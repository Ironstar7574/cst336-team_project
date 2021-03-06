<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Flower Shop</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/heroic-features.css" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="css/styles.css" rel="stylesheet">


</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Flower Shop</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="cart.php">Cart</a>
                    </li>
                    <li>
                        <a href="account.php">Account</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header class="jumbotron hero-spacer">
            <h1>Flower Shop</h1>
            <p>Welcome to the CSUMB Flower Shop created and run by students for students.</p>
        </header>

        <hr>

        <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <center><h1>Our Products</h1></center>
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
      <center><div id="searchform">
            <h3>Search</h3>
            <form>
                Sort by:<br>
                Name <input type="radio" name="sortBy" value="name"checked> or 
                Price <input type="radio" name="sortBy" value="price" ><br>
                Ascending <input type="radio" name="ascDes" value="asc" checked> or
                Desending <input type="radio" name="ascDes" value="des"><br>
            

                Pricing
                <input type="number" name="maxprice" min="0" max="100" value="100">
                
                <br>
                Flowers 
                <input  type="checkbox" class="formCheckbox" name="flowers">
                <br>
                Bouquets
                <input  type="checkbox" class="formCheckbox" name="bouquets">
                <br>
                Plants
                <input  type="checkbox" class="formCheckbox" name="plants">
                <br>
                Other Products
                <input  type="checkbox" class="formCheckbox" name="other"><br>
                Search: 
                <input type="text" name="searchString">
                <input type="submit" value="Search">
            </form>
        </div>
        </center> 

        <div class="row text-center">
        <?=populateList()?>
        <!--
            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="http://placehold.it/800x500" alt="">
                    <div class="caption">
                        <h3>Feature Label</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="#" class="btn btn-primary">Buy Now!</a> <a href="#" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>
         -->
        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <center>
                        <a href="https://docs.google.com/presentation/u/1/d/1bkYpRsHVhn3ic_CkIcRgds0ttgq45RjL1UwC9RP7fZ8/htmlpresent">Link to User Story</a>
                        <br>
                        <p>Copyright &copy; CST 336 - Team Project - Flower Shop</p></center>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

<?php 

    function populateList()
    {

        require_once('database.php');
        session_start();
        $dbConn = new PDO("mysql:host=$host; dbname=$dbname; port=$port", $username, $password);
        $dbConn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart']=array();
        }
        $sql = "SELECT * FROM product";
        if ($_GET['maxprice'] != NULL)
        {
            $sql = $sql . " WHERE price <= " . $_GET['maxprice'];
        }
        else {
            $sql = $sql . " WHERE price >= 0";
        }
        
        if (isset($_GET['flowers']))
        {
            $sql = $sql . " AND productTypeId = '0'";
            if (isset($_GET['bouquets']))
            {
                $sql = $sql . " OR productTypeId = '1'";
            }
            if (isset($_GET['plants']))
            {
                $sql = $sql . " OR productTypeId = '2'";
            }
            if (isset($_GET['other']))
            {
                $sql = $sql . " OR productTypeId = '3'";
            }
        }
        else if (isset($_GET['bouquets']))
        {
            $sql = $sql . " AND productTypeId = '1'";
            if (isset($_GET['plants']))
            {
                $sql = $sql . " OR productTypeId = '2'";
            }
            if (isset($_GET['other']))
            {
                $sql = $sql . " OR productTypeId = '3'";
            }
        }
        else if (isset($_GET['plants']))
        {
            $sql = $sql . " AND productTypeId = '2'";
            if (isset($_GET['other']))
            {
                $sql = $sql . " OR productTypeId = '3'";
            }
        }
        else if (isset($_GET['other']))
        {
            $sql = $sql . " AND productTypeId = '3'";
        }

        if ($_GET['searchString'] != "")
        {
            $sql = $sql ." AND name LIKE '%". $_GET['searchString'] ."%'";
        }
        
        if ($_GET['sortBy'] == 'price')
        {
            $sql = $sql . " ORDER BY  price";
        }
        else {

            $sql = $sql . " ORDER BY  name";
        }
        if ($_GET['ascDes'] == 'des')
        {
            $sql = $sql . " DESC";
        }
        else {
            $sql = $sql . " ASC";
        }
        
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        
        while ($row = $stmt->fetch()) {
            echo '<div class="col-md-4 hero-feature">';
                echo '<div class="thumbnail">';
                echo '<img style="height:200px" src="product_img/'. $row["image"] .'" alt="">';
                    echo '<div class="caption">';
                        echo '<h3>'. $row["name"]. '</h3>';

                        echo '<div class="collapse" id="collapseExample">';
                        echo '<section class="card card-block">'.$row["description"].'</section>
                        </div>
                        <p>
                        <a href="add_to_cart.php?id='.$row["productId"].'" class="btn btn-primary" >$' .number_format((float) $row["price"], 2, '.', '') . '</a> 
                        <button class="btn btn-default" type="button" data-parent="#wrap" data-toggle="collapse" data-target=".collapse" aria-expanded="false" aria-controls="collapseExample">
                        More Info
                        </button>
                        </p>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
    }

?>
