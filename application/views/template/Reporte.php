<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/dist/css/font-awesome.css" rel="stylesheet" type="text/css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/dist/css/ionicons.min.css" rel="stylesheet" type="text/css">
        <!-- DataTables -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/dist/css/estilos.css" rel="stylesheet" type="text/css">
        <link rel="icon" type="image/png" href="<?php echo base_url() ?>frontend/dist/img/favicon.ico" />
        <script src="<?php echo base_url() ?>frontend/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo base_url() ?>frontend/bootstrap/js/bootstrap.min.js"></script>
        <script>
</script>
    </head>
    <body class="hold-transition">
        <?php
        if (isset($view)) {
            $this->load->view($view);
        }
        ?>
        <script src="<?php echo base_url() ?>frontend/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="<?php echo base_url() ?>frontend/plugins/fastclick/fastclick.js"></script>
        <script src="<?php echo base_url() ?>frontend/dist/js/app.min.js"></script>
    </body>
</html>