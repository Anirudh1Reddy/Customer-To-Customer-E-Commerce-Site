<?php
  $sellerId = unserialize( $_SESSION['userObj'] )->get_id();
  $result = $dbObj->retrieveOrdersBySellerId($sellerId, $conn);
?>

  <!-- 
  Color code:
  orange - #FFAE5D
  gray - #404040
  red - #FF3232
  white - #FFFFFF
  -->
    <!-- <div class = "tab_select">
        <div class = "sidebar" style = "height: 100%">
          <div>
            <button type = "order_overview" class="btn_dash"><a class="link" href="sellerOrderHistory.php">Orders</a></button>
            <button type = "product_overview" class="btn_dash"><a class="link" href="productTable.php">Products</a></button>
            <button type = "main_page" class="btn_dash"><a class="link" href="localhost/">Back to Main Page</a></button> 
          </div>
        </div>
      </div> -->

    <main class = "main">       
        <!--Table is created from old experimental code. Must be cleaned up and reworked to fit into the css file-->
        <!--<div> needed to hold table and style. Must hold border radius, color, width, height, overflow: scroll-->
      <div class = "table_container">
        <table class = "order_table card">
          <tr class = "row_dash">
            <th class="product_id_row">Product ID</th>
            <th class="buyer_id_row">Buyer_ID</th>
            <th class="quantity_row">Quantity</th>
            <th class="address_row">Address</th>
            <th class="order_status_row">Order Status</th>
          </tr>
          <?php
          while ($row = $result->fetch_assoc()) {
              echo "<tr class='row_dash'>";
              echo "<td>" . $row['product_id'] . "</td>";
              echo "<td>" . $row['buyer_id'] . "</td>";
              echo "<td>" . $row['quantity'] . "</td>";
              echo "<td>" . $row['address'] . "</td>";
              echo "<td>" . $row['status'] . "</td>";
              echo "</tr>";
          }
        ?>
        </table>
      </div>
    </main>

    <!--<script src="sellerDashScript.js"></script>-->
  </body>
