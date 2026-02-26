<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Rental extends Model
{
    use HasFactory;
    protected $fillable = ['start_date','end_date','total_price','user_id','equipment_id'];

    public function user() : HasMany  {
        return $this->HasMany ('\App\Models\User');
    }
    public function equipment() : HasMany  {
        return $this->HasMany ('\App\Models\Equipment');
    }
    public function review() : BelongsTo  {
        return $this->BelongsTo ('\App\Models\Review');
    }
}
