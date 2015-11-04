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
     * testdoceboId function.
     * 
     * @access public
     * @param mixed $email
     * @param mixed $expectedResult
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestdoceboId
     *
     */
     
    public function testdoceboId ( $email, $checkAttribute, $errorMessage ) {
        
        $responseObj = phoceboDiner::getdoceboId( array( 'email' => $email ) );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    /**
     * providerTesttestdoceboId function.
     *
     * Test data for the testdoceboId function.
     
     * @access public
     * @return void
     *
     */
     
    public function providerTesttestdoceboId() {
               
        return array(
            
            array ( 'patricia.walton@shrm.org', 'email', 'doceboId is valid but not reporting as valid' ),
            
            array ( 'someone@example.com', 'error', 'doceboId is not valid but reporting as valid' ),
            
        );
        
    }

   
    /**
     * testdoceboIdObj function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestdoceboIdObj
     */
     
     
    public function testdoceboIdObj ($checkAttribute, $errorMessage ) {
        
        $responseObj = phoceboDiner::getdoceboId( array('email' => 'patricia.walton@shrm.org') );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestdoceboIdObj() {
               
        return array(
            
            array ( 'doceboId', 'doceboId not in $responseObj' ),

            array ( 'firstname', 'firstname not in $responseObj' ),
            
            array ( 'lastname', 'lastname not in $responseObj' ),

            array ( 'email', 'email not in $responseObj' ),

        );
        
    }

    
    
    /**
     * testdoceboIdRemovedIdst function.
     * 
     * @access public
     * @return void
     *
     */
     
    public function testdoceboIdRemovedIdst () {
        
        $responseObj = phoceboDiner::getdoceboId ( array( 'email' => 'patricia.walton@shrm.org') );
        
        $this->assertObjectNotHasAttribute ( 'idst', $responseObj, 'The parameter idst should be removed from $responseObj');

    }    
    

    /**
     * testdoceboIdCustomErrorsJSONformat function.
     * 
     * @access public
     * @return void
     *
     * @dataProvider providerTesttestdoceboIdCustomErrorsJSONformat
     *
     */
     
    public function testdoceboIdCustomErrorsJSONformat ($checkAttribute, $errorMessage) {
        
        $responseObj = phoceboDiner::getdoceboId( array( 'noemail' => 'patricia.walton@shrm.org') );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestdoceboIdCustomErrorsJSONformat() {
               
        return array(
            
            array ('success', 'success not part of JSON response'),

            array ('error', 'error not part of JSON response'),

            array ('message', 'message not part of JSON response'),

        );
        
    }

    
    /**
     * testdoceboIdCustomErrors function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestdoceboIdCustomErrors
     *
     */

    public function testdoceboIdCustomErrors ($parameters, $expected, $errorMessage) {
        
        $responseObj = phoceboDiner::getdoceboId( $parameters );
        
        $this->assertEquals( $responseObj->error, $expected, $errorMessage);

    }    

    public function providerTesttestdoceboIdCustomErrors() {
               
        return array(
            
            array ( array ( 'noemail' => 'patricia.walton@shrm.org' ), '301', 'JSON response should be reporting error 301' ),

            array ( array ( 'email' => 'not an email address' ), '302', 'JSON response should be reporting error 302' ),

        );
        
    }
    
    
    // test addUser 
    // test for required fields
    // test responses
    
    
    
    
    
    /**
     * testaddUserCustomErrorsJSONformatfirstName function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestaddUsereCustomErrorsJSONformatfirstName
     *     
     */
     
     
    public function testaddUserCustomErrorsJSONformatfirstName ($checkAttribute, $errorMessage) {
        
        $parameters = array (
            
            'lastName'                  => 'Walton',
            
            'email'                     => 'patricia.walton@me.com'
            
        );
        
        $responseObj = phoceboDiner::addUser( $parameters );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestaddUsereCustomErrorsJSONformatfirstName() {
               
        return array(
            
            array ( 'success', 'success not part of JSON response' ),

            array ( 'error', 'error not part of JSON response' ),

            array ( 'message', 'message not part of JSON response' ),

        );
        
    }

    
    /**
     * testaddUserCustomErrorsJSONformatlastName function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestaddUsereCustomErrorsJSONformatlastName
     *     
     */
     
     
    public function testaddUserCustomErrorsJSONformatlastName ($checkAttribute, $errorMessage) {
        
        $parameters = array (
            
            'firstName'                 => 'Patricia',
            
            'email'                     => 'patricia.walton@me.com'
            
        );
        
        $responseObj = phoceboDiner::addUser( $parameters );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestaddUsereCustomErrorsJSONformatlastName() {
               
        return array(
            
            array ( 'success', 'success not part of JSON response' ),

            array ( 'error', 'error not part of JSON response' ),

            array ( 'message', 'message not part of JSON response' ),

        );
        
    }


    /**
     * testaddUserCustomErrorsJSONformatemail function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestaddUsereCustomErrorsJSONformatemail
     *     
     */
     
     
    public function testaddUserCustomErrorsJSONformatemail ($checkAttribute, $errorMessage) {
        
        $parameters = array (
            
            'firstName'                 => 'Patricia',
            
            'lastName'                  => 'Walton',
            
        );
        
        $responseObj = phoceboDiner::addUser( $parameters );
        
        $this->assertObjectHasAttribute( $checkAttribute, $responseObj, $errorMessage);

    }    
    
    public function providerTesttestaddUsereCustomErrorsJSONformatemail() {
               
        return array(
            
            array ( 'success', 'success not part of JSON response' ),

            array ('error', 'error not part of JSON response' ),

            array ( 'message', 'message not part of JSON response' ),

        );
        
    }


    /**
     * testaddUserCustomErrors function.
     * 
     * @access public
     * @param mixed $checkAttribute
     * @param mixed $errorMessage
     * @return void
     *
     * @dataProvider providerTesttestaddUserCustomErrors
     *
     */

    public function testaddUserCustomErrors ($parameters, $expected, $errorMessage) {
        
        $responseObj = phoceboDiner::addUser ( $parameters );
        
        $this->assertEquals ( $responseObj->error, $expected, $errorMessage );

    }    

    public function providerTesttestaddUserCustomErrors() {
               
        return array(
            
            array ( array ( 'nofirstName' => 'Patricia' ), '303', 'JSON response should be reporting error 303' ),

            array ( array ( 'firstName' => 'Patricia', 'nolastName' => 'Walton' ), '304', 'JSON response should be reporting error 304' ),

            array ( array ( 'firstName' => 'Patricia', 'lastName' => 'Walton', 'noemail' => 'patrica.walton@me.com' ), '305', 'JSON response should be reporting error 305' ),

        );
        
    }
    
    
    
    
    public function testaddUser () {
        
/*
object(stdClass)#345 (2) {
  ["idst"]=>
  string(5) "12365"
  ["success"]=>
  bool(true)
}        
*/
        
        $parameters = array (
            
            'firstName'                 => 'Vladan',
            
            'lastName'                  => 'Dasic',
            
            'email'                     => 'vdasic@example.com'
            
        );
        
//         $responseObj = phoceboDiner::addUser( $parameters );
        
//         var_dump($responseObj);
        
        
//         $this->assertObjectHasAttribute( $checkAttribute, $responseObj, 'test addUser');

    }    
    
    

}


?>