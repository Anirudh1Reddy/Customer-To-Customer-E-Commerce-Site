<?php
  $server_name = "localhost";
  $server_username = "root";
  $server_password = "root";
  $db_name = 'ecommerce';

  /**
   * This function creates a connection to database based on the pre-initialized values in dbh_inc.php file.
   *
   * @param string $server_name Name of the server where database is stored
   * @param string $server_username Username for MySQL
   * @param string $server_password Password for MySQL
   * @param string $db_name Database name in MySQL
   * 
   * @return object $conn Mysqli connection object
   */
  function connectDB($server_name, $server_username, $server_password, $db_name ){
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
  }
  
  $conn = connectDB($server_name, $server_username, $server_password, $db_name );

  ?>