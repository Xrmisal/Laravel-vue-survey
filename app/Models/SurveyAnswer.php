<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{

    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $fillable = [
        'survey_id',
        'start_date',
        'end_date'
    ];

    public function survey() {
        return $this->belongsTo(Survey::class);
    }
}
