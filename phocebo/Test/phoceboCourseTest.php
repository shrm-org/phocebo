<?php
    
/**
 * Phởcebo PHPUnit Tests
 *
 * Usu fastidii corrumpit honestatis ad, his ludus assueverit id, scripta 
 * insolens torquatos eu sea. Eum ei maiorum eleifend molestiae, eu mea movet 
 * placerat iudicabit. Pertinax quaestio te vim, falli utamur senserit in sea, 
 * vix id magna modus assueverit. No eirmod euismod mel, te his dicta evertitur,
 * an tota congue consul sed.
 *
 * @package Phởcebo
 * @author Patricia Walton <patricia.walton@shrm.org>
 * @version 0.0.1 In Development
 * @license MIT
 * @copyright 2015 SHRM
 * 
 */  
    
namespace phocebo\Test;

use phocebo\phoceboCourse;

class testphoceboCourse extends \PHPUnit_Framework_TestCase {
    

    /**
     * testuserCoursesCustomErrorNoDoceboId function.
     * 
     * @access public
     * @return void
     *
     */

    public function testuserCoursesCustomErrorNoDoceboId () {
        
        $parameters = array ('no key' => '12332');
        
        $responseObj = phoceboCourse::userCourses( $parameters );
        
        $this->assertEquals( $responseObj->error, '301', 'JSON response should be reporting error 301');

    }    


    /**
     * testuserCoursesCustomErrorNoCoursesForUser function.
     * 
     * @access public
     * @return void
     */
     
    public function testuserCoursesCustomErrorNoCoursesForUser () {
        
/*
        $response = array ('success' => false, 'error' => '306', 'message' => 'Learner is not enrolled in any courses');
        
        $responseObj = json_decode ( json_encode( $response ), FALSE );
        
        $this->assertEquals( $responseObj->error, '306', 'JSON response should be reporting error 301');
*/

    }    

    public function testuserCourses () {

        $parameters = array ('doceboId' => '12332');
        
        $responseObj = phoceboCourse::userCourses( $parameters );
        
    }    


    public function testlistCourses () {
       
        $responseObj = phoceboCourse::listCourses();

    }    


    public function testlistUsersCourses () {
        
        $parameters = array ('doceboId' => '12332');
       
        $responseObj = phoceboCourse::listUsersCourses($parameters);

    }    


    public function testenrollUserInCourse () {
        
        $parameters = array (
        
            'doceboId' => '12332',
            
            'courseCode'    => '14-06'

        );
       
        $responseObj = phoceboCourse::enrollUserInCourse($parameters);
        
    }    


    public function testunenrollUserInCourse () {
        
        $parameters = array (
        
            'doceboId' => '12332',
            
            'courseCode'    => '14-06'

        );
       
        $responseObj = phoceboCourse::unenrollUserInCourse($parameters);
        
//         var_dump($responseObj);


    }    




    

}


?>