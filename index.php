<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="style.css" rel="stylesheet" type="text/css" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"/>
  <title>Ecommerce</title>
</head>
<body>
  <?php
    session_start();
    $uri = $_SERVER['REQUEST_URI'];
    include "./php/dbh_inc.php";
    include "./php/classes.php";
    $dbObj = new Database();
    $transformerObj = new Transformer();
    
    include "./src/header.php";

    if( $uri == '/' ){
      include "./src/home.php";


      // ! TO BE DELETED:
      // if( isset($_SESSION['userObj']) && !empty($_SESSION['userObj']) ){
      //   include_once "./php/classes.php";
      //   echo "</br></br>Current Account info:</br>" . unserialize( $_SESSION['userObj'] )->first_name;
      //   echo "</br>" . unserialize( $_SESSION['userObj'] )->last_name;
      // }

    } else if( str_contains($uri,'/product') ){
      include "./src/product_detail.php";
    } else if( str_contains($uri,'/search') ){
      include "./src/search.php";
    } else if( str_contains($uri,'/category') ){
      include "./src/category.php";
    } else if( $uri == '/cart' ){
      include "./src/cartPage.php";
    } else if( $uri == '/checkout' ){
      include "./src/checkoutPage.php";
    } else if( $uri == '/signup' ){
      include "./src/signup.php";
    } else if( $uri == '/signin' ){
      include "./src/signin.php";
    } else if( $uri == '/dashboard' ){
        if (unserialize($_SESSION['userObj'] )->user_type === '1') {
          include "./src/sellerOrderHistory.php";
      } elseif (unserialize($_SESSION['userObj'] )->user_type === '0') {
          include "./src/buyerOrderHistory.php";
      } else {
        echo "Invalid user type.";
      } 
    } else if( $uri == '/debug' ){
      $_SESSION['cart'] = array();

      // $_POST['request'] = 'add_to_cart';
      // $_POST['id'] = '2';
      // $_POST['quantity'] = '4';
      // include "./php/requests.php";
    } else {
      echo "This page doesnt exist: " . $uri;
    }

    include "./src/footer.php";
  ?>

  <script src="js/classes.js"></script>
  <script src="js/script.js"></script>
</body>
</html>