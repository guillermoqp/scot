<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="control-group">
                            <a href="<?php echo base_url() ?>toma_estado/orden/nuevo" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar Nueva Orden de Trabajo</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="control-group">
                            <a href="<?php echo base_url() ?>toma_estado/reportes/control" class="btn btn-success btn-flat <?php echo (empty($ordenesTrabajo)) ? 'disabled' : ''; ?>"><i class="fa fa-calendar"></i> Reporte Control Mensual</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Ordenes de Trabajo Registradas de Toma de Estado de Suministros</h3>                             
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="ordenesDeTrabajo" class="table table-bordered table-striped table-compact">
                        <thead>
                            <tr Class="info" role="row">
                                <th>N° Orden</th>
                                <th>Periodo Facturación</th>
                                <th>Nivel Comercial</th>
                                <th>Metrado(Lecturas)</th>
                                <th>Fechas</th>
                                <th>Estado / % Avance</th>
                                <th style="width: 180px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="tbodyP" role="alert">
                            <?php foreach ($ordenesTrabajo as $ordenTrabajo): ?>
                                <tr>
                                    <td><?php echo $ordenTrabajo['OrtNum']; ?></td>
                                    <td><?php echo $ordenTrabajo['periodo']['PrdOrd'] ?> (<?php echo strtoupper($this->utilitario_cls->nombreMes($ordenTrabajo['periodo']['PrdOrd'])) ?>) / <?php echo $ordenTrabajo['periodo']['PrdAni'] ?> </td> 
                                    <td>
                                        <?php echo $ordenTrabajo['nivel_comercial']['NgrDes'] ?> : 
                                        <?php echo $ordenTrabajo['ciclos']; ?> 
                                    </td> 
                                    <td><b>Total: </b><?php echo $ordenTrabajo['cantidad']['lec_reg']+$ordenTrabajo['cantidad']['lec_sup']+$ordenTrabajo['cantidad']['lec_ati']; ?>
                                        <br>Regular: <?php echo $ordenTrabajo['cantidad']['lec_reg']; ?>
                                        <br>Muestra/Supervisión: <?php echo $ordenTrabajo['cantidad']['lec_sup']; ?>
                                        <!-- <br>Atipicos: <?php echo $ordenTrabajo['cantidad']['lec_ati']; ?> -->
                                    </td>
                                    <td>
                                        <b>Fecha de Ejecución</b>: <?php echo date_format(date_create($ordenTrabajo['OrtFchEj']), 'd-m-Y'); ?><br>
                                        <b>Fecha Máxima Recepción</b>: <?php echo date_format(date_create($ordenTrabajo['OrtFchEm']), 'd-m-Y H:i:s'); ?>
                                    </td> 
                                    <td>
                                        <label>Regular:</label> <?php echo $ordenTrabajo['validos']; ?>
                                        <?php
                                        if ($ordenTrabajo['DprCod'] == 'eje') {
                                            echo '<small class="label label-primary"><i class="fa fa-clock-o"></i> En Ejecucion</small>';
                                        }
                                        if ($ordenTrabajo['DprCod'] == 'rec') {
                                            echo '<small class="label label-info"><i class="fa fa-clock-o"></i> Recibido</small>';
                                        }
                                        if ($ordenTrabajo['DprCod'] == 'no_rec') {
                                            echo '<small class="label label-danger"><i class="fa fa-clock-o"></i> No Recibido</small>';
                                        }
                                        if ($ordenTrabajo['DprCod'] == 'cer') {
                                            echo '<small class="label label-success"><i class="fa fa-clock-o"></i>  Cerrado</small>';
                                        }
                                        if ($ordenTrabajo['DprCod'] == 'liq') {
                                            echo '<small class="label label-warning"><i class="fa fa-clock-o"></i>  Liquidado</small>';
                                        }
                                        ?>
                                        <br>
                                        <?php $estilos = array('eje' => 'primary', 'rec' => 'info', 'no_rec' => 'danger', 'cer' => 'success', 'liq' => 'warning'); ?>
                                        <a href="<?php echo base_url() . 'toma_estado/orden/avance/' . $ordenTrabajo['OrtId'] ?>">
                                            <small class="label label-<?php echo $estilos[$ordenTrabajo['DprCod']]; ?>"><?php echo $ordenTrabajo['avance']; ?>%</small>
                                            <div class="progress xs progress-striped active">
                                                <div class="progress-bar progress-bar-<?php echo $estilos[$ordenTrabajo['DprCod']]; ?>" style="width: <?php echo $ordenTrabajo['avance']; ?>%"></div>
                                            </div>
                                        </a>
                                        <label>Supervisión:</label> <?php echo $ordenTrabajo['validosSupervision']; ?>
                                        <?php
                                        if ($ordenTrabajo['DprCod'] == 'eje') {
                                            echo '<small class="label label-primary"><i class="fa fa-clock-o"></i> En Ejecucion</small>';
                                        }
                                        if ($ordenTrabajo['DprCod'] == 'cer') {
                                            echo '<small class="label label-success"><i class="fa fa-clock-o"></i>  Cerrado</small>';
                                        }
                                        ?>
                                        <?php $estilos = array('eje' => 'primary', 'rec' => 'info', 'no_rec' => 'danger', 'cer' => 'success', 'liq' => 'warning'); ?>
                                        <a href="<?php echo base_url() . 'toma_estado/orden/avance/' . $ordenTrabajo['OrtId'] ?>">
                                            <small class="label label-<?php echo $estilos[$ordenTrabajo['DprCod']]; ?>"><?php echo $ordenTrabajo['avanceSupervision']; ?>%</small>
                                            <div class="progress xs progress-striped active">
                                                <div class="progress-bar progress-bar-<?php echo $estilos[$ordenTrabajo['DprCod']]; ?>" style="width: <?php echo $ordenTrabajo['avance']; ?>%"></div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                         <?php if ($this->acceso_cls->tienePermiso('ver_orden_lectura')) { ?>
                                            <a href="<?php echo base_url() . 'toma_estado/orden/ver/' . $ordenTrabajo['OrtId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Ver">
                                                <i class="fa fa-eye"></i></a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('editar_orden_lectura')) { ?>
                                            <a href="<?php echo base_url() . 'toma_estado/orden/editar/' . $ordenTrabajo['OrtId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                                <i class="fa fa-pencil"></i></a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('eliminar_orden_lectura')) { ?>
                                            <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                                <a href="#modal-eliminar" role="button" data-toggle="modal" ordenTrabajoId="<?php echo $ordenTrabajo['OrtId']; ?>" class="btn btn-default btn-flat">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </span>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('ver_orden_lectura')) { ?>  
                                            <a href="<?php echo base_url() . 'toma_estado/orden/avance/' . $ordenTrabajo['OrtId'] ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Avance">
                                            <span class="glyphicon glyphicon-chevron-right"></span>
                                        <?php } ?>   
                                        <?php if ($this->acceso_cls->tienePermiso('editar_orden_lectura')) { ?>
                                            <a href="<?php echo base_url() . 'toma_estado/orden/' . $ordenTrabajo['OrtId'] . '/atipicos'; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Agregar Lista de Atipicos">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        <?php } ?>
                                        
                                        <?php if ($this->acceso_cls->tienePermiso('penalizar_orden_lectura')) { ?>
                                            <a href="<?php echo base_url() . 'toma_estado/orden/' . $ordenTrabajo['OrtId'] . '/penalizaciones'; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Penalizaciones">
                                            <i class="fa fa-legal"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>    
                        </tbody> 
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Ordenes de Trabajo Planificadas segun Cronograma de Toma de Estado de Suministros</h3>                             
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="ordenesDeTrabajo" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>N° Orden</th>
                                <th>Periodo Facturación</th>
                                <th>Nivel Comercial</th>
                                <th>Fecha de Ejecución</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody class="tbodyP" role="alert" aria-live="polite" aria-relevant="all">
                            <?php 
                            if (count($ordenesTrabajo)==0) {
                                $codOrden = 0;
                            }else {
                                $codOrden = count($ordenesTrabajo);
                            }
                            $i = 1;
                            foreach ($ordenesTrabajoTemp as $ordenTrabajoTemp): ?>
                                <tr>
                                    <!--<td><?php echo 'xx' . reset($ordenTrabajoTemp)['OrtNum']; ?></td>--> 
                                    <td><?php echo date('Y').str_pad( $codOrden + $i , 6, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo reset($ordenTrabajoTemp)['OrtDes']; ?></td> 
                                    <td><?php echo reset($ordenTrabajoTemp)['NgrDes'].": ".implode(' , ', array_keys($ordenTrabajoTemp)); ?></td>
                                    <!--<td><?php echo reset($ordenTrabajoTemp)['NgrDes']." ".reset($ordenTrabajoTemp)['GprCod']; ?></td>--> 
                                    <td><?php echo reset($ordenTrabajoTemp)['OrtFchEj']; ?></td> 
                                    <td class=" ">
                                        <?php if ($this->acceso_cls->tienePermiso('editar_orden_lectura')) { ?>
                                            <a href="<?php echo base_url() . 'toma_estado/orden/nuevo/' . reset($ordenTrabajoTemp)['DlpId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Enviar">
                                                <i class="fa fa-arrow-right"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php 
                            $i++;
                            endforeach; ?>    
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


<!-- MODAL :  Modal : Eliminar Contratante -->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim" method="post">    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Eliminar Orden de Trabajo de Toma de Estado</h4>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: center">¿Realmente deseas eliminar esta Orden de Trabajo y los datos asociados a ella?</h5>
                </div>     
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->


<script type="text/javascript">
    $(document).ready(function ()
    {
        $('[data-toggle="tooltip"]').tooltip();
        $(document).on('click', 'a', function (event)
        {
            var ordenTrabajoId = $(this).attr('ordenTrabajoId');
            $('#idelim').attr('action', "<?php echo base_url() . 'toma_estado/orden/eliminar/'; ?>" + ordenTrabajoId);
        });
    });
</script>