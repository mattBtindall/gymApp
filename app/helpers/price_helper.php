<?php
function convertNumberToPrice($number) {
    return CURRENCY_SYMBOL . number_format($number, 2);
}
