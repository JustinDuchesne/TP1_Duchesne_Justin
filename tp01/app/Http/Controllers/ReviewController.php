<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use OpenApi\Attributes as OA;

class ReviewController extends Controller
{

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/api/review/{id}',
        summary: 'Supprimer une review',
        tags: ['Reviews'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Review ID',
                in: 'path',
                required: true,
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Review retirée'),
            new OA\Response(response: 404, description: 'Données invalides'),
        ]
    )]
    public function destroy(string $id)
    {
        try {
            Review::findOrFail($id); // pour lever une erreur
            Review::destroy($id);
            return response()->noContent()->setStatusCode(OK);
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Cannot be found in database');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
