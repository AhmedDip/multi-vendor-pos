<?php

namespace App\Manager\Utility;

use App\Manager\Auth\AuthManager;
use App\Models\Category;
use App\Models\District;
use App\Models\Event;
use App\Models\Language;
use App\Models\Skill;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Utility
{
    /**
     * @param string $name
     * @return string
     * @throws Exception
     */
    final public static function prepare_name(string $name = 'no-name-user'): string
    {
        return Str::slug($name . '-' . str_replace(' ', '-', Carbon::now()->toDayDateTimeString() . '-' . random_int(1000, 9999)));
    }

    /**
     * @param string $url
     * @return bool
     */
    final public static function is_url(string $url): bool
    {
        $is_url = false;
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $is_url = true;
        }
        return $is_url;
    }

    final public static function is_phone(string $phone): bool
    {
        $is_phone = false;
        if (preg_match('/^\d{11}$/', $phone)) {
            $is_phone = true;
        }
        return $is_phone;
    }

    /**
     * @return int
     * @throws Exception
     */
    public static function generate_otp(): int
    {
        $min = 10 ** (AuthManager::OTP_LENGTH - 1);
        $max = (10 ** AuthManager::OTP_LENGTH) - 1;
        return random_int($min, $max);
    }

    final public function prepare_event_filter_data(): array
    {
        $experience_data = [];
        $filter = [
            'Job Type'       => Formatter::convert_array(Event::JOB_TYPE_LIST),
            'Categories'     => Formatter::convert_array((new Category())->get_categories_assoc()),
            'Location'       => Formatter::convert_array((new District())->get_district_assoc()),
            'Languages'      => Formatter::convert_array((new Language())->get_languages_assoc()),
            'Skills'         => Formatter::convert_array((new Skill())->get_skills_assoc()),
            'Project Length' => Formatter::convert_array(Event::PROJECT_LENGTH_LIST),
        ];

        foreach ($filter as $key => $value) {
            $query =  strtolower(str_replace(' ', '_', Str::singular($key)));
            
            $data = [
                'name' => $key,
                'query' => $query,
            ];
            foreach ($value as $item) {
                $data['value'][] = [
                    'id' => $item['id'],
                    'name' => $item['value'],
                    'query' => $query,
                ];
            }

            $experience_data[] = $data;
        }

        return $experience_data;
    }
}
