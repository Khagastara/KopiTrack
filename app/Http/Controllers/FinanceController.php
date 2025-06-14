<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index(Request $request, $id = null)
    {
        if ($id) {
            $finance = Finance::findOrFail($id);
            return view('admin.finance.show', compact('finance'));
        }

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

        $finances = Finance::whereBetween('finance_date', [
            $startOfYear->format('Y-m-d'),
            $endOfYear->format('Y-m-d')
        ])->orderBy('finance_date', 'asc')->get();

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
            $financeExpenditure[] = $monthData->sum('expenditure_balance');
        }

        $totalIncome = $finances->sum('income_balance');
        $totalExpenditure = $finances->sum('expenditure_balance');

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
        $finance = Finance::findOrFail($id);

        $transactions = Transaction::where('id_finance', $finance->id)
            ->with(['merchant', 'TransactionDetail.DistributionProduct'])
            ->get();
        if ($transactions->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada transaksi untuk rekapitulasi ini.');
        } else {
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
                        'merchant_name' => $transaction->merchant->name,
                        'product_count' => $details->count(),
                        'quantity' => $totalQuantity,
                        'transaction_cost' => $totalCost,
                    ];
                }
            );
        }

        return view('admin.finance.show', compact('finance', 'transactionData'));
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
            'expenditure_balance' => 'required|numeric|min:0',
        ], [
            'expenditure_balance.required' => 'Saldo pengeluaran wajib diisi',
            'expenditure_balance.numeric' => 'Saldo pengeluaran harus berupa angka',
            'expenditure_balance.min' => 'Saldo pengeluaran tidak boleh kurang dari 0',
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

        $existingFinance = Finance::where('finance_date', $request->finance_date)->first();

        if ($existingFinance) {
            $newExpenditureBalance = $existingFinance->expenditure_balance + $request->expenditure_balance;

            $existingFinance->update([
                'expenditure_balance' => $newExpenditureBalance,
                'income_balance' => $this->calculateIncomeBalance($request->finance_date),
                'total_quantity' => $this->calculateTotalQuantity($request->finance_date),
            ]);

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
            'expenditure_balance' => $request->expenditure_balance,
            'income_balance' => $this->calculateIncomeBalance($request->finance_date),
            'total_quantity' => $this->calculateTotalQuantity($request->finance_date),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data keuangan berhasil ditambahkan'
            ]);
        }

        return redirect()->route('admin.finance.index')
            ->with('success', 'Data keuangan berhasil ditambahkan');
    }

    public function edit(Request $request, $id)
    {
        $finance = Finance::findOrFail($id);

        if ($request->isMethod('put') || $request->isMethod('patch')) {
            return $this->handleUpdate($request, $id);
        }

        return view('admin.finance.edit', compact('finance'));
    }

    private function handleUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'finance_date' => 'required|date',
            'expenditure_balance' => 'required|numeric|min:0',
        ], [
            'expenditure_balance.required' => 'Saldo pengeluaran wajib diisi',
            'expenditure_balance.numeric' => 'Saldo pengeluaran harus berupa angka',
            'expenditure_balance.min' => 'Saldo pengeluaran tidak boleh kurang dari 0',
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

        $finance->update([
            'finance_date' => $request->finance_date,
            'expenditure_balance' => $request->expenditure_balance,
        ]);

        return redirect()->route('admin.finance.index')
            ->with('success', 'Data keuangan berhasil diperbarui');
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

        $finances = Finance::whereBetween('finance_date', [$startDate, $endDate])
            ->orderBy('finance_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $finances,
            'summary' => [
                'total_income' => $finances->sum('income_balance'),
                'total_expenditure' => $finances->sum('expenditure_balance'),
                'difference' => $finances->sum('income_balance') - $finances->sum('expenditure_balance')
            ]
        ]);
    }
}
