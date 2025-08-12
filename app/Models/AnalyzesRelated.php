<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyzesRelated extends Model
{
    //
    protected $fillable = [
        'analyze_order_id',
        'analyze_id',
        'price',
    ];





    public function analyzesRelated_analyze()
    {
        return $this->belongsTo(Analyze::class, 'analyze_id');
    }

    public function analyzesRelated_OrderAnalyze()
    {
        return $this->belongsTo(AnalyzeOrder::class, 'analyze_order_id');
    }



}
