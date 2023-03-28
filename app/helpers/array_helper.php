<?php
function changeKeyName($memberships, $oldKeyName, $newKeyName) {
    foreach($memberships as &$membership) {
        $membership[$newKeyName] = $membership[$oldKeyName];
        unset($membership[$oldKeyName]);
    }
    return $memberships;
}