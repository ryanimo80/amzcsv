@extends('welcome')


@section('content')

    <div class="container">



<div class="box">
<br/>

{{ Form::open(array('url'=>'/amz/uploads/',  'enctype'=>"multipart/form-data")) }}
		<div class="field">
		  <label class="label"> Upload PNGs:
		</label>
		  <div class="control">
			<div class="file is-boxed">
			  <label class="file-label">
				{{ Form::file('pngs[]', ['class'=>'file-input', 'multiple'=>"multiple"]) }}
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
		  </div>
		</div>

		<div class="field">
			<div class="label">Keyword</div>
			<div class="select" id="select">
					{{ Form::select('kw_group_name', $kws_group_name, isset($keywords->id)?$keywords->id:null, ['placeholder'=>'Select keyword']) }}
			</div>
		</div>

		<div class="field">
			<div class="label">Profile</div>
			<div class="select" id="select">
					{{ Form::select('profile_id', $profile_list, isset($profile->id)?$profile->id:null, ['placeholder'=>'Select profile']) }}
			</div>
		</div>

<!-- 			<label class="label" for="testmockup">Test Generate Mockup</label>
			<input type="checkbox" name="testmockup" id="testmockup" class="checkbox"/>
 -->		
		<div class="field">
			<div class="control">
			{{Form::submit('Upload', ['class'=>'button is-dark is-primary'])}}
			</div>
		</div>

{{ csrf_field() }} 
{{ Form::close()}}
</div> 

<br/>

@isset($files)
@foreach ($files as $file)

<div id="mainapp{{ $loop->iteration }}" class="box">
{{ Form::open(array('url'=>'/amz/',  'enctype'=>"multipart/form-data", '')) }}
<input type="hidden" name="selected_profile" v-model="selected_profile" value="<?php echo $profile->name ?>"/>
<input type="hidden" name="filepng" v-model="filepng" value="<?php echo $file ?>" />		
<input type="hidden" name="mockup_url" v-model="mockup_url" value="" />		
<article class="message is-warning" v-if="isError">
  <div class="message-body">
  	@{{ error_message }}
  </div>
</article>
<article class="message is-success" v-if="isSuccess">
  <div class="message-body">
  	<div v-html="success_message">
  		@{{ success_message }}	
  	</div>
  </div>
</article>
<div class="columns">
	<div class="column is-one-fifth has-background-black has-text-white has-text-centered	">
		<img src="<?php echo url($file) ?>" width=216/><br/>
	</div>
	<div class="column">
		
		<div class="columns">

	<div class="column">
<!--
		<div class="field">
		  <label class="label">{{ Form::label('main_keyword', 'Main keyword:') }}</label>
		  <div class="control">
		    {{ Form::text('main_keyword', '', ['class'=>'input', 'v-model'=>'main_keyword']) }}
		  </div>
		</div>
-->
<div class="columns">
	<div class="column">
<div class="field">
		  <label class="label">{{ Form::label('title', 'Title:') }}</label>
		  <div class="control">
		  	<?php 
		  		$fileinfo = pathinfo(public_path().'/'.$file);
		  		$fileinfo_list = explode('_',$fileinfo['filename']);
		  		$design_id = optional($fileinfo_list)[0];
		  		$title = optional($fileinfo_list)[1];
		  		$keyword = optional($fileinfo_list)[2];
		  		$keyword1 = optional($fileinfo_list)[3];
		  		$keyword2 = optional($fileinfo_list)[4];
		  	?>
			{{ Form::text('title', '', ['class'=>'input', 'v-model'=>'title']) }}
		  </div>
</div>
		
	</div>

	
	<div class="column">
<div class="field">
  	<label class="label">Keywords:</label>
	<div class="control">
		<div class="select" id="select">
		{{ Form::select('kw_group_name', $kws_group_name, isset($keywords->id)?$keywords->id:null, ['v-on:change'=>'signalChange']) }}
		</div>
	</div>	
</div>
	</div>



	<div class="column">
		<div class="field">
  			<label class="label">Action:</label>
			<div class="control">
				<div class="submit">
					{{Form::button('Submit to queue', ['class'=>'button is-dark', 'v-on:click'=>'save_data'])}}
				</div>
			</div>
		<!--
			<div class="control">
			{{Form::button('Save keywords', ['class'=>'button is-dark', 'v-on:click'=>'save_keywords'])}}
			</div>
		-->
		</div>		

	</div>
	<div class="column">
		<progress v-if="loading" class="progress is-small is-primary" max="100">15%</progress>
	</div>
</div>


<div class="columns">
	<div class="column">
		<div class="field">
			<div class="field">
		  <label class="label">	Design ID: </label>
		  <div class="control">
		    	{{ Form::text('designid', '', ['class'=>'input', 'v-model'=>'designid']) }}
		  </div>
		  	</div>
		 </div>		
	</div>
	<div class="column">
		<div class="field">
			<div class="field">
		  			<label class="label">Month:</label>
				  <div class="select">
						{{ Form::select('month', [1,2,3,4,5,6,7,8,9,10,11,12], $month_index, ['v-model'=>'design_month']) }}
				  </div>
		  	</div>
		</div>
	</div>
	<div class="column">
<div class="field">	
	<label class="label">Keyword</label>
	<div class="control">
		<input type="text" name="keyword" class="input" v-model="keyword" />
	</div>
</div>		
	</div>
	<div class="column">
<div class="field">	
	<label class="label">Receiver</label>
	<div class="control">
		<input type="text" name="receiver" class="input" v-model="receiver" />
	</div>
</div>		
	</div>
	<div class="column">
<div class="field">	
	<label class="label">Interests</label>
	<div class="control">
		<input type="text" name="interest" class="input" v-model='interest'/>
	</div>
</div>		
	</div>	
</div>


	</div>
</div>

	<div class="columns">
		<div class="column">
<div class="field">
  <label class="label">	{{ Form::label('bulletpoint1', 'Bullet point 1:') }}
</label>
  <div class="control">
	{{ Form::textarea('bulletpoint1', '', ["rows" => 3, 'class'=>'textarea', 'v-model'=>'bulletpoint1']) }}
  </div>
</div>

		</div>

		<div class="column">
<div class="field">
  <label class="label">	{{ Form::label('bulletpoint2', 'Bullet point 2:') }}
</label>
  <div class="control">
	{{ Form::textarea('bulletpoint2', "", ["rows" => 3, 'class'=>'textarea', 'v-model'=>'bulletpoint2']) }}
  </div>
</div>

		</div>
		<div class="column">
<div class="field">
  <label class="label">	{{ Form::label('bulletpoint3', 'Bullet point 3:') }}
</label>
  <div class="control">
	{{ Form::textarea('bulletpoint3', "", ["rows" => 3, 'class'=>'textarea', 'class'=>'textarea', 'v-model'=>'bulletpoint3']) }}
  </div>
</div>
		</div>
		<div class="column">
<div class="field">
  <label class="label">	{{ Form::label('bulletpoint4', 'Bullet point 4:') }}
</label>
  <div class="control">
	{{ Form::textarea('bulletpoint4', "", ["rows" => 3, 'class'=>'textarea', 'v-model'=>'bulletpoint4']) }}
  </div>
</div>
		</div>
		<div class="column">
<div class="field">
  <label class="label">	{{ Form::label('bulletpoint5', 'Bullet point 5:') }}
</label>
  <div class="control">
	{{ Form::textarea('bulletpoint5', "", ["rows" => 3, 'class'=>'textarea', 'v-model'=>'bulletpoint5']) }}
  </div>
</div>
	</div>
</div>

	<div class="columns">
		<div class="column">
<div class="field">
  <label class="label">	{{ Form::label('search_term1', 'Search Term 1:') }}
</label>
  <div class="control">
	{{ Form::textarea('search_term1', "", ["rows" => 1, 'class'=>'textarea', 'v-model'=>'search_term1']) }}
  </div>
</div>
		</div>
		<div class="column">
<div class="field">
  <label class="label">	{{ Form::label('search_term2', 'Search Term 2:') }}
</label>
  <div class="control">
	{{ Form::textarea('search_term2', "", ["rows" => 1, 'class'=>'textarea', 'v-model'=>'search_term2']) }}
  </div>
</div>

		</div>
		<div class="column">
<div class="field">
  <label class="label">	{{ Form::label('search_term3', 'Search Term 3:') }}
</label>
  <div class="control">
	{{ Form::textarea('search_term3', "", ["rows" => 1, 'class'=>'textarea', 'v-model'=>'search_term3']) }}
  </div>
</div>
		</div>
		<div class="column">
<div class="field">
  <label class="label">	{{ Form::label('search_term4', 'Search Term 4:') }}
</label>
  <div class="control">
	{{ Form::textarea('search_term4', "", ["rows" => 1, 'class'=>'textarea', 'v-model'=>'search_term4']) }}
  </div>
</div>
		</div>
		<div class="column">
<div class="field">
  <label class="label">	{{ Form::label('search_term5', 'Search Term 5:') }}
</label>
  <div class="control">
	{{ Form::textarea('search_term5', "", ["rows" => 1, 'class'=>'textarea', 'v-model'=>'search_term5']) }}
  </div>
</div>
		</div>
	</div>

	</div>
</div>
<div class="field">
  <label class="label">Description</label>
  <div class="control">
	{{ Form::textarea('description', "", ["rows" => 2, 'class'=>'textarea', 'v-model'=>'description']) }}
  </div>
</div>

{{ csrf_field() }} 
{{ Form::close()}}
</div>
<br/>

	<script type="text/javascript">
	var vm = new Vue({
		el: "#mainapp{{ $loop->iteration }}",
		data:{
			title: '<?php echo $title;?>',
			designid: '<?php echo extract_numer($design_id) ?>',
			design_month: <?php echo $month_index ?>,
			main_keyword: '<?php echo $keyword ?>',
			bulletpoint1: `<?php echo $keywords->bulletpoint_1 ?>`,
			bulletpoint2: `<?php echo $keywords->bulletpoint_2 ?>`,
			bulletpoint3: `<?php echo $keywords->bulletpoint_3 ?>`,
			bulletpoint4: `<?php echo $keywords->bulletpoint_4 ?>`,
			bulletpoint5: `<?php echo $keywords->bulletpoint_5 ?>`,
			search_term1: `<?php echo $keywords->searchterm_1 ?>`,
			search_term2: `<?php echo $keywords->searchterm_2 ?>`,
			search_term3: `<?php echo $keywords->searchterm_3 ?>`,
			search_term4: `<?php echo $keywords->searchterm_4 ?>`,
			search_term5: `<?php echo $keywords->searchterm_5 ?>`,
			description: `<?php echo $keywords->description ?>`,
			filepng:'<?php echo $file ?>',
			selected_profile:'<?php echo $profile->id ?>',
			mockup_url:'',
			loading: false,
			success_message:'',
			error_message:'',
			isError: false,
			isSuccess: false,
			keyword:'<?php echo $keyword ?>',
			receiver:'<?php echo $keyword1 ?>',
			interest:'<?php echo $keyword2 ?>',
		},
		methods:{
			save_data:function(event){
				this.loading = true;	
				this.isError = false;
				axios.post("/amz/savecsvrow",{
					_token: "{{ csrf_token() }}",
					selected_profile: this.selected_profile,
					item_name: this.title,
					kw_group_name: this.kw_group_name,
					design_id: this.designid,
					design_month: this.design_month,
					bulletpoint_1: this.bulletpoint1,
					bulletpoint_2: this.bulletpoint2,
					bulletpoint_3: this.bulletpoint3,
					bulletpoint_4: this.bulletpoint4,
					bulletpoint_5: this.bulletpoint5,
					searchterm_1: this.search_term1,
					searchterm_2: this.search_term2,
					searchterm_3: this.search_term3,
					searchterm_4: this.search_term4,
					searchterm_5: this.search_term5,
					description: this.description,
					keyword: this.keyword,
					receiver: this.receiver,
					interest: this.interest,
					filepng: this.filepng

				})
				.then(response=>{
					console.log(response);
					this.loading = false;
					data = response["data"];
					if(data['data']!=null){
						this.error_message = data['data'];
						if(this.error_message['errorInfo']!=null){
							this.error_message = this.error_message['errorInfo'];
						}
						this.isError = true;
					}else{
						this.success_message = "Data saved successfully! ";
						this.success_message += "<a target='_blank' href='/amz/edit/"+response["data"]['csv_id']+"'>View</a>";
						this.isSuccess = true;
					}
				});
			},
			signalChange:function(event){
				axios.get("/amz/keyword/json/"+event.target.value)
				.then(
					response=>{
						// alert(response['data'][0]['bulletpoint_1']);
						// console.log(response);
						this.bulletpoint1 = this.getDataFieldVal(response, 'bulletpoint_1');
						this.bulletpoint2 = this.getDataFieldVal(response, 'bulletpoint_2');
						this.bulletpoint3 = this.getDataFieldVal(response, 'bulletpoint_3');
						this.bulletpoint4 = this.getDataFieldVal(response, 'bulletpoint_4');
						this.bulletpoint5 = this.getDataFieldVal(response, 'bulletpoint_5');
						this.search_term1 = this.getDataFieldVal(response, 'searchterm_1');
						this.search_term2 = this.getDataFieldVal(response, 'searchterm_2');
						this.search_term3 = this.getDataFieldVal(response, 'searchterm_3');
						this.search_term4 = this.getDataFieldVal(response, 'searchterm_4');
						this.search_term5 = this.getDataFieldVal(response, 'searchterm_5');
						this.description = this.getDataFieldVal(response, 'description');

					}
				)
			},
			getDataFieldVal:function(response, fieldname){
				return response['data'][0][fieldname];
			},
			generator_mockup:function(event){
				axios.get('/amz/gen-mockup/?file='+this.filepng+'&profile_id='+this.selected_profile)
				.then(response=>{
					alert('Done');
				});
			},

		}
	});
</script>	

@endforeach
@endisset

  @endsection