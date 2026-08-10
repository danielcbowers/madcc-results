<?php

if (!defined('ABSPATH')) {
    exit;
}

function mcc_format_name(string $name): string
{
    return mb_convert_case(trim($name), MB_CASE_TITLE, 'UTF-8');
}