<?php
    
/**
 * Phởcebo PHPUnit Tests
 *
 * Usu fastidii corrumpit honestatis ad, his ludus assueverit id, scripta 
 * insolens torquatos eu sea. Eum ei maiorum eleifend molestiae, eu mea movet 
 * placerat iudicabit. Pertinax quaestio te vim, falli utamur senserit in sea, 
 * vix id magna modus assueverit. No eirmod euismod mel, te his dicta evertitur,
 * an tota congue consul sed.
 *
 * @package Phởcebo
 * @author Patricia Walton <patricia.walton@shrm.org>
 * @version 0.0.1 In Development
 * @license MIT
 * @copyright 2015 SHRM
 * 
 */  

    
namespace Phocebo\Tests;

use Phocebo\phocebo;

use Phocebo\phoceboCook;

/**
 * Phởcebo Recipe File
 * @const INI Environment Settings File
 */
 

define('INI', '.env');

if (file_exists(INI)) {
    
    $settings = parse_ini_file (INI, true);   
    
    /**
     * @const URL Docebo URL
     */

    define('URL', $settings[docebo]['URL']);
    
    /**
     * @const KEY Docebo public Key
     */

    define('KEY', $settings[docebo]['KEY']);

    /**
     * @const SECRET Docebo secret Key
     */

    define('SECRET', $settings[docebo]['SECRET']);

    /**
     * @const SSO - Future SSO 
     */

    define('SSO', $settings[docebo]['SSO']);   
    
} else die( "\nERROR: Phởcebo ingredients are missing (.env) \n\n");





$phocebo = new phocebo();


class EnvironmentVariablesTest extends \PHPUnit_Framework_TestCase {
    
    public function testEnvironmentSettingsLoaded() {
        
        global $settings;

        $this->assertArrayHasKey("docebo", $settings, "Environment settings not loaded");

    }    

    public function testURLisNotBlank() {
              
        $this->assertNotEquals(URL, "URL", "Missing Docebo URL");

    }    

    public function testURLisValidk() {
        
        $URLisValid = true;
        
        if (filter_var( URL, FILTER_VALIDATE_URL) === FALSE) {
            
            $URLisValid = false;
        }

        $this->assertTrue($URLisValid, "The Docebo URL is invalid");

    }    

    public function testKEYisNotBlank() {
              
        $this->assertNotEquals(KEY, "KEY", "Missing Docebo public key");

    }    

    public function testSECRETisNotBlank() {
              
        $this->assertNotEquals(SECRET, "SECRET", "Missing Docebo secret key");

    }    

    public function testSSOisNotBlank() {
              
        $this->assertNotEquals(SSO, "SSO", "Missing Docebo SSO");

    } 
    
}

class testphoceboCooking extends \PHPUnit_Framework_TestCase {
    
    public function testGetHashParametersExist() {
        
        $params = array ( 'userid', 'also_check_as_email' );
        
        $codice = phoceboCook::getHash($params);
        
        $this->assertNotEmpty($codice, "GetHash returned a Null Value");

    }    

    public function testGetHashsha1String40() {
        
        $sha1_len = 0;
        
        $params = array ( 'userid', 'also_check_as_email' );
        
        $codice = phoceboCook::getHash($params);
        
        $sha1_len = strlen ($codice['sha1']);
        
        $this->assertEquals(40, $sha1_len, "Sha1 not calculating incorrectly");

    }    


    public function testResponseIsJSONString() {
        
        $action = '/user/checkUsername';
        
        $data_params = array (
            
            'userid' => 'patricia.walton@shrm.org',
            
        	'also_check_as_email' => true,
        	
        );
        						
        $response = phoceboCook::call($action, $data_params);
        
        $json_error = 'JSON_ERROR_NONE';
        
        $json_error = json_decode($response);
        
        $this->assertNotEquals($json_error, 'JSON_ERROR_NONE', "Not a JSON Response");
        
    }    
  
    
}

class testphoceboDiner extends \PHPUnit_Framework_TestCase {
    
    /**
     * testdoceboId function.
     * 
     * @access public
     * @param mixed $email
     * @param mixed $expectedResult
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestdoceboId
     *
     */
     
    public function testdoceboId ( $email, $checkAttribute, $errorMessage ) {
        
        $responseObj = phocebo::getdoceboId( array( 'email' => $email ) );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    /**
     * providerTesttestdoceboId function.
     *
     * Test data for the testdoceboId function.
     
     * @access public
     * @return void
     *
     */
     
    public function providerTesttestdoceboId() {
               
        return array(
            
            array ( 'patricia.walton@shrm.org', 'email', 'doceboId is valid but not reporting as valid' ),
            
            array ( 'someone@example.com', 'error', 'doceboId is not valid but reporting as valid' ),
            
        );
        
    }

   
    /**
     * testdoceboIdObj function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestdoceboIdObj
     */
     
     
    public function testdoceboIdObj ($checkAttribute, $errorMessage ) {
        
        $responseObj = phocebo::getdoceboId( array('email' => 'patricia.walton@shrm.org') );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestdoceboIdObj() {
               
        return array(
            
            array ( 'doceboId', 'doceboId not in $responseObj' ),

            array ( 'firstname', 'firstname not in $responseObj' ),
            
            array ( 'lastname', 'lastname not in $responseObj' ),

            array ( 'email', 'email not in $responseObj' ),

        );
        
    }

    
    
    /**
     * testdoceboIdRemovedIdst function.
     * 
     * @access public
     * @return void
     *
     */
     
    public function testdoceboIdRemovedIdst () {
        
        $responseObj = phocebo::getdoceboId ( array( 'email' => 'patricia.walton@shrm.org') );
        
        $this->assertObjectNotHasAttribute ( 'idst', $responseObj, 'The parameter idst should be removed from $responseObj');

    }    
    

    /**
     * testdoceboIdCustomErrorsJSONformat function.
     * 
     * @access public
     * @return void
     *
     * @dataProvider providerTesttestdoceboIdCustomErrorsJSONformat
     *
     */
     
    public function testdoceboIdCustomErrorsJSONformat ($checkAttribute, $errorMessage) {
        
        $responseObj = phocebo::getdoceboId( array( 'noemail' => 'patricia.walton@shrm.org') );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestdoceboIdCustomErrorsJSONformat() {
               
        return array(
            
            array ('success', 'success not part of JSON response'),

            array ('error', 'error not part of JSON response'),

            array ('message', 'message not part of JSON response'),

        );
        
    }

    
    /**
     * testdoceboIdCustomErrors function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestdoceboIdCustomErrors
     *
     */

    public function testdoceboIdCustomErrors ($parameters, $expected, $errorMessage) {
        
        $responseObj = phocebo::getdoceboId( $parameters );
        
        $this->assertEquals( $responseObj->error, $expected, $errorMessage);

    }    

    public function providerTesttestdoceboIdCustomErrors() {
               
        return array(
            
            array ( array ( 'noemail' => 'patricia.walton@shrm.org' ), '301', 'JSON response should be reporting error 301' ),

            array ( array ( 'email' => 'not an email address' ), '302', 'JSON response should be reporting error 302' ),

        );
        
    }
    
    
    // test addUser 
    // test for required fields
    // test responses
    
    
    
    
    
    /**
     * testaddUserCustomErrorsJSONformatfirstName function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestaddUsereCustomErrorsJSONformatfirstName
     *     
     */
     
     
    public function testaddUserCustomErrorsJSONformatfirstName ($checkAttribute, $errorMessage) {
        
        $parameters = array (
            
            'lastName'                  => 'Walton',
            
            'email'                     => 'patricia.walton@me.com'
            
        );
        
        $responseObj = phocebo::addUser( $parameters );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestaddUsereCustomErrorsJSONformatfirstName() {
               
        return array(
            
            array ( 'success', 'success not part of JSON response' ),

            array ( 'error', 'error not part of JSON response' ),

            array ( 'message', 'message not part of JSON response' ),

        );
        
    }

    
    /**
     * testaddUserCustomErrorsJSONformatlastName function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestaddUsereCustomErrorsJSONformatlastName
     *     
     */
     
     
    public function testaddUserCustomErrorsJSONformatlastName ($checkAttribute, $errorMessage) {
        
        $parameters = array (
            
            'firstName'                 => 'Patricia',
            
            'email'                     => 'patricia.walton@me.com'
            
        );
        
        $responseObj = phocebo::addUser( $parameters );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestaddUsereCustomErrorsJSONformatlastName() {
               
        return array(
            
            array ( 'success', 'success not part of JSON response' ),

            array ( 'error', 'error not part of JSON response' ),

            array ( 'message', 'message not part of JSON response' ),

        );
        
    }


    /**
     * testaddUserCustomErrorsJSONformatemail function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestaddUsereCustomErrorsJSONformatemail
     *     
     */
     
     
    public function testaddUserCustomErrorsJSONformatemail ($checkAttribute, $errorMessage) {
        
        $parameters = array (
            
            'firstName'                 => 'Patricia',
            
            'lastName'                  => 'Walton',
            
        );
        
        $responseObj = phocebo::addUser( $parameters );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestaddUsereCustomErrorsJSONformatemail() {
               
        return array(
            
            array ( 'success', 'success not part of JSON response' ),

            array ('error', 'error not part of JSON response' ),

            array ( 'message', 'message not part of JSON response' ),

        );
        
    }


    /**
     * testaddUserCustomErrors function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestaddUserCustomErrors
     *
     */

    public function testaddUserCustomErrors ($parameters, $expected, $errorMessage) {
        
        $responseObj = phocebo::addUser ( $parameters );
        
        $this->assertEquals ( $responseObj->error, $expected, $errorMessage );

    }    

    public function providerTesttestaddUserCustomErrors() {
               
        return array(
            
            array ( array ( 'nofirstName' => 'Patricia' ), '303', 'JSON response should be reporting error 303' ),

            array ( array ( 'firstName' => 'Patricia', 'nolastName' => 'Walton' ), '304', 'JSON response should be reporting error 304' ),

            array ( array ( 'firstName' => 'Patricia', 'lastName' => 'Walton', 'noemail' => 'patrica.walton@me.com' ), '305', 'JSON response should be reporting error 305' ),

        );
        
    }
    
    
    
    
    public function testaddUser () {
        
/*  object should not have idst but user doceboId instead
object(stdClass)#345 (2) {
  ["idst"]=>
  string(5) "12365"
  ["success"]=>
  bool(true)
} 

ERROR on add user is NULL

object(stdClass)#347 (2) {
  ["success"]=>
  bool(true)
  ["doceboId"]=>
  string(5) "12367"
}


       
*/
        
        $parameters = array (
            
            'firstName'                 => 'Vladan',
            
            'lastName'                  => 'Dasic',
            
            'email'                     => 'vdasic1@example.com'
            
        );
        
//         $responseObj = phocebo::addUser( $parameters );
        
//         var_dump($responseObj);
        
        
//         $this->assertObjectHasAttribute( $checkAttribute, $responseObj, 'test addUser');

    }    
    
    
    public function testdeleteUser () {
        
/*
object(stdClass)#347 (3) {
  ["success"]=>
  bool(false)
  ["error"]=>
  int(211)
  ["message"]=>
  string(22) "Error in user deletion"
}


object(stdClass)#347 (2) {
  ["success"]=>
  bool(true)
  ["doceboId"]=>
  string(5) "12366"
}



*/
        
        $parameters = array (
            
            'doceboId'                 => '12366',
            
        );
        
//         $responseObj = phocebo::deleteUser( $parameters );
        
//         var_dump($responseObj);
        
        
//         $this->assertObjectHasAttribute( $checkAttribute, $responseObj, 'test addUser');

    }    
    

    public function testgetUserFields () {
        
/*
    
This Error is created when the parameters are not sent.

object(stdClass)#349 (3) {
  ["success"]=>
  bool(false)
  ["message"]=>
  string(40) "Authorization header value doesn't match"
  ["code"]=>
  int(104)
}

object(stdClass)#349 (2) {
  ["fields"]=>
  array(1) {
    [0]=>
    object(stdClass)#344 (2) {
      ["id"]=>
      int(1)
      ["name"]=>
      string(8) "Job Role"
    }
  }
  ["success"]=>
  bool(true)
}



*/

        
//         $responseObj = phocebo::getUserFields( );
        
//         var_dump($responseObj);       
        
//         $this->assertObjectHasAttribute( $checkAttribute, $responseObj, 'test addUser');

    }    


    public function testgetUserProfile () {
        
/*

Errror if retrieving a Global Admin Profile
    
object(stdClass)#351 (3) {
  ["success"]=>
  bool(false)
  ["error"]=>
  int(201)
  ["message"]=>
  string(26) "Invalid user specification"
}


object(stdClass)#351 (11) {
  ["id_user"]=>
  string(5) "12337"
  ["userid"]=>
  string(4) "Test"
  ["firstname"]=>
  string(4) "Test"
  ["lastname"]=>
  string(0) ""
  ["email"]=>
  string(13) "test@shrm.org"
  ["signature"]=>
  string(0) ""
  ["valid"]=>
  bool(true)
  ["register_date"]=>
  string(19) "2015-09-21 19:55:00"
  ["last_enter"]=>
  NULL
  ["fields"]=>
  array(1) {
    [0]=>
    object(stdClass)#346 (3) {
      ["id"]=>
      string(1) "1"
      ["name"]=>
      string(8) "Job Role"
      ["value"]=>
      string(0) ""
    }
  }
  ["success"]=>
  bool(true)
}



*/

        $parameters = array (
            
            'doceboId'                 => '12339',
            
        );
        
        $responseObj = phocebo::getUserProfile( $parameters );
        
//         var_dump($responseObj);       
        
//         $this->assertObjectHasAttribute( $checkAttribute, $responseObj, 'test addUser');

    }    


    public function testsuspendUser () {
        
/*

Will always respond True even if user was previously suspended

object(stdClass)#353 (2) {
  ["idst"]=>
  string(5) "12339"
  ["success"]=>
  bool(true)
}




*/

        $parameters = array (
            
            'doceboId'                 => '12339',
            
        );
        
//         $responseObj = phocebo::suspendUser( $parameters );
        
//         var_dump($responseObj);       
        
//         $this->assertObjectHasAttribute( $checkAttribute, $responseObj, 'test addUser');

    }    

    public function testunsuspendUser () {
        
/*

Will always respond true on sucess even if user was not suspended

object(stdClass)#353 (2) {
  ["idst"]=>
  string(5) "12339"
  ["success"]=>
  bool(true)
}




*/

        $parameters = array (
            
            'doceboId'                 => '12339',
            
        );
        
        $responseObj = phocebo::unsuspendUser( $parameters );
        
        var_dump($responseObj);       
        
//         $this->assertObjectHasAttribute( $checkAttribute, $responseObj, 'test addUser');

    }    



}


class testphoceboCourse extends \PHPUnit_Framework_TestCase {
    

    /**
     * testuserCoursesCustomErrorNoDoceboId function.
     * 
     * @access public
     * @return void
     *
     */

    public function testuserCoursesCustomErrorNoDoceboId () {
        
        $parameters = array ('no key' => '12332');
        
        $responseObj = phocebo::userCourses( $parameters );
        
        $this->assertEquals( $responseObj->error, '301', 'JSON response should be reporting error 301');

    }    


    /**
     * testuserCoursesCustomErrorNoCoursesForUser function.
     * 
     * @access public
     * @return void
     */
     
    public function testuserCoursesCustomErrorNoCoursesForUser () {
        
/*
        $response = array ('success' => false, 'error' => '306', 'message' => 'Learner is not enrolled in any courses');
        
        $responseObj = json_decode ( json_encode( $response ), FALSE );
        
        $this->assertEquals( $responseObj->error, '306', 'JSON response should be reporting error 301');
*/

    }    

    public function testuserCourses () {

        $parameters = array ('doceboId' => '12332');
        
        $responseObj = phocebo::userCourses( $parameters );
        
    }    


    public function testlistCourses () {
       
        $responseObj = phocebo::listCourses();

    }    


    public function testlistUsersCourses () {
        
        $parameters = array ('doceboId' => '12332');
       
        $responseObj = phocebo::listUsersCourses($parameters);

    }    


    public function testenrollUserInCourse () {
        
        $parameters = array (
        
            'doceboId' => '12332',
            
            'courseCode'    => '14-06'

        );
       
        $responseObj = phocebo::enrollUserInCourse($parameters);
        
    }    


    public function testunenrollUserInCourse () {
        
        $parameters = array (
        
            'doceboId' => '12332',
            
            'courseCode'    => '14-06'

        );
       
        $responseObj = phocebo::unenrollUserInCourse($parameters);
        
//         var_dump($responseObj);


    }    




    

}


?>