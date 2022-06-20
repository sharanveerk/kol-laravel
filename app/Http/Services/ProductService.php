<?php
namespace  App\Http\Services;
use App\Models\User;
use App\Models\Category;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use Mail;
use JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class ProductService{


    public function storeCategory($request,$imageUrl){

        $saveData = new Category();
        $saveData->name = $request['name'];
        $saveData->description = $request['description'];
        $saveData->parent_id = $request['parent_id'];
        $saveData->image = $imageUrl;
        $checkSave = $saveData->save();
        if($checkSave){
            return true;
        }else{
            return false;
        }

    }
     public function getAllCategory(){
        $records = Category::orderBy('id','desc')->with('getCategoryParent')->get()->toArray();
        // dd($records['getCategoryParent']->id);
        $filterData = [];
        $i = 0;
        foreach($records as $record){
            $filterData[$i]['name'] = $record['name'];
            $filterData[$i]['description'] = $record['description']; 
            if(isset($record['get_category_parent']['name'])) {
                $filterData[$i]['parent_id'] = $record['get_category_parent']['id']; 
                $filterData[$i]['parent_name'] = $record['get_category_parent']['name']; 
                
            } else {
                $filterData[$i]['parent_id'] = ''; 
                $filterData[$i]['parent_name'] = ''; 
            
            }
            $filterData[$i]['image'] = $record['image'];
            $filterData[$i]['status'] = $record['status'];
            $i++;


        }


        return $filterData;
    }
    public function getCategoryById($id){
        $records = Category::where('id',$id)->with('getCategoryParent')->get()->toArray();
        $filterData = [];
        $i = 0;
        foreach($records as $record){
            $filterData[$i]['name'] = $record['name'];
            $filterData[$i]['description'] = $record['description']; 
            if(isset($record['get_category_parent']['name'])) {
                $filterData[$i]['parent_id'] = $record['get_category_parent']['id']; 
                $filterData[$i]['parent_name'] = $record['get_category_parent']['name']; 
                
            } else {
                $filterData[$i]['parent_id'] = ''; 
                $filterData[$i]['parent_name'] = ''; 
              
            }
            $filterData[$i]['image'] = $record['image'];
            $filterData[$i]['status'] = $record['status'];
            $i++;


        }
        return $filterData;

      
     }
     public function updateCategory($request,$imageUrl){
        $updateArrData = [];
        if($imageUrl==null){
            $updateArrData = ["name"=>$request['name'],"description"=>$request['description'],"parent_id"=>$request['parent_id']];
        }else{
            $updateArrData = ["name"=>$request['name'],"description"=>$request['description'],"parent_id"=>$request['parent_id'],"image"=>$imageUrl];
            
        }
        $response= Category::where('id',$request['id'])->update($updateArrData);
        if($response){
            return true;
        }
        else{
            return false;
        }

    }
    

}