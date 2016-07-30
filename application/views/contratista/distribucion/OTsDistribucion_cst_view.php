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
                    <h3 class="box-title">Ordenes de Trabajo Registradas de Distribucion de Recibos y Comunicaciones</h3>                             
                </div>
                <div class="box-body table-responsive">
                    <table id="ordenesDeTrabajo" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>N° Orden</th>
                                <th>Periodo</th>
                                <th>Nivel Comercial</th>
                                <th>Metrado(Lecturas)</th>
                                <th>Fechas</th>
                                <th>Estado / % Avance</th>
                                <th style="width: 90px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="tbodyP" role="alert" aria-live="polite" aria-relevant="all">
                                <?php foreach ($ordenesTrabajo as $ordenTrabajo): ?>
                                <tr>
                                    <td><?php echo $ordenTrabajo['OrtNum']; ?></td>
                                    <td><?php echo $ordenTrabajo['periodo']['PrdCod'] ?> (<?php echo strtoupper($this->utilitario_cls->nombreMes($ordenTrabajo['periodo']['PrdOrd'])) ?>) / <?php echo $ordenTrabajo['periodo']['PrdAni'] ?> </td>
                                    <td>
                                        <?php echo $ordenTrabajo['nivel_comercial']['NgrDes'] ?> : 
                                        <?php echo $ordenTrabajo['ciclos']; ?>
                                    </td> 
                                    <td><b>Total: </b><?php echo $ordenTrabajo['cantidad']['dbn_reg']+$ordenTrabajo['cantidad']['dbn_sup'] ?>
                                        <br>Regular: <?php echo $ordenTrabajo['cantidad']['dbn_reg']; ?>
                                        <br>Muestra/Supervisión: <?php echo $ordenTrabajo['cantidad']['dbn_sup']; ?>
                                    </td>
                                    <td>
                                        <b>Fecha de Ejecución: </b><?php echo date_format(date_create($ordenTrabajo['OrtFchEj']), 'd-m-Y'); ?><br>
                                        <b>Fecha Máxima Recepción: </b><?php echo date_format(date_create($ordenTrabajo['OrtFchEm']), 'd-m-Y H:i:s'); ?>
                                    </td> 
                                    <td><?php 
                                    if($ordenTrabajo['DprCod'] == 'eje'){   echo '<small class="label label-primary"><i class="fa fa-clock-o"></i> En Ejecucion</small>';}
                                    if($ordenTrabajo['DprCod'] == 'rec'){   echo '<small class="label label-info"><i class="fa fa-clock-o"></i> Recibido</small>';}
                                    if($ordenTrabajo['DprCod'] == 'no_rec'){   echo '<small class="label label-danger"><i class="fa fa-clock-o"></i> No Recibido</small>';}
                                    if($ordenTrabajo['DprCod'] == 'cer'){   echo '<small class="label label-success"><i class="fa fa-clock-o"></i>  Cerrado</small>';}
                                    if($ordenTrabajo['DprCod'] == 'liq'){   echo '<small class="label label-warning"><i class="fa fa-clock-o"></i>  Liquidado</small>';}
                                    ?>
                                    <br>
                                    <?php $estilos = array('eje' => 'primary', 'rec' => 'info', 'no_rec' => 'danger', 'cer' => 'success', 'liq' => 'warning'); ?>
                                        <a href="<?php echo base_url() .'cst/distribucion/orden/avance/'.$ordenTrabajo['OrtId']?>"><small class="label label-<?php echo $estilos[$ordenTrabajo['DprCod']]; ?>"><?php echo $ordenTrabajo['avance']; ?>%</small>
                                        <div class="progress xs progress-striped active">
                                            <div class="progress-bar progress-bar-<?php echo $estilos[$ordenTrabajo['DprCod']]; ?>" style="width: <?php echo $ordenTrabajo['avance']; ?>%"></div>
                                        </div>
                                        </a> 
                                    </td>
                                    <td>
                                        <?php if ($this->acceso_cls->tienePermiso('ver_orden_distribucion')) { ?>
                                            <a href="<?php echo base_url() . 'cst/distribucion/orden/ver/' . $ordenTrabajo['OrtId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Ver">
                                            <i class="fa fa-eye"></i>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('ver_orden_distribucion')) { ?>
                                            <a href="<?php echo base_url() .'cst/distribucion/orden/avance/'.$ordenTrabajo['OrtId']?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Avance">
                                            <span class="glyphicon glyphicon-chevron-right"></span>
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
        $("#ordenesDeTrabajo").dataTable({
  		"bSort": false,
  		"paging": true
  	});
    });
</script>