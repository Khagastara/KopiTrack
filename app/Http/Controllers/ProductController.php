<?php

namespace App\Http\Controllers;

use App\Models\DistributionProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index($id)
    {
        $productIndex = DistributionProduct::all();
        $productShow = DistributionProduct::findOrFail($id);

        return view('admin.product.index', compact('productIndex', 'productShow'));
    }

    public function create(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:100',
            'product_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'product_quantity' => 'required|integer',
            'product_price' => 'required|integer',
            'product_description' => 'nullable|string'
        ]);

        if ($validator->fails())
        {
            if ($validator->errors()->has('product_quantity')){
                return redirect()->back()->with('error', 'Jumlah Produk Harus Berisi Angka!')->withInput();
            }
            elseif ($validator->errors()->has('product_quantity')){
                return redirect()->back()->with('error', 'Harga Produk Harus Berisi Angka!')->withInput();
            }

            return redirect()->back()->with('error', 'Semua Data Harus Diisi!')->withInput();
        }

        if ($request->hasFile('product_image'))
        {
            $productImage = $request->file('gambar_stok');
            $productName = time() . '.' . $productImage->getClientOriginalExtension();
            $productImage->move(public_path('images/product'), $productImage);
            $productPath = 'images/product' . $productName;
        }

        DistributionProduct::create([
            'product_name' => $request->product_name,
            'product_image' => $productPath ?? '',
            'product_quantity' => $request->product_quantity,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description
        ]);

        return redirect()->route('admin.product.index')->with('success');
    }

    public function update(Request $request, $id)
    {
        $product = DistributionProduct::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:100',
            'product_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'product_quantity' => 'required|integer',
            'product_price' => 'required|integer',
            'product_description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('product_quantity')) {
                return redirect()->back()->with('error', 'Jumlah Product Hars Berisi Angka')->withInput();
            }
            if ($validator->errors()->has('product_price')) {
                return redirect()->back()->with('error', 'Harga Product Hars Berisi Angka')->withInput();
            }

            return redirect()->back()->with('error', 'Data Tidak Boleh Ada yang Kosong')->withInput();
        }

        if ($request->hasFile('product_description'))
        {
            if ($product->product_image && file_exists(public_path($product->product_image)))
            {
                unlink(public_path($product->product_image));
            }

            $productImage = $request->file('gambar_stok');
            $productName = time() . '.' . $productImage->getClientOriginalExtension();
            $productImage->move(public_path('images/product'), $productImage);
            $productPath = 'images/product' . $productName;

            $product->update([
                'product_name' => $request->product_name,
                'product_image' => $productPath,
                'product_quantity' => $request->product_quantity,
                'product_price' => $request->product_price,
                'product_description' => $request->product_description
            ]);
        }
        else
        {
            $product->update([
                'product_name' => $request->product_name,
                'product_quantity' => $request->product_quantity,
                'product_price' => $request->product_price,
                'product_description' => $request->product_description
            ]);
        }

        return redirect()->route('admin.product.index', $product->id)->with('success');
    }

    public function merchantIndex($id)
    {
        $productIndex = DistributionProduct::all();
        $productShow = DistributionProduct::findOrFail($id);

        return view('merchant.product.index', compact('productIndex', 'productShow'));
    }
}
