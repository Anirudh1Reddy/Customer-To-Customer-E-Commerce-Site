<?php
// To runt unit test: ./vendor/bin/phpunit ./tests/UnitTest.php

declare(strict_types=1);
include_once "./php/classes.php"; // will be opened from index.php perspective


use PHPUnit\Framework\TestCase;

final class UnitTest extends TestCase{

  public function testTransformer_arrayToString(){
    $transformer = new Transformer();

    $array = [1,2,3];
    $imarr = $transformer->arrayToString($array);

    $this->assertSame('1|2|3', $imarr);
  }

  public function testTransformer_stringToArray(){
    $transformer = new Transformer();

    $str = "1|2|3";
    $exarr = $transformer->stringToArray($str);

    $this->assertSame('2', $exarr[1]);
  }

  public function testDatabase_retrieveProductsByName(){
    include_once './php/dbh_inc.php';
    $dbObj = new Database();
    $result = $dbObj->retrieveProductsByName("test", $conn);

    $row = $result->fetch_assoc();
    $this->assertSame('1', $row[0]->id);
  }

  public function testResponder_singUp1(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );
    
    $responderObj = new Responder();
    $_POST['fname'] = 'Test';
    $_POST['lname'] = 'Test';
    $_POST['uname'] = 'testOne';
    $_POST['psw'] = '123456';
    $_POST['cpsw'] = '123456';
    $_POST['accType_seller'] = 1;
    $_POST['accType_buyer'] = 0;

    $result = $responderObj->sign_up($conn);

    // Succesful example
    $this->assertSame(true, $result['status']);
  }

  public function testResponder_singUp2(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );
    
    $responderObj = new Responder();
    $_POST['fname'] = 'Test';
    $_POST['lname'] = 'Test';
    $_POST['uname'] = 'testOne';
    $_POST['psw'] = '123456';
    $_POST['cpsw'] = '123456';
    $_POST['accType_seller'] = 0;
    $_POST['accType_buyer'] = 0;

    $result = $responderObj->sign_up($conn);

    $this->assertSame("No account type chosen", $result['message']);
  }

  public function testResponder_singUp3(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );
    
    $responderObj = new Responder();
    $_POST['fname'] = 'Test';
    $_POST['lname'] = '';
    $_POST['uname'] = 'testOne';
    $_POST['psw'] = '123456';
    $_POST['cpsw'] = '123456';
    $_POST['accType_seller'] = 1;
    $_POST['accType_buyer'] = 0;

    $result = $responderObj->sign_up($conn);

    $this->assertSame("One of the text fields is empty.", $result['message']);
  }

  public function testResponder_singUp4(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );
    
    $responderObj = new Responder();
    $_POST['fname'] = 'Test';
    $_POST['lname'] = 'Test';
    $_POST['uname'] = 'testOne';
    $_POST['psw'] = '123456';
    $_POST['cpsw'] = '1234567';
    $_POST['accType_seller'] = 1;
    $_POST['accType_buyer'] = 0;

    $result = $responderObj->sign_up($conn);

    $this->assertSame("Passwords don't match", $result['message']);
  }

  public function testResponder_singUp5(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );
    
    $responderObj = new Responder();
    $_POST['fname'] = 'Test';
    $_POST['lname'] = 'Test';
    $_POST['uname'] = 'testOne';
    $_POST['psw'] = '123456';
    $_POST['cpsw'] = '123456';
    $_POST['accType_seller'] = 1;
    $_POST['accType_buyer'] = 0;

    $result = $responderObj->sign_up($conn);

    // This test is run after the intial succesful one,
    // Thereby, the same user is being created
    $this->assertSame("This username is taken already", $result['message']);
  }

  public function testResponder_singUp6(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );
    
    $responderObj = new Responder();
    $_POST['fname'] = 'Test';
    $_POST['lname'] = 'Test';
    $_POST['uname'] = 'testTwo';
    $_POST['psw'] = '123456';
    $_POST['cpsw'] = '123456';
    $_POST['accType_seller'] = 1;
    $_POST['accType_buyer'] = 0;

    $result = $responderObj->sign_up($conn);

    // Another successful example
    $this->assertSame(true, $result['status']);
  }

  public function testResponder_singIn1(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );


    $responderObj = new Responder();
    $_POST['uname'] = 'testOne';
    $_POST['psw'] = '123456';

    $result = $responderObj->sign_in($conn);

    // Successful sing-in after this account was created in the testResponder_singUp1
    $this->assertSame(true, $result['status']);
  }

  public function testResponder_singIn2(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );


    $responderObj = new Responder();
    $_POST['uname'] = 'testOnee';
    $_POST['psw'] = '123456';

    $result = $responderObj->sign_in($conn);

    // Incorrect uname
    $this->assertSame("There is no such user", $result['message']);
  }

  public function testResponder_singIn3(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );


    $responderObj = new Responder();
    $_POST['uname'] = '';
    $_POST['psw'] = '123456';

    $result = $responderObj->sign_in($conn);

    $this->assertSame("Username cannot be empty!", $result['message']);
  }

  public function testResponder_singIn4(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );


    $responderObj = new Responder();
    $_POST['uname'] = 'testOne';
    $_POST['psw'] = '';

    $result = $responderObj->sign_in($conn);

    $this->assertSame("Password cannot be empty!", $result['message']);
  }

  public function testResponder_singIn5(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );


    $responderObj = new Responder();
    $_POST['uname'] = 'testOne';
    $_POST['psw'] = '1234567';

    $result = $responderObj->sign_in($conn);
    
    // Incorrect password
    $this->assertSame("Password is incorrect", $result['message']);
  }

  public function testResponder_singIn6(){
    $server_name = "localhost";
    $server_username = "root";
    $server_password = "root";
    $db_name = 'ecommerce';
    $conn = new mysqli( $server_name, $server_username, $server_password, $db_name );


    $responderObj = new Responder();
    $_POST['uname'] = 'testTwo';
    $_POST['psw'] = '123456';

    $result = $responderObj->sign_in($conn);

    // Successful sing-in after this account was created in the testResponder_singUp6
    $this->assertSame(true, $result['status']);

  }

}



?>