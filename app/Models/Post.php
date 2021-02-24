<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Post extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        "title",            /* TABLE Column*/
        "body",             /* TABLE Column*/
        "tags",             /* TABLE Column*/
        "img",              /* TABLE Column*/
        "user_id",          /* TABLE Column For Forign Key*/
    ];

    // one to many relationship
    public function User(){
        return $this->belongsTo(User::class);
    }
}



