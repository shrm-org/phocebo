<?php
    
/**
 * Phởcebo
 *
 * PHP Version 5.
 *
 * @copyright 2015-2016 SHRM (http://www.shrm.org)
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @link      https://github.com/shrm-org/phocebo
 */
 

namespace SHRM\Phocebo;


/**
 * phocebo class.
 *
 * The Phởcebo class is to manage the calls from the internal application Embark
 * to the Docbeo API. This class will make the call and return a SHRM standard
 * JSON file for Embark to process. Programming logic will remain with Embark.
 * 
 * @package Phởcebo
 * @author Patricia Walton <patricia.walton@shrm.org>
 * @version 0.0.8
 * @license MIT
 * @copyright 2015 SHRM
 * @link https://doceboapi.docebosaas.com/api/docs
 */
 
class phocebo {

    /**
     * getdoceboId function.
     * Return the Docebo ID based on the users username which is set to the
     * users email address.
     *
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.0.8
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
     
     
    static public function getdoceboId ( $parameters ) {
        
       if ( !array_key_exists( 'email', $parameters ) ) {
           
           $json_array = self::dataError ( 'email', 'Required parameter "email": The username or email of a valid user in the LMS');
           
       } elseif ( !filter_var( $parameters[ 'email' ], FILTER_VALIDATE_EMAIL ) ) {
           
           $json_array = self::dataError ( 'email', 'Required parameter "email": The username or email of a valid user in the LMS');

       } else {
           
           $action = '/user/checkUsername';
       
           $data_params = array (
        
               'userid'                 => $parameters[ 'email' ],
        
               'also_check_as_email'    => true,
    	
           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array[ 'success' ] ) {
               
               if ('201' == $json_array[ 'error' ] ) {
                   
                   $json_array[ 'message' ] = "User not found";
                   
               }
               
               if ('202' == $json_array[ 'error' ]) {
                   
                   $json_array[ 'message' ] = "Invalid Parameters passed";
                   
               }
    
               if ('500' == $json_array[ 'error' ]) {
                   
                   $json_array[ 'message' ] = 'Internal server error';
                   
               }
    
           } 
           
       }
       
       return( self::normalizeParams ( $json_array ) );
 
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
     
     
    static public function authenticateUser ( $parameters ) {
        
       if ( array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'This function requires users username (email) not the doceboId');

       } elseif ( !array_key_exists( 'username', $parameters) ) {
           
           $json_array = self::dataError ( 'username', 'Parameter username of the user that to authenticate');

       } elseif ( array_key_exists( 'username', $parameters)  && null == $parameters[ 'username' ] ) {
           
           $json_array = self::dataError ( 'username', 'Parameter username is blank');

       } elseif ( !array_key_exists( 'password', $parameters) ) {
           
           $json_array = self::dataError ( 'password', 'Parameter password of the user to authenticate');

       } elseif ( array_key_exists( 'password', $parameters)  && null == $parameters[ 'password' ] ) {
           
           $json_array = self::dataError ( 'password', 'Parameter password is blank');

       } else {
           
           $action = '/user/authenticate';
       
           $data_params = array (
        
               'username'                 => $parameters[ 'username' ],
        
               'password'                 => $parameters[ 'password' ],
    	
           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array[ 'success' ]) {
               
               if ('201' == $json_array[ 'error' ]) {
                   
                   $json_array[ 'message' ] = "Invalid login data provided: " . $parameters[ 'username' ];
                   
               }
    
               if ('500' == $json_array[ 'error' ] ) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } 
           
       }
       
       return( self::normalizeParams ( $json_array ) );
 
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

    static public function getToken ( $parameters ) {
        
       if ( !array_key_exists( 'username', $parameters ) ) {
           
           $json_array = self::dataError ( 'username', 'The username or email of a valid user in the LMS');
           
       } elseif ( !filter_var( $parameters[ 'username' ], FILTER_VALIDATE_EMAIL ) ) {
           
           $json_array = self::dataError ( 'username', 'The username or email of a valid user in the LMS');

       } else {
           
           $action = '/user/getToken';
       
           $data_params = array (
        
               'username'                 => $parameters[ 'username' ],
        
           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array[ 'success' ] ) {
               
               if ('201' == $json_array[ 'error' ] ) {
                   
                   $json_array[ 'message' ] = "Invalid login data provided";
                   
               }
               
    
               if ('500' == $json_array[ 'error' ]) {
                   
                   $json_array[ 'message' ] = 'Internal server error';
                   
               }
    
           } 
           
       }
       
       return( self::normalizeParams ( $json_array ) );
 
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

    static public function checkToken ( $parameters ) {
        
       if ( !array_key_exists( 'doceboId', $parameters ) ) {
           
           $json_array = self::dataError ( 'doceboId', 'The username or email of a valid user in the LMS');
           
       } elseif ( !filter_var( $parameters[ 'username' ], FILTER_VALIDATE_EMAIL ) ) {
           
           $json_array = self::dataError ( 'username', 'The username or email of a valid user in the LMS');

       } else {
           
           $action = '/user/checkToken';
       
           $data_params = array (
        
               'id_user'                 => $parameters[ 'username' ],
        
               'auth_token'              => $parameters[ 'authToken' ],

           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array[ 'success' ] ) {
               
               if ('201' == $json_array[ 'error' ] ) {
                   
                   $json_array[ 'message' ] = "No DoceboId specified";
                   
               }
               
               if ('202' == $json_array[ 'error' ] ) {
                   
                   $json_array[ 'message' ] = "No authToke specified";
                   
               }
    
               if ('500' == $json_array[ 'error' ]) {
                   
                   $json_array[ 'message' ] = 'Internal server error';
                   
               }
    
           } 
           
       }
       
       return( self::normalizeParams ( $json_array ) );
 
    }
    
    /**
     * addUser function.
     * Add a user Docebo using the email address. Sets the login username to
     * the email address.
     *
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.0.8
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
     */
     
    static public function addUser ( $parameters ) {
           
        if ( !array_key_exists( 'firstName', $parameters) ) {
           
           $json_array = self::dataError ( 'firstName', 'Parameter firstName is missing');

        } elseif ( !array_key_exists( 'lastName', $parameters) ) {
           
           $json_array = self::dataError ( 'lastName', 'Parameter lastName is missing');

        } elseif ( !array_key_exists( 'email', $parameters) ) {
           
           $json_array = self::dataError ( 'email', 'Parameter email is missing');

        } else {
            
            $action = '/user/create';
            
            $data_params = array (
            
                'userid'                 => $parameters['email'],
                
                'firstname'              => $parameters['firstName'],
                
                'lastname'               => $parameters['lastName'],
                
                'email'                  => $parameters['email'],
                
                'valid'                  => true,
                
                'role'                   => 'student',
                
                'disableNotifications'   => false,
            
            );
            
            $response = self::call($action, $data_params);
           
            $json_array = json_decode( $response, true );
           
            if ( false == $json_array['success']) {
               
               if ('201' == $json_array['error']) {
                   
                   $json_array['message'] = "Empty email used for user name";
                   
               }
               
               if ('202' == $json_array['error']) {
                   
                   $json_array['message'] = "Error while assigning user level";
                   
               }
            
               if ('203' == $json_array['error']) {
                   
                   $json_array['message'] = "Cannot create godadmin users";
                   
               }
            
               if ('204' == $json_array['error']) {
                   
                   $json_array['message'] = "Cannot save user";
                   
               }
            
               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
            
            } 
           
       }

       return( self::normalizeParams ( $json_array ) );
        
    }
    
    
    /**
     * deleteUser function.
     * 
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.0.8
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
     
    static public function deleteUser ( $parameters ) {
           
        if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Parameter doceboId is missing');

        } else {
            
            $action = '/user/delete';
            
            $data_params = array (
            
                'id_user'                 => $parameters['doceboId'],
            
            );
            
            $response = self::call($action, $data_params);
           
           $json_array = json_decode( $response, true );
            
            if ( false == $json_array['success']) {
               
               if ('210' == $json_array['error']) {
                   
                   $json_array['message'] = "Invalid user specification";
                   
               }
               
               if ('211' == $json_array['error']) {
                   
                   $json_array['message'] = "Error in user deletion";
                   
               }
            
               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
            
            } 
           
       }

       return( self::normalizeParams ( $json_array ) );
        
    }


    
    /**
     * editUser function.
     * 
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.0.8
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
     
    static public function editUser ( $parameters ) {
        
        if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Parameter doceboId missing');

        } elseif ( 1 == count($parameters)) {
           
           $json_array = self::dataError ( 'null', 'Nothing is being sent to update');

        } elseif ( array_key_exists( 'email', $parameters) && !filter_var( $parameters[ 'email' ], FILTER_VALIDATE_EMAIL ) ) {
           
           $json_array = self::dataError ( 'email', 'The username or email of a valid user in the LMS');

        } else {
            
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
            
            $response = self::call($action, $data_params);
           
           $json_array = json_decode( $response, true );
            
            if ( false == $json_array['success']) {
               
               if ('201' == $json_array['error']) {
                   
                   $json_array['message'] = "Invalid user specification";
                   
               }
               
               if ('203' == $json_array['error']) {
                   
                   $json_array['message'] = "Error while updating user";
                   
               }
            
               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
            
            } 
           
       }

       return( self::normalizeParams ( $json_array ) );
        
    }


    /**
     * getUserFields function.
     * 
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.0.8
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
     
    static public function getUserFields ( ) {
        
        $action = '/user/fields';
        
        $data_params = array (
        
            'language'                 => null,
            
        );
        

       $response = self::call ( $action, $data_params );
       
       $json_array = json_decode( $response, true );
       
       if ( false == $json_array['success']) {

           if ('500' == $json_array['error']) {
               
               $json_array['message'] = 'Internal server error';
               
           }

       } 
           
       return( self::normalizeParams ( $json_array ) );
        
    }



    /**
     * getUserProfile function.
     *
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.0.8
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
     
    static public function getUserProfile ( $parameters ) {

       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Parameter doceboId missing');

       } else {

            $action = '/user/profile';
            
            $data_params = array (
            
                'id_user'                 => $parameters['doceboId'],
                
            );
            

           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('201' == $json_array['error']) {
                   
                   $json_array['message'] = 'Invalid user specification';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } 
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
    }
    
    

    /**
     * getUserGroups function.
     * Groups and folders are returned in separate arrays.
     * @access public
     * @param array $parameters
     * @return object
     
            (stdClass) (2) {
              ["results"]=>
              (stdClass) (2) {
                ["groups"]=>
                array(0) {
                }
                ["folders"]=>
                array(0) {
                }
              }
              ["success"]=>
              bool(true)
            }
     */
     
    static public function getUserGroups ( $parameters ) {

       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Parameter doceboId missing');
           
       } else {

            $action = '/user/group_associations';
            
            $data_params = array (
            
                'id_user'                 => $parameters['doceboId'],
                
            );
            

           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('201' == $json_array['error']) {
                   
                   $json_array['message'] = 'Invalid user specification';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } 
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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
     
    static public function loggedinUser ( $parameters ) {

       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Parameter doceboId missing');

        } elseif ( array_key_exists( 'email', $parameters) && !filter_var( $parameters[ 'email' ], FILTER_VALIDATE_EMAIL ) ) {
           
           $json_array = self::dataError ( 'email', 'The username or email of a valid user in the LMS');

        } elseif ( array_key_exists( 'username', $parameters) && !filter_var( $parameters[ 'username' ], FILTER_VALIDATE_EMAIL ) ) {
           
           $json_array = self::dataError ( 'username', 'The username or email of a valid user in the LMS');

       } else {

            $action = '/user/user_logged_in';

           $data_params = array ();
            
            (array_key_exists('doceboId', $parameters) ?  $data_params['id_user'] = $parameters['doceboId'] : '');

            (array_key_exists('userName', $parameters) ?  $data_params['userid'] = $parameters['userName'] : '');

            (array_key_exists('email', $parameters) ?  $data_params['email'] = $parameters['email'] : '');

            $response = self::call ( $action, $data_params );
           
            $json_array = json_decode( $response, true );
           
            if ( false == $json_array['success']) {
    
                if ('201' == $json_array['error']) {
                   
                   $json_array['message'] = 'Invalid user specification';
                   
                }

                if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
                }
    
            } 
           
        }
        
       return( self::normalizeParams ( $json_array ) );
        
    }
    

    /**
     * suspendUser function.
     * 
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.0.8
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
     
    static public function suspendUser ( $parameters ) {
           
        if ( !array_key_exists( 'doceboId', $parameters) ) {
            
           $json_array = self::dataError ( 'doceboId', 'Parameter doceboId missing');

        } else {
            
            $action = '/user/suspend';
            
            $data_params = array (
            
                'id_user'                 => $parameters['doceboId'],
                
//                 'unenroll_deactivated'    => Should we deactivate all the future enrollments?
            
            );
            
            $response = self::call($action, $data_params);
           
            $json_array = json_decode( $response, true );
            
            if ( false == $json_array['success']) {
               
               if ('210' == $json_array['error']) {
                   
                   $json_array['message'] = "Invalid user specification";
                   
               }
               
               if ('211' == $json_array['error']) {
                   
                   $json_array['message'] = "Error in user deletion";
                   
               }
            
               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
            
            } 
           
       }
      
       return( self::normalizeParams ( $json_array ) );
       
    }
    
    


    /**
     * unsuspendUser function.
     * 
     * @package Phởcebo Diner
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.0.8
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
     
    static public function unsuspendUser ( $parameters ) {
           
        if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Parameter doceboId missing');

        } else {
            
            $action = '/user/unsuspend';
            
            $data_params = array (
            
                'id_user'                 => $parameters['doceboId'],
                
//                 'unenroll_deactivated'    => Shoudl we deactivate all the future enrollments?
            
            );
            
            $response = self::call($action, $data_params);
           
            $json_array = json_decode( $response, true );
            
            if ( false == $json_array['success']) {
               
               if ('210' == $json_array['error']) {
                   
                   $json_array['message'] = "Invalid user specification";
                   
               }
               
               if ('211' == $json_array['error']) {
                   
                   $json_array['message'] = "Error in user deletion";
                   
               }
            
               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
            
            } 
           
       }

       return( self::normalizeParams ( $json_array ) );
        
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

    static public function userCourses ( $parameters) {
        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Parameter doceboId missing');

       } else {
           
           $action = '/user/userCourses';
       
           $data_params = array (
        
               'id_user'                 => $parameters['doceboId'],
    	
           );
     
           $response = self::call ( $action, $data_params );
           
            $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
               
               if ('210' == $json_array['error']) {
                   
                   $json_array['message'] = "Invalid User Specification";
                   
               }
    
               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           }
           
       }
       
       return( self::normalizeParams ( $json_array ) );
 
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

    static public function listCourses () {
        
       $action = '/course/listCourses';
   
       $data_params = array (
    
           'category'                 => null,
	
       );
 
       $response = self::call ( $action, $data_params );
       
        $json_array = json_decode( $response, true );
       
       if ( false == $json_array['success']) {

           if ('500' == $json_array['error']) {
               
               $json_array['message'] = 'Internal server error';
               
           }

       } 
           
       return( self::normalizeParams ( $json_array ) );
        
    }

    
    /**
     * listUsersCourses function.
     * 
     * @access public
     * @param array $parameters
     * @return object
     * @todo test $responseObj has expected attributes from server when valid 
     * @todo test $responseObj does not have attributes (such as idst)
     * @todo test $responseObj has expected attributes from server when invalid 
     * @todo test $responseObj custom errors has proper attributes success, error and message and error value 400
     */

    static public function listUsersCourses ($parameters) {
        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Parameter doceboId missing');

       } else {

           $action = '/course/listEnrolledCourses';
       
           $data_params = array (
        
               'id_user'                 => $parameters['doceboId'],
    	
           );
     
           $response = self::call ( $action, $data_params );
           
            $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } 
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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
     
     */

    static public function enrollUserInCourse ($parameters) {
        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Required parameter "doceboId" missing: Docebo ID for the user to be enrolled in the course');

       } elseif ( !array_key_exists( 'courseCode', $parameters) )  {
           
           $json_array = self::dataError ( 'courseCode', 'Required parameter "courseCode" missing: Course code');
           
       } else {

           $action = '/course/addUserSubscription';
       
            (array_key_exists('doceboId', $parameters) ?  $data_params['id_user'] = $parameters['doceboId'] : '');

            (array_key_exists('courseCode', $parameters) ?  $data_params['course_code'] = $parameters['courseCode'] : '');

            (array_key_exists('courseId', $parameters) ?  $data_params['course_id'] = $parameters['courseId'] : '');

            (array_key_exists('courseId', $parameters) ?  $data_params['course_id'] = $parameters['courseId'] : '');

            $data_params['user_level'] = 'student';
     
            $response = self::call ( $action, $data_params );
           
            $json_array = json_decode( $response, true );
           
            if ( false == $json_array['success']) {
    
                if ('201' == $json_array['error']) {
                   
                    $json_array['message'] = 'Invalid parameters';
                   
                }

                if ('202' == $json_array['error']) {
                   
                    $json_array['message'] = 'Invalid course code';
                   
                }

                if ('203' == $json_array['error']) {
                   
                    $json_array['message'] = 'User already enrolled to the course';
                   
                }

                if ('204' == $json_array['error']) {
                   
                    $json_array['message'] = 'Error while enrolling user';
                   
                }

                if ('500' == $json_array['error']) {
                   
                    $json_array['message'] = 'Internal server error';
                   
                }
    
            } 
           
        }
        
       return( self::normalizeParams ( $json_array ) );
        
    }

    
    /**
     * unenrollUserInCourse function.
     * 
     * @access public
     * @param array $parameters
     * @return object
     
        (stdClass) {
            
          ["success"] => bool(true)
          
        }

        (stdClass) {
            
          ["success"] => bool(false)
          
          ["error"] => int(203)
          
          ["message"] => string(35) "User already enrolled to the course"
          
        }
     
     */

    static public function unenrollUserInCourse ($parameters) {
        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Required parameter "doceboId"');
           
       } elseif ( !array_key_exists( 'courseCode', $parameters) )  {
           
           $json_array = self::dataError ( 'courseCode', 'Required parameter "courseCode" missing');
           
       } else {

           $action = '/course/deleteUserSubscription';

           $data_params = array ();

            (array_key_exists('doceboId', $parameters) ?  $data_params['id_user'] = $parameters['doceboId'] : '');

            (array_key_exists('courseCode', $parameters) ?  $data_params['course_code'] = $parameters['courseCode'] : '');

            (array_key_exists('courseId', $parameters) ?  $data_params['course_id'] = $parameters['courseId'] : '');

           /** @var string $response */

           $response = self::call ( $action, $data_params );

           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('201' == $json_array['error']) {
                   
                   $json_array['message'] = 'Invalid parameters';
                   
               }

               if ('202' == $json_array['error']) {
                   
                   $json_array['message'] = 'Invalid specified course';
                   
               }

               if ('203' == $json_array['error']) {
                   
                   $json_array['message'] = 'User already enrolled to the course';
                   
               }

               if ('204' == $json_array['error']) {
                   
                   $json_array['message'] = 'Error while enrolling user';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } 
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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

    static public function listUserCourses ( $parameters ) {
        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Parameter doceboId missing');
           
       } else {

           $action = '/course/listEnrolledCourses';
       
           $data_params = array (
        
               'id_user'                => $parameters['doceboId'],
               
    	
           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'User Not Found';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } 
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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

    static public function subscribeWithCode ($parameters) {
        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Parameter doceboId missing');
           
       } else {

           $action = '/course/subscribeUserWithCode';
       
           $data_params = array (
        
               'id_user'                => $parameters['doceboId'],
               
               'reg_code'                => $parameters['registrationCode'],
    	
           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('201' == $json_array['error']) {
                   
                   $json_array['message'] = 'Invalid specified user';
                   
               }

               if ('202' == $json_array['error']) {
                   
                   $json_array['message'] = 'Empty code or cody type not specified';
                   
               }

               if ('203' == $json_array['error']) {
                   
                   $json_array['message'] = 'Invalid provided autoregistration code';
                   
               }

               if ('204' == $json_array['error']) {
                   
                   $json_array['message'] = 'Error while enrolling user';
                   
               }


               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } 
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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

    static public function createBranch ($parameters) {
        
       if ( !array_key_exists( 'branchName', $parameters) ) {
           
           $json_array = self::dataError ( 'branchName', 'Required parameter "branchName" missing: alphanumeric name for the branch');

       } elseif ( !array_key_exists( 'parentBranchId', $parameters) ) {
           
           $json_array = self::dataError ( 'parentBranchId', 'Required parameter "parentBranchId" missing');

       } else {

           $responseobj = self::getBranchbyCode( array ( 'branchCode' => $parameters['branchName'] ) );

           if (true == $responseobj->success) {

               echo "branch already created " . $responseobj->branchId . "\n";

               $json_array = array (

                   'success' => false,

                   'error' => '400',

                   'message' => "Branch already exists with that name",

                   'branchId' => $responseobj->branchId

               );


           } else {

               $action = '/orgchart/createNode';

               $data_params = array (

                   'code'                => $parameters['branchName'],

                   'translation[english]'=> $parameters['branchName'],

                   'id_parent'           => $parameters['parentBranchId'],

               );

               $response = self::call ( $action, $data_params );

               $json_array = json_decode( $response, true );

           }


           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "parentId"';
                   
               }

               if ('402' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "branchName" in English';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Error Saving New Branch';
                   
               }
    
           }
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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

    static public function updateBranch ($parameters) {
        
       if ( !array_key_exists( 'branchId', $parameters) ) {
           
           $json_array = self::dataError ( 'branchId', 'Parameter branchId missing');
           
/*
       } elseif ( !array_key_exists( 'code', $parameters) ) {
           
           $json_array = self::dataError ( 'code', 'Parameter code missing: alphanumeric code for the node');

       } elseif ( !array_key_exists( 'branchName', $parameters) ) {
           
           $json_array = self::dataError ( 'branchName', 'Parameter array for branchName missing');

       } elseif ( !array_key_exists( 'parentId', $parameters) ) {
           
           $json_array = self::dataError ( 'parentId', 'Parameter parentId missing');
*/

       } else {

           $action = '/orgchart/updateNode';

           $data_params = array();

            (array_key_exists('branchId', $parameters) ?  $data_params['id_org'] = $parameters['branchId'] : '');

            (array_key_exists('code', $parameters) ?  $data_params['code'] = $parameters['code'] : '');

            (array_key_exists('branchName', $parameters) ?  $data_params['translation'] = array ( 'english' => $parameters['branchName']) : '');

            (array_key_exists('parentId', $parameters) ?  $data_params['new_parent'] = $parameters['parentId'] : '');

           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter nodeId';
                   
               }

               if ('402' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter branchName in English';
                   
               }

               if ('403' == $json_array['error']) {
                   
                   $json_array['message'] = 'Invalid parent branch';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Error Saving New Branch';
                   
               }
    
           } 
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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

    static public function deleteBranch ($parameters) {
        
       if ( !array_key_exists( 'branchId', $parameters) ) {
           
           $json_array = self::dataError ( 'branchId', 'Parameter "branchId" missing');

       } else {

           $action = '/orgchart/deleteNode';
       
           $data_params = array (
        
               'id_org'                => $parameters['branchId'],
    	
           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "branchId"';
                   
               }

               if ('402' == $json_array['error']) {
                   
                   $json_array['message'] = 'Cannot delete non-leaf branch';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Error Saving New Branch';
                   
               }
    
           }
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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
           
           $json_array = self::dataError ( 'branchId', 'Parameter "branchId" missing');

       } elseif ( !array_key_exists( 'destinationParentId', $parameters) ) {
           
           $json_array = self::dataError ( 'destinationParentId', 'Parameter "destinationParentId" missing');


       } else {

           $action = '/orgchart/moveNode';
       
           $data_params = array (
        
               'id_org'                => $parameters['branchId'],
    	
               'destinationParentId'                => $parameters['destinationParentId'],

           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "branchId"';
                   
               }

               if ('402' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "destinationParentId"';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Error Saving New Branch';
                   
               }
    
           } 
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
    }

    /**
     * getBranchbyCode function.
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
            
            (stdClass) {
                
              ["branchCode"] => NULL
              
              ["translation"] => array(0) {
                  
              }

              ["success"] => bool(true)
            }

            
                 
     */

    static public function getBranchbyCode ($parameters) {
        
       if ( !array_key_exists( 'branchCode', $parameters) ) {
           
           $json_array = self::dataError ( 'branchCode', 'Parameter "branchCode" missing: Alphanumeric "branchCode" of the node to retrieve');

       } else {

           $action = '/orgchart/findNodeByCode';
       
           $data_params = array (
        
               'code'                => $parameters['branchCode'],
    	
           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "code"';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           }           
       }

       return( self::normalizeParams ( $json_array ) );
        
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

    static public function getBranchInfo ($parameters) {
        
       if ( !array_key_exists( 'branchId', $parameters) ) {
           
           $json_array = self::dataError ( 'branchId', 'Parameter "branchId" missing');

       } else {

           $action = '/orgchart/getNodeInfo';
       
           $data_params = array (
        
               'id_org'                => $parameters['branchId'],
    	
           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "branchId"';
                   
               }

               if ('402' == $json_array['error']) {
                   
                   $json_array['message'] = 'No Branch Found';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } 
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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

    public function getBranchChildren ($parameters) {
        
       if ( !array_key_exists( 'branchId', $parameters) ) {
           
           $json_array = self::dataError ( 'branchId', 'Parameter "branchId" missing: Branch Id');

       } else {

           $action = '/orgchart/getChildren';
       
           $data_params = array (
        
               'id_org'                => $parameters['branchId'],
    	
           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "branchId"';
                   
               }

               if ('402' == $json_array['error']) {
                   
                   $json_array['message'] = 'Branch not found';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } 
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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

    static public function getBranchParentId ($parameters) {
        
       if ( !array_key_exists( 'branchId', $parameters) ) {
           
           $json_array = self::dataError ( 'branchId', 'Parameter "branchId" missing: Branch Id');

       } else {

           $action = '/orgchart/getParentNode';
       
           $data_params = array (
        
               'id_org'                => $parameters['branchId'],
    	
           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "branchId"';
                   
               }

               if ('402' == $json_array['error']) {
                   
                   $json_array['message'] = 'Branch not found';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           }
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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

    static public function assignUserToBranch ($parameters) {
        
       if ( !array_key_exists( 'branchId', $parameters) ) {
           
           $json_array = self::dataError ( 'branchId', 'Required parameter "branchId" missing');

       } elseif ( !array_key_exists( 'ids', $parameters) ) {
           
           $json_array = self::dataError ( 'ids', 'Parameter "ids" missing: comma separated list of user ids');

       } else {

           $action = '/orgchart/assignUsersToNode';
       
           $data_params = array (
        
               'id_org'                => $parameters['branchId'],
    	
               'user_ids'                => $parameters['ids'],

           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "branchId"';
                   
               }

               if ('402' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required list of branchIds';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } 
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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
           
           $json_array = self::dataError ( 'doceboId', 'Parameter "doceboId" missing: Docebo ID of an existing non Power User account');

/*
       } elseif ( !array_key_exists( 'profileName', $parameters) ) {
           
           $json_array = self::dataError ( 'profileName', 'Parameter "profileName" missing: Power User profile name to be assigned');

       } elseif ( !array_key_exists( 'branchIds', $parameters) ) {
           
           $json_array = self::dataError ( 'branchIds', 'Parameter "branchIds" missing: comma separated list of Branch Ids');
*/

       } else {

           $action = '/poweruser/add';
       
           $data_params = array (
        
               'id_user'                => $parameters['doceboId'],
    	
               'profile_name'           => $parameters['profileName'],
               
               'orgchart'                => $parameters['branchId'],

           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Power User app is not enabled in Docebo';
                   
               }

               if ('402' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "doceboId"';
                   
               }

               if ('403' == $json_array['error']) {
                   
                   $json_array['message'] = 'User is already a power user';
                   
               }

               if ('404' == $json_array['error']) {
                   
                   $json_array['message'] = 'Failed to assign the Branch to the user';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           }
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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

    static public function downgradeUserFromPowerUser ($parameters) {
        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = self::dataError ( 'doceboId', 'Parameter "doceboId" missing: Docebo ID of an existing non Power User account');

       } else {

           $action = '/poweruser/delete';
       
           $data_params = array (
        
               'id_user'                => $parameters['doceboId'],

           );
     
           $response = self::call ( $action, $data_params );
           
           $json_array = json_decode( $response, true );
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Power User is not enabled';
                   
               }

               if ('402' == $json_array['error']) {
                   
                   $json_array['message'] = 'Missing or invalid required parameter "doceboId"';
                   
               }

               if ('403' == $json_array['error']) {
                   
                   $json_array['message'] = 'User is not a power user';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           }
           
       }
        
       return( self::normalizeParams ( $json_array ) );
        
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
     
    static public function normalizeParams ( $json_array ) {
        
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
            
            'assigned_users' => 'assignedUsers'

        );
        
        foreach ( $attributes as $old => $new) {
            
            if ( array_key_exists ($old, $json_array) ) {
                
               $json_array[$new] = $json_array[$old];
               
               unset($json_array[$old]);
                
            }
            
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

    static private function dataError ( $attribute, $message) {
        
       $json_array = array ('success' => false, 'error' => '400', 'message' => "$attribute: $message");
       
       return ($json_array);
        
    }


    /**
     * getHash function.
     *
     * @package Phởcebo Cooking
     * @author Patricia Walton <patricia.walton@shrm.org>
     * @version 0.0.8
     * @access public
     * @param array $data_params
     * @return array $codice hash value for x_auth
     *
     */
	 
	static public function getHash( $data_params ) {

        /** @var array $data_params */

        $codice = array( 'sha1' => '', 'x_auth' => '' );

        if ( !empty ( $data_params ) ) {

//            if ( array_key_exists('translation', $data_params ) ) { // { english: 'First Node' }
//
//                echo "before\n";
//
//                var_dump($data_params);
//
//                $translation = 'translation[english]=';
//
//                foreach ($data_params['translation'] as $lang => $branchName) {
//
//                    $translation .= $branchName;
//
//                }
//
//                unset($data_params['translation']);
//
//                $data_params['translation']  = $translation;
//
//                echo "after\n";
//
//                var_dump($data_params);
//
//
//            }


            $codice['sha1'] = sha1 ( implode( ',', $data_params ) . ',' . SECRET );
    		
    		$codice['x_auth'] = base64_encode ( KEY . ':' . $codice['sha1'] );
    		  		
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
     * @version 0.0.8
     * @access public
	 * @param array $x_auth
	 *
	 * @return array containing default header
	 *
	 */

    static public function getDefaultHeader( $x_auth ) {
    	
		$host = parse_url ( URL, PHP_URL_HOST );
		
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
     * @version 0.0.8
     * @access public
	 * @param mixed $action Docebo API Call
	 * @param mixed $data_params parameters to send
	 *
	 * @return array $output JSON formatted response

     * @todo fix encoding to create branch, issue with translation	 
        array(3) {

          ["code"] => string(7) "Testing"

          ["translation"] => string(34) "{"english":"Test Branch Creation"}"

          ["id_parent"] => string(1) "0"

        }

         array(3) {

            ["code"] => string(7) "Testing"

            ["translation"] =>  array(1) {

               ["english"] => string(20) "Test Branch Creation"
            }

            ["id_parent"] => string(1) "0"
        }

     *
     *
     *
	 */

    static public function call ( $action, $data_params ) {
    	
		$curl = curl_init();

		$hash_info = self::getHash ( $data_params );

		$http_header = self::getDefaultHeader ( $hash_info['x_auth'] );

//        if ( array_key_exists('translation', $data_params ) ) { // { english: 'First Node' }
//
//            echo "before\n";
//
//            var_dump($data_params);
//
//            $translation = 'translation[english]=';
//
//            foreach ($data_params['translation'] as $lang => $branchName) {
//
//                $translation .= $branchName;
//
//            }
//
//            unset($data_params['translation']);
//
//            $data_params['translation']  = $translation;
//
//            echo "after\n";
//
//            var_dump($data_params);
//
//
//        }

		$opt = array (
    		
			CURLOPT_URL => URL . '/api/' . $action,
			
			CURLOPT_RETURNTRANSFER => 1,
			
			CURLOPT_HTTPHEADER => $http_header,
			
			CURLOPT_POST => 1,
			
			CURLOPT_POSTFIELDS => $data_params,
			
			CURLOPT_CONNECTTIMEOUT => 5, // Timeout to 5 seconds
			
			CURLOPT_SSL_VERIFYPEER => false,
			
			CURLOPT_SSL_VERIFYHOST => false,
			
		);

		curl_setopt_array ( $curl, $opt );

		$output = curl_exec ( $curl );

        if ( array_key_exists('translation', $data_params ) ) { // { english: 'First Node' }

            var_dump($output);

        }
		
		curl_close ( $curl );
		
		return $output;
		
	}

}


?>