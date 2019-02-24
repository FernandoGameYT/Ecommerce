<?php
    session_start();
    include("fb_init.php");
    include("UserController.php");
    
    $helper = $fb->getRedirectLoginHelper();

    try {
        $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo "Graph error";
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo "Facebook SDK error";
        exit;
    }

    if (!isset($accessToken)) {
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

    $oAuth2Client = $fb->getOAuth2Client();

    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
    echo '<h3>Metadata</h3>';
    var_dump($tokenMetadata);

    $tokenMetadata->validateAppId('594236447661664');
    $tokenMetadata->validateExpiration();

    if (!$accessToken->isLongLived()) {
        try {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo "Error al obtener el token de acceso de larga duración";
            exit;
        }
    }

    $_SESSION["fb_access_token"] = (string) $accessToken;

    try {
        $response = $fb->get("/me?fields=id,email", $accessToken);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo "Graph error";
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo "Facebook SDK error";
        exit;
    }
    
    $user = $response->getGraphUser();
    
    $userData = [
        "OAuthProvider" => "facebook",
        "OAuthId" => $user["id"]
    ];

    $users = new Users();

    if($users -> hasEmail($user["email"])) {
        echo "El correo electronico ya esta registrado.";
        exit;
    }

    $state = $users -> checkUser($userData);

    if($state) {
        header("Location: $direction");
        exit;
    }else{
        header("Location: $direction"."Users/FBRegister/");
        exit;
    }

?>