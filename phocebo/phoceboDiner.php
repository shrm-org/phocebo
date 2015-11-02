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
     * @access public
     * @static
     * @param mixed $userid
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
    
       return($response);
 
    }
    
    
    
    
    
    
    
    
    static public function isUserIdValid ($userId, $checkEmail = true) {
        
       $response = self::getUserId($userId);
       
       $check = json_decode($response);
       
       if (false == $check->success) {
           
           // reasons it can fail
           // 202 Invlaid Params Used
           // 201 User Not Found
           // 500 Internal Server Error
           
           $response = false;
           
       }
       
       return($response);
 
    }
    
      static public function addUserId ($userId, $checkEmail = true) {
        
       $action = '/user/create';

       $data_params = array (
    
           'userid' => $userId,
           
           'firstname' => '',
           
           'lastname' => '',
           
           'email' => '',
           
           'valid' => true,
           
           'role' => 'student',
           
           'disableNotifications' => true   
	
       );
 
       $response = self::call($action, $data_params);
    
       return($response);
 
    }

    
    
    

    
}

?>