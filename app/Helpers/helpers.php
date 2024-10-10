<?php

use App\Manager\ImageUploadManager;
use App\Manager\UI\MenuManager;
use App\Manager\UI\UIUtilityManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

function app_error_log(string $name, Throwable $throwable, string $type = 'info'): void
{
    Log::{$type}($name, ['message' => $throwable->getMessage(), 'error' => $throwable]);
}

function app_success_log(string $name, string $message, string $type = 'info'): void
{
    Log::{$type}($name, ['message' => $message]);
}

function success_alert($message): void
{
    session()->flash('message', $message);
    session()->flash('class', 'success');
}

function failed_alert($message): void
{
    session()->flash('message', $message);
    session()->flash('class', 'warning');
}

function is_active(string $route, string $name): string
{
    return MenuManager::is_active($route, $name);
}

function get_profile_photo(): string
{
    return UIUtilityManager::get_profile_photo();
}

function get_image(string|null $path): string
{
    return ImageUploadManager::get_image($path);
    //replace https to http
    // return str_replace('https://', 'http://', $url);
}

function chat_time($time): string
{
    //if today then show time if yesterday then show yesterday else show date
    $today     = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $date      = Carbon::parse($time)->format('Y-m-d');
    if ($today === $date) {
        return Carbon::parse($time)->format('h:i A');
    } elseif ($yesterday === $date) {
        return 'Yesterday';
    } else {
        return Carbon::parse($time)->format('d M y');
    }
}

function min_diff_human($time): string
{
    $diff = Carbon::parse($time)->diff(Carbon::now());

    $time = '';
    if ($diff->h > 0) {
        $time .= $diff->h . ' hr ';
        return $time;
    }
    if ($diff->i > 0) {
        $time .= $diff->i . ' min ';
        return $time;
    }
    $time .= $diff->s . ' sec';

    return $time;
}

/**
 * @param $number
 * @return bool|string
 */
function number($number): string|bool
{
    $numberFormatter = new NumberFormatter(App::getLocale(), NumberFormatter::DECIMAL);
    return $numberFormatter->format($number);
}

function date_time($time): string
{
    $nowFr    = Carbon::parse($time);
    $day_name = $nowFr->translatedFormat('l');
    $date     = number($nowFr->translatedFormat('j'));
    $month    = $nowFr->translatedFormat('F');
    $year     = number($nowFr->translatedFormat('y'));
    $hour     = number($nowFr->translatedFormat('H'));
    $min      = number($nowFr->translatedFormat('i'));
    $am_pm    = $nowFr->translatedFormat('a');

    return $day_name . ', ' . $date . ' ' . $month . ', '.number(20) . $year . ' ' . $hour . ':' . $min . ' ' . $am_pm;
}
