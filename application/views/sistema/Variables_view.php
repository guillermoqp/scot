<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <?php if ($this->acceso_cls->tienePermiso('editar_variable')) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="control-group">
                                <a href="<?php echo base_url() ?>configuracion/variable/nuevo" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar nueva Variable</a>
                            </div>
                        </div>
                    </div> 
                </div>
            <?php } ?>
            
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Variables registradas</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="varibles" class="table table-bordered table-striped">
                        <thead>
                            <tr class="info" role="row">
                                <th>Nombre</th>
                                <th>Código</th>
                                <th>Valor</th>
                                <th style="width: 70px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($variables as $variable): ?>
                                <tr>
                                    <td class=" "><?php echo $variable['VarDes']; ?></td>
                                    <td class=" "><?php echo $variable['VarCod']; ?></td>
                                    <td class=" "><?php echo $variable['VarVal']; ?></td>
                                    <td class=" ">
                                        <?php if ($this->acceso_cls->tienePermiso('editar_variable')) { ?>
                                            <a href="<?php echo base_url() . 'configuracion/variable/editar/' . $variable['VarId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        <?php } ?>
                                        <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                            <a href="#modal-eliminar" role="button" data-toggle="modal" idVariable="<?php echo $variable['VarId']; ?>" class="btn btn-default btn-flat">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </span>
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

<!--  MODAL : Eliminar  Grupo de Penalidad-->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim" method="post">    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i>Eliminar Variable</h4>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: center">¿Realmente deseas eliminar esta variable y los datos asociados?</h5>
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


<script type="text/javascript">
$(document).ready(function ()
{
    $("#varibles").dataTable();
    
    $(document).on('click', 'a', function (event)
    {
        var idVariable = $(this).attr('idVariable');
        $('#idelim').attr('action', "<?php echo base_url() . 'configuracion/variable/eliminar/'; ?>" + idVariable);
    });
});
</script>

