<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <?php if ($this->acceso_cls->tienePermiso('editar_contratante')) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="control-group">
                                <a href="<?php echo base_url() ?>configuracion/contratante/nuevo" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar Nuevo Contratante</a>
                            </div>
                        </div>
                    </div> 
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Contratantes Registradas</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>RUC</th>
                                <th>Razón Social</th>
                                <th>Representante Legal</th>
                                <th>Teléfono</th>
                                <th>Logo</th>
                                <th style="width: 70px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="tbodyP" role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($contratantes as $contratante): ?>
                                <tr>
                                    <td class=" ">
                                        <?php echo $contratante['EmpRuc']; ?>
                                    </td>
                                    <td class=" ">
                                        <?php echo $contratante['EmpRaz']; ?>
                                    </td>
                                    <td class=" ">
                                        <?php echo $contratante['EmpNomRp']; ?>
                                    </td>
                                    <td class=" ">
                                        <a href="tel:+<?php echo $contratante['EmpTel']; ?>" class="Blondie">
                                            <b><?php echo $contratante['EmpTel']; ?></b>
                                        </a>
                                    </td>
                                    <td>
                                        <img src="<?php echo base_url().$contratante['CntLog1']; ?>" alt="Logo Contratante" width="150" height="50"/>  
                                    </td>
                                    <td class=" ">
                                        <?php if ($this->acceso_cls->tienePermiso('editar_contratante')) { ?>
                                        <a href="<?php echo base_url() . 'configuracion/contratante/editar/' . $contratante['CntId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('eliminar_contratante')) { ?>
                                        <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                            <a href="#modal-eliminar" role="button" data-toggle="modal" contratante="<?php echo $contratante['CntId']; ?>" class="btn btn-default btn-flat">
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
        $("#example1").dataTable();
    });
</script>

<!-- MODAL :  Modal : Eliminar Contratante -->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim" method="post">    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Eliminar Contratante</h4>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: center">¿Realmente deseas eliminar esta Contratante y los datos asociados con él?</h5>
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
            var contratante = $(this).attr('contratante');
            $('#idelim').attr('action', "<?php echo base_url() . 'configuracion/contratante/eliminar/'; ?>" + contratante);
        });
    });
</script>