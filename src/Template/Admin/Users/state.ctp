<option value="">Please select state</option>
<?php 
	foreach($selectbox as $k=>$sb){
		$Selected = "";
		if($k == $stateId){
			$Selected = "selected='selected'";
		}
	?>
		<option value="<?php echo $k; ?>" <?php echo $Selected; ?> ><?php echo $sb; ?></option>
	<?php }
	exit;
	 ?>