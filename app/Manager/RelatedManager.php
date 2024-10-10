<?php
namespace App\Manager;

use App\Models\AuthUser;

class RelatedManager
{
    public static function related_agencies($specialized_in)
    {
        if ($specialized_in->isEmpty()) {
            return AuthUser::query()->take(4)->get();
        }
        return AuthUser::query()
            ->whereHas('specialized_in', function ($query) use ($specialized_in) {
                $query->whereIn('event_categories.id', $specialized_in->pluck('id'));
        })->take(4)->get();
    }
}
