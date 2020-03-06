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
				            	name="cedula"
              					class="input-group--focused"
				            	label="Cedula"
				            ></v-text-field>
				          </v-flex>
				        </v-layout>
					</v-card-text>
					<v-card-actions class="info text-xs-right">
	                	<v-btn
	                		primary dark
	                		:small="true"
	                		@click.prevent="search()">Buscar</v-btn>
	            	</v-card-actions>
				</v-card>
			</v-flex>
		</v-layout>

		<v-layout v-if="!showSearch" row mt5>
			<v-flex xs2>
				<p class="h4 text-center">CI: {{ asignaciones.cedula }}</p>
			</v-flex>
			<v-flex xs8>
				<p class="h4 text-center">NOMBRE: {{ asignaciones.nombre }}</p>
			</v-flex>
			<v-flex xs2>
				<p class="h5 text-center">PERIODO: {{ asignaciones.periodo }}</p>
			</v-flex>

			<v-flex xs12>
				<v-btn @click="pdf()" class="float-right btn btn-sm btn-warning">
					PDF
				</v-btn>
			</v-flex>
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
		        <v-subheader>{{ asignaciones.enero }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.febrero }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.marzo }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.abril }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.mayo }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.junio }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.julio }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.agosto }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.septiembre }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.octubre }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.noviembre }} Bs.</v-subheader>
		        <v-subheader>{{ asignaciones.diciembre }} Bs.</v-subheader>
		        <v-footer class="mt-5">TOTAL &nbsp<span>{{ asignaciones.total }} Bs.</span></v-footer>
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
			const ok = (await axios.post('/buscar-cedula',{'cedula':this.cedula}))
			if (ok.status==200)
			{
				this.showSearch = false;
				this.asignaciones = ok.data.asignaciones;
            	this.$alertify.success('Busqueda exitosa!')
				return;
			}else{
            	this.$alertify.warning('Sin resultados!')
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
								text: this.asignaciones.enero,
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
								text: this.asignaciones.febrero,
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
								text: this.asignaciones.marzo,
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
								text: this.asignaciones.abril,
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
								text: this.asignaciones.mayo,
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
								text: this.asignaciones.junio,
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
								text: this.asignaciones.julio,
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
								text: this.asignaciones.agosto,
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
								text: this.asignaciones.septiembre,
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
								text: this.asignaciones.octubre,
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
								text: this.asignaciones.noviembre,
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
								text: this.asignaciones.diciembre,
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					},
					{
						columns: [
							{
								text: 'TOTAL',
								alignment: 'left',
								style: 'columsubheader'
							},
							{
								text: this.asignaciones.total,
								alignment: 'left',
								style: 'columsubheader'
							}
						]
					}
			    ],
			    styles: {
					header: {
						fontSize: 15,
						bold: true,
						margin: [0, 0, 0, 10]
					},
					subheader: {
						fontSize: 12,
						bold: true,
						margin: [0, 10, 0, 5]
					},
					small:{
						fontSize: 8,
					},
					columheader: {
						fontSize: 12,
						bold: true,
						margin: [0, 10, 0, 5]
					},
					columsubheader: {
						fontSize: 11,
						bold: false,
						margin: [0, 10, 0, 5]
					}
				}
			}
			pdfMake.createPdf(dPaDtFa).open();
		},
		pdf()
		{
			pdfMake.vfs = pdfFonts.pdfMake.vfs;
			this.print(this.asignaciones);
		}
	}
}

</script>