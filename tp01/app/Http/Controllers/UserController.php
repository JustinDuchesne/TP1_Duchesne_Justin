<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $equipment = User::create($request->validated());
            return (new UserResource($equipment))->response()->setStatusCode(OK_CREATED);
        } catch (QueryException $ex) {
            abort(INVALID_CONTENT, 'Cannot be created in database');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        try{
            //https://laracasts.com/discuss/channels/laravel/how-to-do-update-controller-routes-model-and-other-things-from-laravel-54-from-scratch-tutorial
            $equipment = User::findOrFail($id);
            $equipment->update($request->all());
            return response()->noContent()->setStatusCode(OK);
        }catch (QueryException $ex) {
            abort(INVALID_CONTENT, 'Cannot be edited in database');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
