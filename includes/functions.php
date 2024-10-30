<?php
function bcf_checkLength($value) {
    if (strlen(preg_replace('/ /', '', $value)) < 10) {
        return false;
    } else {
        return true;
    }
}

function bcf_checkLengthSmall($value) {
    if (strlen(preg_replace('/ /', '', $value)) < 3) {
        return false;
    } else {
        return true;
    }
}
