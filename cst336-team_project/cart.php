<?php
function showCart(){
// start session
session_start();
 
// connect to database
require_once('database.php');


 
// initialize objects

// get database connection
        $dbConn = new PDO("mysql:host=$host; dbname=$dbname; port=$port", $username, $password);
        $dbConn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// set page title
$page_title="Cart";

 
// contents will be here 
if(count($_SESSION['cart'])>0){
 
    // get the product ids
    $ids = array();
    foreach($_SESSION['cart'] as $id=>$value){
        array_push($ids, $id);
       
    }

    // query to select products
    $sql= "SELECT * FROM  product  WHERE productId IN (";
    
    for ($i = 0; $i < (sizeof($ids) - 1); $i++) {
    $sql = $sql  .$ids[$i] .",";
	}
	
    $sql = $sql . $ids[sizeof($ids) -1] .") ORDER BY name";

    // prepare query statement
    $stmt = $dbConn->prepare($sql);

    // execute query
    $stmt->execute();
 
    $total=0;
    $item_count=0;
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $quantity=$_SESSION['cart'][$id]['quantity'];
        $sub_total=$price*$quantity;
        
        // =================
        echo "<div class='cart-row'>";
            echo "<div class='col-md-8'>";
 
                echo "<div class='product-name m-b-10px'><h4>{$name}</h4></div>";
 
                // delete from cart
                echo "<a href='remove_from_cart.php?id={$id}' class='btn btn-default'>";
                    echo "Delete";
                echo "</a>";
            echo "</div>";
 
            echo "<div class='col-md-4'>";
                echo "<h4>&#36;" . number_format($price, 2, '.', ',') . "</h4>";
            echo "</div>";
        echo "</div>";
        // =================
 
        $item_count += $quantity;
        $total+=$sub_total;
    }
 
    echo "<div class='col-md-8'></div>";
    echo "<div class='col-md-4'>";
        echo "<div class='cart-row'>";
            echo "<h4 class='m-b-10px'>Total ({$item_count} items)</h4>";
            echo "<h4>&#36;" . number_format($total, 2, '.', ',') . "</h4>";
            echo "<a href='index.php' class='btn btn-success m-b-10px'>";
                echo "<span class='glyphicon glyphicon-shopping-cart'></span> Return to shop";
            echo "</a>";
        echo "</div>";
    echo "</div>";
 
}
 
// no products were added to cart
else{
    echo "<div class='col-md-12'>";
        echo "<div class='alert alert-danger'>";
            echo "No products found in your cart!";
        echo "</div>";
        echo "<a href='index.php' class='btn btn-default'>";
                    echo "Home";
                echo "</a>";
    echo "</div>";
}
}
 
?>

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
                <a class="navbar-brand" href="index.php">Flower Shop</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <!----<a href="#">About</a>---->
                    </li>
                    <li>
                        <a href="cart.php">Cart</a>
                    </li>
                    <li>
                        <!----<a href="account.php">Account</a>----->
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
      <?=showCart()?>

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
