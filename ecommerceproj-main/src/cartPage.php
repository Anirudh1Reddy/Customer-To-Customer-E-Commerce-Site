
<div class="cartPage">
<header>
    <div class="left">
        <h1>Viewing your <span>Cart</span></h1>
        <p></p>
        <a href="/checkout">
            <i class='bx bx-basket'></i>
            <span>Checkout</span>
        </a>
    </div>
    <img src="../img/picture1.jpg">
</header>

<div class="item-shop">
    <div id="cart_items" class="item-list">
        <?php 
            if(isset($_SESSION['userObj']) && !empty($_SESSION['userObj'])){
                $length = count($_SESSION['cart']);
                if($length <= 0){ ?>
                    <h2 class="sign_in_reminder">The cart is empty.</h2>
                <?php } else {
                    for($i = 0; $i < $length; $i++){ 
                        $result = $dbObj->retrieveProductById($_SESSION['cart'][$i]['id'], $conn);?>
                        <div class="item">
                            <a href="/product?id=<?php echo $_SESSION['cart'][$i]['id']; ?>"><img src="<?php echo $result['picture_url'];?>"></a>
                            <div class="info">
                                <div class="wrapper">
                                <a href="/product?id=<?php echo $_SESSION['cart'][$i]['id']; ?>"><h5><?php echo $result['name'];?></h5></a>
                                    <div class="btc">
                                        <p>Price per item: $<?php echo number_format((float)$result['price'], 2, '.', '');?></p>
                                        <p>Quantity: <?php echo $_SESSION['cart'][$i]['quantity'];?></p>
                                    </div>
                                    <button data-productid="<?php echo $_SESSION['cart'][$i]['id'];?>" class="remove">Remove From Cart</button>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
            }
        ?>

    </div>
</div>