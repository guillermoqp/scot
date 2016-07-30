<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (null != $this->session->flashdata('mensaje')) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <?php if ($this->acceso_cls->tienePermiso('editar_nivel')) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="control-group">
                                <a href="<?php echo base_url() ?>permisos/nivel/nuevo" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar Nuevo Nivel Organizacional</a>
                            </div>
                        </div>
                    </div> 
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Niveles Organizacionales Registrados del Contratante</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped" aria-describedby="example1_info">
                        <thead>
                            <tr class="info" role="row">
                                <th>
                                    Nombre
                                </th>
                                <th style="width: 210px;">
                                    Acción
                                </th>
                            </tr>
                        </thead>

                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($niveles1 as $nivel1): ?>
                                <tr>
                                    <td class=" "><?php echo $nivel1['NvlDes']; ?></td>
                                    <td class=" ">
                                        <?php if ($this->acceso_cls->tienePermiso('editar_nivel')) { ?>
                                            <a href="<?php echo base_url() . 'permisos/nivel/editar/subir/' . $nivel1['NvlId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Subir">
                                                <i class="fa fa-arrow-up"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('editar_nivel')) { ?>
                                            <a href="<?php echo base_url() . 'permisos/nivel/editar/bajar/' . $nivel1['NvlId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Bajar">
                                                <i class="fa fa-arrow-down"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('editar_nivel')) { ?>
                                            <a href="<?php echo base_url() . 'permisos/nivel/editar/' . $nivel1['NvlId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('eliminar_nivel')) { ?>
                                            <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                                <a href="#modal-eliminar1" role="button" data-toggle="modal" nivel1="<?php echo $nivel1['NvlId']; ?>" class="btn btn-default btn-flat">
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
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Niveles Organizacionales Registrados del Contratista</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example2" class="table table-bordered table-striped" aria-describedby="example1_info">
                        <thead>
                            <tr class="info" role="row">
                                <th>
                                    Nombre
                                </th>
                                <th style="width: 210px;">
                                    Acción
                                </th>
                            </tr>
                        </thead>

                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($niveles2 as $nivel2): ?>
                                <tr>
                                    <td class=" "><?php echo $nivel2['NvlDes']; ?></td>
                                    <td class=" ">
                                        <?php if ($this->acceso_cls->tienePermiso('editar_nivel')) { ?>
                                            <a href="<?php echo base_url() . 'permisos/nivel/editar/subir/' . $nivel1['NvlId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Subir">
                                                <i class="fa fa-arrow-up"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('editar_nivel')) { ?>
                                            <a href="<?php echo base_url() . 'permisos/nivel/editar/bajar/' . $nivel1['NvlId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Bajar">
                                                <i class="fa fa-arrow-down"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('editar_nivel')) { ?>
                                            <a href="<?php echo base_url() . 'permisos/nivel/editar/' . $nivel2['NvlId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('eliminar_nivel')) { ?>
                                            <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                                <a href="#modal-eliminar2" role="button" data-toggle="modal" nivel2="<?php echo $nivel2['NvlId']; ?>" class="btn btn-default btn-flat">
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

<!-- MODAL :  Modal : Niveles Organizacionales Registrados del Contratante -->
<div class="modal fade" id="modal-eliminar1" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim1" method="post">    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Eliminar Niveles Organizacionales Registrados del Contratante</h4>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: center">¿Realmente deseas eliminar este Nivel Organizacional Registrado del Contratante y los datos asociados?</h5>
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
    $(document).ready(function ()
    {
        $(document).on('click', 'a', function (event)
        {
            var nivel1 = $(this).attr('nivel1');
            $('#idelim1').attr('action', "<?php echo base_url() . 'permisos/nivel/eliminar/'; ?>" + nivel1);
        });
    });
</script>


<!-- MODAL :  Eliminar Niveles Organizacionales Registrados del Contratista -->
<div class="modal fade" id="modal-eliminar2" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim2" method="post">    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Eliminar Niveles Organizacionales Registrados del Contratista</h4>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: center">¿Realmente deseas eliminar este Nivel Organizacional del Contratista y los datos asociados?</h5>
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
        $(document).on('click', 'a', function (event)
        {
            var nivel2 = $(this).attr('nivel2');
            $('#idelim2').attr('action', "<?php echo base_url() . 'permisos/nivel/eliminar/'; ?>" + nivel2);
        });
        $("#example1").dataTable({
            "bSort": false
        });
        $("#example2").dataTable({
            "bSort": false
        });
    });
</script>