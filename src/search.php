<?php
  // Database object initialization

use function PHPUnit\Framework\isEmpty;

  // Making sure 'query' field exists to avoid erros
  if(!isset($_GET['query'])){
    $_GET['query'] = '';
  }
?>
  <div class="the-most-popular-wrapper search_wrap">
    <div class="the-most-popular">Here is what we found for "<?php echo $_GET['query']; ?>"</div>
  </div>
  <div class="component-2-parent popular_products">
    <?php
      // Retreiving corresponding data
      $result = $dbObj->retrieveProductsByName($_GET['query'], $conn);

      while($row = $result->fetch_assoc()){ ?>
        <div class="component-2">
          <img class="frame-child" alt="" src="<?php echo $row['picture_url'] ?>" />

          <div class="samsung-galaxy-tab"><?php echo $row['name'] ?></div>
          <div class="parent">
            <div class="shopcart price_tag">$<?php echo number_format((float)$row['price'], 2, '.', ''); ?></div>
          </div>

          <a href="/product?id=<?php echo $row['id'] ?>"><button class="see_more_product">See more</button></a>
        </div>

    <?php  } ?>
  </div>
