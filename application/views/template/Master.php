<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Sistema de Control de Ordenes de Trabajo - SCOT</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="description" content="Sistema de Control de Ordenes de Trabajo - SCOT | Sedalib S.A.">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="index,follow,noodp,noydir"/>
        <meta name="google-site-verification" content="T_M_Ym5DQ-cEQQhx_jswyCBTssIdgtewICcvb3sgh8g" />
        <meta name="Keywords" content="Sistema,Control,Ordenes,Trabajo,Sedalib" />
        <meta property="og:title" content="Sistema de Control de Ordenes de Trabajo - SCOT | Sedalib S.A." />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="icon.png" />
        <meta property="o g:url" content="http://land.sedalib.com.pe:81/moviles/" />
        <meta property="og:description" content="Sistema de Control de Ordenes de Trabajo - SCOT | Sedalib S.A." />
        <meta property="og:site_name" content="http://land.sedalib.com.pe:81/moviles/" />
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/dist/css/font-awesome.css" rel="stylesheet" type="text/css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/dist/css/ionicons.min.css" rel="stylesheet" type="text/css">
        <!-- daterange picker -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/daterangepicker/daterangepicker-bs3.css">
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/timepicker/bootstrap-timepicker.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css">
        <!-- Morris charts -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/morris/morris.css" rel="stylesheet" type="text/css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/dist/css/estilos.css" rel="stylesheet" type="text/css">
        <link rel="icon" type="image/png" href="<?php echo base_url() ?>frontend/dist/img/favicon.ico" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- jQuery 2.1.4 -->
        <script src="<?php echo base_url() ?>frontend/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo base_url() ?>frontend/bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body class="hold-transition skin-blue-light sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="<?php echo base_url() ?>inicio" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>OT</b></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>SCOT</b></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Menu</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav" id="notificaciones">
                       
                            <!-- NO RECIBIDAS -->
                            <li id="notificacionesOTNoRec" class="dropdown tasks-menu" data-toggle="tooltip" data-placement="bottom" title="Ordenes de Trabajo No Recibidas">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="glyphicon glyphicon-remove-circle"></i>
                                    <span class="label label-danger" id="numOTTENoRec"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header" id="nroNotifNoRec"></li>
                                    <li>
                                        <ul class="menu" id="OTsTENoRec">
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="<?php echo base_url() ?>toma_estado/ordenes"><span class="glyphicon glyphicon-zoom-in"></span> Ver todas las Ordenes de Trabajo</a></li>
                                </ul>
                            </li>

                            <!-- RECIBIDAS -->
                            <li id="notificacionesOTRec" class="dropdown tasks-menu" data-toggle="tooltip" data-placement="bottom" title="Ordenes de Trabajo Recibidas">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="glyphicon glyphicon-ok-circle"></i>
                                    <span class="label label-info" id="numOTTERec"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header" id="nroNotifRec"></li>
                                    <li>
                                        <ul class="menu" id="OTsTERec">
                                        </ul>
                                    </li>
                                    <li class="footer">
                                        <a href="<?php echo base_url() ?>toma_estado/ordenes"><span class="glyphicon glyphicon-zoom-in"></span> Ver Todas las Ordenes de Trabajo</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <!-- EN EJECUCION  -->
                            <li class="dropdown tasks-menu" data-toggle="tooltip" data-placement="bottom" title="Ordenes de Trabajo En Ejecución">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="glyphicon glyphicon-time"></i>
                                    <span class="label label-danger" id="numOTEnEjecucion"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header" id="numOTEnEjecucionDes"></li>
                                    <li>
                                        <ul class="menu" id="OTsTomaEstadoEje">
                                        </ul>
                                    </li>
                                    <li class="footer">
                                        <a href="<?php echo base_url() ?>toma_estado/ordenes"><span class="glyphicon glyphicon-zoom-in"></span> Ver Todas las Ordenes de Trabajo</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <!-- CERRADAS  -->
                            <li id="notificacionesOTCer" class="dropdown tasks-menu" data-toggle="tooltip" data-placement="bottom" title="Ordenes de Trabajo Cerradas">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <span class="glyphicon glyphicon-copyright-mark"></span>
                                    <span class="label label-success" id="numOTTECer"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header" id="nroNotifCer"></li>
                                    <li>
                                        <ul class="menu" id="OTsTECer">
                                        </ul>
                                    </li>
                                    <li class="footer">
                                        <a href="<?php echo base_url() ?>toma_estado/ordenes"><span class="glyphicon glyphicon-zoom-in"></span> Ver Todas las Ordenes de Trabajo</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?php echo base_url() . $userdata['UsrFot']; ?>" class="user-image" alt="User Image">
                                    <span class="hidden-xs"><?php echo $userdata['UsrNomPr']; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="<?php echo base_url() . $userdata['UsrFot']; ?>" class="img-circle" alt="User Image">
                                        <p>
                                            <small style="font-size:14px"><?php echo $userdata['UsrNomPr'] . ' ' . $userdata['UsrApePt']; ?> |<b> <?php echo $userdata['CarDes']; ?></b></small>
                                            <small>
                                            <i class="fa fa-calendar"></i> Registro: <?php echo date_format(new DateTime($userdata['UsrFchRg']), 'd/m/Y'); ?> | <i class="fa fa-calendar"></i> Expiración: <?php echo date_format(new DateTime($userdata['UsrFchEx']), 'd/m/Y'); ?> </small>
                                            <!-- <small><i class="fa fa-laptop"></i><?php echo $_SERVER['REMOTE_ADDR']; ?> | <i class="fa fa-television"></i> <?php echo gethostbyaddr($_SERVER['REMOTE_ADDR']); ?> </small> -->
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="<?php echo base_url() . 'permisos/mi_usuario/editar/' . $userdata['UsrId']; ?>"  class="btn btn-default btn-flat"><i class="fa fa-fw fa-cogs"></i> Configuraciones</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="#" onclick="salir()" class="btn btn-default btn-flat"><i class="fa fa-fw fa-power-off"></i> Salir</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <div class="user-panel">
                        <img style="width: 210px; height: 45px;" src="<?php echo base_url() . $userdata['contratante']['CntLog1'] ?>" alt="<?php echo $userdata['contratante']['EmpRaz'] ?>">
                    </div>
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <!--  <p>EPS : <?php echo $userdata['contratante']['EmpRaz'] ?></p> -->
                            <img src="<?php echo base_url() . $userdata['UsrFot']; ?>" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo $userdata['UsrNomPr'] . ' ' . $userdata['UsrApePt']; ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a><br>
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header">NAVEGACIÓN PRINCIPAL</li>
                        <?php /*foreach ($userdata['actividades'] as $actividad): ?>
                            <?php if (array_key_exists($actividad['ActCod'], $userdata['permisos'])) { ?>
                            <li class="treeview<?php echo ($actividad['ActCod'] == $menu['padre']) ? ' active' : '' ?>">
                                <a href="#">
                                    <i class="fa fa-files-o"></i> <span><?php echo $actividad['ActDes']?></span><i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                <?php foreach ($actividad['permisos'] as $permiso): ?>
                                    <?php if (array_key_exists($permiso['GpeVal'], $userdata['permisos'][$actividad['ActCod']])) { ?>
                                        <li class="<?php echo ($permiso['GpeVal'] == $menu['hijo']) ? ' active' : '' ?>">
                                            <a href="
                                               <?php #if ($actividad['ActMod'] == 'f') {
                                                   #echo base_url(). $actividad['ActCod'].'/'.$permiso['GpeVal'];
                                                #} else {
                                                   echo base_url(). $actividad['ActCod'].'/'.$permiso['GpeVal'];
                                                #}
                                               ?>
                                               "><i class="fa fa-circle-o<?php echo ($permiso['GpeVal'] == $menu['hijo']) ? ' text-red' : '' ?>"></i> <?php echo $permiso['GpeDes']?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                <?php endforeach; ?>
                                </ul>
                            </li>
                            <?php } ?>
                        <?php endforeach; */?>
                        <?php if (array_key_exists('toma_estado', $userdata['permisos'])) { ?>
                            <li class="treeview<?php echo ('toma_estado' == $menu['padre']) ? ' active' : '' ?>">
                                <a href="#">
                                    <i class="fa fa-files-o"></i> <span>Toma de Estado</span><i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if (array_key_exists('cronograma_anual', $userdata['permisos']['toma_estado'])) { ?>
                                        <li class="<?php echo ('cronograma_anual' == $menu['hijo']) ? ' active' : '' ?>">
                                            <a href="<?php echo base_url() ?>toma_estado/cronograma_anual">
                                                <i class="fa fa-calendar-o<?php echo ('cronograma_anual' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Cronograma Anual
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists('cronograma_periodo', $userdata['permisos']['toma_estado'])) { ?>
                                        <li class="<?php echo ('cronograma_periodo' == $menu['hijo']) ? ' active' : '' ?>">
                                            <a href="<?php echo base_url() ?>toma_estado/cronograma_periodo">
                                                <i class="fa fa-calendar<?php echo ('cronograma_periodo' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Cronograma Periodo
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists('lecturista', $userdata['permisos']['toma_estado'])) { ?>
                                        <li class="<?php echo ('lecturista' == $menu['hijo']) ? ' active' : '' ?>">
                                            <a href="<?php echo base_url() ?>toma_estado/lecturista">
                                                <i class="fa fa-users<?php echo ('lecturista' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Lecturistas
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists('ordenes', $userdata['permisos']['toma_estado'])) { ?>
                                        <li class="<?php echo ('ordenes' == $menu['hijo']) ? ' active' : '' ?>">
                                            <a href="<?php echo base_url() ?>toma_estado/ordenes">
                                                <i class="fa fa-file-o<?php echo ('ordenes' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Ordenes de Trabajo
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists('valorizacion', $userdata['permisos']['toma_estado'])) { ?>
                                        <li class="<?php echo ('valorizacion' == $menu['hijo']) ? ' active' : '' ?>">
                                            <a href="<?php echo base_url() ?>toma_estado/valorizacion">
                                                <i class="fa fa-money<?php echo ('valorizacion' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Valorizaciones
                                            </a>
                                        </li>
                                    <?php } ?>
                                    
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (array_key_exists('distribucion', $userdata['permisos'])) { ?>
                            <li class="treeview<?php echo ('distribucion' == $menu['padre']) ? ' active' : '' ?>">
                                <a href="#">
                                    <i class="fa fa-envelope-o"></i> <span>Distribución de Recibos y Comunicaciones al Cliente</span><i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if (array_key_exists('cronograma_distribucion_periodo', $userdata['permisos']['distribucion'])) { ?>
                                        <li class="<?php echo ('cronograma_distribucion_periodo' == $menu['hijo']) ? ' active' : '' ?>">
                                            <a href="<?php echo base_url() ?>distribucion/cronograma_periodo">
                                                <i class="fa fa-calendar<?php echo ('cronograma_distribucion_periodo' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Cronograma Periodo
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists('ordenes_distribucion', $userdata['permisos']['distribucion'])) { ?>
                                        <li class="<?php echo ('ordenes_distribucion' == $menu['hijo']) ? ' active' : '' ?>">
                                            <a href="<?php echo base_url() ?>distribucion/ordenes">
                                                <i class="fa fa-file-o<?php echo ('ordenes_distribucion' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Ordenes de Trabajo
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists('distribuidor', $userdata['permisos']['distribucion'])) { ?>
                                        <li class="<?php echo ('distribuidor' == $menu['hijo']) ? ' active' : '' ?>">
                                            <a href="<?php echo base_url() ?>distribucion/distribuidor">
                                                <i class="fa fa-users<?php echo ('distribuidor' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Distribuidores
                                            </a>
                                        </li>
                                    <?php } ?>    
                                    <?php if (array_key_exists('valorizacion_distribucion', $userdata['permisos']['distribucion'])) { ?>
                                        <li class="<?php echo ('valorizacion_distribucion' == $menu['hijo']) ? ' active' : '' ?>">
                                            <a href="<?php echo base_url() ?>distribucion/valorizacion">
                                                <i class="fa fa-money<?php echo ('valorizacion_distribucion' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Valorizaciones
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                        <?php } ?>
                        <?php if (array_key_exists('inspeccion', $userdata['permisos'])) { ?>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-search"></i> <span>Inspecciones</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (array_key_exists('recaudacion', $userdata['permisos'])) { ?>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-folder-open"></i> <span>Recaudación, Cancelación y Cautela de recibos</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (array_key_exists('acciones_persuasivas', $userdata['permisos'])) { ?>
                            <li class="treeview<?php echo ('acciones_persuasivas' == $menu['padre']) ? ' active' : '' ?>">
                                <a href="#">
                                    <i class="fa fa-calendar"></i> <span>Acciones Persuasivas</span><i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if (array_key_exists('ordenesA', $userdata['permisos']['acciones_persuasivas'])) { ?>
                                        <li class="<?php echo ('ordenesA' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() ?>acciones_persuasivas/ordenesA"><i class="fa fa-book<?php echo ('ordenesA' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Ordenes de Trabajo</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (array_key_exists('acondicionamiento', $userdata['permisos'])) { ?>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-home"></i> <span>Acondicionamiento de Conexiones Domiciliarias</span>
                                </a>
                            </li> 
                        <?php } ?>
                        <?php if (array_key_exists('contratacion', $userdata['permisos'])) { ?>
                            <li class="treeview<?php echo ('contratacion' == $menu['padre']) ? ' active' : '' ?>">
                                <a href="#">
                                    <i class="fa fa-file-text-o"></i> <span>Contratación</span><i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if (array_key_exists('contratista', $userdata['permisos']['contratacion'])) { ?>
                                        <li class="<?php echo ('contratista' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() ?>contratistas"><i class="fa fa-building-o<?php echo ('contratista' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Contratistas</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('contrato', $userdata['permisos']['contratacion'])) { ?>
                                        <li class="<?php echo ('contrato' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() ?>contratos"><i class="fa fa-book<?php echo ('contrato' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Contratos</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (array_key_exists('permisos', $userdata['permisos'])) { ?>
                            <li class="treeview<?php echo ('permisos' == $menu['padre']) ? ' active' : '' ?>">
                                <a href="#">
                                    <i class="fa fa-unlock-alt"></i> <span>Permisos</span><i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if (array_key_exists('usuario', $userdata['permisos']['permisos'])) { ?>
                                        <li class="<?php echo ('usuario' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() ?>permisos/usuarios"><i class="fa fa-group<?php echo ('usuario' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Usuarios</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('rol', $userdata['permisos']['permisos'])) { ?>
                                        <li class="<?php echo ('rol' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() ?>permisos/perfil"><i class="fa fa-user<?php echo ('rol' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Perfiles</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('cargo', $userdata['permisos']['permisos'])) { ?>
                                        <li class="<?php echo ('cargo' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() ?>permisos/cargo"><i class="fa fa-legal<?php echo ('cargo' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Cargos</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('nivel', $userdata['permisos']['permisos'])) { ?>
                                        <li class="<?php echo ('nivel' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() ?>permisos/niveles"><i class="fa fa-sitemap<?php echo ('nivel' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Nivel Organizacional</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (array_key_exists('directorio', $userdata['permisos'])) { ?>
                            <li class="treeview<?php echo ('directorio' == $menu['padre']) ? ' active' : '' ?>">
                                <a href="#">
                                    <i class="fa fa-book"></i> <span>Directorio</span><i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if (array_key_exists('directorio_contratante', $userdata['permisos']['directorio'])) { ?>
                                        <li class="<?php echo ('directorio_contratante' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'directorio/eps' ?>"><i class="fa fa-phone<?php echo ('directorio_contratante' == $menu['hijo']) ? ' text-red' : '' ?>"></i> EPS</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('directorio_contratista', $userdata['permisos']['directorio'])) { ?>
                                        <li class="<?php echo ('directorio_contratista' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'directorio/contratista' ?>"><i class="fa fa-phone<?php echo ('directorio_contratista' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Contratista</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (array_key_exists('configuracion', $userdata['permisos'])) { ?>
                            <li class="treeview<?php echo ('configuracion' == $menu['padre']) ? ' active' : '' ?>">
                                <a href="#">
                                    <i class="fa fa-gear"></i> <span>Configuración</span><i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if (array_key_exists('niveles_comerciales', $userdata['permisos']['configuracion'])) { ?>
                                        <li class="<?php echo ('niveles_comerciales' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/niveles_comerciales' ?>"><span class="glyphicon glyphicon-indent-left<?php echo ('niveles_comerciales' == $menu['hijo']) ? ' text-red' : '' ?>"></span> Niveles Comerciales</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('grupo_penalidad', $userdata['permisos']['configuracion'])) { ?>
                                        <li class="<?php echo ('grupo_penalidad' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/grupos_penalidad' ?>"><i class="fa fa-exclamation-circle<?php echo ('grupo_penalidad' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Grupo de Penalidades</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('penalidad', $userdata['permisos']['configuracion'])) { ?>
                                        <li class="<?php echo ('penalidad' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/penalidades' ?>"><i class="fa fa-legal<?php echo ('penalidad' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Penalidades</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('grupo_observacion', $userdata['permisos']['configuracion'])) { ?>
                                        <li class="<?php echo ('grupo_observacion' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/grupos_observacion' ?>"><i class="fa fa-eye<?php echo ('grupo_observacion' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Grupo de Observaciones</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('observacion', $userdata['permisos']['configuracion'])) { ?>
                                        <li class="<?php echo ('observacion' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/observaciones' ?>"><i class="fa fa-eye<?php echo ('observacion' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Observaciones</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('variable', $userdata['permisos']['configuracion'])) { ?>
                                        <li class="<?php echo ('variable' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/variables' ?>"><i class="fa fa-tasks<?php echo ('variable' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Variables Generales</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (array_key_exists('configuracion_global', $userdata['permisos'])) { ?>
                            <li class="treeview<?php echo ('configuracion_global' == $menu['padre']) ? ' active' : '' ?>">
                                <a href="#">
                                    <i class="fa fa-gear"></i> <span>Configuración Global</span><i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if (array_key_exists('contratante', $userdata['permisos']['configuracion_global'])) { ?>
                                        <li class="<?php echo ('contratante' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/contratantes' ?>"><i class="fa fa-building-o<?php echo ('contratante' == $menu['hijo']) ? ' text-red' : '' ?>"></i> EPSs</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('unidad_medida', $userdata['permisos']['configuracion_global'])) { ?>
                                        <li class="<?php echo ('unidad' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/unidades' ?>"><i class="fa fa-ticket<?php echo ('unidad' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Unidad de Medida</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('actividad', $userdata['permisos']['configuracion_global'])) { ?>
                                        <li class="<?php echo ('actividad' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/actividad' ?>"><i class="fa fa-eye<?php echo ('actividad' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Actividades Comerciales</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('subactividad', $userdata['permisos']['configuracion_global'])) { ?>
                                        <li class="<?php echo ('subactividad' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/subactividad' ?>"><i class="fa fa-spinner<?php echo ('subactividad' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Sub-Actividades Comerciales</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('parametros', $userdata['permisos']['configuracion_global'])) { ?>
                                        <li class="<?php echo ('parametros' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/parametros' ?>"><i class="fa fa-cog<?php echo ('parametros' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Parámetros Globales</a></li>
                                    <?php } ?>
                                    <?php if (array_key_exists('periodos', $userdata['permisos']['configuracion_global'])) { ?>
                                        <li class="<?php echo ('periodos' == $menu['hijo']) ? ' active' : '' ?>"><a href="<?php echo base_url() . 'configuracion/periodos' ?>"><i class="fa fa-calendar<?php echo ('periodos' == $menu['hijo']) ? ' text-red' : '' ?>"></i> Periodos</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>

                        <li class="treeview">
                            <a href="#" onclick="salir()">
                                <i class="fa fa-power-off text-yellow"></i> <span>Salir</span>
                            </a>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <p>
                        <b> Proceso: </b><small> <?php echo $proceso ?></small> - <b>  Usuario:  </b><small> <?php echo $userdata['UsrNomPr'] . ' ' . $userdata['UsrApePt']; ?></small> - <b> Cargo: </b><small><?php echo $userdata['CarDes']; ?></small> 
                    </p>
                </section>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Inicio</a></li>
                    <?php
                    foreach ($breadcrumbs as $bcItem) {
                        if ($bcItem[1] != '') {
                            echo '<li><a href="' . base_url() . $bcItem[1] . '">' . $bcItem[0] . '</a></li>';
                        } else {
                            echo '<li class="active">' . $bcItem[0] . '</li>';
                        }
                    }
                    ?>
                </ol>
                <!-- Main content -->
                <?php
                if (isset($view)) {
                    $this->load->view($view);
                }
                ?>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    SCOT-Sistema de Control de Ordenes de Trabajo | <b>Versión  1.4</b>
                </div>
                <strong>&copy; 2016 - Gerencia Comercial - <a href="http://www.sedalib.com.pe/"><b>Sedalib S.A.</b></a></strong>
            </footer>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>
        <!-- ./wrapper -->
        <!-- SlimScroll -->
        <script src="<?php echo base_url() ?>frontend/plugins/slimScroll/jquery.slimscroll.min.js"></script>

        <!-- FastClick -->
        <script src="<?php echo base_url() ?>frontend/plugins/fastclick/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo base_url() ?>frontend/dist/js/app.min.js"></script>

        <!-- sweetAlert. -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/sweetalert/sweetalert2.css" rel="stylesheet" type="text/css">
        <script src="<?php echo base_url() ?>frontend/plugins/sweetalert/sweetalert2.min.js"></script> 

        <!-- include the script -->
        <script src="<?php echo base_url() ?>frontend/plugins/alertify/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/alertify/css/alertify.min.css" />
        <!-- include a theme -->
        <link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/alertify/css/themes/bootstrap.min.css" />
        
        <script>
            function salir() {
            swal({
                 title: "¿Seguro que desea Salir?",
                 text: "Saldrá del Sistema Sin Guardar los cambios que haya realizado!",
                 type: "warning",
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: "Si, deseo Salir",
                 cancelButtonText: "Cancelar",
                 closeOnConfirm: false
               },
               function(isConfirm) {
                if (isConfirm) {   
                    swal({
                        title: 'Ha salido del Sistema',
                        text: 'Ha Salido del Sistema.',
                        type: 'success',
                        showConfirmButton: false
                      }),
                    window.location.href = "<?php echo base_url() ?>logout";
                }
               });
        }
        </script>
        
        <!-- NOTIFICACIONES DE ORDENES DE TRABAJO-->
        <script type="text/javascript">
        $(document).ready(function ()
        {         
                var idUsuario = <?php echo $userdata['UsrId']; ?>;
                var id = $(".lastId").attr("id"), getLastId, html = "";

                $.ajax({
                    url: "<?php echo base_url() . 'toma_estado/obtener_ordenes_no_rec_x_usuario_json'; ?>",
                    type: "POST",
                    data: ({idUsuario: idUsuario}),
                    dataType: "html",
                    success: function (data)
                    {
                        var OTsTomaEstado = JSON.parse(data);

                        console.log(OTsTomaEstado);
                        if (OTsTomaEstado.length != 0)
                        {
                            $('#numOTTENoRec').text(OTsTomaEstado.length);
                            $('#nroNotifNoRec').text('Tiene ' + OTsTomaEstado.length + ' Ordenes de trabajo No Recibidas por la Contratista');
                            $("#OTsTENoRec").append(html);
                            for (var i = 0; i < OTsTomaEstado.length; i++)
                            {
                                var getLastId = OTsTomaEstado[i]["OrtId"];
                                html += '<li>';
//                                if(OTsTomaEstado[i]["ActCod"]==='toma_estado')
//                                {
//                                    html += '<a href="<?php #echo base_url() . 'toma_estado/orden/ver/'; ?>' + getLastId + '">';
//                                }
//                                else{
//                                    html += '<a href="<?php #echo base_url() ; ?>' + OTsTomaEstado[i]["ActCod"] + '/' + OTsTomaEstado[i]["GpeVal"] + '/ver/' + getLastId +'">';
//                                }
                                html += '<a href="<?php echo base_url() ; ?>' + OTsTomaEstado[i]["ActCod"] + '/orden/ver/' + getLastId +'">';
                                html += '<h3 class="panel-title">';
                                html += '<i class="fa fa-file-text text-red"></i> <span style="font-size:12px">Orden de Trabajo N°: ' + OTsTomaEstado[i]["OrtNum"] + '</span><br></h3>';
                                html += '<span style="font-size:9.5px;margin-left:12px; color:#72afd2"> Actividad : ' + OTsTomaEstado[i]["ActDes"] + '</span><br>';
                                html += '<p style="display: inline; color:#888888; font-size:11px;margin-left:20px;"><i class="fa fa-calendar"></i> F. Ejecución: ' + OTsTomaEstado[i]["OrtFchEj"] + '</p><br>';
                                html += '<p style="display: inline; color:#888888; font-size:11px;margin-left:20px;"><i class="fa fa-calendar"></i> F. Máxima: ' + OTsTomaEstado[i]["OrtFchEm"] + '</p>';
                                html += '</a>';
                                html += '</li>';
                            }
                            $("#OTsTENoRec").append(html);
                            html = '';
                        } else
                        {
                            $("#OTsTENoRec").append("<li class='text-center'>No tiene Ordenes de Trabajo No Recibidas</li>");
                            //alertify.set('notifier','position', 'top-right');
                            //alertify.success('No tiene Ordenes de Trabajo No Recibidas');
                            
                        }
                        $(".lastId").attr("id", getLastId);
                    },
                    error: function ()
                    {
                        //hacer algo cuando ocurra un error
                        $("#OTsTENoRec").append("<li class='text-center'>No ha sido posible cargar las Ordenes de Trabajo</li>");
                    }
                });

                $.ajax({
                    url: "<?php echo base_url() . 'toma_estado/obtener_ordenes_rec_x_usuario_json'; ?>",
                    type: "POST",
                    data: ({idUsuario: idUsuario}),
                    dataType: "html",
                    success: function (data)
                    {
                        var OTsTomaEstado = JSON.parse(data);

                        console.log(OTsTomaEstado);
                        if (OTsTomaEstado.length != 0)
                        {
                            $('#numOTTERec').text(OTsTomaEstado.length);
                            $('#nroNotifRec').text('Tiene ' + OTsTomaEstado.length + ' Ordenes de trabajo Recibidas por la Contratista');
                            $("#OTsTERec").append(html);
                            for (var i = 0; i < OTsTomaEstado.length; i++)
                            {
                                var getLastId = OTsTomaEstado[i]["OrtId"];
                                html += '<li>';
//                                if(OTsTomaEstado[i]["ActCod"]==='toma_estado')
//                                {
//                                    html += '<a href="<?php #echo base_url() . 'toma_estado/orden/ver/'; ?>' + getLastId + '">';
//                                }
//                                else{
//                                    html += '<a href="<?php #echo base_url() ; ?>' + OTsTomaEstado[i]["ActCod"] + '/' + OTsTomaEstado[i]["GpeVal"] + '/ver/' + getLastId +'">';
//                                }
                                html += '<a href="<?php echo base_url() ; ?>' + OTsTomaEstado[i]["ActCod"] + '/orden/ver/' + getLastId +'">';
                                html += '<h3 class="panel-title">';
                                html += '<i class="fa fa-file-text text-aqua"></i> <span style="font-size:12px">Orden de Trabajo N°: ' + OTsTomaEstado[i]["OrtNum"] + '</span><br></h3>';
                                html += '<span style="font-size:9.5px;margin-left:16px; color:#72afd2"> Actividad : ' + OTsTomaEstado[i]["ActDes"] + '</span><br>';
                                html += '<p style="display: inline; color:#888888; font-size:11px;margin-left:24px;"><i class="fa fa-calendar"></i> F. Recepción: ' + OTsTomaEstado[i]["OrtFchRs"] + '</p><br>';
                                html += '<p style="display: inline; color:#888888; font-size:11px;margin-left:24px;"><i class="fa fa-calendar"></i> F. Ejecución: ' + OTsTomaEstado[i]["OrtFchEj"] + '</p><br>';
                                html += '<p style="display: inline; color:#888888; font-size:11px;margin-left:24px;"><i class="fa fa-calendar"></i> F. Máxima: ' + OTsTomaEstado[i]["OrtFchEm"] + '</p>';
                                html += '</a>';
                                html += '</li>';

                            }
                            $("#OTsTERec").append(html);
                            html = '';
                        } else
                        {
                            $("#OTsTERec").append("<li class='text-center'>No tiene Ordenes de Trabajo Recibidas por la Contratista</li>");
                        }
                        $(".lastId").attr("id", getLastId);
                    },
                    error: function ()
                    {
                        //hacer algo cuando ocurra un error
                        $("#OTsTERec").append("<li class='text-center'>No ha sido posible cargar las Ordenes de Trabajo.</li>");
                    }
                });

                $.ajax({
                    url: "<?php echo base_url() . 'toma_estado/obtener_ordenes_cer_x_usuario_json'; ?>",
                    type: "POST",
                    data: ({idUsuario: idUsuario}),
                    dataType: "html",
                    success: function (data)
                    {
                        var OTsTomaEstado = JSON.parse(data);

                        console.log(OTsTomaEstado);
                        if (OTsTomaEstado.length != 0)
                        {
                            $('#numOTTECer').text(OTsTomaEstado.length);
                            $('#nroNotifCer').text('Tiene ' + OTsTomaEstado.length + ' Ordenes de trabajo Cerradas por la Contratista');
                            $("#OTsTECer").append(html);
                            for (var i = 0; i < OTsTomaEstado.length; i++)
                            {
                                var getLastId = OTsTomaEstado[i]["OrtId"];
                                html += '<li>';
//                                if(OTsTomaEstado[i]["ActCod"]==='toma_estado')
//                                {
//                                    html += '<a href="<?php #echo base_url() . 'toma_estado/orden/ver/'; ?>' + getLastId + '">';
//                                }
//                                else{
//                                    html += '<a href="<?php #echo base_url() ; ?>' + OTsTomaEstado[i]["ActCod"] + '/' + OTsTomaEstado[i]["GpeVal"] + '/ver/' + getLastId +'">';
//                                }
                                html += '<a href="<?php echo base_url() ; ?>' + OTsTomaEstado[i]["ActCod"] + '/orden/ver/' + getLastId +'">';
                                html += '<h3 class="panel-title">';
                                html += '<i class="fa fa-file-text text-green"></i> <span style="font-size:12px">Orden de Trabajo N°: ' + OTsTomaEstado[i]["OrtNum"] + '</span><br></h3>';
                                html += '<span style="font-size:9.5px;margin-left:12px; color:#72afd2"> Actividad : ' + OTsTomaEstado[i]["ActDes"] + '</span><br>';
                                html += '<p style="display: inline; color:#888888; font-size:11px;margin-left:20px;"><i class="fa fa-calendar"></i> F. Envio Contratista: ' + OTsTomaEstado[i]["OrtFchEr"] + '</p><br>';
                                html += '<p style="display: inline; color:#888888; font-size:11px;margin-left:20px;"><i class="fa fa-calendar"></i> F. Ejecución: ' + OTsTomaEstado[i]["OrtFchEj"] + '</p><br>';
                                html += '<p style="display: inline; color:#888888; font-size:11px;margin-left:20px;"><i class="fa fa-calendar"></i> F. Máxima: ' + OTsTomaEstado[i]["OrtFchEm"] + '</p>';
                                html += '</a>';
                                html += '</li>';
                            }
                            $("#OTsTECer").append(html);
                            html = '';
                        } else
                        {
                            $("#OTsTECer").append("<li class='text-center'>No tiene Ordenes de Trabajo Cerradas por la Contratista</li>");
                        }
                        $(".lastId").attr("id", getLastId);
                    },
                    error: function ()
                    {
                        //hacer algo cuando ocurra un error
                        $("#OTsTECer").append("<li class='text-center'>No ha sido posible cargar las Ordenes de Trabajo.</li>");
                    }
                });

                $.ajax({
                    url: "<?php echo base_url() . 'toma_estado/obtener_ordenes_en_ejec_x_usuario_json'; ?>",
                    type: "POST",
                    data: ({idUsuario: idUsuario}),
                    dataType: "html",
                    success: function (data)
                    {
                        var OTsTomaEstado = JSON.parse(data);
                        console.log(OTsTomaEstado);
                        if (OTsTomaEstado.length != 0)
                        {
                            $('#numOTEnEjecucion').text(OTsTomaEstado.length);
                            $('#numOTEnEjecucionDes').text('Tiene ' + OTsTomaEstado.length + ' Ordenes de Trabajo en Ejecución');
                            $("#OTsTomaEstadoEje").append(html);
                            for (var i = 0; i < OTsTomaEstado.length; i++)
                            {
                                var estilo = "danger";
                                if(OTsTomaEstado[i]["avance"]>=0&&OTsTomaEstado[i]["avance"]<10){estilo = "danger";}
                                if(OTsTomaEstado[i]["avance"]>10&&OTsTomaEstado[i]["avance"]<50){estilo = "warning";}
                                if(OTsTomaEstado[i]["avance"]>50&&OTsTomaEstado[i]["avance"]<70){estilo = "primary";}
                                if(OTsTomaEstado[i]["avance"]>70&&OTsTomaEstado[i]["avance"]<=100){estilo = "success";}
                                var getLastId = OTsTomaEstado[i]["OrtId"];
                                html += '<li>';
//                                if(OTsTomaEstado[i]["ActCod"]==='toma_estado')
//                                {
//                                    html += '<a href="<?php #echo base_url() . 'toma_estado/orden/avance/'; ?>' + getLastId + '">';
//                                }
//                                else{
//                                    html += '<a href="<?php #echo base_url() ; ?>' + OTsTomaEstado[i]["ActCod"] + '/' + OTsTomaEstado[i]["GpeVal"] + '/avance/' + getLastId +'">';
//                                }
                                html += '<a href="<?php echo base_url() ; ?>' + OTsTomaEstado[i]["ActCod"] + '/orden/avance/' + getLastId +'">';
                                html += '<h3 class="panel-title">';
                                html += '<i class="fa fa-file-text text-primary"></i> <span style="font-size:12px">Orden de Trabajo N°: ' + OTsTomaEstado[i]["OrtNum"] + '</span><br></h3>';
                                html += '<span style="font-size:9.5px;margin-left:12px; color:#72afd2"> Actividad : ' + OTsTomaEstado[i]["ActDes"] + '</span>';
                                html += '<h3><small class="sr-only" style="margin-left:12px" >'+ OTsTomaEstado[i]["avance"] +'% Completo</small><small class="pull-right">'+ OTsTomaEstado[i]["avance"] +'%</small></h3>';
                                html += '<div class="progress xs progress-striped active" style="margin-left:12px" ><div class="progress-bar progress-bar-'+estilo+'" style="width: '+ OTsTomaEstado[i]["avance"] +'%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div></div>';
                                html += '</a>';
                                html += '</li>';
                            }
                            $("#OTsTomaEstadoEje").append(html);
                            html = '';
                        } else
                          {
                            $("#OTsTomaEstadoEje").append("<li class='text-center'>No tiene Ordenes de Trabajo en Ejecución</li>");
                          }
                          $(".lastId").attr("id", getLastId);
                    },
                    error: function ()
                    {
                        //hacer algo cuando ocurra un error
                        $("#OTsTomaEstadoEje").append("<li class='text-center'>No ha sido Posible cargar las Ordenes de Trabajo.</li>");
                    }
                });
                
                $.ajax({
                    url: "<?php echo base_url() . 'configuracion/nivel_comercial/subciclos_sin_agrupar_notificaciones_json'; ?>",
                    type: "GET",
                    dataType: "html",
                    success: function (data)
                    {
                        var SubCiclosSinAgrupar = JSON.parse(data);
                        var subciclos = "";
                        if (SubCiclosSinAgrupar.length != 0)
                        {
                            for (var i = 0; i < SubCiclosSinAgrupar.length; i++)
                            {
                                subciclos += "Subciclo : " + SubCiclosSinAgrupar[i]["GprCod"] 
                                        + ", Localidades : " + SubCiclosSinAgrupar[i]["GprDes"] 
                                        + "<a href='<?php echo base_url() ; ?>configuracion/nivel_comercial/"+SubCiclosSinAgrupar[i]["NgrId"] +"/detalle'>  >> Click Aqui <<  </a><br>";
                            }
                            /*
                            if(!alertify.errorAlert){
                              alertify.dialog('errorAlert',function factory(){
                                return{
                                        build:function(){
                                            var errorHeader = '<span class="fa fa-times-circle fa-2x" '
                                            +    'style="vertical-align:middle;color:#e10000;">'
                                            + '</span> Faltan Agrupar Subciclo(s)';
                                            this.setHeader(errorHeader);
                                        }
                                    };
                                },true,'alert');
                            }
                            alertify.errorAlert("<b>Subciclos:</b><br>" + subciclos );
                            */
                           // alertify.alert('Faltan Agrupar Subciclo(s)', '<b>Subciclos:</b><br>' + subciclos , function(){ alertify.error('Faltan Agrupar Subciclo(s)'); });
                           alertify.error('Faltan Agrupar Subciclo(s)<br>' + '<b>Subciclos:</b><br>' + subciclos);
                        } 
                    },     
                    error: function( jqXHR, textStatus, errorThrown ) {
                        if (jqXHR.status === 0) {
                          sweetAlert("Error", "Not connect: Verify Network.", "error");  
                        } else if (jqXHR.status == 404) {
                          sweetAlert("Error", "Requested page not found [404]", "error");  
                        } else if (jqXHR.status == 500) {
                          sweetAlert("Error", "Internal Server Error [500]", "error");    
                        } else if (textStatus === 'parsererror') {
                          sweetAlert("Error", "Requested JSON parse failed.", "error");    
                        } else if (textStatus === 'timeout') {
                          sweetAlert("Error", "Time out error.", "error");    
                        } else if (textStatus === 'abort') {
                          sweetAlert("Error", "Ajax request aborted.", "error");    
                        } else {
                          sweetAlert("Error", 'Uncaught Error: ' + jqXHR.responseText , "error");    
                        }
                    }
                });
        });                       
    </script>
</body>
</html>