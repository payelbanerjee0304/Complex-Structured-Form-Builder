<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Display</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
	@if(session('message'))
	<div class="alert alert-danger">
		 {{session('message')}}
	</div>
	@endif
	@if(isset($allinfo))
	<div class="table-responsiv">
		<table border="1" cellpadding="20" cellspacing="0" class="table-hover table-border">
			<tr>
				<td>SL.No.</td>
				<td>Name</td>
				<td>Email</td>
				<td>Password</td>
				<td>Phone</td>
				<td>Gender</td>
				<td>Language</td>
				<td>Profile Picture</td>
				<td>Action</td>
			</tr>
			@php
			$i=1;
			@endphp
			@foreach($allinfo->all() as $all)
			<tr>
				<td>@php echo $i; @endphp</td>
				<td>{{$all->name}}</td>
				<td>{{$all->email}}</td>
				<td>{{$all->password}}</td>
				<td>{{$all->phone}}</td>
				<td>{{$all->gender}}</td>
				<td>{{$all->language}}</td>
				<td><img src="{{$all->image}}" height="100"></td>
				<td><a href="{{url('/edit')}}{{$all->user_id}}">Edit</a>
					<a href="{{url('/delete')}}{{$all->user_id}}">Delete</a>
					<a href="{{url('/logout')}}">Logout</a></td>
			</tr>
			@php
			$i++;
			@endphp
			@endforeach
	</div>
	@endif
</body>
</html>