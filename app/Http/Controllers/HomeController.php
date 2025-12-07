<?php

namespace App\Http\Controllers;

use App\Models\Zonas;
use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {  
        $totalZonas = Zonas::count(); 
        $totalAreas = Area::count();
        $totalUsuarios = User::where('estado', true)->count();
        
        $zonasPorMes = Zonas::select(
                    DB::raw("EXTRACT(MONTH FROM created_at) as mes"),
                    DB::raw("COUNT(*) as total")
                )
                ->whereYear('created_at', date('Y'))
                ->groupBy(DB::raw("EXTRACT(MONTH FROM created_at)"))
                ->orderBy(DB::raw("EXTRACT(MONTH FROM created_at)"))
                ->get();

            $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

            $data = array_fill(0, 12, 0);

            foreach ($zonasPorMes as $item) {
                $data[$item->mes - 1] = $item->total; 
            }

            $labels = $meses;

        return view('home', compact('totalZonas', 'totalAreas', 'totalUsuarios','labels', 'data'));
    }
}
