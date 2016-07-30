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
    <div class="row invoice-info">
        <div class="col-sm-12">
            <h5><i class="fa fa-file"></i> ORDEN DE TRABAJO N°: <?php echo $OTTomaEstado["OrtNum"]; ?></h5>
            <small class="pull-right">Fecha de Envio: <?php echo date('d-m-Y',  strtotime($OTTomaEstado["OrtFchEn"]))?></small>
            <img style="width: 210px; height: 50px; background-color: #FFF" src="<?php echo base_url().$userdata['contratante']['CntLog1']; ?>" alt="<?php echo $userdata['contratante']['EmpRaz']?>">
            <br><hr style="margin-top:10px;margin-bottom: 10px;">
            <p style="margin: 0px;"><b><?php echo $userdata['contratante']['EmpRaz']; ?></b> - <b> RUC: </b> <span><?php echo $userdata['contratante']['EmpRuc'];  ?></span>- <b>Dirección:</b> <?php echo $userdata['contratante']['EmpDir']; ?></p> 
            <p style="margin: 0px;"><b>E-mail:</b><?php echo $userdata['contratante']['EmpCor']; ?> <b>Teléfono: </b><?php echo $userdata['contratante']['EmpTel']; ?>
            <b>Representante:</b> <?php echo $userdata['contratante']['EmpNomRp']; ?> <b>D.N.I:</b> <?php echo $userdata['contratante']['EmpDniRp']; ?></p>
        </div>
     </div>
    <hr style="margin-top:10px;margin-bottom: 10px;">
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
          <!--<b>Orden de Trabajo N°:</b><?php echo $OTTomaEstado["OrtNum"]; ?><br>
          <br>-->
          <b><i class="fa fa-calendar"></i> Fecha Ejecución:</b> <?php echo date('d-m-Y',  strtotime($OTTomaEstado["OrtFchEj"]))?> <br>
          <b><i class="fa fa-calendar"></i> Fecha Máxima de Recepción:</b> <?php echo date('d-m-Y H:i:s',  strtotime($OTTomaEstado["OrtFchEm"]))?> <br>
          <b><i class="fa fa-calendar"></i> Mes de Facturación:</b> <?php echo $OTTomaEstado['periodo']['PrdCod'].' / '.$OTTomaEstado['periodo']['PrdAni'] ?><br>
          <b>Descripción del Trabajo:</b> <?php echo $OTTomaEstado['OrtDes']; ?><br>
          <b>Actividad:</b> <?php echo $proceso; ?>  <br>
          <b>Sub Actividad:</b> ---
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <hr style="margin-top:10px;margin-bottom: 10px;">
      <!-- Table row -->
      <div class="row invoice-info">
        <div class="col-sm-12">
            <h3><b><?php echo $Nivel['NgrDes']; ?>:</b> <?php echo $GprNivel['GprCod']; ?></h3>
            <h4>Sub Ciclos de la Orden de Trabajo de Toma de Estado de suministros a ejecutar</h4>
            <div class="table-responsive">
                    <table id="ciclos" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Sub Ciclo</th>
                                <th>Localidades</th>
                                <th>Cantidad de Lecturistas Asignados</th>
                            </tr>
                        </thead>
                          <tbody>
                            <?php foreach ($Subciclos as $Subciclo):?>
                                <tr>
                                    <td><?php echo $Subciclo['GprCod'];?></td>
                                    <td><?php echo $Subciclo['GprDes'];?></td>
                                    <?php foreach ($LecSubCiclos as $LecSubCiclo):?>
                                        <?php if ($LecSubCiclo['GprCod']==$Subciclo['GprCod']){?>
                                            <td><?php echo $LecSubCiclo['Lecturistas'];?></td>
                                        <?php }?>
                                    <?php endforeach;?>
                                </tr>
                            <?php endforeach;?>
                          </tbody>
                    </table>
            </div>
        </div>
      </div>
    <hr style="margin-top:10px;margin-bottom: 10px;">
      <!-- Table row -->
      <div class="row no-print invoice-info">
        <div class="col-sm-12">
            <h4>Sub Ordenes de Trabajo de Toma de Estado de suministros ( Asignadas a cada Lecturista )</h4>
            <?php if(empty($subordenes)) { ?>
                <p>La Contratista aún no ha asignado las Sub-Ordenes a los Lecturistas.</p>
            <?php } else { ?>
            <div class="table-responsive">
                    <table id="detalleCicloSubOrdenes" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>N° SubOrden</th>
                                <th>Sub Ciclo</th>
                                <th>Lecturista</th>
                                <th>Foto</th>
                                <th>N° Suministros</th>
                            </tr>
                        </thead>
                          <tbody>
                            <?php 
                                $i = 0;
                                foreach ($subordenes as $suborden) { ?>
                                <?php foreach ($lecturistas as $lecturista) { ?>
                                    <?php if($suborden['SolLtaId']==$lecturista['LtaId']) { ?>
                                        <tr>
                                            <td><?php echo $i+1; ?></td>
                                            <td><?php echo $suborden['GprCod']; ?></td>
                                            <td><?php echo $lecturista['UsrApePt'].'  '.$lecturista['UsrApeMt'].'  '.$lecturista['UsrNomPr']; ?></td>
                                            <td><img src="<?php echo base_url() . $lecturista['UsrFot']; ?>" style="width: 40px; height: 40px;" class="img-circle"></td>
                                            <td><?php echo $suborden['suministros']; ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } 
                                $i+=1;
                            ?>
                            <?php } ?>
                          </tbody>
                    </table>
                </div>
            <?php } ?>
        <!-- /.col -->
        </div>
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
      <div class="row no-print invoice-info">
        <a id="btn_imprimir" class="btn btn-primary btn-flat pull-right"><i class="fa fa-print"></i> Imprimir Orden de Trabajo</a>
      </div>
</div>
</form>   
</section>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function ()
    {
        $("#detalleCicloSubOrdenes").dataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "bSort": false});
    });
</script>

<script type="text/javascript">
$(document).ready(function () 
{
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

