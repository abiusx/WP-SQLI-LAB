<?php
//curl

$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://odihost.com/news.php");
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
$data = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);

?>