<?php

namespace App\Controllers;

interface SessionInterface
{
    public static function get(string $key);

    public static function set(string $key, $value): void;
    // public static function set(string $key, $value): self;

    public static function remove(string $key): void;

    public static function clear(): void;

    public static function has(string $key): bool;
}
