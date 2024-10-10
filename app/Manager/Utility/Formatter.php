<?php

namespace App\Manager\Utility;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Formatter
{
    /**
     * @throws Exception
     */
    final public static function generate_unique_email(): string
    {
        return 'eventic_' . time() . '_' . random_int(1000, 9999) . '@' . 'eventic.com';
    }

    final public static function check_is_system_fake_email(string $email): bool
    {
        return str_contains($email, 'eventic_1');
    }

    final public static function url_format(string | null $url): string | null
    {
        // add https if not present
        if ($url) {
            $url_arr = explode('://', $url);
            if (count($url_arr) == 1) {
                $url = 'https://' . $url_arr[0];
            }
        }
        return $url;
    }

    final public static function youtube_url_format(string | null $url): string | null
    {

        if ($url) {
            $url_arr = explode('=', $url);
            if (str_contains($url, 'youtu.be')) {
                $url_arr = explode('/', $url);
                $url = 'https://www.youtube.com/embed/' . end($url_arr);
            } else if (count($url_arr) > 1) {
                $url_arr = explode('&', $url_arr[1]);
                $url = 'https://www.youtube.com/embed/' . $url_arr[0];
            }
        }
        return $url;
    }   

    final public static function convert_array(array|Collection $list, bool $key = true, $label_value = false)
    {
        $multi_dimensional_array = [];
        if ($key) {
            foreach ($list as $id => $value) {
                if ($label_value) {
                    $multi_dimensional_array[] = [
                        'value' => $id,
                        'label' => $value
                    ];
                } else {
                    $multi_dimensional_array[] = [
                        'id' => $id,
                        'value' => $value
                    ];
                }
            }
        } else {
            foreach ($list as $value) {
                if ($label_value) {
                    $multi_dimensional_array[] = [
                        'value' => $value,
                        'label' => $value
                    ];
                } else {
                    $multi_dimensional_array[] = [
                        'id' => $value,
                        'value' => $value
                    ];
                }
            }
        }
        return $multi_dimensional_array;
    }

    final public static function prepare_address(Model|null $address): ?string
    {
        if ($address) {
            $address_string = '';
            $address_string .= $address->address;
            $address_string .= $address->thana->name ? ', ' . $address->thana->name : '';
            $address_string .= $address->district->name ? ', ' . $address->district->name : '';
            $address_string .= $address->division->name ? ', ' . $address->division->name : '';
            $address_string .= $address->landmark ? ', (' . $address->landmark . ')' : '';
            return $address_string;
        }
        return null;
    }
}
