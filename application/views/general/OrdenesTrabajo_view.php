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
                            <a href="<?php echo base_url(). $padre.'/'.$hijo.'/nuevo';?>" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar Nueva Orden de Trabajo por DBF</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Ordenes de Trabajo Registradas de <?php echo $proceso; ?></h3>                             
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="ordenesDeTrabajo" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Número de Orden</th>
                                <th>Mes de Facturación</th>
                                <th>Fecha de Ejecución</th>
                                <th>Fecha y Hora de Envio Máximo Service</th>
                                <th>Sub Actividades a realizar</th>
                                <th>Estado</th>
                                <th style="width: 180px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="tbodyP" role="alert">
                            <?php foreach ($ordenesTrabajo as $ordenTrabajo): ?>
                                <tr>
                                    <td><?php echo $ordenTrabajo['OrtNum']; ?></td>
                                    <?php
                                    $campoDes = $ordenTrabajo['OrtDes'];
                                    $porciones = explode("<br>", $campoDes);
                                    $entregadoA = $porciones[0];
                                    $descripcion = $porciones[1];
                                    $mes = $porciones[2];
                                    $anio = $porciones[3];
                                    $parametro = $this->Parametro_model->get_one_detParam_x_codigo('cer');
                                    $OrtEstId = $parametro['DprId'];
                                    if ($ordenTrabajo['OrtEstId'] == $OrtEstId) {
                                        $band = 'penalizar';
                                    } else {
                                        $band = 'nopenalizar';
                                    }
                                    ?>
                                    <td><?php echo $mes . ' / ' . $anio; ?></td> 
                                    <td><?php echo date_format(date_create($ordenTrabajo['OrtFchEj']), 'd-m-Y'); ?></td> 
                                    <td><?php echo date_format(date_create($ordenTrabajo['OrtFchEm']), 'd-m-Y H:i:s'); ?></td>
                                    <td>
                                        <?php foreach ($subactividades as $subactividad): ?>
                                            <?php if($ordenTrabajo['OrtId']===$subactividad['OrtId']){
                                                echo $subactividad['SacDes'];}
                                            ?>
                                        <dd></dd>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><?php
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
                                        ?></td>
                                    <td class=" ">
                                        <?php if ($this->acceso_cls->tienePermiso('penalizar_orden')) { ?>
                                            <a href="<?php echo base_url() . $padre. '/'. $hijo . '/'. $ordenTrabajo['OrtId'] . '/penalizaciones'; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Penalizaciones">
                                                <i class="fa fa-legal"></i></a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('ver_orden')) { ?>
                                            <a href="<?php echo base_url() . $padre. '/'. $hijo . '/ver/'. $ordenTrabajo['OrtId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Ver">
                                                <i class="fa fa-eye"></i></a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('ver_orden') && $band == 'penalizar') { ?>
                                            <a href="<?php echo base_url() . $padre. '/'. $hijo. '/valorizar/' . $ordenTrabajo['OrtId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Valorizar Orden">
                                                <span class="glyphicon glyphicon-usd"></span></a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('editar_orden')) { ?>
                                            <a href="<?php echo base_url() . $padre. '/'. $hijo . '/editar/' . $ordenTrabajo['OrtId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                                <i class="fa fa-edit"></i></a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('eliminar_orden')) { ?>
                                            <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                                <a href="#modal-eliminar" role="button" data-toggle="modal" ordenTrabajoId="<?php echo $ordenTrabajo['OrtId']; ?>" class="btn btn-default btn-flat">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </span>
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
            $('#idelim').attr('action', "<?php echo base_url() . $padre. '/' .$hijo. '/eliminar/'; ?>" + ordenTrabajoId);
        });
    });
</script>