<?php

/* 
 * 
 */

$doi = $_GET[ "doi" ];
$url = "http://dx.doi.org/" . $doi;
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url );
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt ($ch, CURLOPT_MAXREDIRS, 6 );
curl_setopt ($ch, CURLOPT_HTTPHEADER,
             array (
                    "Accept: text/bibliography; style=bibtex" ) );

curl_exec ($ch);
curl_close($ch);

?>