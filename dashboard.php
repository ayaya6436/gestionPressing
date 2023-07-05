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
					<p><b><large>Benefice </large></b></p>
				<hr>
					<p class="text-right"><b><large><?php 
					include 'db_connect.php';
					$commande = $conn->query("SELECT SUM(montant_total) as montant FROM commandes WHERE pay_status = 1 AND DATE(date_creation) = '" . date('Y-m-d') . "'");
					$montant = $commande->num_rows > 0 ? $commande->fetch_array()['montant'] : 0;
					echo $montant !== null ? number_format($montant, 2) : "Fcfa";

					 ?></large></b></p>
					
				</div>
				<div class="alert alert-info col-md-3 ml-4">
					<p><b><large>Total Client</large></b></p>
				<hr>
					<p class="text-right"><b><large><?php 
					include 'db_connect.php';
					$commande = $conn->query("SELECT count(id) as `count` FROM commandes where  date(date_creation)= '".date('Y-m-d')."'");
					echo $commande->num_rows > 0 ? number_format($commande->fetch_array()['count'])." client(es)" : "0";

					 ?></large></b></p>
					 
				</div>
				<div class="alert alert-primary col-md-3 ml-4">
					<p><b><large>Total article nettoye </large></b></p>
				<hr>
					<p class="text-right"><b><large><?php 
					include 'db_connect.php';
					$commande = $conn->query("SELECT count(id) as `count` FROM commandes where status = 3 and date(date_creation)= '".date('Y-m-d')."'");
					echo $commande->num_rows > 0 ? number_format($commande->fetch_array()['count']). " articles" : "0";

					 ?></large></b></p>
					 
				</div>
				</div>
			</div>
			
		</div>
		</div>
	</div>

</div>
<script>
	
</script>