<?php
function getMembershipStatus($startDate, $expiryDate) {
    $today = new DateTime();
    if (!$startDate instanceof DateTime) $startDate = DateTime::createFromFormat(SQL_DATE_TIME_FORMAT, $startDate);
    // +1 day as it has to be after this day
    if (!$expiryDate instanceof DateTime) $expiryDate = DateTime::createFromFormat(SQL_DATE_TIME_FORMAT, $expiryDate)->modify('+1 day');

    if ($today > $startDate && $today <= $expiryDate) {
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

function formatForOutput($dateTime, $outputFormat = OUTPUT_DATE_TIME_FORMAT) {
    return DateTime::createFromFormat(SQL_DATE_TIME_FORMAT, $dateTime)->format($outputFormat);
}

function formatActivity($activity) {
    $activity['date'] = formatForOutput($activity['created_at']);
    $activity['time'] = formatForOutput($activity['created_at'], 'H:i:s');
    unset($activity['created_at']);

    $activity['status'] = ($activity['membership_status'] == 'active') ? GRANTED_ACCESS_ICON . 'granted' : NO_ENTRY_ICON . 'no-entry';
    unset($activity['is_active']);
    return $activity;
}

function convertUnixTimeToDays($unixTime) {
    return $unixTime/60/60/24;
}
