<!-- sweetAlert. -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/sweetalert/sweetalert2.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url() ?>frontend/plugins/sweetalert/sweetalert2.min.js"></script> 
<section class="content">
      <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <script>
                    swal("Dias de Acceso al Sistema", "<?php echo $_SESSION['mensaje'][1]; ?>" , "<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'warning' : 'success'; ?>"); 
                </script>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Sub Ordenes de Trabajo de Toma de Estado de suministros del Dia</h3>
                    <h4></h4>
                    <div class="table-responsive">
                        <table id="detalleCicloSubOrdenes" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>N° SubOrden</th>
                                <th>Sub Ciclo</th>
                                <th>Fecha de Ejecución</th>
                                <th>N° Suministros/Lecturas</th>
                                <th class="no-print">Acción</th>
                            </tr>
                        </thead>
                          <tbody>
                          <?php 
                            $i = 0;
                            foreach ($subordenes as $suborden) { ?>
                                <tr>
                                  <td><?php echo $i+1; ?></td>
                                  <td><?php echo $suborden['SolCod']; ?></td>
                                  <td><?php echo $suborden['GprCod']; ?></td>
                                  <td><?php echo $suborden['SolFchEj']; ?></td>
                                  <td><?php echo $suborden['suministros']; ?></td>
                                  <?php if ($this->acceso_cls->tienePermiso('imprimir_suborden')) { ?>
                                  <td class="no-print">
                                  <a id="imprimir" name="imprimir" idOrden="<?php echo $suborden['OrtId']; ?>" idSubOrden="<?php echo $suborden['SolId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Imprimir Suborden">
                                      <span class="glyphicon glyphicon-file"></span></a>
                                  <?php } ?>
                                  <?php if ($this->acceso_cls->tienePermiso('digitar_suborden')) { ?>
                                  <a id="digitar" name="digitar" class="btn btn-default btn-flat" data-toggle="tooltip" idOrden="<?php echo $suborden['OrtId']; ?>" idSubOrden="<?php echo $suborden['SolId']; ?>" data-placement="bottom" title="Digitar Suborden">
                                     <span class="glyphicon glyphicon-sort-by-order-alt"></span></a>
                                  </td>
                                  <?php } ?>
                                </tr>
                                <?php $i+=1;} ?>
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
<script type="text/javascript">
function popup(url) 
{
 params  = 'width='+screen.width;
 params += ', height='+screen.height;
 params += ', top=0, left=0';
 params += ', fullscreen=yes';

 newwin=window.open(url,'windowname4', params);
 if (window.focus) {newwin.focus()}
 return false;
} 
$(document).ready(function () 
{
        
        $("#detalleCicloSubOrdenes").dataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "bSort": false});

        $(document).on("click", "#imprimir", function ()
        {
            var idSubOrden = $(this).attr('idSubOrden');
            var iOrden = $(this).attr('idOrden');
            popup("<?php echo base_url(); ?>"+'cst/toma_estado/orden/'+iOrden+'/imprimir_suborden/'+idSubOrden);
        });
        
        $(document).on("click", "#digitar", function ()
        {
            var idSubOrden = $(this).attr('idSubOrden');
            var iOrden = $(this).attr('idOrden');
            popup("<?php echo base_url(); ?>"+'cst/toma_estado/orden/'+iOrden+'/digitar_suborden/'+idSubOrden);
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
                            <div><b>Fecha y Hora de Impresión: </b><small>"+fecha_hora+"</small></div>\n\
                            <div><b>Generado por:</b>  <small>"+usuario+" - "+cargo+"</small></div>\n\
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