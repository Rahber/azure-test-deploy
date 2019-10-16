<?php
error_reporting(~E_NOTICE);
$response_type = "code";
$client_id = "81j99zm1ygvajl";
$redirect_uri = "https://app.rahberashraf.com/linkedin.php";
$client_secret = "aFdjSw3mKv8rdMYN";
$scope = "r_liteprofile%20r_emailaddress%20w_member_social";
$code = "";
$current_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


$firstString ="https://www.linkedin.com/oauth/v2/authorization?response_type=".$response_type."&client_id=".$client_id."&redirect_uri=".$redirect_uri."&scope=".$scope;
if($_GET['code']){
$code = $_GET['code'];
}
if($_POST['frmAction']=="access"){
	$ch = curl_init();
	$request_headers = array(
                    "POST /oauth/v2/accessToken HTTP/1.1",
                    "Host: www.linkedin.com",
					"Content-Type: application/x-www-form-urlencoded"
                );	
	$secondString = "client_id=".$client_id."&grant_type=authorization_code&code=".$code."&redirect_uri=".$redirect_uri."&client_secret=".$client_secret;
	curl_setopt($ch, CURLOPT_POSTFIELDS, $secondString );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "https://www.linkedin.com/oauth/v2/accessToken");
	$result = (curl_exec($ch));
	$resultt = json_decode($result);
	
	
	if($resultt->access_token){
		$oo = $resultt->access_token;
	}else if($resultt->error){
		$oo = $resultt->error;
	}else{
		$oo = "There was an error";
	}
	curl_close($ch);

}else if($_POST['frmAction']=="profile"){
	$access_token = $_POST['access_token'];
	$oo = $access_token;
$ch = curl_init();
	$request_headers = array(
                    "GET /v2/me HTTP/1.1",
                    "Host: api.linkedin.com",
					"X-RestLi-Protocol-Version:2.0.0",
					"Connection: Keep-Alive",
					"Authorization: Bearer ".$access_token
                );	
	//$secondString = "client_id=".$client_id."&grant_type=authorization_code&code=".$code."&redirect_uri=".$redirect_uri."&client_secret=".$client_secret;
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $secondString );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "https://api.linkedin.com/v2/people/(id:45744062)");
	echo $result = (curl_exec($ch));
	$resultt = json_decode($result);
	
	
	/*if($resultt->access_token){
		$profile = $resultt->access_token;
	}else if($resultt->error){
		$profile = $resultt->error;
	}else{
		$profile = "There was an error";
	}
	*/
	curl_close($ch);	
}
		
		





?>
<br />
<br />
<br />

<a href ="<?php echo $firstString ?>" ><img src="https://content.linkedin.com/content/dam/developer/global/en_US/site/img/signin-button.png" /></a>
<br />
<br />
<br />
<form action="<?php echo $current_uri?>" method="post">
<input type="hidden" name="frmAction" value="access">
The Authorization Code is <input type="text" value="<?php echo $code; ?>" />
<input type="submit" value="Fetch Access Code">
</form>

<br /><br />

<form action="<?php echo $current_uri?>" method="post">
<input type="hidden" name="frmAction" value="profile">
The Access Code is <input type="text" name ="access_token" value="<?php echo $oo; ?>" />
<input type="submit" value="Fetch Profile">
</form><br />
The Profile is <input type="text" value="<?php echo $profile; ?>" /><br />