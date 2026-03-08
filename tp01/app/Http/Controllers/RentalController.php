<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function popularity(string $id, request $request)
    {
        try{
            $request;


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
