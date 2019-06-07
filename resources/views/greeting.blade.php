<!-- View stored in resources/views/greeting.php -->
@extends('welcome')


@section('content')
	<h1>Hello, <?php echo $name; ?></h1>
    <p>This is my body content.</p>

@foreach ($list_user as $user)
<img src="/storage/{{ $user->avatar}}" />
<p>This is user {{ $user->username }}</p>
@endforeach

{{ Form::open(array('url'=>'my_user',  'enctype'=>"multipart/form-data")) }}
	
	{{ Form::label('username', 'Username:') }}
	{{ Form::text('username', '') }}
<br/>
	{{ Form::label('password', 'Password:') }}
	{{ Form::password('password') }}

	{{ Form::file('avatar') }}

	{{Form::submit('Register')}}

{{ Form::close()}}
@endsection