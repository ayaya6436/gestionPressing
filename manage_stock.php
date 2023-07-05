<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM stock where id=".$_GET['id']);
	foreach($qry->fetch_assoc() as $k => $v){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<form action="" id="manage-inv">
		<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
		<div class="form-group">
			<div class="form-group">	
				<label for="" class="control-label">Produit Nom</label>
				<select class="custom-select browser-default" name="produit_id">
					<?php 
						$supply = $conn->query("SELECT * FROM produits order by nom asc");
						while($row= $supply->fetch_assoc()):
					?>
					<option value="<?php echo $row['id'] ?>" <?php echo isset($produit_id) && $produit_id == $row['id'] ? "selected" : '' ?>><?php echo $row['nom'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="form-group">	
				<label for="" class="control-label">Nombre</label>
				<input type="number" step="any" min="1" value="<?php echo isset($qty) ? $qty : 1 ?>" class="form-control text-right" name="qty">
			</div>
			<div class="form-group">	
				<label for="" class="control-label">Type</label>
				<select name="stock_type" id="" class="custom-select browser-default">
					<option value="1" <?php echo isset($stock_type) && $stock_type == 1 ? "selected" : '' ?>>stocker</option>
					<option value="2" <?php echo isset($stock_type) && $stock_type == 2 ? "selected" : '' ?>>Utiliser</option>
				</select>
			</div>
		</div>
	</form>
</div>

<script>
	$('#manage-inv').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_inv',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					alert_toast("produit enregistrer avec succes",'success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})

	})
</script>