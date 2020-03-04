SELECT 	vw_rhnommvd.per_ficha, 
	vw_rhnommvd.*, 
	rhnommvdkp.kon_numerico,  
	date_part('month', 
	vw_rhnommvd.nom_hasta) mes_hasta, 
	CASE WHEN vw_rhnommvd.nom_fecha<'2018.08.20' THEN vw_rhnommvd.rub_monto/100000 ELSE vw_rhnommvd.rub_monto END monto_rub
FROM vw_rhnommvd
JOIN rhnomrubgrp ON vw_rhnommvd.rub_codigo=rhnomrubgrp.rub_codigo and (rhnomrubgrp.grp_codigo=6 or rhnomrubgrp.grp_codigo=20)
left JOIN rhnommvdkp ON vw_rhnommvd.emp_codigo = rhnommvdkp.emp_codigo AND vw_rhnommvd.nom_codigo = rhnommvdkp.nom_codigo AND vw_rhnommvd.per_ficha = rhnommvdkp.per_ficha and rhnommvdkp.kon_codigo=22

where vw_rhnommvd.emp_codigo=1
--and vw_rhnommvd.nom_desde >='[nom_desde]'
--and vw_rhnommvd.nom_hasta <='[nom_hasta]'
and date_part('year', vw_rhnommvd.nom_hasta)=2019
and vw_rhnommvd.nom_clase=0
and vw_rhnommvd.rub_monto > 0
and vw_rhnommvd.fic_id=1005
order by vw_rhnommvd.fic_cedula, vw_rhnommvd.nom_hasta,vw_rhnommvd.nom_codigo,vw_rhnommvd.rub_tipo