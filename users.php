<?php 

?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	
</head>
<body>
<div class="container-fluid">
	
	<div class="row">
	<div class="col-lg-12">
			<button class="btn btn-primary float-right btn-sm mt-4" id="new_user"><i class="fa fa-plus"></i> Nouveau utulisateur</button>
	</div>
	</div>
	<br>
	<div class="row">
		<div class="card col-lg-12">
			<div class="card-body">
				<table class="table-striped table-bordered col-md-12">
			<thead>
				<tr>
					<th class="text-center">Numero</th>
					<th class="text-center">Nom</th>
					<th class="text-center">Prenom</th>
					<th class="text-center">Email</th>
					<th class="text-center">Telephone</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
 					include 'db_connect.php';
 					$users = $conn->query("SELECT * FROM users ");
 					$i = 1;
 					while($row= $users->fetch_assoc()):
				 ?>
				 <tr>
				 	<td>
				 		<?php echo $i++ ?>
				 	</td>
				 	<td>
				 		<?php echo $row['nom'] ?>
				 	</td>
				 	<td>
				 		<?php echo $row['prenom'] ?>
				 	</td>
					 <td>
				 		<?php echo $row['email'] ?>
				 	</td>
					 <td>
				 		<?php echo $row['telephone'] ?>
				 	</td>
					
				 	<td>
				 		<center>
								<div class="btn-group">
								<a class="dropdown-item edit_user" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'><i class="fa-solid fa-pen-to-square"></i></a>
								    <div class="dropdown-divider"></div>
								    <a class="dropdown-item delete_user" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'><i class="fa-sharp fa-solid fa-trash"></i></a>
								</div>
						</center>
				 	</td>
				 </tr>
				<?php endwhile; ?>
			</tbody>
		</table>
			</div>
		</div>
	</div>

</div>
</body>
</html>


<script>
	
$('#new_user').click(function(){
	uni_modal('Ajouter Utilisateur','manage_user.php')
})
$('.edit_user').click(function(){
	uni_modal('Modifier Utilisateur','manage_user.php?id='+$(this).attr('data-id'))
})
$('.delete_user').click(function(){
		_conf("Voulez vous suppimer cet utilisateur?","delete_user",[$(this).attr('data-id')])
	})
	function delete_user($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_user',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Utilisateur supprimer avec succes",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>