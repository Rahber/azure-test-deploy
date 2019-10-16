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
if($_POST){
	
	$ch = curl_init();
	$request_headers = array(
                    "POST /oauth/v2/accessToken HTTP/1.1",
                    "Host: www.linkedin.com",
					"Content-Type: application/x-www-form-urlencoded"
                );	
	$secondString = "client_id=".$client_id."&grant_type=authorization_code&code=".$code."&redirect_uri=".$redirect_uri."&client_secret=".$client_secret;
	curl_setopt($ch, CURLOPT_POSTFIELDS, $secondString );
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
	curl_setopt($ch, CURLOPT_URL, "https://www.linkedin.com/oauth/v2/accessToken");
	  $output = curl_exec($ch);
	echo $output = json_decode($output);
	
	if($output[0]->access_token){
		$oo = $output[0]->access_token;
	}else if($output[0]->error){
		$oo = $output[0]->error;
	}else{
		$oo = "There was an error";
	}

}


	




?>


<a href ="<?php echo $firstString ?>" ><img src="https://content.linkedin.com/content/dam/developer/global/en_US/site/img/signin-button.png" /></a>
<br />
<br />
<br />
<form action="<?php echo $current_uri?>" method="post">
<input type="hidden" name="hidden">
<input type="submit" value="Submit form">
</form>

<br />
The Authorization Code is <input type="text" value="<?php echo $code; ?>" /><br />
The Access Code is <input type="text" value="<?php echo $oo; ?>" />