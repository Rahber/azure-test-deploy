<?php
$response_type = "code";
$client_id = "81j99zm1ygvajl";
$redirect_uri = "https://app.rahberashraf.com/linkedin.php";
$client_secret = "aFdjSw3mKv8rdMYN";
$scope = "scope=r_liteprofile%20r_emailaddress%20w_member_social";
$code = "";

if($_GET['code']){
$code = $_GET['code'];
}else if ($step==2){
	
	


$string = "grant_type=authorization_code&code=".$code."&redirect_uri=".$redirect_uri."&client_id=".$client_id."&client_secret=".$client_secret;
}
	




?>


<a href ="" ><img src="https://content.linkedin.com/content/dam/developer/global/en_US/site/img/signin-button.png" /></a>

The Access Code is <input type="text><?php echo $code; ?></input>