<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datatables/buttons.dataTables.min.css">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Distribuidores de Recibos y Comunicaciones al Cliente Registrados</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="lecturistas" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Usuario</th>
                                <th>DNI</th>
                                <th>Apellidos y Nombres</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($distribuidores as $distribuidor) { ?>
                                <tr class="<?php echo $distribuidor['DbrSup']=='t'?'success':'' ?>">
                                    <td>
                                        <?php echo $distribuidor['UsrNomCt']; ?>
                                    </td>
                                    <td>
                                        <?php echo $distribuidor['UsrDni']; ?>
                                    </td>
                                    <td class="nivel2">
                                        <?php echo $distribuidor['UsrApePt'] . ' ' . $distribuidor['UsrApeMt'] . ' ' . $distribuidor['UsrNomPr'] ?>
                                        <?php echo $distribuidor['DbrSup']=='t'?'</br><h4><b>SUPERVISOR</b></h4>':'' ?>
                                    </td>
                                    <td>
                                        <i class="fa fa-fw fa-phone"></i>Teléfono:
                                        <a href="tel:+<?php echo $distribuidor['UsrTel'] ?>" class="Blondie"><b><?php echo $distribuidor['UsrTel'] ?></b></a>      
                                        <br>
                                        <i class="glyphicon fa-fw glyphicon-phone"></i>Móvil 1:
                                        <a href="tel:+<?php echo $distribuidor['UsrMov1'] ?>" class="Blondie"><b><?php echo $distribuidor['UsrMov1'] ?></b></a>      
                                        <br>
                                        <i class="fa fa-fw fa-mobile-phone"></i>Móvil 2:
                                        <a href="tel:+<?php echo $distribuidor['UsrMov2'] ?>" class="Blondie"><b><?php echo $distribuidor['UsrMov2'] ?></b></a>
                                    </td>
                                    <td>
                                        <i class="fa fa-fw fa-envelope-o"></i>
                                        <a href="mailto:<?php echo $distribuidor['UsrCor'] ?>?Subject=Mensaje%20Importante" target="_top"><?php echo $distribuidor['UsrCor'] ?></a>       
                                    </td>
                                    <td>
                                        <img style="height: 80px; width: 75px;" src="<?php echo base_url() . $distribuidor['UsrFot'] ?>">
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- DATA TABES PRINT -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datatables/buttons.dataTables.min.css">
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/buttons.flash.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jszip.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/pdfmake.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/vfs_fonts.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/buttons.html5.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/buttons.print.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.select.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/buttons.colVis.min.js" type="text/javascript"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() ?>frontend/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url() ?>frontend/plugins/fastclick/fastclick.js"></script>

<script type="text/javascript">
$(document).ready($(function() {
  $("#lecturistas").dataTable({
    bSort: false,
    lengthMenu: [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, "Todos"]
    ],
    dom: 'lfBrtip',
    buttons: [{
      extend: 'copy',
      text: 'Copiar',
      key: {
        key: 'c',
        altKey: true
      }
    }, {
      extend: 'pdf',
      text: 'Exportar a PDF'
    }, {
      extend: 'excel',
      text: 'Exportar a Excel'
    }, {
      extend: 'print',
      text: 'Imprimir Pagina Actual',
      exportOptions: {
        columns: ':visible',
        modifier: {
          page: 'current'
        }
      },
      customize: function(win) {
        $(win.document.body).css('font-size', '6px');
        $(win.document.body).find('table').addClass('compact').css('font-size', '6px');
      }
    }, {
      extend: 'print',
      text: 'Imprimir Todos',
      customize: function(win) {
        $(win.document.body).css('font-size', '6px');
        $(win.document.body).find('table').addClass('compact').css('font-size', '6px');
      }
    }, {
      extend: 'colvis',
      text: 'Columnas'
    }],
    columnDefs: [{
      visible: false
    }]
  });
}));
</script>

