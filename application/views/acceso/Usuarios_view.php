<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if(isset($_SESSION['mensaje'])){ ?>
            <div class="alert alert-<?php echo ($_SESSION['mensaje'][0]=='error')? 'danger' : 'success'; ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $_SESSION['mensaje'][1] ;?>
            </div>
            <?php } ?>
            <?php if ($this->acceso_cls->tienePermiso('editar_usuario')) { ?>
             <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="control-group">
                            <a href="<?php echo base_url() ?>permisos/usuario/nuevo" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar Nuevo Usuario</a>
                        </div>
                    </div>
                </div> 
            </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Usuarios Registrados</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr class="info">
                                <th>Usuario</th>
                                <th>Nombres</th>
                                <th>Cargo</th>
                                <th>Perfil</th>
                                <th style="width: 70px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($usuarios as $usuarioitem): ?>
                                <tr>
                                    <td class=" "><?php echo $usuarioitem['UsrNomCt']; ?></td>
                                    <td class=" "><?php echo $usuarioitem['UsrNomPr'] . ' ' . $usuarioitem['UsrApePt'] . ' ' . $usuarioitem['UsrApeMt']; ?></td>
                                    <td class=" "><?php echo $usuarioitem['CarDes']; ?></td>
                                    <td class=" "><?php echo implode('<br>', $usuarioitem['roles']); ?></td>
                                    <td class=" ">
                                        <?php if ($this->acceso_cls->tienePermiso('editar_usuario')) { ?>
                                        <a href="<?php echo base_url() . 'permisos/usuario/editar/' . $usuarioitem['UsrId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('eliminar_usuario')) { ?>
                                        <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                        <a href="#modal-eliminar" role="button" data-toggle="modal" usuario="<?php echo $usuarioitem['UsrId']; ?>" class="btn btn-default btn-flat">
                                            <i class="fa fa-trash-o"></i>
                                        </a> 
                                        </span>
                                        <?php } ?>
                                    </td>
                                </tr> 
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.box-body -->
       </div>
    </div>
</section>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- MODAL :  Modal : Eliminar Usuario  -->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim" method="post">    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><i class="fa fa-edit"></i> Eliminar Usuario</h4>
        </div>
        <div class="modal-body">
            <h5 style="text-align: center">¿Realmente deseas eliminar este Usuario y los datos asociados con él?</h5>
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
   $("#example1").dataTable();
   $('[data-toggle="tooltip"]').tooltip();
   $(document).on('click', 'a', function(event) 
   {
        var usuario = $(this).attr('usuario');
        $('#idelim').attr('action',"<?php echo base_url() . 'usuario/eliminar/'; ?>"+usuario);
    });
});
</script>