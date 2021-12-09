<?php

function autoInc() : string {
    static $autoInc = 1;
    return (string)$autoInc++;
}
