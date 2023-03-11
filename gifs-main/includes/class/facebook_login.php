<?php
function facebook_login2()
{
	debug_r($_REQUEST);
}
function facebook_login()
{
	if($_REQUEST['action'] == 'facebook_login')
	{
		$_SESSION['FBRLH_state']=$_GET['state'];
	    $fb = new \Facebook\Facebook([
	      'app_id' => FB_APP_ID,
	      'app_secret' => FB_APP_SECRET,
	      'default_graph_version' => 'v2.10',
	      //'default_access_token' => '{access-token}', // optional
	    ]);

	    $helper = $fb->getRedirectLoginHelper();

	    try {
	      $accessToken = $helper->getAccessToken();
	    } catch(Facebook\Exceptions\FacebookResponseException $e) {
	      // When Graph returns an error
	      alert('Graph returned an error: ' . $e->getMessage());
	      redirect('index.php');
	    } catch(Facebook\Exceptions\FacebookSDKException $e) {
	      // When validation fails or other local issues
	      alert('Facebook SDK returned an error: ' . $e->getMessage());
	      redirect('index.php');
	    }

	    if (! isset($accessToken)) {
	      if ($helper->getError()) {
	        header('HTTP/1.0 401 Unauthorized');
	        echo "Error: " . $helper->getError() . "\n";
	        echo "Error Code: " . $helper->getErrorCode() . "\n";
	        echo "Error Reason: " . $helper->getErrorReason() . "\n";
	        echo "Error Description: " . $helper->getErrorDescription() . "\n";
	      } else {
	        header('HTTP/1.0 400 Bad Request');
	        echo 'Bad request';
	      }
	      exit;
	    }
/*
	    // Logged in
	    // echo '<h3>Access Token</h3>';
	    // var_dump($accessToken->getValue());
	    // The OAuth 2.0 client handler helps us manage access tokens
	    $oAuth2Client = $fb->getOAuth2Client();

	    // Get the access token metadata from /debug_token
	    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
	    // echo '<h3>Metadata</h3>';
	    // var_dump($tokenMetadata);

	    // Validation (these will throw FacebookSDKException's when they fail)
	    $tokenMetadata->validateAppId(FB_APP_ID); // Replace {app-id} with your app id
	    // If you know the user ID this access token belongs to, you can validate it here
	    //$tokenMetadata->validateUserId('123');
	    $tokenMetadata->validateExpiration();

	    if (! $accessToken->isLongLived()) {
	      // Exchanges a short-lived access token for a long-lived one
	      try {
	        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
	      } catch (Facebook\Exceptions\FacebookSDKException $e) {
	        echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
	        exit;
	      }

	      // echo '<h3>Long-lived</h3>';
	      // var_dump($accessToken->getValue());
	    }
*/
		$_SESSION['fb_access_token'] = (string) $accessToken;
	    $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);
			$user = $response->getGraphUser();
	    $_SESSION['facebook_user_id'] = $user['id'];
		//set_social_login($name, $email, $locale, $email_verified, $profile_image, $source_id)
		set_social_login($user['name'], $user['email'], $user['locale'], 1, $user['picture']['url'], 1, 'Facebook');
	}
	//ELSE GOOOGLE LOGIN
	/*action: "google_login"
email: "hasnainwasaya@gmail.com"
family_name: "Hasnain"
full_name: "Muhammad Hasnain"
given_Name: "Muhammad"
id: "110646928747683319381"
id_token: "eyJhbGciOiJSUzI1NiIsImtpZCI6IjQ2NjVjMjc4MTg5OTAxNDYxNzMzN2RmOWNiZjIyMDY4NjUwNWEwNmMiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXpwIjoiNTI2ODcwMjgwNzIyLTZjY3I1aWZ2ZDBrYWE5cWFnODYxaHBuZWpkaThzcm9zLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiNTI2ODcwMjgwNzIyLTZjY3I1aWZ2ZDBrYWE5cWFnODYxaHBuZWpkaThzcm9zLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTEwNjQ2OTI4NzQ3NjgzMzE5MzgxIiwiZW1haWwiOiJoYXNuYWlud2FzYXlhQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhdF9oYXNoIjoiQzVGV2pITXJtS21FV3JXQUJFSEdhUSIsIm5hbWUiOiJNdWhhbW1hZCBIYXNuYWluIiwicGljdHVyZSI6Imh0dHBzOi8vbGg1Lmdvb2dsZXVzZXJjb250ZW50LmNvbS8tdmFraFVRY2hNS0EvQUFBQUFBQUFBQUkvQUFBQUFBQUFLX1UvMW5kQjV1bGNQUlkvczk2LWMvcGhvdG8uanBnIiwiZ2l2ZW5fbmFtZSI6Ik11aGFtbWFkIiwiZmFtaWx5X25hbWUiOiJIYXNuYWluIiwibG9jYWxlIjoiZW4tR0IiLCJpYXQiOjE1NDU0OTI4ODMsImV4cCI6MTU0NTQ5NjQ4MywianRpIjoiMmJlM2QxZmFlYjllMjk4Y2RiNmJkZmVmZjgyNWY1ODkxOWIxZDZhNCJ9.be18DFdR7P3RMNDozNTnrYYq777S_fzKQvhArTjstMUHaNiQXZWvQVY_7emN3X7_EG7rZYcD7N0rD-NEwGg6x5NH_oyKJKNI26O-eMbRX16RoOhTWYHuoSFhDjTdexF4_ZQ5x3McE6Io33ZtNMzVqzFmUoncrDlIoPfKiBjV69jolN9UpbRusO9Wk1EOsMZumQz6D-hfm6IISRFutMeziR0RON-XWbsidjQO43mBD6undrM__-6atYEmaGwlb1LxXy4Jrp_ZVAyawS5nORKVD_d40_j-KQqQGVC_tBxAHs-Yv7wIqfIGkL5o_IczGFYCX-T_khigRGbIlV256lxNjg"
image_url: */

	 try {
		require_once 'library_google/Google_Client.php';
		require_once 'library_google/contrib/Google_Oauth2Service.php';
		$client = new Google_Client();
		$client->setApplicationName("Google UserInfo PHP Starter Application");
		// Visit https://code.google.com/apis/console?api=plus to generate your
		// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
		// $client->setClientId('insert_your_oauth2_client_id');
		// $client->setClientSecret('insert_your_oauth2_client_secret');
		$client->setRedirectUri(page_link('facebook_login', ''));
		if (isset($_GET['code'])) {
			$oauth2 = new Google_Oauth2Service($client);
			$client->authenticate($_GET['code']);
			$token = $client->getAccessToken();
			$_SESSION['token'] = $token;
			$google_user = $oauth2->userinfo->get();
			set_social_login($google_user['name'], $google_user['email'], $google_user['locale'], $google_user['verified_email'] ? 1 : '0', $google_user['picture'], 7, 'Google');
			//debug_r('Location: ' . filter_var(page_link('facebook_login'), FILTER_SANITIZE_URL));
			//return;
		}
		else
		{

		}
	}
	catch(Exception $e) 
	{
		// When Graph returns an error
		alert('Error Google Login ' . $e->getMessage());
		redirect('index.php');
	}	     
	
	if($_POST['action'] == "google_login")
	{
		
		$token = $_POST["id_token"];//https://accounts.google.com/o/oauth2/revoke?token=
		
		$data = json_decode(file_get_contents("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$token));
		if($data)
		{
			// $client->setDeveloperKey('insert_your_developer_key');
			$oauth2 = new Google_Oauth2Service($client);
			  $client->authenticate($token);
			  $_SESSION['token'] = $client->getAccessToken();

			debug_r($_POST);
			//action = sucess and redirect to index page
			$email = $_POST['email'];
			if(!$email)
			{
				
			}
			elseif($user = get_tuple("users", $email, "users_email"))
			{
				//user is logging in 
				$_SESSION['users_id'] = $user['users_id'];
				$_SESSION['users_name'] = $user['users_name'];
				$_SESSION['users_email'] = $user['users_email'];
				$_SESSION['groups_id'] = $user['groups_id'];
				$_SESSION['permission_dynamic_cmm'] = 'Yes';
				$_SESSION['source_id'] = $source_id;
				$_SESSION['token'] = $token;
				$user_full_name = $user['users_full_name'];
				$_SESSION["user_full_name"] = $user_full_name;
				//debug_r($_SESSION);
				die(json_encode(array('result'=>'failure')));
			}
			else
			{
				//check for guest
				$guest = get_tuple("guest", $email, "guest_email");
				if($guest)
				{
					debug_r($guest);					
					die(json_encode(array('result'=>'success')));
				}
			}
			
		}
		//action = failure 
		die(json_encode(array('result'=>'success')));
	}
}


function set_social_login($name, $email, $locale, $email_verified, $url, $source_id, $source_name)
{
	$email = filter_var($email, FILTER_SANITIZE_EMAIL);
	if($user = get_tuple("users", $email, "users_email"))
	{
		//user is logging in 
		$_SESSION['users_id'] = $user['users_id'];
		$_SESSION['users_name'] = $user['users_name'];
		$_SESSION['users_email'] = $user['users_email'];
		$_SESSION['profile_image'] = $user['profile_image'];
		$_SESSION['groups_id'] = $user['groups_id'];
		$_SESSION['member_since'] = humanTiming($user['added_on']);
		$_SESSION['permission_dynamic_cmm'] = 'Yes';
		$_SESSION['source_id'] = $source_id;
		$user_full_name = $user['users_full_name'];
		$_SESSION["users_full_name"] = $user_full_name;
		//debug_r($_SESSION);
	}
	else
	{
		//check for guest
		$guest = get_tuple("guest", $email, "guest_email");
		if($guest)
		{
			//die(json_encode(array('result'=>'success')));
		}
		else
		{
			//register as new email 
			$added_on = time();
			$rnd = returnPassword();
			$extension = basename($url);
			if(strlen($extension) > 10 )
			{
				$extension = ".jpg";
			}
			$profile_image = 'guest_images/'.'/'.adodb_date("ymd").$rnd.$extension;
			//$img = _level0.'/'.$profile_image;
			file_put_contents(_level0.'/'.$profile_image, file_get_contents($url));
			$sql = "Insert into guest (guest_name, guest_email, email_verified, locale, source_id, source_name, profile_image) VALUES ('$name', '$email', '$email_verified', '$locale', '$added_on', '$source_id', '$source_name', '$profile_image')";
			$db = new db2();
			$db->sqlq($sql, 1);
			$guest = get_tuple("guest", $email, "guest_email");
		}
		$_SESSION['guest_id'] = $guest['guest_id'];
		$_SESSION['member_since'] = humanTiming($guest['added_on']);
		$_SESSION['guest_name'] = $guest['guest_name'];
		$_SESSION['guest_email'] = $guest['guest_email'];
		$_SESSION['profile_image'] = $guest['profile_image'];
		$_SESSION['groups_id'] = 4;
		$_SESSION['permission_dynamic_cmm'] = 'Yes';
		$_SESSION['source_id'] = 7;//'google';
		$user_full_name = $guest['guest_name'];
		$_SESSION["user_full_name"] = $user_full_name;
		//debug_r($_SESSION);
	}
	if(isset($_SESSION["page_url_current"]) && $_SESSION["page_url_current"])
		redirect($_SESSION["page_url_current"]);
	redirect('index.php');
}