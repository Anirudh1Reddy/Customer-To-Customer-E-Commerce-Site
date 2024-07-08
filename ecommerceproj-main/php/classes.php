<?php

//INSERT INTO `products` (`id`, `name`, `description`, `picture_url`, `rating`, `price`, `delivery_methods`, `categories`, `total_number_ordered`, `seller_id`) VALUES (NULL, 'Test product 2', 'This is a dummy product, to be deleted later on', 'https://unruhfurniture.com/wp-content/uploads/2018/02/Product-Desk-Olivia-Desk-No-Background-1-W1600.png', '0', '10', 'method2', '1|3', '0', '14'); 

use function PHPUnit\Framework\isEmpty;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * User classs is a set of properties that correspond to the current user after sign-in procedure is completed
 *
 * @author Ruslan Abdulin <arusla747@gmail.com>
 * @since April 19, 2024
 * @access public 
 */
class User{
  /**
   * Identification number
   * @var int
   */
  private $id;
  /**
   * Integer that determines whether a user is a buyer or a seller
   * @var int
   */
  public $user_type;
  /**
   * First name
   * @var string
   */
  public $first_name;
  /**
   * Last name
   * @var string
   */
  public $last_name;
  /**
   * Email
   * @var string
   */
  public $email;
  /**
   * String that can be transformed into array with ids of products rated by this user
   * @var string
   */
  public $products_rated;

  function __construct($id, $user_type, $first_name, $last_name, $email, $products_rated){
    $this->id = $id;
    $this->user_type = $user_type;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->email = $email;
    $this->products_rated = $products_rated;
  }

  /**
   * This function returns id field from the User class
   * 
   * @return string Id of the corresponding user in DB
   */
  public function get_id(){
    return $this->id;
  }

}

/**
 * Responder is a class that handles requests received via JS promises
 *
 * @author Ruslan Abdulin <arusla747@gmail.com>
 * @since April 19, 2024
 * @access public 
 */
class Responder{
  /**
   * Boolean that represents whether request is successfully satisfied or not
   * @var bool
   */
  public $status;
  /**
   * String that holds message to be utilized for better user-experience
   * @var string
   */
  public $message;

  function __construct(){
    $this->status = true;
    $this->message = "";
  }

  /**
   * This function prints out given array in a JSON format
   *
   * @param string $data array to be transformed into a json syntax and printed out
   *
   * @return string JSON string resulted from an array conversion.
   */
  public function printJSON($data){
    echo json_encode($data);
  }

  /**
   * Adds a new product to the $_SESSION['cart'] as long
   * as it hasn't been added yet.
   *
   * @return string JSON string with status and message.
   */
  public function add_to_cart(){
    try{
      // Making sure there is no duplicates
      for($i = 0; $i < count($_SESSION['cart']); $i++){
        if($_SESSION['cart'][$i]['id'] == $_POST['id']){
          throw new Exception('Such an item has already been added to the cart.');
        }
      }

      array_push($_SESSION['cart'], array('id'=>$_POST['id'], 'quantity'=>$_POST['quantity']));

      $result = array('status'=>true,'message'=>'Successfully added to cart');
    } catch ( Exception $e ){
      $result = array('status'=>false, 'message'=> $e->getMessage());
    }

    $this->printJSON($result);
  }

  /**
   * Removes a product from the $_SESSION['cart'],
   * If an item is not in the array => throws an Exception.
   *
   * @return string JSON string with status and message.
   */
  public function remove_from_cart(){
    try{
      $index = -1;

      // Finding the product in array
      for($i = 0; $i < count($_SESSION['cart']); $i++){
        if($_SESSION['cart'][$i]['id'] == $_POST['id']){
          $index = $i;
          continue;
        }
      }

      if($index == -1){
        throw new Exception('Error: no such item in $_SESSION');
      }

      array_splice($_SESSION['cart'], $index, 1 );

      $result = array('status'=>true,'message'=>'Removed.');
    } catch ( Exception $e ){
      $result = array('status'=>false, 'message'=> $e->getMessage());
    }

    $this->printJSON($result);
  }

  /**
   * Inserts a new order to the DB based off $_SESSION['cart']; 
   * After its execution, $_SESSION['cart'] is set to empty array.
   *
   * @return string JSON string with status and message.
   */
  public function checkout($dbObj, $conn){
    try{

      // Generate orders
      for($i = 0; $i < count($_SESSION['cart']); $i++){ 
        $sellerId = $dbObj->retrieveProductById($_SESSION['cart'][$i]['id'], $conn)['seller_id'];

        $dataArr = array(
          'product_id' => $_SESSION['cart'][$i]['id'],
          'seller_id' => $sellerId,
          'buyer_id' => unserialize( $_SESSION['userObj'] )->get_id(),
          'quantity' => $_SESSION['cart'][$i]['quantity'],
          'paid_to_seller' => 0,
          'paid_by_buyer' => 0, 
          'delivery_method' => $_POST['shipping_method'],
          'status' => 'Placed',
          'payment_details' => $_POST['card_info'],
          'address' => $_POST['address_info']
        );
        
        $result = $dbObj->addOrder( $dataArr, $conn);
      }

      // Empty the cart
      $_SESSION['cart'] = array();

      // TODO: Purchase SMTP and provide credentials in order to send a confirmation email to buyer.
      /*
      // Start with PHPMailer class
      require_once './vendor/autoload.php';
      // create a new object
      $mail = new PHPMailer();
      // configure an SMTP
      $mail->isSMTP();
      $mail->Host = 'live.smtp.mailtrap.io';
      $mail->SMTPAuth = true;
      $mail->Username = 'api';
      $mail->Password = '1a2b3c4d5e6f7g';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      $mail->setFrom('confirmation@registered-domain', 'Your Hotel');
      $mail->addAddress('receiver@gmail.com', 'Me');
      $mail->Subject = 'Thanks for choosing Our Hotel!';
      // Set HTML 
      $mail->isHTML(TRUE);
      $mail->Body = '<html>Hi there, we are happy to <br>confirm your booking.</br> Please check the document in the attachment.</html>';
      $mail->AltBody = 'Hi there, we are happy to confirm your booking. Please check the document in the attachment.';
      // add attachment 
      // just add the '/path/to/file.pdf'
      $attachmentPath = './confirmations/yourbooking.pdf';
      if (file_exists($attachmentPath)) {
          $mail->addAttachment($attachmentPath, 'yourbooking.pdf');
      }

      // send the message
      if(!$mail->send()){
        throw new Exception('There was an error with email sending.');
      } */

      $result = array('status'=>true,'message'=>'Order has been placed');
      
    } catch ( Exception $e ){
      $result = array('status'=>false, 'message'=> $e->getMessage());
    }

    $this->printJSON($result);
  }

  /**
   * Check if data is valid, check if username is not taken.
   * In case everything is correct => create an account and insert it to database
   * Return corresponding message and status
   *
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return string JSON string with status and message.
   */
  public function sign_up($conn){
    /* Input:  fname, lname, uname, psw,cpsw, email, accType_seller = binary, accType_buyer = binary.
    */
    $fname = isset($_POST['fname']) && !empty($_POST['fname']) ? $_POST['fname'] : false;
    $lname = isset($_POST['lname']) && !empty($_POST['lname']) ? $_POST['lname'] : false;
    $uname = isset($_POST['uname']) && !empty($_POST['uname']) ? $_POST['uname'] : false;
    $psw = isset($_POST['psw']) && !empty($_POST['psw']) ? $_POST['psw'] : false;
    $cpsw = isset($_POST['cpsw']) && !empty($_POST['cpsw']) ? $_POST['cpsw'] : false; // confirm passowrd
    $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : false;
    $accType_seller = isset($_POST['accType_seller']) && !empty($_POST['accType_seller']) ? $_POST['accType_seller'] : false;
    $accType_buyer = isset($_POST['accType_buyer']) && !empty($_POST['accType_buyer']) ? $_POST['accType_buyer'] : false;
    $accType = $accType_seller;

    
    // Check if the input data is valid
    if( !$accType_seller && !$accType_buyer ){
      $this->message = "No account type chosen";
      $this->status = false;
    }else if( !$fname || !$lname || !$uname || !$psw || !$cpsw || !$email){
      $this->message = "One of the text fields is empty.";
      $this->status = false;
    } else if( $psw != $cpsw ){
      $this->message = "Passwords don't match";
      $this->status = false;
    }

    // Check if username is taken already
    $stmt = $conn->prepare("SELECT username FROM users WHERE username=?");
    $stmt->bind_param('s', $uname);
    $stmt->execute();
    $stmt->store_result();
    $username_duplicate = NULL;
    $stmt->bind_result($username_duplicate);
    if($stmt->num_rows == 1) {
      $this->status = false;
      $this->message = "This username is taken already";
    }

    if($this->status){

      // Encode the password
      $password_hash = password_hash($psw, PASSWORD_DEFAULT);

      $sql = "INSERT INTO users (first_name, last_name, user_type, username, password, email) VALUES ('".$fname."', '".$lname."', ".$accType.", '".$uname."', '".$password_hash."', '".$email."')";

      $result = mysqli_query($conn, $sql);

      if(!$result){
        $this->status = false;
        $this->message = "MySQL error (Server-side error)";
      }

    }

    $this->printJSON(array("status" => $this->status, "message" => $this->message));
  }

  /**
   * Check if data is valid, decode password and check if username with password match each other.
   * In case everything is correct => return corresponding data.
   *
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return string JSON string with status and message.
   */
  public function sign_in($conn){
    $this->status = true;
    $this->message = "";

    // Check if username and password are not empty
    $uname = isset($_POST['uname']) ? $_POST['uname'] : '';
    $psw = isset($_POST['psw']) ? $_POST['psw'] : '';

    // Validation on whether empty or not
    if( !isset($_POST['uname']) || empty($_POST['uname']) ){
      $this->message = "Username cannot be empty!";
      $this->status = false;
    }

    if( !isset($_POST['psw']) || empty($_POST['psw']) ){
      $this->message = "Password cannot be empty! ";
      $this->status = false;
    }

    // If validation passed
    if($this->status){
      $stmt = $conn->prepare("SELECT id, first_name, last_name, user_type, email, password, products_rated FROM users WHERE username=?");
      $stmt->bind_param('s', $uname);
      $stmt->execute();
      $result = $stmt->get_result();

      // Check on the case when uname doesn't exist
      if($result->num_rows == 1) {
        $data = $result->fetch_assoc();
      } else {
        $this->message = "There is no such user";
        $this->status = false;

        $this->printJSON(
          array(
            'status' => $this->status,
            'message' => $this->message
          )
        );
        return;
      }

      if( !password_verify($psw, $data['password']) ){
        $this->message = "Password is incorrect";
        $this->status = false;

        // Validation error
        echo json_encode(
          array(
            'status' => $this->status,
            'message' => $this->message
          )
        );
      } else {
        // Success
        $this->printJSON(array(
          'status' => $this->status,
          'id' => $data['id'],
          'user_type' => $data['user_type'],
          'first_name' => $data['first_name'],
          'last_name' => $data['last_name'],
          'email' => $data['email'],
          'products_rated' => $data['products_rated']
        ));
      }
    } else{
      // Error before validation  
      $this->printJSON(array(
        'status' => $this->status,
        'message' => $this->message
      ));
    }
  }

  /**
   * This function to be called after successful sign_in() execution.
   * Retrieved data from sign_in() to be transformed into a new User instance,
   * PHP session to be filled with the instance object.
   *
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return void
   */
  public function init_session(){
    $currentUser = new User($_POST['id'], $_POST['user_type'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['products_rated']);

    $_SESSION['userObj'] = serialize($currentUser);
    $_SESSION['cart'] = array();
  }

  /**
   * This function to be called when user wants to sign-out
   * PHP session to be cleared. All cookies to be deleted.
   * PHP session to be destroyed.
   *
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return void
   */
  public function destroy_session(){
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
  }
}

/**
 * Database classs is a set of database related methods
 *
 * @author Ruslan Abdulin <arusla747@gmail.com>
 * @since April 25, 2024
 * @access public 
 */
class Database{

  /**
   * This function forces warnings to throw an exception;
   * To be utilized for error-handling in other methods.
   *
   * @access private
   *
   * @return void
   */
  private function exception_error_handler(int $errno, string $errstr, string $errfile = null, int $errline) {
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
  }

  /**
   * This function takes in an id of a user and returns all the data 
   * that corresponds to that user in Database
   *
   * @param int $id is an identification number used to retreive corresponding information
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return array array with the retreived data
   */
  public function retrieveUser( $id, $conn ){
      $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
      $stmt->bind_param('s', $id);
      $stmt->execute();
      $result = $stmt->get_result();

      // Check if a user is succesfully retrieved
      if($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $data['status'] = true;
        return $data;
      } else {
        return array('status'=>false);
      }
  }

  /**
   * This function takes in an id of a product and returns all the data 
   * that corresponds to that user in Database
   *
   * @param int $id is an identification number used to retreive corresponding information
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return array array with the retreived data
   */
  public function retrieveProductById( $id, $conn ){
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a product is succesfully retrieved
    if($result->num_rows == 1) {
      $data = $result->fetch_assoc();
      $data['status'] = true;
      return $data;
    } else {
      return array('status'=>false);
    }
  }

  /**
   * This function takes in a seller's id and returns all products of
   * that corresponds seller to that in Database.
   * To loop over returned object, use: 
   * while($row = $test->fetch_assoc()){
   *    YOUR_CODE
   * }
   *
   * @param int $sellerId is an identification number used to retreive corresponding information
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return object object with the retreived data
   */
  public function retrieveProductsOfSeller( $sellerId, $conn ){
    $stmt = $conn->prepare("SELECT * FROM products WHERE seller_id=?");
    $stmt->bind_param('s', $sellerId);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
  }

  /**
   * This function returns all products available in Database.
   * ```php
   * // To loop over returned object, use:
   * $dbObj = new Database();
   * $result = $dbObj->retrieveAllProducts($conn);
   * 
   * while($row = $result->fetch_assoc()){
   *    YOUR_CODE;
   * }
   * ```
   *
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return object object with the retreived data
   */
  public function retrieveAllProducts( $conn ){
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
  }

  /**
   * This function returns all products that fall under the category provided.
   * ```php
   * // To loop over returned object, use: 
   * $dbObj = new Database();
   * $result = $dbObj->retrieveProductsByCategory(YOUR_ID, $conn);
   * 
   * while($row = $result->fetch_assoc()){
   *   YOUR_CODE;
   * }
   * ```
   *
   * @param int $categoryId is an Identification number of a category used to look for products
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return object object with the retreived data
   */
  public function retrieveProductsByCategory($categoryId, $conn){
    $stmt = $conn->prepare("SELECT * FROM `products` WHERE categories LIKE '%".$categoryId."%'");
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
  }

  /**
   * This function returns all products which name field partially or completely matching provided querry.
   * ```php
   * // To loop over returned object, use: 
   * $dbObj = new Database();
   * $result = $dbObj->retrieveProductsByName(YOUR_NAME, $conn);
   * 
   * while($row = $result->fetch_assoc()){
   *   YOUR_CODE;
   * }
   * ```
   *
   * @param string $query is a text that 'name' field of retrieved products will partially or completely match.
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return object object with the retreived data
   */
  public function retrieveProductsByName($query, $conn){
    $stmt = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%".$query."%'");
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
  }

  /**
   * This function inserts a new product into Database.
   * ```php
   * // Example of usage:
   * $dbObj = new Database();
   * $data = array('name'=>'Insert test', 
   *  'description'=>'dummy test of the method', 
   *  'picture_url'=>'https://cdn.thewirecutter.com/wp-content/media/2022/10/laptopstopicpage-2048px-2102-2x1-1.jpg?auto=webp&quality=75&   * crop=2:1&width=1024&dpr=2',
   *  'price'=> 12,
   *  'delivery_methods'=>'pickup | fedex',
   *  'categories'=>'laptop|devices',
   *  'seller_id'=>16);
   * 
   * // if $result['status'] is false, it will have a $result['exception'] field.
   * $result = $dbObj->addProduct( $data, $conn);
   * 
   * // Print out result values
   * var_dump($result)
   * ```
   *
   * @param array $dataArr is an associative array that holds data to be inserted.
   * @param object $conn is a mysql connection object that conducts interaction with database.
   *
   * @return array array that holds status info and exception message info if exception took place.
   */
  public function addProduct( $dataArr, $conn ){

    // Force warnings to trigger error in the try catch
    set_error_handler($this->exception_error_handler(...));

    try{
      // Prepare data
      $dataArr['delivery_methods'] = preg_replace('/\s+/', '', $dataArr['delivery_methods']);
      $dataArr['categories'] = preg_replace('/\s+/', '', $dataArr['categories']);

      // Process the request
      $stmt = $conn->prepare("INSERT INTO `products` (`name`, `description`, `picture_url`, `rating`, `price`, `delivery_methods`, `categories`, `total_number_ordered`, `seller_id`) VALUES ( '".$dataArr['name']."', '".$dataArr['description']."', '".$dataArr['picture_url']."', '0', '".$dataArr['price']."','".$dataArr['delivery_methods']."', '".$dataArr['categories']."', '0', '".$dataArr['seller_id']."')");
      $stmt->execute();

      $status = array('status'=>true);
    } catch( Exception $e ){
      $status = array('status'=>false, 'exception'=> $e->getMessage());
    } finally {
      restore_error_handler();
      return $status;
    }
    
  }

  /**
   * This function takes in an id of a product, deletes it
   * from database, and returns a status of the request.
   *
   * @param int $id is an identification number used to delete corresponding row
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return array array that holds status info and exception message info if exception took place.
   */
  public function deleteProduct( $id, $conn ){

    // Force warnings to trigger error in the try catch
    set_error_handler($this->exception_error_handler(...));

    try{
      $stmt = $conn->prepare("DELETE FROM `products` WHERE id=?");
      $stmt->bind_param('s', $id);
      $stmt->execute();

      if($stmt->affected_rows == 0){
        throw new Exception('No product with such an id');
      }

      $status = array('status'=>true);
    } catch( Exception $e ){
      $status = array('status'=>false, 'exception'=> $e->getMessage());
    } finally {
      restore_error_handler();
      return $status;
    }
    
  }

  /**
   * This function takes in an id of an order and 
   * returns the correspodning information it holds.
   *
   * @param int $id is an identification number used to find corresponding row
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return array array that holds status and order information.
   */
  public function retrieveOrder( $id, $conn ){
    $stmt = $conn->prepare("SELECT * FROM `orders` WHERE id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
      $data = $result->fetch_assoc();
      $data['status'] = true;
      return $data;
    } else {
      return array('status'=>false);
    }
  }

  /**
   * This function returns all orders that have corresponding seller_id value.
   * ```php
   * // To loop over returned object, use: 
   * $dbObj = new Database();
   * $result = $dbObj->retrieveOrdersBySellerId(YOUR_ID, $conn);
   * 
   * while($row = $result->fetch_assoc()){
   *   YOUR_CODE;
   * }
   * ```
   *
   * @param int $categoryId is an Identification number of a seller_id used to look for orders
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return object object with the retreived data
   */
  public function retrieveOrdersBySellerId( $sellerId, $conn ){
    $stmt = $conn->prepare("SELECT * FROM `orders` WHERE seller_id=?");
    $stmt->bind_param('s', $sellerId);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
  }

  /**
   * This function returns all orders that have corresponding buyer_id value.
   * ```php
   * // To loop over returned object, use: 
   * $dbObj = new Database();
   * $result = $dbObj->retrieveOrdersByBuyerId(YOUR_ID, $conn);
   * 
   * while($row = $result->fetch_assoc()){
   *   YOUR_CODE;
   * }
   * ```
   *
   * @param int $buyerId is an Identification number of a seller_id used to look for orders
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return object object with the retreived data
   */
  public function retrieveOrdersByBuyerId( $buyerId, $conn ){
    $stmt = $conn->prepare("SELECT * FROM `orders` WHERE buyer_id=?");
    $stmt->bind_param('s', $buyerId);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
  }

  /**
   * This function inserts a new order into Database.
   * ```php
   * // Example of usage:
   * $dbObj = new Database();
   * $dataArr = array(
   *  'product_id' => 4,
   *  'seller_id' => 15,
   *  'buyer_id' => 3,
   *  'quantity' => 2,
   *  'paid_to_seller' => 0,
   *  'paid_by_buyer' => 1, 
   *  'delivery_method' => 'usps',
   *  'status' => 'Delivered',
   *  'payment_details' => 'some string',
   *  'address' => '123 magnolia blvd, Northridge, CA 91304'
   * );
   *
   * $result = $dbObj->addOrder( $dataArr, $conn);
   * 
   * // Print out result values
   * var_dump($result)
   * ```
   *
   * @param array $dataArr is an associative array that holds data to be inserted.
   * @param object $conn is a mysql connection object that conducts interaction with database.
   *
   * @return array array that holds status info and exception message info if exception took place.
   */
  public function addOrder( $dataArr, $conn ){
    // Force warnings to trigger error in the try catch
    set_error_handler($this->exception_error_handler(...));

    try{
      // Process the request
      $stmt = $conn->prepare("INSERT INTO `orders`( `product_id`, `seller_id`, `buyer_id`, `quantity`, `paid_to_seller`, `paid_by_buyer`, `delivery_method`, `status`, `payment_details`, `address`) VALUES ( '".$dataArr['product_id']."', '".$dataArr['seller_id']."', '".$dataArr['buyer_id']."', '".$dataArr['quantity']."', '".$dataArr['paid_to_seller']."','".$dataArr['paid_by_buyer']."', '".$dataArr['delivery_method']."','".$dataArr['status']."','".$dataArr['payment_details']."','".$dataArr['address']."')");
      $stmt->execute();

      $status = array('status'=>true);
    } catch( Exception $e ){
      $status = array('status'=>false, 'exception'=> $e->getMessage());
    } finally {
      restore_error_handler();
      return $status;
    }
    
  }

   /**
   * This function returns all categories available in Database.
   * ```php
   * // To loop over returned object, use:
   * $dbObj = new Database();
   * $result = $dbObj->retrieveAllCategories($conn);
   * 
   * while($row = $result->fetch_assoc()){
   *    YOUR_CODE;
   * }
   * ```
   *
   * @param object $conn is a mysql connection object that conducts interaction with database
   *
   * @return object object with the retreived data
   */
  public function retrieveAllCategories( $conn ){
    $stmt = $conn->prepare("SELECT * FROM `categories`");
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
  }

}

/**
 * Transformer classs is a set of methods used to convert / modify data.
 *
 * @author Ruslan Abdulin <arusla747@gmail.com>
 * @since April 25, 2024
 * @access public 
 */
class Transformer{

  /**
   * This function takes in an array and converts it to
   * a string which can be later stored in database
   *
   * @param array $arr is an array to be transformed
   *
   * @return string string version of the array
   */
  public function arrayToString($arr){
    return implode("|", $arr);
  }

  /**
   * This function takes in a string and converts it to
   * an array which can be later stored in database
   *
   * @param string $str is a string to be transformed
   *
   * @return array array version of the string
   */
  public function stringToArray($str){
    // remove all the white spaces
    $str = $this->prepString($str);
    return explode("|", $str);
  }

  /**
   * This function takes in a string and removes
   * all white spaces from it.
   *
   * @param string $str is a string to be transformed
   *
   * @return string transformed string
   */
  public function prepString($str){
    return preg_replace('/\s+/', '', $str);
  }

}

?>