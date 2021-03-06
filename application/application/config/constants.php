<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*  CONSTANTS USED IN APP */

define('USER_FRIEND_REJ', -1);
define('USER_FRIEND_BLK', 0);
define('USER_FRIEND_REQ', 1);
define('USER_FRIEND_ACPT', 2);
define('PUSH_MESSAGE_REF', 'push');
define('PUSH_MESSAGE_SHARE', 'This is a test push notification for share');
define('PUSH_MESSAGE_INVITE', 'This is a test push notification for invite');
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('IOS_PUSH_APIKEY', '');
define('IOS_PUSH_GATEWAY', 'ssl://gateway.sandbox.push.apple.com:2195');
define('IOS_PUSH_CERTIFICATE', '/certificates/apns-Certificates.pem');


/* End of file constants.php */
/* Location: ./application/config/constants.php */