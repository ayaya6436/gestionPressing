<?php include 'db_connect.php' ?>
<div class="container-fluid">
	<div class="col-lg-12">	
			<div class="card">
				<div class="card-body">	
					<div class="row">
						<div class="col-md-12">		
							<button class="col-sm-3 float-right btn btn-primary btn-sm" type="button" id="new_laundry"><i class="fa fa-plus"></i> Nouveau commande</button>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12">		
							<table class="table table-bordered" id="laundry-list">
								<thead>
									<tr>
										<th class="text-center">Date</th>
										<th class="text-center">attente</th>
										<th class="text-center">Nom du client</th>
										<th class="text-center">Status</th>
										<th class="text-center">Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$list = $conn->query("SELECT * FROM commandes order by status asc, id asc ");
									while($row=$list->fetch_assoc()):
									?>
									<tr>
										<td class=""><?php echo date("M d, Y",strtotime($row['date_creation'])) ?></td>
										<td class="text-right"><?php echo $row['attente'] ?></td>
										<td class=""><?php echo ucwords($row['nom_client']) ?></td>
										<?php if($row['status'] == 0): ?>
											<td class="text-center"><span class="badge badge-secondary">En attente</span></td>
										<?php elseif($row['status'] == 1): ?>
											<td class="text-center"><span class="badge badge-primary">Traitement</span></td>
										<?php elseif($row['status'] == 2): ?>
											<td class="text-center"><span class="badge badge-info">Prêt</span></td>
										<?php elseif($row['status'] == 3): ?>
											<td class="text-center"><span class="badge badge-success">Livre</span></td>
										<?php endif; ?>
										<td class="text-center">
											<button type="button" class="btn btn-outline-primary btn-sm edit_laundry" data-id="<?php echo $row['id'] ?>">Edit</button>
											<button type="button" class="btn btn-outline-danger btn-sm delete_laundry" data-id="<?php echo $row['id'] ?>">Delete</button>
										</td>
									</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>	
	</div>	
</div>
<script>
	$('#new_laundry').click(function(){
		uni_modal('New Laundry','manage_laundry.php','mid-large')
	})
	$('.edit_laundry').click(function(){
		uni_modal('Edit Laundry','manage_laundry.php?id='+$(this).attr('data-id'),'mid-large')
	})
	$('.delete_laundry').click(function(){
		_conf("Voulez vous supprimer la commande?","delete_laundry",[$(this).attr('data-id')])
	})
	$('#laundry-list').dataTable()
	function delete_laundry($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_laundry',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Commande supprimer avec success",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}

</script>