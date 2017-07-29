<?php
function trace($var, $return = false, $parse_html=false)
{
	$dbt = debug_backtrace(false);
	$debug = $dbt[0];
	$debug_info = '<span>function <b>trace()</b> called in: <b>'.$debug['file'].'</b> at line: <b>'.$debug['line'].'</b></span>';
	if(!$parse_html)
	{
		$parsed = '<pre>'.print_r($var, true).'</pre>';
	} else {
		$parsed =  '<pre>'.htmlentities(print_r($var, true)).'</pre>';
	}
	if($return)
	{
		return $parsed.$debug_info;
	} else {
		echo $parsed.$debug_info;
	}
}

function escape($string, $use_mysql_escape=false)
{
	$string = stripslashes($string);
	return (!$use_mysql_escape) ? addslashes($string) : mysql_real_escape_string($string);
}

function get_user_image()
{
	$user = user::get_instance('user');
	if($user->social_account == 'facebook')
	{
		return 'https://graph.facebook.com/'.$user->social_profile['username'].'/picture?width=40&amp;height=40';
	}
	if($user->social_account == 'twitter')
	{
		return $user->social_profile->profile_image_url_https;
	}
	return '';
}

function get_user_complete_name()
{
	$user = user::get_instance('user');
	if($user->social_account == 'facebook')
	{
		$middle_name = (isset($user->social_profile['middle_name'])) ? $user->social_profile['middle_name'] : '';
		return $user->social_profile['first_name'].' '.$middle_name.' '.$user->social_profile['last_name'];
	}
	if($user->social_account == 'twitter')
	{
		return $user->social_profile->name;
	}
	return '';
}

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }
    switch ($errno) {
		case E_USER_ERROR:
			echo "<b>My ERROR</b> [$errno] $errstr<br />\n Fatal error on line $errline in file $errfile, PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\nAborting...<br />\n";
			exit(1);
			break;
	
		case E_USER_WARNING:
		case E_WARNING:
			reports::set_report("<b>Warning</b> $errstr in <b>$errfile</b> on line <b>$errline</b> error number <b>[$errno]</b>");
			break;
	
		case E_USER_NOTICE:
		case E_NOTICE:
			reports::set_report("<b>Notice</b> $errstr in <b>$errfile</b> on line <b>$errline</b> error number <b>[$errno]</b>");
			break;
	
		default:
       		reports::set_report("Unknown error type: [$errno] $errstr");
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}
//set_error_handler("myErrorHandler");