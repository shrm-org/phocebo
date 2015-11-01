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

use phocebo\phoceboCook;

class testphoceboCooking extends \PHPUnit_Framework_TestCase {
    
    public function testGetHashParametersExist() {
        
        $params = array('userid', 'also_check_as_email');
        
        $codice = phoceboCook::getHash($params);
        
        $this->assertNotEmpty($codice, "GetHash returned a Null Value");

    }    

    public function testGetHashsha1String40() {
        
        $sha1_len = 0;
        
        $params = array('userid', 'also_check_as_email');
        
        $codice = phoceboCook::getHash($params);
        
        $sha1_len = strlen ($codice['sha1']);
        
        $this->assertEquals(40, $sha1_len, "Sha1 not calculating incorrectly");

    }    


    public function testResponseIsJSONString() {
        
        $action = '/user/checkUsername';
        
        $data_params = array (
            
            'userid' => 'patricia.walton@shrm.org',
            
        	'also_check_as_email' => true,
        	
        );
        						
        $response = phoceboCook::call($action, $data_params);
        
        $json_error = 'JSON_ERROR_NONE';
        
        $json_error = json_decode($response);
        
        $this->assertNotEquals($json_error, 'JSON_ERROR_NONE', "Not a JSON Response");
        
    }    
  
    
}


?>