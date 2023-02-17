<?php
function printArray($array) {
    if (!$array) {
        echo 'Empty Array';
        return;
    }

    foreach($array as $key => $value) {
        echo '<br>' . $key . ': ' . '<br>';
        foreach ($value as $innerKey => $innerValue) {
            echo $innerKey . ': ';
            var_dump($innerValue);
            echo '<br>';
        }
    }
}
