@extends('welcome')
@section('content')

{{ Form::open(array('url'=>url()->current(), 'method'=>'POST')) }}
<div class="columns">
	<div class="column is-three-quarters">
		<div class="field">	
			<div class="control">
				<input type="text" name="brand_name" class="input" placeholder="Enter Brand name" />
			</div>
		</div>
	</div>
	<div class="column">
		{{Form::submit('Save', ['class'=>'button is-dark is-primary'])}}
	</div>
</div>

{{ Form::close()}}

<div id="brnapp">
	<table class="table">
		<tr>
			<td>Brand name</td>
		</tr>
	    <tr v-for="brand in brand_list">
			<td>
				<a :href="'{{url()->current()}}/'+brand.id">@{{ brand.brand_name }}</a>
			</td>
		</tr>		
	</table>
</div>
<script type="text/javascript">
	var vm = new Vue({
		el:'#brnapp',
		data:{
			brand_list: [
			@foreach ($brand_list as $brand)
				{'id':'{{$brand->id}}', 'brand_name':'{{$brand->brand_name}}'},
			@endforeach
			],
		},
		methods:{

		}
	});
</script>

@endsection