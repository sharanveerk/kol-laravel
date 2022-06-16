<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['id','name','parent_id','image','status','parent_id','description'];

    public static function makeImageUrl($file)
    {
        $uploadFolder = 'category';
        $name = preg_replace("/[^a-z0-9\._]+/", "-", strtolower(time() . rand(1, 9999) . '.' . $file->getClientOriginalName()));
        if ($file->move(public_path() . '/uploads/'.$uploadFolder, str_replace(" ", "", $name))) {
            return url('/') . '/uploads/'.$uploadFolder.'/' . $name;
        }
    }
    public function getCategoryParent(){
        return $this->belongsTo(Category::class,'parent_id','id');
        
    }
}
