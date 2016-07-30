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
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Cronogramas de lecturas por periodos registrados</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                    <table id="CronogramasLecturaPeriodo" class="table table-bordered table-striped">
                        <thead>
                            <tr class="info" role="row">
                                <th>Periodo</th>
                                <th>Nivel Comercial</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($cronogramas as $anio => $anios) { ?>
                                <tr>
                                    <th colspan="3">
                                        <?php echo $anio ?>
                                    </th>
                                </tr>
                                <?php foreach ($anios as $cronograma) { ?>
                                <tr>
                                   <td>
                                        <?php
                                        $periodo = $this->Periodo_model->get_one_periodo($cronograma['ClpPrdId']);
                                        echo $periodo["PrdOrd"]; ?>
                                        (<?php echo strtoupper($this->utilitario_cls->nombreMes($periodo["PrdOrd"])) ?>)
                                    </td>
                                    <td>
                                        <?php print_r($cronograma["NgrDes"]) ?>
                                    </td>
                                    <td>
                                        <?php if ($this->acceso_cls->tienePermiso('ver_cron_lectura_anual')) { ?>
                                            <a href="<?php echo base_url() . 'cst/toma_estado/cronograma_periodo/ver/' . $cronograma['ClpId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Detalle">
                                                <i class="fa fa-info"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php  } ?>
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
//    $("#CronogramasLecturaPeriodo").dataTable({
//        bSort: false,
//        lengthMenu: [[10, 25, 50, 100, -1],[10, 25, 50, 100, "Todos"]]
//    });
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
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Eliminar',
                closeOnConfirm: false
            },
            function ()
            {
                var idCronograma = $(e.currentTarget).attr('idCronograma');
                window.location.href = "<?php echo base_url() . 'toma_estado/cronograma_anual/eliminar/'; ?>" + idCronograma;
                swal("Eliminado!", "El Cronograma Anual de Toma de Estado ha sido eliminado!", "success");
            });
        });
    });
</script>

