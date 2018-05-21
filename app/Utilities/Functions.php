<?php

namespace App\Utilities\Functions;

function testBladedVar(string $var)
{
    return isset($var) && unserialize(html_entity_decode($var)) !== '';
}

function getBladedContent(string $var)
{
    if (isset($var) && unserialize(html_entity_decode($var)) !== '') {
        return unserialize(html_entity_decode($var));
    } else {
        return null;
    }
}

function testVar($var)
{
    return isset($var) && !empty($var);
}
