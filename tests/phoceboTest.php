<?php
    
/**
 * Phởcebo PHPUnit Tests.
 *
 *
 * @package Phởcebo
 * @author Patricia Walton <patricia.walton@shrm.org>
 * @license MIT
 * @copyright 2015 SHRM
 * 
 */  

    
namespace Phocebo\Tests;

use Phocebo\phocebo;


/**
 * Phởcebo Recipe File
 * @const INI Environment Settings File
 */
 


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
        
        $codice = phocebo::getHash($params);
        
        $this->assertNotEmpty($codice, "GetHash returned a Null Value");

    }    

    public function testGetHashsha1String40() {
        
        $sha1_len = 0;
        
        $params = array ( 'userid', 'also_check_as_email' );
        
        $codice = phocebo::getHash($params);
        
        $sha1_len = strlen ($codice['sha1']);
        
        $this->assertEquals(40, $sha1_len, "Sha1 not calculating incorrectly");

    }    


    public function testResponseIsJSONString() {
        
        $action = '/user/checkUsername';
        
        $data_params = array (
            
            'userid' => 'patricia.walton@shrm.org',
            
        	'also_check_as_email' => true,
        	
        );
        						
        $response = phocebo::call($action, $data_params);
        
        $json_error = 'JSON_ERROR_NONE';
        
        $json_error = json_decode($response);
        
        $this->assertNotEquals($json_error, 'JSON_ERROR_NONE', "Not a JSON Response");
        
    }    
  
    
}

class testphoceboDiner extends \PHPUnit_Framework_TestCase {
    
    /**
     * testdoceboId function.
     *
     * Test if function is returning correct keys in object.
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
        
        $responseObj = phocebo::getdoceboId ( array( 'email' => $email ) );
        
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
     *
     * @dataProvider providerTesttestdoceboIdObj
     */
     
     
    public function testdoceboIdObj ( $checkAttribute, $errorMessage ) {
        
        $responseObj = phocebo::getdoceboId ( array ( 'email' => 'patricia.walton@shrm.org') );
        
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

    public function testdoceboIdCustomErrors ( $parameters ) {
        
        $responseObj = phocebo::getdoceboId( $parameters );
        
        $this->assertEquals( $responseObj->error, '400', 'JSON response should be reporting error 400' );

    }    

    public function providerTesttestdoceboIdCustomErrors() {
               
        return array(
            
            array ( array ( 'noemail' => 'patricia.walton@shrm.org' )  ),

            array ( array ( 'email' => 'not an email address' ) ),

        );
        
    }
    
    
    /**
     * testaddUserCustomErrorsJSONformatfirstName function.
     * 
     * @access public
     * @param mixed $checkAttribute
     *
     * @dataProvider providerTesttestaddUsereCustomErrorsJSONformatfirstName
     *     
     */
     
     
    public function testaddUserCustomErrorsJSONformatfirstName ( $checkAttribute ) {
        
        $parameters = array (
            
            'lastName'                  => 'Walton',
            
            'email'                     => 'patricia.walton@me.com'
            
        );
        
        $responseObj = phocebo::addUser( $parameters );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, "Object response missing $checkAttribute");

    }    
    
    public function providerTesttestaddUsereCustomErrorsJSONformatfirstName() {
               
        return array(
            
            array ( 'success' ),

            array ( 'error' ),

            array ( 'message' ),

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
     
     
    public function testaddUserCustomErrorsJSONformatlastName ( $checkAttribute ) {
        
        $parameters = array (
            
            'firstName'                 => 'Patricia',
            
            'email'                     => 'patricia.walton@me.com'
            
        );
        
        $responseObj = phocebo::addUser( $parameters );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, "Object response missing $checkAttribute" );

    }    
    
    public function providerTesttestaddUsereCustomErrorsJSONformatlastName() {
               
        return array(
            
            array ( 'success' ),

            array ( 'error' ),

            array ( 'message' ),

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
     
     
    public function testaddUserCustomErrorsJSONformatemail ( $checkAttribute ) {
        
        $parameters = array (
            
            'firstName'                 => 'Patricia',
            
            'lastName'                  => 'Walton',
            
        );
        
        $responseObj = phocebo::addUser( $parameters );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, "Object response missing $checkAttribute" );

    }    
    
    public function providerTesttestaddUsereCustomErrorsJSONformatemail() {
               
        return array(
            
            array ( 'success' ),

            array ( 'error' ),

            array ( 'message' ),

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

    public function testaddUserCustomErrors ( $parameters ) {
        
        $responseObj = phocebo::addUser ( $parameters );
        
        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    

    public function providerTesttestaddUserCustomErrors() {
               
        return array(
            
            array ( array ( 'nofirstName' => 'Patricia' ) ),

            array ( array ( 'firstName' => 'Patricia', 'nolastName' => 'Walton' ) ),

            array ( array ( 'firstName' => 'Patricia', 'lastName' => 'Walton', 'noemail' => 'patrica.walton@me.com' ) ),

        );
        
    }
    
    
    public function testaddUser () {
        
        /*  
            
            object should not have idst but user doceboId instead
                
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
        
//         $responseObj = $mock::phocebo( $parameters );
        
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
    
    
    

    /**
     * testgetUserFields function.
     * 
     * @access public
     */
     
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
            
        */

        $responseObj = phocebo::getUserFields( );
        
        $this->assertObjectHasAttribute( 'fields', $responseObj, "Object response missing attribute fields" );

        $fields = $responseObj->fields;
        
        $this->assertObjectHasAttribute( 'id', $fields['0'], "Object response missing attribute id" );

        $this->assertObjectHasAttribute( 'name', $fields['0'], "Object response missing attribute name" );
        
        $this->assertEquals ($fields['0']->name, 'Job Role', 'User Fields in Docebo does not have Job Role' );
        
    }    

    
    /**
     * testgetUserProfileCustomErrors function.
     * 
     * @access public
     */
     
    public function testgetUserProfileCustomErrors () {

        $parameters = array (
            
            'nodoceboId'                 => '',
            
        );
        
        $responseObj = phocebo::getUserProfile( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    

    /**
     * testgetUserProfileValid function.
     * 
     * @access public
     */
     
    public function testgetUserProfileValid () {
        
        $parameters = array (
            
            'doceboId'                 => '12339',
            
        );
        
        $responseObj = phocebo::getUserProfile( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, "Object response missing attribute doceboId" );

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

        $this->assertObjectHasAttribute( 'firstName', $responseObj, "Object response missing attribute firstName" );

        $this->assertObjectHasAttribute( 'lastName', $responseObj, "Object response missing attribute lastName" );

        $this->assertObjectHasAttribute( 'email', $responseObj, "Object response missing attribute email" );

        $this->assertObjectHasAttribute( 'valid', $responseObj, "Object response missing attribute valid" );

        $this->assertObjectHasAttribute( 'registerDate', $responseObj, "Object response missing attribute registerDate" );

        $this->assertObjectHasAttribute( 'lastEnter', $responseObj, "Object response missing attribute lastEnter" );
        
        $this->assertObjectHasAttribute( 'fields', $responseObj, "Object response missing attribute fields" );
        
        $fields = $responseObj->fields;
        
        $this->assertObjectHasAttribute ( 'id', $fields['0'], 'Object response missing attribute fields->id' );

        $this->assertObjectHasAttribute ( 'name', $fields['0'], 'Object response missing attribute fields->name' );

        $this->assertObjectHasAttribute ( 'value', $fields['0'], 'Object response missing attribute fields->value' );

    }    

    
    /**
     * testgetUserProfileInvalid function.
     * 
     * @access public
     */

    public function testgetUserProfileInvalid () {

        $parameters = array (
            
            'doceboId'                 => '10101',
            
        );
        
        $responseObj = phocebo::getUserProfile( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

    }    

    /**
     * testsuspendUseCustomErrors function.
     * 
     * @access public
     */

    public function testsuspendUseCustomErrors () {

        $parameters = array (
            
            'nodoceboId'                 => '',
            
        );
        
        $responseObj = phocebo::suspendUser( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    
    
    /**
     * testsuspendUserValidUser function.
     * 
     * @access public
     */

    public function testsuspendUserValidUser () {

        $parameters = array (
            
            'doceboId'                 => '12339',
            
        );
        
        $responseObj = phocebo::suspendUser( $parameters );
        
        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object response missing attribute doceboId');

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }    

    /**
     * testsuspendUserInValidUser function.
     * 
     * @access public
     */

    public function testsuspendUserInValidUser () {

        $parameters = array (
            
            'doceboId'                 => '10101',
            
        );
        
        $responseObj = phocebo::suspendUser( $parameters );
        
        $this->assertFalse ( $responseObj->success,  'Success message should be flase' );

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object response missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object response missing attribute message');


    }    
    
    /**
     * testunsuspendUseCustomErrors function.
     * 
     * @access public
     */

    public function testunsuspendUseCustomErrors () {

        $parameters = array (
            
            'nodoceboId'                 => '',
            
        );
        
        $responseObj = phocebo::unsuspendUser( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    

    
    /**
     * testunsuspendUserValidUser function.
     * 
     * @access public
     */

    public function testunsuspendUserValidUser () {

        $parameters = array (
            
            'doceboId'                 => '12339',
            
        );
        
        $responseObj = phocebo::unsuspendUser( $parameters );
        
        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object response missing attribute doceboId');

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }    

    /**
     * testunsuspendUserInValidUser function.
     * 
     * @access public
     */

    public function testunsuspendUserInValidUser () {

        $parameters = array (
            
            'doceboId'                 => '10101',
            
        );
        
        $responseObj = phocebo::unsuspendUser( $parameters );
        
        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object response missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object response missing attribute message');

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