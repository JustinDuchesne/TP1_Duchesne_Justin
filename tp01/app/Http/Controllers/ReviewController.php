<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Exception;
use Illuminate\Database\QueryException;

class ReviewController extends Controller
{

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Review::destroy($id); 
            return response()->noContent()->setStatusCode(OK);
        } catch (QueryException $ex) {
            abort(INVALID_CONTENT, 'Cannot be deleted in database');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
