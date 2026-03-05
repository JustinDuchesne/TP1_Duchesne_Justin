<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Review;
use Exception;
use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        try {
            return EquipmentResource::collection(Equipment::paginate(20))->response()->setStatusCode(OK);
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipmentRequest $request)
    {
        try {
            $equipment = Equipment::create($request->validated());
            return (new EquipmentResource($equipment))->response()->setStatusCode(OK_CREATED);
        } catch (QueryException $ex) {
            abort(INVALID_CONTENT, 'Cannot be created in database');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    /**
     * Display the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function update(EquipmentRequest $request, string $id)
    {
        try{
            //https://laracasts.com/discuss/channels/laravel/how-to-do-update-controller-routes-model-and-other-things-from-laravel-54-from-scratch-tutorial
            $equipment = Equipment::findOrFail($id);
            $equipment->update($request->all());
            return response()->noContent()->setStatusCode(OK);
        }catch (QueryException $ex) {
            abort(INVALID_CONTENT, 'Cannot be edited in database');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try { //il y a pas de réponse...
            Equipment::destroy($id); 
            return response()->noContent()->setStatusCode(OK);
        } catch (QueryException $ex) {
            abort(INVALID_CONTENT, 'Cannot be deleted in database');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    public function popularity(string $id)
    {
        try{
            //$equipment = Equipment::findOrFail($id);
            //$allRentals = Rental::where("equipment_id", $id); //faire join

            //dd($allRentals);
            //$allReviews = Review::join( 'rentals', 'reviews.rental_id' ,'=', ' rentals.id') //'reviews', 'rentals.id', '=', 'reviews.rental_id'
            //    ->where('rentals.equipment_id', $id)
            //    ->select('COALESCE(COUNT(reviews.id),0) AS Nb_Reviews', 'SUM(reviews.rating) AS Avg_rating')
            //    ->get();

            $allReviews = Review::join( 'rentals', 'reviews.rental_id' ,'=', 'rentals.id') //'reviews', 'rentals.id', '=', 'reviews.rental_id'
                ->where('rentals.equipment_id', $id)
                ->selectRaw('COALESCE(COUNT(reviews.id),0) AS Nb_Reviews, SUM(reviews.rating) AS Avg_rating')
                ->first();

            //dd($allReviews);
            return ['popularity' => $allReviews->count() * 0.6 + $allReviews->sum('rating') * 0.4]; //'popularity' => $allReviews->count() * 0.6 + $allReviews->sum('rating') * 0.4
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
