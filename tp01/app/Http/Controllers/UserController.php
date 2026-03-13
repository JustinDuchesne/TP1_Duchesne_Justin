<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use OpenApi\Attributes as OA;

class UserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/api/user',
        summary: 'Ajouter un user',
        tags: ['Users'],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'first_name', type: 'string', example: 'Justin'),
                        new OA\Property(property: 'last_name', type: 'string', example: 'Duchène'),
                        new OA\Property(property: 'email', type: 'string', example: 'Jhon@mcjhon.com'),
                        new OA\Property(property: 'phone', type: 'string', example: '418-418-4188'),
                    ]
                )
            ]
        ),
        responses: [
            new OA\Response(response: 201, description: 'User ajoutée'),
            new OA\Response(response: 422, description: 'Données invalides')
        ]
    )]
    public function store(UserRequest $request)
    {
        try {
            $user = User::create($request->validated());
            return (new UserResource($user))->response()->setStatusCode(OK_CREATED);
        } catch (QueryException $ex) {
            abort(INVALID_CONTENT, 'Cannot be created in database');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Patch(
        path: '/api/user/{id}',
        summary: 'Modifier un user',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'User ID',
                in: 'path',
                required: true,
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: [
                new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'first_name', type: 'string', example: 'Justin'),
                        new OA\Property(property: 'last_name', type: 'string', example: 'Duchène'),
                        new OA\Property(property: 'email', type: 'string', example: 'Jhon@mcjhon.com'),
                        new OA\Property(property: 'phone', type: 'string', example: '418-418-4188'),
                    ]
                )
            ]
        ),
        responses: [
            new OA\Response(response: 200, description: 'User modifiée'),
            new OA\Response(response: 422, description: 'Données invalides'),
            new OA\Response(response: 404, description: 'User non trouvé')
        ]
    )]
    public function update(UserRequest $request, string $id)
    {
        try {
            //https://laracasts.com/discuss/channels/laravel/how-to-do-update-controller-routes-model-and-other-things-from-laravel-54-from-scratch-tutorial
            $user = User::findOrFail($id);
            $user->update($request->all());
            return response()->json($user)->setStatusCode(OK);
        } catch (QueryException $ex) {
            abort(INVALID_CONTENT, 'Cannot be edited in database');
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
