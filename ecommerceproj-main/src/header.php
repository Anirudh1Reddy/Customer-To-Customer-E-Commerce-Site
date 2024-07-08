<div class="home">
  <div class="shopcart-parent">
    <div class="shopcart">
      <a class="header_homelink" href="http://<?php echo $_SERVER['HTTP_HOST']?>"><b>ABC</b></a>
    </div>
    <div class="frame-wrapper">
      <div class="vector-parent">
        <?php
          if( isset($_SESSION['userObj']) && !empty($_SESSION['userObj']) ){
        ?>
            <a class="sign_in_button" href="./dashboard">Dashboard</a>
            <a class="sign_in_button" href="./cart">Cart</a>
            <a class="sign_in_button" href="./signin">Sign Out</a>
        <?php
          } else {
        ?>
          <a class="sign_in_button" href="./signin">Sign In / Sign Up</a>

        <?php } ?>
      </div>
    </div>
  </div>

  <div class="frame-parent">
    <div class="group-parent">
      <div class="sign-in">Los Angeles, CA</div>
    </div>
    <input id="search_value" type="text" class="search-parent">
    <img id="search_button" class="vector-icon1" alt="" src="./img/magnifying-lens.png" />
  </div>
  <div class="fresh-parent">
    <?php
      // Retreiving corresponding data
      $result = $dbObj->retrieveAllCategories($conn);
      
      while($row = $result->fetch_assoc()){ ?>
        <div class="sign-in"><a class="category_link" href="/category?id=<?php echo $row['id']; ?>&name=<?php echo $row['name']; ?>"><?php echo $row['name']; ?></a></div>

    <?php  } ?>
  </div>