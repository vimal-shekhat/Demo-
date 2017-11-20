<?php
$FullPath = PROJECT_URL;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title_for_layout; ?></title>       
        <script>
            var FullPath = "<?php echo $FullPath; ?>";
        </script>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> 
        <?php
        echo $this->Html->css(array(
            '../backend/css/bootstrap.min.css',
            '../backend/css/dataTables.bootstrap.css',
            '../backend/css/AdminTheme.min.css',
            '../backend/css/_all-skins.min.css',
            '../backend/css/summernote.css',
            '../backend/css/custome.css'
        ));
        ?>  
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

        <?php
        echo $this->Html->script(array(
            '../backend/js/jquery-2.2.3.min.js',
            '../backend/js/bootstrap.min.js',
            '../backend/js/fastclick.js',
            '../backend/js/app.min.js',
            '../backend/js/jquery.sparkline.min.js',
            '../backend/js/jquery.slimscroll.min.js',
            '../backend/js/jquery.sparkline.min.js',
            '../backend/js/summernote.min.js',
            '../backend/js/custome.js'
        ));
        ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="ajax-loader">
            <img src="<?php echo PROJECT_URL; ?>backend/img/loading.gif" class="img-responsive" />
        </div>
        <div class="wrapper">
            <header class="main-header">                
                <a href="<?php echo PROJECT_URL; ?>" class="logo">                    
                    <span class="logo-mini"><b>S</b>A</span>                    
                    <span class="logo-lg"><b><?php echo FullName; ?></b></span>
                </a>                
                <nav class="navbar navbar-static-top">                    
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>                    
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">                                                        
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?php echo PROJECT_URL; ?>img/user2-160x160.jpg" class="user-image" alt="User Image">
                                    <span class="hidden-xs"><?php echo FullName; ?></span>
                                </a>
                                <ul class="dropdown-menu">                                    
                                    <li class="user-header">
                                        <img src="<?php echo PROJECT_URL; ?>img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                        <p>
                                            <?php echo FullName; ?>
                                            <!--<small>xxx-xxx-xxx-xxx</small>-->
                                        </p>
                                    </li>                                    
                                    <li class="user-body"><div class="row"></div></li>                                    
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <?php echo $this->Html->link('Change Password', ['controller' => 'Users', 'action' => 'change_pwd', '_full' => true], ['escape' => false, 'class' => 'btn btn-default btn-flat']); ?>
                                        </div>
                                        <div class="pull-right">                                            
                                            <?php echo $this->Html->link('Sign out', ['controller' => 'Users', 'action' => 'logout', '_full' => true], ['escape' => false, 'class' => 'btn btn-default btn-flat']); ?>
                                        </div>
                                    </li>
                                </ul>
                            </li>                           
                        </ul>
                    </div>

                </nav>
            </header>            
            <aside class="main-sidebar">                
                <section class="sidebar">                    
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?php echo PROJECT_URL; ?>img/user2-160x160.jpg" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo FullName; ?></p>                            
                        </div>
                    </div>                    
                    <ul class="sidebar-menu">
                        <li class="header">MAIN NAVIGATION</li>
                        <li class="active">
                            <a href="<?php echo PROJECT_URL; ?>admin/dashboard">
                                <i class="fa fa-dashboard"></i><span>Dashboard</span>
                            </a>							 
                        </li>
                                               
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-upload"></i><span>Users</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="<?php echo PROJECT_URL; ?>admin/usersList">
                                        <i class="fa fa-upload"></i><span>UserList</span>
                                    </a>                            
                                </li>
                                <li>
                                    <a href="<?php echo PROJECT_URL; ?>admin/Users/AllCsvData">
                                        <i class="fa fa-circle-o"></i>View Users
                                    </a> 
                                </li>                                
                            </ul>
                        </li>                        
                    </ul>
                </section>
            </aside>            
            <div class="content-wrapper">                
                <section class="content-header">
                    <?php echo $this->Flash->render('flash'); ?>
                </section>                
                <?php echo $this->fetch('content'); ?>
            </div>           
            <footer class="main-footer hidden">
                <!--<div class="pull-right hidden-xs">
                    <b>Version</b> 2.3.8
                </div>-->
                <strong>Copyright &copy; 2017 <a href="#">#</a>.</strong> All rights reserved.
            </footer>          
            <div class="control-sidebar-bg"></div>
        </div>   
        <script>
//            $(function () {
//                $('input').iCheck({
//                    checkboxClass: 'icheckbox_square-blue',
//                    radioClass: 'iradio_square-blue',
//                    increaseArea: '20%' // optional
//                });
//            });
        </script>
    </body>
</html>
