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

    
namespace SHRM\Phocebo\Tests;

use SHRM\Phocebo\phocebo;


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

    define('URL', $settings['docebo']['URL']);
    
    /**
     * @const KEY Docebo public Key
     */

    define('KEY', $settings['docebo']['KEY']);

    /**
     * @const SECRET Docebo secret Key
     */

    define('SECRET', $settings['docebo']['SECRET']);

    /**
     * @const SSO - Future SSO 
     */

    define('SSO', $settings['docebo']['SSO']);   
    
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
            
            'userid' => 'test@shrm.org',
            
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
        
//         var_dump($responseObj);

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
            
            array ( 'test@shrm.org', 'email', 'doceboId is valid but not reporting as valid' ),
            
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
     */
     
     
    public function testdoceboIdObj ( ) {
        
        $responseObj = phocebo::getdoceboId ( array ( 'email' => 'test@shrm.org') );
        
        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'doceboId not in $responseObj');

        $this->assertObjectNotHasAttribute ( 'idst', $responseObj, 'The parameter idst should be removed from $responseObj');

        $this->assertObjectHasAttribute( 'firstName', $responseObj, 'firstName not in $responseObj');

        $this->assertObjectHasAttribute( 'lastName', $responseObj, 'lastName not in $responseObj');

        $this->assertObjectHasAttribute( 'email', $responseObj, 'email not in $responseObj');

    }    

    /**
     * testdoceboIdCustomErrorsJSONformat function.
     * 
     * @access public
     * @return void
     *
     */
     
    public function testdoceboIdCustomErrorsJSONformat ( ) {
        
        $responseObj = phocebo::getdoceboId( array( 'noemail' => 'test@shrm.org') );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

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
            
            array ( array ( 'noemail' => 'test@shrm.org' )  ),

            array ( array ( 'email' => 'not an email address' ) ),

        );
        
    }
    
    
    /**
     * testauthenticateUserValid function.
     * 
     * @access public
     */
     
    public function testauthenticateUserValid ( ) {
        
        $parameters = array( 'username' => 'test@shrm.org', 'password' => 'password' );
        
        $responseObj = phocebo::authenticateUser ( $parameters );
        
        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object missing attribute success');

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'token', $responseObj, 'Object missing attribute token');


    }    

    /**
     * testauthenticateUserInvalid function.
     * 
     * @access public
     * @param mixed $checkAttribute
     *
     * @dataProvider providerTesttestauthenticateUserInvalid
     *     
     */

    public function testauthenticateUserInvalid ( $parameters ) {
        
        $responseObj = phocebo::authenticateUser ( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

    }    


    public function providerTesttestauthenticateUserInvalid() {
               
        return array(
            
            array ( array( 'username' => '', 'password' => 'password' ) ),

            array ( array( 'username' => 'test@shrm.org', 'password' => '' ) ),

            array ( array( 'username' => 'notest@shrm.org', 'password' => 'password' ) ),

            array ( array( 'username' => 'notest@shrm.org', 'password' => '' ) ),
        );
        
    }


    /**
     * testauthenticateUserInvalidJSONMessage400 function.
     * 
     * @access public
     * @param mixed $checkAttribute
     *
     * @dataProvider providerTesttestauthenticateUserInvalidJSONMessage400
     *     
     */

    public function testauthenticateUserInvalidJSONMessage400 ( $parameters ) {
        
        $responseObj = phocebo::authenticateUser ( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertEquals ( $responseObj->error, '400', "Object response should be reporting error 400" );

    }    


    public function providerTesttestauthenticateUserInvalidJSONMessage400() {
               
        return array(
            
            array ( array( 'doceboId' => '11111' ) ),

            array ( array( 'username' => '', 'password' => 'password' ) ),

            array ( array( 'username' => 'test@shrm.org', 'password' => '' ) ),

        );
        
    }


    /**
     * testaddUserCustomErrorsJSONformatfirstName function.
     * 
     * @access public
     * @param mixed $checkAttribute
     *
     */
     
    public function testaddUserCustomErrorsJSONformatfirstName ( ) {
        
        $parameters = array (
            
            'lastName'                  => 'Account',
            
            'email'                     => 'test@me.com'
            
        );
        
        $responseObj = phocebo::addUser( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

    }    

    
    /**
     * testaddUserCustomErrorsJSONformatlastName function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     *
     */
     
     
    public function testaddUserCustomErrorsJSONformatlastName ( ) {
        
        $parameters = array (
            
            'firstName'                 => 'Test',
            
            'email'                     => 'test@shrm.org'
            
        );
        
        $responseObj = phocebo::addUser( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

    }    
    


    /**
     * testaddUserCustomErrorsJSONformatemail function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     *     
     */
     
     
    public function testaddUserCustomErrorsJSONformatemail ( ) {
        
        $parameters = array (
            
            'firstName'                 => 'Test',
            
            'lastName'                  => 'Account',
            
        );
        
        $responseObj = phocebo::addUser( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );


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
            
            array ( array ( 'nofirstName' => 'Test' ) ),

            array ( array ( 'firstName' => 'Test', 'nolastName' => 'Account' ) ),

            array ( array ( 'firstName' => 'Test', 'lastName' => 'Account', 'noemail' => 'test@shrm.org' ) ),

        );
        
    }
    
    
    /**
     * testaddUser function.
     * 
     * @access public
     */

    public function testaddUser () {
        
        $parameters = array (
            
            'firstName'                 => 'Vladan',
            
            'lastName'                  => 'Dasic',
            
            'email'                     => 'vdasic3@example.com'
            
        );
        
/*
        $responseObj = phocebo::addUser ( $parameters );
        
        var_dump ($responseObj);
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );
        
        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object missing attribute doceboId');

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');
        
*/


    }    
    
    /**
     * testdeleteUserCustomError function.
     * 
     * @access public
     */

    public function testdeleteUserCustomError () {

        $parameters = array (
            
            'nodoceboId'                 => '10101',
            
        );
        
        $responseObj = phocebo::deleteUser( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    
    
    /**
     * testdeleteUserDoesntExist function.
     * 
     * @access public
     */

    public function testdeleteUserDoesntExist () {

        $parameters = array (
            
            'doceboId'                 => '10101',
            
        );
        
        $responseObj = phocebo::deleteUser( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

        $this->assertEquals ( $responseObj->error, '211', 'Object response should be reporting error 211' );

    }    
    
    /**
     * testdeleteUserValid function.
     * 
     * @access public
     */

    public function testdeleteUserValid () {

        $parameters = array (
            
            'doceboId'                 => '12370',
            
        );
        
/*
        $responseObj = phocebo::deleteUser( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object missing attribute success');

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

*/

    }    
    
  
    /**
     * testeditUserCustomErrors function.
     * 
     * @access public
     * @dataProvider providerTesttesteditUserCustomErrors
     */

    public function testeditUserCustomErrors ( $parameters ) {
        
        $responseObj = phocebo::editUser ( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    

    public function providerTesttesteditUserCustomErrors() {
               
        return array(
            
            array ( array ( 'nodoceboId' => '10101' ) ),

            array ( array ( 'doceboId' => '10101' ) ),

            array ( array ( 'doceboId' => '10101', 'email' => 'test invalid email' ) ),

        );
        
    }


    /**
     * testeditUserCustomServerErrors function.
     * 
     * @access public
     * @dataProvider providerTesttesteditUser
     */

    public function testeditUser ( $parameters ) {
        

        $responseObj = phocebo::editUser ( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object missing attribute doceboId');

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

    }    

    public function providerTesttesteditUser() {
               
        return array(
            
            array ( array ( 'doceboId' => '12369', 'firstName' => 'Change First Name') ),

            array ( array ( 'doceboId' => '12369', 'lastName' => 'Change Last Name') ),

            array ( array ( 'doceboId' => '12369', 'firstName' => 'Change First and Last Name', 'lastName' => 'Change First and Last Name') ),

            array ( array ( 'doceboId' => '12369', 'password' => 'Change Password') ),

            array ( array ( 'doceboId' => '12369', 'valid' => false) ),

            array ( array ( 'doceboId' => '12369', 'unenroll_deactivated' => false) ),

            array ( array ( 'doceboId' => '12369', 'email' => 'test2@shrm.org') ),

            array ( array ( 'doceboId' => '12369', 'password' => 'password') ),

            array ( array ( 'doceboId' => '12369', 'email' => 'test@shrm.org') ),
            
            array ( array ( 'doceboId' => '12369', 'valid' => true) ),

            array ( array ( 'doceboId' => '12369', 'unenroll_deactivated' => true ) ),

        );
        
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
        
        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

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

        $this->assertTrue ( $responseObj->success,  'Success message should be true ' );

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

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

    }    



    public function testgetUserGroupsCustomErrors () {

        $parameters = array (
            
            'nodoceboId'                 => '',
            
        );
        
        $responseObj = phocebo::getUserGroups( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );
        
        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    


    public function testgetUserGroupsValid () {

        $parameters = array (
            
            'doceboId'                 => '12339',
            
        );
        
        $responseObj = phocebo::getUserGroups( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );
        
        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'results', $responseObj, "Object response missing attribute results" );
        
        $results = $responseObj->results;
        
        $this->assertObjectHasAttribute( 'groups', $results, "Object response results missing attribute groups" );

        $this->assertObjectHasAttribute( 'folders', $results, "Object response results missing attribute folders" );

    }    

    public function testloggedinUserCustomError () {

        $parameters = array (
            
            'nodoceboId'                 => '10101',
            
        );
        
        $responseObj = phocebo::loggedinUser( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );
        
        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    

    public function testloggedinUserValid () {

        $parameters = array (
            
            'doceboId'                 => '12339',
            
        );
        
        $responseObj = phocebo::loggedinUser( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );
        
        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'loggedIn', $responseObj, "Object response missing attribute loggedIn" );

    }    

    public function testloggedinUserInValid () {

        $parameters = array (
            
            'doceboId'                 => '10101',
            
        );
        
        $responseObj = phocebo::loggedinUser( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );
        
        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '201', 'Object response should be reporting error 201' );

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

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

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
        
        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

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

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

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

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

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
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

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
        
        $parameters = array ('nodoceboId' => '10101');
        
        $responseObj = phocebo::userCourses( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    



    public function testuserCoursesValid () {

        $parameters = array ('doceboId' => '12339');
        
        $responseObj = phocebo::userCourses( $parameters );
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        
    }    


    public function testlistCourses () {
       
        $responseObj = phocebo::listCourses();

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }    


    public function testlistUsersCourses () {
        
        $parameters = array ('doceboId' => '12339');
       
        $responseObj = phocebo::listUsersCourses( $parameters );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }    


    public function testenrollUserInCourseCustomErrors () {
        
        $parameters = array (
        
            'nodoceboId' => '10101',
            
            'courseCode'    => '14-06'

        );

        $responseObj = phocebo::enrollUserInCourse($parameters);
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $parameters = array (
        
            'doceboId' => '10101',
            
            'nocourseCode'    => '14-06'

        );

        $responseObj = phocebo::enrollUserInCourse($parameters);
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    

    public function testenrollUserInCourse () {
        
        $parameters = array (
        
            'doceboId' => '12339',
            
            'courseCode'    => '14-06'

        );
       
        $responseObj = phocebo::unenrollUserInCourse($parameters);

        $responseObj = phocebo::enrollUserInCourse($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $responseObj = phocebo::enrollUserInCourse($parameters);
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

    }    


    public function testunenrollUserInCourse () {
        
        $parameters = array (
        
            'doceboId' => '12339',
            
            'courseCode'    => '14-06'

        );
       
        $responseObj = phocebo::enrollUserInCourse($parameters);

        $responseObj = phocebo::unenrollUserInCourse($parameters);
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }    

    public function testunenrollUserInCourseError () {
        
        $parameters = array (
        
            'doceboId' => '101010',
            
            'courseCode'    => '14-06'

        );
       
        $responseObj = phocebo::enrollUserInCourse($parameters);

        $responseObj = phocebo::unenrollUserInCourse($parameters);
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

    }    

    public function testunenrollUserInCourseCustomError () {
        
        $parameters = array (
        
            'nodoceboId' => '12339',
            
            'courseCode'    => '14-06'

        );
       
        $responseObj = phocebo::enrollUserInCourse($parameters);

        $responseObj = phocebo::unenrollUserInCourse($parameters);
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $parameters = array (
        
            'doceboId' => '12339',
            
            'nocourseCode'    => '14-06'

        );
       
        $responseObj = phocebo::enrollUserInCourse($parameters);

        $responseObj = phocebo::unenrollUserInCourse($parameters);
        
        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    


    public function testlistUserCourses () {
        
        $parameters = array (
        
            'doceboId' => '12339',
            
        );
       
        phocebo::enrollUserInCourse( array (
                    
            'doceboId' => '12339',
            
            'courseCode'    => '14-06'

        ) );

        $responseObj = phocebo::listUserCourses($parameters);
        
        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );
        
        $this->assertInternalType( 'object', $responseObj->{'0'}, 'Object does not contain an object about course information');

        $this->assertObjectHasAttribute( 'course_info', $responseObj->{'0'}, 'Object response missing attribute "course_info"');

        $this->assertGreaterThanOrEqual ( '1' , count($responseObj), 'Object should have more than 1 element' );
        
    }    

    
    public function testlistUserCoursesNoCourse () {
        
        $parameters = array (
        
            'doceboId' => '12339',
            
        );
       
        phocebo::unenrollUserInCourse( array (
                    
            'doceboId' => '12339',
            
            'courseCode'    => '14-06'

        ) );

        $responseObj = phocebo::listUserCourses($parameters);
        
//         var_dump($response);

//         $this->assertNull ( $responseObj, 'Object should be null - User is not enrolled in any course' );
        
    }    

    public function testcreateBranchCustomError () {
        
        $parameters = array (
            
            'nobranchCode'    => 'TEST',

            'branchName'    => 'Test Branch Creation',

            'parentBranchId'    => 'Parent Branch ID'

        );
       
        $responseObj = phocebo::createBranch($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $parameters = array (
            
            'branchCode'    => 'TEST',

            'nobranchName'    => 'Test Branch Creation',

            'parentBranchId'    => 'Parent Branch ID'

        );
       
        $responseObj = phocebo::createBranch($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $parameters = array (
            
            'branchCode'    => 'TEST',

            'branchName'    => 'Test Branch Creation',

            'noparentBranchId'    => 'Parent Branch ID'

        );
       
        $responseObj = phocebo::createBranch($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }    

    public function testgetBranchbyCode () {
        
        $parameters = array (
        
            'branchCode' => 'root',
            
        );
       

        $responseObj = phocebo::getBranchbyCode($parameters);
        
//         var_dump($responseObj);

        
    }    

    public function testgetBranchInfo () {
        
        $parameters = array (
        
            'branchId' => "0",
            
        );
       
        $responseObj = phocebo::getBranchInfo($parameters);
        
        var_dump($responseObj);

        
    }    

}


?>