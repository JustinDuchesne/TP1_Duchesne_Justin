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
use Illuminate\Http\Request;

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
            $allReviews = Review::join( 'rentals', 'reviews.rental_id' ,'=', 'rentals.id') //'reviews', 'rentals.id', '=', 'reviews.rental_id'
                ->where('rentals.equipment_id', $id)
                ->selectRaw('COALESCE(COUNT(reviews.id),0) AS Nb_Reviews, SUM(reviews.rating) AS Avg_rating')
                ->first(); //first? a revoir

            //dd($allReviews);
            return ['popularity' => $allReviews->count() * 0.6 + $allReviews->sum('rating') * 0.4]; //'popularity' => $allReviews->count() * 0.6 + $allReviews->sum('rating') * 0.4
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    public function average(string $id, Request $request)
    {
        try{
            $request->min_date; //doit lever érreur si min est plus grande que end
            $request->max_date; //le format doit etre valide
                                //paginer 20

            if($request->min_date > $request->max_date){
                return ["La date minimum ne peut pas être suppérieur à la date maximum"];
            }

            //$allEquipment = Rental::join('equipment', 'rentals.equipment_id', '=', 'equipment.id')
            //    ->where('rentals.start_date', '>=', $request->min_date)
            //    ->where('rentals.end_date', '<=', $request->max_date)
            //    ->where('equipment.id', $id)
            //    ->selectRaw('AVG(rentals.total_price) AS average_price')
            //    ->first();


            $allEquipment = Rental::join('equipment', 'rentals.equipment_id', '=', 'equipment.id')
                ->whereDate('rentals.start_date', '>', $request->min_date)
                ->whereDate('rentals.end_date', '<', $request->max_date)
                ->where('rentals.equipment_id', '=',$id)
                ->avg('total_price');
                //->selectRaw('AVG(rentals.total_price) AS average_price')
                //->first();

            //dd($allEquipment);
            return $allEquipment;
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
