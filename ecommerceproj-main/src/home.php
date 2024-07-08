<div class="home">
  <div class="we-picked-some-container">
    <span>
      <span class="cart">We picked some</span>
      <b class="b"> </b>
    </span>
    <b class="b">
      <span>cool things</span>
      <span> </span>
    </b>
    <span>
      <span class="cart">for you!</span>
      <b class="b1">*</b>
    </span>
  </div>

  <div class="the-most-popular-wrapper">
    <div class="the-most-popular">the most popular</div>
  </div>
  <div class="component-2-parent popular_products">
    <?php
      // Retreiving corresponding data
      $result = $dbObj->retrieveAllProducts($conn);

      while($row = $result->fetch_assoc()){ ?>
        <div class="component-2">
          <img class="frame-child" alt="" src="<?php echo $row['picture_url'] ?>" />

          <div class="samsung-galaxy-tab"><?php echo $row['name'] ?></div>
          <div class="parent">
            <div class="shopcart price_tag">$<?php echo number_format((float)$row['price'], 2, '.', ''); ?></div>
          </div>

          <a href="/product?id=<?php echo $row['id'] ?>"><button class="see_more_product">See more</button></a>
        </div>

    <?php  }
    ?>
  </div>

  <img class="component-1-icon" alt="" src="../img/Apple-Imac.png" />

  <div class="todays-most-recent-wrapper">
    <div class="the-most-popular">Today’s most recent</div>
  </div>
  <div class="component-2-parent">
    <?php
      // Retreiving corresponding data
      $result = $dbObj->retrieveAllProducts($conn);

      while($row = $result->fetch_assoc()){ ?>
        <div class="component-2">
          <img class="frame-child" alt="" src="<?php echo $row['picture_url'] ?>" />

          <div class="samsung-galaxy-tab"><?php echo $row['name'] ?></div>
          <div class="parent">
            <div class="shopcart price_tag">$<?php echo number_format((float)$row['price'], 2, '.', ''); ?></div>
          </div>

          <a href="/product?id=<?php echo $row['id'] ?>"><button class="see_more_product">See more</button></a>
        </div>

    <?php  }
    ?>
  </div>
</div>
