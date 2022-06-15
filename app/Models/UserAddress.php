<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $table = 'user_address';
    protected $fillable = ['name','phone','city','landmark','user_id','city','postal_code','contry','address_type','state','location_type','is_primary'];

}
