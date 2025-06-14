<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Merchant;
use App\Models\Finance;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Merchant
        $totalMerchant = Merchant::count();

        // Merchant bulan sebelumnya
        $previousMonthMerchant = Merchant::whereMonth('created_at', Carbon::now()->subMonth()->month)
                                        ->whereYear('created_at', Carbon::now()->subMonth()->year)
                                        ->count();

        // Persentase pertumbuhan merchant
        $merchantGrowth = $previousMonthMerchant > 0 ?
            round((($totalMerchant - $previousMonthMerchant) / $previousMonthMerchant) * 100) : 0;

        // Total Pendapatan bulan ini
        $currentMonthIncome = Finance::whereMonth('finance_date', Carbon::now()->month)
                                    ->whereYear('finance_date', Carbon::now()->year)
                                    ->sum('income_balance');

        // Total Pendapatan bulan lalu
        $previousMonthIncome = Finance::whereMonth('finance_date', Carbon::now()->subMonth()->month)
                                     ->whereYear('finance_date', Carbon::now()->subMonth()->year)
                                     ->sum('income_balance');

        // Persentase pertumbuhan pendapatan
        $incomeGrowth = $previousMonthIncome > 0 ?
            round((($currentMonthIncome - $previousMonthIncome) / $previousMonthIncome) * 100) : 0;

        // Total Produk (dari total_quantity)
        $totalProducts = Finance::sum('total_quantity');

        // Total Produk bulan lalu
        $previousMonthProducts = Finance::whereMonth('finance_date', Carbon::now()->subMonth()->month)
                                       ->whereYear('finance_date', Carbon::now()->subMonth()->year)
                                       ->sum('total_quantity');

        // Persentase pertumbuhan produk
        $productsGrowth = $previousMonthProducts > 0 ?
            round((($totalProducts - $previousMonthProducts) / $previousMonthProducts) * 100) : 0;

        // Total Pengguna (asumsi dari merchant + admin, untuk sementara hanya merchant)
        $totalUsers = Merchant::count();

        // Pengguna bulan lalu
        $previousMonthUsers = Merchant::whereMonth('created_at', Carbon::now()->subMonth()->month)
                                     ->whereYear('created_at', Carbon::now()->subMonth()->year)
                                     ->count();

        // Persentase pertumbuhan pengguna
        $usersGrowth = $previousMonthUsers > 0 ?
            round((($totalUsers - $previousMonthUsers) / $previousMonthUsers) * 100) : 0;

        // Transaksi Terbaru (5 transaksi terakhir)
        $recentTransactions = Transaction::with(['merchant', 'finance'])
                                        ->orderBy('transaction_date', 'desc')
                                        ->take(5)
                                        ->get();

        // Data untuk grafik pertumbuhan penjualan (12 bulan terakhir)
        $salesGrowthData = Finance::select(
                                DB::raw('MONTH(finance_date) as month'),
                                DB::raw('YEAR(finance_date) as year'),
                                DB::raw('SUM(income_balance) as total_income')
                            )
                            ->where('finance_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
                            ->groupBy('year', 'month')
                            ->orderBy('year', 'asc')
                            ->orderBy('month', 'asc')
                            ->get();

        // Format data untuk chart
        $chartLabels = [];
        $chartData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M Y');
            $chartLabels[] = $monthName;

            $monthData = $salesGrowthData->where('month', $date->month)
                                       ->where('year', $date->year)
                                       ->first();

            $chartData[] = $monthData ? $monthData->total_income : 0;
        }

        return view('admin.dashboard', compact(
            'totalMerchant',
            'merchantGrowth',
            'currentMonthIncome',
            'incomeGrowth',
            'totalProducts',
            'productsGrowth',
            'totalUsers',
            'usersGrowth',
            'recentTransactions',
            'chartLabels',
            'chartData'
        ));
    }
}
