<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['rating','comment','user_id','rental_id'];

    public function rental() : HasMany  {
        return $this->HasMany ('\App\Models\Category');
    }
    public function user() : HasMany  {
        return $this->HasMany ('\App\Models\User');
    }
}
