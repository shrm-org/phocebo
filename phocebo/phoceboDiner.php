<?php
    
/**
 * Phởcebo Diner - Delicious PHP wrapper for https://doceboapi.docebosaas.com/api/docs
 *
 * The goal of the Phởcebo class is to manage the calls to the Docbeo API only.
 * These classes will make the call and return a SHRM Standard JSON for Embark
 * to process. Programming logic will remain with Embark.
 * 
 * Phởcebo Diner will house all API calls to manage users of the LMS
 *
 * @package Phởcebo Diner
 * @author Patricia Walton <patricia.walton@shrm.org>
 * @version 0.0.1 In Development
 * @license MIT
 * @copyright 2015 SHRM
 * @link https://doceboapi.docebosaas.com/api/docs#!/user
 *
 */
 

namespace phocebo;


class phoceboDiner extends phoceboCook {
    
    
    /**
     * doceboId function.
     *
     * 201 User not found
     * 202 Invalid Params passed
     * 500 Internal server error
     *
     * {"success":false,"error":201,"message":"User not found"}"
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
     
     // 
     
     
    static public function doceboId ( $parameters ) {
        
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
     
           $response = self::call ( $action, $data_params );
           
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
            
            $response = self::call($action, $data_params);
           
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
    
}

?>