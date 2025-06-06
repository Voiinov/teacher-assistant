<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeUnit\FunctionUnit;

class MinuteBook extends Model
{
    use HasFactory;

    protected $fillable = [
        "doc_date",
        "doc_type",
        "number",
        "title",
        "description",
    ];

    public function getDocList(int $paginate = 10, int $perPage = 10)
    {
        return DB::table("minute_books")
        ->leftJoin("variables","minute_books.doc_type","=","variables.id")
        ->select("minute_books.*","variables.name")
        ->get();
    }

    public function getDocTypes()
    {
        
    }
}
