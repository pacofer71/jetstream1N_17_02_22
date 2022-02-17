<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable=['titulo', 'contenido', 'image', 'user_id', 'status'];

    //Post tiene una relacion 1:N com users (un post pertenece a un usuario)
    public function user(){
        return $this->belongsTo(User::class);
    }
}
