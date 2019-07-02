@extends('welcome')


@section('content')

    <div class="container">
		<div class="box">

			{{ Form::open(['url'=>url()->current(), 'method'=>'POST']) }}
			<div class="field">	
				<label class="label">Select product</label>
				<div class="select">
			{{Form::select('select_product', $product, 
					!empty(optional($current_setting)->short_code) ? $current_setting->short_code: request()->get('select_product'),
					['placeholder'=>'Select product', 'class'=>'select'])}}
				</div>
			</div>

			<div class="field">	
				<label class="label">Size SKUs</label>
				<div class="control">
					<textarea name="size_sku" class="textarea" rows=10>{{optional($current_setting)->size_sku}}</textarea>
				</div>
			</div>

			<div class="field">	
				<label class="label">Color</label>
				<div class="select">
			{{Form::select('select_color', $color, 
					!empty(optional($current_setting)->color) ? $current_setting->color: request()->get('select_color'),
					['placeholder'=>'Select color', 'class'=>'select'])}}					
				</div>
			</div>

			{{Form::submit('Submit',['class'=>'button is-primary','name'=>'submit'])}}
		</div> 

		<div class="box">
			<div id="ccapp">
				<table class="table">
						<tr>
							<td><input type="checkbox" @click="selectAll" v-model="allSelected" /></td>
							<td>Product</td>
							<td>Color</td>
						</tr>

					    <tr v-for="row in cc_settings">
							<td>
								<div class="field">	
									<div class="control">
										<input type="checkbox" @click="select" v-model="IDs" :value="row.id" number />
									</div>
								</div>
							</td>
							<td><a :href="'/customcat/'+row.id">@{{ get_title(row.short_code) }} - @{{ row.short_code }}</a></td>
							<td>@{{ row.color }}</td>
						</tr>
					</table>
			</div>
		</div>
	</div>

<script type="text/javascript">
	var vm = new Vue({
		el: '#ccapp',
		data:{
			cc_settings:[
				@foreach ($customcat_sku_settings as $setting)
					{'id':'{{$setting->id}}', 'short_code':'{{$setting->short_code}}', 'color':'{{$setting->color}}'},
				@endforeach							
			],
			products:[
				@foreach ($product as $short_code=>$p)
					{'short_code':'{{$short_code}}', 'title':'{{$p}}' },
				@endforeach							
			],			
			IDs: [],
			allSelected: []
		},
		methods:{
			selectAll: function (isSelected) {
	            this.IDs = [];
	            if(this.allSelected==false)
	            	this.allSelected = true;
	           	else
	           		this.allSelected = false;
	            if (this.allSelected) {
	                for (row in this.cc_settings) {
	                    this.IDs.push(this.cc_settings[row].id.toString());
	                }
	            }
			},
	        select: function() {
	            this.allSelected = false;
	        },
	        get_title:function(short_code){
	        	for (var i = this.products.length - 1; i >= 0; i--) {
	        		if(this.products[i].short_code==short_code)
	        			return this.products[i].title
	        	}
	        }
		}
	});
</script>
@endsection