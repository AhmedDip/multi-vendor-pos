<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionExtended extends Permission
{

    /**
     * @param Request $request
     * @param bool $all
     * @return LengthAwarePaginator|Collection
     */
    final public function get_permissions(Request $request, bool $all = false): LengthAwarePaginator|Collection
    {
        $query = self::query()->with('roles');
        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('role')) {

        }
        if ($request->input('order_by_column')) {
            $direction = $request->input('order_by', 'asc') ?? 'asc';
            $query->orderBy($request->input('order_by_column'), $direction);
        }else{
            $query->orderBy('id', 'desc');
        }
        if ($all) {
            return $query->get();
        }
        return $query->paginate($request->input('per_page', 20));
    }
}
