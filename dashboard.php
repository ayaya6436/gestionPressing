<style>
   
</style>

<div class="containe-fluid">

	<div class="row">
		<div class="col-lg-12">
			
		</div>
	</div>

	<div class="row mt-3 ml-3 mr-3">
			<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
				<?php 
				$role = $_SESSION['login_type'];
					if($role==1){
						echo "Bienvenue cher Administatreur";
					}else{
						echo "Bienvenue cher Employe";
					}
				?>
									
				</div>
				<hr>
				<div class="row">
				<div class="alert alert-success col-md-3 ml-4">
					<p><b><large>Benefice du jour</large></b></p>
				<hr>
					<p class="text-right"><b><large><?php 
					include 'db_connect.php';
					// $laundry = $conn->query("SELECT SUM(total_amount) as amount FROM commandes where pay_status= 1 and date(date_created)= '".date('Y-m-d')."'");
					// echo $laundry->num_rows > 0 ? number_format($laundry->fetch_array()['amount'],2) : "0.00";
					

					 ?></large></b></p>
					 5000 Fcfa
				</div>
				<div class="alert alert-info col-md-3 ml-4">
					<p><b><large>Total Client du jour</large></b></p>
				<hr>
					<p class="text-right"><b><large><?php 
					include 'db_connect.php';
					// $laundry = $conn->query("SELECT count(id) as `count` FROM commandes where  date(date_created)= '".date('Y-m-d')."'");
					// echo $laundry->num_rows > 0 ? number_format($laundry->fetch_array()['count']) : "0";

					 ?></large></b></p>
					 4 clients
				</div>
				<div class="alert alert-primary col-md-3 ml-4">
					<p><b><large>Total article nettoye du jour</large></b></p>
				<hr>
					<p class="text-right"><b><large><?php 
					include 'db_connect.php';
					// $laundry = $conn->query("SELECT count(id) as `count` FROM commandes where status = 3 and date(date_created)= '".date('Y-m-d')."'");
					// echo $laundry->num_rows > 0 ? number_format($laundry->fetch_array()['count']) : "0";

					 ?></large></b></p>
					 2 articles
				</div>
				</div>
			</div>
			
		</div>
		</div>
	</div>

</div>
<script>
	
</script>