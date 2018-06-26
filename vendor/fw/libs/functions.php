<?php

function debug($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

function redirect($http = false)
{
    if ($http) {
        $redirect = $http;
    } else {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
    }

    header("Location: $redirect");
    exit;
}

function prevValue($value)
{
    return isset($_POST[$value]) ? h($_POST[$value]) : '';
}

function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}

function setPrevValue(&$obj, $data)
{
    foreach ($data as $key => $value) {
        $obj->$key = $value;
    }
}

function getExt($filename)
{
    $info = pathinfo($filename);

    return $info['extension'];
}
