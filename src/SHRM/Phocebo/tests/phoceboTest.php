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
 * Run All Tests
 *     ./vendor/bin/phpunit
 * Run Single Test
 *     ./vendor/bin/phpunit --filter testupgradeUserToPowerUser src/SHRM/Phocebo/tests/phoceboTest.php
 *
 * Run Test with Results in HTML
 * ./vendor/bin/phpunit src/SHRM/Phocebo/tests/phoceboTest.php --testdox-html  src/SHRM/Phocebo/tests/test_results.html
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
 * PhởceboTest class.
 */

class PhoceboAPITest extends \PHPUnit_Framework_TestCase {

    public function __construct ( $name = NULL, array $data = array(), $dataName = '' ) {

        global $settings;

//        $this->preSetUp();

        parent::__construct($name, $data, $dataName);

        $this->phocebo = new phocebo( $GLOBALS['settings']['docebo'] );

    }

    /**
     * testEnvironmentSettingsLoaded function.
     * @group Environment
     */

    public function testEnvironmentSettingsLoaded( ) {

        global $settings;

        $this->assertArrayHasKey("docebo", $settings, "Environment settings not loaded");

    }

    /**
     * testUrlIsNotBlank function.
     * @group Environment
     */

    public function testUrlIsNotBlank() {

        $this->assertNotEquals(URL, "URL", "Missing Docebo URL");

    }

    /**
     * testEnvironmentVariableUrlIsValid function.
     * @group Environment
     */

    public function testEnvironmentVariableUrlIsValid() {

        $URLisValid = true;

        if (filter_var( URL, FILTER_VALIDATE_URL) === FALSE) {

            $URLisValid = false;
        }

        $this->assertTrue($URLisValid, "The Docebo URL is invalid");

    }


    /**
     * testEnvironmentVariableKeyIsNotBlank function.
     * @group Environment
     */

    public function testEnvironmentVariableKeyIsNotBlank() {

        $this->assertNotEquals(KEY, "KEY", "Missing Docebo public key");

    }

    /**
     * testEnvironmentVariableSecretIsNotBlank function.
     * @group Environment
     */

    public function testEnvironmentVariableSecretIsNotBlank() {

        $this->assertNotEquals(SECRET, "SECRET", "Missing Docebo secret key");

    }

    /**
     * testEnvironmentVariableSsoIsNotBlank function.
     * @group Environment
     */

    public function testEnvironmentVariableSsoIsNotBlank() {

        $this->assertNotEquals(SSO, "SSO", "Missing Docebo SSO");

    }


    /**
     * testGetHashParametersExist function.
     * @group Connection
     */

    public function testGetHashParametersExist() {

        $params = array ( 'userid', 'also_check_as_email' );

        $codice = $this->phocebo->getHash($params);

        $this->assertNotEmpty($codice, "GetHash returned a Null Value");

    }

    /**
     * testGetHashsha1String40 function.
     * @group Connection
     */

    public function testGetHashsha1String40() {

        $params = array ( 'userid', 'also_check_as_email' );

        $codice = $this->phocebo->getHash($params);

        $sha1_len = strlen ($codice['sha1']);

        $this->assertEquals(40, $sha1_len, "Sha1 not calculating incorrectly");

    }

    /**
     * testAddUserCustomErrorsJsonFormatFirstName function.
     * @group Response
     */

    public function testAddUserCustomErrorsJsonFormatFirstName ( ) {

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
     * testAddUserCustomErrorsJsonFormatLastName function.
     * @group Response
     */

    public function testAddUserCustomErrorsJsonFormatLastName ( ) {

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
     * testAddUserCustomErrorsJsonFormatEmail function.
     * @group Response
     */

    public function testAddUserCustomErrorsJsonFormatEmail ( ) {

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
     * testAddUserCustomErrors function.
     * @group User
     */

    public function testAddUserCustomErrors ( ) {

        $responseObj = $this->phocebo->addUser ( array ( 'nofirstName' => 'Test', 'nolastName' => 'Account', 'noemail' => TEST_ACCOUNT ) );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $responseObj = $this->phocebo->addUser ( array ( 'firstName' => 'Test', 'nolastName' => 'Account' ) );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

        $responseObj = $this->phocebo->addUser ( array ( 'firstName' => 'Test', 'lastName' => 'Account', 'noemail' => TEST_ACCOUNT ) );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }

    /**
     * testAddUser function.
     * @group User
     */

    public function testAddUser () {

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
     * @group Response
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
     * testValidDoceboid function.
     * @group User
     */

    public function testValidDoceboid ( ) {

        $responseObj = $this->phocebo->getdoceboId ( array( 'email' => TEST_ACCOUNT ) );

        $this->assertObjectHasAttribute( 'email', $responseObj, 'doceboId is valid but not reporting as valid');

    }

    /**
     * testInalidDoceboid function.
     * @group User
     */

    public function testInalidDoceboid ( ) {

        $responseObj = $this->phocebo->getdoceboId ( array( 'email' => 'someone@example.com' ) );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'doceboId is not valid but reporting as valid');

    }

    /**
     * testValidDoceboidObject function.
     * @group User
     */

    public function testValidDoceboidObject ( ) {

        $responseObj = $this->phocebo->getdoceboId ( array ( 'email' => TEST_ACCOUNT) );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'The attribute "doceboId" not in "$responseObj"');

        $this->assertObjectNotHasAttribute ( 'idst', $responseObj, 'The attribute "idst" should be removed from "$responseObj"');

        $this->assertObjectHasAttribute( 'firstName', $responseObj, 'The attribute "firstName" not in "$responseObj"');

        $this->assertObjectHasAttribute( 'lastName', $responseObj, 'The attribute "lastName" not in "$responseObj"');

        $this->assertObjectHasAttribute( 'email', $responseObj, 'The attribute "email" not in "$responseObj"');

    }

    /**
     * testCustomErrorsJsonResponesFordoceboId function.
     * @group User
     */

    public function testCustomErrorsJsonResponesFordoceboId ( ) {

        $responseObj = $this->phocebo->getdoceboId( array( 'noemail' => TEST_ACCOUNT) );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute "success"');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute "error"');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute "message"');

    }


    /**
     * testInvalidDoceboidCustomErrors function.
     * @group User
     */

    public function testInvalidDoceboidCustomErrors ( ) {

        $responseObj = $this->phocebo->getdoceboId( array( 'noemail' => TEST_ACCOUNT) );

        $this->assertEquals( $responseObj->error, '400', 'JSON response should be reporting error 400' );

        $responseObj = $this->phocebo->getdoceboId( array ( 'email' => 'not an email address' ) );

        $this->assertEquals( $responseObj->error, '400', 'JSON response should be reporting error 400' );

    }


    /**
     * testValidAuthenticateUser function.
     * @group User
     */

    public function testValidAuthenticateUser ( ) {

        $parameters = array( 'username' => TEST_ACCOUNT, 'password' => TEST_PASSWORD );

        $responseObj = $this->phocebo->authenticateUser ( $parameters );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object missing attribute "success"');

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'token', $responseObj, 'Object missing attribute token');


    }

    /**
     * testInvalidAuthenticateUser function.
     * @group User
     * @dataProvider authenticateUserInvalidProvider
     */

    public function testInvalidAuthenticateUser ( $parameters ) {

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
     * testInvalidMessagesForAuthenticateUser function.
     * @group User
     * @dataProvider providerTesttestauthenticateUserInvalidJSONMessage400
     */

    public function testInvalidMessagesForAuthenticateUser ( $parameters ) {

        $responseObj = $this->phocebo->authenticateUser ( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertEquals ( $responseObj->error, '400', "Object response should be reporting error 400" );

    }

    /**
     * providerTesttestauthenticateUserInvalidJSONMessage400 function.
     */

    public function providerTesttestauthenticateUserInvalidJSONMessage400() {

        return array(

            array ( array( 'doceboId' => '11111' ) ),

            array ( array( 'username' => '', 'password' => TEST_PASSWORD ) ),

            array ( array( 'username' => TEST_ACCOUNT, 'password' => '' ) ),

        );

    }


    /**
     * testCustomErrorDeleteUser function.
     * @group User
     */

    public function testCustomErrorDeleteUser () {

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
     * testDeleteUserDoesntExist function.
     * @group User
     */

    public function testDeleteUserDoesntExist () {

        $parameters = array ( 'doceboId' => '10101' );

        $responseObj = $this->phocebo->deleteUser( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

        $this->assertEquals ( $responseObj->error, '211', 'Object response should be reporting error 211' );

    }

    /**
     * testDeleteValidUser function.
     * @group User
     */

    public function testDeleteValidUser () {

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
     * testCustomErrorsWhenEditingUser function.
     * @group User
     * @dataProvider providerTesttesteditUserCustomErrors
     */

    public function testCustomErrorsWhenEditingUser ( $parameters ) {

        $responseObj = $this->phocebo->editUser ( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, 'Object missing attribute error');

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object missing attribute message');

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }

    /**
     * providerTesttesteditUserCustomErrors function.
     */

    public function providerTesttesteditUserCustomErrors() {

        return array(

            array ( array ( 'nodoceboId' => '10101' ) ),

            array ( array ( 'doceboId' => '10101' ) ),

            array ( array ( 'doceboId' => '10101', 'email' => 'test invalid email' ) ),

        );

    }

    /**
     * testEditUserEmail function.
     * @group User
     */

    public function testEditUserEmail ( ) {

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $responseObj = $this->phocebo->editUser ( array ( 'doceboId' => $userObj->doceboId, 'email' => 'test2@shrm.org') );

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'doceboId', $responseObj, 'Object missing attribute doceboId');

        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'Object response should not have attribute idst');

        $this->phocebo->editUser ( array ( 'doceboId' => $userObj->doceboId, 'email' => TEST_ACCOUNT) );

    }


    /**
     * testEditUser function.
     * @group User
     * @dataProvider providerTesttesteditUser
     */

    public function testEditUser ( $parameters ) {

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
     * testGetUserFields function.
     * @group User
     */

    public function testGetUserFields () {

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
     * testCustomErrorsForGetUserProfile function.
     * @group User
     */

    public function testCustomErrorsForGetUserProfile () {

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
     * testGetValidUserProfile function.
     * @group User
     */

    public function testGetValidUserProfile () {

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
     * testGetInvalidUserProfile function.
     * @group User
     */

    public function testGetInvalidUserProfile () {

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
     * testCustomErrorsForGettingUserGroups function.
     * @group User
     */

    public function testCustomErrorsForGettingUserGroups () {

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
     * testGettingValidUserGroups function.
     * @group User
     */

    public function testGettingValidUserGroups () {

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

        $this->assertObjectHasAttribute( 'groups', $responseObj, 'Object response missing attribute "groups"' );

        $this->assertObjectHasAttribute( 'branches', $responseObj, 'Object response missing attribute "branches"' );

    }



    /**
     * testCustomErrorsForUserLoggedIn function.
     * @group Access
     */

    public function testCustomErrorsForUserLoggedIn () {

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
     * testValidLoggedInUserValid function.
     * @group Access
     * @todo Fix Tests
     */

    public function testValidLoggedInUserValid () {

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
     * testInvalidUserLoggedIn function.
     * @group Access
     */

    public function testInvalidUserLoggedIn () {

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
     * testCustomErrorsSuspendUser function.
     * @group User
     */

    public function testCustomErrorsSuspendUser () {

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
     * testValidSuspendUser function.
     * @group User
     */

    public function testValidSuspendUser () {

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
     * testInvalidSuspendUser function.
     * @group User
     */

    public function testInvalidSuspendUser () {

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
     * testCustomErrorsUnsuspendUser function.
     * @group User
     */

    public function testCustomErrorsUnsuspendUser () {

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
     * testValidUserUnsuspend function.
     * @group User
     */

    public function testValidUserUnsuspend () {

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
     * testInvalidUnsuspendUser function.
     * @group User
     */

    public function testInvalidUnsuspendUser () {

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
     * testCustomErrorNoDoceboidForUserCourses function.
     * @group Course
     */

    public function testCustomErrorNoDoceboidForUserCourses () {

        $parameters = array ('nodoceboId' => '10101');

        $responseObj = $this->phocebo->userCourses( $parameters );

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertFalse ( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'error', $responseObj, "Object response missing attribute error" );

        $this->assertObjectHasAttribute( 'message', $responseObj, "Object response missing attribute message" );

        $this->assertEquals ( $responseObj->error, '400', 'Object response should be reporting error 400' );

    }


    /**
     * testValidUserCourses function.
     * @group Course
     */

    public function testValidUserCourses () {

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
     * testListCourses function.
     * @group Course
     */

    public function testListCourses () {

        $responseObj = $this->phocebo->listCourses();

        $this->assertObjectHasAttribute( 'success', $responseObj, "Object response missing attribute success" );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }

    /**
     * testListUsersCourses function.
     * @group Course
     */

    public function testListUsersCourses () {

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $responseObj = $this->phocebo->listUserCourses( array ('doceboId' => $userObj->doceboId) );

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }

    /**
     * testCustomErrorsEnrollUserInCourse function.
     * @group Course
     */

    public function testCustomErrorsEnrollUserInCourse () {

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
     * testEnrollUserInCourse function.
     * @group Course
     */

    public function testEnrollUserInCourse () {

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
     * testUnenrollUserInCourse function.
     * @group Course
     */

    public function testUnenrollUserInCourse () {

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
     * testErrorWithUnenrollUserInCourse function.
     * @group Course
     */

    public function testErrorWithUnenrollUserInCourse () {

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
     * testCustomErrorUnenrollUserInCourse function.
     * @group Course
     */

    public function testCustomErrorUnenrollUserInCourse () {

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
     * testListUserCourses function.
     * @group Course
     *
     * if this test fails check if test@shrm.org is a valid user and if the courseCode if valid
     * @todo fix reference to  $responseObj->{'0'}
     */

    public function testListUserCourses () {

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
     * testListUserWithNoCourse function.
     * @group Course
     */

    public function testListUserWithNoCourse () {

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
     * testUpgradeUserToPowerUser function.
     * @group PowerUser
     */

    public function testUpgradeUserToPowerUser () {

        /** @var object $branchObj */
        $branchObj = $this->phocebo->getBranchbyCode( array ( 'branchCode' => TEST_BRANCH ) );

        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_POWER_USER ) );

        if ( false == $userObj->success) {

            $parameters = array (

                'firstName'                 => 'Power User',

                'lastName'                  => 'Account',

                'email'                     => TEST_POWER_USER,

                'password'                  => TEST_POWER_USER_PASSWORD

            );

            $this->phocebo->addUser( $parameters );

            /** @var object $userObj */
            $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_POWER_USER ) );

        }

        $parameters = array (

            'branchId' => $branchObj->branchId,

            'profileName' => TEST_POWER_USER_PROFILE,

            'doceboId'   => $userObj->doceboId

        );
        $responseObj = $this->phocebo->upgradeUserToPowerUser($parameters);

        if ( false == $responseObj->success) {

            $testUser = $this->phocebo->downgradeUserFromPowerUser( array ( 'doceboId' => $userObj->doceboId));

            if (true == $testUser->success) {

                $responseObj = $this->phocebo->upgradeUserToPowerUser($parameters);

            }

        }

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }


    /**
     * testAssignCourseToPowerUserWhoIsNotPowerUser function.
     * @group PowerUser
     */

    public function testAssignCourseToPowerUserWhoIsNotPowerUser () {

        /** @var object $userObj */
        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_ACCOUNT ) );

        $this->phocebo->downgradeUserFromPowerUser( array(  'doceboId' => $userObj->doceboId ));

        $parameters = array (

            'doceboId'   => $userObj->doceboId,

            'courseCode' => TEST_COURSE_CODE

        );

        $responseObj = $this->phocebo->assignCourseToPowerUser($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertFalse( $responseObj->success,  'Success message should be false' );

        $this->assertObjectHasAttribute( 'message', $responseObj, 'Object response missing attribute "success"');


    }

    /**
     * testCustomErrorsForAssigningCourseToPowerUser function.
     * @group PowerUser
     */

    public function testCustomErrorsForAssigningCourseToPowerUser () {

        /** @var object $userObj */
        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_POWER_USER ) );

        $parameters = array (

            'courseCode' => TEST_COURSE_CODE

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
     * testAssignCourseToPowerUser function.
     * @group PowerUser
     */

    public function testAssignCourseToPowerUser () {

        /** @var object $userObj */
        $userObj = $this->phocebo->getdoceboId( array ( 'email' => TEST_POWER_USER ) );

        $parameters = array (

            'doceboId'   => $userObj->doceboId,

            'courseCode' => TEST_COURSE_CODE

        );

        $responseObj = $this->phocebo->assignCourseToPowerUser($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }

    /**
     * testValidGetBranchByCode function.
     * @group Branch
     * if this test fails check Docebo Root folder should have Org Chart Code as root and Name in English as root
     * @access public
     * @internal param array $parameters
     */

    public function testValidGetBranchByCode () {

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
     * testInvalidGetBranchbyCode function.
     * @group Branch
     */

    public function testInvalidGetBranchbyCode () {

        $parameters = array (

            'branchCode' => 'invalid',

        );

        $responseObj = $this->phocebo->getBranchbyCode($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute success');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'branchId', $responseObj, 'Object response missing attribute "branchId"');

        $this->assertNull ( $responseObj->branchId,  'Parameter "branchId" should be NULL' );

    }

    /**
     * testCustomErrorsGetBranchbyCode function.
     * @group Branch
     */

    public function testCustomErrorsGetBranchbyCode () {

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
     * testValidGetBranchInformation function.
     * @group Branch
     */

    public function testValidGetBranchInformation () {

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
     * testInvalidGetBranchbyInformation function.
     * @group Branch
     */

    public function testInvalidGetBranchbyInformation () {

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
     * testCustomErrorsForGettingBranchInformation function.
     * @group Branch
     */

    public function testCustomErrorsForGettingBranchInformation () {

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
     * testValidGetBranchChildren function.
     * @group Branch
     * If this test fails, root branch in Docebo was always 0
     */

    public function testValidGetBranchChildren () {

        $parameters = array (

            'branchId' => '0',

        );

        $responseObj = $this->phocebo->getBranchChildren($parameters);

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

        $this->assertObjectHasAttribute( 'children', $responseObj, 'Object response missing attribute "children"');

    }

    /**
     * testValidGetBranchParentId function.
     * @group Branch
     */

    public function testValidGetBranchParentId () {

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
     * testValidUserAssignedToBranch function.
     * @group Branch
     */

    public function testValidUserAssignedToBranch () {

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
     * testValidCreateBranch function.
     * @group Branch
     */

    public function testValidCreateBranch () {

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
     * testCustomErrorForCreateBranch function.
     * @group Branch
     */

    public function testCustomErrorForCreateBranch () {

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
     * testListGroupsInDocebo function.
     * @group Groups
     */

    public function testListGroupsInDocebo () {

        $responseObj = $this->phocebo->listGroups();

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }

    /**
     * testValidGetGroupId function.
     * @group Groups
     */

    public function testValidGetGroupId () {

        $parameters = array (

            'groupName'    => TEST_GROUP,

        );

        $response = $this->phocebo->getGroupId($parameters);

        $this->assertStringMatchesFormat('%d', $response);

    }

    /**
     * testValidUserAssignedToGroup function.
     * @group Groups
     */

    public function testValidUserAssignedToGroup () {

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
     * testValidListProfiles function.
     * @group Profiles
     * @todo expand tests for poweruser profiles list
     */

    public function testValidListProfiles () {

        $responseObj = $this->phocebo->listProfiles();

        $this->assertObjectHasAttribute( 'success', $responseObj, 'Object response missing attribute "success"');

        $this->assertTrue ( $responseObj->success,  'Success message should be true' );

    }

    /**
     * testValidGetProfileId function.
     * @group Profiles
     * @todo expand tests for poweruser profiles list
     */

    public function testValidGetProfileId () {

        $parameters = array (

            'profileName' => TEST_POWER_USER_PROFILE

        );

        $response = $this->phocebo->getProfileId($parameters);

        $this->assertStringMatchesFormat('%d', $response);

    }



}

?>