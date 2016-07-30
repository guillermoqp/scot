<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_usable('base_url')){
    //function base_url(){ return 'http://'.$_SERVER['SERVER_NAME'].'/scot';}
    function base_url(){ return 'https://scot-b.herokuapp.com/';}
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $heading; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="https://almsaeedstudio.com/themes/AdminLTE/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="https://almsaeedstudio.com/themes/AdminLTE/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    404 Error !
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Inicio</a></li>
                    <li><a href="<?php echo base_url(); ?>"><?php echo $heading; ?></a></li>
                    <li class="active">Error 404!</li>    
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="error-page">
                    <h2 class="headline text-info">404</h2>
                    <div class="error-content">
                        <h3><i class="fa fa-warning text-yellow"></i> <?php echo $heading; ?></h3>
                        <p>
                            <?php echo $message; ?>
                        </p>
                        <a href="<?php echo base_url(); ?>"><h4 class="modal-title"><i class="fa fa-mail-reply-all"></i> Regresar al Inicio</h4> </a> 
                    </div><!-- /.error-content -->
                </div><!-- /.error-page -->

            </section><!-- /.content -->
            
        </div><!-- ./wrapper -->
        <!-- jQuery 2.0.2 -->
        <script src="https://almsaeedstudio.com/themes/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap -->
        <script src="https://almsaeedstudio.com/themes/AdminLTE/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="https://almsaeedstudio.com/themes/AdminLTE/dist/js/app.min.js" type="text/javascript"></script>

    </body>
</html>