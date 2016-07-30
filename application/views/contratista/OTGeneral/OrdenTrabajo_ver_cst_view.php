<!-- REPORTE DE ORDEN DE TRABAJO -->
<section class="invoice">
    <?php if (isset($_SESSION['mensaje'])) { ?>
        <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $_SESSION['mensaje'][1]; ?>
        </div>
    <?php } ?>
<form role="form" accept-charset="utf-8" enctype="multipart/form-data" method="post" action="<?php echo str_replace('/index.php', '', current_url())."/guardar" ; ?>">
<div id="impresion">
    <div class="row">
        <div class="col-sm-12">
            <h5><i class="fa fa-file"></i> ORDEN DE TRABAJO N°: <?php echo $OrdenTrabajo["OrtNum"]; ?></h5>
            <small class="pull-right">Fecha de Envio: <?php echo date('d-m-Y',  strtotime($OrdenTrabajo["OrtFchEn"]))?></small>
            <img style="width: 210px; height: 50px; background-color: #FFF" src="<?php echo base_url().$userdata['contratante']['CntLog1']; ?>" alt="<?php echo $userdata['contratante']['EmpRaz']?>">
            <br><hr style="margin-top:10px;margin-bottom: 10px;">
            <p style="margin: 0px;"><b><?php echo $userdata['contratante']['EmpRaz']; ?></b> - <b> RUC: </b> <span><?php echo $userdata['contratante']['EmpRuc'];  ?></span>- <b>Dirección:</b> <?php echo $userdata['contratante']['EmpDir']; ?></p> 
            <p style="margin: 0px;"><b>E-mail:</b><?php echo $userdata['contratante']['EmpCor']; ?> <b>Teléfono: </b><?php echo $userdata['contratante']['EmpTel']; ?>
            <b>Representante:</b> <?php echo $userdata['contratante']['EmpNomRp']; ?> <b>D.N.I:</b> <?php echo $userdata['contratante']['EmpDniRp']; ?></p>
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
                            <tr role="row">
                                <th>Archivo</th>
                                <th>Fecha de Subida</th>
                                <th class="no-print"></th>
                            </tr>
                        </thead>
                          <tbody>
                          <?php foreach ($archivosEnv  as $archivoEnv): ?>
                          <tr>
                            <td class="mailbox-name"><?php echo $archivoEnv['ArcNomOr']; ?></td>
                            <td class="mailbox-date"><?php echo date('d-m-Y H:i:s',  strtotime($archivoEnv['ArcFchSu']))?></td>
                            <td class="no-print mailbox-name"><a target="_blank" href="<?php echo base_url().str_replace("%2F", "/", $archivoEnv['ArcRutUr']); ?>" download="<?php echo $archivoEnv['ArcNomOr']; ?>"><i class="fa fa-fw fa-download"></i></a></td>
                          </tr>
                          <?php endforeach; ?>
                          </tbody>
                        </table>
                    </div>
                    <?php } else {?>
                    <p>No existen archivos enviados por la Contratante.</p>
                    <?php }?>
        </div>
        <!-- /.col -->
      </div>
      <hr style="margin-top:10px;margin-bottom: 10px;">
      <div class="row invoice-info">
        <!-- accepted payments column -->
        <div class="col-sm-12">
          <p class="lead">Observaciones:</p>
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
           <?php echo $OrdenTrabajo['OrtObs']; ?>
          </p>
        </div>
        <!-- /.col -->
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <hr style="margin-top:10px;margin-bottom: 10px;">
      <div class="row invoice-info">
        <!-- Table row -->
        <?php
            if(!empty($archivosRec))
        { ?>
        <div class="col-sm-12">
            <h4>Archivos de Orden de Trabajo de <?php echo $proceso; ?> Ejecutada</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr role="row">
                            <th>Archivo</th>
                            <th>Fecha de Subida</th>
                            <th class="no-print"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($archivosRec  as $archivoRec ): ?>
                        <tr>
                            <td class="mailbox-name"><?php echo $archivoRec['ArcNomOr']; ?></td>
                            <td class="mailbox-date"><?php echo date('d-m-Y H:i:s',  strtotime($archivoRec['ArcFchSu']))?></td>
                            <td class="no-print mailbox-name"><a target="_blank" href="<?php echo base_url().str_replace("%2F", "/", $archivoRec['ArcRutUr']); ?>" download="<?php echo $archivoRec['ArcNomOr']; ?>"><i class="fa fa-fw fa-download"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
        </div>
        <!-- /.col -->
        <?php } else {?>
        <div class="col-xs-12 no-print">
            <h4>Archivos de Orden de Trabajo de <?php echo $proceso; ?> Ejecutada</h4>
            <fieldset>
                    <section>
                        <div class="row invoice-info">
                            <div class="col-md-6 invoice-col">
                                <input type="file" name="upload_file1" id="upload_file1" readonly="true" required/>
                            </div>
                            <div class="col-md-6 invoice-col">
                                <div id="moreImageUploadLink" style="display:none;margin-left: 10px;">
                                    <a href="javascript:void(0);" id="attachMore" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> Añadir otro Archivo</a>
                                </div>
                            </div>
                        </div><br>
                        <div style="clear:both;"></div>
                        <div id="moreImageUpload" style="margin-bottom: 20px;"></div>
                    </section>
            </fieldset>
        </div>
        <?php } ?>
      </div>
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
    <div class="row no-print invoice-info">
            <?php
            if(empty($archivosRec))
            { ?>
            <div class="col-sm-6 invoice-col">
                <a id="btn_imprimir" class="btn btn-primary btn-flat pull-left"><i class="fa fa-print"></i> Imprimir Orden de Trabajo</a>
            </div>
            <div class="col-sm-6 invoice-col">
                <button type="submit" class="btn btn-primary btn-flat pull-right">Enviar Archivos <i class="fa fa-arrow-right"></i></button>
            </div>
            <?php }else {?>
            <div class="col-sm-12 invoice-col">
                <a id="btn_imprimir" class="btn btn-primary btn-flat pull-right"><i class="fa fa-print"></i> Imprimir Orden de Trabajo</a>
            </div>
            <?php }?>
        </div>
        <!-- /.row -->
</form>   
</section>

<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- page script -->
<script type="text/javascript">
$(document).ready(function() {
        $("input[id^='upload_file']").each(function() {
            var id = parseInt(this.id.replace("upload_file", ""));
            $("#upload_file" + id).change(function() {
                if ($("#upload_file" + id).val() !== "") {
                    $("#moreImageUploadLink").show();
                }
            });
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var upload_number = 2;
        $('#attachMore').click(function() {
            //añadir mas archivos
            var moreUploadTag = '<div class="row invoice-info">';
            moreUploadTag += '<div class="col-md-6 invoice-col"><label for="upload_file"' + upload_number + '>Archivo N° ' + upload_number + '</label><input type="file" id="upload_file' + upload_number + '" name="upload_file' + upload_number + '"/></div>';
            moreUploadTag += '<div class="col-md-6 invoice-col" style="margin-top: 13px;"><div style="margin-left: 10px;"><a href="javascript:del_file(' + upload_number + ')" style="cursor:pointer;" onclick="" class="btn btn-danger btn-flat"><i class="fa fa-trash-o"></i> Eliminar ' + upload_number + '</a></div></div></div>';
            $('<dl id="delete_file' + upload_number + '">' + moreUploadTag + '</dl>').fadeIn('slow').appendTo('#moreImageUpload');
            upload_number++;
        });
    });
</script>
<script type="text/javascript">
    function del_file(eleId) {
        var ele = document.getElementById("delete_file" + eleId);
        ele.parentNode.removeChild(ele);
    }
</script>
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
            Window.document.write('<html><head><title>SCOT</title></head><body onload="window.print()">'+ data + footer + '</html>');
            Window.document.close();
            
            return false;
        }

    });
</script>

