<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;


class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function add_product(Request $request)

    {
        $request->validate([
            'title'=>'required|min:6|unique:products',
            'description'=>'required|max:100',
            'image'=>'required|image|mimes:jpg,png,jpeg,svg|max:2048'

        ]

        ,[
            'title.required'=>'The title field is required ',
            'description.required'=>'The description field is required',
            'image.required'=>'The image field is required',
                'title=>min'=>'title field must be at least 6 characters',
                'image.mimes'=>'image field must be a file of type: jpg, png, jpeg, svg.'

        ]

        );

        $data = new Products;
        $data->title = $request->title;
        $data->description = $request->description;

        $image = $request->image;
        if($image){
            $imagename = time() . '.' . $image->getClientOriginalExtension(); // Fix typo here

            $request->image->move('products', $imagename);
            $data->image = $imagename;

        }

        $data->save();

        return redirect()->back();
    }

    public function show_product()
    {
        $data=Products::all();
        return view('product',compact('data'));
    }
    public function delete_product($id){
        $data = Products::find($id);
        $data->delete();
        return redirect()->back();

    }
    public function update_product($id){

        $product = Products::find($id);
        return view('product_update',compact('product'));

    }
    public function edit_product(Request $request,$id){
        $data = Products::find($id);
        $data->title=$request->title;
        $data->description = $request->description;
        $image = $request->image;
        if($image){
            $imagename = time() . '.' . $image->getClientOriginalExtension(); // Fix typo here
            $request->image->move('products', $imagename);
            $data->image =$imagename;
        }




        $data->save();
        return redirect()->back();
    }
}
