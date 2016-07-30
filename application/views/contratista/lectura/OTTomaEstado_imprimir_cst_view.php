<section class="content">
    <div class="row">
        <div class="col-xs-12">
                <div class="row">
                    <div class="col-md-4"><img style="width: 150px; height: 40px;" src="<?php echo base_url() . $userdata['contratante']['CntLog1'] ?>" alt="<?php echo $userdata['contratante']['EmpRaz'] ?>" style="position:absolute; margin-top:0; margin-bottom: 5px; left:0;" /></div>
                    <div class="col-md-4"><h4><b>TOMA DE ESTADO - LECTURAS DE CAMPO</b></h4></div>
                    <div class="col-md-4"><b>Lecturista:</b> <?php echo $lecturista['UsrApePt'] . '  ' . $lecturista['UsrApeMt'] . '  ' . $lecturista['UsrNomPr']; ?></div>
                </div>
                <div class="row">
                    <div class="col-md-4">SCOT-Sistema de Control de Ordenes de Trabajo</div>
                    <div class="col-md-4"><h5 class="modal-title"><b>Localidades:</b> <?php echo $localidades; ?></h5></div>
                    <div class="col-md-4"><b><i class="fa fa-calendar"></i> Fecha Ejecución:</b> <?php echo date('d/m/Y', strtotime($OTTomaEstado["OrtFchEj"])) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-4">SubSistema de Toma de Estado de Suministros</div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4"><b>Nro de Suministros: </b><?php echo $nroSuministros; ?></div>
                    <div class="col-md-4"><b>Página: </b><?php echo $paginaActual . ' de ' . $nroPaginas; ?></div>
                </div> 
                <?php
                if (isset($accion)) {
                    if ($accion == 'imprimir') {
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
                <div class="row">
                  <div class="col-md-12">
                    <?php echo $this->pagination->create_links(); ?>
                    <div class="table-responsive">
                        <div id="impresion">                                                                                  
                            <table id="detalleCicloSubOrden" class="table table-bordered table-striped">
                            <thead>
                                <tr role="row">
                                        <th rowspan="2" style="border: 1px solid #000;padding: 5px;">ITEM</th>
                                        <th rowspan="2" style="border: 1px solid #000;padding: 5px;"><center>CÓDIGO</center></th>
                                        <th rowspan="2" style="border: 1px solid #000;padding: 5px;">NOMBRE</th>
                                        <th colspan="3" style="border: 1px solid #000;padding: 5px;"><center>DIRECCIÓN</center></th>
                                        <th rowspan="2" style="border: 1px solid #000;padding: 5px;">MEDIDOR</th>
                                        <th rowspan="2" style="border: 1px solid #000;padding: 5px;">LECTURA</th>
                                        <th rowspan="2" style="border: 1px solid #000;padding: 5px;">OBS.</th>
                                </tr>
                                <tr role="row">
                                        <th style="border: 1px solid #000;padding: 5px;">URBANIZACIÓN</th>
                                        <th style="border: 1px solid #000;padding: 5px;">CALLE/JR./AV.</th>
                                        <th style="border: 1px solid #000;padding: 5px;">NÚMERO</th>
                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php foreach ($results as $lectura) { ?>
                                    <tr>
                                        <td style="border: 1px solid #000;padding: 5px;"><?php echo $lectura['LecId']; ?></td>
                                        <td style="border: 1px solid #000;padding: 5px;"><?php echo $lectura['LecCodFc']; ?></td>
                                        <td style="border: 1px solid #000;padding: 5px;"><?php echo $lectura['LecNom']; ?></td>
                                        <td style="border: 1px solid #000;padding: 5px;"><?php echo $lectura['LecUrb']; ?></td>
                                        <td style="border: 1px solid #000;padding: 5px;"><?php echo $lectura['LecCal']; ?></td>
                                        <td style="border: 1px solid #000;padding: 5px;"><?php echo $lectura['LecMun']; ?></td>
                                        <td style="border: 1px solid #000;padding: 5px;"><?php echo $lectura['LecMed']; ?></td>
                                        <td style="border: 1px solid #000;padding: 5px;"></td>
                                        <td style="border: 1px solid #000;padding: 5px;"></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
 
                        </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" onclick="window.close();" class="btn btn-block btn-primary btn-flat">Cerrar Ventana</button><br><br>
                    </div>
                </div>
        </div>
    </div>
</section>
<!-- page script -->
<script type="text/javascript">
$(document).ready(function () {
        $("#btn_imprimir").click(function () {
            $("#detalleCicloSubOrden").attr("style","font-size: 8px");
            printdiv('impresion');
        });
        function printdiv(impresion)
        {
            var contenido = document.getElementById(impresion).innerHTML;
            var popupWin = window.open('', '_blank', 'width=1100,height=600');
            var usuario = "<?php echo $userdata['UsrNomPr'] . ' ' . $userdata['UsrApePt'] . ' ' . $userdata['UsrApeMt']; ?>"; 
            var cargo = "<?php echo $userdata['CarDes']; ?>";
            var d = new Date(); 
            var fecha_hora = "<?php echo date('d-m-Y H:i:s') ?>";
            var cabecera = '<table cellspacing="0" width="100%"><thead><tbody><tr style="font-size: 8px">' +
                '<td style="font-size: 8px"><img style="width: 100px; height: 25px;" src="<?php echo base_url() . $userdata['contratante']['CntLog1'] ?>" alt="<?php echo $userdata['contratante']['EmpRaz'] ?>" style="position:absolute; margin-top:0; margin-bottom: 5px; left:0;" /></td>' +
                '<td style="font-size: 8px"><center><h5><b>TOMA DE ESTADO - LECTURAS DE CAMPO</b></h5></center></td>' +
                '<td style="font-size: 8px"><b>Lecturista :</b> <?php echo $lecturista['UsrApePt'] . '  ' . $lecturista['UsrApeMt'] . '  ' . $lecturista['UsrNomPr']; ?></td>' +
                '</tr><tr style="font-size: 8px">' +
                '<td style="font-size: 8px">SCOT-Sistema de Control de Ordenes de Trabajo</td>' +
                '<td style="font-size: 8px"><center><span style="font-size: 12px"><b>Localidades: </b><?php echo $localidades; ?></span></center></td>' +
                '<td style="font-size: 8px"><b><i class="fa fa-calendar"></i> Fecha Ejecución:</b> <?php echo date('d/m/Y', strtotime($OTTomaEstado["OrtFchEj"])) ?> </td>' +
                '</tr></tbody></table>';
            var footer = "<footer class='main-footer' style='font-size: 8px'><small>\n\
                            <div><b>Fecha y Hora de Impresión:</b>"+fecha_hora+"\n\
                            <b>Generado por:</b> "+usuario+" - "+cargo+"</div>\n\
                          </small></footer>";
            popupWin.document.open();
            popupWin.document.write("<link rel='stylesheet' href='<?php echo base_url();?>frontend/bootstrap/css/bootstrap.min.css' />");
            popupWin.document.write("<link rel='stylesheet' href='<?php echo base_url();?>frontend/dist/css/impresion.css' />");
            popupWin.document.write('<html><title>SCOT</title></head><body onload="window.print()">'+ cabecera + contenido + footer + '</body></html>');
            popupWin.document.close(); 
            return false;
        }
});
</script>