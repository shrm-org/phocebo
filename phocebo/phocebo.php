<?php
    
/**
 * Phởcebo - Delicious PHP wrapper for https://doceboapi.docebosaas.com/api/docs
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
 

namespace phocebo;

/**
 * Phởcebo Recipe File
 * @const INI Environment Settings File
 */
 
define('INI', '.env');

if (file_exists(INI)) {
    
    $settings = parse_ini_file (INI, true);   
    
    /**
     * @const URL Docebo URL
     */

    define('URL', $settings[docebo]['URL']);
    
    /**
     * @const KEY Docebo public Key
     */

    define('KEY', $settings[docebo]['KEY']);

    /**
     * @const SECRET Docebo secret Key
     */

    define('SECRET', $settings[docebo]['SECRET']);

    /**
     * @const SSO - Future SSO 
     */

    define('SSO', $settings[docebo]['SSO']);   
    
} else die( "\nERROR: Phởcebo ingredients are missing (.env) \n\n");



// Test Class


$action = '/user/checkUsername';

$data_params = array (
    
    'userid' => 'patricia.walton@shrm.org',
    
	'also_check_as_email' => true,
	
);
						
$data = phoceboCook::call($action, $data_params);

var_dump($data);



    
/**
 * Phởcebo Cooking Instructions
 *
 * Usu fastidii corrumpit honestatis ad, his ludus assueverit id, scripta 
 * insolens torquatos eu sea. Eum ei maiorum eleifend molestiae, eu mea movet 
 * placerat iudicabit. Pertinax quaestio te vim, falli utamur senserit in sea, 
 * vix id magna modus assueverit. No eirmod euismod mel, te his dicta evertitur,
 * an tota congue consul sed.
 *
 * @package classpackage
 */
 
class phoceboCook {

	/**
	 * getHash function.
	 * 
	 * @access public
	 * @static
	 * @data_params mixed $params
	 * @return array $codice hash value for x_auth
	 *
	 */
	 
	static public function getHash( $data_params ) {
    	
    	if ( !empty ( $data_params ) ) {
        	
    		$codice = array( 'sha1' => '', 'x_auth' => '' );
    		
    		$codice['sha1'] = sha1 ( implode( ',', $data_params ) . ',' . SECRET );
    		
    		$codice['x_auth'] = base64_encode ( KEY . ':' . $codice['sha1'] );
    		  		
    		return $codice;
    		
    	} else {
        	
        	return null;
        	
    	}
		
	}
	
	
	/**
	 * getDefaultHeader function.
	 * 
	 * @access private
	 * @static
	 * @param mixed $x_auth
	 * @return void
	 *
	 */
	 
	static private function getDefaultHeader( $x_auth ) {
    	
		$host = parse_url ( URL, PHP_URL_HOST );
		
 		return array (
			
			"Host: " . ($host ? $host : ''),
			
			"Content-Type: multipart/form-data",
			
			'X-Authorization: Docebo '.$x_auth,
			
		);
		
	}


	/**
	 * call function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $action Docebo API Call
	 * @param mixed $data_params parameters to send
	 * @return $output JSON formatted response
	 *
	 */
	 
	static public function call ( $action, $data_params ) {
    	
		$curl = curl_init();

		$hash_info = self::getHash ( $data_params );

		$http_header = self::getDefaultHeader ( $hash_info['x_auth'] );

		$opt = array (
    		
			CURLOPT_URL => URL . '/api/' . $action,
			
			CURLOPT_RETURNTRANSFER => 1,
			
			CURLOPT_HTTPHEADER => $http_header,
			
			CURLOPT_POST => 1,
			
			CURLOPT_POSTFIELDS => $data_params,
			
			CURLOPT_CONNECTTIMEOUT => 5, // Timeout to 5 seconds
			
			CURLOPT_SSL_VERIFYPEER => false,
			
			CURLOPT_SSL_VERIFYHOST => false,
			
		);
		
		curl_setopt_array ( $curl, $opt );
		
		$output = curl_exec ( $curl );
		
		curl_close ( $curl );
		
		return $output;
		
	}
    
}

?>