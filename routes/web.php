<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $records = Category::orderBy('id','desc')->with('getCategoryParent')->get();
    // dd($records);
    $filterData = [];
    $i = 0;
    foreach($records as $record){
        $filterData[$i]['name'] = $record['name'];
        $filterData[$i]['description'] = $record['description'];
        $filterData[$i]['parent_name'] = $record['getCategoryParent'];
        $filterData[$i]['image'] = $record['image'];
        $filterData[$i]['status'] = $record['status'];
        $i++;

    }
    dd($filterData);
    return $allCategoriesData ;;
});


Auth::routes();
Route::get('/home', function () {
    return view('auth.login');
});

Route::group(['middleware' =>  'isAdmin'], function() {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
  

});
Route::get('/check', [AuthController::class,'checkLogin'])->middleware('role_id');


Route::get('phone-auth', [\App\Http\Controllers\PhoneAuthController::class, 'index']);
Route::get('admin/routes', [DashboardController::class,'index'])->middleware('admin');

