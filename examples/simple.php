<?php

require_once(dirname(__FILE__).'/../KLGCountries.php');

$klgCountries = new KLGCountries();
$countries = $klgCountries->getCountries();
print_r($countries);

exit();


