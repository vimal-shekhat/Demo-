<?php if ($message != '') {?>
<div class="alert alert-danger fade in">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
    <script type="text/javascript"> $(document).ready(function () {
            $(".alert.alert-danger.fade.in").delay(7000).slideUp("slow");
        });</script>
        <?php echo $message; ?></div>
<?php
}?>

