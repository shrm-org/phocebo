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


define('INI', '.env');

global $settings;

if (file_exists(INI)) {

    $settings = parse_ini_file (INI, true);

    /**
     * @const URL Docebo URL
     */

    define('URL', $settings['docebo']['url']);

    /**
     * @const KEY Docebo public Key
     */

    define('KEY', $settings['docebo']['key']);

    /**
     * @const SECRET Docebo secret Key
     */

    define('SECRET', $settings['docebo']['secret']);

    /**
     * @const SSO - Future SSO
     */

    define('SSO', $settings['docebo']['sso']);

    define('TEST_ACCOUNT', $settings['docebo']['test_account']);

    define('TEST_PASSWORD', $settings['docebo']['test_password']);

    define('TEST_ADMIN_ACCT', $settings['docebo']['test_admin_account']);

    define('TEST_ADMIN_PASSWORD', $settings['docebo']['test_admin_password']);

    define('TEST_POWER_USER', $settings['docebo']['test_power_user']);

    define('TEST_POWER_USER_PASSWORD', $settings['docebo']['test_power_user_password']);

    define('TEST_COURSE_CODE', $settings['docebo']['test_course_code']);

    define('ROOT_BRANCH', $settings['docebo']['root_branch_code']);

    define('USER_ADDED_FIELD', $settings['docebo']['test_added_field']);

    define('TEST_GROUP', $settings['docebo']['test_group_name']);

    define('TEST_BRANCH', $settings['docebo']['test_branch_name']);

    define('TEST_POWER_USER_PROFILE', $settings['docebo']['test_power_user_profile']);

    define('TEST_BRANCH_CREATE', $settings['docebo']['test_branch_create']);

} else die( "\nERROR: Phởcebo ingredients are missing (.env) \n\n");



/**
 * EnvironmentVariablesTest class.
 */

class EnvironmentVariablesTest extends \PHPUnit_Framework_TestCase {

    public function __construct ( $name = NULL, array $data = array(), $dataName = '' ) {

        global $settings;

//        $this->preSetUp();

        parent::__construct($name, $data, $dataName);

        $this->phocebo = new phocebo( $GLOBALS['settings']['docebo'] );

    }

    /**
     * testEnvironmentSettingsLoaded function.
     *
     * @access public
     */

    public function testEnvironmentSettingsLoaded( ) {

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




    /**
     * testGetHashParametersExist function.
     *
     * @access public
     * @internal param array $params
     * @internal param array $codice
     */

    public function testGetHashParametersExist() {

        $params = array ( 'userid', 'also_check_as_email' );

        $codice = $this->phocebo->getHash($params);

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

        $codice = $this->phocebo->getHash($params);

        $sha1_len = strlen ($codice['sha1']);

        $this->assertEquals(40, $sha1_len, "Sha1 not calculating incorrectly");

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

            'email'                     => TEST_ACCOUNT

        );

        $responseObj = $this->phocebo->addUser( $parameters );

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

            'email'                     => TEST_ACCOUNT

        );

        $responseObj = $this->phocebo->addUser( $parameters );

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

        $responseObj = $this->phocebo->addUser( $parameters );

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
     */

    public function testaddUserCustomErrors ( ) {

        $responseObj = $this->phocebo->addUser ( array ( 'nofirstName' => 'Test', 'nolastName' => 'Account', 'noemail' => TEST_ACCOUNT ) );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $responseObj = $this->phocebo->addUser ( array ( 'firstName' => 'Test', 'nolastName' => 'Account' ) );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $responseObj = $this->phocebo->addUser ( array ( 'firstName' => 'Test', 'lastName' => 'Account', 'noemail' => TEST_ACCOUNT ) );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }

    /**
     * testaddUser function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testaddUser () {

        $parameters = array (

            'email'                     => TEST_ACCOUNT

        );

        $userObj = $this->phocebo->getdoceboId ( $parameters );

        if ( $userObj->doceboId ) {

            $parameters = array ('doceboId' => $userObj->doceboId);

            $this->phocebo->deleteUser( $parameters );

            sleep(1);

        }

        $parameters = array (

            'firstName'                 => 'Test',

            'lastName'                  => 'User',

            'email'                     => TEST_ACCOUNT,

            'password'                  => TEST_PASSWORD,


        );

        $responseObj = $this->phocebo->addUser ( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute "success"' );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object missing attribute "doceboId"' );

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute "idst"' );

    }


    /**
     * testResponseIsAnObject function.
     *
     * @access public
     * @internal param string $action
     * @internal param array $data_params
     * @internal param string $response
     * @internal param string $json_error
     */

    public function testResponseIsAnObject() {

        $action = '/user/checkUsername';

        $data_params = array (

            'userid' => TEST_ACCOUNT,

        	'also_check_as_email' => true,

        );

        $response = $this->phocebo->call($action, $data_params, []);

        $this->assertEquals($response->success, true);

    }



    /**
     * testdoceboId function.
     *
     * Test if function is returning correct keys in object. Relies on testaddUser processing prior to testdoceboId
     *
     * @access public
     */

    public function testdoceboId ( ) {

        $responseObj = $this->phocebo->getdoceboId ( array( 'email' => TEST_ACCOUNT ) );

        $this->assertObjectHasAttribute( 'email', $responseObj, 'doceboId is valid but not reporting as valid');

        $responseObj = $this->phocebo->getdoceboId ( array( 'email' => 'someone@example.com' ) );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'doceboId is not valid but reporting as valid');

    }

    /**
     * testdoceboIdObj function.
     *
     * @access public
     *
     */


    public function testdoceboIdObj ( ) {

        $responseObj = $this->phocebo->getdoceboId ( array ( 'email' => TEST_ACCOUNT) );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'The attribute "doceboId" not in "$responseObj"');

        $this->assertObjectNotHasAttribute ( 'idst', $responseObj, 'The attribute "idst" should be removed from "$responseObj"');

        $this->assertObjectHasAttribute( 'firstName', $responseObj, 'The attribute "firstName" not in "$responseObj"');

        $this->assertObjectHasAttribute( 'lastName', $responseObj, 'The attribute "lastName" not in "$responseObj"');

        $this->assertObjectHasAttribute( 'email', $responseObj, 'The attribute "email" not in "$responseObj"');

    }

    /**
     * testdoceboIdCustomErrorsJSONformat function.
     *
     * @access public
     *
     */

    public function testdoceboIdCustomErrorsJSONformat ( ) {

        $responseObj = $this->phocebo->getdoceboId( array( 'noemail' => TEST_ACCOUNT) );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute "success"');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute "error"');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute "message"');

    }


    /**
     * testdoceboIdCustomErrors function.
     *
     * @access public
     */

    public function testdoceboIdCustomErrors ( ) {

        $responseObj = $this->phocebo->getdoceboId( array( 'noemail' => TEST_ACCOUNT) );

        $this->assertEquals( $responseObj->error, '400', 'JSON response should be reporting error 400' );

        $responseObj = $this->phocebo->getdoceboId( array ( 'email' => 'not an email address' ) );

        $this->assertEquals( $responseObj->error, '400', 'JSON response should be reporting error 400' );

    }


    /**
     * testauthenticateUserValid function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testauthenticateUserValid ( ) {

        $parameters = array( 'username' => TEST_ACCOUNT, 'password' => TEST_PASSWORD );

        $responseObj = $this->phocebo->authenticateUser ( $parameters );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object missing attribute "success"');

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
     * @dataProvider authenticateUserInvalidProvider
     */

    public function testauthenticateUserInvalid ( $parameters ) {

        $responseObj = $this->phocebo->authenticateUser ( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

    }

    public function authenticateUserInvalidProvider() {

        return array (

            'no username' =>  array( 'parameters' => array ('username' => '', 'password' => TEST_PASSWORD ) ),

            'no password' =>  array( 'parameters' => array( 'username' => TEST_ACCOUNT, 'password' => '' ) ),

            'invalid username' =>  array( 'parameters' =>  array ( 'username' => 'notest@shrm.org', 'password' => TEST_PASSWORD ) ),

            'invalid username no password' =>  array( 'parameters' => array ('username' => 'notest@shrm.org', 'password' => '' ) ),

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

        $responseObj = $this->phocebo->authenticateUser ( $parameters );

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

            array ( array( 'username' => '', 'password' => TEST_PASSWORD ) ),

            array ( array( 'username' => TEST_ACCOUNT, 'password' => '' ) ),

        );

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

        $responseObj = $this->phocebo->deleteUser( $parameters );

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

        $responseObj = $this->phocebo->deleteUser( $parameters );

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

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        /** @var array $parameters */

        $parameters = array ( 'doceboId'  => $userObj->doceboId );

        $responseObj = $this->phocebo->deleteUser( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object missing attribute success');

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

        $parameters = array (

            'firstName'                 => 'Test',

            'lastName'                  => 'Account',

            'email'                     => TEST_ACCOUNT,

            'password'                  => TEST_PASSWORD

        );

        $this->phocebo->addUser ( $parameters );

    }


    /**
     * testeditUserCustomErrors function.
     *
     * @access public
     * @param $parameters
     * @dataProvider providerTesttesteditUserCustomErrors
     */

    public function testeditUserCustomErrors ( $parameters ) {

        $responseObj = $this->phocebo->editUser ( $parameters );

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
     */

    public function testeditUserEmail ( ) {

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $responseObj = $this->phocebo->editUser ( array ( 'doceboId' => $userObj->doceboId, 'email' => 'test2@shrm.org') );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object missing attribute doceboId');

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

        $this->phocebo->editUser ( array ( 'doceboId' => $userObj->doceboId, 'email' => TEST_ACCOUNT) );

    }


    /**
     * testeditUserCustomServerErrors function.
     *
     * @access public
     * @param $parameters
     * @dataProvider providerTesttesteditUser
     */

    public function testeditUser ( $parameters ) {

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters['doceboId'] =  $userObj->doceboId;

        $responseObj = $this->phocebo->editUser ( $parameters );

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

            array ( array ( 'firstName' => 'Change First Name') ),

            array ( array ( 'lastName' => 'Change Last Name') ),

            array ( array ( 'firstName' => 'Change First and Last Name', 'lastName' => 'Change First and Last Name') ),

            array ( array ( 'password' => 'Change Password') ),

            array ( array ( 'valid' => false) ),

            array ( array ( 'unenroll_deactivated' => false) ),

            array ( array ( 'firstName' => 'Test', 'lastName' => 'Account') ),

            array ( array ( 'password' => TEST_PASSWORD) ),

            array ( array ( 'email' => TEST_ACCOUNT) ),

            array ( array ( 'valid' => true) ),

            array ( array ( 'unenroll_deactivated' => true ) ),

        );

    }





    /**
     * testgetUserFields function.
     *
     * @access public
     */

    public function testgetUserFields () {

        if (NULL != USER_ADDED_FIELD) {

            $responseObj = $this->phocebo->getUserFields( );

            $this->assertObjectHasAttribute( 'fields', $responseObj, "Object response missing attribute fields" );

            $fields = $responseObj->fields;

            $this->assertObjectHasAttribute( 'id', $fields['0'], "Object response missing attribute id" );

            $this->assertObjectHasAttribute( 'name', $fields['0'], "Object response missing attribute name" );

            $this->assertEquals ($fields['0']->name, USER_ADDED_FIELD, 'User Fields in Docebo does not have Job Role' );

        }

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

        $responseObj = $this->phocebo->getUserProfile( $parameters );

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

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'doceboId'                 => $userObj->doceboId,

        );

        $responseObj = $this->phocebo->getUserProfile( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertTrue ( $responseObj->success,  'Success message should be true ' );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object response missing attribute "doceboId"' );

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute "idst"');

        $this->assertObjectHasAttribute( 'firstName', $responseObj, 'Object response missing attribute "firstName"' );

        $this->assertObjectHasAttribute( 'lastName', $responseObj, 'Object response missing attribute "lastName"' );

        $this->assertObjectHasAttribute( 'email', $responseObj, 'Object response missing attribute "email"' );

        $this->assertObjectHasAttribute( 'valid', $responseObj, 'Object response missing attribute "valid"' );

        $this->assertObjectHasAttribute( 'registerDate', $responseObj, 'Object response missing attribute "registerDate"' );

        $this->assertObjectHasAttribute( 'lastEnter', $responseObj, 'Object response missing attribute "lastEnter"' );

        $this->assertObjectHasAttribute( 'fields', $responseObj, 'Object response missing attribute "fields"' );

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

        $responseObj = $this->phocebo->getUserProfile( $parameters );

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

        $responseObj = $this->phocebo->getUserGroups( $parameters );

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

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $groupObj = $this->phocebo->listGroups();

        $array = (array) $groupObj;

        $learnerGroupObj = $array[TEST_GROUP];

        $parameters = array (

            'doceboId'               => $userObj->doceboId,

            'groupId'                => $learnerGroupObj->id

        );

        $this->phocebo->assignUserToGroup( $parameters );

        $branchObj = $this->phocebo->getBranchbyCode( array ( 'branchCode' => TEST_BRANCH ) );

        $parameters = array (

            'doceboIds'               => $userObj->doceboId,

            'branchId'                => $branchObj->branchId

        );

        $this->phocebo->assignUserToBranch( $parameters );

        $parameters = array (

            'email'                 => TEST_ACCOUNT,

        );

        $responseObj = $this->phocebo->getUserGroups( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"' );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'groupIds', $responseObj, 'Object response missing attribute "groupIds"' );

        $this->assertObjectHasAttribute( 'branchIds', $responseObj, 'Object response missing attribute "branchIds"' );

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

        $responseObj = $this->phocebo->loggedinUser( $parameters );

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

//        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );
//
//        $parameters = array (
//
//            'doceboId'                 => $userObj->doceboId,
//
//        );
//
//        $responseObj = $this->phocebo->loggedinUser( $parameters );
//
//        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );
//
//        $this->assertTrue ( $responseObj->success,  'Success message should be true' );
//
//        $this->assertObjectHasAttribute( 'loggedIn', $responseObj, "Object response missing attribute loggedIn" );

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

        $responseObj = $this->phocebo->loggedinUser( $parameters );

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

        $responseObj = $this->phocebo->suspendUser( $parameters );

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

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'doceboId'                 => $userObj->doceboId,

        );

        $responseObj = $this->phocebo->suspendUser( $parameters );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object response missing attribute doceboId');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->phocebo->unsuspendUser( $parameters );

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

        $responseObj = $this->phocebo->suspendUser( $parameters );

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

        $responseObj = $this->phocebo->unsuspendUser( $parameters );

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

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'doceboId'                 => $userObj->doceboId,

        );

        $this->phocebo->suspendUser( $parameters );

        $responseObj = $this->phocebo->unsuspendUser( $parameters );

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

        $responseObj = $this->phocebo->unsuspendUser( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object response missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object response missing attribute message');

    }

    /**
     * testuserCoursesCustomErrorNoDoceboId function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testuserCoursesCustomErrorNoDoceboId () {

        $parameters = array ('nodoceboId' => '10101');

        $responseObj = $this->phocebo->userCourses( $parameters );

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

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'doceboId'      => $userObj->doceboId,

            'courseCode'    => TEST_COURSE_CODE

        );

        $this->phocebo->enrollUserInCourse($parameters);

        $responseObj = $this->phocebo->userCourses( array( 'doceboId' => $userObj->doceboId ) );

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

        $responseObj = $this->phocebo->listCourses();

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

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $responseObj = $this->phocebo->listUserCourses( array ('doceboId' => $userObj->doceboId) );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }

    /**
     * testenrollUserInCourseCustomErrors function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testenrollUserInCourseCustomErrors () {

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'nodoceboId'      => $userObj->doceboId,

            'courseCode'    => TEST_COURSE_CODE

        );

        $responseObj = $this->phocebo->enrollUserInCourse($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'doceboId'      => $userObj->doceboId,

            'nocourseCode'    => TEST_COURSE_CODE

        );

        $responseObj = $this->phocebo->enrollUserInCourse($parameters);

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

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'doceboId'      => $userObj->doceboId,

            'courseCode'    => TEST_COURSE_CODE

        );

        $this->phocebo->unenrollUserInCourse($parameters);

        $responseObj = $this->phocebo->enrollUserInCourse($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $responseObj = $this->phocebo->enrollUserInCourse($parameters);

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

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'doceboId'      => $userObj->doceboId,

            'courseCode'    => TEST_COURSE_CODE

        );

        $this->phocebo->enrollUserInCourse($parameters);

        $responseObj = $this->phocebo->unenrollUserInCourse($parameters);

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

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'doceboId'      => $userObj->doceboId,

            'courseCode'    => TEST_COURSE_CODE

        );

        $responseObj = $this->phocebo->unenrollUserInCourse($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertFalse ( $responseObj->success,  'Success message should be "false"' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object response missing attribute "error"' );

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object response missing attribute "message"' );

    }

    /**
     * testunenrollUserInCourseCustomError function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testunenrollUserInCourseCustomError () {

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'nodoceboId'      => $userObj->doceboId,

            'courseCode'    => TEST_COURSE_CODE

        );

        $this->phocebo->enrollUserInCourse($parameters);

        $responseObj = $this->phocebo->unenrollUserInCourse($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $parameters = array (

            'doceboId'      => $userObj->doceboId,

            'nocourseCode'    => TEST_COURSE_CODE

        );

        $this->phocebo->enrollUserInCourse($parameters);

        $responseObj = $this->phocebo->unenrollUserInCourse($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }


    /**
     * testlistUserCourses function.
     *
     * if this test fails check if test@shrm.org is a valid user and if the courseCode if valid
     * @access public
     * @internal param array $parameters
     * @todo fix reference to  $responseObj->{'0'}
     */

    public function testlistUserCourses () {

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'doceboId'      => $userObj->doceboId,

            'courseCode'    => TEST_COURSE_CODE

        );

        $this->phocebo->enrollUserInCourse($parameters);

        $responseObj = $this->phocebo->listUserCourses($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'list', $responseObj, 'Object response missing attribute "list"');

        $list = $responseObj->list;

        $course_info = $list[0];

        $this->assertObjectHasAttribute( 'course_id', $course_info->course_info, 'Object response missing attribute "course_id"');

        $this->assertObjectHasAttribute( 'code', $course_info->course_info, 'Object response missing attribute "code"');

        $this->assertObjectHasAttribute( 'course_name', $course_info->course_info, 'Object response missing attribute "course_name"');

        $this->assertObjectHasAttribute( 'credits', $course_info->course_info, 'Object response missing attribute "credits"');

        $this->assertObjectHasAttribute( 'total_time', $course_info->course_info, 'Object response missing attribute "total_time"');

        $this->assertObjectHasAttribute( 'enrollment_date', $course_info->course_info, 'Object response missing attribute "enrollment_date"');

        $this->assertObjectHasAttribute( 'completion_date', $course_info->course_info, 'Object response missing attribute "completion_date"');

        $this->assertObjectHasAttribute( 'first_access_date', $course_info->course_info, 'Object response missing attribute "first_access_date"');

        $this->assertObjectHasAttribute( 'score', $course_info->course_info, 'Object response missing attribute "score"');

        $this->assertObjectHasAttribute( 'status', $course_info->course_info, 'Object response missing attribute "status"');

    }


    /**
     * testlistUserCoursesNoCourse function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testlistUserCoursesNoCourse () {

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'doceboId'      => $userObj->doceboId,

            'courseCode'    => TEST_COURSE_CODE

        );

        $this->phocebo->enrollUserInCourse($parameters);

        $this->phocebo->unenrollUserInCourse( array (

            'doceboId' => $userObj->doceboId,

            'courseCode'    => TEST_COURSE_CODE

        ) );

        $responseObj = $this->phocebo->listUserCourses( array ('doceboId' => $userObj->doceboId ) );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }

    /**
     * upgradeUserToPowerUser function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function upgradeUserToPowerUser () {

        /** @var object $branchObj */
        $branchObj = $this->phocebo->getBranchbyCode( array ( 'branchCode' => TEST_BRANCH ) );

        /** @var object $userObj */
        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'branchId' => $branchObj->branchId,

            'profileName' => TEST_POWER_USER_PROFILE,

            'ids'   => $userObj->doceboId

        );

        $responseObj = $this->phocebo->upgradeUserToPowerUser($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'assignedUsers', $responseObj, 'Object response missing attribute "assignedUsers"');

        $this->phocebo->downgradeUserFromPowerUser( array(  'doceboId' => $userObj->doceboId ));

    }


    /**
     * testassignCourseToPowerUserNotPowerUser function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testassignCourseToPowerUserNotPowerUser () {

        /** @var object $userObj */
        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $this->phocebo->downgradeUserFromPowerUser( array(  'doceboId' => $userObj->doceboId ));

        $parameters = array (

            'doceboId'   => $userObj->doceboId,

            'items' => TEST_COURSE_CODE

        );

        $responseObj = $this->phocebo->assignCourseToPowerUser($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertFalse( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object response missing attribute "success"');


    }

    /**
     * testassignCourseToPowerUserCustomErrors function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testassignCourseToPowerUserCustomErrors () {

        /** @var object $userObj */
        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_POWER_USER ) );

        $parameters = array (

            'items' => TEST_COURSE_CODE

        );

        $responseObj = $this->phocebo->assignCourseToPowerUser($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertFalse ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object response missing attribute "success"');

        $parameters = array (

            'doceboId'   => $userObj->doceboId,

        );

        $responseObj = $this->phocebo->assignCourseToPowerUser($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertFalse ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object response missing attribute "success"');

    }

    /**
     * testassignCoursesToPowerUser function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testassignCourseToPowerUser () {

        /** @var object $userObj */
        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_POWER_USER ) );

        $parameters = array (

            'doceboId'   => $userObj->doceboId,

            'items' => TEST_COURSE_CODE

        );

        $responseObj = $this->phocebo->assignCourseToPowerUser($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }

    /**
     * testgetBranchbyCodeValid function.
     *
     * if this test fails check Docebo Root folder should have Org Chart Code as root and Name in English as root
     * @access public
     * @internal param array $parameters
     */

    public function testgetBranchbyCodeValid () {

        $parameters = array (

            'branchCode' => ROOT_BRANCH,

        );

        $responseObj = $this->phocebo->getBranchbyCode($parameters);

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

        $responseObj = $this->phocebo->getBranchbyCode($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be true' );

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

        $responseObj = $this->phocebo->getBranchbyCode($parameters);

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

        $responseObj = $this->phocebo->getBranchInfo($parameters);

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

        $responseObj = $this->phocebo->getBranchInfo($parameters);

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

        $responseObj = $this->phocebo->getBranchInfo($parameters);

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

        $responseObj = $this->phocebo->getBranchChildren($parameters);

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

        $parameters = array (

            'parentBranchId' => '0',

            'branchName'    => TEST_BRANCH_CREATE,

            'branchCode'    => TEST_BRANCH_CREATE

        );

        $this->phocebo->createBranch( $parameters );

        $testObj = $this->phocebo->getBranchbyCode( array ( 'branchCode' => TEST_BRANCH_CREATE ));

        $parameters = array (

            'branchId' => $testObj->branchId,

        );

        $responseObj = $this->phocebo->getBranchParentId($parameters);

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

        $parameters = array (

            'parentBranchId' => '0',

            'branchName'    => TEST_BRANCH_CREATE,

            'branchCode'    => TEST_BRANCH_CREATE

        );

        $this->phocebo->createBranch( $parameters );

        $branchObj = $this->phocebo->getBranchbyCode( array ( 'branchCode' => TEST_BRANCH_CREATE ) );

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'branchId' => $branchObj->branchId,

            'doceboIds'   => $userObj->doceboId

        );

        $responseObj = $this->phocebo->assignUserToBranch($parameters);

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

        $branchObj = $this->phocebo->getBranchbyCode( array ('branchCode' => TEST_BRANCH_CREATE ) );

        $this->phocebo->deleteBranch( array ('branchId' => $branchObj->branchId ) );

        $parameters = array (

            'parentBranchId' => '0',

            'branchName'    => TEST_BRANCH_CREATE,

            'branchCode'    => TEST_BRANCH_CREATE

        );

        $responseObj = $this->phocebo->createBranch( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'branchId', $responseObj, 'Object response missing attribute "branchId"');

        $this->phocebo->deleteBranch( array ('branchId' => $responseObj->branchId ) );

    }

    /**
     * testcreateBranchCustomError function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testcreateBranchCustomError () {

        $parameters = array (

            'nobranchCode'    => TEST_BRANCH_CREATE,

            'parentBranchId'    => '0',

            'branchName'    => TEST_BRANCH_CREATE

        );

        $responseObj = $this->phocebo->createBranch($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"' );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $parameters = array (

            'branchCode'    => TEST_BRANCH_CREATE,

            'nobranchName'    => 'Test Branch Creation',

            'parentBranchId'    => 'Parent Branch ID'

        );

        $responseObj = $this->phocebo->createBranch($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $parameters = array (

            'branchCode'    => TEST_BRANCH_CREATE,

            'branchName'    => 'Test Branch Creation',

            'noparentBranchId'    => 'Parent Branch ID'

        );

        $responseObj = $this->phocebo->createBranch($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }

    /**
     * testlistGroups function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testlistGroups () {

        $responseObj = $this->phocebo->listGroups();

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }


    /**
     * testassignUserToBranch function.
     *
     * @access public
     * @internal param array $parameters
     */

    public function testgetGroupId () {

        $parameters = array (

            'groupName'    => TEST_GROUP,

        );

        $response = $this->phocebo->getGroupId($parameters);

        $this->assertStringMatchesFormat('%d', $response);

    }

    public function testassignUserToGroup () {

        $groupId = $this->phocebo->getGroupId(array ( 'groupName' => TEST_GROUP ) );

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $parameters = array (

            'groupId' => $groupId,

            'doceboId'   => $userObj->doceboId

        );

        $this->phocebo->unassignUserFromGroup($parameters);

        $responseObj = $this->phocebo->assignUserToGroup($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->phocebo->unassignUserFromGroup($parameters);

    }

    /**
     * testlistProfiles function.
     *
     * @access public
     * @internal param array $parameters
     * @todo expand tests for poweruser profiles list
     */

    public function testlistProfiles () {

        $responseObj = $this->phocebo->listProfiles();

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }

    /**
     * testgetProfileId function.
     *
     * @access public
     * @internal param array $parameters
     * @todo expand tests for poweruser profiles list
     * 122713
     */

    public function testgetProfileId () {

        $parameters = array (

            'profileName' => TEST_POWER_USER_PROFILE

        );

        $response = $this->phocebo->getProfileId($parameters);

        $this->assertStringMatchesFormat('%d', $response);

    }



}

?>