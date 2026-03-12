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

    public function average(string $id, AverageRequest $request)
    {
        try{
            $min_date = $request->input('min_date') ?? '1111-01-01'; 
            $max_date = $request->input('max_date') ?? now()->toDateString();

            //https://laracasts.com/discuss/channels/laravel/working-with-laravel-dates-for-dummies
            if(Carbon::parse($min_date)->isAfter(Carbon::parse($max_date))){
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
