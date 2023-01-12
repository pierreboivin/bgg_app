<?php

$url = 'http://www.boardgamegeek.com/xmlapi2/collection?own=1&excludesubtype=boardgameexpansion&stats=1&username=slyqc';

$cookie = 'bggusername=slyqc; bggpassword=';

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

$contentUrl = curl_exec($ch);

var_dump(curl_error($ch));

//$contentUrl = file_get_contents($url);

var_dump($contentUrl);
