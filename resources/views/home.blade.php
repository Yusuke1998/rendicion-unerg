@extends('layout')

@section('content')
	<h1>Hola Mundo</h1>
	<form method="POST" action="{{ url('/buscar-cedula') }}">
		<input type="text" name="cedula">
		<input type="submit" name="Buscar">
	</form>
@endsection