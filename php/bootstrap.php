<?php

    require "../vendor/autoload.php";

    $clientId = "AUSp21wTdzaQnRU0d5qmvYKbtNQiWV3c2E6_Ht39NQDN1mY3VgJ4WD_ZexD-48a39lKwjBHhQGvaQ-gz";
    $clientSecret = "EPtPGQXhdcUdUT1Z5sQtr2pQ1HsWggisaU9POVBIqF3LLb8poQxEveQcYcv3_kOieupHVy5jtO8GFezO";

    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            $clientId,
            $clientSecret
        )
    );

?>