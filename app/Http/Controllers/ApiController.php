<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Traits\Utils;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\RequiredIf;

class ApiController extends Controller
{

    use Utils;

    const CATEGORY_NOT_FOUND = 1;
    const CATEGORY_CANNOT_CREATE = 2;
    const CATEGORY_CANNOT_UPDATE = 3;
    const CATEGORY_CANNOT_DELETE = 4;

    const MOVIE_NOT_FOUND = 5;
    const MOVIE_CANNOT_CREATE = 6;
    const MOVIE_CANNOT_UPDATE = 7;
    const MOVIE_CANNOT_DELETE = 8;

    /**
     * 
     */
    public function listCategories()
    {
        return response()->json($this->parseResponse(CategoryController::list()));
    }
    /**
     * 
     */
    public function createCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,title|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $this->parseResponse(
                    [],
                    $validator->errors()->getMessages(),
                    400,
                    true
                ),
                400
            );
        }

        return response()->json(
            $this->parseResponse(CategoryController::create([
                'title' => $request->name
            ])),
            200
        );
    }
    /**
     * 
     */
    public function updateCategory(Request $request)
    {
        // return request()->name;

        $validator = Validator::make(
            [
                request()->id,
                request()->name,
            ],
            [
                0 => 'required|exists:categories,id',
                1 => 'required|unique:categories,title|max:30',
            ],
            [
                "0.required" => "The selected :attribute is required.",
                "1.required" => "The selected :attribute is required.",
                "0.exists" => "The selected :attribute is not exists.",
                "1.exists" => "The selected :attribute is not exists."
            ],
            ["0" => 'category', "1" => 'category_name']
        );

        if ($validator->fails()) {
            return response()->json($this->parseResponse(
                [],
                $validator->errors()->getMessages(),
                500,
                true
            ), 500);
        }

        return response()->json(
            $this->parseResponse(CategoryController::update(request()->id, [
                'title' => $request->name
            ]))
        );
    }
    /**
     * Done
     */
    public function deleteCategory($id)
    {
        $validator = $this->validateSingleQueryParam('categories', 'category');
        if (!$validator->error) {
            if (CategoryController::delete($id))
                $validator->response = true;
            else
                $validator->response = false;
        } else
            $validator->response = false;

        return response()->json($validator);
    }
    /**
     * Done
     */
    public function getCategory(Request $request, $id)
    {
        $validator = $this->validateSingleQueryParam('categories', 'category');
        if (!$validator->error) {
            $validator->response = CategoryController::find($id);
        } else
            $validator->response = false;

        return response()->json($validator);
    }




















    /**
     * Done
     */
    public function listMovies()
    {
        return response()->json($this->parseResponse(MovieController::list()), 200);
    }
    /**
     * Done
     */
    public function createMovie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:movies,title|max:30',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(
                $this->parseResponse(
                    [],
                    $validator->errors()->getMessages(),
                    400,
                    true
                ),
                400
            );
        }

        $image = $request->file('image');
        $filename = time() . '_' . $image->getClientOriginalName();
        $movie = MovieController::create([
            "title" => $request->title,
            "rate" => $request->rate ? $request->rate : 0,
            "image" => $filename,
            "description" => $request->description ?? "",
            "category_id" => $request->category_id,
        ]);

        if ($movie) {
            $image->move(public_path('images/movies'), $filename);
            return response()->json($this->parseResponse($movie), 200);
        } else
            return response()->json($this->parseResponse([], ["can't create movie"], 500, true), 500);
    }
    /**
     * 
     */
    public function updateMovie($id, Request $request)
    {
        // return $request->image;
        $validator = Validator::make($request->all(), [
            'title' => 'required_if:name,null',
            'category_id' => 'required_if:category_id,null|exists:categories,id',
            'image' => 'required_if:image,null|mimes:jpeg,jpg,png,gif|max:10000',
            'description' => 'required_if:description,null|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $this->parseResponse(
                    [],
                    $validator->errors()->getMessages(),
                    400,
                    true
                ),
                400
            );
        }

        /**
         * find movie
         */
        $movie = Movie::find($id);
        $data = collect($request->all())->except(['_method'])->filter(fn ($item) => $item);

        /**
         * chcek if there is image
         */
        if ($data->has('image')) {
            if (is_file(public_path("images/movies/" . $movie->image))) {
                unlink(public_path("images/movies/" . $movie->image));
            }
            $data['image'] = time() . '_' . $request->file('image')->getClientOriginalName();
        }


        /**
         * update movie
         */
        $isUpdated = $movie->update($data->toArray());
        if ($isUpdated) {
            if ($data->has('image')) {
                $request->file('image')->move(public_path('images/movies'), $data['image']);
            }
            return response()->json($this->parseResponse($movie), 200);
        } else
            return response()->json($this->parseResponse([], "movie can't created ...", 400, true), 400);
    }
    /**
     * Done
     */
    public function deleteMovie($id)
    {
        $validator = $this->validateSingleQueryParam('movies', 'movie');
        if (!$validator->error) {
            if (MovieController::delete($id))
                $validator->response = true;
            else
                $validator->response = false;
        } else
            $validator->response = false;

        return response()->json($validator);
    }
    /**
     * Done
     */
    public function getMovie($id)
    {
        $validator = $this->validateSingleQueryParam('movies', 'movie');
        if (!$validator->error) {
            $validator->response = MovieController::find($id);
        } else
            $validator->response = false;

        return response()->json($validator);
    }
    /**
     * Done
     */
    public function moviesByCategory($id)
    {
        $validator = $this->validateSingleQueryParam('categories', 'category');
        if (!$validator->error) {
            $validator->response = MovieController::listByCategory($id);
        } else
            $validator->response = false;

        return response()->json($validator);
    }
    /**
     * Done
     */
    public function filterMovies(Request $request)
    {
        $movies = Movie::with(['category']);
        if ($request->title) {
            $movies->where('title', 'LIKE', '%' . $request->title . '%');
        }
        if ($request->rate) {
            $movies->where('rate', 'LIKE', '%' . $request->rate . '%');
        }
        if ($request->category) {
            $movies->whereHas('category', function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->category . '%');
            });
        }
        return response()->json($this->parseResponse($movies->get()));
    }
}
