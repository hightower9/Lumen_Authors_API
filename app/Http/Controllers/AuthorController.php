<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use App\Author;

class AuthorController extends Controller
{
    use ApiResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Return full list of Authors
     * @return Illuminate\Http\Response
     */
    public function index(){
        $authors = Author::all();

        return $this->successResponse($authors);
    }

    /**
     * Create a new Author
     * @return Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = [
            'name' => 'required|max:255',
            'gender' => 'required|max:255|in:male,female',
            'country' => 'required|max:255',
        ];

        $this->validate($request, $rules);

        $author = Author::create($request->all());

        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    /**
     * Obtain and show one Author
     * @return Illuminate\Http\Response
     */
    public function show($author){
        $author = Author::findOrFail($author);

        return $this->successResponse($author);
    }

    /**
     * Update an existing Author
     * @return Illuminate\Http\Response
     */
    public function update(Request $request, $author){
        $rules = [
            'name' => 'max:255',
            'gender' => 'max:255|in:male,female',
            'country' => 'max:255',
        ];

        $this->validate($request, $rules);

        $author = Author::findOrFail($author);
        $author->fill($request->all());

        if($author->isClean()){ //checks if nothing is changed
            return $this->errorResponse('Atleast one value must change', Response::HTTP_UNPROCESSABLE_ENTITY); //422
        }
        $author->save();
        return $this->successResponse($author);
    }

    /**
     * Remove an existing Author
     * @return Illuminate\Http\Response
     */
    public function destroy($author){
        $author = Author::findOrFail($author);
        $author->delete();
        return $this->successResponse($author);
    }
}
