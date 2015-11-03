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
     * checkUsername function.
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
     *    userId as valid email address for the user account
     *
     * @return void
     *
     * @link https://doceboapi.docebosaas.com/api/docs#!/user/user_checkUsername_post_0
     * @todo determine if we need to check numeric id or email
     * @todo add Confluence link?
     *
     */
     
     
    static public function checkUsername ( $parameters) {
        
       if ( !array_key_exists( 'userId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter userId missing');
           
       } elseif ( !filter_var($parameters['userId'], FILTER_VALIDATE_EMAIL)) {
           
           $json_array = array ('success' => false, 'error' => '302', 'message' => 'userId must be users email address');

       } else {
           
           $action = '/user/checkUsername';
       
           $data_params = array (
        
               'userid' => $parameters['userId'],
        
               'also_check_as_email' => true,
    	
           );
     
           $response = self::call($action, $data_params);
           
           $json_array = json_decode($response, true);
           
           if ( false == $json_array['success']) {
               
               if ('201' == $json_array['error']) {
                   
                   $json_array['message'] = "User not found: $userid";
                   
               }
               
               if ('202' == $json_array['error']) {
                   
                   $json_array['message'] = "Invalid Parameters passed: $data_params";
                   
               }
    
               if ('500' == $json_array['error']) {
                   
                   $json_array['message'] = 'Internal server error';
                   
               }
    
           } else {
               
               $json_array['userId'] = $json_array['idst'];
               
               unset($json_array['idst']);
    
               
           }
           
           
           
       }
       
       $responseObj = json_decode ( json_encode( $json_array ), FALSE );
       
       return($responseObj);
 
    }
    


    
}

?>