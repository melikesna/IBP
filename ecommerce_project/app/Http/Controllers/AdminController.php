<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;



class AdminController extends Controller
{
    public function index()
    {
        $data = Category::all();

        return view('admin.category', compact('data'));
    }

    public function add_category(Request $request)
    {
        $category = new Category;

        $category->category_name = $request->category;

        $category->save();

        toastr()->success('Category Added Successfully', ['timeOut' => 5000]);

        return redirect()->back();
    }

    public function delete_category($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            return redirect()->back();
        } else {
            // Handle the case where no category is found (e.g., flash an error message)
            return redirect()->back()->with('error', 'Category not found!');
        }
    }

    public function edit_category($id)
    {
        $data= Category::find($id);
        return view('admin.edit_category', compact('data'));
    }

    public function update_category(Request $request, $id)
    {
        $data = Category::find($id);
        $data->category_name=$request->category;
        $data->save();
        toastr()->timeOut(10000)->closeButton()->addSuccess('Category Updated Successfully');
        return redirect('/view_category');
    }
    public function add_product(Request $request)
    {
        $category = Category::all();
        return view('admin.add_product',compact('category'));
    }
    public function upload_product(Request $request)
    {
        $data= new Product;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->price = $request->price;
        $data->quantity = $request->qty;
        $data->category = $request->category;
        $image = $request->image;

        if($image)
        {
            $imagename = time().'.'.$image->getClientOriginalExtension();
            $request->image->move('product',$imagename);
            $data->image = $imagename;
        }

        $data->save();
        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Added Successfully');

        return redirect()->back();
    }

    public function view_product()
    {
        $product = Product::all();
        return view('admin.view_product' , compact('product'));
    }

    public function product_search(Request $request)
    {
        $search = $request->search;
        $product = Product::where('title','like','%'.$search.'%')->orWhere('category','like','%'.$search.'%')->get();
        return view('admin.view_product', compact('product'));

    }

}
