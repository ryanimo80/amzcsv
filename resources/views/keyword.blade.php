@extends('welcome')
@section('content')

<div class="box">
<div class="columns">
	<div class="column">
		{{ Form::open(array('url'=>'/amz/keyword/'.$id)) }}
			<div class="field">	
				<label class="label">Main keyword</label>
				<div class="control">
					<input type="text" name="main_keyword" class="input" value="{{ $keyword->main_keyword }}"/>
				</div>
			</div>
			<div class="field">	
				<label class="label">Bullet point 1</label>
				<div class="control">
					<textarea name="bulletpoint_1" v-model='bulletpoint_1' class="textarea" rows="2">{{ $keyword->bulletpoint_1 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Bullet point 2</label>
				<div class="control">
					<textarea name="bulletpoint_2" v-model='bulletpoint_2' class="textarea" rows="2">{{ $keyword->bulletpoint_2 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Bullet point 3</label>
				<div class="control">
					<textarea name="bulletpoint_3" v-model='bulletpoint_3' class="textarea" rows="2">{{ $keyword->bulletpoint_3 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Bullet point 4</label>
				<div class="control">
					<textarea name="bulletpoint_4" v-model='bulletpoint_4' class="textarea" rows="2">{{ $keyword->bulletpoint_4 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Bullet point 5</label>
				<div class="control">
					<textarea name="bulletpoint_5" v-model='bulletpoint_5' class="textarea" rows="2">{{ $keyword->bulletpoint_5 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Search term 1</label>
				<div class="control">
					<textarea name="searchterm_1" v-model='searchterm_1' class="textarea" rows="2">{{ $keyword->searchterm_1 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Search term 2</label>
				<div class="control">
					<textarea name="searchterm_2" v-model='searchterm_2' class="textarea" rows="2">{{ $keyword->searchterm_2 }}</textarea>
				</div>
			</div>	<div class="field">	
				<label class="label">Search term 3</label>
				<div class="control">
					<textarea name="searchterm_3" v-model='searchterm_3' class="textarea" rows="2">{{ $keyword->searchterm_3 }}</textarea>
				</div>
			</div>	<div class="field">	
				<label class="label">Search term 4</label>
				<div class="control">
					<textarea name="searchterm_4" v-model='searchterm_4' class="textarea" rows="2">{{ $keyword->searchterm_4 }}</textarea>
				</div>
			</div>	
			<div class="field">	
				<label class="label">Search term 5</label>
				<div class="control">
					<textarea name="searchterm_5" v-model='searchterm_5' class="textarea" rows="2">{{ $keyword->searchterm_5 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Description</label>
				<div class="control">
					<textarea name="description" v-model='description' class="textarea" rows="2">{{ $keyword->description }}</textarea>
				</div>
			</div>
			{{ Form::submit('Submit', ['class'=>'button is-primary', 'name'=>'submit']) }}
			{{ Form::submit('Save as New', ['class'=>'button is-primary', 'name'=>'create']) }}
		</form>			
	</div>
	<div class="column">
		<table class="table">
			<thead>
				<tr>
					<th><abbr title="Main keyword">Main keyword</abbr></th>
					<th><abbr title="Main keyword">Action</abbr></th>
				</tr>			
			</thead>
			<tfoot>
				<tr>
					<th><abbr title="Main keyword">Main keyword</abbr></th>
					<th><abbr title="Main keyword">Action</abbr></th>
				</tr>
			</tfoot>			
		<tbody>
		@foreach($keyword_list as $id=>$keyword)
			<tr>
				<td> <a href="/amz/keyword/{{$id}}">{{$keyword}}</a> </td>
				<td> <a href="/amz/keyword/delete/{{$id}}" onclick="return confirm('Are you sure?')">Delete</a> </td>
			</tr>
		@endforeach
		</tbody>
		</table>
	</div>
</div>

</div>

@endsection