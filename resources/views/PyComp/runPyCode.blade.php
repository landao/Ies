@if (isset($out) && count($out) >0 )
   
   {{ count($out) }}
	<div>
		<ul>
		@foreach ($out as $k => $v)
			<li>{{ $v }}</li>

		@endforeach

		</ul>

	</div>


@endif



