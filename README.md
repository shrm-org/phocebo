Phởcebo
==============

Delicious PHP wrapper for https://doceboapi.docebosaas.com/api/docs

Related Projects
--------------

Ruby wrapper: 
https://github.com/huynhquancam/docebo_ruby

Docebo LMS eLearning Platform Integration: 
https://github.com/wp-plugins/docebo-lms-elearning-platform-integration


What is the X-Authorization parameter?
--------------

This is a pre-flighted request which requires the sender to fist send an HTTP 
OPTIONS request. The server checks this to know whether the call is coming from 
a trusted source. 

As a parameter it added to the header of the request. This header is used 
to authenticate to the Docebo API.  


Compute the X-Authorization for Docebo API
--------------

Example
--------------

$action = '/user/checkUsername';

$params = array ('userid', 'also_check_as_email' );

KEY = Docebo Public Key

SCRECT = Docebo Private Key


1. $cypher_sha = implode( ',', $params) . ',' . SECRET ;

2. $cypher_base64 = base64_encode ( KEY . ':' . cypher_sha] );

3. 'X-Authorization: Docebo '. cypher_base64

