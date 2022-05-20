<?php

function hasPrintDebug(): bool
{
    return (isset($_GET['printDebug']) && $_GET['printDebug'] == 1);
}

function dd($data, $print = false, $exit = true): void
{
    echo '<pre>';
    ($print) ? print_r($data) : var_dump($data);
    echo '</pre>';

    if ($exit) {
        exit;
    }
}

function env($key): mixed
{
    $envs = parse_ini_file('../.env');

    return $envs[$key] ?? null;
}
