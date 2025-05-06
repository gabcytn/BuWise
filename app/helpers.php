<?php

if (!function_exists('truncate')) {
    function truncate($text, $max = 50)
    {
        return strlen($text) > 50 ? substr($text, 0, $max) . '...' : $text;
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date)
    {
        $res = \Carbon\Carbon::parse($date);
        return $res->format('F d, Y');
    }
}
