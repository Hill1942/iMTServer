<?php
if(file_exists('vendor/autoload.php')){
	require 'vendor/autoload.php';
} else {
	echo "<h1>Please install via composer.json</h1>";
	echo "<p>Install Composer instructions: <a href='https://getcomposer.org/doc/00-intro.md#globally'>https://getcomposer.org/doc/00-intro.md#globally</a></p>";
	echo "<p>Once composer is installed navigate to the working directory in your terminal/command promt and enter 'composer install'</p>";
	exit;
}

if (!is_readable('app/core/Config.php')) {
	die('No Config.php found, configure and rename config.example.php to Config.php in app/core.');
}

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
	define('ENVIRONMENT', 'development');
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but production will hide them.
 */

if (defined('ENVIRONMENT')){

	switch (ENVIRONMENT){
		case 'development':
			error_reporting(E_ALL);
		break;

		case 'production':
			error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}

}
//initiate config
new \core\Config();

//create alias for Router
use \core\Router,
    \helpers\Url;

//define routes
Router::any('api/init-role', '\controllers\api\user@initRole');
Router::any('api/get-top-list', '\controllers\api\user@getTopList');
Router::any('api/upload-survival-score', '\controllers\api\user@uploadSurvivalScore');
Router::any('api/upload-challenge-score', '\controllers\api\user@uploadChallengeScore');
Router::any('api/get-friend-list', '\controllers\api\user@getFriendRankList');

Router::any('api/send-red-heart', '\controllers\api\user@sendRedHeart');
Router::any('api/send-message', '\controllers\api\user@sendMessage');
Router::any('api/receive-mail', '\controllers\api\user@receiveMail');
Router::any('api/get-all-mails', '\controllers\api\user@getAllMails');

Router::any('api/test', '\controllers\api\user@test');

//if no route found
Router::error('\core\error@index');

//turn on old style routing
Router::$fallback = false;

//execute matched routes
Router::dispatch();