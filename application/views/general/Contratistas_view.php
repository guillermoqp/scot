<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <?php if ($this->acceso_cls->tienePermiso('editar_contratista')) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="control-group">
                                <a href="<?php echo base_url() ?>contratista/nuevo" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar Nueva Contratista</a>
                            </div>
                        </div>
                    </div> 
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Contratistas Registradas</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="contratistas" class="table table-bordered table-striped" aria-describedby="example1_info">
                        <thead>
                            <tr Class="info">
                                <th>RUC</th>
                                <th>Razón Social</th>
                                <th>Representante Legal</th>
                                <th>Tipo</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Logo</th>
                                <th style="width: 70px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contratistas as $contratista): ?>
                                <tr>
                                    <td><?php echo $contratista['CstRuc']; ?></td>
                                    <td><?php echo $contratista['CstRaz']; ?></td>
                                    <td><?php echo $contratista['CstNomRp']; ?></td>
                                    <td><?php echo ($contratista['CstCio'] == 't') ? 'Consorcio' : 'Individual'; ?></td>
                                    <td><a href="tel:+<?php echo $contratista['CstTel1']; ?>" class="Blondie"><b><?php echo $contratista['CstTel1']; ?></b></a></td>
                                    <td><a href="mailto:<?php echo $contratista['CstCor1']; ?>?Subject=Mensaje%20Importante" target="_top"><?php echo $contratista['CstCor1']; ?></a></td>
                                    <td> 
                                        <img src="<?php echo base_url().$contratista['CstLog1']; ?>" alt="Logo Contratista" width="150" height="50"/>
                                    </td>
                                    <td class=" ">
                                        <?php if ($this->acceso_cls->tienePermiso('editar_contratista')) { ?>
                                            <a href="<?php echo base_url() . 'contratista/editar/' . $contratista['CstId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if ($this->acceso_cls->tienePermiso('eliminar_contratista')) { ?>
                                        <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                            <a href="#modal-eliminar" role="button" data-toggle="modal" contratista="<?php echo $contratista['CstId']; ?>" class="btn btn-default btn-flat">
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
        $("#contratistas").dataTable();
    });
</script>

<!-- MODAL : Eliminar Contratista  -->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim" method="post">    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Eliminar Contratista</h4>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: center">¿Realmente deseas eliminar esta Contratista y los datos asociados a ella?</h5>
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
            var contratista = $(this).attr('contratista');
            $('#idelim').attr('action', "<?php echo base_url() . 'contratista/eliminar/'; ?>" + contratista);
        });
    });
</script>
