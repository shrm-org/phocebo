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


/**
 * EnvironmentVariablesTest class.
 */

class EnvironmentVariablesTest extends \PHPUnit_Framework_TestCase {

    /**
     * testEnvironmentSettingsLoaded function.
     *
     * @access public
     */

    public function testEnvironmentSettingsLoaded() {
        
        global $settings;

        $this->assertArrayHasKey("docebo", $settings, "Environment settings not loaded");

    }

    /**
     * testURLisNotBlank function.
     *
     * @access public
     */

    public function testURLisNotBlank() {
              
        $this->assertNotEquals(URL, "URL", "Missing Docebo URL");

    }

    /**
     * testURLisValid function.
     *
     * @access public
     */

    public function testURLisValid() {
        
        $URLisValid = true;
        
        if (filter_var( URL, FILTER_VALIDATE_URL) === FALSE) {
            
            $URLisValid = false;
        }

        $this->assertTrue($URLisValid, "The Docebo URL is invalid");

    }


    /**
     * testKEYisNotBlank function.
     *
     * @access public
     */

    public function testKEYisNotBlank() {
              
        $this->assertNotEquals(KEY, "KEY", "Missing Docebo public key");

    }

    /**
     * testSECRETisNotBlank function.
     *
     * @access public
     */

    public function testSECRETisNotBlank() {
              
        $this->assertNotEquals(SECRET, "SECRET", "Missing Docebo secret key");

    }

    /**
     * testSSOisNotBlank function.
     *
     * @access public
     */

    public function testSSOisNotBlank() {
              
        $this->assertNotEquals(SSO, "SSO", "Missing Docebo SSO");

    } 
    
}

/**
 * testphoceboCooking class.
 */


class testphoceboCooking extends \PHPUnit_Framework_TestCase {


    /**
     * testGetHashParametersExist function.
     *
     * @access public
     * @internal param array $params
     * @internal param array $codice
     */

    public function testGetHashParametersExist() {
        
        $params = array ( 'userid', 'also_check_as_email' );
        
        $codice = phocebo::getHash($params);

        $this->assertNotEmpty($codice, "GetHash returned a Null Value");

    }

    /**
     * testGetHashsha1String40 function.
     *
     * @access public
     * @internal param array $params
     * @internal param array $codice
     * @internal param array $sha1_len
     */

    public function testGetHashsha1String40() {

        $params = array ( 'userid', 'also_check_as_email' );

        $codice = phocebo::getHash($params);

        $sha1_len = strlen ($codice['sha1']);

        $this->assertEquals(40, $sha1_len, "Sha1 not calculating incorrectly");

    }


    /**
     * testResponseIsJSONString function.
     *
     * @access public
     * @internal param string $action
     * @internal param array $data_params
     * @internal param string $response
     * @internal param string $json_error
     */

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

/**
 * testphoceboDiner class.
 */

class testphoceboDiner extends \PHPUnit_Framework_TestCase {

    /**
     * testdoceboId function.
     *
     * Test if function is returning correct keys in object.
     *
     * @access public
     * @param mixed $email
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @dataProvider providerTesttestdoceboId
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
     *
     * @access public
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
     * @param array $parameters
     * @dataProvider providerTesttestdoceboIdCustomErrors
     */

    public function testdoceboIdCustomErrors ( $parameters ) {

        $responseObj = phocebo::getdoceboId( $parameters );

        $this->assertEquals( $responseObj->error, '400', 'JSON response should be reporting error 400' );

    }

    /**
     * providerTesttestdoceboIdCustomErrors function.
     *
     * @access public
     */

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
     * @internal param array $parameters
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
     * @param array $parameters
     * @dataProvider providerTesttestauthenticateUserInvalid
     */

    public function testauthenticateUserInvalid ( $parameters ) {

        $responseObj = phocebo::authenticateUser ( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

    }

    /**
     * providerTesttestauthenticateUserInvalid function.
     *
     * @access public
     */

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
     * @param array $parameters
     * @dataProvider providerTesttestauthenticateUserInvalidJSONMessage400
     */

    public function testauthenticateUserInvalidJSONMessage400 ( $parameters ) {

        $responseObj = phocebo::authenticateUser ( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertEquals ( $responseObj->error, '400', "Object response should be reporting error 400" );

    }

    /**
     * providerTesttestauthenticateUserInvalidJSONMessage400 function.
     *
     * @access public
     */

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
     * @internal param array $parameters
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
     * @internal param array $parameters
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
     * @param array $parameters
     * @dataProvider providerTesttestaddUserCustomErrors
     */

    public function testaddUserCustomErrors ( $parameters ) {

        $responseObj = phocebo::addUser ( $parameters );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }

    /**
     * providerTesttestaddUserCustomErrors function.
     *
     * @access public
     */

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
     * @internal param array $parameters
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
     * @internal param array $parameters
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
     * @internal param array $parameters
     */

    public function testdeleteUserDoesntExist () {

        $parameters = array ( 'doceboId' => '10101' );

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
     * @internal param array $parameters
     */

    public function testdeleteUserValid () {

        /** @var array $parameters */
        $parameters = array ( 'doceboId'  => '12370' );

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
     * @param $parameters
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

    /**
     * providerTesttesteditUserCustomErrors function.
     *
     * @access public
     */

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
     * @param $parameters
     * @dataProvider providerTesttesteditUser
     */

    public function testeditUser ( $parameters ) {

        $responseObj = phocebo::editUser ( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object missing attribute doceboId');

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

    }

    /**
     * providerTesttesteditUser function.
     *
     * @access public
     */

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
     * @internal param array $parameters
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
     * @internal param array $parameters
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
     * @internal param array $parameters
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

    /**
     * testgetUserGroupsCustomErrors function.
     *
     * @access public
     * @internal param array $parameters
     */

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

    /**
     * testgetUserGroupsCustomErrors function.
     *
     * @access public
     * @internal param array $parameters
     */

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

    /**
     * testloggedinUserCustomError function.
     *
     * @access public
     * @internal param array $parameters
     */

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

    /**
     * testloggedinUserValid function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testloggedinUserValid () {

        $parameters = array (

            'doceboId'                 => '12339',

        );

        $responseObj = phocebo::loggedinUser( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'loggedIn', $responseObj, "Object response missing attribute loggedIn" );

    }

    /**
     * testloggedinUserInValid function.
     *
     * @access public
     * @internal param array $parameters
     */

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
     * @internal param array $parameters
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
     * @internal param array $parameters
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
     * @internal param array $parameters
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
     * @internal param array $parameters
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
     * @internal param array $parameters
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
     * @internal param array $parameters
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


/**
 * testphoceboCourse class.
 */

class testphoceboCourse extends \PHPUnit_Framework_TestCase {

    /**
     * testuserCoursesCustomErrorNoDoceboId function.
     *
     * @access public
     * @internal param array $parameters
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


    /**
     * testuserCoursesValid function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testuserCoursesValid () {

        $parameters = array ('doceboId' => '12339');

        $responseObj = phocebo::userCourses( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );


    }


    /**
     * testlistCourses function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testlistCourses () {

        $responseObj = phocebo::listCourses();

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }

    /**
     * testlistUsersCourses function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testlistUsersCourses () {

        $parameters = array ('doceboId' => '12339');

        $responseObj = phocebo::listUsersCourses( $parameters );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }

    /**
     * testenrollUserInCourseCustomErrors function.
     *
     * @access public
     * @internal param array $parameters
     */

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

    /**
     * testenrollUserInCourse function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testenrollUserInCourse () {

        $parameters = array (

            'doceboId' => '12339',

            'courseCode'    => '14-06'

        );

        phocebo::unenrollUserInCourse($parameters);

        $responseObj = phocebo::enrollUserInCourse($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $responseObj = phocebo::enrollUserInCourse($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

    }

    /**
     * testunenrollUserInCourse function.
     *
     * @access public
     * @internal param array $parameters
     */

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

    /**
     * testunenrollUserInCourseError function.
     *
     * @access public
     * @internal param array $parameters
     */

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

    /**
     * testunenrollUserInCourseCustomError function.
     *
     * @access public
     * @internal param array $parameters
     */

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


    /**
     * testlistUserCourses function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testlistUserCourses () {

        $parameters = array (

            'doceboId' => '12339',

        );

        phocebo::enrollUserInCourse( [

            'doceboId' => '12339',

            'courseCode'    => '14-06'

        ]);

        $responseObj = phocebo::listUserCourses($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertInternalType( 'object', $responseObj->{'0'}, 'Object does not contain an object about course information');

        $this->assertObjectHasAttribute( 'course_info', $responseObj->{'0'}, 'Object response missing attribute "course_info"');

        $this->assertGreaterThanOrEqual ( '1' , count($responseObj), 'Object should have more than 1 element' );

    }


    /**
     * testlistUserCoursesNoCourse function.
     *
     * @access public
     * @internal param array $parameters
     */

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

    /**
     * upgradeUserToPowerUser function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function upgradeUserToPowerUser () {

        /** @var object $branchObj */
        $branchObj = phocebo::getBranchbyCode( array ( 'branchCode' => 'test' ) );

        /** @var object $userObj */
        $userObj = phocebo::getdoceboId( array ( 'email' => 'test@shrm.org' ) );

//        var_dump($userObj);

        $parameters = array (

            'branchId' => $branchObj->branchId,

            'ids'   => $userObj->doceboId

        );

/*
        $responseObj = phocebo::upgradeUserToPowerUser($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'assignedUsers', $responseObj, 'Object response missing attribute "assignedUsers"');
*/

    }


    /**
     * testgetBranchbyCodeValid function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testgetBranchbyCodeValid () {

        $parameters = array (

            'branchCode' => 'root',

        );

        $responseObj = phocebo::getBranchbyCode($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'branchId', $responseObj, 'Object response missing attribute "branchId"');

        $this->assertObjectHasAttribute( 'translation', $responseObj, 'Object response missing attribute "translation"');

        $translation = $responseObj->translation;

        $this->assertObjectHasAttribute( 'arabic', $translation, 'Object response missing attribute "arabic"');

        $this->assertObjectHasAttribute( 'bosnian', $translation, 'Object response missing attribute "bosnian"');

        $this->assertObjectHasAttribute( 'bulgarian', $translation, 'Object response missing attribute "bulgarian"');

        $this->assertObjectHasAttribute( 'croatian', $translation, 'Object response missing attribute "croatian"');

        $this->assertObjectHasAttribute( 'czech', $translation, 'Object response missing attribute "czech"');

        $this->assertObjectHasAttribute( 'danish', $translation, 'Object response missing attribute "danish"');

        $this->assertObjectHasAttribute( 'dutch', $translation, 'Object response missing attribute "dutch"');

        $this->assertObjectHasAttribute( 'english', $translation, 'Object response missing attribute "english"');

        $this->assertObjectHasAttribute( 'farsi', $translation, 'Object response missing attribute "farsi"');

        $this->assertObjectHasAttribute( 'finnish', $translation, 'Object response missing attribute "finnish"');

        $this->assertObjectHasAttribute( 'french', $translation, 'Object response missing attribute "french"');

        $this->assertObjectHasAttribute( 'german', $translation, 'Object response missing attribute "german"');

        $this->assertObjectHasAttribute( 'greek', $translation, 'Object response missing attribute "greek"');

        $this->assertObjectHasAttribute( 'hebrew', $translation, 'Object response missing attribute "hebrew"');

        $this->assertObjectHasAttribute( 'hungarian', $translation, 'Object response missing attribute "hungarian"');

        $this->assertObjectHasAttribute( 'indonesian', $translation, 'Object response missing attribute "indonesian"');

        $this->assertObjectHasAttribute( 'italian', $translation, 'Object response missing attribute "italian"');

        $this->assertObjectHasAttribute( 'japanese', $translation, 'Object response missing attribute "japanese"');

        $this->assertObjectHasAttribute( 'korean', $translation, 'Object response missing attribute "korean"');

        $this->assertObjectHasAttribute( 'norwegian', $translation, 'Object response missing attribute "norwegian"');

        $this->assertObjectHasAttribute( 'polish', $translation, 'Object response missing attribute "polish"');

        $this->assertObjectHasAttribute( 'portuguese', $translation, 'Object response missing attribute "portuguese"');

        $this->assertObjectHasAttribute( 'portuguese-br', $translation, 'Object response missing attribute "portuguese-br"');

        $this->assertObjectHasAttribute( 'romanian', $translation, 'Object response missing attribute "romanian"');

        $this->assertObjectHasAttribute( 'russian', $translation, 'Object response missing attribute "russian"');

        $this->assertObjectHasAttribute( 'simplified_chinese', $translation, 'Object response missing attribute "simplified_chinese"');

        $this->assertObjectHasAttribute( 'spanish', $translation, 'Object response missing attribute "spanish"');

        $this->assertObjectHasAttribute( 'swedish', $translation, 'Object response missing attribute "swedish"');

        $this->assertObjectHasAttribute( 'thai', $translation, 'Object response missing attribute "thai"');

        $this->assertObjectHasAttribute( 'turkish', $translation, 'Object response missing attribute "turkish"');

        $this->assertObjectHasAttribute( 'ukrainian', $translation, 'Object response missing attribute "ukrainian"');

    }

    /**
     * testgetBranchbyCodeInValid function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testgetBranchbyCodeInValid () {

        $parameters = array (

            'branchCode' => 'invalid',

        );

        $responseObj = phocebo::getBranchbyCode($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'branchId', $responseObj, 'Object response missing attribute "branchId"');

        $this->assertNull ( $responseObj->branchId,  'Parameter "branchId" should be NULL' );

    }

    /**
     * testgetBranchbyCodeCustomErrors function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testgetBranchbyCodeCustomErrors () {

        $parameters = array (

            'nobranchCode' => 'invalid',

        );

        $responseObj = phocebo::getBranchbyCode($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }

    /**
     * testgetBranchInfo function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testgetBranchInfo () {

        $parameters = array (

            'branchId' => "0",

        );

        $responseObj = phocebo::getBranchInfo($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'branchCode', $responseObj, 'Object response missing attribute "branchCode"');

        $this->assertObjectHasAttribute( 'translation', $responseObj, 'Object response missing attribute "translation"');

        $translation = $responseObj->translation;

        $this->assertObjectHasAttribute( 'arabic', $translation, 'Object response missing attribute "arabic"');

        $this->assertObjectHasAttribute( 'bosnian', $translation, 'Object response missing attribute "bosnian"');

        $this->assertObjectHasAttribute( 'bulgarian', $translation, 'Object response missing attribute "bulgarian"');

        $this->assertObjectHasAttribute( 'croatian', $translation, 'Object response missing attribute "croatian"');

        $this->assertObjectHasAttribute( 'czech', $translation, 'Object response missing attribute "czech"');

        $this->assertObjectHasAttribute( 'danish', $translation, 'Object response missing attribute "danish"');

        $this->assertObjectHasAttribute( 'dutch', $translation, 'Object response missing attribute "dutch"');

        $this->assertObjectHasAttribute( 'english', $translation, 'Object response missing attribute "english"');

        $this->assertObjectHasAttribute( 'farsi', $translation, 'Object response missing attribute "farsi"');

        $this->assertObjectHasAttribute( 'finnish', $translation, 'Object response missing attribute "finnish"');

        $this->assertObjectHasAttribute( 'french', $translation, 'Object response missing attribute "french"');

        $this->assertObjectHasAttribute( 'german', $translation, 'Object response missing attribute "german"');

        $this->assertObjectHasAttribute( 'greek', $translation, 'Object response missing attribute "greek"');

        $this->assertObjectHasAttribute( 'hebrew', $translation, 'Object response missing attribute "hebrew"');

        $this->assertObjectHasAttribute( 'hungarian', $translation, 'Object response missing attribute "hungarian"');

        $this->assertObjectHasAttribute( 'indonesian', $translation, 'Object response missing attribute "indonesian"');

        $this->assertObjectHasAttribute( 'italian', $translation, 'Object response missing attribute "italian"');

        $this->assertObjectHasAttribute( 'japanese', $translation, 'Object response missing attribute "japanese"');

        $this->assertObjectHasAttribute( 'korean', $translation, 'Object response missing attribute "korean"');

        $this->assertObjectHasAttribute( 'norwegian', $translation, 'Object response missing attribute "norwegian"');

        $this->assertObjectHasAttribute( 'polish', $translation, 'Object response missing attribute "polish"');

        $this->assertObjectHasAttribute( 'portuguese', $translation, 'Object response missing attribute "portuguese"');

        $this->assertObjectHasAttribute( 'portuguese-br', $translation, 'Object response missing attribute "portuguese-br"');

        $this->assertObjectHasAttribute( 'romanian', $translation, 'Object response missing attribute "romanian"');

        $this->assertObjectHasAttribute( 'russian', $translation, 'Object response missing attribute "russian"');

        $this->assertObjectHasAttribute( 'simplified_chinese', $translation, 'Object response missing attribute "simplified_chinese"');

        $this->assertObjectHasAttribute( 'spanish', $translation, 'Object response missing attribute "spanish"');

        $this->assertObjectHasAttribute( 'swedish', $translation, 'Object response missing attribute "swedish"');

        $this->assertObjectHasAttribute( 'thai', $translation, 'Object response missing attribute "thai"');

        $this->assertObjectHasAttribute( 'turkish', $translation, 'Object response missing attribute "turkish"');

        $this->assertObjectHasAttribute( 'ukrainian', $translation, 'Object response missing attribute "ukrainian"');



    }

    /**
     * testgetBranchbyInfoInValid function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testgetBranchbyInfoInValid () {

        $parameters = array (

            'branchId' => '-1',

        );

        $responseObj = phocebo::getBranchInfo($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

    }

    /**
     * testgetBranchbyInfoCustomErrors function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testgetBranchbyInfoCustomErrors () {

        $parameters = array (

            'nobranchId' => '0',

        );

        $responseObj = phocebo::getBranchInfo($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }

    /**
     * testgetBranchChildren function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testgetBranchChildren () {

        $parameters = array (

            'branchId' => '0',

        );

        $responseObj = phocebo::getBranchChildren($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'children', $responseObj, 'Object response missing attribute "children"');

    }

    /**
     * testgetBranchParentId function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testgetBranchParentId () {

        $testObj = phocebo::getBranchbyCode( array ( 'branchCode' => 'test' ));

        $parameters = array (

            'branchId' => $testObj->branchId,

        );

        $responseObj = phocebo::getBranchParentId($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'branchCode', $responseObj, 'Object response missing attribute "branchCode"');

    }

    /**
     * testassignUserToBranch function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testassignUserToBranch () {

        $branchObj = phocebo::getBranchbyCode( array ( 'branchCode' => 'test' ) );

        $userObj = phocebo::getdoceboId( array ( 'email' => 'test@shrm.org' ) );

        $parameters = array (

            'branchId' => $branchObj->branchId,

            'ids'   => $userObj->doceboId

        );

        $responseObj = phocebo::assignUserToBranch($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'assignedUsers', $responseObj, 'Object response missing attribute "assignedUsers"');

    }

    /**
     * testcreateBranch function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testcreateBranch () {

//        $parameters = array (
//
//            'branchCode' => 'Testing Branch Name as Code',
//
//            'parentBranchId' => '0',
//
//            'branchName'    => 'Testing Branch Name as Code'
//
//        );
//
//        $responseObj = phocebo::createBranch( $parameters );
//
//        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');
//
//        $this->assertTrue ( $responseObj->success,  'Success message should be true' );
//
//        $this->assertObjectHasAttribute( 'branchId', $responseObj, 'Object response missing attribute "branchId"');

    }

    /**
     * testcreateBranchCustomError function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testcreateBranchCustomError () {

        $parameters = array (

            'nobranchCode'    => 'TEST',

            'parentBranchId'    => '0',

            'branchName'    => 'Test Branch Creation'


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

}


?>