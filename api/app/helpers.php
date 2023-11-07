<?php

declare(strict_types=1);

if (!function_exists('toArrayData')) {
    function toArrayData(array $keys, array $values)
    {
        return array_diff(array_combine($keys, $values), ['']);
    }
}

if (!function_exists('isManageArticle')) {
    function isManageArticle()
    {
        $config = app()->make('config')->get('role');
        return $config['products']['role'] === 'admin';
    }
}

if (!function_exists('getFullName')) {
    function getFullName(): string
    {
        $config = app()->make('config')->get('role');
        return $config['products']['lastName'] . ' ' . $config['products']['name'] . ' ' . $config['products']['patronymic'];
    }
}

if (!function_exists('parseToReadStyle')) {
    function parseToReadStyle(array $data): string
    {
        return implode(PHP_EOL, array_map(static fn ($key, $value) => "{$key}: {$value}", array_keys($data), $data));
    }
}
