<template>
	<div>
		<v-layout>
			<v-flex xs12>
				<v-card v-if="showSearch"
					class="mx-auto mt-5"
					:loading="loading"
					:shaped="true">
					<v-card-text>
						<p class="text-right">Este buscador te permite obtener información por mes de tu sueldo asi como su total.</p>
						<v-layout row>
				          <v-flex xs4>
				            <v-subheader>
				            	<v-container fluid>
						        	<img class="image" v-bind:src="'unerg-logo.png'" alt="UNERG" width="100%" height="100%">
						        </v-container>
				            </v-subheader>
				          </v-flex>
				          <v-flex xs8>
				            <v-text-field
				            	@keyup.enter="search()"
				            	v-model="cedula"
				            	label="Cedula"
				            	required
				            	hint="Debes ingresar 7 o 8 digitos!"
				            	min="7"
				            	max="8"
				            ></v-text-field>
				          </v-flex>
				        </v-layout>
					</v-card-text>
					<v-card-actions class="text-xs-right">
						<v-flex xs12>
		                	<v-btn
		                		class="float-right cyan"
		                		:small="true"
		                		@click.prevent="search()">Buscar</v-btn>
		                </v-flex>
	            	</v-card-actions>
				</v-card>
			</v-flex>
		</v-layout>

		<v-layout v-if="!showSearch" row mt5>
			<v-flex xs12 class="py-5">
				<v-btn 
					@click="reset()" 
					class="float-left teal">
					VOLVER</v-btn>
				<v-btn 
					@click="pdf()" 
					class="float-right indigo">
					PDF</v-btn>
			</v-flex>
			
			<v-layout row class="py-2">
				<v-flex xs2>
					<p class="h4 text-center">CI: {{ asignaciones.cedula }}</p>
				</v-flex>
				<v-flex xs8>
					<p class="h4 text-center">NOMBRE: {{ asignaciones.nombre }}</p>
				</v-flex>
				<v-flex xs2>
					<p class="h5 text-center">PERIODO: {{ asignaciones.periodo }}</p>
				</v-flex>
			</v-layout>
		</v-layout>

		<v-layout v-if="!showSearch" row mt5>
		    <v-flex xs6>
		      <v-card class="grid">
		      	<v-card-title primary-title>
		          <div class="h5 text-center">MES</div>
		        </v-card-title>
		        <v-subheader>Enero</v-subheader>
		        <v-subheader>Febrero</v-subheader>
		        <v-subheader>Marzo</v-subheader>
		        <v-subheader>Abril</v-subheader>
		        <v-subheader>Mayo</v-subheader>
		        <v-subheader>Junio</v-subheader>
		        <v-subheader>Julio</v-subheader>
		        <v-subheader>Agosto</v-subheader>
		        <v-subheader>Septiembre</v-subheader>
		        <v-subheader>Octubre</v-subheader>
		        <v-subheader>Noviembre</v-subheader>
		        <v-subheader>Diciembre</v-subheader>
		      </v-card>
		    </v-flex>

		    <v-flex xs6>
		      <v-card class="grid">
		      	<v-card-title primary-title>
		          <div class="h5 text-center">REMUNERACIÓN</div>
		        </v-card-title>
		        <v-subheader>{{ asignaciones.enero || 0 }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.febrero || 0 }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.marzo || 0 }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.abril || 0 }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.mayo || 0 }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.junio || 0 }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.julio || 0 }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.agosto || 0 }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.septiembre || 0 }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.octubre || 0 }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.noviembre || 0 }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.diciembre || 0 }} Bs.</v-subheader>
		        <v-footer class="mt-5">TOTAL: &nbsp<span>{{ asignaciones.total || 0 }} Bs.</span></v-footer>
		      </v-card>
		    </v-flex>
		</v-layout>
	</div>
</template>

<script>
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
export default {
	name:'home',

	created()
	{
		this.dateToday();
	},

	data()
	{
		return {
			today:'',
			showSearch:true,
			loading:false,
			cedula:'',
			asignaciones:[]
		}
	},

	methods:{
		// Ejecutando consulta
		async search()
		{
			this.loading = true;
			try {
				const ok = (await axios.post('/buscar-cedula',{'cedula':this.cedula}))
				if (ok.status==200)
				{
					this.showSearch = false;
					this.asignaciones = ok.data.asignaciones;
	            	this.$alertify.success('Busqueda exitosa!')
					return;
				}
				else if (ok.status==201)
				{
	            	this.$alertify.warning('Sin resultados!')
				}
			}
			catch(e)
			{
            	this.$alertify.error('Solo puedes ingresar números!')
				console.log(e)
			}
			this.loading = false;
			this.showSearch = true;
		},
		dateToday()
		{
			let date = new Date().toISOString().slice(0,10)
			this.today = date.split('-')[2]+'-'+date.split('-')[1]+'-'+date.split('-')[0]
		},
		// Generando pdf
		print(data)
		{
			var dPaDtFa = {
			    content: [
		    		{
						text: this.today,
						width: '*',
						alignment: 'right',
						style: 'small'
					},
			        { 
			        	text: 'UNIVERSIDAD NACIONAL EXPERIMENTAL ROMULO GALLEGOS',
						width: '*',
						alignment: 'center',
						style: 'header'
			        },
					{	columns: [
							{
								text: 'CI: '+data.cedula,
								width: 100,
								alignment: 'center',
								style: 'leyend'
							},
							{
								text: 'NOMBRE: '+data.nombre,
								width: '*',
								alignment: 'center',
								style: 'leyend'
							},
							{
								text: 'PERIODO: '+data.periodo,
								width: 100,
								alignment: 'center',
								style: 'leyend'
							}
						]
					},
			    	{
						text: 'ARC',
						width: '*',
						alignment: 'center',
						style: 'subheader'
					},
			        {
				        columns: [
							{
								text: 'MES',
								alignment: 'left',
								style: 'columheader'
							},
							{
								text: 'REMUNERACIÓN',
								alignment: 'left',
								style: 'columheader'
							}
						]
					},
					{
				        columns: [
							{
								text: 'ENERO',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.enero || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'FEBRERO',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.febrero || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'MARZO',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.marzo || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'ABRIL',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.abril || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'MAYO',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.mayo || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'JUNIO',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.junio || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'JULIO',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.julio || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'AGOSTO',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.agosto || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'SEPTIEMBRE',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.septiembre || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'OCTUBRE',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.octubre || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'NOVIEMBRE',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.noviembre || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'DICIEMBRE',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: data.diciembre || 0+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								
							},
							{
								text: 'TOTAL: '+data.total+' Bs.',
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					}
			    ],
			    styles: {
					header: {
						fontSize: 12,
						bold: true,
						margin: [0, 0, 0, 10]
					},
					subheader: {
						fontSize: 10,
						bold: true,
						margin: [0, 10, 0, 5]
					},
					leyend: {
						fontSize: 10,
						bold: false,
						margin: [0, 10, 0, 5]
					},
					small:{
						fontSize: 8,
					},
					columheader: {
						fontSize: 10,
						bold: true,
						margin: [0, 10, 0, 5]
					},
					columsubheader: {
						fontSize: 10,
						bold: false,
						margin: [0, 10, 0, 5]
					}
				}
			}
			pdfMake.createPdf(dPaDtFa).open();
		},
		// Imprimiendo pdf
		pdf()
		{
			pdfMake.vfs = pdfFonts.pdfMake.vfs;
			this.print(this.asignaciones);
		},
		reset()
		{
			this.showSearch 	= true
			this.loading 		= false
			this.cedula 		= ''
			this.asignaciones 	= []
		}
	}
}

</script>