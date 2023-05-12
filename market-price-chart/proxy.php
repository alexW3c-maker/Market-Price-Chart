<?php
header('Content-Type: text/csv');
header('Access-Control-Allow-Origin: *');

$csvUrl = 'https://api.blockchain.info/charts/market-price?format=csv&timespan=all';
echo file_get_contents($csvUrl);