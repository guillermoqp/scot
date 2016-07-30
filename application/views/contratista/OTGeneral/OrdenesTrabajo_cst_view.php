<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Ordenes de Trabajo de <?php echo $proceso; ?></h3>                             
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="ordenesDeTrabajo" class="table table-bordered table-striped">
                        <thead>
                            <tr role="row">
                                <th>Número de Orden</th>
                                <th>Mes de Facturación</th>
                                <!-- <th>Ciclos</th>
                                <th>Metrado</th>
                                <th>Fecha de Envio EPS</th> -->
                                <th>Fecha de Ejecución</th>
                                <!-- <th>Fecha de Recepcion Real</th>  -->
                                <th>Fecha y Hora de Envio Máximo Service</th> 
                                <!-- <th>Fecha Envio Real Service</th>  -->
                                <!-- <th>Fecha de Recepción EPS</th>  -->
                                <th>Sub Actividades a realizar</th>
                                <th>Estado</th>
                                <!--<th>% Avance</th>-->
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody class="tbodyP" role="alert" aria-live="polite" aria-relevant="all">
                                <?php foreach ($ordenesTrabajo as $ordenTrabajo): ?>
                                <tr>
                                    <?php 
                                    $campoDes = $ordenTrabajo['OrtDes'];
                                    $porciones = explode("<br>", $campoDes);
                                    $entregadoA = $porciones[0];
                                    $descripcion = $porciones[1];
                                    $mes = $porciones[2];
                                    $anio = $porciones[3];
                                    ?>
                                    <td><?php echo $ordenTrabajo['OrtNum']; ?></td>
                                    <td><?php echo $mes.' / '.$anio; ?></td> 
                                    <!-- <td><?php echo $ordenTrabajo['ciclos']; ?></td>
                                    <td><?php echo $ordenTrabajo['cantidad']; ?></td>
                                    <td><?php echo date_format(date_create($ordenTrabajo['OrtFchEn']), 'd-m-Y H:i:s'); ?></td>  -->
                                    <td><?php echo date_format(date_create($ordenTrabajo['OrtFchEj']), 'd-m-Y'); ?></td> 
                                    <!--  <td><?php echo date_format(date_create($ordenTrabajo['OrtFchRs']), 'd-m-Y H:i:s'); ?></td>  -->
                                    <td><?php echo date_format(date_create($ordenTrabajo['OrtFchEm']), 'd-m-Y H:i:s'); ?></td> 
                                    <!--  <td><?php echo date_format(date_create($ordenTrabajo['OrtFchEr']), 'd-m-Y H:i:s'); ?></td>  -->
                                   <!--   <td><?php echo date_format(date_create($ordenTrabajo['OrtFchRa']), 'd-m-Y H:i:s'); ?></td>  -->
                                    <td>
                                        <?php foreach ($subactividades as $subactividad): ?>
                                            <?php if($ordenTrabajo['OrtId']===$subactividad['OrtId']){
                                                echo $subactividad['SacDes'];}
                                            ?>
                                        <dd></dd>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><?php 
                                    if($ordenTrabajo['DprCod'] == 'eje'){   echo '<small class="label label-primary"><i class="fa fa-clock-o"></i> En Ejecucion</small>';}
                                    if($ordenTrabajo['DprCod'] == 'rec'){   echo '<small class="label label-info"><i class="fa fa-clock-o"></i> Recibido</small>';}
                                    if($ordenTrabajo['DprCod'] == 'no_rec'){   echo '<small class="label label-danger"><i class="fa fa-clock-o"></i> No Recibido</small>';}
                                    if($ordenTrabajo['DprCod'] == 'cer'){   echo '<small class="label label-success"><i class="fa fa-clock-o"></i>  Cerrado</small>';}
                                    if($ordenTrabajo['DprCod'] == 'liq'){   echo '<small class="label label-warning"><i class="fa fa-clock-o"></i>  Liquidado</small>';}
                                    ?></td>
                                    <!--
                                    <td><?php $estilos = array('eje' => 'primary', 'rec' => 'info', 'no_rec' => 'danger', 'cer' => 'success', 'liq' => 'warning'); ?>
                                        <a href="<?php echo base_url() .'toma_estado/orden/avance/'.$ordenTrabajo['OrtId']?>"><small class="label label-<?php echo $estilos[$ordenTrabajo['DprCod']]; ?>"><?php echo $ordenTrabajo['avance']; ?>%</small>
                                        <div class="progress xs progress-striped active">
                                            <div class="progress-bar progress-bar-<?php echo $estilos[$ordenTrabajo['DprCod']]; ?>" style="width: <?php echo $ordenTrabajo['avance']; ?>%"></div>
                                        </div>
                                        </a> 
                                    </td>
                                    <td><?php 
                                    if($ordenTrabajo['DprCod'] == 'eje'){   echo '<small class="label label-primary">70%</small>
                                            <div class="progress xs progress-striped active">
                                                <div class="progress-bar progress-bar-primary" style="width: 70%"></div>
                                            </div>';}
                                    if($ordenTrabajo['DprCod'] == 'rec'){   echo '<small class="label label-info">5%</small>
                                            <div class="progress xs progress-striped active">
                                                <div class="progress-bar progress-bar-info" style="width: 5%"></div>
                                            </div>';}
                                    if($ordenTrabajo['DprCod'] == 'no_rec'){   echo '<small class="label label-danger">0%</small>
                                            <div class="progress xs progress-striped active">
                                                <div class="progress-bar progress-bar-danger" style="width: 0%"></div>
                                            </div>';}
                                    if($ordenTrabajo['DprCod'] == 'cer'){   echo '<small class="label label-success">100% </small>
                                            <div class="progress xs progress-striped active">
                                                <div class="progress-bar progress-bar-success" style="width: 100%"></div>
                                            </div>';}
                                    if($ordenTrabajo['DprCod'] == 'liq'){   echo '<small class="label label-warning">100% </small>
                                            <div class="progress xs progress-striped active">
                                                <div class="progress-bar progress-bar-warning" style="width: 100%"></div>
                                            </div>';}
                                    ?></td>
                                    -->
                                    <td class=" ">
                                        <?php if ($this->acceso_cls->tienePermiso('ver_orden')) { ?>
                                            <a href="<?php echo base_url() . 'cst/'.$padre.'/'. $hijo .'/ver/' . $ordenTrabajo['OrtId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Ver | Enviar">
                                            <i class="fa fa-eye"></i> | <i class="fa fa-arrow-right"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>    
                        </tbody> 
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
       </div>
    </div>
</section>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- page script -->
<script type="text/javascript">
    $(function () 
    {
        $("#ordenesDeTrabajo").dataTable();
    });
</script>