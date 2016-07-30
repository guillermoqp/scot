<!-- REPORTE DE ORDEN DE TRABAJO -->
<section class="invoice">
<div id="impresion"> 
    <div class="row invoice-info">
        <div class="col-sm-12">
          <h5><i class="fa fa-file"></i> ORDEN DE TRABAJO N°: <?php echo $OrdenTrabajo["OrtNum"]; ?></h5>
        <small class="pull-right">Fecha de Envio: <?php echo date('d-m-Y',  strtotime($OrdenTrabajo["OrtFchEn"]))?></small><br>
        <small class="pull-right">Estado de la Orden: <b> <?php echo $EstadoOrden["DprDes"]; ?></b>  </small>
        <img style="width: 210px; height: 50px; background-color: #FFF" src="<?php echo base_url().$userdata['contratante']['CntLog1']; ?>" alt="<?php echo $userdata['contratante']['EmpRaz']?>">
        <br><hr style="margin-top:10px;margin-bottom: 10px;">
        <p style="margin: 0px;"><b><?php echo $userdata['contratante']['EmpRaz']; ?></b> - <b> RUC: </b> <span><?php echo $userdata['contratante']['EmpRuc'];  ?></span>- <b>Dirección:</b> <?php echo $userdata['contratante']['EmpDir']; ?></p> 
        <p style="margin: 0px;"><b>E-mail: </b><?php echo $userdata['contratante']['EmpCor']; ?> <b>Teléfono:</b><?php echo $userdata['contratante']['EmpTel']; ?> 
        <b>Representante: </b> <?php echo $userdata['contratante']['EmpNomRp']; ?> <b>D.N.I: </b> <?php echo $userdata['contratante']['EmpDniRp']; ?></p>
        </div>
     </div>
    <hr style="margin-top:10px;margin-bottom: 10px;">
    <?php 
    if(isset($OrdenTrabajo))
    {
        $campoDes = $OrdenTrabajo['OrtDes'];
        $porciones = explode("<br>", $campoDes);
        $entregadoA = $porciones[0];
        $descripcion = $porciones[1];
        $mes = $porciones[2];
        $anio = $porciones[3];
    }
   ?>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          <b>De:</b>
          <address>
            <i><?php echo $UsrEn['UsrNomPr'] . ' ' . $UsrEn['UsrApePt']. ' ' . $UsrEn['UsrApeMt']; ?></i><br>
            <b>Contratante:</b> <?php echo $UsrEn['EmpRaz']; ?><br>
            <b>Cargo:</b> <?php echo $UsrEn['RolDes']; ?><br>
            <b>Teléfono:</b> <?php echo $UsrEn['UsrTel']; ?><br>
            <b>E-mail:</b> <?php echo $UsrEn['UsrCor']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Para:</b>
          <address>
            <i><?php echo $UsrRs['UsrNomPr'] . ' ' . $UsrRs['UsrApePt']. ' ' . $UsrRs['UsrApeMt']; ?></i><br>
            <b>Contratista:</b> <?php echo $UsrRs['CstRaz']; ?><br>
            <b>Cargo:</b> <?php echo $UsrRs['RolDes']; ?><br>
            <b>Teléfono:</b> <?php echo $UsrRs['UsrTel']; ?><br>
            <b>E-mail:</b> <?php echo $UsrRs['UsrCor']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <!--<b>Orden de Trabajo N°: </b><?php echo $OrdenTrabajo["OrtNum"]; ?><br>
          <br>-->
          <b><i class="fa fa-calendar"></i> Fecha Ejecución:</b> <?php echo date('d-m-Y',  strtotime($OrdenTrabajo["OrtFchEj"]))?> <br>
          <b><i class="fa fa-calendar"></i> Fecha Máxima de Recepción:</b> <?php echo date('d-m-Y H:i:s',  strtotime($OrdenTrabajo["OrtFchEm"]))?> <br>
          <b><i class="fa fa-calendar"></i> Mes de Facturación:</b> <?php echo $mes; ?> / <?php echo $anio; ?><br>
          <b>Descripción del Trabajo:</b> <?php echo $descripcion; ?><br>
          <b>Actividad:</b> <?php echo $proceso; ?> <br>
          <b>Sub Actividades a realizar:</b>
          <?php foreach ($subactividades as $subactividad): ?>
                <?php if($OrdenTrabajo['OrtId']===$subactividad['OrtId']){?>
                    <?php echo $subactividad['SacDes']; ?>
                <dd></dd>
                <?php } ?>
          <?php endforeach; ?>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <hr style="margin-top:10px;margin-bottom: 10px;">
      <!-- Table row -->
      <div class="row invoice-info">
        <div class="col-sm-12">
            <h4>Archivos de Orden de Trabajo de <?php echo $proceso; ?></h4>
            <?php
                if(!empty($archivosEnv))
                { ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Archivo</th>
                            <th>Fecha de Subida</th>
                            <th class="no-print"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($archivosEnv as $archivoEnv): ?>
                            <tr>
                                <td class="mailbox-name"><?php echo $archivoEnv['ArcNomOr']; ?></td>
                                <td class="mailbox-date"><?php echo date('d-m-Y H:i:s', strtotime($archivoEnv['ArcFchSu'])) ?></td>
                                <td class="no-print mailbox-name"><a target="_blank" href="<?php echo base_url().str_replace("%2F", "/", $archivoEnv['ArcRutUr']); ?>" download="<?php echo $archivoEnv['ArcNomOr']; ?>"><i class="fa fa-fw fa-download"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php } else {?>
            <p>No existen archivos enviados.</p>
            <?php }?>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <hr style="margin-top:10px;margin-bottom: 10px;">
      <div class="row invoice-info">
        <!-- accepted payments column -->
        <div class="col-sm-12">
          <p class="lead">Observaciones:</p>
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
           <?php echo $OrdenTrabajo['OrtObs']; ?>
          </p>
        </div>
        <!-- /.col 
        <div class="col-xs-6">
          <p class="lead" style="margin-left: 7px">Monto Total al: <?php echo date('d/m/Y',  strtotime($OrdenTrabajo["OrtFchEr"]))?></p>

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Sub Total:</th>
                <td>S/. 250.30</td>
              </tr>
              <tr>
                <th>IGV (18%)</th>
                <td>S/. 10.34</td>
              </tr>
              <tr>
                <th>Total con IGV:</th>
                <td>S/. 250.30</td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <div class="row invoice-info">
        <div class="col-sm-6 invoice-col">
            <p style="text-align: center;"><span>_________________________</span><br><b><?php echo $UsrEn['UsrNomPr'] . ' ' . $UsrEn['UsrApePt']. ' ' . $UsrEn['UsrApeMt']; ?></b><br><span style="font-size: 12px;">Representante de EPS</span></p>
        </div>
        <div class="col-sm-1 invoice-col">
        </div>
        <div class="col-sm-5 invoice-col">
            <p style="text-align: center;"><span>_________________________</span><br><b><?php echo $UsrRs['UsrNomPr'] . ' ' . $UsrRs['UsrApePt']. ' ' . $UsrRs['UsrApeMt']; ?></b><br><span style="font-size: 12px;">Representante del Contratista</span></p>
        </div>
      </div>
</div>
      <?php
        if (isset($accion)) 
        {   
            if ($accion == 'ver') 
            {   ?>
                <div class="row no-print invoice-info">
                  <a id="btn_imprimir" class="btn btn-primary btn-flat pull-right"><i class="fa fa-print"></i> Imprimir Orden de Trabajo</a>
                </div>
            <?php
            }
        }   
        ?>
</section>
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/moment.min.js"></script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- page script -->
<script type="text/javascript">
    $(document).ready(function () {
        $("#btn_imprimir").click(function () 
        {
           print('impresion');
        });
        
        function print(impresion)
        {
            var data = document.getElementById(impresion).innerHTML;
            var Window = window.open('', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=80,width=1100,height=800');
            var usuario = "<?php echo $userdata['UsrNomPr'] . ' ' . $userdata['UsrApePt'] . ' ' . $userdata['UsrApeMt']; ?>"; 
            var cargo = "<?php echo $userdata['CarDes']; ?>";
            var fecha_hora = "<?php echo date('d-m-Y H:i:s') ?>";
            var footer = "<footer class='main-footer'>\n\
                            <div><b>Fecha y Hora de Impresión:</b> <small>"+fecha_hora+"</small></div>\n\
                            <div><b>Generado por:</b> <small>"+usuario+" - "+cargo+"</small></div>\n\
                            <div class='pull-left'>SCOT - Sistema de Control de Ordenes de Trabajo</div><br>\n\
                            <div class='pull-left'><strong>&copy; 2016 - Gerencia Comercial - Sedalib S.A.</strong></div>\n\
                          </footer>";
            Window.document.open();
            Window.document.write("<link rel='stylesheet' href='<?php echo base_url();?>frontend/bootstrap/css/bootstrap.minPrint.css' />");
            Window.document.write("<link rel='stylesheet' href='<?php echo base_url();?>frontend/dist/css/ionicons.minPrint.css' />");
            Window.document.write("<link rel='stylesheet' href='<?php echo base_url();?>frontend/dist/css/AdminLTE.minPrint.css' />");
            Window.document.write("<link rel='stylesheet' href='<?php echo base_url();?>frontend/dist/css/font-awesomePrint.css' />");
            Window.document.write('<html><head><title>SCOT</title></head><body onload="window.print()">'+ data + footer + '</body></html>');
            Window.document.close();
            //window.close();
            return false;
        }
    });
</script>


