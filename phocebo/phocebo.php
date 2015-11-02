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





?>