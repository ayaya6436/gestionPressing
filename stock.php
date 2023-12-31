<?php include 'db_connect.php' ?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-5">
				<div class="card">
					<div class="card-header">
						<h4><b>stock</b></h4>
					</div>
					<div class="card-body">
						<table class="table table-bordered">
							<thead>
								<th class="text-center">#</th>
								<th class="text-center">Produits</th>
								<th class="text-center">Stock disponible</th>
							</thead>
							<tbody>
							<?php 
								$i = 1;
								$supply = $conn->query("SELECT * FROM produits order by nom asc");
								while($row=$supply->fetch_assoc()):
									$sup_arr[$row['id']] = $row['nom'];
								$inn = $conn->query("SELECT sum(qty) as inn FROM stock where stock_type = 1 and produit_id = ".$row['id']);
								$inn = $inn && $inn->num_rows > 0 ? $inn->fetch_array()['inn'] : 0;
								$out = $conn->query("SELECT sum(qty) as `out` FROM stock where stock_type = 2 and produit_id = ".$row['id']);
								$out = $out && $out->num_rows > 0 ? $out->fetch_array()['out'] : 0;
								$available = $inn - $out;
							?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class=""><?php echo $row['nom'] ?></td>
									<td class="text-right"><?php echo $available ?></td>
								</tr>
							<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="card">
					<div class="card-header">
					<h3><b>Liste des entrées/sorties d'approvisionnement</b></h3>	
						<!-- <button class="btn btn-primary btn-sm float-right" id="manage-supply">Manage Supply</button> -->
					</div>
					<div class="card-body">
						<table class="table table-bordered">
							<thead>
								<th class="text-center">Date</th>
								<th class="text-center">Produits</th>
								<th class="text-center">Nombre</th>
								<th class="text-center">Etat</th>
								<th class="text-center">Actions</th>
							</thead>
							<tbody>
							<?php 
								$i = 1;
								$stock = $conn->query("SELECT * FROM stock order by id desc");
								while($row=$stock->fetch_assoc()):
							?>
								<tr>
									<td class="text-center"><?php echo date("Y-m-d",strtotime($row['date_creation'])) ?></td>
									<td class=""><?php echo $sup_arr[$row['produit_id']] ?></td>
									<td class="text-right"><?php echo $row['qty'] ?></td>
									<?php if($row['stock_type'] == 1): ?>
										<td class="text-center"><span class="badge badge-primary"> IN </span></td>
									<?php else: ?>
										<td class="text-center"><span class="badge badge-secondary"> Used </span></td>
									<?php endif; ?>
									<td>
										<button type="button" class="btn btn-sm btn-outline-primary edit_stock" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit"></i></button>
										<button type="button" class="btn btn-sm btn-outline-danger delete_stock" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
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
	$('table').dataTable()
	$('#manage-supply').click(function(){
		uni_modal("Gestion Stock","manage_stock.php")
	})
	$('.edit_stock').click(function(){
		uni_modal("Gestion Stock","manage_stock.php?id="+$(this).attr('data-id'))
	})
	$('.delete_stock').click(function(){
		_conf("Voulez vous supprimer ce produit du stock?","delete_stock",[$(this).attr('data-id')])
	})
	$('#laundry-list').dataTable()
	function delete_stock($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_inv',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Produit supprimer avec success",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>