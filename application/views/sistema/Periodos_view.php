<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <?php if ($this->acceso_cls->tienePermiso('editar_periodo')) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="control-group">
                                <a href="<?php echo base_url() ?>configuracion/periodo/nuevo" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar nuevo Período</a>
                            </div>
                        </div>
                    </div> 
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Periodos Registrados</h3>                                   
                </div>
                <div class="box-body table-responsive">
                    <table id="periodos" class="table table-bordered table-striped" aria-describedby="example1_info">
                        <thead>
                            <tr class="info" role="row">
                                <th>
                                    Codigo
                                </th>
                                <th>
                                    Año
                                </th>
                                <th>
                                    Fecha Inicio
                                </th>
                                <th>
                                    Fecha Fin
                                </th>
                                <th style="width: 100px;">
                                    Acción
                                </th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($periodos as $periodo): ?>
                                <tr>
                                    <td><?php echo $periodo['PrdCod']; ?></td>
                                    <td><?php echo $periodo['PrdAni']; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($periodo['PrdFchIn'])); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($periodo['PrdFchFn'])); ?></td>
                                    <td>
                                        <a href="<?php echo base_url().'configuracion/periodo/editar/'.$periodo['PrdId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                            <a href="#modal-eliminar" role="button" data-toggle="modal" periodo="<?php echo $periodo['PrdId']; ?>" class="btn btn-default btn-flat">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
    $("#periodos").dataTable({
        "bSort": false,
        "paging": true
    });
});
</script>
<!-- MODAL :   Eliminar Periodo -->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim" method="post">    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-trash-o"></i> Eliminar Periodo</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="periodo" name="id" value="" />
                    <h5 style="text-align: center">¿Realmente deseas eliminar este periodo y los datos asociados a el?</h5>
                </div>     
                <div class="modal-footer">
                    <button class="btn btn-default btn-flat" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                    <button class="btn btn-danger btn-flat">Eliminar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->
<script type="text/javascript">
$(document).ready(function()
{
    $(document).on('click', 'a', function(event) 
    {
        var idPeriodo = $(this).attr('periodo');
        $('#idelim').attr('action',"<?php echo base_url(); ?>configuracion/periodo/eliminar/"+idPeriodo);
    });
});
</script>
