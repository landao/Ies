<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title>Py Park Apache Set up  </title>
    <link rel="stylesheet" href="{{asset('public/css/app.css')}}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">  
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">  
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
</head>
<body>
	<div class=row>
		<div class="col-md-12">
			<table class="table table-bordered table-striped">
				<tr>
					<th> IP List </th>
				</tr>
				
				  @foreach( $out as $out_1 )
                        
                       <tr>
                                                                
                         <td>{{ $out_1 }}</td>
                                                                   
                        </tr>
                   @endforeach  
				
			</table>

			

		</div>
		
		

		  

	</div>

</body>
</html>