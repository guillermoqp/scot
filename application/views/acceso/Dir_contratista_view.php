<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">DIRECTORIO <?php echo $contsta['CstRaz']; ?></h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr class="info" role="row">
                                <th>Apellidos y Nombres</th>
                                <th>Teléfonos</th>
                                <th>Correo </th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($niveles as $nivel) { ?>
                                <tr>
                                    <td class="nivel1" colspan="4"><?php echo reset($nivel[key($nivel)])['NvlDes']; ?></td>
                                    <td style="display: none"></td>
                                    <td style="display: none"></td>
                                    <td style="display: none"></td>
                                </tr>
                                <?php foreach ($nivel as $cargo) { ?>
                                    <tr>
                                        <td class="nivel2" colspan="4"><strong><?php echo reset($cargo)['CarDes'] ?></strong></td>
                                        <td style="display: none"></td>
                                        <td style="display: none"></td>
                                        <td style="display: none"></td>
                                    </tr>
                                    <?php foreach ($cargo as $usuario) { ?>
                                        <tr>
                                            <td class="nivel2">
                                                <?php echo $usuario['UsrApePt'] . ' ' . $usuario['UsrApeMt'] . ' ' . $usuario['UsrNomPr'] ?>
                                            </td>
                                            <td>
                                                <i class="fa fa-fw fa-phone"></i>Teléfono:
                                                <a href="tel:+<?php echo $usuario['UsrTel'] ?>" class="Blondie"><b><?php echo $usuario['UsrTel'] ?></b></a>      
                                                <br>
                                                <i class="glyphicon fa-fw glyphicon-phone"></i>Móvil 1:
                                                <a href="tel:+<?php echo $usuario['UsrMov1'] ?>" class="Blondie"><b><?php echo $usuario['UsrMov1'] ?></b></a>      
                                                <br>
                                                <i class="fa fa-fw fa-mobile-phone"></i>Móvil 2:
                                                <a href="tel:+<?php echo $usuario['UsrMov2'] ?>" class="Blondie"><b><?php echo $usuario['UsrMov2'] ?></b></a>      
                                            
                                            </td>
                                            <td>
                                                <i class="fa fa-fw fa-envelope-o"></i>
                                                <a href="mailto:<?php echo $usuario['UsrCor'] ?>?Subject=Mensaje%20Importante" target="_top"><?php echo $usuario['UsrCor'] ?></a>       
                                            </td>
                                            <td>
                                                <img style="height: 80px; width: 75px;" src="<?php echo base_url() . $usuario['UsrFot'] ?>">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
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
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Eliminar Grupo de Penalidad</h4>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: center">¿Realmente deseas eliminar este Grupo de Penalidad y los datos asociados?</h5>
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
</script>
<script type="text/javascript">
    $(document).ready(
            $(function ()
            {
                $("#example1").dataTable({
                    "bSort": false
                });
            }));
</script>

