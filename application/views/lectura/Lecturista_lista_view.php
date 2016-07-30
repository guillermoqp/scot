<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datatables/buttons.dataTables.min.css">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>-->
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Lecturistas Registrados</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="lecturistas" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Usuario</th>
                                <th>DNI</th>
                                <th>Apellidos y Nombres</th>
                                <th>Teléfono</th>
                                <th>Correo </th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($lecturistas as $lecturista) { ?>
                                <tr class="<?php echo $lecturista['LtaSup']=='t'?'success':'' ?>">
                                    <td>
                                        <?php echo $lecturista['UsrNomCt']; ?>
                                    </td>
                                    <td>
                                        <?php echo $lecturista['UsrDni']; ?>
                                    </td>
                                    <td class="nivel2">
                                        <?php echo $lecturista['UsrApePt'] . ' ' . $lecturista['UsrApeMt'] . ' ' . $lecturista['UsrNomPr'] ?>
                                        <?php echo $lecturista['LtaSup']=='t'?'</br><h4><b>SUPERVISOR</b></h4>':'' ?>
                                    </td>
                                    <td>
                                        <i class="fa fa-fw fa-phone"></i>Teléfono:
                                        <a href="tel:+<?php echo $lecturista['UsrTel'] ?>" class="Blondie"><b><?php echo $lecturista['UsrTel'] ?></b></a>      
                                        <br>
                                        <i class="glyphicon fa-fw glyphicon-phone"></i>Móvil 1:
                                        <a href="tel:+<?php echo $lecturista['UsrMov1'] ?>" class="Blondie"><b><?php echo $lecturista['UsrMov1'] ?></b></a>      
                                        <br>
                                        <i class="fa fa-fw fa-mobile-phone"></i>Móvil 2:
                                        <a href="tel:+<?php echo $lecturista['UsrMov2'] ?>" class="Blondie"><b><?php echo $lecturista['UsrMov2'] ?></b></a>       
                                    </td>
                                    <td>
                                        <i class="fa fa-fw fa-envelope-o"></i>
                                        <a href="mailto:<?php echo $lecturista['UsrCor'] ?>?Subject=Mensaje%20Importante" target="_top"><?php echo $lecturista['UsrCor'] ?></a>       
                                    </td>
                                    <td>
                                        <img style="height: 80px; width: 75px;" src="<?php echo base_url() . $lecturista['UsrFot'] ?>">
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
$(document).ready($(function ()
{  
    $("#lecturistas").dataTable({
        bSort: false,
        lengthMenu: [[10, 25, 50, 100, -1],[10, 25, 50, 100, "Todos"]],
	dom: 'lfBrtip',
	buttons: [{
                extend: 'copy',
                text: 'Copiar',
                key: {
                key: 'c',
                altKey: true
            }}, 
        {
		extend: 'pdf',
		text: 'Exportar a PDF'
	}, {
		extend: 'excel',
		text: 'Exportar a Excel'
	}, {
		extend: 'print',
		text: 'Imprimir Pagina Actual',
		exportOptions: 
                {
                    columns: ':visible',
                    modifier: {
			page: 'current'
                    }
		},
		customize: function(win) {
			$(win.document.body).css('font-size', '8px');
			$(win.document.body).find('table').addClass('compact').css('font-size', '8px');
		}
	}, {
		extend: 'print',
		text: 'Imprimir Todos',
                exportOptions: 
                {
                    columns: ':visible'
		},
		customize: function(win) {
			$(win.document.body).css('font-size', '8px');
			$(win.document.body).find('table').addClass('compact').css('font-size', '8px');
		}
	},
        {
            extend : 'colvis',
            text: 'Columnas'
        }],
        columnDefs: [ {
            visible: false
        } ]
    });
}));
</script>

