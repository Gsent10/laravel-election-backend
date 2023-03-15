<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingUnitResult extends Model
{
    use HasFactory;

    protected $table = 'announced_pu_results';

    protected $fillable = [
        'polling_unit_uniqueid',
        'party_abbreviation',
        'party_score'
    ];
}
