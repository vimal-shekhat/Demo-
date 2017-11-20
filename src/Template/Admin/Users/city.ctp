<option value="">Please select city</option>
<?php 
	foreach($selectboxcity as $k=>$sb){
		$Selected = "";
		if($k == $cityId){
			$Selected = "selected='selected'";
		}
	?>
		<option value="<?php echo $k; ?>" <?php echo $Selected; ?> ><?php echo $sb; ?></option>
	<?php }
	exit;
	 ?>