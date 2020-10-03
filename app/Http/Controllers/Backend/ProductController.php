<?php
/**
 * @Oshit Sutra Dhar
 */
namespace App\Http\Controllers\Backend;

use App\Model\Product;
use App\Http\Resources\Resource;
use App\Http\Controllers\Controller;
use App\Model\VariationProduct;
use App\Model\VaritionProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\Promise\all;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        if ($request->format() == 'html') {
            return view('layouts.backend_app');
        }
        $query  = Product::latest();
        if(!empty($request->field_name) && !empty($request->value)){
            $query->where($request->field_name,'like','%'.$request->value.'%');
        }

        $datas  = $query->paginate($request->pagination);
        return new Resource($datas);
    }


    public function create()
    {
        return view('layouts.backend_app');
    }


    public function store(Request $request)
    {

        $product    = $request->product;
        $variation  = $request->variation;
        $images     = $request->product_images;
        // Available alpha caracters
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $number = mt_rand(100, 999)
            . $characters[rand(0, strlen($characters) - 1)];
        $product_id = str_shuffle($number);
        $product['product_id'] = $product_id;

        if (!empty($product['image'])) {
            $product['image'] = Storage::putFile('upload/Product', $product['image']);
//            $product['image'] = $this->uploadImage('product', $product['image']);
        }
        $insert_product = Product::create($product);
        if ($insert_product){
            if (!empty($variation)){
                foreach ($variation as $variant){
                    $variant['product_id'] = $product_id;
                    if (!empty($images)) {
                        foreach ($images as $image) {
                            if (!empty($image)) {
                                $image['image'] = Storage::putFile('upload/product_extra', $image['images']);
                                $variant['extra_image'] = $image['image'];
                            }
                        }
                    }
                    VaritionProduct::create($variant);
                }
            }
        }
        // extra product images
//        if (!empty($images)) {
//            foreach ($images as $image) {
//                if (!empty($image)) {
//                    $pro_images['image'] = Storage::putFile('upload/product_extra', $image['images']);
////                    $pro_images['image'] = $this->uploadImage('product_extra', $image['images']);
////                    $pro->product_images()->create($pro_images);
//                }
//            }
//        }

//         variation product
//        if (!empty($variation)) {
//            $pro->variation()->createMany($variation);
//        }

        return response()->json(['message' => 'Create Successfully!'],200);
    }


    public function show(Request $request, Product $product)
    {
        if ($request->format() == 'html') {
            return view('layouts.backend_app');
        }
        return $product;
    }

    public function edit(Product $product)
    {
        return view('layouts.backend_app');
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return response()->json(['message' => 'Update Successfully!'],200);
    }

    public function destroy(Product $product)
    {
        if($product->delete()){
            return response()->json(['message' => 'Delete Successfully!'],200);
        }else{
            return response()->json(['message' => 'Delete Unsuccessfully!'],200);
        }
    }


    public function validateCheck($request)
    {
        return $request->validate([
            //ex: 'name' => 'required|email|nullable|date|string|min:0|max:191',
        ]);
    }
}
