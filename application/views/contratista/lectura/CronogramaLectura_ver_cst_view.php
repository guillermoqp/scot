<section class="content">
    <div class="row">
        <div class="col-xs-12">
                <?php
                if (isset($accion)) {
                    if ($accion == 'ver') {
                        ?>
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <a id="btn_imprimir" href="#" class="btn btn-info btn-flat">
                                            <i class="fa fa-print"></i> Imprimir
                                        </a>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <?php
                    }
                }
                ?>
                <div id="toPdf" class="box">
                    <div class="box-body table-responsive">
                        <div id="impresion">
                            <div class="box-header">
                                <img style="width: 210px; height: 45px;" src="<?php echo base_url() . $userdata['contratante']['CntLog1'] ?>" alt="<?php echo $userdata['contratante']['EmpRaz'] ?>">
                                <p style="margin: 0px;"><?php echo strtoupper($userdata['contratante']['EmpRaz']) ?></p>
                                <p style="margin: 0px;">SISTEMA DE CONTROL DE ÓRDENES DE TRABAJO</p>
                                <p style="margin: 0px;">PROCESO TOMA DE LECTURAS</p><br>
                            </div><!-- /.box-header -->
                            <div class="row">   
                                <div class="col-md-12" style="text-align: center">
                                    <p style="margin: 12px;"><b>CRONOGRAMA PARA TOMA DE LECTURAS POR: <?php echo $cronograma['NgrDes'] ?>, AÑO: <?php echo $anio ?>, versión: <?php echo $cronograma['ClaVer'] ?></b></p><br>
                                </div> 
                            </div>
                            <?php
                            if (empty($ultimaLectura)) 
                            {
                                echo 'No hay datos anteriores.';
                            }
                            ?>
                                <table id="example1" class="table table-bordered table-striped" aria-describedby="example1_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="border: 1px solid #000; padding: 5px;">
                                                AÑO
                                            </th>
                                            <th colspan="2" style="border: 1px solid #000; padding: 5px;">
                                                <?php echo $anio - 1; ?>
                                            </th>
                                            <th colspan="<?php  echo (count($periodos) * 2) ?>" style="border: 1px solid #000; padding: 5px;">
                                                <?php echo $anio; ?>
                                            </th>
                                            <th rowspan="3" style="border: 1px solid #000; padding: 5px;">Total Días</th>
                                        </tr>
                                        <tr role="row">
                                            <th rowspan="2" style="border: 1px solid #000; padding: 5px;">
                                                <?php echo $cronograma['NgrDes'] ?>
                                            </th>
                                            <th colspan="2" style="border: 1px solid #000; padding: 5px;">
                                                <?php echo isset($ultimoPeriodo) ? $ultimoPeriodo['PrdCod'] : 'Diciembre'; ?>
                                            </th>
                                            <?php for ($i = 0; $i < count($periodos) ; $i++) { ?>
                                                <th colspan="2" style="border: 1px solid #000; padding: 5px;"> 
                                                    <?php echo $periodos[$i]["PrdCod"]; ?>
                                                </th>
                                            <?php } ?>
                                        </tr>
                                        <tr role="row">
                                            <th style="border: 1px solid #000; padding: 5px;">
                                                Toma 
                                            </th>
                                            <th style="border: 1px solid #000; padding: 5px;">
                                                Suministros
                                            </th>
                                        <?php for ($i = 0; $i < count($periodos); $i++) { ?>
                                            <th style="border: 1px solid #000; padding: 5px;"> 
                                                Toma 
                                            </th>
                                            <th style="border: 1px solid #000; padding: 5px;">
                                                Días
                                            </th>
                                        <?php } ?>    
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    <?php foreach ($gruposPredio as $grupoPredio) { ?>
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php echo $grupoPredio['GprCod'] ?>
                                            </td>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php
                                                if (isset($ultimaLectura[$grupoPredio['GprId']])) {
                                                    $fecha = $ultimaLectura[$grupoPredio['GprId']];
                                                } else {
                                                    $fecha = date('d/m/Y',strtotime('01-11-'.($anio-1)));
                                                }
                                                ?>
                                                <?php echo set_value('fch_inicial_' . $grupoPredio['GprId'], $fecha); ?>
                                            </td>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php echo $grupoPredio['cantidad'] ?>
                                            </td>
                                            <?php
                                            $nroMes = ($diasAnio == 365) ? 8 : 7; ?>
                                            <?php
                                            for ($i = 1; $i <= count($periodos) ; $i++) {
                                                $dias = $cronograma['detalle'][$grupoPredio['GprId']][$i];
                                                ?>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php   
                                                $parts = explode('/', $fecha);
                                                $fecha = date('d/m/Y', strtotime($parts[1] . '/' . $parts[0] . '/' . $parts[2] . ' + ' . $dias . ' days'));
                                                echo set_value('fch_' . $grupoPredio['GprId'] . '_' . $i, $fecha);
                                                ?>
                                            </td>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php echo set_value('dias_' . $grupoPredio['GprId'] . '_' . $i, $dias) ?>
                                            </td>
                                                <?php } ?>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php echo set_value('anio_' . $grupoPredio['GprId'], $diasAnio) ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- page script -->
<script type="text/javascript">
    $(document).ready(function () {
        $("#btn_imprimir").click(function () {
            printdiv('impresion');
        })
        function printdiv(impresion)
        {
            var contenido = document.getElementById(impresion).innerHTML;
            var popupWin = window.open('', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=80,width=1100,height=800');
            var usuario = "<?php echo $userdata['UsrNomPr'] . ' ' . $userdata['UsrApePt'] . ' ' . $userdata['UsrApeMt']; ?>"; 
            var cargo = "<?php echo $userdata['CarDes']; ?>";
            var d = new Date(); 
            var fecha_hora = "<?php echo date('d-m-Y H:i:s') ?>";
            var footer = "<footer class='main-footer'>\n\
                            <div><b>Fecha y Hora de Impresión:</b> <small>"+fecha_hora+"</small></div>\n\
                            <div><b>Generado por:</b> <small>"+usuario+" - "+cargo+"</small></div>\n\
                            <div class='pull-left'>SCOT - Sistema de Control de Ordenes de Trabajo</div><br>\n\
                            <div class='pull-left'><strong>&copy; 2016 - Gerencia Comercial - Sedalib S.A.</strong></div>\n\
                          </footer>";popupWin.document.open();
            popupWin.document.write("<link rel='stylesheet' href='<?php echo base_url();?>frontend/bootstrap/css/bootstrap.min.css' />");
            popupWin.document.write("<link rel='stylesheet' href='<?php echo base_url();?>frontend/dist/css/impresion.css' />");
            popupWin.document.write('<html><body onload="window.print()">'+ contenido + footer + '</html>');
            popupWin.document.close(); 
            return false;
        }
    });
</script>

