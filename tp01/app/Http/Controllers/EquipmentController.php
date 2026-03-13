<?php

namespace App\Http\Controllers;

use App\Http\Requests\AverageRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use OpenApi\Attributes as OA;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/equipment',
        summary: 'routourne une liste de equipment',
        tags: ['Equipments'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK'
            )
        ]
    )]
    public function index()
    {
        try {
            return EquipmentResource::collection(Equipment::paginate(20))->response()->setStatusCode(OK);
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/api/equipment/{id}',
        summary: 'Afficher un equipment',
        tags: ['Equipments'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Equipment ID',
                in: 'path',
                required: true,
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK'
            ),
            new OA\Response(
                response: 404,
                description: 'Equipment non trouvé'
            )
        ]
    )]
    public function show(string $id)
    {
        try {
            return (new EquipmentResource(Equipment::findOrFail($id)))->response()->setStatusCode(OK);
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    #[OA\Get(
        path: '/api/equipment/popularity/{id}',
        summary: 'Afficher la popularité d\'un equipment',
        tags: ['Equipments'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Equipment ID',
                in: 'path',
                required: true,
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK'
            ),
            new OA\Response(
                response: 404,
                description: 'Equipment non trouvé'
            )
        ]
    )]
    public function popularity(string $id)
    {
        try {
            Equipment::findOrFail($id);

            $allReviews = Rental::join('reviews', 'rentals.id', '=', 'reviews.rental_id')
                ->where('rentals.equipment_id', '=', $id)
                ->selectRaw('COALESCE(COUNT(rentals.id),0) AS Nb_Reviews, SUM(reviews.rating) AS Avg_rating')
                ->first();

            $popularity = round(($allReviews->Nb_Reviews * 0.6) + ($allReviews->Avg_rating * 0.4), 2);

            return response()->json(['popularity' => $popularity])->setStatusCode(OK);
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    #[OA\Get(
        path: '/api/equipment/average/{id}',
        summary: 'Afficher la moyenne du prix d\'un equipment',
        tags: ['Equipments'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Equipment ID',
                in: 'path',
                required: true,
            ),
            new OA\Parameter(
                name: 'min_date',
                description: 'Minimum date',
                example: '2020-01-01',
                in: 'path',
                required: false,
            ),
            new OA\Parameter(
                name: 'max_date',
                description: 'Maximum Date',
                in: 'path',
                example: '2025-01-01',
                required: false,
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK'
            ),
            new OA\Response(
                response: 404,
                description: 'Equipment non trouvé'
            )
        ]
    )]
    public function average(string $id, AverageRequest $request)
    {
        try {
            $min_date = $request->input('min_date') ?? '1111-01-01';
            $max_date = $request->input('max_date') ?? now()->toDateString();

            //https://laracasts.com/discuss/channels/laravel/working-with-laravel-dates-for-dummies
            if (Carbon::parse($min_date)->isAfter(Carbon::parse($max_date))) {
                return response()->json(['error' => "La date minimum ne peut pas être suppérieur à la date maximum"])->setStatusCode(INVALID_CONTENT);
            }

            Equipment::findOrFail($id); //pour lever une erreur

            $avg =  Rental::where('equipment_id', $id)
                ->where('start_date', '>=', $min_date)
                ->where('end_date', '<=', $max_date)
                ->avg('total_price');

            return response()->json(['average' => $avg])->setStatusCode(OK);
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
