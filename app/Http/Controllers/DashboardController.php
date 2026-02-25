<?php

namespace App\Http\Controllers;

use App\Models\Passation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $now        = now();
        $debutMois  = $now->copy()->startOfMonth();
        $debutMoisD = $now->copy()->subMonth()->startOfMonth();
        $finMoisD   = $now->copy()->subMonth()->endOfMonth();

        // ── Métriques simples ──────────────────────────────────────
        $passationsMois     = Passation::where('created_at', '>=', $debutMois)->count();
        $passationsMoisPrec = Passation::whereBetween('created_at', [$debutMoisD, $finMoisD])->count();
        $evolutionMois      = $passationsMois - $passationsMoisPrec;
        $totalPassations    = Passation::count();
        $totalBeneficiaires = Passation::distinct('id_beneficiaire')->count('id_beneficiaire');

        // ── Score moyen — score est un JSON {"Resilience":0, ...} ──
        // On récupère tous les scores et on fait la somme en PHP
        $tousLesScores = Passation::pluck('score')->map(function ($s) {
            $arr = is_string($s) ? json_decode($s, true) : $s;
            return array_sum($arr ?? []);
        });

        $scoreMoyen = $totalPassations > 0
            ? round($tousLesScores->avg(), 1)
            : null;

        // ── Répartition scores pour le donut ───────────────────────
        $repartitionScores = [
            $tousLesScores->filter(fn($v) => $v < 0)->count(),
            $tousLesScores->filter(fn($v) => $v >= 0 && $v <= 15)->count(),
            $tousLesScores->filter(fn($v) => $v > 15)->count(),
        ];

        // ── 5 dernières passations ─────────────────────────────────
        $dernieresPassations = Passation::with(['beneficiaire', 'user'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // ── Graphique barres : 6 derniers mois ─────────────────────
        $labelsBarChart = [];
        $dataBarChart   = [];

        for ($i = 5; $i >= 0; $i--) {
            $mois  = $now->copy()->subMonths($i);
            $count = Passation::whereYear('created_at', $mois->year)
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
