<?php

namespace App\Http\Controllers;

use App\Http\Requests\AverageRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function popularity(string $id)
    {
        try{
            $allReviews = Review::join( 'rentals', 'reviews.rental_id' ,'=', 'rentals.id')
                ->where('rentals.equipment_id', $id)
                ->selectRaw('COALESCE(COUNT(reviews.id),0) AS Nb_Reviews, SUM(reviews.rating) AS Avg_rating')
                ->first();

            //dd($allReviews);
            return response()->json(['popularity' => $allReviews->count() * 0.6 + $allReviews->sum('rating') * 0.4])->setStatusCode(OK); //['popularity' => $allReviews->count() * 0.6 + $allReviews->sum('rating') * 0.4]
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    public function average(string $id, AverageRequest $request)
    {
        try{

            $min_date = $request->input('min_date') ?? '1111-01-01'; 
            $max_date = $request->input('max_date') ?? now()->toDateString();

                                //paginer 20

            if($request->min_date > $request->max_date){
                return response()->json(["La date minimum ne peut pas être suppérieur à la date maximum"])->setStatusCode(INVALID_CONTENT);
            }

            $avg =  Rental::where('equipment_id', $id)
                ->where('start_date', '>=', $min_date)
                ->where('end_date', '<=', $max_date)
                ->avg('total_price');

            return response()->json($avg)->setStatusCode(OK);
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
        
}
