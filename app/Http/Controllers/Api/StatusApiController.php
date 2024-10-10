<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Manager\AccessControl\AccessControlTrait;
use App\Manager\API\Traits\CommonResponse;
use App\Models\Traits\AppActivityLog;
use Illuminate\Database\Eloquent\table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class StatusApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'status';

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $model="App\Models\\". $request['slug'];
            $data=$model::query()->where('id',$request['id'])->first();
            if($data->status == self::STATUS_INACTIVE){
                $data->update(['status' => self::STATUS_ACTIVE]);
            }
            else if($data->status == self::STATUS_ACTIVE){
                $data->update(['status' => self::STATUS_INACTIVE]);
            }
            $this->status_message = 'Status Updated Successfully';
            DB::commit();
        }
        catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('status_update_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
