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

class TransactionController extends Controller
{
    public function index($id)
    {
        $transactions = Transaction::with(['merchant', 'transactionDetail.distributionProduct'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(7);

        $transactions->getCollection()->transform(function ($transaction)
        {
            $detail = $transaction->transactionDetail->first();
            return
            [
                'id' => $transaction->id,
                'transaction_date' => $transaction->transaction_date->format('d-m-Y'),
                'merchant_name' => $transaction->merchant->name,
                'product_name' => $detail ? $detail->distributionProduct->product_name : 'N/A',
                'quantity'=> $detail ? $detail->quantity : 0,
                'transaction_cost' => $detail ? $detail->sub_price : 0,
            ];
        });

        $transactionId = Transaction::with(['merchant', 'transactionDetail.distributionProduct'])
            ->orderBy('transaction_date', 'desc')
            ->findOrFail($id);

        $detailId = $transactionId->transactionDetail->first();

        $transactionIdDetail = [
            'id' => $transactionId->id,
            'transaction_date' => $transactionId->transaction_date->format('d-m-Y'),
            'merchant_name' => $transactionId->merchant->name,
            'product_name' => $detailId ? $detailId->distributionProduct->product_name : 'N/A',
            'quantity'=> $detailId ? $detailId->quantity : 0,
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

        $transactions->getCollection()->transform(function ($transaction)
        {
            $detail = $transaction->transactionDetail->first();
            return
            [
                'id' => $transaction->id,
                'transaction_date' => $transaction->transaction_date->format('d-m-Y'),
                'merchant_name' => $transaction->merchant->name,
                'product_name' => $detail ? $detail->distributionProduct->product_name : 'N/A',
                'quantity'=> $detail ? $detail->quantity : 0,
                'transaction_cost' => $detail ? $detail->sub_price : 0,
            ];
        });

        $transactionId = Transaction::with(['merchant', 'transactionDetail.distributionProduct'])
        ->where('id_merchant', $merchant->id)
        ->orderBy('transaction_date', 'desc')
        ->findOrFail($id);

        $detailId = $transactionId->transactionDetail->first();

        $transactionIdDetail = [
            'id' => $transactionId->id,
            'transaction_date' => $transactionId->transaction_date->format('d-m-Y'),
            'merchant_name' => $transactionId->merchant->name,
            'product_name' => $detailId ? $detailId->distributionProduct->product_name : 'N/A',
            'quantity'=> $detailId ? $detailId->quantity : 0,
            'transaction_cost' => $detailId ? $detailId->sub_price : 0,
        ];

        return view('merchant.transaction.index', compact('transactions', 'transactionIdDetail'));
    }

    public function create(Request $request, $id, $quantity)
    {
        $user = Auth::user();
        $distributionProduct = DistributionProduct::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()
            ->with('error', 'Kuantitas Harus Berisi Angka')
            ->withInput();
        }

        if ($distributionProduct->product_quantity < $request->quantity)
        {
            return redirect()->back()
            ->with('error', 'Produk Tidak Mencukupi')
            ->withInput();
        }

        try {
            DB::beginTransaction();

            $subTotal = $distributionProduct->product_price * $request->quantity;

            $transaction = Transaction::create([
                'transaction_date' => now(),
                'id_merchant' => $user->merchant->id,
            ]);

            TransactionDetail::create([
                'quantity' => $request->quantity,
                'sub_price' => $subTotal,
                'id_transaction' => $transaction->id,
                'id_distribution_product' => $distributionProduct->id
            ]);

            $distributionProduct->decrement('product_quantity', $request->quantity);

            $financeDate = now()->toDateString();
            $finance = Finance::where('finance_date', $financeDate)->first();

            if ($finance) {
                $finance->update([
                    'total_quantity' => $finance->total_quantity + $request->quantity,
                    'income_balance' => $finance->income_balance + $subTotal
                ]);
            } else {
                $finance = Finance::create([
                    'finance_date' => $financeDate,
                    'total_quantity' => $request->quantity,
                    'income_balance' => $subTotal,
                    'expenditure_balance' => 0
                ]);
            }

            $transaction->update(['id_finance' => $finance->id]);

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return view('merchant.transaction.create', compact('distributionProduct'));
    }
}
