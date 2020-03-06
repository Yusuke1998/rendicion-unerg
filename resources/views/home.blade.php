@extends('app')
@section('content')
	<div class="col-md-12 row">
		<form method="POST" action="{{ url('/buscar-cedula') }}">
			<div class="form-group col-md-10">
				<label>Cedula</label>
				<input class="form-control" type="text" name="cedula">
			</div>
			<div class="form-group col-md-2">
				<input class="btn btn-info btn-sm" type="submit" name="Buscar">
			</div>
		</form>
	</div>
@endsection