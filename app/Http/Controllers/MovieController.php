<?php

namespace App\Http\Controllers;

use App\Models\Movie;

class MovieController extends Controller
{
    /**
     * 
     */
    static function list()
    {
        return Movie::with(['category'])->get();
    }



    /**
     * 
     */
    static function create($data)
    {
        return Movie::create($data);
    }


    /**
     * 
     */
    static function update($id, $data)
    {
        return Movie::find($id)->update($data) ? true : false;
    }




    /**
     * 
     */
    static function delete($id)
    {
        $movie = Movie::find($id);
        if ($movie && $movie->delete()) {
            if (is_file(public_path('images/movies/' . $movie->image)))
                unlink(public_path('images/movies/' . $movie->image));
            return true;
        }
        return false;
    }



    /**
     * 
     */
    static function find($id)
    {
        return Movie::with(['category'])->find($id);
    }


    /**
     * 
     */
    static function listByCategory($id)
    {
        return Movie::with(['category'])->where(['category_id' => $id])->get();
    }
}
