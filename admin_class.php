<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where email = '".$email."' and password = '".$password."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$data = " nom = '$nom' ";
		$data .= ", prenom = '$prenom' ";
		$data .= ", email = '$email' ";
		$data .= ", telephone = '$telephone' ";
		$data .= ", password = '$password' ";
		$data .= ", type = '$type' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}


	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM user where id = ".$id);
		if($delete)
			return 1;
	}
	function save_settings(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'../assets/img/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data." where id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['setting_'.$key] = $value;
		}

			return 1;
				}
	}

	
	function save_category(){
		extract($_POST);
		$data = " nom = '$nom' ";
		$data .= ", prix = '$prix' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO categories set ".$data);
		}else{
			$save = $this->db->query("UPDATE categories set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM categories where id = ".$id);
		if($delete)
			return 1;
	}
	function save_supply(){
		extract($_POST);
		$data = " nom = '$nom' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO produits set ".$data);
		}else{
			$save = $this->db->query("UPDATE produits set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_supply(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM produits where id = ".$id);
		if($delete)
			return 1;
	}

	function save_laundry(){
		extract($_POST);
		$data = "nom_client = '$nom_client' ";
		$data .= ", montant_total = '$tendered' ";
		$data .= ", montant_paye = '$tamount' ";
		$data .= ", montant_restant = '$change' ";
		if(isset($pay)){
			$data .= ", pay_status = '1' ";
		}
		if(isset($status)){
			$data .= ", status = '$status' ";
		}
		if(empty($id)){
			$attente = $this->db->query("SELECT `attente` FROM commandes where status != 3 order by id desc limit 1");
			$attente = $attente->num_rows > 0 ? $attente->fetch_array()['attente'] + 1 : 1;
			$data .= ", attente = '$attente' ";
			$save = $this->db->query("INSERT INTO commandes set ".$data);
			if($save){
				$id = $this->db->insert_id;
				foreach ($weight as $key => $value) {
					$items = " article_id = '$id' ";
					$items .= ", categories_id = '$categories_id[$key]' ";
					$items .= ", weight = '$weight[$key]' ";
					$items .= ", prix_unitaire = '$prix_unitaire[$key]' ";
					$items .= ", montant = '$montant[$key]' ";
					$save2 = $this->db->query("INSERT INTO articles set ".$items);
				}
				return 1;
			}        
		}else{
			$save = $this->db->query("UPDATE commandes set ".$data." where id=".$id);
			if($save){
				$this->db->query("DELETE FROM articles where id not in (".implode(',',$item_id).") ");
				foreach ($weight as $key => $value) {
					$items = " article_id = '$id' ";
					$items .= ", categories_id = '$categories_id[$key]' ";
					$items .= ", weight = '$weight[$key]' ";
					$items .= ", prix_unitaire = '$prix_unitaire[$key]' ";
					$items .= ", montant = '$montant[$key]' ";
					if(empty($item_id[$key])){
						$save2 = $this->db->query("INSERT INTO articles set ".$items);
					}else{
						$save2 = $this->db->query("UPDATE articles set ".$items." where id=".$item_id[$key]);
					}
				}
				return 1;
			}    
		}
	}
	
	function delete_laundry(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM commandes where id = ".$id);
		$delete2 = $this->db->query("DELETE FROM articles where article_id = ".$id);
		if($delete && $delete2)
			return 1;
	}
	function save_inv(){
		extract($_POST);
		$data = " produit_id = '$produit_id' ";
		$data .= ", qty = '$qty' ";
		$data .= ", stock_type = '$stock_type' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO stock set ".$data);
		}else{
			$save = $this->db->query("UPDATE stock set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_inv(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM stock where id = ".$id);
		if($delete)
			return 1;
	}
	

}