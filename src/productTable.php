<?php
  include 'dbh_inc.php';
?>

<!-- 
  Color code:
  orange - #FFAE5D
  gray - #404040
  red - #FF3232
  white - #FFFFFF
  -->
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>replit</title>
    <link href="dashStyle.css" rel="stylesheet" type="text/css" />
  </head>

  <body>
    <header class = "header">
        <div class = "title">
            <h2>ABC Seller's Dashboard</h2>
        </div>
    </header>

    <div class = "tab_select">
        <div class = "sidebar" style = "height: 100%">
          <div>
            <button type = "order_overview" class="btn"><a class="link" href="/orderHistory.html">Orders</a></button>
            <button type = "product_overview" class="btn"><a class="link" href="/productDetails.html">Products</a></button>
            <button type = "main_page" class="btn"><a class="link" href="/-----">Back to Main Page</a></button> <!--Needs link still-->
          </div>
          <div>
            <button type="profile" class="btn"><a class="link" href="/-----">Profile</a></button> <!--Needs link still-->
            <button type="log_out" class="btn"><a class="link" href="/-----">Log Out</a></button> <!--Needs link still-->
          </div>
        </div>
      </div>

    <main class = "main">
        <div class = button_select>
            <button id="myBtn1">Add Product</button>
        </div>

        <div id="addPModal" class = modal> <!--For Add Product Button-->
            <div class = modal-content>
                <span class="close">&times;</span>
                <h2>Add Product</h2>
                <form>
                    <label for="nameP">Name:</label>
                    <input type="text" id="nameP" name="nameP" required><br><br>

                    <label for="descriptionP">Description:</label>
                    <input type="text" id="descriptionP" name="descriptionP" required><br><br>

                    <label for="image_url">Image URL:</label>
                    <input type="text" id="image_url" name="image_url" required><br><br>

                    <label for="price">Price: $</label>
                    <input type="text" id="priceP" name="priceP" required><br>
                    
                    <p>Select Delivery Method:</p>
                    
                    <input type="radio" id="expedited" name="delivery_opt" value="expedited" required>
                    <label for="expeditied">Expedited Shipping</label><br>
                    <input type="radio" id="std" name="delivery_opt" value="std" required>
                    <label for="std">Standard/Flat Shipping</label><br>

                    <p>Select Category:</p>

                    <input type="radio" id="home" name="category_tag" value="home" required>
                    <label for="home">Home/Furniture</label><br>
                    <input type="radio" id="electronics" name="category_tag" value="electronics" required>
                    <label for="electronics">Electronics</label><br>
                    <input type="radio" id="fashion" name="category_tag" value="fashion" required>
                    <label for="fashion">Fashion and Apparel</label><br>
                    <input type="radio" id="hardware" name="category_tag" value="hardware" required>
                    <label for="hardware">DIY and Hardware</label><br>

                    <br><input type="submit">
                </form>
            </div>
        </div>
        
        <div class = "table_container">
            <table class = "order_table card">
              <tr class = "row">
                <th class="name_r">Name</th>
                <th class="description_r">Description</th>
                <th class="image_url_r">Image URL</th>
                <th class="price_r">Price (USD)</th>
                <th class="delivery_m_r">Quantity</th>
                <th class="categories_r">Category</th>
                <th class="delete_r">Delete?</th>
              </tr><br>
            </table>
        </div><br>
    </main>
    <script src="productAddDelScript.js"></script>
  </body>