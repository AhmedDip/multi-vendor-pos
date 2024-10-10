<?php

namespace App\Manager;

use App\Models\AuthUser;
use App\Models\District;
use App\Models\Thana;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Composer\Autoload\includeFile;

class LocationManager
{
    final public static function get_thana_ids(Request $request): array
    {
        // dd($request->all());
        $districts = $request->input('operation_area_district') ?? $request->input('service_operation_area_district');
        $thanas    = $request->input('operation_area') ?? $request->input('service_operation_area');
        $thana_ids = [];
        if (!empty($thanas)) {
            $thana_ids = $thanas;
        }
        if (!empty($districts)) {
            //get district with no selected thanas
            $district_ids = District::query()
                ->whereIn('id', $districts)
                ->whereNotIn('id', function ($query) use ($thana_ids) {
                    $query->select('district_id')
                        ->from('thanas')
                        ->whereIn('id', $thana_ids);
                })
                ->pluck('id')
                ->toArray();

            //get thanas from districts with no selected thanas
            $additional_thana_ids = Thana::query()
                ->whereIn('district_id', $district_ids)
                ->pluck('id')
                ->toArray();

            $thana_ids = array_unique(array_merge($thana_ids, $additional_thana_ids));

            //remove thanas from not districts
            $thana_ids = Thana::query()
                ->whereIn('id', $thana_ids)
                ->whereIn('district_id', $districts)
                ->pluck('id')
                ->toArray();
        }

        return $thana_ids;
    }

    final public static function get_selected_districts(Model|AuthUser|Collection $authUser): array
    {
        $selected_districts = [];
        if ($authUser->operation_area) {
            $unique_districts   = $authUser->operation_area->unique('district_id')->pluck('district_id')->toArray();
            $selected_districts = District::query()
                ->whereIn('id', $unique_districts)
                ->pluck('id')->toArray();
        }

        return $selected_districts;
    }

    final public static function get_location_ids(Request $request): array
    {
        $location_type = $request->input('location_type');
        $thana_ids     = [$request->input('location_id')];
        if (!empty($location_type)) {
            if ($location_type == 'Division') {
                $thana_ids = Thana::query()
                    ->where('division_id', $request->input('location_id'))
                    ->pluck('id')
                    ->toArray();
            } elseif ($location_type == 'District') {
                $thana_ids = Thana::query()
                    ->where('district_id', $request->input('location_id'))
                    ->pluck('id')
                    ->toArray();
            }
        }
        return $thana_ids;
    }

    public static function get_districts_by_ids(array $district_ids)
    {
        return District::query()->select(['id', 'name'])->whereIn('id', $district_ids)->get();
    }
}
