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
    
    public function testgetUserIDresponse() {
        
        $response = phoceboDiner::getUserID('patricia.walton@shrm.org');

        $this->assertNotEmpty($response, "No Data returned from phoceboDiner getUserId using patricia.walton@shrm.org");

    }    
    
    public function testgetUserIdResponseIsJSONString() {
        
        $response = phoceboDiner::getUserID('patricia.walton@shrm.org');
        
        $json_error = 'JSON_ERROR_NONE';
        
        $json_error = json_decode($response);
        
        // test fails using a single string
        
        $this->assertNotEquals($json_error, 'JSON_ERROR_NONE', "Not a JSON Response");
        
    }    

    public function testgetUserIDresponseWithInvalidUserId() {
        
        $response = phoceboDiner::isUserIdValid('null');
        
        $this->assertFalse($response, "Invalid userid given, method should return false value");

    }    
    
    
}


?>