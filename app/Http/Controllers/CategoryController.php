<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    static function list()
    {
        return Category::with(['movies'])->get();
    }

    static function create($data)
    {
        return Category::create($data) ? true : false;
    }

    static function update($id, $data)
    {
        return Category::find($id)->update($data) ? true : false;
    }

    static function delete($id)
    {
        return Category::find($id)->delete() ? true : false;
    }

    static function find($id)
    {
        return Category::with(['movies'])->find($id);
    }
}
