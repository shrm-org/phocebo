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

require_once dirname(__FILE__) . '/../phocebo.php';

class EnvironmentVariablesTest extends \PHPUnit_Framework_TestCase {
    
    public function testEnvironmentSettingsLoaded() {
        
        global $settings;

        $this->assertArrayHasKey("docebo", $settings, "Environment settings not loaded");

    }    

    public function testURLisNotBlank() {
              
        $this->assertNotEquals(URL, "URL", "Missing Docebo URL");

    }    

    public function testURLisValidk() {
        
        $URLisValid = true;
        
        if (filter_var( URL, FILTER_VALIDATE_URL) === FALSE) {
            
            $URLisValid = false;
        }

        $this->assertTrue($URLisValid, "The Docebo URL is invalid");

    }    

    public function testKEYisNotBlank() {
              
        $this->assertNotEquals(KEY, "KEY", "Missing Docebo public key");

    }    

    public function testSECRETisNotBlank() {
              
        $this->assertNotEquals(SECRET, "SECRET", "Missing Docebo secret key");

    }    

    public function testSSOisNotBlank() {
              
        $this->assertNotEquals(SSO, "SSO", "Missing Docebo SSO");

    } 
    
}


?>