<?php
$response_type = "code";
$client_id = "81j99zm1ygvajl";
$redirect_uri = "https://app.rahberashraf.com/linkedin.php";
$client_secret = "aFdjSw3mKv8rdMYN";
$scope = "r_liteprofile%20r_emailaddress%20w_member_social";
$code = "";


$firstString ="https://www.linkedin.com/oauth/v2/authorization?response_type=".$response_type."&client_id=".$client_id."&redirect_uri=".$redirect_uri."&scope=".$scope;
if($_GET['code']){
$code = $_GET['code'];
}else if ($step==2){
	$ch = curl_init();
	$request_headers = array(
                    "X-Mashape-Key:" . $subscription_key,
                    "X-Mashape-Host:" . $host
                );	
	
	curl_setopt($ch, CURLOPT_URL, "https://www.linkedin.com/oauth/v2/accessToken");
               // curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                $output = json_encode(curl_exec($ch));

$string = "grant_type=authorization_code&code=".$code."&redirect_uri=".$redirect_uri."&client_id=".$client_id."&client_secret=".$client_secret;
}
	




?>


<a href ="<?php echo $firstString ?>" ><img src="https://content.linkedin.com/content/dam/developer/global/en_US/site/img/signin-button.png" /></a>

The Access Code is <input type="text><?php echo $code; ?></input>