<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const OK = 200;
    const SERVER_ERROR = 500;
    const NOT_FOUND = 404;
    const INVALID_CONTENT = 422;   //faire enum plus tard
    const OK_CREATED = 201;

    public function index()
    {
        try {
            return CategoryResource::collection(Category::paginate(20))->response()->setStatusCode(self::OK);
        } catch (Exception $ex) {
            abort(self::SERVER_ERROR, 'Server error'); //Mettre en const
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());
            return (new CategoryResource($category))->response()->setStatusCode(self::OK_CREATED);
        } catch (QueryException $ex) {
            abort(self::INVALID_CONTENT, 'Cannot be created in database');
        } catch (Exception $ex) {
            abort(self::SERVER_ERROR, 'Server error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return (new CategoryResource(Category::findOrFail($id)))->response()->setStatusCode(self::OK);
        } catch (ModelNotFoundException $ex) {
            abort(self::NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(self::SERVER_ERROR, 'Server error');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        //
        try{
            $category = Category::findOrFail($id);
            //Category::updating
            //Je sais pas trop quoi faire...
        }catch (QueryException $ex) {
            abort(self::INVALID_CONTENT, 'Cannot be edited in database');
        } catch (Exception $ex) {
            abort(self::SERVER_ERROR, 'Server error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Category::destroy($id);
        } catch (QueryException $ex) {
            abort(self::INVALID_CONTENT, 'Cannot be deleted in database');
        } catch (Exception $ex) {
            abort(self::SERVER_ERROR, 'Server error');
        }
    }
}
