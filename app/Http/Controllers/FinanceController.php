<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\FinanceDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $currentDate = $request->get('current_date', now()->format('Y-m-d'));
        $date = Carbon::parse($currentDate);

        $startOfYear = $date->copy()->startOfYear();
        $endOfYear = $date->copy()->endOfYear();

        if ($request->get('direction') === 'prev') {
            $startOfYear = $startOfYear->subYear();
            $endOfYear = $endOfYear->subYear();
        } elseif ($request->get('direction') === 'next') {
            $startOfYear = $startOfYear->addYear();
            $endOfYear = $endOfYear->addYear();
        }

        $periodeStart = $startOfYear->format('Y');
        $periodeEnd = $endOfYear->format('Y');

        $navigationDate = $startOfYear->copy()->addMonths(6)->format('Y-m-d');

        $finances = Finance::with('FinanceDetail')
            ->whereBetween('finance_date', [
                $startOfYear->format('Y-m-d'),
                $endOfYear->format('Y-m-d')
            ])
            ->orderBy('finance_date', 'desc')
            ->get();

        $financeLabels = [];
        $financeIncome = [];
        $financeExpenditure = [];

        for ($i = 0; $i < 12; $i++) {
            $currentMonth = $startOfYear->copy()->addMonths($i);
            $monthStart = $currentMonth->copy()->startOfMonth();
            $monthEnd = $currentMonth->copy()->endOfMonth();

            $monthData = $finances->whereBetween('finance_date', [
                $monthStart->format('Y-m-d'),
                $monthEnd->format('Y-m-d')
            ]);

            $financeLabels[] = $currentMonth->format('M Y');
            $financeIncome[] = $monthData->sum('income_balance');

            $monthExpenditure = 0;
            foreach ($monthData as $finance) {
                $monthExpenditure += $finance->FinanceDetail->sum('expenditure_cost');
            }
            $financeExpenditure[] = $monthExpenditure;
        }

        $totalIncome = $finances->sum('income_balance');

        $totalExpenditure = 0;
        foreach ($finances as $finance) {
            $totalExpenditure += $finance->FinanceDetail->sum('expenditure_cost');
        }

        $tanggalRekapitulasi = Transaction::select('transaction_date')
            ->distinct()
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('admin.finance.index', compact(
            'finances',
            'financeLabels',
            'financeIncome',
            'financeExpenditure',
            'tanggalRekapitulasi',
            'periodeStart',
            'periodeEnd',
            'totalIncome',
            'totalExpenditure',
            'navigationDate'
        ));
    }

    public function show($id)
    {
        $finance = Finance::with('FinanceDetail')->findOrFail($id);

        $transactions = Transaction::where('id_finance', $finance->id)
            ->with(['merchant', 'TransactionDetail.DistributionProduct'])
            ->get();

        $transactionData = [];

        if ($transactions->isNotEmpty()) {
            $transactionData = $transactions->map(
                function ($transaction) {
                    $details = $transaction->TransactionDetail;
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
                        'product_count' => $details->count(),
                        'quantity' => $totalQuantity,
                        'transaction_cost' => $totalCost,
                    ];
                }
            );
        }

        $totalExpenditure = $finance->FinanceDetail->sum('expenditure_cost');

        return view('admin.finance.show', compact('finance', 'transactionData', 'totalExpenditure'));
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->handleStore($request);
        }

        $tanggalRekapitulasi = Transaction::select('transaction_date')
            ->distinct()
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('admin.finance.create', compact('tanggalRekapitulasi'));
    }

    private function handleStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'finance_date' => 'required|date',
            'expenditure_details' => 'required|array|min:1',
            'expenditure_details.*.cost' => 'required|numeric|min:0',
            'expenditure_details.*.description' => 'required|string|max:255',
        ], [
            'expenditure_details.required' => 'Detail pengeluaran wajib diisi',
            'expenditure_details.array' => 'Detail pengeluaran harus berupa array',
            'expenditure_details.min' => 'Minimal harus ada 1 detail pengeluaran',
            'expenditure_details.*.cost.required' => 'Biaya pengeluaran wajib diisi',
            'expenditure_details.*.cost.numeric' => 'Biaya pengeluaran harus berupa angka',
            'expenditure_details.*.cost.min' => 'Biaya pengeluaran tidak boleh kurang dari 0',
            'expenditure_details.*.description.required' => 'Deskripsi pengeluaran wajib diisi',
            'expenditure_details.*.description.string' => 'Deskripsi pengeluaran harus berupa teks',
            'expenditure_details.*.description.max' => 'Deskripsi pengeluaran maksimal 255 karakter',
            'finance_date.required' => 'Tanggal rekapitulasi wajib diisi',
            'finance_date.date' => 'Format tanggal tidak valid',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $existingFinance = Finance::where('finance_date', $request->finance_date)->first();

            if ($existingFinance) {
                foreach ($request->expenditure_details as $detail) {
                    FinanceDetail::create([
                        'id_finance' => $existingFinance->id,
                        'expenditure_cost' => $detail['cost'],
                        'expenditure_description' => $detail['description'],
                    ]);
                }

                $totalExpenditure = FinanceDetail::where('id_finance', $existingFinance->id)
                    ->sum('expenditure_cost');

                $existingFinance->update([
                    'expenditure_balance' => $totalExpenditure,
                    'total_quantity' => $this->calculateTotalQuantity($request->finance_date),
                ]);

                DB::commit();

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Data keuangan berhasil diperbarui'
                    ]);
                }

                return redirect()->route('admin.finance.index')
                    ->with('success', 'Data keuangan berhasil diperbarui');
            }

            $finance = Finance::create([
                'finance_date' => $request->finance_date,
                'expenditure_balance' => 0,
                'income_balance' => $this->calculateIncomeBalance($request->finance_date),
                'total_quantity' => $this->calculateTotalQuantity($request->finance_date),
            ]);

            $totalExpenditure = 0;
            foreach ($request->expenditure_details as $detail) {
                FinanceDetail::create([
                    'id_finance' => $finance->id,
                    'expenditure_cost' => $detail['cost'],
                    'expenditure_description' => $detail['description'],
                ]);
                $totalExpenditure += $detail['cost'];
            }

            $finance->update(['expenditure_balance' => $totalExpenditure]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data keuangan berhasil ditambahkan'
                ]);
            }

            return redirect()->route('admin.finance.index')
                ->with('success', 'Data keuangan berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        $finance = Finance::with('FinanceDetail')->findOrFail($id);

        if ($request->isMethod('put') || $request->isMethod('patch')) {
            return $this->handleUpdate($request, $id);
        }

        return view('admin.finance.edit', compact('finance'));
    }

    private function handleUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'finance_date' => 'required|date',
            'expenditure_details' => 'required|array|min:1',
            'expenditure_details.*.cost' => 'required|numeric|min:0',
            'expenditure_details.*.description' => 'required|string|max:255',
        ], [
            'expenditure_details.required' => 'Detail pengeluaran wajib diisi',
            'expenditure_details.array' => 'Detail pengeluaran harus berupa array',
            'expenditure_details.min' => 'Minimal harus ada 1 detail pengeluaran',
            'expenditure_details.*.cost.required' => 'Biaya pengeluaran wajib diisi',
            'expenditure_details.*.cost.numeric' => 'Biaya pengeluaran harus berupa angka',
            'expenditure_details.*.cost.min' => 'Biaya pengeluaran tidak boleh kurang dari 0',
            'expenditure_details.*.description.required' => 'Deskripsi pengeluaran wajib diisi',
            'expenditure_details.*.description.string' => 'Deskripsi pengeluaran harus berupa teks',
            'expenditure_details.*.description.max' => 'Deskripsi pengeluaran maksimal 255 karakter',
            'finance_date.required' => 'Tanggal rekapitulasi wajib diisi',
            'finance_date.date' => 'Format tanggal tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $finance = Finance::findOrFail($id);

        $existingFinance = Finance::where('finance_date', $request->finance_date)
            ->where('id', '!=', $id)
            ->first();

        if ($existingFinance) {
            return redirect()->back()
                ->withErrors(['finance_date' => 'Data keuangan untuk tanggal ini sudah ada'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            FinanceDetail::where('id_finance', $finance->id)->delete();

            $totalExpenditure = 0;
            foreach ($request->expenditure_details as $detail) {
                FinanceDetail::create([
                    'id_finance' => $finance->id,
                    'expenditure_cost' => $detail['cost'],
                    'expenditure_description' => $detail['description'],
                ]);
                $totalExpenditure += $detail['cost'];
            }

            $finance->update([
                'finance_date' => $request->finance_date,
                'expenditure_balance' => $totalExpenditure,
            ]);

            DB::commit();

            return redirect()->route('admin.finance.index')
                ->with('success', 'Data keuangan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function addExpenditureDetail(Request $request, $financeId)
    {
        $validator = Validator::make($request->all(), [
            'expenditure_cost' => 'required|numeric|min:0',
            'expenditure_description' => 'required|string|max:255',
        ], [
            'expenditure_cost.required' => 'Biaya pengeluaran wajib diisi',
            'expenditure_cost.numeric' => 'Biaya pengeluaran harus berupa angka',
            'expenditure_cost.min' => 'Biaya pengeluaran tidak boleh kurang dari 0',
            'expenditure_description.required' => 'Deskripsi pengeluaran wajib diisi',
            'expenditure_description.string' => 'Deskripsi pengeluaran harus berupa teks',
            'expenditure_description.max' => 'Deskripsi pengeluaran maksimal 255 karakter',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $finance = Finance::findOrFail($financeId);

            FinanceDetail::create([
                'id_finance' => $finance->id,
                'expenditure_cost' => $request->expenditure_cost,
                'expenditure_description' => $request->expenditure_description,
            ]);

            $totalExpenditure = FinanceDetail::where('id_finance', $finance->id)
                ->sum('expenditure_cost');

            $finance->update(['expenditure_balance' => $totalExpenditure]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Detail pengeluaran berhasil ditambahkan'
                ]);
            }

            return redirect()->back()->with('success', 'Detail pengeluaran berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function removeExpenditureDetail($detailId)
    {
        DB::beginTransaction();

        try {
            $financeDetail = FinanceDetail::findOrFail($detailId);
            $financeId = $financeDetail->id_finance;

            $financeDetail->delete();

            $finance = Finance::findOrFail($financeId);
            $totalExpenditure = FinanceDetail::where('id_finance', $financeId)
                ->sum('expenditure_cost');

            $finance->update(['expenditure_balance' => $totalExpenditure]);

            DB::commit();

            return redirect()->back()->with('success', 'Detail pengeluaran berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function calculateIncomeBalance($date)
    {
        return Transaction::whereDate('transaction_date', $date)
            ->with('TransactionDetail')
            ->get()
            ->sum(function ($transaction) {
                return $transaction->TransactionDetail->sum('sub_total');
            });
    }

    private function calculateTotalQuantity($date)
    {
        return Transaction::whereDate('transaction_date', $date)
            ->with('TransactionDetail')
            ->get()
            ->sum(function ($transaction) {
                return $transaction->TransactionDetail->sum('quantity');
            });
    }

    public function getFinanceByPeriod(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $finances = Finance::with('FinanceDetail')
            ->whereBetween('finance_date', [$startDate, $endDate])
            ->orderBy('finance_date', 'asc')
            ->get();

        $totalExpenditure = 0;
        foreach ($finances as $finance) {
            $totalExpenditure += $finance->FinanceDetail->sum('expenditure_cost');
        }

        return response()->json([
            'success' => true,
            'data' => $finances,
            'summary' => [
                'total_income' => $finances->sum('income_balance'),
                'total_expenditure' => $totalExpenditure,
                'difference' => $finances->sum('income_balance') - $totalExpenditure
            ]
        ]);
    }
}
