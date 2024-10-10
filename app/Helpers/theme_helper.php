<?php

use Illuminate\Support\Facades\Cache;

function theme(): string
{
    return Cache::get('theme', 1);
}
function set_theme(int $theme):void
{
    Cache::forever('theme', $theme);
}
