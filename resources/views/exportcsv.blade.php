@extends('welcome')
@section('content')

<div class="container">
<div class="box">
{{ Form::open(array('url'=>'/amz/export')) }}
<div id="tblApp">
			<div class="field is-grouped">	
				<div class="control">
					<label class="label">Export to Marketplace</label>
					<div class="select">
						{{ Form::select('marketplace', $marketplace, null, ['placeholder'=>'Select marketplace'])  }}
					</div>
				</div>
				<div class="control">
					<label class="label">Profile Filter</label>
					<div class="select">
						{{ Form::select('filter', $filter, request()->filter, ['placeholder'=>'Select filter'])  }}
					</div>
				</div>	
				<div class="control">
					<label class="label">Paging</label>
					<div class="select">
						{{ Form::select('number_per_page', $number_per_page, request()->get('number_per_page') )  }}
					</div>
				</div>	
				<div class="control">
					<label class="label">Sort</label>
					<div class="select">
						{{ Form::select('sort_by', $sort_by,  request()->sort_by)  }}
					</div>
				</div>	
				<div class="control">
					<label class="label">Keyword:</label>
					<div class="text">
						{{ Form::text('keyword', request()->keyword, ['class'=>'input'] )  }}
					</div>
				</div>
				<div class="control">	
					<label class="label">Search</label>
					<div class="control">
						<input type="submit" name="search" class="button" value="Apply filter"/>
					</div>
				</div>
				<div class="control">	
					<label class="label">Export</label>
					<div class="control">
						<input type="submit" name="export" class="button is-primary" value="Export"/>
					</div>
				</div>
			</div>

	<table class="table">
		<tr>
			<td><input type="checkbox" @click="selectAll" v-model="allSelected" /></td>
			<td>SKU</td>
			<td>Title</td>
			<td>Profile</td>
			<td>Created at</td>
		</tr>

	    <tr v-for="csv in csvdata">
			<td>
				<div class="field">	
					<div class="control">
						<input type="checkbox" v-model="csvIDs" @click="select" :value="csv.id" number />
					</div>
				</div>
			</td>
			<td><a :href="'/amz/edit/'+csv.id">@{{ csv.sku }}</a></td>
			<td>@{{ csv.title }}</td>
			<td>@{{ csv.p_name }}</td>
			<td>@{{ csv.created_at }}</td>
		</tr>
	</table>
<input type="hidden" :value="csvIDs" name="selectedIDs"/>
{{ $csvdata->appends(['sort' => 'id'])->links('nav') }}
{{ Form::close() }}

</div></div>

</div>

<script type="text/javascript">
	var vm = new Vue({
		el:'#tblApp',
		data:{
			csvdata: [
			@foreach ($csvdata as $csv_row)
				{'id':'{{$csv_row->id}}', 'sku':'{{$csv_row->item_sku}}', 'title':'{{$csv_row->item_name}}', 'p_name':'{{$csv_row->p_name}}', 'created_at':'{{$csv_row->created_at}}'},
			@endforeach
			],
        selected: [],
        allSelected: false,
        csvIDs: []
		},
		methods:{
			selectAll: function (isSelected) {
	            this.csvIDs = [];
	            if(this.allSelected==false)
	            	this.allSelected = true;
	           	else
	           		this.allSelected = false;
	            if (this.allSelected) {
	                for (csv in this.csvdata) {
	                    this.csvIDs.push(this.csvdata[csv].id.toString());
	                }
	            }
			},
	        select: function() {
	            this.allSelected = false;
	        }
		}
	});
</script>
@endsection