<?php
    
/**
 * Phởcebo Dinner - Delicious PHP wrapper for https://doceboapi.docebosaas.com/api/docs
 *
 * Usu fastidii corrumpit honestatis ad, his ludus assueverit id, scripta 
 * insolens torquatos eu sea. Eum ei maiorum eleifend molestiae, eu mea movet 
 * placerat iudicabit. Pertinax quaestio te vim, falli utamur senserit in sea, 
 * vix id magna modus assueverit. No eirmod euismod mel, te his dicta evertitur,
 * an tota congue consul sed.
 *
 * @package Phởcebo User
 * @author Patricia Walton <patricia.walton@shrm.org>
 * @version 0.0.1 In Development
 * @license MIT
 * @copyright 2015 SHRM
 *
 * https://doceboapi.docebosaas.com/api/docs#!/user
 */
 

namespace phocebo;


class phoceboDiner extends phoceboCook {
    
    // ultimate return Docebo User ID
    
    // check for user, return id
    
    // if no user, create user
    
    // also have update and delete functions based on Docebo User ID
    
    
    // /user/checkUsername
    
    static public function getUserId ($userId, $checkEmail = true) {
        
       $action = '/user/checkUsername';

       $data_params = array (
    
           'userid' => $userId,
    
           'also_check_as_email' => $checkEmail,
	
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