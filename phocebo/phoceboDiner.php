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
     * @param mixed $userid (The username or email of a valid user in LMS)
     * @param bool $also_check_as_email (default: true)
     * @return void
     *
     * @link https://doceboapi.docebosaas.com/api/docs#!/user/user_checkUsername_post_0
     * @todo determine if we need to check numeric id or email
     * @todo add Confluence link?
     *
     */
     
     
    static public function checkUsername ($userid, $also_check_as_email = true) {
        
       $action = '/user/checkUsername';

       $data_params = array (
    
           'userid' => $userid,
    
           'also_check_as_email' => $also_check_as_email,
	
       );
 
       $response = self::call($action, $data_params);
       
       $responseObj = self::validateJSON($action, $response);
       
       return($responseObj);
 
    }
    
    static public function validateJSON($action, $response) {
        
        $json_decode = json_decode($response);
        
        if (false == $json_decode->success) {
            
            var_dump($json_decode);
            
            $responseObj = $json_decode;
        
            
        } else {
            
            $responseObj = $json_decode;

            
        }
        
        return $responseObj;
        
    }
    
}

?>