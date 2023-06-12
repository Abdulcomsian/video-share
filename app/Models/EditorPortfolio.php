<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditorPortfolio extends Model
{
    use HasFactory;

    protected $table = "editor_portfolio";
    protected $primaryKey = "id";
    protected $fillable = [
        "editor_id",
        "link"
    ];
}
