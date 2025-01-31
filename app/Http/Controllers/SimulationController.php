<?php

namespace App\Http\Controllers;

use App\Services\SimulationService;
use Illuminate\Support\Facades\Session;

class SimulationController extends Controller
{
    /**
     * Le service utilisé pour exécuter la simulation.
     *
     * @var SimulationService
     */
    protected $simulationService;

    /**
     * Injecte le service SimulationService dans le contrôleur.
     *
     * @param SimulationService $simulationService
     */
    public function __construct(SimulationService $simulationService)
    {
        $this->simulationService = $simulationService;
    }

    /**
     * Affiche la vue de simulation.
     */
    public function simulationView()
    {
        $login = Session::get('login');
        return view('simulation.simulation', compact('login'));
    }

    /**
     * Lance la simulation d'affectations.
     */
    public function runSimulation()
    {
        $result = $this->simulationService->run();

        if (str_contains($result, 'erreur')) {
            return back()->withErrors($result);
        }

        return back()->with('success', $result);
    }
}
