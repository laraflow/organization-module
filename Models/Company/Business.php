<?php

namespace Modules\Organization\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model
{
    use HasFactory;

    protected $fillable = ["name","display_name","description","website"];
    
    protected static function newFactory()
    {
        return \Modules\Organization\Database\Factories\BusinessFactory::new();
    }
}