<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    protected $fillable = [
        'category_name',
        'performance_order',
        'active_category',
    ];
    
    protected function casts(): array
    {
        return [
            'performance_order' => 'integer',
            'active_category' => 'boolean',
        ];
    }
}