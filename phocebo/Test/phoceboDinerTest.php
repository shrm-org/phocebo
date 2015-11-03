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
    
    /**
     * testcheckUsername function.
     * 
     * @access public
     * @param mixed $userId
     * @param mixed $expectedResult
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestcheckUsername
     */
     
    public function testcheckUsername ($userId, $checkAttribute, $errorMessage ) {
        
        $responseObj = phoceboDiner::checkUsername( array('userId' => $userId) );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestcheckUsername() {
               
        return array(
            
            array ('patricia.walton@shrm.org', 'userId', 'UserId is valid but not reporting as valid'),
            
            array ('someone@example.com', 'error', 'UserId is not valid but reporting as valid'),
            
        );
        
    }

   
    /**
     * testcheckUsernameObj function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestcheckUsernameObj
     */
     
     
    public function testcheckUsernameObj ($checkAttribute, $errorMessage ) {
        
        $responseObj = phoceboDiner::checkUsername( array('userId' => 'patricia.walton@shrm.org') );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestcheckUsernameObj() {
               
        return array(
            
            array ('userId', 'userId not in $responseObj'),

            array ('userId', 'userId not in $responseObj'),

            array ('firstname', 'firstname not in $responseObj'),
            
            array ('lastname', 'lastname not in $responseObj'),

            array ('email', 'email not in $responseObj'),  

        );
        
    }

    
    
    /**
     * testcheckUsernameRemovedIdst function.
     * 
     * @access public
     * @return void
     *
     */
     
    public function testcheckUsernameRemovedIdst () {
        
        $responseObj = phoceboDiner::checkUsername( array('userId' => 'patricia.walton@shrm.org') );
        
        $this->assertObjectNotHasAttribute( 'idst', $responseObj, 'The parameter idst should be removed from $responseObj');

    }    
    

    /**
     * testcheckUsernameCustomErrors function.
     * 
     * @access public
     * @return void
     *
     * @dataProvider providerTesttestcheckUsernameCustomErrorsJSONformat
     *
     */
     
    public function testcheckUsernameCustomErrorsJSONformat ($checkAttribute, $errorMessage) {
        
        $responseObj = phoceboDiner::checkUsername( array( 'noUserId' => 'patricia.walton@shrm.org') );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestcheckUsernameCustomErrorsJSONformat() {
               
        return array(
            
            array ('success', 'success not part of JSON response'),

            array ('error', 'error not part of JSON response'),

            array ('message', 'message not part of JSON response'),

        );
        
    }

    
    /**
     * testcheckUsernameCustomErrors function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestcheckUsernameCustomErrors
     *
     */

    public function testcheckUsernameCustomErrors ($parameters, $expected, $errorMessage) {
        
        $responseObj = phoceboDiner::checkUsername( $parameters );
        
        $this->assertEquals( $responseObj->error, $expected, $errorMessage);

    }    

    public function providerTesttestcheckUsernameCustomErrors() {
               
        return array(
            
            array ( array ( 'noUserId' => 'patricia.walton@shrm.org'), '301', 'JSON response should be reporting error 301'),

            array ( array ( 'userId' => 'not an email address'), '302', 'JSON response should be reporting error 302'),

        );
        
    }

}


?>