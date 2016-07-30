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
                            <a href="<?php echo base_url().'contrato/'.$contrato['ConId'].'/precios_penalidades/nuevo'; ?>" class="btn btn-info btn-flat"> <i class="fa fa-pencil"></i> Registrar Nuevo Precio Penalidad </a>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Detalles del Contrato</h3>  
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="control-group">
                                    <label>Número de Contrato:</label>
                                        <input type="text" class="form-control" value="<?php echo (isset($contrato)) ? $contrato['ConNum'] : 'Sin Datos'; ?>" disabled/>
                                </div>
                            </div>    
                        </div>
                        <div class="col-md-6">
                            <!-- text input -->
                            <div class="form-group">
                                <div class="control-group">
                                <label>Descripción:</label>
                                <input type="text" class="form-control" value="<?php echo (isset($contrato)) ? $contrato['ConDes'] : 'Sin Datos'; ?>" disabled/>
                                </div>
                            </div>
                        </div>       
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="control-group">
                                    <label>Fecha de Inicio:</label>
                                        <input type="text" class="form-control" value="<?php echo (isset($contrato)) ? date('d/m/Y',  strtotime($contrato['ConFchIn'])) : 'Sin Datos'; ?>" disabled/>
                                </div>
                            </div>    
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <div class="control-group">
                                <label>Fecha de Fin:</label>
                                <input type="text" class="form-control" value="<?php echo (isset($contrato)) ? date('d/m/Y',  strtotime($contrato['ConFchFn'])) : 'Sin Datos'; ?>" disabled/>
                                </div>
                            </div>
                        </div>       
                    </div> 
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Precios de Penalidades Registradas al Contrato</h3><hr>
                    <p>*K = 5% UIT <br> En las Bases se establecen penalidades distintas a casos de retraso injustificado en la ejecución de las prestaciones objeto del contrato (penalidad por mora), siendo estas objetivas, razonables y congruentes con el objeto de la convocatoria.
                    La unidad de medida aprobada para tal fin es la Unidad Impositiva Tributaria.</p>
                </div>
                <div class="box-body table-responsive">
                    <table id="precios_penalidades" class="table table-bordered" aria-describedby="example1_info">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Multa</th>
                                <th style="width: 70px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="tbodyP" role="alert">
                            <?php foreach ($gruposPenalidad as $grupoPenalidad) { ?>
                                <?php 
                                $band = true;
                                foreach ($detallesContratoPenalidad as $detalleContratoPenalidad): ?>  
                                    <?php if ($grupoPenalidad['GpeId'] == $detalleContratoPenalidad['GpeId'] && $band == true) { ?>
                                        <tr class="nivel1"><b>
                                        <td><?php echo $grupoPenalidad['GpeCod']; ?></td>
                                        <td><?php echo $grupoPenalidad['GpeDes']; ?></td>
                                        <td></td><td></td></b>
                                    </tr>
                                <?php $band = false; } ?>
                            <?php endforeach; ?>
                                <?php foreach ($detallesContratoPenalidad as $detalleContratoPenalidad): ?> 
                                    <?php if ($detalleContratoPenalidad['PenGpeId'] == $grupoPenalidad['GpeId']) { ?>   
                                        <tr>
                                            <td><span class="nivel2"><?php echo $detalleContratoPenalidad['PenCod'] ?></span></td>
                                            <td class="nivel2desc"><?php echo $detalleContratoPenalidad['PenDes']; ?></td>
                                            <td><?php echo $detalleContratoPenalidad['DcpVal']; ?> x K </td>
                                            <td>
                                                <a href="<?php echo base_url() . 'contrato/' . $detalleContratoPenalidad['ConId'] . '/precios_penalidades/editar/' . $detalleContratoPenalidad['DcpId'] . ''; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                                <a href="#modal-eliminar" role="button" data-toggle="modal" detalleContratoPenalidad="<?php echo $detalleContratoPenalidad['DcpId']; ?>" class="btn btn-default btn-flat">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php endforeach; ?> 
                            <?php } ?> 
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>
</section>
<!-- page script -->
<!-- MODAL : Eliminar Contrato  -->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim" method="post">    
        <div class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><i class="fa fa-edit"></i> Eliminar Precio por Penalidad del Contrato</h4>
        </div>
        <div class="modal-body">
            <h5 style="text-align: center">¿Realmente deseas eliminar este Precio por Penalidad del Contrato y los datos asociados a el?</h5>
        </div>     
        <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>
        </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
 
<!-- page script -->
<script type="text/javascript">
    $(function () 
    {
        $("#precios_penalidades").DataTable({"bSort": false});
    });
</script>
<script type="text/javascript">
$(document).ready(function()
{
    $('[data-toggle="tooltip"]').tooltip();
    $(document).on('click', 'a', function(event) 
    {
        var detalleContratoPenalidad = $(this).attr('detalleContratoPenalidad');
        $('#idelim').attr('action',"<?php echo base_url() . 'contrato/'.$contrato['ConId'].'/precios_penalidades/eliminar/'; ?>"+detalleContratoPenalidad);
    });
});
</script>