<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $title_for_layout; ?></h3>
                </div>            
                <?php
                echo $this->Form->create($user, array('action' => 'add', 'class' => 'form-horizontal', 'novalidate','type' => 'file'));
                echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'id' => 'id'));
                ?> 
                <div class="box-body">
                        <fieldset>
                        <legend><?php echo __('User Fields:'); ?></legend>
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Device Id</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('device_id', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Device Type</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('device_type', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="col-sm-4 control-label">First Name</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('first_name', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Last Name</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('last_name', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Username</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('username', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Username')); ?>
                        </div>
                        </div>                    
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('password', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Password')); ?>
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('email', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Email')); ?>
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Token</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('token', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Email')); ?>
                        </div>
                        </div>
                        </fieldset>
                        <fieldset>
                        <legend><?php echo __('Profile Fields:'); ?></legend>
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Age</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('profile.age', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div> 
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Gender</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('profile.gender', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div> 
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Photo</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('profile.photo', array('type' => 'file', 'label' => false)); ?>
                        </div>
                        <div class="profile_img">
                        <?php if($user['profile']['photo']){ echo $this->Html->image($user['profile']['photo'], ['class'=>'MainImg','alt' => 'Profile Photo','height'=>100,'width'=>100]); 
                             }?>
                        </div>
                        </div> 
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Tags</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('profile.tags', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div> 
                        
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Display Name</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('profile.display_name', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div> 
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Bio</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('profile.bio', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div> 
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Interested In</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('profile.interested_in', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div> 
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Country</label>
                        <div class="col-sm-5">
                            <?php
							echo $this->Form->input('profile.country_id',['type' => 'select','options' => $countries, 'empty' => 'Please Select','label'=>false,'class'=>'form-control']);
                            ?>
                        </div>
                        </div> 
                         <div class="form-group">
                        <label class="col-sm-4 control-label">State</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('profile.state_id', array('type' => 'select', 'empty' => 'Please Select State', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div> 
                        <div class="form-group">
                        <label class="col-sm-4 control-label">City</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('profile.city', array('type' => 'select',  'empty' => 'Please Select City', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div>  
                          
                        
                        <div class="form-group">
                        <label class="col-sm-4 control-label">See Profile</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('profile.see_profile', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Age</label>
                        <div class="col-sm-5">
                            <div style="float:left">From:<?php echo $this->Form->input('profile.ageFrom', array('type' => 'text', 'label' => false));
                            ?></div>
                            <div style="float:right">
                             To:<?php echo $this->Form->input('profile.ageTo', array('type' => 'text', 'label' => false));
                            ?></div>
                        </div>
                        </div>        
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Snap chat</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('profile.snapchat', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div>   
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Instagram</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('profile.instagram', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                        </div>   
                        
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Coins</label>
                        <div class="col-sm-5">
                        <?php echo $this->Form->input('profile.coins', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                        ?>
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Random Number date</label>
                        <div class="col-sm-5">
                        <?php echo $this->Form->input('profile.rdate', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                        ?>
                        </div>
                        </div>                 
                        
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Hearts</label>
                        <div class="col-sm-5">
                        <?php echo $this->Form->input('profile.hearts', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                        ?>
                        </div>
                        </div>                 
                        <div class="form-group">
                        <label class="col-sm-4 control-label">Profile Visited Count</label>
                        <div class="col-sm-5">
                        <?php echo $this->Form->input('profile.profile_visited_count', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                        ?>
                        </div>
                        </div>      
                        </fieldset>
                    	</div>
               
                <div class="box-footer" style="text-align: center;">                    
                    <?php //echo $this->Html->link('Cancel', ['controller' => 'users', 'action' => 'index', '_full' => true], ['escape' => false, 'class' => 'btn btn-default']); ?>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>               
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
var site_url = '<?php echo PROJECT_URL; ?>';
$(document).ready(function (){
	$('#profile-country-id').change(function() {
		getState();
	 });
	 $('#profile-state-id').change(function() {
		getCity();
		
	 });
	 getState();
	 getCity();
});
 function getState(){
	var id = $('#profile-country-id').val();
	var state_id = "<?php echo $user['profile']['state_id']; ?>";				
	$.ajax({
		url: site_url+'admin/users/state',
		type: "POST",
		data: {country_id:id,state_id:state_id},
		success: function(data)
		 {

		  	$('#profile-state-id').html(data);
		 },
	});	 
}
 function getCity(){
	var id = $('#profile-country-id').val();
	var state_id1 = $('#profile-state-id').val();
	if(state_id1 == ''){
	var state_id = "<?php echo $user['profile']['state_id']; ?>";
	}else{
	var state_id = state_id1;
	}
	
	var city_id = "<?php echo $user['profile']['city']; ?>";				
	$.ajax({
		url: site_url+'admin/users/city',
		type: "POST",
		data: {country_id:id,state_id:state_id,city_id:city_id},
		success: function(data)
		 {

		  	$('#profile-city').html(data);
		 },
	});	 
}
</script>