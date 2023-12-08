<?php
require 'vendor/autoload.php';

use libphonenumber\PhoneNumberUtil;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $country = $_POST['country'];

    $phoneUtil = PhoneNumberUtil::getInstance();
    $countryCode = $phoneUtil->getCountryCodeForRegion($country);

    echo $countryCode;
}
