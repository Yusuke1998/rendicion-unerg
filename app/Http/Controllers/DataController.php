<?php

namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function search(Request $request)
    {
        $this->validate($request, [
            'cedula' => 'integer'
        ]);

        $dateNowSub = Carbon::now()
                        ->subYear()
                        ->format('Y');
        $periodo = $dateNowSub;
        $cedula = $request->cedula;
        $dataJson = [];
        $registersA=[];
        $registersD=[];
        $months = [
                    '01'=>'enero', '02'=>'febrero', '03'=>'marzo', 
                    '04'=>'abril', '05'=>'mayo', '06'=>'junio', 
                    '07'=>'julio', '08'=>'agosto', '09'=>'septiembre',
                    '10'=>'octubre', '11'=>'noviembre', '12'=>'diciembre'
                ];

        if ($cedula!=='')
        {
            $find = DB::table('rhnomficha')
                        ->select('fic_id as ficha', 'fic_cedula as cedula', 'fic_tnombre as nombrec')
                        ->where('fic_cedula','=', $cedula)
                        ->first();

            if (!empty($find->ficha))
            {
                $allData = DB::table('vw_rhnommvd')
                            ->select(DB::raw('vw_rhnommvd.fic_id, vw_rhnommvd.fic_cedula, vw_rhnommvd.per_ficha, vw_rhnommvd.rub_tipo, vw_rhnommvd.rub_codigo, vw_rhnommvd.rub_monto, vw_rhnommvd.nom_codigo, vw_rhnommvd.nom_desde, vw_rhnommvd.nom_hasta, vw_rhnommvd.nom_fecha, vw_rhnommvd.niv_codigo, rhnommvdkp.kon_numerico'))
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
                            ->where(DB::raw('date_part(\'year\', vw_rhnommvd.nom_hasta)'), '=', $periodo)
                            ->where('vw_rhnommvd.fic_id', '=', $find->ficha)
                            ->get();

                foreach ($allData as $data)
                {
                    $date = new Carbon($data->nom_hasta);
                    if ($data->rub_tipo === 'A')
                    {
                        $value = $registersA[$months[$date->format('m')]] ?? 0;
                        $registersA[$months[$date->format('m')]] = $value + $data->rub_monto;
                        $dataJson['asignaciones'] = $registersA;
                    }
                }
                $dataJson['asignaciones']['total'] = 0;
                foreach ($dataJson['asignaciones'] as $key => $asignacion)
                {
                    $num = $dataJson['asignaciones'][$key];
                    $dataJson['asignaciones']['total'] += $num;
                    $str_num = number_format($num, 2, ',', '.');
                    $dataJson['asignaciones'][$key] = $str_num;
                }
                $dataJson['asignaciones']['periodo'] = $periodo;
                $dataJson['asignaciones']['nombre'] = $find->nombrec;
                $dataJson['asignaciones']['cedula'] = $find->cedula;
                return  response($dataJson, 200);
            }
        }
        return  response($dataJson, 201);
    }
}