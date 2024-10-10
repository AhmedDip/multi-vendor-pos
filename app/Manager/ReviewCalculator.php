<?php

namespace App\Manager;

use App\Models\AuthUser;
use App\Models\Review;

class ReviewCalculator
{

    public static function calculate($auth_user_id)
    {
        $auth_user    = (new AuthUser())->get_auth_by_id($auth_user_id);
        $total_review = 0;
        $avg_rating   = 0;

        if ($auth_user) {
            $total_review = $auth_user->reviews->where('status', Review::STATUS_ACTIVE)->count();
            $avg_rating   = $auth_user->reviews->where('status', Review::STATUS_ACTIVE)->avg('rating');
        }
        return [
            'total_review' => number_format($total_review, 2),
            'avg_rating'   => number_format($avg_rating, 2)
        ];
    }
}
