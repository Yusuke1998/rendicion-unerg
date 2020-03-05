<?php

namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function search(Request $request)
    {
        $cedula = $request->cedula;
        if ($cedula!=='')
        {
            $find = DB::table('rhnomficha')
                        ->select('fic_id as ficha', 'fic_cedula as cedula')
                        ->where('fic_cedula','=', $cedula)
                        ->first();

            if (!empty($find->ficha))
            {
                // Inicio Consulta
                $allData = DB::table('vw_rhnommvd')
                            ->select(DB::raw('vw_rhnommvd.per_ficha, vw_rhnommvd.*, rhnommvdkp.kon_numerico'))
                            ->join('rhnomrubgrp', function($join)
                               {
                                $join->on('vw_rhnommvd.rub_codigo', '=', 'rhnomrubgrp.rub_codigo')
                                    ->on(function($cjoin){
                                        $cjoin->where('rhnomrubgrp.grp_codigo', '=', '6')
                                            ->orWhere('rhnomrubgrp.grp_codigo', '=', '20');
                                    });
                                })
                            ->leftJoin('rhnommvdkp', function($lJoin)
                                { 
                                    $lJoin->on('vw_rhnommvd.emp_codigo', '=', 'rhnommvdkp.emp_codigo')
                                        ->on('vw_rhnommvd.nom_codigo', '=', 'rhnommvdkp.nom_codigo')
                                        ->on('vw_rhnommvd.per_ficha', '=', 'rhnommvdkp.per_ficha')
                                        ->where('rhnommvdkp.kon_codigo', '=', '22');
                                })
                            ->where('vw_rhnommvd.emp_codigo', '=', '1')
                            ->where('vw_rhnommvd.nom_clase', '=', '0')
                            ->where('vw_rhnommvd.rub_monto', '>', '0')
                            ->where(DB::raw('date_part(\'year\', vw_rhnommvd.nom_hasta)'), '=', '2019')
                            ->where('vw_rhnommvd.fic_id', '=', $find->ficha)
                            ->get();
                // Fin Consulta

                $months = [
                    '01'=>'Enero', '02'=>'Febrero', '03'=>'Marzo', 
                    '04'=>'Abril', '05'=>'Mayo', '06'=>'Junio', 
                    '07'=>'Julio', '08'=>'Agosto', '09'=>'Septiembre',
                    '10'=>'Octubre', '11'=>'Noviembre', '12'=>'Diciembre'
                ];

                $rub_tipo = [];
                $registersA=[];
                $registersD=[];
                foreach ($allData as $data)
                {
                    if ($data->rub_tipo === 'A')
                    {
                        $date = new Carbon($data->nom_fecha);
                        $value = $registersA[$months[$date->format('m')]] ?? 0;
                        $registersA[$months[$date->format('m')]] = $value + $data->rub_monto;
                        $rub_tipo['Asignaciones'] = $registersA;

                    }elseif ($data->rub_tipo === 'D') 
                    {
                        $date = new Carbon($data->nom_fecha);
                        $value = $registersD[$months[$date->format('m')]] ?? 0;
                        $registersD[$months[$date->format('m')]] = $value + $data->rub_monto;
                        $rub_tipo['Deducciones'] = $registersD;
                    }
                }

                // dd($rub_tipo);
                // dd($registersA,$registersD);

                return  response([
                    'status'    =>  'success',
                    'data'      =>  $rub_tipo,
                ], 200);
            }
        }
        return  response([
                    'status'    =>  'success',
                    'data'      =>  '[]',
                ], 200);
    }
}
