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
			{{ Form::submit('Update', ['class'=>'button is-primary', 'name'=>'updatekw']) }}
			{{ Form::submit('Delete', ['class'=>'button', 'name'=>'delete']) }}
		</form>			
	</div>
	<div class="column">

		{{ Form::open(array('url'=>url()->current(), 'enctype'=>"multipart/form-data")) }}

<label class="label">Upload a PNG:</label>
<div class="field file">
  <label class="file-label">
    <input class="file-input" type="file" name="file_png">
    <span class="file-cta">
      <span class="file-icon">
        <i class="fas fa-upload"></i>
      </span>
      <span class="file-label">
        Choose a file…
      </span>
    </span>
  </label>
</div>

<div class="field">
	<div class="label">Profile</div>
	<div class="select" id="select">
			{{ Form::select('profile_id', $profile_list, $csv->profile_id, ['placeholder'=>'Select profile']) }}
	</div>
</div>

<div class="field">	
	<div class="control">
		<input type="submit" value="Generate Mockup" name="updatemk" class="button is-primary"/>
	</div>
</div>

<div class="field">
	<div class="label">Marketplace:</div>
	<div class="select" id="select">
			{{ Form::select('marketplace', $marketplace, null, ['placeholder'=>'Select marketplace']) }}
	</div>
</div>

<div class="field">	
	<div class="control">
		<input type="submit" value="Generate Mockup & Export CSV" name="genmkcsv" class="button is-primary"/>
	</div>
</div>


		{{ Form::close() }}

<hr/>

		{{ Form::open(array('url'=>url()->current(), 'enctype'=>"multipart/form-data")) }}
<div class="field">	
	<label class="label">New SKU:</label>
	<div class="control">
		<input type="text" name="new_sku" class="input" value="{{ $csv->item_sku }}"/>
	</div>
</div>
<div class="field">	
	<div class="control">
		<input type="submit" value="Change SKU" name="updatesku" class="button"/>
	</div>
</div>
		{{ Form::close() }}
<hr/>


		<strong>Current profile</strong>: {{ $current_profile->name }}
<hr/>
		<?php foreach (json_decode($csv->mockup, true) as $type => $color_mockup): ?>
			<?php foreach ($color_mockup as $color => $mockup): ?>
				<?php if(is_mug_type($type)){ ?>
				<a target="_blank" href="<?php echo explode("|", $mockup)[0]; ?>"><img src="<?php echo explode("|", $mockup)[0]; ?>" width="75" /></a>
				<a target="_blank" href="<?php echo explode("|", $mockup)[1]; ?>"><img src="<?php echo explode("|", $mockup)[1]; ?>" width="75" /></a>
				<?php }else{ ?>
				<a target="_blank" href="<?php echo $mockup; ?>"><img src="<?php echo $mockup; ?>" width="75" /></a>					
				<?php } ?>
			<?php endforeach ?>
		<?php endforeach ?>
	</div>
 
</div>

</div>

@endsection