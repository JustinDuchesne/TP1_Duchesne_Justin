<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','daily_price','category_id'];

    public function category() : BelongsTo  {
        return $this->BelongsTo ('\App\Models\Category');
    }
    public function sport() : BelongsToMany  { 
        return $this->BelongsToMany ('\App\Models\Sport');
    }

    public function rental() : HasMany  {
        return $this->HasMany ('\App\Models\Rental');
    }
}