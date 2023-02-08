<?php
function getMembershipStatus($startDate, $expiryDate) {
    $today = new DateTime();
    if (!$startDate instanceof DateTime) $startDate = DateTime::createFromFormat(SQL_DATE_TIME_FORMAT, $startDate);
    if (!$expiryDate instanceof DateTime) $expiryDate = DateTime::createFromFormat(SQL_DATE_TIME_FORMAT, $expiryDate);

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
