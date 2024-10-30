<?php
function compnp_checklink($link) {
    if (filter_var($link, FILTER_VALIDATE_URL) === FALSE) {
        return false;
    } else {
        return true;
    }
}

function compnp_checkLength($content) {
    if (strlen($content) >= 3) {
        return true;
    }
}
