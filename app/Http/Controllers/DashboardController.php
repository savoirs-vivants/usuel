<?php

namespace App\Http\Controllers;

use App\Models\Passation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $baseQuery = Passation::query();

        if ($user->role === 'travailleur') {
            $baseQuery->where('id_travailleur', $user->id);

        } elseif ($user->role === 'gestionnaire') {
            $baseQuery->whereHas('user', function (Builder $query) use ($user) {
                $query->where('structure', $user->structure);
            });
        }


        $now        = now();
        $debutMois  = $now->copy()->startOfMonth();
        $debutMoisD = $now->copy()->subMonth()->startOfMonth();
        $finMoisD   = $now->copy()->subMonth()->endOfMonth();


        $passationsMois     = (clone $baseQuery)->where('created_at', '>=', $debutMois)->count();
        $passationsMoisPrec = (clone $baseQuery)->whereBetween('created_at', [$debutMoisD, $finMoisD])->count();
        $evolutionMois      = $passationsMois - $passationsMoisPrec;
        $totalPassations    = (clone $baseQuery)->count();
        $totalBeneficiaires = (clone $baseQuery)->distinct('id_beneficiaire')->count('id_beneficiaire');


        $tousLesScores = (clone $baseQuery)->pluck('score')->map(function ($s) {
            $arr = is_string($s) ? json_decode($s, true) : $s;
            return array_sum($arr ?? []);
        });

        $scoreMoyen = $totalPassations > 0
            ? round($tousLesScores->avg(), 1)
            : null;


        $repartitionScores = [
            $tousLesScores->filter(fn($v) => $v < 0)->count(),
            $tousLesScores->filter(fn($v) => $v >= 0 && $v <= 15)->count(),
            $tousLesScores->filter(fn($v) => $v > 15)->count(),
        ];


        $dernieresPassations = (clone $baseQuery)->with(['beneficiaire', 'user'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();


        $labelsBarChart = [];
        $dataBarChart   = [];

        for ($i = 5; $i >= 0; $i--) {
            $mois  = $now->copy()->subMonths($i);
            $count = (clone $baseQuery)
                ->whereYear('created_at', $mois->year)
                ->whereMonth('created_at', $mois->month)
                ->count();

            $labelsBarChart[] = ucfirst($mois->isoFormat('MMM'));
            $dataBarChart[]   = $count;
        }

        return view('dashboard', compact(
            'passationsMois',
            'evolutionMois',
            'scoreMoyen',
            'totalPassations',
            'totalBeneficiaires',
            'dernieresPassations',
            'labelsBarChart',
            'dataBarChart',
            'repartitionScores',
        ));
    }
}
