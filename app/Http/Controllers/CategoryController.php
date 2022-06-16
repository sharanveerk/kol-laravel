<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use App\Http\Services\UserService;
use App\Http\Services\ProductService;

class CategoryController extends Controller
{

    private $productService;

    public function __construct(ProductService $productService){
        $this->productService = $productService;
    }
  
    public function getCategory(){
        $allCategories = $this->productService->getAllCategory();
        $msg = __('api_string.categories_list');
        $successMsg = __('api_string.success_message');
        return response()->json(['status'=>true,"statusCode"=>200,"data"=>$allCategories]);
   }

    public function store(Request $request)
    {
        try {
            $valdiation = Validator::make($request->all(),[
                'name' => 'required|unique:categories',
                'description' => 'required',
                'image' => 'required|mimes:png,jpeg,jpg',
            ]);
            if($valdiation->fails()) {
                $msg = __("api_string.invalid_fields");
                return response()->json(["status"=>false,'statusCode'=>422,"message"=>$msg]);
            }
            $imageUrl = Category::makeImageUrl($request['image']);
            if($imageUrl)
            {
                $saveResponse = $this->productService->storeCategory($request,$imageUrl);
                if($saveResponse==true){
                    $msg=__("api_string.category_added");
                    return response()->json(["status"=>true,'statusCode'=>201,"message"=>$msg]);
                }else{
                    $msg= __("api_string.error");
                    return response()->json(["statusCode"=>403,"status"=>false,"message"=>$msg]);
                }

            }
            
        } catch (\Throwable $th) {
            return response()->json(["statusCode"=>500,"status"=>false,"message"=>$th->getMessage()]);
        }
        
    }

    public function viewCategory(Request $request)
    {
        try {
            $valdiation = Validator::make($request->all(),[
                'id' => 'required|integer',
            
            ]);

            if($valdiation->fails()) {
                $msg = __("api_string.invalid_fields");
                return response()->json(["status"=>false,'statusCode'=>422,"message"=>$msg]);
            }

            $checkCategory = $this->productService->getCategoryById($request['id']);
            if($checkCategory)
            {
                return response()->json(["status"=>true,"statusCode"=>200,"Details"=>$checkCategory]);
            }
            else{
                $msg= __("api_string.invalid_parent_id");
                return response()->json(["statusCode"=>401,"status"=>false,"message"=>$msg]);

            }
        } catch (\Throwable $th) {
            return response()->json(["statusCode"=>500,"status"=>false,"message"=>$th->getMessage()]);
        }
    }

    public function updateCateory(Request $request)
    {
        try {
                $valdiation = Validator::make($request->all(),[
                    'name' => 'required|string',
                    'id' => 'required',
                    'description' => 'string|nullable',
                ]);
                if($valdiation->fails()) {
                    $msg = __("api_string.invalid_fields");
                    return response()->json(["message"=>$msg, "statusCode"=>422]);
                }
                if($request['image']){
                    $imageUrl = Category::makeImageUrl($request['image']);
                    if($imageUrl){
                        $checkCategory = $this->productService->getCategoryById($request['id']);
                        if($checkCategory){
                            $checkCategory = $this->productService->updateCategory($request,$imageUrl);
                            $msg=__("api_string.category_updated");
                            return response()->json(["status"=>true,'statusCode'=>202,"message"=>$msg]);
                        }else{
                            $msg= __("api_string.invalid_parent_id");
                            return response()->json(["statusCode"=>401,"status"=>false,"message"=>$msg]);
                        }
                    }
                    else{
                        $msg= __("api_string.error");
                        return response()->json(["statusCode"=>403,"status"=>false,"message"=>$msg]);
                    }
                }else{
                    $checkCategory = $this->productService->getCategory($request['id']);
                    if($checkCategory){
                        $checkCategory = $this->productService->updateCategory($request,$imageUrl=null);
                        $msg=__("api_string.category_updated");
                        return response()->json(["status"=>true,'statusCode'=>202,"message"=>$msg]);
                    }else{
                        $msg= __("api_string.invalid_parent_id");
                        return response()->json(["statusCode"=>401,"status"=>false,"message"=>$msg]);
                    }
                }
            } catch (\Throwable $th) {
            return response()->json(["statusCode"=>500,"status"=>false,"message"=>$th->getMessage()]);
        }
    }

public function CategoryStatus(Request $request){
    try {
        $valdiation = Validator::make($request->all(),[
            'id' => 'required',
        ]);
        if($valdiation->fails()) {
            $msg = __("api_string.invalid_fields");
            return response()->json(["message"=>$msg, "statusCode"=>422]);
        }
        $checkCategory = $this->productService->updateCategoryStatus($request['id']);
        //code...
    } catch (\Throwable $th) {
        return response()->json(["statusCode"=>500,"status"=>false,"message"=>$th->getMessage()]);
    }
   
 
}

}
