<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Importar Carbon para manejar fechas

class DashboardController extends Controller
{
     public function index()
     {
          // --- Cálculos de KPIs ---
          $totalClients = Customer::count();

          if ($totalClients === 0) {
               return Inertia::render('Dashboard', ['dashboardData' => null]);
          }

          // --- Usando DB::raw para máxima compatibilidad ---
          $currentMonth = Carbon::now()->month;
          $monthlyBirthdays = Customer::where(DB::raw('MONTH(birthday)'), $currentMonth)
               ->select('full_name', 'birthday')
               ->orderByRaw('DAY(birthday)') // Ordenar por día del mes
               ->get();

          $today = Carbon::today();
          $threeDaysFromNow = Carbon::today()->addDays(3);

          $pendingVisits = Customer::where('visit_confirmed', false)
               ->whereNotNull('next_visit')
               ->whereBetween('next_visit', [$today, $threeDaysFromNow])
               ->select('id', 'full_name', 'next_visit')
               ->orderBy('next_visit')
               ->get();

          $totalBalance = Customer::sum('current_balance');

          $overdueBalance = Customer::sum('due_31_60_days')
               + Customer::sum('due_61_90_days')
               + Customer::sum('due_over_90_days');

          $delinquencyRate = $totalBalance > 0 ? ($overdueBalance / $totalBalance) * 100 : 0;

          $agingData = [
               '0-30' => (float) Customer::sum('due_0_30_days'),
               '31-60' => (float) Customer::sum('due_31_60_days'),
               '61-90' => (float) Customer::sum('due_61_90_days'),
               '90+' => (float) Customer::sum('due_over_90_days'),
          ];

          $topClients = Customer::select('full_name', 'current_balance')
               ->orderBy('current_balance', 'desc')
               ->take(20)
               ->get();

          $topCities = Customer::select('city', DB::raw('count(*) as total'))
               ->whereNotNull('city')->where('city', '!=', '')
               ->groupBy('city')->orderBy('total', 'desc')
               ->take(5)->get();

          $topStates = Customer::select('state', DB::raw('count(*) as total'))
               ->whereNotNull('state')->where('state', '!=', '')
               ->groupBy('state')->orderBy('total', 'desc')
               ->take(5)->get();

          $levelDistribution = Customer::select('level', DB::raw('count(*) as total'))
               ->whereNotNull('level')
               ->groupBy('level')
               ->orderBy('level')
               ->get();

          $levelLabels = $levelDistribution->map(fn($item) => 'Nivel ' . $item->level);
          $levelTotals = $levelDistribution->pluck('total');

          $dashboardStats = [
               'pendingVisits' => $pendingVisits, // <-- LÍNEA CORREGIDA Y RESTAURADA
               'monthlyBirthdays' => $monthlyBirthdays,
               'totalBalance' => number_format($totalBalance, 2),
               'overdueBalance' => number_format($overdueBalance, 2),
               'delinquencyRate' => round($delinquencyRate, 2),
               'totalClients' => $totalClients,
               'agingChartData' => [
                    'labels' => ['0-30 Días', '31-60 Días', '61-90 Días', 'Más de 90 Días'],
                    'datasets' => [['label' => 'Saldo de Cartera ($)', 'backgroundColor' => ['#4ade80', '#facc15', '#fb923c', '#f87171'], 'data' => array_values($agingData)]],
               ],
               'topClients' => $topClients,
               'topCities' => $topCities,
               'topStates' => $topStates,
               'levelDistributionChartData' => [
                    'labels' => $levelLabels,
                    'datasets' => [['backgroundColor' => ['#41B883', '#E46651', '#00D8FF', '#DD1B16', '#34495E', '#F1C40F', '#9B59B6', '#2ECC71'], 'data' => $levelTotals]],
               ],
          ];

          return Inertia::render('Dashboard', [
               'dashboardData' => $dashboardStats
          ]);
     }
}
