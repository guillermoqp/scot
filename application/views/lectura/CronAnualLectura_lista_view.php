<!-- sweetAlert. -->
<link rel="stylesheet" href="<?php echo base_url()?>frontend/plugins/sweetalert/sweetalert2.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url()?>frontend/plugins/sweetalert/sweetalert2.min.js"></script>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <?php if ($this->acceso_cls->tienePermiso('editar_cron_lectura_anual')) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="control-group">
                                <a href="<?php echo base_url() ?>toma_estado/cronograma_anual/nuevo" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar Nuevo Cronograma</a>
                            </div>
                        </div>
                    </div> 
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Cronogramas para Toma de Lecturas por Ciclos registrados</h3>                                   
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="CronogramasLecuraAnual" class="table table-bordered table-striped" aria-describedby="example1_info">
                            <thead>
                                <tr Class="info" role="row">
                                    <th>Año</th>
                                    <th>Versión</th>
                                    <th>Aprobado</th>
                                    <th>Vigente</th>
                                    <th>Nivel Comercial</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php foreach ($cronogramas as $cronograma) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $cronograma['ClaAni'] ?>
                                        </td>
                                        <td>
                                            <?php echo $cronograma['ClaVer'] ?>
                                        </td>
                                        <td>
                                            <?php echo ($cronograma['ClaApr'] == 't') ? 'Sí' : 'No' ?>
                                        </td>
                                        <td>
                                            <?php echo ($cronograma['ClaVig'] == 't') ? 'Sí' : 'No' ?>
                                        </td>
                                        <td>
                                            <?php echo $cronograma['NgrDes'] ?>
                                        </td>
                                        <td class=" ">
                                            <?php if ($this->acceso_cls->tienePermiso('ver_cron_lectura_anual')) { ?>
                                                <a href="<?php echo base_url() . 'toma_estado/cronograma_anual/ver/' . $cronograma['ClaId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Detalle">
                                                    <i class="fa fa-info"></i>
                                                </a>
                                            <?php } ?>
                                            <?php if ($this->acceso_cls->tienePermiso('editar_cron_lectura_anual')) { ?>
                                                <a href="<?php echo base_url() . 'toma_estado/cronograma_anual/editar/' . $cronograma['ClaId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <?php } ?>
                                            
                                            <?php if ($this->acceso_cls->tienePermiso('aprobar_cron_lectura_anual') && $cronograma['ClaApr'] == 'f') { ?>
                                                <a href="<?php echo base_url() . 'toma_estado/cronograma_anual/aprobar/' . $cronograma['ClaId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Aprobar">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            <?php } ?>

                                            <?php if ($this->acceso_cls->tienePermiso('eliminar_cron_lectura_anual')) { ?>
                                                <a id="eliminar" idCronograma="<?php echo $cronograma['ClaId']; ?>" class="eliminar btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            <?php } ?>
                                            
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
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
    $("#CronogramasLecuraAnual").dataTable({
        bSort: false,
        lengthMenu: [[10, 25, 50, 100, -1],[10, 25, 50, 100, "Todos"]]
    });
});
</script>

<script type="text/javascript">
$(document).ready(function ()
{
        $('[data-toggle="tooltip"]').tooltip();
        $(".eliminar").click(function (e)
        {
            swal({
                 title: "¿Estas Seguro de Eliminar?",
                 text: "Vas a eliminar el cronograma Anual de Toma de Estado",
                 type: "warning",
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: "Eliminar",
                 cancelButtonText: "Cancelar",
                 closeOnConfirm: false
               },
               function(isConfirm) {
                if (isConfirm) {   
                    var idCronograma = $(e.currentTarget).attr('idCronograma');
                    window.location.href = "<?php echo base_url() . 'toma_estado/cronograma_anual/eliminar/'; ?>" + idCronograma;
                    swal("Eliminado!", "El Cronograma Anual de Toma de Estado ha sido eliminado!", "success");
                }
                });
        });
});
</script>

