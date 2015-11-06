<?php
    
/**
 * Phởcebo - Delicious PHP wrapper for https://doceboapi.docebosaas.com/api/docs
 *
 * The goal of the Phởcebo class is to manage the calls to the Docbeo API only.
 * These classes will make the call and return a SHRM Standard JSON for Embark
 * to process. Programming logic will remain with Embark.
 * 
 *
 * @package Phởcebo
 * @author Patricia Walton <patricia.walton@shrm.org>
 * @version 0.0.1 In Development
 * @license MIT
 * @copyright 2015 SHRM
 *
 */
 

namespace Phocebo;


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





class phocebo {

    /**
     * User Functions
     *
     */


    /**
     * getdoceboId function.
     *
     * @access public
     * @static
     *
     * @param mixed $parameters
     *    doceboId - email address to create the User Name
     *
     * @return object
     *
     * @link https://doceboapi.docebosaas.com/api/docs#!/user/user_checkUsername_post_0
     * @todo determine if we need to check numeric id or email
     * @todo add Confluence link?
     *
     */
     
     
    static public function getdoceboId ( $parameters ) {
        
       if ( !array_key_exists( 'email', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter email is missing');
           
       } elseif ( !filter_var($parameters['email'], FILTER_VALIDATE_EMAIL)) {
           
           $json_array = array ('success' => false, 'error' => '302', 'message' => 'must be users email address');

       } else {
           
           $action = '/user/checkUsername';
       
           $data_params = array (
        
               'userid'                 => $parameters['email'],
        
               'also_check_as_email'    => true,
    	
           );
     
           $response = phoceboCook::call ( $action, $data_params );
           
           $json_array = json_decode($response, true);
           
           if ( false == $json_array['success']) {
               
               if ('201' == $json_array['error']) {
                   
                   $json_array['message'] = "User not found";
                   
               }
               
               if ('202' == $json_array['error']) {
                   
                   $json_array['message'] = "Invalid Parameters passed";
                   
               }
    
               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } else {
               
               $json_array['doceboId'] = $json_array['idst'];
               
               unset($json_array['idst']);
    
           }
           
       }
       
       $responseObj = json_decode ( json_encode ( $json_array ), FALSE );
       
       return($responseObj);
 
    }


    
    
    /**
     * addUser function.
     * 
     * @access public
     * @static
     * @param mixed $parameters
     *      firstName - users first name
     *      lastName - users last name
     *      email - users email, also used to create users username
     *
     * @return object
     *
     */
     
     
    static public function addUser ( $parameters ) {
           
        if ( !array_key_exists( 'firstName', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '303', 'message' => 'Parameter firstName is missing');

        } elseif ( !array_key_exists( 'lastName', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '304', 'message' => 'Parameter lastName is missing');

        } elseif ( !array_key_exists( 'email', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '305', 'message' => 'Parameter email is missing');

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
            
            $response = phoceboCook::call($action, $data_params);
           
            $json_array = json_decode($response, true);
           
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
            
            } else {
               
               $json_array['doceboId'] = $json_array['idst'];
               
               unset ( $json_array['idst'] );

            
            }
           
       }

       $responseObj = json_decode ( json_encode ( $json_array ), FALSE );
       
       return($responseObj);
        
    }
    
    
    /**
     * deleteUser function.
     * 
     * @access public
     * @static
     * @param mixed $parameters
     * @return void
     */
     
    static public function deleteUser ( $parameters ) {
           
        if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter doceboId missing');

        } else {
            
            $action = '/user/delete';
            
            $data_params = array (
            
                'id_user'                 => $parameters['doceboId'],
            
            );
            
            $response = phoceboCook::call($action, $data_params);
           
            $json_array = json_decode($response, true);
            
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
            
            } else {
               
               $json_array['doceboId'] = $json_array['idst'];
               
               unset ( $json_array['idst'] );

            
            }
           
       }

       $responseObj = json_decode ( json_encode ( $json_array ), FALSE );
       
       return($responseObj);
        
    }


    
    /**
     * editUser function.
     * 
     * @access public
     * @static
     * @param mixed $parameters
     * @return void
     */
     
    static public function editUser ( $parameters ) {
           
        if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter doceboId missing');

        } else {
            
            $action = '/user/edit';
            
            $data_params = array (
            
                'id_user'                 => $parameters['doceboId'],
                
                'userid'                  => $parameters['email'],
                
                'firstname'               => $parameters['firstName'],
            
                'firstname'               => $parameters['firstName'],

            );
            
            $response = phoceboCook::call($action, $data_params);
           
            $json_array = json_decode($response, true);
            
            var_dump($json_array);
           
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
            
            } else {
               
               $json_array['doceboId'] = $json_array['idst'];
               
               unset ( $json_array['idst'] );

            
            }
           
       }

       $responseObj = json_decode ( json_encode ( $json_array ), FALSE );
       
       return($responseObj);
        
    }

    

    /**
     * getUserFields function.
     * 
     * @access public
     * @static
     * @return void
     */
     
    static public function getUserFields ( ) {
        
/*
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter doceboId missing');
           
       } else {
*/

            $action = '/user/fields';
            
            $data_params = array (
            
                'language'                 => null,
                
            );
            

           $response = phoceboCook::call ( $action, $data_params );
           
           $json_array = json_decode($response, true);
           
           if ( false == $json_array['success']) {
    
               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } else { // Success == true
               
    
           }
           
//        }
        
       $responseObj = json_decode ( json_encode( $json_array ), FALSE );
       
       return($responseObj);
        
    }

    

    /**
     * getUserProfile function.
     * 
     * @access public
     * @static
     * @return void
     */
     
    static public function getUserProfile ( $parameters ) {

/*
       $action = '/user/checkUsername';
   
       $data_params = array (
    
           'userid'                 => 'patricia.walton@shrm.org',
    
           'also_check_as_email'    => true,
	
       );
 
       $response = phoceboCook::call ( $action, $data_params );
       
       var_dump($response);
*/
        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter doceboId missing');
           
       } else {

            $action = '/user/profile';
            
            $data_params = array (
            
                'id_user'                 => $parameters['doceboId'],
                
            );
            

           $response = phoceboCook::call ( $action, $data_params );
           
           $json_array = json_decode($response, true);
           
           if ( false == $json_array['success']) {
    
               if ('201' == $json_array['error']) {
                   
                   $json_array['message'] = 'Invalid user specification';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } else { // Success == true
               
    
           }
           
       }
        
       $responseObj = json_decode ( json_encode( $json_array ), FALSE );
       
       return($responseObj);
        
    }

    

    /**
     * suspendUser function.
     * 
     * @access public
     * @static
     * @param mixed $parameters
     * @return void
     */
     
    static public function suspendUser ( $parameters ) {
           
        if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter doceboId missing');

        } else {
            
            $action = '/user/suspend';
            
            $data_params = array (
            
                'id_user'                 => $parameters['doceboId'],
                
//                 'unenroll_deactivated'    => Shoudl we deactivate all the future enrollments?
            
            );
            
            $response = phoceboCook::call($action, $data_params);
           
            $json_array = json_decode($response, true);
            
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
            
            } else {
               
/*
               $json_array['doceboId'] = $json_array['idst'];
               
               unset ( $json_array['idst'] );
*/

            
            }
           
       }

       $responseObj = json_decode ( json_encode ( $json_array ), FALSE );
       
       return($responseObj);
        
    }
    
    


    /**
     * unsuspendUser function.
     * 
     * @access public
     * @static
     * @param mixed $parameters
     * @return void
     */
     
    static public function unsuspendUser ( $parameters ) {
           
        if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter doceboId missing');

        } else {
            
            $action = '/user/unsuspend';
            
            $data_params = array (
            
                'id_user'                 => $parameters['doceboId'],
                
//                 'unenroll_deactivated'    => Shoudl we deactivate all the future enrollments?
            
            );
            
            $response = phoceboCook::call($action, $data_params);
           
            $json_array = json_decode($response, true);
            
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
            
            } else {
               
/*
               $json_array['doceboId'] = $json_array['idst'];
               
               unset ( $json_array['idst'] );
*/

            
            }
           
       }

       $responseObj = json_decode ( json_encode ( $json_array ), FALSE );
       
       return($responseObj);
        
    }

    /**
     * Course Functions
     *
     */

    static public function userCourses ( $parameters) {
        
/*
       $action = '/user/checkUsername';
   
       $data_params = array (
    
           'userid'                 => 'patricia.walton@shrm.org',
    
           'also_check_as_email'    => true,
	
       );
 
       $response = phoceboCook::call ( $action, $data_params );
       
       var_dump($response);
*/

        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter doceboId missing');
           
       } else {
           
           $action = '/user/userCourses';
       
           $data_params = array (
        
               'id_user'                 => $parameters['doceboId'],
    	
           );
     
           $response = phoceboCook::call ( $action, $data_params );
           
           $json_array = json_decode($response, true);
           
           if ( false == $json_array['success']) {
               
               if ('210' == $json_array['error']) {
                   
                   $json_array['message'] = "Invalid User Specification";
                   
               }
    
               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } else { // Success == true
               
    
           }
           
       }
       
       $responseObj = json_decode ( json_encode( $json_array ), FALSE );
       
       return($responseObj);
 
    }
    
    
    static public function listCourses () {
        
           $action = '/course/listCourses';
       
           $data_params = array (
        
               'category'                 => null,
    	
           );
     
           $response = phoceboCook::call ( $action, $data_params );
           
           $json_array = json_decode($response, true);
           
           if ( false == $json_array['success']) {
    
               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } else { // Success == true
               
    
           }
        
       $responseObj = json_decode ( json_encode( $json_array ), FALSE );
       
       return($responseObj);
        
    }

    static public function listUsersCourses ($parameters) {
        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter doceboId missing');
           
       } else {

           $action = '/course/listEnrolledCourses';
       
           $data_params = array (
        
               'id_user'                 => $parameters['doceboId'],
    	
           );
     
           $response = phoceboCook::call ( $action, $data_params );
           
           $json_array = json_decode($response, true);
           
           if ( false == $json_array['success']) {
    
               if ('401' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }

               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } else { // Success == true
               
    
           }
           
       }
        
       $responseObj = json_decode ( json_encode( $json_array ), FALSE );
       
       return($responseObj);
        
    }
    
    static public function enrollUserInCourse ($parameters) {
        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter doceboId missing');
           
       } else {

           $action = '/course/addUserSubscription';
       
           $data_params = array (
        
               'id_user'                => $parameters['doceboId'],
               
               'course_code'            => $parameters['courseCode'],
               
               'user_level'             => 'student'
    	
           );
     
           $response = phoceboCook::call ( $action, $data_params );
           
           $json_array = json_decode($response, true);
           
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
    
           } else { // Success == true
               
    
           }
           
       }
        
       $responseObj = json_decode ( json_encode( $json_array ), FALSE );
       
       return($responseObj);
        
    }

    static public function unenrollUserInCourse ($parameters) {
        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter doceboId missing');
           
       } else {

           $action = '/course/deleteUserSubscription';
       
           $data_params = array (
        
               'id_user'                => $parameters['doceboId'],
               
               'course_code'            => $parameters['courseCode'],
               
               'user_level'             => 'student'
    	
           );
     
           $response = phoceboCook::call ( $action, $data_params );
           
           $json_array = json_decode($response, true);
           
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
    
           } else { // Success == true
               
    
           }
           
       }
        
       $responseObj = json_decode ( json_encode( $json_array ), FALSE );
       
       return($responseObj);
        
    }


}






/**
 * Phởcebo Cooking Instructions
 *
 * Usu fastidii corrumpit honestatis ad, his ludus assueverit id, scripta 
 * insolens torquatos eu sea. Eum ei maiorum eleifend molestiae, eu mea movet 
 * placerat iudicabit. Pertinax quaestio te vim, falli utamur senserit in sea, 
 * vix id magna modus assueverit. No eirmod euismod mel, te his dicta evertitur,
 * an tota congue consul sed.
 *
 * @package classpackage
 */
 
class phoceboCook {

	/**
	 * getHash function.
	 * 
	 * @access public
	 * @static
	 * @data_params mixed $params
	 *
	 * @return array $codice hash value for x_auth
	 *
	 */
	 
	public function getHash( $data_params ) {
    	
    	if ( !empty ( $data_params ) ) {
        	
    		$codice = array( 'sha1' => '', 'x_auth' => '' );
    		
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
	 * @access private
	 * @static
	 * @param mixed $x_auth
	 *
	 * @return array containting default header
	 *
	 */
	 
	static private function getDefaultHeader( $x_auth ) {
    	
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
	 * @access public
	 * @static
	 * @param mixed $action Docebo API Call
	 * @param mixed $data_params parameters to send
	 *
	 * @return $output JSON formatted response
	 *
	 */
	 
	static public function call ( $action, $data_params ) {
    	
		$curl = curl_init();

		$hash_info = self::getHash ( $data_params );

		$http_header = self::getDefaultHeader ( $hash_info['x_auth'] );

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
		
		curl_close ( $curl );
		
		return $output;
		
	}
    
}



?>