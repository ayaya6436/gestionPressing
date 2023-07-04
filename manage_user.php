<?php 
include('db_connect.php');
if(isset($_GET['id'])){
$user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
?>
<div class="container-fluid">
	
	<form action="" id="manage-user">
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="form-group">
			<label for="nom">Nom</label>
			<input type="text" name="nom" id="nom" class="form-control" value="<?php echo isset($meta['nom']) ? $meta['nom']: '' ?>" required>
		</div>

		<div class="form-group">
			<label for="prenom">Prenom</label>
			<input type="text" name="prenom" id="prenom" class="form-control" value="<?php echo isset($meta['prenom']) ? $meta['prenom']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="email">Email</label>
			<input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required>
		</div>

		<div class="form-group">
			<label for="telephone">Telephone</label>
			<input type="text" name="telephone" id="telephone" class="form-control" value="<?php echo isset($meta['telephone']) ? $meta['telephone']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" value="<?php echo isset($meta['password']) ? $meta['password']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="type">Role</label>
			<select name="type" id="type" class="custom-select">
				<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Administrateur</option>
				<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>Employe</option>
			</select>
		</div>
	</form>
</div>
<script>
	$('#manage-user').submit(function(e){
		e.preventDefault();
		start_load()
		$.ajax({
			url:'ajax.php?action=save_user',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp ==1){
					alert_toast("Utilisateur energistrer avec succes",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	})
</script>