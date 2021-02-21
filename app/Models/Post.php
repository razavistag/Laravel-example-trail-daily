<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",            /* TABLE Column*/
        "body",             /* TABLE Column*/
        "tags",             /* TABLE Column*/
        "user_id",          /* TABLE Column For Forign Key*/
    ];

    // one to many relationship
    public function User(){
        return $this->belongsTo(User::class);
    }
}



