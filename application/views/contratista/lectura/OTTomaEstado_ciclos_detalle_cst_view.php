<!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datatables/buttons.dataTables.min.css">
<style>
    .modal .modal-dialog { width: 95%; }
</style>
<section class="content">
    <div class="row">
        <form method="post"> 
            <div class="col-xs-12">
                <?php if (isset($_SESSION['mensaje'])) { ?>
                    <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $_SESSION['mensaje'][1]; ?>
                    </div>
                <?php } ?>
                <h4 class="modal-title"><i class="fa fa-book"></i> SubOrdenes por Ciclo: Orden de Trabajo de Toma de Estado de suministros</h4>
                <div class="row">   
                    <div class="col-md-4">
                        Nro Suministros: <input id="nroSuministros" name="nroSuministros" type="number" step="any" class="form-control" value="<?php echo $cantidad; ?>" disabled=""/>
                    </div>   

                    <div class="col-md-4">
                        Nro Lecturistas Necesarios: <input type="text" id="nroLecturistasN" name="nroLecturistasN" class="form-control" value="<?php echo $nroLecturistas; ?>" disabled=""/>
                    </div> 

                    <div class="col-md-4">
                        Promedio Lecturas por Lecturista:<input type="text" id="promLectxLectu" name="promLectxLectu" class="form-control" value="<?php echo ceil($cantidad/$nroLecturistas); ?>" disabled=""/>
                    </div> 
                </div>
                <hr style="margin-top:10px;margin-bottom: 10px;">
                <div class="row">   
                    <div class="col-md-6">
                        <label>Tipo de Distribución de Sub Ordenes:</label>
                        <div class="form-group">
                            <div class="control-group"> 
                                <input id="aleatoria" name="asignacion" type="radio" required value="0" onchange="actualizarDatos()" <?php echo( $error == FALSE ) ? 'checked': ''; ?>>Aleatoria
                                <input id="manual" name="asignacion" type="radio" required value="1" onchange="actualizarDatos()">Manual               
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">  
                            <table id="detalleCicloSubOrdenes" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr Class="info" >
                                        <th>Nro Sub orden</th>
                                        <th>Nro Suministros</th>
                                        <th>Fecha Ejecución</th>
                                        <th>Lecturista Asignado</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyDetalle">
                                    <?php foreach ($subordenesSinAsignar as $nroSuborden => $suborden) { ?>
                                        <tr>
                                            <td>
                                                <?php echo $suborden['SolCod'] ?>
                                            </td>
                                            <td><?php echo $suborden['suministros'] ?></td>
                                            <td>
                                                <?php echo $suborden['SolFchEj'] ?>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <select class="lecturistas form-control" name="lecturista_<?php echo $nroSuborden ?>">
                                                        <?php foreach ($lecturistas as $lectId => $lecturista) { ?>
                                                            <option value="<?php echo $lecturista['LtaId'] ?>" <?php echo set_select('lecturista_' . $nroSuborden, $lecturista['LtaId'] , -1==$lecturista['LtaId'] ); ?>>
                                                                <?php echo $lecturista['UsrApePt'] . ' ' . $lecturista['UsrApeMt'] . ' ' . $lecturista['UsrNomPr'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </td>

                                            <td><span data-toggle="tooltip" data-placement="bottom" title="Suministros por Sub Orden"><a href="#modal-detalle-usuarios" id="detalle" name="detalle" subOrden="<?php echo $suborden['SolId'] ?>" role="button" data-toggle="modal" class="btn btn-default btn-flat"><i class="fa fa-eye"></i></a></span></td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>     
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" name="asignar_lecturistas" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> Asignar Lecturistas a Subordenes</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" onclick="window.close();" class="btn btn-block btn-default btn-flat">Cerrar</button>
                    </div>
                </div>  
            </div>
        </form>
    </div>
</section>

<!-- MODAL : Detalle Suministros por Sub Ciclo -->
<div class="modal fade" id="modal-detalle-usuarios" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim" method="post">    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-group"></i> Usuarios por Sub Orden: Orden de Trabajo de Toma de Estado de suministros</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="detalleCicloSubOrden" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                        <tr Class="info" >
                                            <th>ITEM</th>
                                            <th>CÓDIGO FAC.</th>
                                            <th>NOMBRES/RAZ. SOCIAL</th>
                                            <th>URBANIZACIÓN</th>
                                            <th>CALLE</th>
                                            <th>CLIE. NRO</th>
                                            <th>MED. CÓDIGO</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>     
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-block btn-default btn-flat" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                        </div>
                    </div>   
                </div>     
            </div>
        </div>
    </form>
</div>
<!-- Select2 -->
<script src="<?php echo base_url() ?>frontend/plugins/select2/select2.full.min.js"></script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- DATA TABES PRINT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/buttons.flash.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jszip.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/pdfmake.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/vfs_fonts.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/buttons.html5.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/buttons.print.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.select.min.js" type="text/javascript"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() ?>frontend/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url() ?>frontend/plugins/fastclick/fastclick.js"></script>

<!-- Validar COMBO-BOX UNICOS SELECT -->
<script src="<?php echo base_url() ?>frontend/plugins/validation/unique.js" type="text/javascript"></script>

<script>
<?php if (isset($cerrar)) { ?>
window.onunload = refreshParent;
function refreshParent() {
    window.opener.location.reload();
}
window.close();
<?php } ?>
$(document).ready(function ()
{
    $("#detalleCicloSubOrdenes").dataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "bSort": false
    });
    
    <?php if( $error == FALSE ) { ?>
        actualizarDatos();
    <?php } ?>
        
    $("#detalleCiclo").DataTable({
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]]
    });
    
    $(document).on("click", "#detalle", function ()
    {
        var idSubOrden = $(this).attr('subOrden');
            $.ajax({
                url: "<?php echo base_url() . 'cst/toma_estado/get_detalle_x_suborden_json'; ?>",
                type: "POST",
                data: ({idSubOrden: idSubOrden}),
                dataType: "html",
                success: function (data)
                {
                    var oTable = $("#detalleCicloSubOrden").DataTable();
                    if (JSON.parse(data) == null)
                    {
                        oTable.clear();
                        oTable.draw();
                    } else
                    {
                        oTable.clear();
                        oTable.draw();
                        oTable.destroy();
                        var detalleCiclo = JSON.parse(data);
                        var trHTML = '';
                        $.each(detalleCiclo, function (i, item)
                        {
                            trHTML += '<tr><td>' + 
                                    (i + 1) + '</td><td>' + 
                                    item.LecCodFc + '</td><td>' +
                                    item.LecNom + '</td><td>' + 
                                    (( item.LecUrb == null ) ? '--' : item.LecUrb) + '</td><td>' +
                                    ((item.LecCal == null ) ? '--' : item.LecCal)  + '</td><td>' + 
                                    item.LecMun + '</td><td>' + item.LecMed + '</td>';
                        });
                        $('#detalleCicloSubOrden').append(trHTML);
                        $("#detalleCicloSubOrden").DataTable({
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                        dom: 'lfBrtip',
                        buttons: [
                        {
                            extend: 'copy',
                            text: 'Copiar al Portapapeles',
                            key: {
                                key: 'c',
                                altKey: true
                            }
                            },{
                                extend: 'pdf',
                                text: 'Exportar a PDF'
                            },{
                                extend: 'excel',
                                text: 'Exportar a excel'
                            },{
                                extend: 'print',
                                text: 'Imprimir Pagina Actual',
                                exportOptions: {
                                    modifier: {
                                        page: 'current'
                                    }
                                },
                                customize: function (win)
                                {
                                    $(win.document.body).css('font-size', '10pt');
                                    $(win.document.body).find('table').addClass('compact').css('font-size', '10pt');
                                }
                                },{
                                    extend: 'print',
                                    text: 'Imprimir Todos',
                                    customize: function (win)
                                    {
                                        $(win.document.body).css('font-size', '10pt');
                                        $(win.document.body).find('table').addClass('compact').css('font-size', '10pt');
                                    }}]
                        });
                    }
                },
                error: function (xhr, ajaxOptions, thrownError)
                {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        });
});
function actualizarDatos()
{
    if ($("#aleatoria").is(':checked')) {
        //$("#tbodyDetalle :input").prop("disabled", true);
        $("#tbodyDetalle :input").attr("readonly", true);
        
        <?php if(is_array($subordenesSinAsignar)) { ?>
            var subordenes = <?php echo json_encode(array_column($subordenesSinAsignar, 'SolId')); ?>;
            var lecturistas = <?php echo json_encode(array_column($lecturistas, 'LtaId')); ?>;
        <?php } ?>
            
        var random_lecturistas = [];
        while(random_lecturistas.length < subordenes.length){
          var randomnumber=Math.ceil(Math.random()*lecturistas.length);
          var found=false;
          for(var i=0;i<random_lecturistas.length;i++){
                if(random_lecturistas[i]==randomnumber){found=true;break}
          }
          if(!found)random_lecturistas[random_lecturistas.length]=randomnumber;
        }
        $(".lecturistas").each(function( index ) {
            var options = $(this).children('option');
            var random = random_lecturistas[index];
            options.attr('selected', false).eq(random-1).attr('selected', true);
        });
        
    
        var arreglo = random_lecturistas;
        var sorted_arr = arreglo.slice().sort(); 
        var results = [];
        for (var i = 0; i < arreglo.length - 1; i++) {
            if (sorted_arr[i + 1] == sorted_arr[i]) {
                results.push(sorted_arr[i]);
            }
        }
        console.log("Arreglo de Lecturistas Random : " + random_lecturistas);
        console.log("repetidos : " + results);
   
    } else {
        var k = new Unique('.lecturistas','null');
        //$("#tbodyDetalle :input").prop("disabled", false);
        $("#tbodyDetalle :input").attr("readonly", false);
    }
}
</script> 