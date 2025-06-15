<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Merchant;
use App\Models\Finance;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMerchant = Merchant::count();

        $previousMonthMerchant = Merchant::whereMonth('created_at', Carbon::now()->subMonth()->month)
                                        ->whereYear('created_at', Carbon::now()->subMonth()->year)
                                        ->count();

        $merchantGrowth = $previousMonthMerchant > 0 ?
            round((($totalMerchant - $previousMonthMerchant) / $previousMonthMerchant) * 100) : 0;

        $currentMonthIncome = Finance::whereMonth('finance_date', Carbon::now()->month)
                                    ->whereYear('finance_date', Carbon::now()->year)
                                    ->sum('income_balance');

        $previousMonthIncome = Finance::whereMonth('finance_date', Carbon::now()->subMonth()->month)
                                     ->whereYear('finance_date', Carbon::now()->subMonth()->year)
                                     ->sum('income_balance');

        $incomeGrowth = $previousMonthIncome > 0 ?
            round((($currentMonthIncome - $previousMonthIncome) / $previousMonthIncome) * 100) : 0;

        $totalProducts = Finance::sum('total_quantity');

        $previousMonthProducts = Finance::whereMonth('finance_date', Carbon::now()->subMonth()->month)
                                       ->whereYear('finance_date', Carbon::now()->subMonth()->year)
                                       ->sum('total_quantity');

        $productsGrowth = $previousMonthProducts > 0 ?
            round((($totalProducts - $previousMonthProducts) / $previousMonthProducts) * 100) : 0;

        $totalUsers = Merchant::count();

        $previousMonthUsers = Merchant::whereMonth('created_at', Carbon::now()->subMonth()->month)
                                     ->whereYear('created_at', Carbon::now()->subMonth()->year)
                                     ->count();

        $usersGrowth = $previousMonthUsers > 0 ?
            round((($totalUsers - $previousMonthUsers) / $previousMonthUsers) * 100) : 0;

        $recentTransactions = Transaction::with(['merchant', 'finance'])
                                        ->orderBy('transaction_date', 'desc')
                                        ->take(5)
                                        ->get();

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
