<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (null != $this->session->flashdata('mensaje')) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Niveles Comerciales Registrados</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="nivelesComerciales" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Nombre</th>
                                <th style="width: 210px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($nivelesGrupo as $nivelGrupo) { ?>
                                <tr>
                                    <td><?php echo $nivelGrupo['NgrDes']; ?></td>
                                    <td>
                                        <?php if ($this->acceso_cls->tienePermiso('editar_nivel_comercial')) { ?>
                                            <a href="<?php echo base_url() . 'configuracion/nivel_comercial/'.$nivelGrupo['NgrId'].'/detalle'; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar / Detalle">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <form role="form" accept-charset="utf-8" method="post" action="<?php echo str_replace('/index.php', '', current_url()); ?>">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Agregar Nuevo Nivel Comercial</h3>                                   
                </div>
                <div class="box-body">
                    <div class="col-md-6">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Nombre:</label>
                            <div class="input-group">
                                <input id="NgrDes" name="NgrDes" type="text" class="form-control" placeholder="Ciclo">
                                <div class="input-group-btn">
                                    <button id="btn_nuevo_nivel" name="btn_nuevo_nivel" type="submit" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar</button>
                                    <button style="margin-left: 5px" type="reset" class="btn btn-default btn-flat">Limpiar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form> 
        </div>
    </div>
</section>

<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- page script -->


<script type="text/javascript">
$(document).ready(function ()
{
    $("#nivelesComerciales").dataTable({
            "bSort": false
    });
    $('#btn_nuevo_nivel').prop('disabled', 'disabled');
    $('#btn_nuevo_nivel').attr('class', "btn btn-info btn-flat disabled");
    $('#NgrDes').on('keyup blur', function () { 
        if ($("#NgrDes").val()!='') {                 
            $('#btn_nuevo_nivel').prop('disabled', false);  
            $('#btn_nuevo_nivel').attr('class', "btn btn-info btn-flat"); 
        } else {
            $('#btn_nuevo_nivel').prop('disabled', 'disabled');
            $('#btn_nuevo_nivel').attr('class', "btn btn-info btn-flat disabled");
        }
    });
});
</script>