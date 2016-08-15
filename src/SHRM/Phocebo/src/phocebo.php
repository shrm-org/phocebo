<?php

/**
 * Phởcebo
 *
 * PHP Version 5.6
 *
 * @copyright 2015-2016 SHRM (http://www.shrm.org)
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @link      https://github.com/shrm-org/phocebo
 */


namespace SHRM\Phocebo;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * phocebo class.
 *
 * The Phởcebo class is to manage the calls from the internal application Embark
 * to the Docbeo API. This class will make the call and return a SHRM standard
 * JSON file for Embark to process. Programming logic will remain with Embark.
 *
 * @package Phởcebo
 * @author Patricia Walton <patricia.walton@shrm.org>
 * @version 0.3.2
 * @license MIT
 * @copyright 2015 SHRM
 * @link https://doceboapi.docebosaas.com/api/docs
 */

class phocebo {

    private $settings;

    private $url;

    private $key;

    private $secret;

    private $sso;

    function __construct($settings) {

        foreach($settings as $key => $value) {

            $this->$key = $value;

        }

    }

    /**
     * getdoceboId function.
     * Return the Docebo ID based on the users username which is set to the
     * users email address.
     *
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.3.2
     * @access public
     * @param array $parameters

    'email' => $email

     * @return object

    (stdClass) {

    ["email"] => string(13) "test@shrm.org"

    ["success"]  => bool(true)

    ["doceboId"] => int(12337)

    ["firstName"] => string(4) "Test"

    ["lastName"] => string(0) ""

    }

     *
     * @link https://doceboapi.docebosaas.com/api/docs#!/user/user_checkUsername_post_0
     * @todo determine if we need to check numeric id or email
     * @todo update test to report on Object not JSON
     */


    public function getdoceboId ( $parameters ) {

        if ( !array_key_exists( 'email', $parameters ) ) {

            return( self::dataError ( 'email', 'Required parameter "email": The username or email of a valid user in the LMS') );

        } elseif ( !filter_var( $parameters[ 'email' ], FILTER_VALIDATE_EMAIL ) ) {

            return( self::dataError ( 'email', 'Required parameter "email": The username or email of a valid user in the LMS') );

        }

        $action = '/user/checkUsername';

        $data_params = array (

            'userid'                 => $parameters[ 'email' ],

            'also_check_as_email'    => true,

        );

        $error_messages = [

            '201' => "User not found",

            '202' => "Invalid Parameters passed",

        ];

        return self::call ( $action, $data_params, $error_messages );

    }



    /**
     * authenticateUser function.
     *
     * @access public
     * @param array $parameters
     *
     * 'doceboId' => $doceboID
     *
     * 'username' => $username
     *
     * 'password' => $password
     *
     * @return object
     *
     *       (stdClass) {
     *
     *         ["success"] => bool(false)
     *
     *         ["error"] => int(201)
     *
     *         ["message"] => string(42) "Invalid login data provided: test@shrm.org"
     *
     *       }
     *
     *       (stdClass) {
     *
     *         ["id_user"] => string(5) "12369"
     *
     *         ["token"] => string(36) "62a9d6c6-4434-4e95-9c8b-1e1a1a5fe52d"
     *
     *         ["success"] => bool(true)
     *
     *       }
     */


    public function authenticateUser ( $parameters ) {

        if ( array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'This function requires users username (email) not the doceboId') );

        } elseif ( !array_key_exists( 'username', $parameters) ) {

            return(  self::dataError ( 'username', 'Parameter username of the user that to authenticate') );

        } elseif ( array_key_exists( 'username', $parameters)  && null == $parameters[ 'username' ] ) {

            return(  self::dataError ( 'username', 'Parameter username is blank') );

        } elseif ( !array_key_exists( 'password', $parameters) ) {

            return(  self::dataError ( 'password', 'Parameter password of the user to authenticate') );

        } elseif ( array_key_exists( 'password', $parameters)  && null == $parameters[ 'password' ] ) {

            return(  self::dataError ( 'password', 'Parameter password is blank') );

        }


        $action = '/user/authenticate';

        $data_params = array (

            'username'                 => $parameters[ 'username' ],

            'password'                 => $parameters[ 'password' ],

        );

        $error_messages = [

            '201' => sprintf("Invalid login data provided: %s", $parameters[ 'username' ]),

        ];

        return self::call ( $action, $data_params, $error_messages );

    }




    /**
     * getToken function.
     *
     * @access public
     * @param array $parameters
     *
     * 'username' => $username
     *
     * @return object
     * @todo write tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj has each of the expected attributes when valid
     * @todo test $responseObj does not have attributes (such as idst)
     */

    public function getToken ( $parameters ) {

        if ( !array_key_exists( 'username', $parameters ) ) {

            return( self::dataError ( 'username', 'The username or email of a valid user in the LMS') );

        } elseif ( !filter_var( $parameters[ 'username' ], FILTER_VALIDATE_EMAIL ) ) {

            return( self::dataError ( 'username', 'The username or email of a valid user in the LMS') );

        }

        $action = '/user/getToken';

        $data_params = array (

            'username'                 => $parameters[ 'username' ],

        );

        $error_messages = [

            '201' => "Invalid login data provided",

        ];

        return self::call ( $action, $data_params, $error_messages );

    }


    /**
     * checkToken function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo write tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj has each of the expected attributes when valid
     * @todo test $responseObj does not have attributes (such as idst)
     */

    public function checkToken ( $parameters ) {

        if ( !array_key_exists( 'doceboId', $parameters ) ) {

            return( self::dataError ( 'doceboId', 'The username or email of a valid user in the LMS') );

        } elseif ( !filter_var( $parameters[ 'username' ], FILTER_VALIDATE_EMAIL ) ) {

            return( self::dataError ( 'username', 'The username or email of a valid user in the LMS') );

        }

        $action = '/user/checkToken';

        $data_params = array (

            'id_user'                 => $parameters[ 'username' ],

            'auth_token'              => $parameters[ 'authToken' ],

        );

        $error_messages = [

            '201' => "No DoceboId specified",

            '202' => "No authToken specified",

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * addUser function.
     * Add a user Docebo using the email address. Sets the login username to
     * the email address.
     *
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.3.2
     * @access public
     * @param array $parameters

    'firstName' => $firstName

    'lastName'  => $lastName

    'email'     =>  $email

     * @return object

    (stdClass) {

    ["success"] => bool(true)

    ["doceboId"] => string(5) "12367"

    }

     * @link https://doceboapi.docebosaas.com/api/docs#!/user/user_create_post_5
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj has each of the expected attributes when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo add phake mocking for addUser testing
     * @todo is password mandatory?
     */

    public function addUser ( $parameters ) {

        if ( !array_key_exists( 'firstName', $parameters) ) {

            return( self::dataError ( 'firstName', 'Parameter firstName is missing') );

        } elseif ( !array_key_exists( 'lastName', $parameters) ) {

            return( self::dataError ( 'lastName', 'Parameter lastName is missing') );

        } elseif ( !array_key_exists( 'email', $parameters) ) {

            return( self::dataError ( 'email', 'Parameter email is missing') );

        }

        $action = '/user/create';

        $data_params = array (

            'userid'                 => $parameters['email'],

            'firstname'              => $parameters['firstName'],

            'lastname'               => $parameters['lastName'],

            'email'                  => $parameters['email'],

            'password'               => $parameters['password'],

            'valid'                  => true,

            'role'                   => 'student',

            'disableNotifications'   => false,

        );

        $error_messages = [

            '201' => "Empty email used for user name",

            '202' => "Error while assigning user level",

            '203' => "Cannot create godadmin users",

            '204' => "Cannot save user",

        ];

        return self::call( $action, $data_params, $error_messages );

    }


    /**
     * deleteUser function.
     *
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.3.2
     * @access public
     * @param array $parameters
     * @return object

    (stdClass)#347 (3) {
    ["success"]=>
    bool(false)
    ["error"]=>
    int(211)
    ["message"]=>
    string(22) "Error in user deletion"
    }

    (stdClass)#347 (2) { 12370
    ["success"]=>
    bool(true)
    ["doceboId"]=>
    string(5) "12366"
    }


     * @todo add phake mocking to test deleteUser
     */

    public function deleteUser ( $parameters ) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter doceboId is missing') );

        }

        $action = '/user/delete';

        $data_params = array (

            'id_user'                 => $parameters['doceboId'],

        );

        $error_messages = [

            '210' => "Invalid user specification",

            '211' => "Error in user deletion",

        ];

        return self::call( $action, $data_params, $error_messages );

    }



    /**
     * editUser function.
     *
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.3.2
     * @access public
     * @param array $parameters
     * @return object

     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     * @todo changing password responds null
     * @todo add edit orgchart
     * @todo add Timzone Tests
     * @todo review unenroll_deactivated
     * @todo review and test fields if needed

     */

    public function editUser ( $parameters ) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter doceboId missing') );

        } elseif ( 1 == count($parameters)) {

            return(  self::dataError ( 'null', 'Nothing is being sent to update') );

        } elseif ( array_key_exists( 'email', $parameters) && !filter_var( $parameters[ 'email' ], FILTER_VALIDATE_EMAIL ) ) {

            return(  self::dataError ( 'email', 'The username or email of a valid user in the LMS') );

        }

        $action = '/user/edit';

        $data_params = array();

        (array_key_exists('doceboId', $parameters) ?  $data_params['id_user'] = $parameters['doceboId'] : '');

        (array_key_exists('userName', $parameters) ?  $data_params['userid'] = $parameters['userName'] : '');

        (array_key_exists('firstName', $parameters) ?  $data_params['firstname'] = $parameters['firstName'] : '');

        (array_key_exists('lastName', $parameters) ?  $data_params['lastname'] = $parameters['lastName'] : '');

        (array_key_exists('password', $parameters) ?  $data_params['password'] = $parameters['password'] : '');

        (array_key_exists('email', $parameters) ?  $data_params['email'] = $parameters['email'] : '');

        (array_key_exists('valid', $parameters) ?  $data_params['valid'] = $parameters['valid'] : '');

        (array_key_exists('fields', $parameters) ?  $data_params['fields'] = $parameters['fields'] : '');

        (array_key_exists('orgchart', $parameters) ?  $data_params['orgchart'] = $parameters['orgchart'] : '');

        (array_key_exists('timezone', $parameters) ?  $data_params['timezone'] = $parameters['timezone'] : '');

        (array_key_exists('unenroll_deactivated', $parameters) ?  $data_params['unenroll_deactivated'] = $parameters['unenroll_deactivated'] : '');

        $error_messages = [

            '201' => "Invalid user specification",

            '203' => "Error while updating user",

        ];

        return self::call( $action, $data_params, $error_messages );

    }


    /**
     * getUserFields function.
     *
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.3.2
     * @access public
     * @return object
     *
    (stdClass) (2) {
    ["fields"] => array(1) {
     *
    [0] => object(stdClass) (2) {
     *
    ["id"] => int(1)
     *
    ["name"] => string(8) "Job Role"
     *
    }
     *
    }
     *
    ["success"] => bool(true)
     *
    }
     *
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function getUserFields ( ) {

        $action = '/user/fields';

        $data_params = array (

            'language'                 => null,

        );

        return self::call ( $action, $data_params, [] );

    }



    /**
     * getUserProfile function.
     *
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.3.2
     * @access public
     * @static
     * @param array $parameters
     * @return object
     *
    (stdClass) (11) {
    ["id_user"]=>
    string(5) "12339"
    ["userid"]=>
    string(4) "vdas"
    ["firstname"]=>
    string(3) "Vla"
    ["lastname"]=>
    string(3) "Das"
    ["email"]=>
    string(16) "vdasic@gmail.com"
    ["signature"]=>
    string(0) ""
    ["valid"]=>
    bool(true)
    ["register_date"]=>
    string(19) "2015-09-23 03:16:23"
    ["last_enter"]=>
    string(19) "2015-09-23 01:20:11"
    ["fields"]=>
    array(1) {
    [0]=>
    object(stdClass) (3) {
    ["id"]=>
    string(1) "1"
    ["name"]=>
    string(8) "Job Role"
    ["value"]=>
    string(1) "3"
    }
    }
    ["success"]=>
    bool(true)
    }

    (stdClass) (3) {
    ["success"]=>
    bool(false)
    ["error"]=>
    int(201)
    ["message"]=>
    string(26) "Invalid user specification"
    }
     * @todo add Phake to mock calls
     */

    public function getUserProfile ( $parameters ) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter doceboId missing') );

        }

        $action = '/user/profile';

        $data_params = array (

            'id_user'                 => $parameters['doceboId'],

        );

        $error_messages = [

            '201' => 'Invalid user specification'

        ];

        return self::call ( $action, $data_params, $error_messages );

    }



    /**
     * getUserGroups function.
     * Groups and folders are returned in separate arrays.
     * @access public
     * @param array $parameters
     * @return object
     *
     *       object(stdClass) (2) {
     *
     *          ["results"] => object(stdClass) (2) {
     *
     *              ["groups"] =>  array(1) {
     *
     *                  [0] => string(4) "CFGI"
     *
     *               }
     *
     *              ["folders"] => array(1) {
     *
     *                  [0] => string(23) "root/http://www.att.com"
     *
     *              }
     *
     *          }
     *
     *         ["success"] => bool(true)
     *
     *       }
     *
     */

    public function getUserGroups ( $parameters ) {

        if ( !array_key_exists( 'email', $parameters) ) {

            return( self::dataError ( 'email', 'Parameter "email" missing') );

        }

        $action = '/user/group_associations';

        $data_params = array (

            'email'                 => $parameters['email'],

        );

        $error_messages = [

            '201'                   => 'Invalid user specification',

        ];

//        $resultsObj = self::call ( $action, $data_params, $error_messages );
//
//        var_dump($resultsObj);
//
//        exit;

        return self::call ( $action, $data_params, $error_messages, 'Groups as List' );

    }

    /**
     * loggedinUser function.
     *
     * @access public
     * @param array $parameters
     * @return object

    (stdClass)#376 (2) {
    ["loggedIn"]=>
    bool(false)
    ["success"]=>
    bool(true)
    }

    (stdClass)#376 (2) {
    ["loggedIn"]=>
    bool(false)
    ["success"]=>
    bool(true)
    }

     */

    public function loggedinUser ( $parameters ) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter doceboId missing') );

        } elseif ( array_key_exists( 'email', $parameters) && !filter_var( $parameters[ 'email' ], FILTER_VALIDATE_EMAIL ) ) {

            return( self::dataError ( 'email', 'The username or email of a valid user in the LMS') );

        } elseif ( array_key_exists( 'username', $parameters) && !filter_var( $parameters[ 'username' ], FILTER_VALIDATE_EMAIL ) ) {

            return( self::dataError ( 'username', 'The username or email of a valid user in the LMS') );

        }

        $action = '/user/user_logged_in';

        $data_params = array ();

        (array_key_exists('doceboId', $parameters) ?  $data_params['id_user'] = $parameters['doceboId'] : '');

        (array_key_exists('userName', $parameters) ?  $data_params['userid'] = $parameters['userName'] : '');

        (array_key_exists('email', $parameters) ?  $data_params['email'] = $parameters['email'] : '');

        $error_messages = [

            '201' => 'Invalid user specification',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }


    /**
     * suspendUser function.
     *
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.3.2
     * @access public
     * @param array $parameters
     * @return object

    (stdClass) (2) {
    ["doceboId"]=>
    string(5) "12339"
    ["success"]=>
    bool(true)
    }

    (stdClass)#355 (3) {
    ["success"]=>
    bool(false)
    ["error"]=>
    int(210)
    ["message"]=>
    string(26) "Invalid user specification"
    }

     * @todo add Phake to mock calls
     * @todo follow up on unenroll_deactivated
     */

    public function suspendUser ( $parameters ) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter doceboId missing') );

        }

        $action = '/user/suspend';

        $data_params = array (

            'id_user'                 => $parameters['doceboId'],

            // 'unenroll_deactivated'    => Should we deactivate all the future enrollments?

        );

        $error_messages = [

            '210' => "Invalid user specification",

            '211' => "Error in user deletion",

        ];

        return self::call($action, $data_params, $error_messages );

    }




    /**
     * unsuspendUser function.
     *
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.3.2
     * @access public
     * @param array $parameters
     * @return object

    (stdClass) (2) {
    ["doceboId"]=>
    string(5) "12339"
    ["success"]=>
    bool(true)
    }
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400

     */

    public function unsuspendUser ( $parameters ) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter doceboId missing') );

        }

        $action = '/user/unsuspend';

        $data_params = array (

            'id_user'                 => $parameters['doceboId'],

            // 'unenroll_deactivated'    => Shoudl we deactivate all the future enrollments?

        );

        $error_messages = [

            '210' => "Invalid user specification",

            '211' => "Error in user deletion",

        ];

        return self::call($action, $data_params, $error_messages );

    }




    /**
     * userCourses function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     */

    public function userCourses ( $parameters ) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter doceboId missing') );

        }

        $action = '/user/userCourses';

        $data_params = array (

            'id_user'                 => $parameters['doceboId'],

        );

        $error_messages = [

            '210' => "Invalid User Specification",

        ];

        return self::call ( $action, $data_params, $error_messages );

    }


    /**
     * listCourses function.
     *
     * @access public
     * @return object
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function listCourses () {

        $action = '/course/listCourses';

        $data_params = array (

            'category'                 => null,

        );

        return self::call ( $action, $data_params, [] );

    }


    /**
     * enrollUserInCourse function.
     *
     * @access public
     * @param array $parameters
     * @return object

    (stdClass) (1) {

    ["success"] => bool(true)

    }

    (stdClass) (3) {

    ["success"] => bool(false)

    ["error"] => int(203)

    ["message"] => string(35) "User already enrolled to the course"

    }

     * the enrollment validity start (in yyyy-MM-dd HH:mm:ss format, UTC timezone)
     */

    public function enrollUserInCourse ($parameters) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Required parameter "doceboId" missing: Docebo ID for the user to be enrolled in the course') );

        } elseif ( !array_key_exists( 'courseCode', $parameters) )  {

            return( self::dataError ( 'courseCode', 'Required parameter "courseCode" missing: Course code') );

        };

        $action = '/course/addUserSubscription';

        (array_key_exists('doceboId', $parameters) ?  $data_params['id_user'] = $parameters['doceboId'] : '');

        (array_key_exists('courseCode', $parameters) ?  $data_params['course_code'] = $parameters['courseCode'] : '');

        (array_key_exists('courseId', $parameters) ?  $data_params['course_id'] = $parameters['courseId'] : '');

        if (array_key_exists('dateStart', $parameters ) ) {

            if ( preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/", $parameters['dateStart'] )) {

                $data_params['date_begin_validity'] = date('Y-m-d H:i:s', strtotime($parameters['dateStart'] . " " . date('H:i:s', time()) . " UTC"));

            } else {

                return( self::dataError ( 'dateStart', 'Parameter "dateStart" must be in in yyyy-MM-dd HH:mm:ss format, UTC timezone') );
            }

        }

        if (array_key_exists('dateEnd', $parameters ) ) {

            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/", $parameters['dateEnd'] )) {

                $data_params['date_expire_validity'] = date('Y-m-d H:i:s', strtotime($parameters['dateEnd'] . " " . date('H:i:s', time()) . " UTC"));

            } else {

                return( self::dataError ( 'dateEnd', 'Parameter "dateEnd" must be in in yyyy-MM-dd HH:mm:ss format, UTC timezone') );
            }

        }

        $data_params['user_level'] = 'student';

        $error_messages = [

            '201' => 'Invalid parameters',

            '202' => 'Invalid course code',

            '203' => 'User already enrolled to the course',

            '204' => 'Error while enrolling user',

        ];

        return self::call ( $action, $data_params, $error_messages );
    }

    /**
     * updateEnrollment function.
     *
     * @todo check next version - undocumented mandatory field user_level (202 Error if not sent)
     * @access public
     * @param array $parameters
     * @return object
     *
     *     class stdClass (1) {
     *
     *         public $success => bool(true)
     *
     *      }
     *
     * the enrollment validity start (in yyyy-MM-dd HH:mm:ss format, UTC timezone)
     */

    public function updateEnrollment ($parameters) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Required parameter "doceboId" missing: Docebo ID for the user to be enrolled in the course') );

        } elseif ( !array_key_exists( 'courseCode', $parameters) )  {

            return( self::dataError ( 'courseCode', 'Required parameter "courseCode" missing: Course code') );

        }

        $action = '/course/updateUserSubscription';

        (array_key_exists('doceboId', $parameters) ?  $data_params['id_user'] = $parameters['doceboId'] : '');

        (array_key_exists('courseCode', $parameters) ?  $data_params['course_code'] = $parameters['courseCode'] : '');

        (array_key_exists('courseId', $parameters) ?  $data_params['course_id'] = $parameters['courseId'] : '');

        (array_key_exists('userStatus', $parameters) ?  $data_params['user_status'] = $parameters['userStatus'] : '');

        if (array_key_exists('dateStart', $parameters ) ) {

            if ( preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/", $parameters['dateStart'] )) {

                $data_params['date_begin_validity'] = date('Y-m-d H:i:s', strtotime($parameters['dateStart'] . " " . date('H:i:s', time()) . " UTC"));

            } else {

                return( self::dataError ( 'dateStart', 'Parameter "dateStart" must be in in yyyy-MM-dd HH:mm:ss format, UTC timezone') );
            }

        }

        if (array_key_exists('dateEnd', $parameters ) ) {

            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/", $parameters['dateEnd'] )) {

                $data_params['date_expire_validity'] = date('Y-m-d H:i:s', strtotime($parameters['dateEnd'] . " " . date('H:i:s', time()) . " UTC"));

            } else {

                return( self::dataError ( 'dateEnd', 'Parameter "dateEnd" must be in in yyyy-MM-dd HH:mm:ss format, UTC timezone') );
            }

        }

        $data_params['user_level'] = 'student';

        $error_messages = [

            '201' => 'Invalid specified user',

            '202' => 'No updating info has been specified',

            '203' => 'Invalid specified course',

            '254' => 'Invalid enrollment',

            '205' => 'Error while updating enrollment data',

            '500' => 'Internal server error',

        ];

        return self::call ( $action, $data_params, $error_messages );
    }


    /**
     * unenrollUserInCourse function.
     *
     * @access public
     * @param array $parameters
     * @return object
     *
     *     (stdClass) {
     *
     *         ["success"] => bool(true)
     *
     *      }
     *
     *     (stdClass) {
     *
     *         ["success"] => bool(false)
     *
     *         ["error"] => int(203)
     *
     *         ["message"] => string(35) "User already enrolled to the course"
     *
     *     }
     */

    public function unenrollUserInCourse ($parameters) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Required parameter "doceboId"') );

        } elseif ( !array_key_exists( 'courseCode', $parameters) )  {

            return( self::dataError ( 'courseCode', 'Required parameter "courseCode" missing') );

        }

        $action = '/course/deleteUserSubscription';

        $data_params = array ();

        (array_key_exists('doceboId', $parameters) ?  $data_params['id_user'] = $parameters['doceboId'] : '');

        (array_key_exists('courseCode', $parameters) ?  $data_params['course_code'] = $parameters['courseCode'] : '');

        (array_key_exists('courseId', $parameters) ?  $data_params['course_id'] = $parameters['courseId'] : '');

        /** @var string $response */

        $error_messages = [

            '201' => 'Invalid parameters',

            '202' => 'Invalid specified course',

            '203' => 'User already enrolled to the course',

            '204' => 'Error while enrolling user',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * unenrollUserInCourse function.
     *
     * @access public
     * @param array $parameters
     * @return object
     *
     *     (stdClass) {
     *
     *        ["success"] => bool(true)
     *
     *     }
     *
     *     (stdClass) {
     *
     *        ["success"] => bool(false)
     *
     *        ["error"] => int(203)
     *
     *        ["message"] => string(35) "User already enrolled to the course"
     *
     *    }
     */

    public function updateUserInCourse ($parameters) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Required parameter "doceboId"') );

        } elseif ( !array_key_exists( 'courseCode', $parameters) )  {

            return( self::dataError ( 'courseCode', 'Required parameter "courseCode" missing') );

        }

        $action = '/course/deleteUserSubscription';

        $data_params = array ();

        (array_key_exists('doceboId', $parameters) ?  $data_params['id_user'] = $parameters['doceboId'] : '');

        (array_key_exists('courseCode', $parameters) ?  $data_params['course_code'] = $parameters['courseCode'] : '');

        (array_key_exists('courseId', $parameters) ?  $data_params['course_id'] = $parameters['courseId'] : '');

        /** @var string $response */

        $error_messages = [

            '201' => 'Invalid parameters',

            '202' => 'Invalid specified course',

            '203' => 'User already enrolled to the course',

            '204' => 'Error while enrolling user',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }


    /**
     * listUserCourses function.
     *
     * @access public
     * @param array $parameters
     * @return object

    (stdClass) {

    ["0"]=>

    object(stdClass) {

    ["course_info"] => object(stdClass) {

    ["course_id"] => string(2) "10"

    ["code"] => string(5) "14-06"

    ["course_name"] => string(98) "The Foundation for Success: Effectively Aligning Performance Management Systems with Total Rewards"

    ["credits"] => string(4) "0.00"

    ["total_time"] => string(2) "0s"

    ["enrollment_date"] => string(19) "2015-11-09 13:00:44"

    ["completion_date"] => NULL

    ["first_access_date"] => NULL

    ["score"] => int(0)

    ["status"] => string(10) "Subscribed"

    }

    }

    ["success"] => bool(true)

    }

     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     * @todo expand tests into Course Information
     *
     */

    public function listUserCourses ( $parameters ) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter doceboId missing') );

        }

        $action = '/course/listEnrolledCourses';

        $data_params = array (

            'id_user'                => $parameters['doceboId'],

        );

        $error_messages = [

            '401' => 'User Not Found',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }


    /**
     * subscribeWithCode function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function subscribeWithCode ($parameters) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter doceboId missing') );

        }

        $action = '/course/subscribeUserWithCode';

        $data_params = array (

            'id_user'                => $parameters['doceboId'],

            'reg_code'                => $parameters['registrationCode'],

        );

        $error_messages = [

            '201' => 'Invalid specified user',

            '202' => 'Empty code or cody type not specified',

            '203' => 'Invalid provided autoregistration code',

            '204' => 'Error while enrolling user',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }




    /**
     * createBranch function.
     *
     * @access public
     * @param array $parameters
     * @return object
     *
     *       (stdClass) {
     *
     *           ["success"] =>  bool(true)
     *
     *           ["branchId"] => string(2) "60"
     *
     *       }
     *
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo how to get list of orgchart/node/branch IDs
     */

    public function createBranch ($parameters) {

        if ( !array_key_exists( 'branchName', $parameters) ) {

            return( self::dataError ( 'branchName', 'Required parameter "branchName" missing: alphanumeric name for the branch') );

        } elseif ( !array_key_exists( 'branchCode', $parameters) ) {

            return( self::dataError ( 'branchCode', 'Required parameter "branchCode" missing') );

        } elseif ( !array_key_exists( 'parentBranchId', $parameters) ) {

            return( self::dataError ( 'parentBranchId', 'Required parameter "parentBranchId" missing') );

        }


        $responseobj = self::getBranchbyCode( array ( 'branchCode' => $parameters['branchCode'] ) );

        if (true == $responseobj->success && $responseobj->branchId != null) {

            $json_array = array (

                'success' => false,

                'error' => '201',

                'message' => "Branch already exists with that name",

                'branchId' => $responseobj->branchId,

                'branchCode' => $parameters['branchCode'],

                'branchName' => $parameters['branchName']


            );

            return( self::normalizeParams ( $json_array ) );

        } else {

            $action = '/orgchart/createNode';

            $data_params = array (

                'code'                => $parameters['branchCode'],

                'translation[english]'=> $parameters['branchName'],

                'id_parent'           => $parameters['parentBranchId'],

            );

            $error_messages = [

                '401' => 'Missing or invalid required parameter "parentId"',

                '402' => 'Missing or invalid required parameter "branchName" in English',

            ];

            return self::call ( $action, $data_params, $error_messages );

        }

    }


    /**
     * updateBranch function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function updateBranch ($parameters) {

        if ( !array_key_exists( 'branchId', $parameters) ) {

            return( self::dataError ( 'branchId', 'Parameter branchId missing') );

        }

        $action = '/orgchart/updateNode';

        $data_params = array();

        (array_key_exists('branchId', $parameters) ?  $data_params['id_org'] = $parameters['branchId'] : '');

        (array_key_exists('code', $parameters) ?  $data_params['code'] = $parameters['code'] : '');

        (array_key_exists('branchName', $parameters) ?  $data_params['translation'] = array ( 'english' => $parameters['branchName']) : '');

        (array_key_exists('parentId', $parameters) ?  $data_params['new_parent'] = $parameters['parentId'] : '');

        $error_messages = [

            '401' => 'Missing or invalid required parameter nodeId',

            '402' => 'Missing or invalid required parameter branchName in English',

            '403' => 'Invalid parent branch',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * deleteBranch function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function deleteBranch ($parameters) {

        if ( !array_key_exists( 'branchId', $parameters) ) {

            return( self::dataError ( 'branchId', 'Parameter "branchId" missing') );

        }

        $action = '/orgchart/deleteNode';

        $data_params = array (

            'id_org'                => $parameters['branchId'],

        );

        $error_messages = [

            '401' => 'Missing or invalid required parameter "branchId"',

            '402' => 'Cannot delete non-leaf branch',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * moveBranch function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function moveBranch ($parameters) {

        if ( !array_key_exists( 'branchId', $parameters) ) {

            return( self::dataError ( 'branchId', 'Parameter "branchId" missing') );

        } elseif ( !array_key_exists( 'destinationParentId', $parameters) ) {

            return( self::dataError ( 'destinationParentId', 'Parameter "destinationParentId" missing') );

        }

        $action = '/orgchart/moveNode';

        $data_params = array (

            'id_org'                => $parameters['branchId'],

            'destinationParentId'                => $parameters['destinationParentId'],

        );

        $error_messages = [

            '401' => 'Missing or invalid required parameter "branchId"',

            '402' => 'Missing or invalid required parameter "destinationParentId"',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * getBranchbyCode function.
     *
     * @access public
     * @param array $parameters
     * @return object
     *
     *      (stdClass) {
     *
     *        ["branchCode"] => string(4) "root"
     *
     *        ["translation"] => object(stdClass) {
     *
     *          ["arabic"] => string(4) "root"
     *
     *          ["bosnian"] => string(4) "root"
     *
     *          ["bulgarian"] => string(4) "root"
     *
     *          ["croatian"] => string(4) "root"
     *
     *          ["czech"] => string(4) "root"
     *
     *          ["danish"] => string(4) "root"
     *
     *          ["dutch"] => string(4) "root"
     *
     *          ["english"] => string(4) "root"
     *
     *          ["farsi"] => string(4) "root"
     *
     *          ["finnish"] => string(4) "root"
     *
     *          ["french"] => string(4) "root"
     *
     *          ["german"] => string(4) "root"
     *
     *          ["greek"] => string(4) "root"
     *
     *          ["hebrew"] => string(4) "root"
     *
     *          ["hungarian"] => string(4) "root"
     *
     *          ["indonesian"] => string(4) "root"
     *
     *          ["italian"] => string(4) "root"
     *
     *          ["japanese"] => string(4) "root"
     *
     *          ["korean"] => string(4) "root"
     *
     *          ["norwegian"] => string(4) "root"
     *
     *          ["polish"] => tring(4) "root"
     *
     *          ["portuguese"] => string(4) "root"
     *
     *          ["portuguese-br"] => string(4) "root"
     *
     *          ["romanian"] => string(4) "root"
     *
     *          ["russian"] => string(4) "root"
     *
     *          ["simplified_chinese"] => string(4) "root"
     *
     *          ["spanish"] => string(4) "root"
     *
     *          ["swedish"] => string(4) "root"
     *
     *          ["thai"] => string(4) "root"
     *
     *          ["turkish"] => string(4) "root"
     *
     *          ["ukrainian"] => string(4) "root"
     *
     *        }
     *
     *        ["success"] => bool(true)
     *
     *      }
     *
     *      (stdClass) {
     *
     *        ["branchCode"] => NULL
     *
     *        ["translation"] => array(0) {
     *
     *        }
     *
     *       ["success"] => bool(true)
     *
     *      }
     *
     */

    public function getBranchbyCode ($parameters) {

        if ( !array_key_exists( 'branchCode', $parameters) ) {

            return( self::dataError ( 'branchCode', 'Parameter "branchCode" missing: Alphanumeric "branchCode" of the node to retrieve') );

        };

        $action = '/orgchart/findNodeByCode';

        $data_params = array (

            'code'                => $parameters['branchCode'],

        );

        $error_messages = [

            '401' => 'Missing or invalid required parameter "code"',

        ];

        $res = self::call ( $action, $data_params, $error_messages );

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * getBranchInfo function.
     *
     * @access public
     * @param array $parameters
     * @return object

    (stdClass) {

    ["branchCode"] => string(4) "root"

    ["translation"] => object(stdClass) {

    ["arabic"] => string(4) "root"

    ["bosnian"] => string(4) "root"

    ["bulgarian"] => string(4) "root"

    ["croatian"] => string(4) "root"

    ["czech"] => string(4) "root"

    ["danish"] => string(4) "root"

    ["dutch"] => string(4) "root"

    ["english"] => string(4) "root"

    ["farsi"] => string(4) "root"

    ["finnish"] => string(4) "root"

    ["french"] => string(4) "root"

    ["german"] => string(4) "root"

    ["greek"] => string(4) "root"

    ["hebrew"] => string(4) "root"

    ["hungarian"] => string(4) "root"

    ["indonesian"] => string(4) "root"

    ["italian"] => string(4) "root"

    ["japanese"] => string(4) "root"

    ["korean"] => string(4) "root"

    ["norwegian"] => string(4) "root"

    ["polish"] => tring(4) "root"

    ["portuguese"] => string(4) "root"

    ["portuguese-br"] => string(4) "root"

    ["romanian"] => string(4) "root"

    ["russian"] => string(4) "root"

    ["simplified_chinese"] => string(4) "root"

    ["spanish"] => string(4) "root"

    ["swedish"] => string(4) "root"

    ["thai"] => string(4) "root"

    ["turkish"] => string(4) "root"

    ["ukrainian"] => string(4) "root"

    }

    ["success"] => bool(true)

    }

     */

    public function getBranchInfo ($parameters) {

        if ( !array_key_exists( 'branchId', $parameters) ) {

            return( self::dataError ( 'branchId', 'Parameter "branchId" missing') );

        };

        $action = '/orgchart/getNodeInfo';

        $data_params = array (

            'id_org'                => $parameters['branchId'],

        );

        $error_messages = [

            '401' => 'Missing or invalid required parameter "branchId"',

            '402' => 'No Branch Found',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }



    /**
     * getBranchChildren function.
     *
     * @access public
     * @param array $parameters
     * @return object

    (stdClass)#273 (2) {
    ["children"]=>
    array(4) {
    [0]=>
    object(stdClass)#243 (3) {
    ["code"]=>
    string(3) "CG2"
    ["id_org"]=>
    int(2)
    ["translation"]=>
    object(stdClass)#245 (31) {
    ...
    }
    }
    [1]=>
    object(stdClass)#241 (3) {
    ["code"]=>
    string(3) "CG3"
    ["id_org"]=>
    int(3)
    ["translation"]=>
    object(stdClass)#245 (31) {
    ...
    }
    }
    [2]=>
    object(stdClass)#250 (3) {
    ["code"]=>
    NULL
    ["id_org"]=>
    int(7)
    ["translation"]=>
    object(stdClass)#245 (31) {
    ...
    }
    }
    [3]=>
    object(stdClass)#244 (3) {
    ["code"]=>
    string(4) "Test"
    ["id_org"]=>
    int(8)
    ["translation"]=>
    object(stdClass)#245 (31) {
    ...
    }
    }
    }
    ["success"]=>
    bool(true)
    }


     * @todo create tests
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     * @todo change children->code to children-branchCode
     * @todo change children->id_org to children-branchId

     */

    public function getBranchChildren($parameters) {

        if ( !array_key_exists( 'branchId', $parameters) ) {

            return( self::dataError ( 'branchId', 'Parameter "branchId" missing: Branch Id') );

        };

        $action = '/orgchart/getChildren';

        $data_params = array (

            'id_org'                => $parameters['branchId'],

        );

        $error_messages = [

            '401' => 'Missing or invalid required parameter "branchId"',

            '402' => 'Branch not found',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * getBranchParentId function.
     *
     * @access public
     * @param array $parameters
     * @return object

    (stdClass) {

    ["branchCode"] => string(4) "root"

    ["translation"] => object(stdClass) {

    ["arabic"] => string(4) "root"

    ["bosnian"] => string(4) "root"

    ["bulgarian"] => string(4) "root"

    ["croatian"] => string(4) "root"

    ["czech"] => string(4) "root"

    ["danish"] => string(4) "root"

    ["dutch"] => string(4) "root"

    ["english"] => string(4) "root"

    ["farsi"] => string(4) "root"

    ["finnish"] => string(4) "root"

    ["french"] => string(4) "root"

    ["german"] => string(4) "root"

    ["greek"] => string(4) "root"

    ["hebrew"] => string(4) "root"

    ["hungarian"] => string(4) "root"

    ["indonesian"] => string(4) "root"

    ["italian"] => string(4) "root"

    ["japanese"] => string(4) "root"

    ["korean"] => string(4) "root"

    ["norwegian"] => string(4) "root"

    ["polish"] => tring(4) "root"

    ["portuguese"] => string(4) "root"

    ["portuguese-br"] => string(4) "root"

    ["romanian"] => string(4) "root"

    ["russian"] => string(4) "root"

    ["simplified_chinese"] => string(4) "root"

    ["spanish"] => string(4) "root"

    ["swedish"] => string(4) "root"

    ["thai"] => string(4) "root"

    ["turkish"] => string(4) "root"

    ["ukrainian"] => string(4) "root"

    }

    ["success"] => bool(true)

    }


     * @todo create tests
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function getBranchParentId($parameters) {

        if ( !array_key_exists( 'branchId', $parameters) ) {

            return( self::dataError ( 'branchId', 'Parameter "branchId" missing: Branch Id') );

        };

        $action = '/orgchart/getParentNode';

        $data_params = array (

            'id_org'                => $parameters['branchId'],

        );

        $error_messages = [

            '401' => 'Missing or invalid required parameter "branchId"',

            '402' => 'Branch not found',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * assignUserToBranch function.
     *
     * @access public
     * @param array $parameters
     * @return object

    (stdClass) {

    ["assigned_users"] => string(5) "12337"

    ["success"] => bool(true)

    }

    note: returns same if user is already in the branch

     * @todo talk to Richard at Docebo - no method to remove a user from a branch /poweruser/unassignUsers
     * @todo create tests
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function assignUserToBranch ($parameters) {

        if ( !array_key_exists( 'branchId', $parameters) ) {

            return( self::dataError ( 'branchId', 'Required parameter "branchId" missing') );

        } elseif ( !array_key_exists( 'doceboIds', $parameters) ) {

            return( self::dataError ( 'doceboIds', 'Parameter "doceboIds" missing: comma separated list of user doceboId') );

        };

        $action = '/orgchart/assignUsersToNode';

        $data_params = array (

            'id_org'                => $parameters['branchId'],

            'user_ids'              => $parameters['doceboIds'],

        );

        $error_messages = [

            '401' => "Missing or invalid required parameter 'branchId'",

            '402' => "Missing or invalid required list of 'branchIds'",

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * unassignUserToBranch function.
     *
     * @access public
     * @param array $parameters
     * @return object

    (stdClass) {

    ["assigned_users"] => string(5) "12337"

    ["success"] => bool(true)

    }

    note: returns same if user is already in the branch

     * @todo talk to Richard at Docebo - no method to remove a user from a branch /poweruser/unassignUsers
     * @todo create tests
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function unassignUserToBranch ($parameters) {

        if ( !array_key_exists( 'branchId', $parameters) ) {

            return( self::dataError ( 'branchId', 'Required parameter "branchId" missing') );

        } elseif ( !array_key_exists( 'doceboIds', $parameters) ) {

            return( self::dataError ( 'doceboIds', 'Parameter "doceboIds" missing: comma separated list of user doceboId') );

        };

        $action = '/orgchart/unassignUsersFromNode';

        $data_params = array (

            'id_org'                => $parameters['branchId'],

            'user_ids'              => $parameters['doceboIds'],

        );

        $error_messages = [

            '401' => "Missing or invalid required parameter 'branchId'",

            '402' => "Missing or invalid required list of 'user_ids'",

            '403' => "Invalid 'branchId'",

            '500' => 'Internal server error'

        ];

        return self::call ( $action, $data_params, $error_messages );

    }


    /**
     * upgradeUserToPowerUser function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function upgradeUserToPowerUser ($parameters) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter "doceboId" missing: Docebo ID of an existing non Power User account') );


        } elseif ( !array_key_exists( 'profileName', $parameters) ) {

            $json_array = self::dataError ( 'profileName', 'Parameter "profileName" missing: Power User profile name to be assigned');

        } elseif ( !array_key_exists( 'branchIds', $parameters) ) {

            $json_array = self::dataError ( 'branchIds', 'Parameter "branchIds" missing: comma separated list of Branch Ids');

        };

        $action = '/poweruser/add';

        $data_params = array (

            'id_user'                => $parameters['doceboId'],

            'profile_name'           => $parameters['profileName'],

            'orgchart'                => $parameters['branchId'],

        );

        $error_messages = [

            '401' => 'Power User app is not enabled in Docebo',

            '402' => 'Missing or invalid required parameter "doceboId"',

            '403' => 'User is already a power user',

            '404' => 'Failed to assign the Branch to the user',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * assignCourseToPowerUser function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function assignCourseToPowerUser ($parameters) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter "doceboId" missing: Docebo ID of an existing non Power User account') );

        } elseif ( !array_key_exists( 'courseCode', $parameters) ) {

            return( self::dataError ( 'courseCode', 'Parameter "courseCode" missing') );

        };

        $action = '/poweruser/assignCourses';

        $data_params = array (

            'id_user'                => $parameters['doceboId'],

            'items[course_code]'                  => $parameters['courseCode'],

        );

        $error_messages = [

            '401' => 'Power User app is not enabled in Docebo',

            '402' => 'Missing or invalid required parameter "doceboId"',

            '403' => 'User is not a power user',

            '404' => 'Invalid Item',

            '405' => 'Curricula App not Enabled',

            '500' => 'Internal server error',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }


    /**
     * downgradeUserToPowerUser function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function downgradeUserFromPowerUser($parameters) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return( self::dataError ( 'doceboId', 'Parameter "doceboId" missing: Docebo ID of an existing non Power User account') );

        };

        $action = '/poweruser/delete';

        $data_params = array (

            'id_user'                => $parameters['doceboId'],

        );

        $error_messages = [

            '401' => 'Power User is not enabled',

            '402' => 'Missing or invalid required parameter "doceboId"',

            '403' => 'User is not a power user',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * listGroups function.
     *
     * @access public
     * @param array $parameters
     * @return object
     *
    (stdClass) {
     *
    ["groups"] => array(5) {

     *              [0] => object(stdClass) (3) {

     *                  ["id_group"] => string(6) "122660"

     *                  ["name"] => string(10) "SHRM Admin"

     *                  ["description"] => string(0) ""
    }
     *
    [1] => object(stdClass) (3) {
     *
    ["id_group"] => string(6) "122671"
     *
     *                 ["name"] => string(10) "Power User"
     *
     *                 ["description"] => string(0) ""
     *
     *              }
     *
    [2] => object(stdClass) (3) {
     *
    ["id_group"] => string(6) "122672"
     *
     *                 ["name"] => string(12) "SHRM Learner"
     *
     *                 ["description"] => string(0) ""
     *
    }
     *
    [3] => object(stdClass) (3) {
     *
     *                  ["id_group"] => string(6) "122700"
     *
     *                  ["name"] => string(4) "CFGI"
     *
     *                  ["description"] => string(0) ""
     *
     *              }
     *
     *             [4] => object(stdClass) (3) {
     *
     *                  ["id_group"] => string(6) "122712"
     *
     *                  ["name"] => string(22) "eLearning Subscription"
     *
     *                  ["description"] => string(0) ""
     *
     *              }
     *
     *          }
     *
     *         ["success"] => bool(true)
     *
     *      }
     *
     *      if no parameter sent, this is the error response, API does not request a parameter
     *
     *      object(stdClass) (3) {
     *
     *          ["success"] => bool(false)
     *
     *          ["message"] => string(40) "Authorization header value doesn't match"
     *
     *          ["branchCode"] => int(104)
     *
     *      }
     *
     *      if random paramter sent "category" a list is not returned
     *
     * object(stdClass)#22 (1) {
    ["success"]=>
    bool(true)
    }

     *

     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function listGroups () {

        $action = '/group/listGroups';

        $data_params = array (

            'category'                 => null,

        );

        $error_messages = [

            '500' => 'Internal server error',

        ];

        $results = self::call ( $action, $data_params, $error_messages );

//        echo 'here';
//
//        var_dump($results);

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * getGroupId function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function getGroupId($parameters) {

        if ( !array_key_exists( 'groupName', $parameters) ) {

            return( self::dataError ( 'groupName', 'Parameter "groupName" missing' ) );

        };

        $groupsObj = self::listGroups();

        $groupArray = json_decode(json_encode ( $groupsObj ), true);

        if ( true == $groupArray['success']) {

            if ( array_key_exists($parameters['groupName'], $groupArray) ) {

                $groupId = $groupArray[$parameters['groupName']]['id'];

                return $groupId;

            } else {

                return false;

            }

        } else {

            return false;
        }

    }

    /**
     * assignUserToGroup function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function assignUserToGroup($parameters) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return (self::dataError ('doceboId', 'Parameter "doceboId" missing'));

        } elseif ( !array_key_exists( 'groupId', $parameters) ) {

            return( self::dataError ( 'groupId', 'Parameter "groupId" missing') );

        };

        $action = '/group/assign';

        $data_params = array (

            'id_group'                => $parameters['groupId'],

            'id_user'                => $parameters['doceboId'],

        );

        $error_messages = [

            '201' => 'Missing mandatory params',

            '202' => 'Invalid group ID provided ' . $parameters['groupId'],

            '203' => 'Invalid user ID provided ' . $parameters['doceboId'],

            '205' => "User " . $parameters['doceboId'] . " already assigned to this group",

            '500' => 'Internal server error',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * unassignUserFromGroup function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function unassignUserFromGroup($parameters) {

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return (self::dataError ('doceboId', 'Parameter "doceboId" missing'));

        } elseif ( !array_key_exists( 'groupId', $parameters) ) {

            return( self::dataError ( 'groupId', 'Parameter "groupId" missing') );

        };

        $action = '/group/unassign';

        $data_params = array (

            'id_group'                => $parameters['groupId'],

            'id_user'                => $parameters['doceboId'],

        );

        $error_messages = [

            '201' => 'Missing mandatory params',

            '202' => 'Invalid group ID provided ' . $parameters['groupId'],

            '203' => 'Invalid user ID provided ' . $parameters['doceboId'],

            '205' => "User " . $parameters['doceboId'] . " in not assigned to this group",

            '500' => 'Internal server error',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }

    /**
     * listProfiles function.
     *
     * @access public
     * @param array $parameters
     * @return object

    ["profiles"] =>
    array(1) {
    [0]=>
    object(stdClass)#6761 (2) {
    ["id"]=>
    string(6) "122713"
    ["name"]=>
    string(20) "Corporate Power User"
    }
    }
    ["success"]=>
    bool(true)
     *
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function listProfiles () {

        $action = '/poweruser/listProfiles';

        $data_params = array (

            'category'                 => null,

        );

        $error_messages = [

            '500' => 'Internal server error',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }


    /**
     * getProfileId function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function getProfileId ($parameters) {

        if ( !array_key_exists( 'profileName', $parameters) ) {

            return( self::dataError ( 'profileName', 'Parameter "profileName" missing') );

        };

        $profilesObj = self::listProfiles();

        $profileArray = json_decode(json_encode ( $profilesObj ), true);

        if ( true == $profileArray['success']) {

            $profileId = $profileArray[$parameters['profileName']];

        }

        return $profileId;

    }

    /**
     * assignUserToProfile function.
     *
     * @access public
     * @param array $parameters
     * @return object
     * @todo create tests
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function assignUserToProfile ($parameters) {

//        var_dump($parameters);

        if ( !array_key_exists( 'doceboId', $parameters) ) {

            return (self::dataError ('doceboId', 'Parameter "doceboId" missing'));

        } elseif ( !array_key_exists( 'groupId', $parameters) ) {

            return( self::dataError ( 'groupId', 'Parameter "groupId" missing') );

        };

        $action = '/group/assign';

        $data_params = array (

            'id_user'                => $parameters['doceboId'],

            'id_user'                => $parameters['doceboId'],

        );

        $error_messages = [

            '201' => 'Missing mandatory params',

            '202' => 'Invalid group ID provided ' . $parameters['groupId'],

            '203' => 'Invalid user ID provided ' . $parameters['doceboId'],

            '205' => "User " . $parameters['doceboId'] . " already assigned to this group",

            '500' => 'Internal server error',

        ];

        return self::call ( $action, $data_params, $error_messages );

    }


    /**
     * normalizeParams function.
     *
     * Pass JSON array to ensure all variables are returned with consistent names.
     *
     * @access private
     * @param array $json_array
     * @return object
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    public function normalizeParams ( $json_array,  $alternative_meaning = null ) {

        if ( isset($json_array[0]) ) {

            $normalizeJson['success'] = $json_array['success'];

            unset($json_array['success']);

            $normalizeJson['list'] = $json_array;

            $json_array = $normalizeJson;

        }

        $attributes = array (

            'id_user' => 'doceboId',

            'idst' => 'doceboId',

            'firstname' => 'firstName',

            'lastname' => 'lastName',

            'register_date' => 'registerDate',

            'last_enter' => 'lastEnter',

            'logged_in' => 'loggedIn',

            'code' => 'branchCode',

            'id_org' => 'branchId',

            'assigned_users' => 'assignedUsers',

        );

        foreach ( $attributes as $old => $new) {

            if ( array_key_exists ( $old, $json_array ) ) {

                $json_array[$new] = $json_array[$old];

                unset( $json_array[$old] );

            }

        }

        if ( array_key_exists ( 'results', $json_array ) ) {

            if ( array_key_exists ( 'folders', $json_array['results'] ) ) {

                $json_array['branches'] = $json_array['results']['folders'];

                unset( $json_array['results']['folders'] );

            }

            if ( array_key_exists ( 'groups', $json_array['results'] ) ) {

                $json_array['groups'] = $json_array['results']['groups'];

                unset( $json_array['results']['groups'] );

            }

            if ( 0 == count($json_array['results']) ) {

                unset( $json_array['results'] );

            }

        }

        if ( array_key_exists ( 'groups', $json_array ) ) {

            $groups = $json_array['groups'];

            if ( 'Groups as List' == $alternative_meaning ) {


            } else {

                foreach ($groups as $key => $group) {

                    $json_array[$group['name']]['id'] = $group['id_group'];

                    $json_array[$group['name']]['description'] = $group['description'];

                }

                unset($json_array['groups']);

            }


        }

        if ( array_key_exists ( 'profiles', $json_array ) ) {

            $profiles = $json_array['profiles'];

            foreach ($profiles as $key => $profile) {

                $json_array[$profile['name']] = $profile['id'];

            }

            unset($json_array['profiles']);

        }

        return( json_decode ( json_encode ( $json_array ), FALSE ) );

    }


    /**
     * dataError function.
     *
     * Return JSON array with error message.
     *
     * @access private
     * @param string $attribute
     * @param string $message
     * @return array $json_array
     * @todo test $responseObj has expected attributes from server when valid
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400

     */

    private function dataError ( $attribute, $message) {

        $json_array = array ('success' => false, 'error' => '400', 'message' => "$attribute: $message");

        return $this->normalizeParams($json_array);

    }


    /**
     * getHash function.
     *
     * @package Phởcebo Cooking
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.3.2
     * @access public
     * @param array $data_params
     * @return array $codice hash value for x_auth
     *
     */

    public function getHash( $data_params ) {

        /** @var array $data_params */

        $codice = array( 'sha1' => '', 'x_auth' => '' );

        if ( !empty ( $data_params ) ) {

            $codice['sha1'] = sha1 ( implode( ',', $data_params ) . ',' . $this->secret );

            $codice['x_auth'] = base64_encode ( $this->key . ':' . $codice['sha1'] );

            return $codice;

        } else {

            return null;

        }

    }


    /**
     * getDefaultHeader function.
     *
     * @package Phởcebo Cooking
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.3.2
     * @access public
     * @param array $x_auth
     *
     * @return array containing default header
     *
     */

    public function getDefaultHeader( $x_auth ) {

        $host = parse_url ( $this->url, PHP_URL_HOST );

        return array (

            "Host: " . ($host ? $host : ''),

            "Content-Type: multipart/form-data",

            'X-Authorization: Docebo '.$x_auth,

        );

    }


    /**
     * call function.
     *
     * @package Phởcebo Cooking
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.3.2
     * @access public
     * @param mixed $action Docebo API Call
     * @param mixed $data_params parameters to send
     *
     * @param $error_messages
     * @return array $output JSON formatted response
     * @throws \Exception
     * @todo fix encoding to create branch, issue with translation
     * array(3) {
     *
     * ["code"] => string(7) "Testing"
     *
     * ["translation"] => string(34) "{"english":"Test Branch Creation"}"
     *
     * ["id_parent"] => string(1) "0"
     *
     * }
     *
     * array(3) {
     *
     * ["code"] => string(7) "Testing"
     *
     * ["translation"] =>  array(1) {
     *
     * ["english"] => string(20) "Test Branch Creation"
     * }
     *
     * ["id_parent"] => string(1) "0"
     * }
     *
     *
     */

    public function call ( $action, $data_params, $error_messages, $alternative_meaning = null ) {

        $error_messages['500'] = 'Internal server error';

        $curl = curl_init();

        try {

            $hash_info = self::getHash ( $data_params );

            $http_header = self::getDefaultHeader ( $hash_info['x_auth'] );

            $opt = array (

                CURLOPT_URL => $this->url . '/api/' . $action,

                CURLOPT_RETURNTRANSFER => 1,

                CURLOPT_HTTPHEADER => $http_header,

                CURLOPT_POST => 1,

                CURLOPT_POSTFIELDS => $data_params,

                CURLOPT_CONNECTTIMEOUT => 20, // Timeout to 5 seconds

            );

            curl_setopt_array ( $curl, $opt );

            $output = curl_exec ( $curl );

            if ($output == FALSE) {

                $output = [

                    'success' => false,

                    'error' => strval(curl_getinfo($curl, CURLINFO_HTTP_CODE)),

                ];

                if (array_key_exists($output['error'], $error_messages) == true) {

                    $output['message'] = $error_messages[$output['error']];

                } else  {

                    throw new \Exception(sprintf("curl failed!\n * %d - %s\n * header: %s\n * data:%s\n",
                        curl_getinfo($curl, CURLINFO_HTTP_CODE),
                        $opt[CURLOPT_URL],
                        json_encode($opt[CURLOPT_HTTPHEADER]),
                        json_encode($opt[CURLOPT_POSTFIELDS])));

                };

            } else {

                $output = json_decode($output, TRUE);

                if (array_key_exists('success', $output) == false) {
                    throw new \Exception(sprintf("No 'success' key in result array: %s", $opt[CURLOPT_URL]));
                };

                if ($output['success'] == false) {

                    if (array_key_exists('error', $output) == true) {

                        if (array_key_exists($output['error'], $error_messages) == true) {

                            $output['message'] = $error_messages[$output['error']];

                        };

                    };

                    if ($output['message'] == null) {

                        throw new \Exception(sprintf("curl failed!\n * %d - %s\n * header: %s\n * data:%s\n",
                            curl_getinfo($curl, CURLINFO_HTTP_CODE),
                            $opt[CURLOPT_URL],
                            json_encode($opt[CURLOPT_HTTPHEADER]),
                            json_encode($opt[CURLOPT_POSTFIELDS])));

                    };

                };

            };

            $output = self::normalizeParams ( $output, $alternative_meaning );

        } finally {

            curl_close ( $curl );

        }

        return $output;

    }

}


?>
