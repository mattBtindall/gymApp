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

function formatForOutput($dateTime, $outputFormat = OUTPUT_DATE_TIME_FORMAT) {
    return DateTime::createFromFormat(SQL_DATE_TIME_FORMAT, $dateTime)->format($outputFormat);
}

function formatActivity($activity) {
    $activity['date'] = formatForOutput($activity['created_at']);
    $activity['time'] = formatForOutput($activity['created_at'], 'H:i:s');
    unset($activity['created_at']);

    $activity['status'] = ($activity['membership_status'] == 'active') ? '<i class="bi bi-check-circle"></i>granted' : '<i class="bi bi-x-circle"></i>no-entry';
    unset($activity['is_active']);
    return $activity;
}