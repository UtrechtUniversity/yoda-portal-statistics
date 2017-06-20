<?php
function bytesToTerabytes($bytes) {
    //https://www.checkyourmath.com/convert/digital_storage/bytes_terabytes.php
    return $bytes / 1000000000000;
}

function bytesToGigabytes($bytes) {
    return $bytes / 1000000000;
}

function roundUpBytes ($value, $places=0) {
    $mult = pow(10, $places);
    return ceil($value * $mult) / $mult;
}