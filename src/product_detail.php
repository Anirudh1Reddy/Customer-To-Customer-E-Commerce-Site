<?php
use function PHPUnit\Framework\isEmpty;
  // Making sure 'id' field exists to avoid erros
  if(!isset($_GET['id'])){
    $_GET['id'] = '';
  }

  // Retreiving corresponding data
  $result = $dbObj->retrieveProductById($_GET['id'], $conn);
?>

<div class="small-container single-product">
  <div class="product_content">
    <img class="product_img" src="<?php echo $result['picture_url']; ?>" id="ProductImg" />
    <div class="product_info">
      <p>Home / <?php echo $result['name']; ?></p>
      <h1><?php echo $result['name']; ?></h1>
      <h4>$<?php echo number_format((float)$result['price'], 2, '.', ''); ?></h4>

      <div id="p_add_to_cart" class="p_product_add_to_cart">
        <?php
          // TODO: Refactor code to have less complicated and redundant logic (like on Cart page).

          $logged_in = false;
          $not_added = true;
          if( isset($_SESSION['userObj']) && !empty($_SESSION['userObj'])){
            $logged_in = true;

            for($i = 0; $i < count($_SESSION['cart']); $i++){
              if($_SESSION['cart'][$i]['id'] == $result['id']){
                $not_added = false;
              }
            }
          }

          if($not_added && $logged_in){
        ?>
          <input id="product_quantity" type="number" value="1" />
        
          <button id="add_to_cart" class="product_add_to_cart" data-productId="<?php echo $result['id']; ?>">
            <a href="" class="btn">Add To Cart</a>
            <p>
          </button>
        <?php } else if($logged_in) { ?>
          <p class="auth_success">This product is already in your cart.</p>
        <?php } ?>
      </div>

      <br /><br />
      <h3>Product Details</h3>
      <p class="product-details">
        <?php echo $result['description']; ?>
      </p>
    </div>
  </div>
</div>
