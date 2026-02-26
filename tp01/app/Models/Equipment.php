<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','daily_price','category'];

    public function category() : HasMany  {
        return $this->HasMany ('\App\Models\Category');
    }
    public function equipmentsport() : HasMany  { 
        return $this->HasMany ('\App\Models\Category'); //a regarder comment faire 
    }

    public function rental() : BelongsTo  {
        return $this->BelongsTo ('\App\Models\Rental');
    }
}