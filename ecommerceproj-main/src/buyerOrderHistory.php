<?php
  $buyerId = unserialize( $_SESSION['userObj'] )->get_id();
  $result = $dbObj->retrieveOrdersByBuyerId($buyerId, $conn);
?>

  <!-- <div class = "tab_select">
      <div class = "sidebar" style = "height: 100%">
        <div>
          <button type = "order_overview" class="btn_dash"><a class="link" href="/orderHistory">Orders</a></button>
          <button type = "main_page" class="btn_dash"><a class="link" href="/-----">Back to Main Page</a></button>
        </div>
        <div>
          <button type = "profile" class="btn_dash"><a class="link" href="/-----">Profile</a></button>
          <button type = "log_out" class="btn_dash"><a class="link" href="/-----">Log Out</a></button>
        </div>
      </div>
    </div> -->

  <main class = "main">       
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

  <!--<script src = "buyerDashScript.js"></script>-->
</body>