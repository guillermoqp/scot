<!-- sweetAlert. -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/sweetalert/sweetalert2.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url() ?>frontend/plugins/sweetalert/sweetalert2.min.js"></script> 
<section class="content">
      <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <script>
                    swal("Dias de Acceso al Sistema", "<?php echo $_SESSION['mensaje'][1]; ?>" , "<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'warning' : 'success'; ?>"); 
                </script>
            <?php } ?>
            <!--
                <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Actividades Comerciales</h3>                                   
                </div>
                <div class="box-body">
                <div class="row">
                <?php if (array_key_exists('toma_estado', $userdata['permisos'])) { ?>  
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4>T</h4>
                        <p>Toma de estado</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-clipboard"></i>
                      </div>
                      <?php if (array_key_exists('cronograma_anual', $userdata['permisos']['toma_estado'])) { ?>         
                        <a href="<?php echo base_url() ?>toma_estado/cronograma_anual" class="small-box-footer">
                         <i class="fa fa-calendar"></i> Cronograma Anual
                        </a>
                      <?php } ?>
                      <?php if (array_key_exists('cron_lectura_periodo', $userdata['permisos']['toma_estado'])) { ?>
                      <a href="<?php echo base_url() ?>toma_estado/cronograma_mensual" class="small-box-footer">
                       <i class="fa fa-calendar"></i> Cronograma Mensual
                      </a>
                      <?php } ?>
                      <?php if (array_key_exists('orden_trabajo_lectura', $userdata['permisos']['toma_estado'])) { ?>  
                      <a href="<?php echo base_url() ?>toma_estado/ordenes" class="small-box-footer">
                       <i class="fa fa-book"></i> Ordenes de Trabajo
                      </a>
                      <?php } ?>
                      <?php if (array_key_exists('liquidaciones', $userdata['permisos']['toma_estado'])) { ?>
                      <a href="<?php echo base_url() ?>toma_estado/liquidaciones" class="small-box-footer">
                       <i class="fa fa-dollar"></i> Liquidaciones
                      </a>
                      <?php } ?>
                      <?php if (array_key_exists('lecturistas', $userdata['permisos']['toma_estado'])) { ?>
                       <a href="<?php echo base_url() ?>toma_estado/lecturista" class="small-box-footer">
                        <i class="fa fa-book"></i> Lecturistas
                      </a>
                      <?php } ?>
                    </div>
                </div>
                <?php } ?>
                
                <?php if (array_key_exists('distribucion', $userdata['permisos'])) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4>D</h4>
                        <p>Distribución de Recibos y Comunicaciones al Cliente</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-barcode"></i>
                      </div>
                    </div>
                </div>
                <?php } ?>
                
                <?php if (array_key_exists('inspeccion', $userdata['permisos'])) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4>I</h4>
                        <p>Inspecciones</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-search"></i>
                      </div>
                    </div>
                </div>
                <?php } ?>
                   
                <?php if (array_key_exists('recaudacion', $userdata['permisos'])) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4>R</h4>
                        <p>Recaudación, Cancelación y Cautela de recibos</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-folder-open"></i>
                      </div>
                    </div>
                </div>
                <?php } ?>    
                         
                <?php if (array_key_exists('persuasivo', $userdata['permisos'])) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4>A</h4>
                        <p>Acciones Persuasivas</p>
                      </div>
                      <div class="icon">
                         <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                </div>
                <?php } ?>        
                    
                <?php if (array_key_exists('acondicionamiento', $userdata['permisos'])) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4>C</h4>
                        <p>Acondicionamiento de Conexiones Domiciliarias</p>
                      </div>
                      <div class="icon">
                         <i class="fa fa-home"></i>
                      </div>
                    </div>
                </div>
                <?php } ?>         
  
            </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Configuraciones</h3>                                   
            </div>
            <div class="box-body"> 
            <div class="row">    
                <?php if (array_key_exists('contratacion', $userdata['permisos'])) { ?>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-aqua"><i class="fa fa-file-text-o"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text">Contratación<br>
                        <?php if (array_key_exists('contratista', $userdata['permisos']['contratacion'])) { ?>    
                            <a href="<?php echo base_url() ?>contratistas"><i class="fa fa-building-o"></i> Contratistas</a><br>
                        <?php } ?>
                        <?php if (array_key_exists('contrato', $userdata['permisos']['contratacion'])) { ?>
                            <a href="<?php echo base_url() ?>contratos"><i class="fa fa-book"></i> Contratos</a>
                        <?php } ?>
                      </div>
                    </div>
                </div>
                <?php } ?>
                
                <?php if (array_key_exists('permisos', $userdata['permisos'])) { ?>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-green"> <i class="fa fa-unlock-alt"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text">Permisos<br>
                         <?php if (array_key_exists('usuario', $userdata['permisos']['permisos'])) { ?>
                            <a href="<?php echo base_url() ?>permisos/usuarios"><i class="fa fa-group"></i> Usuarios</a><br>
                        <?php } ?>
                        <?php if (array_key_exists('rol', $userdata['permisos']['permisos'])) { ?>
                            <a href="<?php echo base_url() ?>permisos/perfil"><i class="fa fa-legal"></i> Perfiles</a><br>
                        <?php } ?>
                            
                        <?php if (array_key_exists('cargo', $userdata['permisos']['permisos'])) { ?>
                            <a href="<?php echo base_url() ?>permisos/cargo"><i class="fa fa-legal"></i> Cargos</a><br>
                        <?php } ?>  
                            
                        <?php if (array_key_exists('nivel', $userdata['permisos']['permisos'])) { ?>
                            <a href="<?php echo base_url() ?>permisos/niveles"><i class="fa fa-sitemap"></i> Niveles</a>
                        <?php } ?>
                      </div>
                    </div>
                </div>
                <?php } ?>

                
                <?php if (array_key_exists('directorio', $userdata['permisos'])) { ?>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-yellow"> <i class="fa fa-book"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text">Directorio<br>
                        <?php if (array_key_exists('directorio_contratante', $userdata['permisos']['directorio'])) { ?>
                            <a href="<?php echo base_url() . 'directorio/eps' ?>"><i class="fa fa-exclamation-circle"></i> EPS</a><br>
                        <?php } ?>
                        <?php if (array_key_exists('directorio_contratista', $userdata['permisos']['directorio'])) { ?>
                            <a href="<?php echo base_url() . 'directorio/contratista' ?>"><i class="fa fa-exclamation-circle"></i> Contratista</a><br>
                        <?php } ?>
                      </div>
                    </div>
                </div>
                <?php } ?>
                
                <?php if (array_key_exists('configuracion', $userdata['permisos'])) { ?>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-red"><i class="fa fa-gear"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text">Configuración<br>
                        <?php if (array_key_exists('grupo_penalidad', $userdata['permisos']['configuracion'])) { ?>
                            <a href="<?php echo base_url() . 'configuracion/grupos_penalidad' ?>"><i class="fa fa-exclamation-circle"></i>G.Penalidad</a><br>
                        <?php } ?>
                        <?php if (array_key_exists('penalidad', $userdata['permisos']['configuracion'])) { ?>
                            <a href="<?php echo base_url() . 'configuracion/penalidades' ?>"><i class="fa fa-exclamation-circle"></i>Penalidades</a><br>
                        <?php } ?>
                        <?php if (array_key_exists('grupo_observacion', $userdata['permisos']['configuracion'])) { ?>
                            <a href="<?php echo base_url() . 'configuracion/grupos_observacion' ?>"><i class="fa fa-eye"></i>G.Observaciones</a><br>
                        <?php } ?> 
                        <?php if (array_key_exists('observacion', $userdata['permisos']['configuracion'])) { ?>
                            <a href="<?php echo base_url() . 'configuracion/observaciones' ?>"><i class="fa fa-eye"></i> Observaciones</a><br>
                        <?php } ?>      
                         <?php if (array_key_exists('variable', $userdata['permisos']['configuracion'])) { ?>
                            <a href="<?php echo base_url() . 'configuracion/variables' ?>"><i class="fa fa-tasks"></i> Variables G.</a><br>
                        <?php } ?>          
                      </div>
                    </div>
                </div>
                <?php } ?>
                </div>
                <div class="row"> 
                <?php if (array_key_exists('configuracion_global', $userdata['permisos'])) { ?>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-red"><i class="fa fa-gear"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text">Configuración<br>
                        <?php if (array_key_exists('contratante', $userdata['permisos']['configuracion_global'])) { ?>
                            <a href="<?php echo base_url() . 'configuracion/contratantes' ?>"><i class="fa fa-building-o"></i> EPSs</a><br>
                        <?php } ?>
                        <?php if (array_key_exists('unidad_medida', $userdata['permisos']['configuracion_global'])) { ?>
                            <a href="<?php echo base_url() . 'configuracion/unidades' ?>"><i class="fa fa-ticket"></i> Unidad de Medida</a><br>
                        <?php } ?>
                         <?php if (array_key_exists('actividad', $userdata['permisos']['configuracion_global'])) { ?>
                            <a href="<?php echo base_url() . 'configuracion/actividad' ?>"><i class="fa fa-eye"></i> Actividades Comerciales</a><br>
                        <?php } ?> 
                        <?php if (array_key_exists('subactividad', $userdata['permisos']['configuracion_global'])) { ?>
                            <a href="<?php echo base_url() . 'configuracion/subactividad' ?>"><i class="fa fa-eye"></i> Sub-Actividades Comerciales</a><br>
                        <?php } ?>      
                        <?php if (array_key_exists('parametros', $userdata['permisos']['configuracion_global'])) { ?>
                            <a href="<?php echo base_url() . 'configuracion/' ?>"><i class="fa fa-eye"></i> Parámetros Globales</a><br>
                        <?php } ?>          
                      </div>
                    </div>
                </div>
                <?php } ?>
                </div>
        </div>
    </div>-->
          
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Propuesta Contractual de Servicios Comerciales</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped" aria-describedby="example1_info">
                        <thead>
                            <tr class="info">
                                <th>Servicios Comerciales</th>
                                <th>Unidad de Medida</th>
                                <th>Metrado</th>
                                <th>Precio Unitario</th>
                                <th>Presupuesto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <b>Inspecciones</b>
                                    <br>-Interna de Reclamo uso Unico
                                    <br>-Interna de Reclamo uso Multiple
                                    <br>-Interna uso Unico
                                    <br>-Interna uso Multiple
                                    <br>-Especial con Geofono
                                    <br>-Externa
                                    <br>-Actualización Catastral
                                </td>
                                <td>
                                    <br>Inspección
                                    <br>Inspección
                                    <br>Inspección
                                    <br>Inspección
                                    <br>Inspección
                                    <br>Inspección
                                    <br>Inspección
                                </td>
                                <td style="text-align: right">
                                    <br><?php echo number_format(13388558, 2); ?>
                                    <br><?php echo number_format(167064, 2); ?>
                                    <br><?php echo number_format(167064, 2); ?>
                                    <br><?php echo number_format(13388558, 2); ?>
                                    <br><?php echo number_format(13388558, 2); ?>
                                    <br><?php echo number_format(167064, 2); ?>
                                    <br><?php echo number_format(167064, 2); ?>
                                </td>
                                <td style="text-align: right">
                                    <br><?php echo number_format(4.88, 2); ?>
                                    <br><?php echo number_format(7.53, 2); ?>
                                    <br><?php echo number_format(2.44, 2); ?>
                                    <br><?php echo number_format(5.86, 2); ?>
                                    <br><?php echo number_format(19.99, 2); ?>
                                    <br><?php echo number_format(1.23, 2); ?>
                                    <br><?php echo number_format(0.87, 2); ?>
                                </td>
                                <td style="text-align: right">
                                    <br><?php echo number_format(227554.86, 2); ?>
                                    <br><?php echo number_format(30068.28, 2); ?>
                                </td>
                            </tr>


                            <tr>
                                <td><b>Distribución de Recibos y Comunicaciones al Cliente</b>
                                    <br>--Continua
                                    <br>--Dispersa
                                    <br>--Con Cedula 
                                    <br>--Sin Cedula
                                </td>
                                <td>
                                    <br>Recibo
                                    <br>Recibo
                                    <br>Comunicac.
                                    <br>Comunicac.
                                </td>
                                <td style="text-align: right">
                                    <br><?php echo number_format(13388558, 2); ?>
                                    <br><?php echo number_format(167064, 2); ?>
                                    <br><?php echo number_format(167065, 2); ?>
                                    <br><?php echo number_format(167063, 2); ?>
                                </td>
                                <td style="text-align: right">
                                    <br><?php echo number_format(0.12, 2); ?>
                                    <br><?php echo number_format(0.38, 2); ?>
                                    <br><?php echo number_format(1.20, 2); ?>
                                    <br><?php echo number_format(0.38, 2); ?>
                                </td>
                                <td style="text-align: right">
                                    <br><?php echo number_format(227554.86, 2); ?>
                                    <br><?php echo number_format(30068.28, 2); ?>
                                    <br><?php echo number_format(30068.28, 2); ?>
                                    <br><?php echo number_format(30068.28, 2); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <b>Toma de Estado</b>
                                    <br>--Con Capturador
                                    <br>--Con Hoja
                                </td>
                                <td>
                                    <br>Lectura
                                    <br>Lectura
                                </td>
                                <td style="text-align: right">
                                    <br><?php echo number_format(13388558, 2); ?>
                                    <br><?php echo number_format(167064, 2); ?>
                                </td>
                                <td style="text-align: right">
                                    <br><?php echo number_format(0.17, 2); ?>
                                    <br><?php echo number_format(0.18, 2); ?>
                                </td>
                                <td style="text-align: right">
                                    <br><?php echo number_format(227554.86, 2); ?>
                                    <br><?php echo number_format(30068.28, 2); ?>
                                </td>
                            </tr>

                        </tbody>   
                    </table>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Resumen de Valorización</h3>                                   
                </div>
                <div class="box-body table-responsive">
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                            <tr class="info">
                                <th>Actividad</th>
                                <th>Presupuesto</th>
                                <th>Valorización N° 8</th>
                                <th>% Avance </th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>Inspecciones</td>
                                <td style="text-align: right"><?php echo number_format(446105.74, 2); ?></td>
                                <td style="text-align: right"><?php echo number_format(29142.56, 2); ?></td>
                                <td>
                                    <span class="badge bg-red">6.53%</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Toma de Estado</td>
                                <td style="text-align: right"><?php echo number_format(374764.38, 2); ?></td>
                                <td style="text-align: right"><?php echo number_format(37630.83, 2); ?></td>
                                <td>
                                    <span class="badge bg-red">10.04%</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Distribucion de Recibos y Comunicaciones al Cliente</td>
                                <td style="text-align: right"><?php echo number_format(408732.53, 2); ?></td>
                                <td style="text-align: right"><?php echo number_format(33636.30, 2); ?></td>
                                <td>
                                    <span class="badge bg-red">8.23%</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Avance de Actividades Comerciales - Mes Enero 2016</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr class="info">
                            <th style="width: 10px">#</th>
                            <th>Actividad</th>
                            <th>Progreso</th>
                            <th style="width: 40px">Porcentaje</th>
                        </tr>
                        <tr>
                            <td>1.</td>
                            <td>Toma de Estado</td>
                            <td>
                                <div class="progress xs">
                                    <div class="progress-bar progress-bar-success" style="width: 55%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-green">55%</span></td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Distribucion de Recibos y Comunicaciones al Cliente</td>
                            <td>
                                <div class="progress xs">
                                    <div class="progress-bar progress-bar-success" style="width: 70%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-green">70%</span></td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Inspecciones</td>
                            <td>
                                <div class="progress xs progress-striped active">
                                    <div class="progress-bar progress-bar-danger" style="width: 30%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-red">30%</span></td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>Recaudaciones, Cancelaciones y Cautela de Recibos</td>
                            <td>
                                <div class="progress xs progress-striped active">
                                    <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-green">90%</span></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Gráfico: Resumen de Valorización</h3>
                </div>
                <div class="box-body">
                <div class="row">
                    <div class="chart" id="bar-chart" style="height: 300px;"></div>
                </div> 
                </div>   
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Gráfico: Curva S</h3>
                </div> 
                <div class="box-body">       
                    <div class="row">
                        <div class="chart" id="bar-chart2" style="height: 250px;"></div>
                    </div>
                </div>
            </div>
        </div>
      </div>
</section>
        <!-- DATA TABES SCRIPT -->
        <script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        
        <!-- Morris.js charts -->
        <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="<?php echo base_url() ?>frontend/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(function () 
            {
                $("#example1").dataTable();
                //$("#example2").dataTable();
            });
        </script>
        <!-- page script -->
        <script type="text/javascript">
        $(function() 
        {
            
        "use strict";
                var bar = new Morris.Bar({
                    element: 'bar-chart',
                    resize: true,
                    data: 
                    [
                        {y: 'Mes 1', a: 397985, b: 399985},
                        {y: 'Mes 2', a: 397985, b: 399985},
                        {y: 'Mes 3', a: 395985, b: 399985},
                        {y: 'Mes 4', a: 395985, b: 399985},
                        {y: 'Mes 5', a: 392985, b: 399985},
                        {y: 'Mes 6', a: 311985, b: 399985},
                        {y: 'Mes 7', a: 400000, b: 300000},
                        {y: 'Mes 8', a: 400012, b: 333850},
                        {y: 'Mes 9', a: 400412, b: 0},
                        {y: 'Mes 10', a: 400536, b: 0},
                        {y: 'Mes 11', a: 400022, b: 0},
                        {y: 'Mes 12', a: 400123, b: 0}
                    ],
                    barColors: ['#00065a', '#f51954'],
                    xkey: 'y',
                    ykeys: ['a', 'b'],
                    labels: ['Presupuesto Mensual', 'Presupuesto Ejecutado'],
                    fillOpacity: 0.6,
                    behaveLikeLine: true,
                    resize: true,
                    pointFillColors:['#ffffff'],
                    pointStrokeColors: ['black'],
                    lineColors:['gray','red'],
                    hideHover: 'auto'
                });
                var bar = new Morris.Bar({
                    element: 'bar-chart2',
                    resize: true,
                    data: [
                        {y: 'Mes 1', a: 500000, b: 10000},
                        {y: 'Mes 2', a: 1000000, b: 30000},
                        {y: 'Mes 3', a: 1500000, b: 60000},
                        {y: 'Mes 4', a: 2000000, b: 90000},
                        {y: 'Mes 5', a: 2500000, b: 1500000},
                        {y: 'Mes 6', a: 3000000, b: 2000000},
                        {y: 'Mes 7', a: 3300000, b: 2500000},
                        {y: 'Mes 8', a: 3600000, b: 3000000},
                        {y: 'Mes 9', a: 4200000, b: 0},
                        {y: 'Mes 10', a: 4300000, b: 0},
                        {y: 'Mes 11', a: 4400000, b: 0},
                        {y: 'Mes 12', a: 4500000, b: 0}
                    ],
                    barColors: ['#00a65a', '#f56954'],
                    xkey: 'y',
                    ykeys: ['a', 'b'],
                    labels: ['Presupuesto Acumulado', 'Presupuesto Ejecutado'],
                    hideHover: 'auto'
                });
            });
        </script>
        
