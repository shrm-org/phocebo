<?php
    
/**
 * Phởcebo User - Delicious PHP wrapper for https://doceboapi.docebosaas.com/api/docs
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
 */
 

namespace phocebo;

use phocebo\phoceboCook;

class phoceboCourse extends phoceboCook {


    static public function userCourses ( $parameters) {
        
/*
       $action = '/user/checkUsername';
   
       $data_params = array (
    
           'userid'                 => 'patricia.walton@shrm.org',
    
           'also_check_as_email'    => true,
	
       );
 
       $response = self::call ( $action, $data_params );
       
       var_dump($response);
*/

        
       if ( !array_key_exists( 'doceboId', $parameters) ) {
           
           $json_array = array ('success' => false, 'error' => '301', 'message' => 'Parameter doceboId missing');
           
       } else {
           
           $action = '/user/userCourses';
       
           $data_params = array (
        
               'id_user'                 => $parameters['doceboId'],
    	
           );
     
           $response = self::call ( $action, $data_params );
           
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
     
           $response = self::call ( $action, $data_params );
           
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
     
           $response = self::call ( $action, $data_params );
           
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
     
           $response = self::call ( $action, $data_params );
           
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
     
           $response = self::call ( $action, $data_params );
           
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

?>