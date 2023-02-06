<?php
function getMembershipStatus($startDate, $expiryDate) {
    $today = new DateTime();
    if (!$startDate instanceof DateTime) $startDate = date_create_from_format(SQL_DATE_TIME_FORMAT, $startDate);
    if (!$expiryDate instanceof DateTime) $expiryDate = date_create_from_format(SQL_DATE_TIME_FORMAT, $expiryDate);

    if ($today >= $startDate && $today <= $expiryDate) {
        return 'active';
    }
    else if ($today < $startDate) {
        return 'future';
    }
    else if ($today > $expiryDate) {
        return 'expired';
    }
    else {
        return 'invalid';
    }
}
