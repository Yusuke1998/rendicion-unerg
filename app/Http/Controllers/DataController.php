<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;


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
                $data = DB::table('vw_rhnommvd')
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
                return  response([
                    'status'    =>  'success',
                    'data'      =>  $data,
                ], 200);
            }
        }
        return  response([
                    'status'    =>  'success',
                    'data'      =>  '[]',
                ], 200);
    }
}
