@extends('welcome')
@section('content')
{{ Form::open(array('url'=>url()->current().'/update', 'method'=>'POST')) }}
	
	<div class="field">	
		<div class="field">	
			<label class="label">Brand name</label>
			<div class="control">
				{{ Form::text('brand_name', $brand->brand_name, ['class'=>'input']) }}
			</div>
		</div>
		<input type="submit" name="brandupdate" value="Update" class="button is-primary"/>
		<input type="submit" name="branddel" value="Delete" class="button"/>
	</div>

{{ Form::close() }}

@endsection