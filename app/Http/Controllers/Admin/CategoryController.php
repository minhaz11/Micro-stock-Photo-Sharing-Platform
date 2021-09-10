<?php

namespace App\Http\Controllers\Admin;

use File;
use ZipArchive;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use phpDocumentor\Reflection\Types\True_;

class CategoryController extends Controller
{
    public function all()
    {
       $categories =  Category::latest()->paginate(15);
       $page_title = 'All categories';
       $empty_message = 'No data';
       return view('admin.category.all',compact('categories','page_title','empty_message'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $category = new Category();
        $category->name = $request->name;
        isset($request->status) ? $category->status = 1: $category->status = 0;
        if($request->image){
            try {
                $path = 'assets/images/category';
                $category->image = uploadImage($request->image,$path,'205x251',null,'205x251');
            }
            catch(\Exception $exp){
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }


        }

         $category->save();
         $notify[]=['success','Category created successfully'];
         return back()->withNotify($notify);
    }
    public function update(Request $request,$id)
    {
        $request->validate([
            'name'=>'required',
            'image'=>'image|max:5048|mimes:jpg,jpeg,png|'
        ]);

        $category =  Category::findOrFail($id);
        $category->name = $request->name;
        isset($request->status) ? $category->status = 1: $category->status = 0;
        if($request->image){
            try {
                $old = $category->image?:null;
                $path = 'assets/images/category';
                $category->image = uploadImage($request->image,$path,'205x251', $old);
            }
            catch(\Exception $exp){
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $category->save();
         $notify[]=['success','Category updated successfully'];
         return back()->withNotify($notify);
    }

    public function search(Request $request)
       {
           $categories = Category::where('name','like',"%$request->search")->latest()->paginate(15);
           $page_title='Result of'. ' '.$request->search;
           $empty_message = 'No result found';
           return view('admin.category.search',compact('categories','page_title','empty_message'));

       }






}
