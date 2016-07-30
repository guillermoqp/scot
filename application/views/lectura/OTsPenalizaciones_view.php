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
                            <a href="<?php echo base_url().'toma_estado/orden/'.$ordenTrabajoEdit['OrtId'].'/penalizacion/nuevo'; ?>" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar Nueva Penalización a la Orden de Trabajo</a>
                        </div>
                    </div>
                </div> 
            </div>
            
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Penalizaciones de la Orden de Trabajo N°: <small style="font-size: 16px;"> <?php echo $ordenTrabajoEdit['OrtNum']; ?></small></h3>                             
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="penalizacionesOT" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Cod. Penaliz.</th>
                                <th style="width: 390px;">Descripción</th>
                                <th>N° Casos</th>
                                <th>N° Dias</th> 
                                <th>Monto</th>
                                <th style="width: 100px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="penalizacionesOT" class="tbodyP" role="alert">
                                <?php foreach ($penalizaciones as $penalizacion): ?>
                                <tr>
                                    <td><?php echo $penalizacion['PnzCod']; ?></td>
                                    <td><?php echo $penalizacion['PnzDes']; ?></td>
                                    <td><?php echo $penalizacion['PnzCas']; ?></td>
                                    <td><?php echo $penalizacion['PnzDia']; ?></td>
                                    <td>S/. <?php echo $penalizacion['PnzMon']; ?></td>
                                    <td class=" ">
                                        <?php if ($this->acceso_cls->tienePermiso('editar_orden')) { ?>
                                            <a href="<?php echo base_url().'toma_estado/orden/'.$ordenTrabajoEdit['OrtId'].'/penalizacion/editar/'.$penalizacion['PnzId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('eliminar_orden')) { ?>
                                        <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                            <a href="#modal-eliminar" role="button" data-toggle="modal" penalizacionId="<?php echo $penalizacion['PnzId']; ?>" class="btn btn-default btn-flat">
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
        $("#penalizacionesOT").dataTable();
    });
</script>


<!-- MODAL :  Modal : Eliminar Contratante -->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim" method="post">    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><i class="fa fa-edit"></i> Eliminar Penalización de la Orden de Trabajo</h4>
        </div>
        <div class="modal-body">
            <h5 style="text-align: center">¿Realmente deseas eliminar la Penalización de la Orden de Trabajo y los datos asociados con él?</h5>
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
$(document).ready(function()
{
   $(document).on('click', 'a', function(event) 
   {
        var penalizacionId = $(this).attr('penalizacionId');
        $('#idelim').attr('action',"<?php echo base_url().'toma_estado/orden/'.$ordenTrabajoEdit['OrtId'].'/penalizacion/eliminar/'; ?>"+penalizacionId);
    });
});
</script>