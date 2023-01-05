<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class Movie extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['img'];

    /**
     * Determine if the user is an administrator.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function img(): Attribute
    {        
        return new Attribute(
            get: function () {
                return [
                    "url" => is_file(public_path("images/movies/$this->image")) ? asset("images/movies/$this->image") : asset("images/default.jpg"),
                    "name" => $this->image,
                    "size" => is_file(public_path("images/movies/$this->image")) ? public_path("images/movies/$this->image") : public_path("images/default.jpg"),
                ];
            },
        );
    }
}
