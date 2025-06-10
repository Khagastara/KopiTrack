<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\DistributionProduct;
use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TransactionController extends Controller
{
    public function index($id)
    {
        $transactions = Transaction::with(['merchant', 'transactionDetail.distributionProduct'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(7);

        $transactions->getCollection()->transform(function ($transaction) {
            $detail = $transaction->transactionDetail->first();
            return [
                'id' => $transaction->id,
                'transaction_date' => $transaction->transaction_date instanceof \DateTime
                    ? $transaction->transaction_date->format('d-m-Y')
                    : date('d-m-Y', strtotime($transaction->transaction_date)),
                'merchant_name' => $transaction->merchant->merchant_name,
                'product_name' => $detail ? $detail->distributionProduct->product_name : 'N/A',
                'quantity' => $detail ? $detail->quantity : 0,
                'transaction_cost' => $detail ? $detail->sub_price : 0,
            ];
        });

        $transactionId = Transaction::with(['merchant', 'transactionDetail.distributionProduct'])
            ->orderBy('transaction_date', 'desc')
            ->findOrFail($id);

        $detailId = $transactionId->transactionDetail->first();

        $transactionIdDetail = [
            'id' => $transactionId->id,
            'transaction_date' => $transactionId->transaction_date instanceof \DateTime
                ? $transactionId->transaction_date->format('d-m-Y')
                : date('d-m-Y', strtotime($transactionId->transaction_date)),
            'merchant_name' => $transactionId->merchant->name,
            'product_name' => $detailId ? $detailId->distributionProduct->product_name : 'N/A',
            'quantity' => $detailId ? $detailId->quantity : 0,
            'transaction_cost' => $detailId ? $detailId->sub_price : 0,
        ];

        return view('admin.transaction.index', compact('transactions', 'transactionIdDetail'));
    }

    public function merchantIndex($id)
    {
        $merchant = Auth::user()->merchant;

        $transactions = Transaction::with(['merchant', 'transactionDetail.distributionProduct'])
            ->where('id_merchant', $merchant->id)
            ->orderBy('transaction_date', 'desc')
            ->paginate(7);

        $transactions->getCollection()->transform(function ($transaction) {
            $details = $transaction->transactionDetail;
            $firstDetail = $details->first();
            $totalQuantity = 0;
            $totalCost = 0;

            foreach ($details as $detail) {
                $totalQuantity += $detail->quantity;
                $totalCost += $detail->sub_price;
            }

            return [
                'id' => $transaction->id,
                'transaction_date' => $transaction->transaction_date instanceof \DateTime
                    ? $transaction->transaction_date->format('d-m-Y')
                    : date('d-m-Y', strtotime($transaction->transaction_date)),
                'merchant_name' => $transaction->merchant->merchant_name,
                'product_name' => $firstDetail ? $firstDetail->distributionProduct->product_name : 'N/A',
                'product_details' => $details->map(function ($detail) {
                    return [
                        'product_name' => $detail->distributionProduct->product_name,
                        'quantity' => $detail->quantity,
                        'price' => $detail->sub_price,
                    ];
                }),
                'quantity' => $totalQuantity,
                'transaction_cost' => $totalCost,
            ];
        });
        $transactionId = Transaction::with(['merchant', 'transactionDetail.distributionProduct'])
            ->where('id_merchant', $merchant->id)
            ->orderBy('transaction_date', 'desc')
            ->findOrFail($id);

        $details = $transactionId->transactionDetail;
        $firstDetail = $details->first();

        $totalQuantity = 0;
        $totalCost = 0;

        foreach ($details as $detail) {
            $totalQuantity += $detail->quantity;
            $totalCost += $detail->sub_price;
        }

        $transactionIdDetail = [
            'id' => $transactionId->id,
            'transaction_date' => $transactionId->transaction_date instanceof \DateTime
                ? $transactionId->transaction_date->format('d-m-Y')
                : date('d-m-Y', strtotime($transactionId->transaction_date)),
            'merchant_name' => $transactionId->merchant->name,
            'product_name' => $firstDetail ? $firstDetail->distributionProduct->product_name : 'N/A',
            'product_details' => $details->map(function ($detail) {
                return [
                    'product_name' => $detail->distributionProduct->product_name,
                    'quantity' => $detail->quantity,
                    'price' => $detail->sub_price,
                ];
            }),
            'quantity' => $totalQuantity,
            'transaction_cost' => $totalCost,
        ];

        return view('merchant.transaction.index', compact('transactions', 'transactionIdDetail'));
    }

    public function createForm()
    {
        $products = DistributionProduct::where('product_quantity', '>', 0)
            ->orderBy('product_name')
            ->get();

        $cart = Session::get('cart', []);
        $cartTotal = 0;

        // Hitung total
        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }

        return view('merchant.transaction.create', compact('products', 'cart', 'cartTotal'));
    }

    public function addToCart(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity;

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:distribution_products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Input tidak valid')
                ->withErrors($validator)
                ->withInput();
        }

        $product = DistributionProduct::findOrFail($productId);

        if ($product->product_quantity < $quantity) {
            return redirect()->back()
                ->with('error', 'Stok produk tidak mencukupi')
                ->withInput();
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            if ($product->product_quantity < $newQuantity) {
                return redirect()->back()
                    ->with('error', 'Total kuantitas melebihi stok yang tersedia')
                    ->withInput();
            }
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->product_name,
                'price' => $product->product_price,
                'quantity' => $quantity
            ];
        }

        Session::put('cart', $cart);

        return redirect()->route('merchant.transaction.create.form')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function removeFromCart($productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        return redirect()->route('merchant.transaction.create.form')
            ->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    public function updateCart(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity;

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:distribution_products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Input tidak valid');
        }

        $product = DistributionProduct::findOrFail($productId);

        if ($product->product_quantity < $quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }

        return redirect()->route('merchant.transaction.create.form')
            ->with('success', 'Keranjang berhasil diperbarui');
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('merchant.transaction.create.form')
                ->with('error', 'Keranjang belanja kosong');
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $totalAmount = 0;
            $totalQuantity = 0;

            $financeDate = now()->toDateString();
            $finance = Finance::where('finance_date', $financeDate)->first();

            if (!$finance) {
                $finance = Finance::create([
                    'finance_date' => $financeDate,
                    'total_quantity' => 0,
                    'income_balance' => 0,
                    'expenditure_balance' => 0
                ]);
            }

            // Create main transaction with finance_id
            $transaction = Transaction::create([
                'transaction_date' => now(),
                'id_merchant' => $user->merchant->id,
                'id_finance' => $finance->id // Tambahkan id_finance saat membuat transaksi
            ]);

            // Process each product in cart
            foreach ($cart as $item) {
                $productId = $item['id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $subTotal = $price * $quantity;

                $product = DistributionProduct::findOrFail($productId);

                // Check stock availability again to be safe
                if ($product->product_quantity < $quantity) {
                    throw new \Exception("Stok untuk produk {$product->product_name} tidak mencukupi");
                }

                // Create transaction detail
                TransactionDetail::create([
                    'quantity' => $quantity,
                    'sub_price' => $subTotal,
                    'id_transaction' => $transaction->id,
                    'id_distribution_product' => $productId
                ]);

                // Update product quantity
                $product->decrement('product_quantity', $quantity);

                // Add to total
                $totalAmount += $subTotal;
                $totalQuantity += $quantity;
            }

            // Update finance dengan nilai total yang sudah dihitung
            $finance->update([
                'total_quantity' => $finance->total_quantity + $totalQuantity,
                'income_balance' => $finance->income_balance + $totalAmount
            ]);

            // Clear cart
            Session::forget('cart');

            DB::commit();

            return redirect()->route('merchant.transaction.index', $transaction->id)
                ->with('success', 'Transaksi berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('merchant.transaction.create.form')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function clearCart()
    {
        Session::forget('cart');
        return redirect()->route('merchant.transaction.create.form')
            ->with('success', 'Keranjang berhasil dikosongkan');
    }

    public function create(Request $request, $id, $quantity)
    {
        $user = Auth::user();
        $distributionProduct = DistributionProduct::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Kuantitas Harus Berisi Angka')
                ->withInput();
        }

        if ($distributionProduct->product_quantity < $request->quantity) {
            return redirect()->back()
                ->with('error', 'Produk Tidak Mencukupi')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $subTotal = $distributionProduct->product_price * $request->quantity;

            // Buat finance terlebih dahulu
            $financeDate = now()->toDateString();
            $finance = Finance::where('finance_date', $financeDate)->first();

            if (!$finance) {
                $finance = Finance::create([
                    'finance_date' => $financeDate,
                    'total_quantity' => 0,
                    'income_balance' => 0,
                    'expenditure_balance' => 0
                ]);
            }

            // Buat transaction dengan id_finance
            $transaction = Transaction::create([
                'transaction_date' => now(),
                'id_merchant' => $user->merchant->id,
                'id_finance' => $finance->id // Tambahkan id_finance
            ]);

            TransactionDetail::create([
                'quantity' => $request->quantity,
                'sub_price' => $subTotal,
                'id_transaction' => $transaction->id,
                'id_distribution_product' => $distributionProduct->id
            ]);

            $distributionProduct->decrement('product_quantity', $request->quantity);

            // Update finance
            $finance->update([
                'total_quantity' => $finance->total_quantity + $request->quantity,
                'income_balance' => $finance->income_balance + $subTotal
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return view('merchant.transaction.create', compact('distributionProduct'));
    }
}
