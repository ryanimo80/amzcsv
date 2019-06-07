@extends('welcome')
@section('content')

<br/>
{{ Form::open(array('url'=>'/amz/profile/'.@$current_profile->id)) }}
<div class="box">
	<div class="field">	
		<label class="label">Profile name:</label>
		<div class="control">
			<input type="text" name="name" class="input" v-model="name" value="{{ $current_profile->name }}" />
		</div>
	</div>	
	<div class="columns">
		<div class="column is-four-fifths">
	@forelse($clothing as $cl)
		<div class="columns">
			<div class="column">{{ $cl['title'] }}</div>
			<div class="column">
				<div class="field">	
					<label class="label">Print on</label>
					<div class="select">
						<?php 
							$print_location = get_object_vars(json_decode($current_profile->print_location)); 
							$print_available_location = array_keys($cl['print_location']);
							// array_walk($print_available_location, 'ucfirst');
						?>
						{{ Form::select("print_location[$cl[name]]", $print_available_location, @$print_location[$cl['name']], ['class'=>'select']) }}
					</div>
				</div>
			</div>	
			<div class="column">
				<div class="field">	
					<label class="label">Price</label>
					<div class="control">
						<?php $price = get_object_vars(json_decode($current_profile->price)); ?>
						<input type="text" name="price[{{$cl['name']}}]" class="input" value="{{ @$price[$cl['name']] }}"/>
					</div>
				</div>
			</div>
			<div class="column">
				
				<div class="columns">
				<?php 
					$current_color = get_object_vars(json_decode($current_profile->color)); 
					$current_color = isset($current_color[$cl['name']])?$current_color[$cl['name']]:array();
				?>
				@foreach($cl['color'] as $color)
					<div class="column">
						<label class="label" for="">{{ $color }}</label>
						<div id="" class="control has-background-{{ $color }}">
							<input <?php echo (in_array($color, $current_color))?'checked':'' ?> type="checkbox" name="color[{{$cl['name']}}][]" value="{{$color}}" />
						</div>
					</div>
				@endforeach
				</div>

			</div>
		</div>
	@empty
		empty
	@endforelse
	<div class="field">	
		<div class="control">
			<input type="submit" value="Save profile" class="button is-primary"/>
		</div>
	</div>

		</div>
		<div class="column">
			<table class="table">
				<thead>
					<tr>
						<td>Profile name</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
@foreach($profile_list as $k=>$v)
					<tr>
						<td><a href="/amz/profile/{{ $v->id }}">{{ $v->name }}</a></td>
						<td><a href="/amz/profile/delete/{{ $v->id }}" onclick="return confirm('Are you sure?')">Delete</a></td>
@endforeach					</tr>

				</tbody>
			</table>
		</div>
	</div>
</div>
{{ Form::close() }}
@endsection