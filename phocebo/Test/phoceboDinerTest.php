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

use phocebo\phoceboDiner;

class testphoceboDiner extends \PHPUnit_Framework_TestCase {
    
    public function testcheckUsername() {
        
        $responseObj = phoceboDiner::checkUsername('patricia.walton@shrm.org');
        
        $this->assertNotEmpty($responseObj, "No Data returned from phoceboDiner checkUsername using patricia.walton@shrm.org");

    }    
    
    /**
     * testcheckUsernameError201 function.
     * 
     * @access public
     * @return void
     * 
     * @dataProvider providerTesttestcheckUsernameErrors
     */
     
    public function testcheckUsernameErrors($userid,$expectedResult,$errorMessage) {
        
       $action = '/user/checkUsername';
        
       $data_params = array (
    
           'userid' => $userid,
    
           'also_check_as_email' => true,
	
       );


        $response = phoceboDiner::call($action, $data_params);
        
        var_dump($response);
        
        $this->assertEquals($expectedResult, $response, $errorMessage);

    }    
    
    public function providerTesttestcheckUsernameErrors() {
        
        return array(
            
            array('patricia.walton@shrm.org', 'what to expect?', 'error message'),
            
            array('someone@example.com', 'what to expect?', 'error message'),
            
            array('not a valid email', 'what to expect?', 'error message'),

            array('12332', 'what to expect?', 'Valid Docebo userid sent instead of email function is defaulted to check userid as email'),
            
            array('11111', '{"success":false,"error":201,"message":"User not found"}', 'Invalid Docebo userid sent instead of email function is defaulted to check userid as email'),
            
            array('', '{"success":false,"error":202,"message":"Invalid params passed"}', 'No parameters sent'),
            
        );
        
    }        //


/*
    public function testgetUserIdResponseIsJSONString() {
        
        $response = phoceboDiner::checkUsername('patricia.walton@shrm.org');
        
        $json_error = 'JSON_ERROR_NONE';
        
        $json_error = json_decode($response);
        
        // test fails using a single string
        
        $this->assertNotEquals($json_error, 'JSON_ERROR_NONE', "Not a JSON Response");
        
    }    

    public function testgetUserIDresponseWithInvalidUserId() {
        
        $response = phoceboDiner::isUserIdValid('null');
        
        $this->assertFalse($response, "Invalid userid given, method should return false value");

    }    
*/
    
    
}


?>