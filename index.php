<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="datatable/dataTable.bootstrap.min.css">
	<style>
		.height10{
			height:10px;
		}
		.mtop10{
			margin-top:10px;
		}
		.modal-label{
			position:relative;
			top:7px
		}

		#myInput {
			background-image: url('/css/searchicon.png');
			background-position: 10px 10px;
			background-repeat: no-repeat;
			width: 100%;
			font-size: 16px;
			padding: 12px 20px 12px 40px;
			border: 1px solid #ddd;
			margin-bottom: 12px;
			margin-top:12px;
}
	</style>
</head>
<body>
	<?php include_once('header.php');?>

<div class="container">
	<h1 class="page-header text-center">Bincom test</h1>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<div class="row">
			<?php
				if(isset($_SESSION['error'])){
					echo
					"
					<div class='alert alert-danger text-center'>
						<button class='close'>&times;</button>
						".$_SESSION['error']."
					</div>
					";
					unset($_SESSION['error']);
				}
				if(isset($_SESSION['success'])){
					echo
					"
					<div class='alert alert-success text-center'>
						<button class='close'>&times;</button>
						".$_SESSION['success']."
					</div>
					";
					unset($_SESSION['success']);
				}
			?>
			</div>
			<div class="row">
			<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for polling unit uniqueid" title="Type in a name">
			</div>
			

			<div class="height10">
				
			</div>
			<div class="row">
				<table id="myTable" class="table table-bordered table-striped">
					<thead class="header">
						<th>S/N</th>
						<th>polling unit uniqueid</th>
						<th>party abbreviation</th>
						<th>party score</th>
					</thead>
					<tbody>
						<?php
							include_once('connection.php');
							$sql = "SELECT * FROM announced_pu_results";

							//use for MySQLi-OOP
							$query = $conn->query($sql);
							while($row = $query->fetch_assoc()){
								echo 
								"<tr>
									<td>".$row['result_id']."</td>
									<td>".$row['polling_unit_uniqueid']."</td>
									<td>".$row['party_abbreviation']."</td>
									<td>".$row['party_score']."</td>


								</tr>";
								
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="js/filter.js"></script> 
<script src="datatable/dataTable.bootstrap.min.js"></script>
<!-- generate datatable on our table -->
<script>
$(document).ready(function(){
	//inialize datatable
    $('#myTable').DataTable();

    //hide alert
    $(document).on('click', '.close', function(){
    	$('.alert').hide();
    })
});
</script>
</body>
<<footer><div class="PP"><p>Brought To You By:<a href="#"> Zion olamide</a></p></div></footer>
<<style>
.PP{
	text-align: center;
}
</style>
</html>