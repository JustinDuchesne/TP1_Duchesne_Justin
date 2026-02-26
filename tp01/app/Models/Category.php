<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function equipment() : BelongsTo  { //je le fait apres chu brain dead rn
        return $this->BelongsTo ('\App\Models\Equipment');
    }
}
