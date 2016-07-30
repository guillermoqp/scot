<!-- REPORTE DE ORDEN DE TRABAJO -->
<section class="invoice">
    <?php if (isset($_SESSION['mensaje'])) { ?>
        <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $_SESSION['mensaje'][1]; ?>
        </div>
    <?php } ?>
<form role="form" accept-charset="utf-8" enctype="multipart/form-data" method="post" action="<?php echo str_replace('/index.php', '', current_url()); ?>">
<div id="impresion">
    <div class="row">
        <div class="col-xs-12">
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
          <b><i class="fa fa-calendar"></i> Fecha Inicio de Ejecución:</b> <?php echo date('d-m-Y',  strtotime($OTTomaEstado["OrtFchEj"]))?> <br>
          <b><i class="fa fa-calendar"></i> Fecha Máxima de Recepción:</b> <?php echo date('d-m-Y h:m:s',  strtotime($OTTomaEstado["OrtFchEm"]))?> <br>
          <b><i class="fa fa-calendar"></i> Mes de Facturación:</b> <?php echo $OTTomaEstado['periodo']['PrdCod'].' / '.$OTTomaEstado['periodo']['PrdAni'] ?><br>
          <b>Descripción del Trabajo:</b> <?php echo $OTTomaEstado['OrtDes']; ?><br>
          <b>Actividad: <?php echo $proceso; ?> </b> <br>
          <b>Sub Actividad:</b> ---
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <hr style="margin-top:10px;margin-bottom: 10px;">
      <!-- Table row -->
      <div class="row invoice-info">
        <div class="col-xs-12">
            <h3>Ciclo: <?php echo $cicloOrden['GprCod']; ?></h3>
            <h4>Sub Ciclos de la Orden de Trabajo de Toma de Estado de suministros a ejecutar</h4>
            <div class="table-responsive">
                    <table id="ciclos" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Sub Ciclo</th>
                                <th>Descripción</th>
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
    <?php if(empty($subordenes)) { ?>
    <div class="row invoice-info">
        <div class="col-xs-12">
            <h4>Lecturas de la Orden de Trabajo de Toma de Estado de suministros</h4>
            <div class="row"> 
                <div class="col-md-3">
                    Rendimiento por Lecturista:<input type="text" class="form-control" value="<?php echo $rendimiento; ?>" disabled=""/>
                </div>    
                <div class="col-md-3">
                    N° Total de Lecturistas:<input type="text" class="form-control" value="<?php echo $totalLecturistas; ?>" disabled=""/>
                </div>
                <div class="col-md-3">
                    N° Subordenes:<input type="text" class="form-control" value="<?php echo $totalSubordenes; ?>" disabled=""/>
                </div> 
                <div class="col-md-3">
                    Lecturas Totales de la Orden:<input type="text" class="form-control" value="<?php echo $cantidad; ?>" disabled=""/>
                </div> 
            </div><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="subordenes" class="table table-bordered table-striped">
                            <thead>        
                                <tr Class="info" >
                                    <th>Dia</th>
                                    <th>Fecha ejecución</th>
                                    <th>N° Conexiones asignadas</th>
                                    <th>N° Lecturistas</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>        
                            <tbody>
                                <?php
                                foreach ($SubOrdenesDia as $dia => $SubOrdenDia) {
                                ?>
                                <tr>
                                    <td> <?php echo $dia+1 ?></td>
                                    <td> <?php echo $SubOrdenDia['SolFchEj'] ?></td>
                                    <td> <?php echo $SubOrdenDia['cantidad'] ?></td>
                                    <td> <?php echo $SubOrdenDia['nroLecturistas'] ?></td>
                                    <td class=" ">
                                        <?php if($SubOrdenDia['asignadas']===FALSE) { ?>
                                        <span data-toggle="tooltip" data-placement="bottom" title="Modificar la asignación Lecturistas">
                                            <a id="detalle" name="detalle" fchej="<?php echo $SubOrdenDia['SolFchEj']; ?>" class="btn btn-default btn-flat">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </span>
                                        <small class="label label-danger"><i class="fa fa-clock-o"></i> Aun no han sido Asignadas Subordenes</small>
                                        <?php   }  
                                        if($SubOrdenDia['asignadas']===TRUE) { ?>
                                            <small class="label label-success"><i class="fa fa-clock-o"></i> Ya se Asignaron subordenes de Toma de Estado a los lecturistas</small>
                                        <?php } ?>
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
      <?php } else { ?>
        <div class="row no-print invoice-info">
        <div class="col-xs-12">
            <h4>Sub Ordenes de Trabajo de Toma de Estado de suministros ( Asignadas a cada Lecturista )</h4>
            <div class="table-responsive">
                        <table id="detalleCicloSubOrdenes" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info">
                                <th>#</th>
                                <th>N° SubOrden</th>
                                <th>Sub Ciclo</th>
                                <th>Fecha de Ejecución</th>
                                <th>Lecturista</th>
                                <th>Foto</th>
                                <th>N° Suministros/Lecturas</th>
                                <?php if ($this->acceso_cls->tienePermiso('imprimir_suborden') || $this->acceso_cls->tienePermiso('digitar_suborden') ) { ?>
                                <th class="no-print">Acción</th>
                                <?php } ?>
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
                                  <td><?php echo $suborden['SolCod']; ?></td>
                                  <td><?php echo $suborden['GprCod']; ?></td>
                                  <td><?php echo $suborden['SolFchEj']; ?></td>
                                  <td><?php echo $lecturista['UsrApePt'].'  '.$lecturista['UsrApeMt'].'  '.$lecturista['UsrNomPr']; ?></td>
                                  <td><img src="<?php echo base_url() . $lecturista['UsrFot']; ?>" style="width: 40px; height: 40px;" class="img-circle"></td>
                                  <td><?php echo $suborden['suministros']; ?></td>
                                  
                                  <?php if ($this->acceso_cls->tienePermiso('imprimir_suborden')) { ?>
                                  <td class="no-print">
                                  <a id="imprimir" name="imprimir" idSubOrden="<?php echo $suborden['SolId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Imprimir Suborden">
                                      <span class="glyphicon glyphicon-file"></span></a>
                                  <?php } ?>
                                      
                                  <?php if ($this->acceso_cls->tienePermiso('digitar_suborden')) { ?>
                                  <a id="digitar" name="digitar" class="btn btn-default btn-flat" data-toggle="tooltip" idSubOrden="<?php echo $suborden['SolId']; ?>" data-placement="bottom" title="Digitar Suborden">
                                     <span class="glyphicon glyphicon-sort-by-order-alt"></span></a>
                                  </td>
                                  <?php } ?>
                                </tr>
                                <?php } ?>
                            <?php }
                                $i+=1;?>
                          <?php } ?>
                          </tbody>
                        </table>
            </div>
        <!-- /.col -->
        </div>
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
      <div class="row no-print invoice-info">
        <div class="col-xs-12 invoice-col">
            <a id="btn_imprimir" class="btn btn-primary btn-flat pull-right" style="margin-right: 5px;"><i class="fa fa-print"></i> Imprimir Orden de Trabajo</a>
        </div>
      </div>
      <?php  } ?>
</div>
</form>   
</section>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>


<script type="text/javascript">
function popup(url) 
{
 params  = 'width='+screen.width;
 params += ', height='+screen.height;
 params += ', top=0, left=0'
 params += ', fullscreen=yes';
 newwin=window.open(url,'windowname4', params);
 if (window.focus) {newwin.focus()}
 return false;
} 
$(document).ready(function () 
{
        $("#detalleCiclo").dataTable({
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
            "bSort": false
        });
        
        <?php if(empty($subordenes)) { ?>
            $("#subordenes").dataTable({
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                "bSort": false
            });    
        <?php } else { ?> 
            $("#detalleCicloSubOrdenes").dataTable({
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                "bSort": false
            });
        <?php } ?>    
        
        
        $(document).on("click", "#detalle", function ()
        {
        var FchEj = $(this).attr('fchej');
        window.open("<?php echo base_url() . 'cst/toma_estado/orden/ver/'.$OTTomaEstado["OrtId"].'/detalle_ciclo/'; ?>" + FchEj , "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=50, left=80, width=1100, height=800");
        });
    
        $(document).on("click", "#imprimir", function ()
        {
            var idSubOrden = $(this).attr('idSubOrden');
            popup("<?php echo base_url().'cst/toma_estado/orden/'.$OTTomaEstado["OrtId"].'/imprimir_suborden/'; ?>"+idSubOrden);
            //window.open("<?php echo base_url().'cst/toma_estado/orden/'.$OTTomaEstado["OrtId"].'/imprimir_suborden/'; ?>"+idSubOrden, "_blank", "toolbar=no, fullscreen=yes, scrollbars=auto");
        });
        
        $(document).on("click", "#digitar", function ()
        {
            var idSubOrden = $(this).attr('idSubOrden');
            popup("<?php echo base_url().'cst/toma_estado/orden/'.$OTTomaEstado["OrtId"].'/digitar_suborden/'; ?>"+idSubOrden);
            //window.open("<?php echo base_url().'cst/toma_estado/orden/'.$OTTomaEstado["OrtId"].'/digitar_suborden/'; ?>"+idSubOrden, "_blank", "toolbar=no, fullscreen=yes, scrollbars=auto");
        });
        
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