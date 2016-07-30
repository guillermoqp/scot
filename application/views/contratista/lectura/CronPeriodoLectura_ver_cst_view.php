<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <form role="form" method="post">
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
                                    <p style="margin: 0px;">CRONOGRAMA PARA TOMA DE LECTURAS POR: <?php echo $NivelSeleccionado['NgrDes'] ?> | Periodo : <?php echo $mes ?> , <?php echo strtoupper($this->utilitario_cls->nombreMes($mes)) . ' / ' . $anio ?></p><br>
                                </div>    
                            </div>
                            <table id="example1" class="table table-bordered table-striped" aria-describedby="example1_info">
                                <thead>
                                    <tr role="row">
                                        <th style="border: 1px solid #000; padding: 5px;">
                                            <?php echo $NivelSeleccionado['NgrDes'] ?>
                                        </th>
                                        <?php if(!is_null($SubNivel)) {?>
                                        <th style="border: 1px solid #000; padding: 5px;">
                                            <?php echo $SubNivel['NgrDes'] ?>
                                        </th>
                                        <?php } ?>
                                        <th style="border: 1px solid #000; padding: 5px;">
                                            Suministros
                                        </th>
                                        <th style="border: 1px solid #000; padding: 5px;">
                                            Fecha Lectura Anterior 
                                        </th>
                                        <th style="border: 1px solid #000; padding: 5px;">
                                            Fecha Entrega a la Service 
                                        </th>
                                        <th style="border: 1px solid #000; padding: 5px;">Fecha de Lectura</th>
                                        <th style="border: 1px solid #000; padding: 5px;">Fecha de Consistencia de Lecturas</th>
                                    </tr>
                                </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    <?php foreach ($gruposPredio as $grupoPredio) { ?>
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php echo $grupoPredio['GprCod'] ?>
                                            </td>
                                            <?php if(!empty($subniveles)) {?>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php 
                                                $subniveles = array_column($grupoPredio['subniveles'], 'GprCod');
                                                echo implode(',', $subniveles) ?>
                                            </td>
                                            <?php } ?>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php echo $grupoPredio['cantidad']; ?>
                                            </td>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <span id="<?php echo 'fchAnterior_' . $grupoPredio['GprId']; ?>"> <?php echo $lecturaAnual[$grupoPredio['GprId']]['AuxFchAn']; ?> </span>
                                            </td>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php echo $cronograma[$grupoPredio['GprId']]['DlpFchEn']; ?>
                                            </td>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php echo $cronograma[$grupoPredio['GprId']]['DlpFchTm']; ?>
                                            </td>
                                            <td style="border: 1px solid #000; padding: 5px;">
                                                <?php echo $cronograma[$grupoPredio['GprId']]['DlpFchCo']; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div>
                </div><!-- /.box -->
            </form>
        </div>
    </div>
</section>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- page script -->
<script type="text/javascript">
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
</script>

