<?php

include_once 'config.php'; // Defines APP_ID and APP_SECRET

class FacebookLogin {

	private $tabUrl;

	public function __construct() {

		$this->tabUrl = 'http://www.facebook.com/apps/application.php?id=128534967229326&sk=app_128534967229326';

		$redirect = $this->tabUrl;
		if (!empty($_GET['app_data'])) {
			// Strip the slashes that Facebook added
			$redirect .= '&app_data='.urlencode(stripslashes($_GET['app_data']));
		}
		header('Location: '.$redirect);

		//echo '<pre>' . print_r($_GET, 1) . '</pre>';
		//echo 'Redirect to '.$redirect;
	}
}

new FacebookLogin();

