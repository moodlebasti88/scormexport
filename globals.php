<?php
/*
 *<p>contains global used constants</p>
 * 
 * @author Bastian Rosenfelder 
 */


define('NL','\n');
define('CRLF','\r\n');


/*page identifiers*/
define('CONTENT','20');
define('MULT_CHOICE','3');
define('NUMERIC','8');
define('TRUE_FALSE','2');
define('SHORTANSWER','1');
define('ASSIGNMENT','5');
define('CONTENT_END','21');
define('ESSAY','10');

/*dropdown returns only the selected array index. So we need this workaround */
define('SCORM12',0);
define('SCORM2004',1);


/*name of the manifest file; should not be changed*/
define('MANIFEST_NAME','imsmanifest.xml');

/*enter here your preferred xml version*/
define('XML_VERSION','1.0');

/*used scorm schema*/
define('SCORM_SCHEMA','ADL SCORM');




?>