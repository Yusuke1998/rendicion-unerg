<?php

use Illuminate\Http\Request;

$router->get('/', function() use ($router) {
	return view('home');
});

$router->post('/buscar-cedula', function(Request $request) use ($router){
	$cedula = $request->cedula;
	if ($cedula!='') {

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
					 ->where('vw_rhnommvd.fic_id', '=', '1005')
                     // ->toSql();
                     ->get();
        dd($data);

	    return $router->app->version();
        // dd($data);

	 //    SELECT 	vw_rhnommvd.per_ficha, 
		// 	vw_rhnommvd.*, 
		// 	rhnommvdkp.kon_numerico
		// FROM vw_rhnommvd
		// JOIN rhnomrubgrp ON vw_rhnommvd.rub_codigo=rhnomrubgrp.rub_codigo and (rhnomrubgrp.grp_codigo=6 or rhnomrubgrp.grp_codigo=20)

		// left JOIN rhnommvdkp ON vw_rhnommvd.emp_codigo = rhnommvdkp.emp_codigo AND vw_rhnommvd.nom_codigo = rhnommvdkp.nom_codigo AND vw_rhnommvd.per_ficha = rhnommvdkp.per_ficha and rhnommvdkp.kon_codigo=22

		// where vw_rhnommvd.emp_codigo=1
		// --and vw_rhnommvd.nom_desde >='[nom_desde]'
		// --and vw_rhnommvd.nom_hasta <='[nom_hasta]'
		// and date_part('year', vw_rhnommvd.nom_hasta)=2019
		// and vw_rhnommvd.nom_clase=0
		// and vw_rhnommvd.rub_monto > 0
		// and vw_rhnommvd.fic_id=1005
		// order by vw_rhnommvd.fic_cedula, vw_rhnommvd.nom_hasta,vw_rhnommvd.nom_codigo,vw_rhnommvd.rub_tipo
/*
*/
		return view('data',compact('cedula'));
	}
	return view('error');
});
