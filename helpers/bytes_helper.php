<?php
/**
* Bytes helper
 *
 * @package    Yoda
 * @copyright  Copyright (c) 2017-2019, Utrecht University. All rights reserved.
 * @license    GPLv3, see LICENSE.
 */

 /**
  * Convert bytes to terabytes.
  *
  * @param $bytes Number of bytes
  * @return float Number of terabytes
  */
function bytesToTerabytes($bytes) {
    //https://www.checkyourmath.com/convert/digital_storage/bytes_terabytes.php
    return $bytes / 1000000000000;
}

/**
 * Convert bytes to gigabytes.
 *
 * @param $bytes Number of bytes
 * @return float Number of gigabytes
 */
function bytesToGigabytes($bytes) {
    return $bytes / 1000000000;
}

/**
 * Round up bytes.
 *
 * @param value  Number of bytes
 * @param places Decimal places to round up to
 * @return float Number of bytes
 */
function roundUpBytes ($value, $places=0) {
    $mult = pow(10, $places);
    return ceil($value * $mult) / $mult;
}
