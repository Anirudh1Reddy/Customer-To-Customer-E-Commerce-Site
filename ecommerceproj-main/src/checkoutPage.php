<?php
    $price = 0;

    if(isset($_SESSION['userObj']) && !empty($_SESSION['userObj'])){
        for($i = 0; $i < count($_SESSION['cart']); $i++){ 
            $price += ($dbObj->retrieveProductById($_SESSION['cart'][$i]['id'], $conn)['price'])*$_SESSION['cart'][$i]['quantity'];
        }
?>
<div class="checkoutPage">
    <form action="" class="checkout-form">
    <div class="payment">
        <div class="form-container">
            <h2 class="form-title">Payment Details</h2>
            <div class="input-line">
                <label for="name">Name on card</label>
                <input required="" type="text" id="name_card" placeholder="first and last name">
            </div>
            <div class="input-line">
                <label for="name">Card number</label>
                <input required="" type="text" id="number_card" placeholder="1111-2222-3333-4444">
            </div>
            <div class="input-container">
                <div class="input-line">
                    <label for="name">Expiring Date</label>
                    <input required="" type="text" id="expiration_card" placeholder="##-##">
                </div>
                <div class="input-line">
                    <label for="name">CVC</label>
                    <input required="" type="text" id="cvc_card" placeholder="***">
                </div>
            </div>
        </div>
    </div>

    <div class="address">
        <div class="form-container">
            <h2 class="form-title">Confirm Address</h2>
            <div class="input-line">
                <label for="name">Address</label>
                <input required="" type="text" id="address_address" placeholder="">
            </div>
            <div class="input-line">
                <label for="name">City</label>
                <input required="" type="text" id="city_address" placeholder="">
            </div>
            <div class="input-container">
                <div class="input-line">
                    <label for="name">State</label>
                    <input required="" type="text" id="state_address" placeholder="">
                </div>
                <div class="input-line">
                    <label for="name">Zip Code</label>
                    <input required="" type="text" id="zip_address" placeholder="">
                </div>
            </div>
            <div class="input-container">
                <div class="checkout_select">
                    <label for="shipping_methods">Choose a shipping method:</label>
                    <select name="shipping_methods" id="shipping_methods">
                        <option value="ups">UPS</option>
                        <option value="fedex">FedEx</option>
                        <option value="pickup">Pickup</option>
                    </select>
                </div> 
            </div>
        </div>
    </div>

    <div class="total-price">
        <div id="information_checkout" class="form-container">
            <h2 class="form-title">Total Price</h2>
            <div class="cart_price">$<?php echo number_format((float)$price, 2, '.', ''); ?></div>
            <button id="submit_checkout" class="submit_checkout" type="submit">Complete purchase</button>
        </div>
    </div>

    </form>


</div>

<?php } else { ?>
    <h2 class="sign_in_reminder">Please, sign-in to order.</h2>
<?php } ?>