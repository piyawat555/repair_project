<?php

require_once('LineLogin.php');

$line = new LineLogin();
$get = $_GET;

$code = $get['code'];
$state = $get['state'];

$token = $line->token($code, $state);

if (property_exists($token, 'error'))
    echo "error";

if ($token->id_token) {
    $profile = $line->profileFormIdToken($token);

    $line_id = $line->profile($profile->access_token);
    $_SESSION['img_profile'] = $profile->picture;
    $_SESSION['line_id'] =$line_id->userId;
    echo '<script>
    window.location = "../login.php";
     </script>';

}
