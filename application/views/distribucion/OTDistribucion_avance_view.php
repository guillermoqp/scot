<!-- REPORTE DE ORDEN DE TRABAJO -->
<section class="invoice">
    <?php if (isset($_SESSION['mensaje'])) { ?>
        <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $_SESSION['mensaje'][1]; ?>
        </div>
    <?php } ?>
    <form role="form" accept-charset="utf-8" enctype="multipart/form-data" method="post" action="<?php echo str_replace('/index.php', '', current_url()) . "/guardar"; ?>">
        <div id="impresion">
            <div class="row">
                <div class="col-xs-12">
                    <h5><i class="fa fa-file"></i> ORDEN DE TRABAJO N°: <?php echo $OTTomaEstado["OrtNum"] . '  -  ' . $OTTomaEstado["ActDes"]; ?></h5>
                    <small class="pull-right">Fecha de Envio: <?php echo date('d/m/Y', strtotime($OTTomaEstado["OrtFchEn"])) ?></small>
                </div>
            </div>
            <hr style="margin-top:10px;margin-bottom: 10px;">
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    <b>Contratista:</b> <?php echo $contratista['CstRaz']; ?><br>
                    <b>Teléfono:</b> <?php echo $contratista['CstTel1']; ?><br>
                    <b>E-mail:</b> <?php echo $contratista['CstCor1']; ?>
                </div>
                <!-- /.col -->
                <div class="col-sm-6 invoice-col">
                    <b><i class="fa fa-calendar"></i> Fecha Ejecución:</b> <?php echo date('d-m-Y', strtotime($OTTomaEstado["OrtFchEj"])) ?> <br>
                    <b><i class="fa fa-calendar"></i> Fecha Máxima de Recepción:</b> <?php echo date('d-m-Y H:i:s', strtotime($OTTomaEstado["OrtFchEm"])) ?> <br>
                    <b><i class="fa fa-calendar"></i> Mes de Facturación:</b> <?php echo $mesFact; ?> / <?php echo $anioFact; ?><br>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <hr style="margin-top:10px;margin-bottom: 10px;">
            <?php 
            $estilo = "danger";
            if($OTTomaEstado['avance']>=0&&$OTTomaEstado['avance']<10) $estilo = "danger";
            if($OTTomaEstado['avance']>10&&$OTTomaEstado['avance']<50) $estilo = "warning";
            if($OTTomaEstado['avance']>50&&$OTTomaEstado['avance']<70) $estilo = "primary";
            if($OTTomaEstado['avance']>70&&$OTTomaEstado['avance']<=100) $estilo = "success";
            ?>
            <b>Avance total de la Orden de Trabajo por <?php echo $NivelOrden['NgrDes'] ?></b>
            <h1>
                <small class="sr-only" style="margin-left:12px" >
                    <?php echo $OTTomaEstado['avance'] ?>% Completo
                </small>
                <small class="pull-right"><?php echo $OTTomaEstado['avance'] ?>%</small>
            </h1>
            <div class="progress xs progress-striped active" style="margin-left:12px" >
                <div class="progress-bar progress-bar-<?php echo $estilo ?>" style="width: <?php echo $OTTomaEstado['avance'] ?>%" role="progressbar" aria-valuenow="<?php echo $OTTomaEstado['avance'] ?>" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <hr style="margin-top:10px;margin-bottom: 10px;">
            <!-- Table row -->
            <div class="row invoice-info">
                <div class="col-xs-12">
                    <h3><b><?php echo $Nivel['NgrDes']; ?>:</b> <?php echo $GprNivel['GprCod']; ?></h3>
                    <h5>Orden de Trabajo de Distribucion de Recibos y Comunicaciones a ejecutar</h5>
                    <div class="table-responsive">
                        <table id="ciclos" class="table table-bordered table-striped">
                            <thead>
                                <tr Class="info" role="row">
                                    <th><?php echo $SubNivel['NgrDes']; ?></th>
                                    <th>Localidades</th>
                                    <th>N° Distribuidores Asignados</th>
                                    <th>N° Distribuciones totales</th>
                                    <th>N° Distribuciones realizadas</th>
                                    <th>% Avance</th>
                                    <th>N° Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ciclos as $ciclo): ?>
                                    <tr>
                                        <td><?php echo $ciclo['GprCod']; ?></td>
                                        <td><?php echo $ciclo['GprDes']; ?></td>
                                        <td><?php echo $ciclo['cantDistribuidores']; ?></td>
                                        <td><?php echo $ciclo['cantDistribuciones']; ?></td>
                                        <td><?php echo $ciclo['cantDistribucionesEj']; ?></td>
                                        <td>
                                            <a href="#detalleCiclo<?php echo $ciclo["GprCod"]; ?>">
                                            <small class="label label-success"><?php echo $ciclo['avance']; ?>%</small>
                                            <div class="progress xs progress-striped active">
                                                <div class="progress-bar progress-bar-success" style="width: <?php echo $ciclo['avance']; ?>%"></div>
                                            </div>
                                        </td>
                                        <td><?php echo $ciclo['cantObs']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr style="margin-top:10px;margin-bottom: 10px;">
            <!-- Table row -->
            <div class="row no-print invoice-info">
                <div class="col-xs-12">
                    <h5>Sub Ordenes de Trabajo de Distribuciones de Recibos y Comunicaciones ( Asignadas a cada Distribuidor )</h5>
                    <?php foreach ($ciclos as $ciclo) { ?>
                        <h4><?php echo $SubNivel['NgrDes']; ?>: <?php echo $ciclo["GprCod"]; ?></h4>
                        <div class="table-responsive">
                            <table id="detalleCiclo<?php echo $ciclo["GprCod"]; ?>" class="table table-bordered table-striped">
                                <thead>
                                    <tr Class="info" role="row">
                                        <th>Nro. Suborden</th>
                                        <th>Distribuidor</th>
                                        <th>Entregas totales</th>
                                        <th>Fecha de ejecución</th>
                                        <th>Distribuciones ejecutadas</th>
                                        <th>Avance</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ciclo['distribuidores'] as $distribuidor) { ?>
                                        <tr>
                                            <td><?php echo $distribuidor['Numero']; ?></td>
                                            <td><?php echo $distribuidor['nombre']; ?></td>
                                            <td><?php echo $distribuidor['cantDistribuciones']; ?></td>
                                            <td><?php echo $distribuidor['SodFchEj'] ?></td>
                                            <td><?php echo $distribuidor['cantDistribucionesEj'] ?></td>
                                            <td>
                                                <a href="<?php echo base_url() .'distribucion/orden/avance/'.$OTTomaEstado['OrtId'].'/suborden/'.$distribuidor['SodCod']?>">
                                                <small class="label label-success"><?php echo $distribuidor['avance']; ?>%</small>
                                                <div class="progress xs progress-striped active">
                                                    <div class="progress-bar progress-bar-success" style="width: <?php echo $distribuidor['avance']; ?>%"></div>
                                                </div>
                                                </a>
                                            </td>
                                            <td><?php echo $distribuidor['cantObs'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.row -->
        </div>
    </form>   
</section>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function ()
    {
       $("#ciclos").dataTable({
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        bSort: false});
        <?php foreach ($ciclos as $ciclo) { ?>
                $("#detalleCiclo<?php echo $ciclo["GprCod"]; ?>").dataTable({
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                bSort: false});
        <?php } ?>
    });
</script>


