Phởcebo
==============

Delicious PHP wrapper for https://doceboapi.docebosaas.com/api/docs

Related Projects
--------------

Ruby wrapper: https://github.com/huynhquancam/docebo_ruby

Docebo LMS eLearning Platform Integration: https://github.com/wp-plugins/docebo-lms-elearning-platform-integration


What is the X-Authorization parameter?
--------------

It is the parameter that has to be added to the header of the request. This 
header is used to authenticate the call to an API and the server first checks 
for this parameter to know whether the call is coming from a trusted source. 
This kind of requests that have a custom X- headers are called pre-flighted 
requests that require the sender to first send an HTTP OPTIONS request. The 
server responds with a list of allowed actions that can be performed. Only 
if the origin(of the sender) is allowed to have the specific header/Have access
to the server resources, the request is actually executed.


How does one compute the X-Authorization parameter to add to the request header 
to make calls to the Docebo API?
--------------

It is as follows : First take a look at the API documentation of Docebo for the 
specific API you want to call. It will have a list of parameters that the call 
requires. Then, you need to have the API keys from docebo handy since both of 
them are used in generating this X-Authorization parameter.Then proceed as 
follows :

1)Suppose you have n parameters that the call needs.Do the following : 
SHA1 encoding of the following string between the brackets - 
(param-1,param-2,param-3.....param-n,secretKey). Don't forget the commas! 
Take the SHA1 hash generated in this step and proceed to step 2

2)A UTF-8 base64 encoding of the following string between the brackets- 
(PublicKey:hash from step 1).Again, don't forget the colon! and you will 
obtain an alphanumeric string.

3) The X-Authorization parameter is - Docebo code 
(notice the space between Docebo and the code).

4)Add the parameter named X-Authorization to the request header before 
sending it and you will receive the response.

