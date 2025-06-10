<?php

namespace App\Http\Controllers;

use App\Models\DistributionProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index($id = null)
    {
        $productIndex = DistributionProduct::all();

        if ($id) {
            $productShow = DistributionProduct::findOrFail($id);
        } else {
            $productShow = $productIndex->first();
        }

        return view('admin.product.index', compact('productIndex', 'productShow'));
    }

    public function create()
    {
        return view('admin.product.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:100',
            'product_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'product_quantity' => 'required|integer',
            'product_price' => 'required|integer',
            'product_description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('product_quantity')) {
                return redirect()->back()->with('error', 'Jumlah Produk Harus Berisi Angka!')->withInput();
            } elseif ($validator->errors()->has('product_price')) {
                return redirect()->back()->with('error', 'Harga Produk Harus Berisi Angka!')->withInput();
            }

            return redirect()->back()->with('error', 'Semua Data Harus Diisi!')->withInput();
        }

        $productPath = null;

        if ($request->hasFile('product_image')) {
            $productPath = $request->file('product_image')->store('product-images', 'public');
        }

        DistributionProduct::create([
            'product_name' => $request->product_name,
            'product_image' => $productPath,
            'product_quantity' => $request->product_quantity,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description
        ]);

        return redirect()->route('admin.product.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = DistributionProduct::findOrFail($id);
        return view('admin.product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = DistributionProduct::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:100',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'product_quantity' => 'required|integer',
            'product_price' => 'required|integer',
            'product_description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('product_quantity')) {
                return redirect()->back()->with('error', 'Jumlah Produk Harus Berisi Angka')->withInput();
            }
            if ($validator->errors()->has('product_price')) {
                return redirect()->back()->with('error', 'Harga Produk Harus Berisi Angka')->withInput();
            }

            return redirect()->back()->with('error', 'Data Tidak Boleh Ada yang Kosong')->withInput();
        }

        $data = [
            'product_name' => $request->product_name,
            'product_quantity' => $request->product_quantity,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description
        ];

        if ($request->hasFile('product_image')) {
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }
            $data['product_image'] = $request->file('product_image')->store('product-images', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.product.index', $product->id)->with('success', 'Produk berhasil diperbarui');
    }

    public function merchantIndex($id = null)
    {
        $productIndex = DistributionProduct::all();

        if ($id) {
            $productShow = DistributionProduct::findOrFail($id);
        } else {
            $productShow = $productIndex->first();
        }

        return view('merchant.product.index', compact('productIndex', 'productShow'));
    }
}
