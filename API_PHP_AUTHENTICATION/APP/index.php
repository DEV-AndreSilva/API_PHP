<?php

echo 'Ola mundo';
echo '<hr>';
//request API

api_request('http://localhost/API/API_PHP_AUTHENTICATION/API/get_datetime/');


function api_request($endpoint, $user = null, $pass = null)
{
    $curl =  curl_init($endpoint);
    $headers =  array(
        'Content-Type: application/json',
        'Authorization: Basic '.base64_encode("$user:$pass")
    );

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    $response = curl_exec($curl);

    if(curl_errno($curl))
    {
        throw new Exception(curl_error($curl));
    }

    curl_close($curl);

    echo "<pre>$response</pre>";
}

