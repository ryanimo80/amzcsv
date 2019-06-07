@extends('welcome')


@section('content')

		{{ Form::open(array('url'=>'/teezily/scan/')) }}
<div id="teezily_form">
<div class="field">	
	<label class="label">Links</label>
	<div class="control">
		{{ Form::textarea('links', '', ['class'=>'textarea', 'v-model'=>'links']) }}
	</div>
</div>

<div class="field is-grouped">	
	<div class="control">
		{{Form::button('Submit to Scan', ['class'=>'button is-dark', 'v-on:click'=>'teezily_scan'])}}
	</div>
	<div class="control">
		{{Form::submit('Download Photo', ['class'=>'button is-dark'])}}
	</div>
</div>
</div>
		{{ Form::close() }}
<script type="text/javascript">
	
	var vm = new Vue({
		el: '#teezily_form',
		data:{
			links:'',
			teezily_url:'https://www.teezily.com/'
		},
		methods:{
			teezily_scan:function(event){
				links = this.links.split('\n');
				for (var i = 0; i <= links.length-1; i++) {
					axios.post('/teezily/ajax_scan',{
							link: this.teezily_url+this.links[i],
						}
					)
					.then(
						response=>{
							// console.log(response);
						} 	
					)					
				}
			}
		}
	});

</script>
@endsection