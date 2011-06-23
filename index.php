<?php

include_once 'config.php'; // Defines APP_ID and APP_SECRET
include_once 'client/facebook.php';

class MyPageTab {

	private $facebook;
	private $tabUrl;

	public function __construct() {

		// Initialise Facebook SDK
		$this->facebook = new Facebook(array(
			'appId'  => APP_ID,
			'secret' => APP_SECRET,
		));

		// Get the Facebook signed_request object
		$signedRequest = $this->facebook->getSignedRequest();

		// Get the app_data string parameter and decode it into an array
		$appData = array();
		if (!empty($signedRequest) && !empty($signedRequest['app_data'])) {
			$appData = json_decode($signedRequest['app_data'], true);
		}

		// Get the colour parameter from app_data or use a default of blue
		$colour = (!empty($appData['colour']) ? $appData['colour'] : 'blue');

		// Echo the top of the HTML page
		$this->echoPageTop($colour);

		// Echo the page heading
		if ($colour == 'red') {
			echo '<h1>Red page</h1>';
		} elseif ($colour == 'green') {
			echo '<h1>Green page</h1>';
		} elseif ($colour == 'blue') {
			echo '<h1>Blue page</h1>';
		}

		$userId = $this->facebook->getUser();
		echo '<p>Is the session accessible by the server? - ' . (empty($userId) ? 'No' : 'Yes, user ID = ' . $userId) . '</p>';
		echo '<p>Is the session accessible by JavaScript? - <span id="login-status">checking...</span></p>';

		// Echo a navigation menu
		$this->tabUrl = 'https://www.facebook.com/apps/application.php?id=128534967229326&sk=app_128534967229326';
		echo '<nav>';
		$this->echoNavItem(array(
			'colour'        => 'red',
			'another_param' => '5000'
		));
		$this->echoNavItem(array(
			'colour' => 'green',
			'id'     => '12',
			'list'   => array('kangaroos & wallabies', 'koalas', 'wombats', 'emus')
		));
		$this->echoNavItem(array(
			'colour' => 'blue'
		));
		echo '</nav>';

		// Echo the app_data array
		echo '<h2>app_data params</h2>';
		echo '<pre>' . print_r($appData, 1) . '</pre>';

		// Echo the bottom of the HTML page
		$this->echoPageBottom();
	}

	private function echoNavItem($params) {
		$encodedParams = urlencode(json_encode($params)); // Encode the parameters to a JSON string for use in a URL query string
		$url = $this->facebook->getLoginUrl(array(
			'redirect_uri' => 'https://playground.thesedays.com/facebook-serverside-login/facebook-callback.php?app_data=' . $encodedParams
		));
		echo '<a href="' . $url . '" target="_top">Login and then visit ' . $params['colour'] . '</a><br/> ';
	}

	private function echoPageTop($colour) {
		echo "<!doctype html>\n" .
			'<html>' .
				'<head>' .
					'<meta charset=utf-8>' .
					'<title>Demo of app_data parameter</title>' .
					'<style>body { font-family: sans-serif; color: #FFF; } a { color: #FFF; }</style>' .
				'</head>' .
				'<body style="background: '.$colour.'">' .
					'<div id="fb-root"></div>' .
					'<script src="//connect.facebook.net/en_US/all.js"></script>' .
					'<script>' .
						'FB.init({ appId: "'.APP_ID.'", status: true, cookie: true, xfbml: false });' .
						'FB.getLoginStatus(function(response) {' .
							'var status = document.getElementById("login-status");' .
							'if (response.session) {' .
								'status.innerHTML = "Logged in";' .
							'} else {' .
								'status.innerHTML = "Not logged in";' .
							'}' .
						'});' .
					'</script>';
	}

	private function echoPageBottom() {
		echo '</body>' .
			'</html>';
	}

}

new MyPageTab();

