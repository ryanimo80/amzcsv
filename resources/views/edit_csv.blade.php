@extends('welcome')
@section('content')

<div class="box">
<div class="columns">
	<div class="column">
		{{ Form::open(array('url'=>'/amz/edit/'.request()->id)) }}
			<div class="field">	
				<label class="label">Name</label>
				<div class="control">
					<input type="text" name="item_name" class="input" value="{{ $csv->item_name }}"/>
				</div>
			</div>
			<div class="field">	
				<label class="label">Bullet point 1</label>
				<div class="control">
					<textarea name="bulletpoint_1" v-model='bulletpoint_1' class="textarea" rows="2">{{ $csv->bulletpoint_1 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Bullet point 2</label>
				<div class="control">
					<textarea name="bulletpoint_2" v-model='bulletpoint_2' class="textarea" rows="2">{{ $csv->bulletpoint_2 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Bullet point 3</label>
				<div class="control">
					<textarea name="bulletpoint_3" v-model='bulletpoint_3' class="textarea" rows="2">{{ $csv->bulletpoint_3 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Bullet point 4</label>
				<div class="control">
					<textarea name="bulletpoint_4" v-model='bulletpoint_4' class="textarea" rows="2">{{ $csv->bulletpoint_4 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Bullet point 5</label>
				<div class="control">
					<textarea name="bulletpoint_5" v-model='bulletpoint_5' class="textarea" rows="2">{{ $csv->bulletpoint_5 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Search term 1</label>
				<div class="control">
					<textarea name="searchterm_1" v-model='searchterm_1' class="textarea" rows="2">{{ $csv->searchterm_1 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Search term 2</label>
				<div class="control">
					<textarea name="searchterm_2" v-model='searchterm_2' class="textarea" rows="2">{{ $csv->searchterm_2 }}</textarea>
				</div>
			</div>	<div class="field">	
				<label class="label">Search term 3</label>
				<div class="control">
					<textarea name="searchterm_3" v-model='searchterm_3' class="textarea" rows="2">{{ $csv->searchterm_3 }}</textarea>
				</div>
			</div>	<div class="field">	
				<label class="label">Search term 4</label>
				<div class="control">
					<textarea name="searchterm_4" v-model='searchterm_4' class="textarea" rows="2">{{ $csv->searchterm_4 }}</textarea>
				</div>
			</div>	
			<div class="field">	
				<label class="label">Search term 5</label>
				<div class="control">
					<textarea name="searchterm_5" v-model='searchterm_5' class="textarea" rows="2">{{ $csv->searchterm_5 }}</textarea>
				</div>
			</div>
			<div class="field">	
				<label class="label">Description</label>
				<div class="control">
					<textarea name="description" v-model='description' class="textarea" rows="2">{{ $csv->description }}</textarea>
				</div>
			</div>
			{{ Form::submit('Update', ['class'=>'button is-primary', 'name'=>'submit']) }}
		</form>			
	</div>
 
</div>

</div>

@endsection