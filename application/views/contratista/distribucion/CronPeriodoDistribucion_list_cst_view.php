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
                    <h3 class="box-title">Cronogramas Por Periodos de Distribución de Recibos y Comunicaciones al Cliente registrados</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                    <table id="cronogramasPeriodo" class="table table-bordered table-striped">
                        <thead>
                            <tr class="info" role="row">
                                <th>Periodos</th>
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
                                        $periodo = $this->Periodo_model->get_one_periodo($cronograma['CdpPrdId']);
                                        echo $periodo["PrdOrd"]; ?>
                                        (<?php echo strtoupper($this->utilitario_cls->nombreMes($periodo["PrdOrd"])) ?>)
                                    </td>
                                    <td>
                                        <?php print_r($cronograma["NgrDes"]) ?>
                                    </td>
                                    <td>
                                        <?php if ($this->acceso_cls->tienePermiso('ver_cron_distribucion_periodo')) { ?>
                                            <a href="<?php echo base_url() . 'cst/distribucion/cronograma_periodo/ver/' . $cronograma['CdpId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Ver/Imprimir/Detalle">
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

<!-- page script  -->
<script type="text/javascript">
$(function ()
{
//    $("#cronogramasPeriodo").dataTable({
//        bSort: false,
//        lengthMenu: [[10, 25, 50, 100, -1],[10, 25, 50, 100, "Todos"]]
//    });
});
</script>

