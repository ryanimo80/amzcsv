@extends('welcome')
@section('content')

{{ Form::open(array('url'=>url()->current(), 'method'=>'POST')) }}
	
	<div class="field">	
		<label class="label">Select a brand</label>
		<div class="select">
			{{ Form::select('select_brand', $brand_list, '', ['placeholder'=>'Select brand']) }}
		</div>
		<input type="hidden" value="<?php echo $selectedIDs ?? '' ?>" name="selectedIDs">
		<input type="hidden" value="{{$profile_id ?? ''}}" name="profile_id">
		<input type="submit" name="{{$action_name}}" value="Export" class="button"/>
	</div>

{{ Form::close() }}

@endsection