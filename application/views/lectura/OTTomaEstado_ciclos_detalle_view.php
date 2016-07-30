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
                <?php if (isset($_SESSION['mensaje2'])) { ?>
                    <div class="alert alert-<?php echo ($_SESSION['mensaje2'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $_SESSION['mensaje2'][1]; ?>
                    </div>
                <?php } ?>
                <h4 class="modal-title"><i class="fa fa-book"></i> SubOrdenes por Ciclo: Orden de Trabajo de Toma de Estado de suministros</h4>
                <div class="row">   
                    <div class="col-md-4">
                        Nro Suministros: <input id="nroSuministros" name="nroSuministros" type="number" step="any" class="form-control" value="<?php echo $_SESSION['orden']['detalle'][$nroDia]['lecturasRealizar']; ?>" disabled=""/>
                    </div>   

                    <div class="col-md-4">
                        Nro Lecturistas Necesarios: <input type="text" id="nroLecturistasN" name="nroLecturistasN" class="form-control" value="<?php echo $_SESSION['orden']['detalle'][$nroDia]['nroLecturistas']; ?>" disabled=""/>
                    </div> 

                    <div class="col-md-4">
                        Promedio Lecturas por Lecturista:<input type="text" id="promLectxLectu" name="promLectxLectu" class="form-control" value="<?php echo ceil($_SESSION['orden']['detalle'][$nroDia]['lecturasRealizar'] / $_SESSION['orden']['detalle'][$nroDia]['nroLecturistas']); ?>" disabled=""/>
                    </div> 
                </div>
                <!--
                <div class="row">   
                    <div class="col-md-6">
                        <label>Tipo de Distribución de Sub Orden:</label>
                        <div class="form-group">
                            <div class="control-group"> 
                                <input id="aleatoria" name="asignacion" type="radio" required value="0" 
                                       <?php #echo $_SESSION['orden']['detalle'][$nroDia]['aleatorio'] ? 'checked' : '' ?> onchange="actualizarDatos()">Aleatoria
                                <input id="manual" name="asignacion" type="radio" required value="1" 
                                       <?php #echo $_SESSION['orden']['detalle'][$nroDia]['aleatorio'] ? '' : 'checked' ?> onchange="actualizarDatos()">Manual               
                            </div>
                        </div>  
                    </div>
                </div>
                -->
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">  
                            <table id="detalleCiclo" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr Class="info" >
                                        <th>Nro Sub orden</th>
                                        <th>Nro Suministros</th>
                                        <!-- <th>Lecturista Asignado</th> -->
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyDetalle">
                                    <?php foreach ($subordenes as $nroSuborden => $suborden) { ?>
                                        <tr>
                                            <td>
                                                <?php echo $nroSuborden ?>
                                            </td>
                                            <td><?php echo $suborden['cantidad'] ?></td>
                                            <!--
                                            <td><div class="form-group">
                                                    <select class="form-control select2" style="width: 100%;" name="lecturista_<?php echo $nroSuborden ?>" <?php echo $aleatorio ? 'disabled' : '' ?>>
                                                        <option value=0></option>
                                                        
                                                        <?php foreach ($lecturistas as $lectId => $lecturista) { ?>
                                                        <option value="<?php echo $lectId  ?>" <?php echo set_select('lecturista_'.$nroSuborden, $lectId, $suborden['idLecturista']==$lectId); ?>>
                                                                <?php echo $lecturista['UsrApePt'] . ' ' . $lecturista['UsrApeMt'] . ' ' . $lecturista['UsrNomPr'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                            </div></td>
                                            -->
                                            <td><span data-toggle="tooltip" data-placement="bottom" title="Suministros por Sub Orden"><a href="#modal-detalle-usuarios" id="detalle" name="detalle" subOrden="<?php echo $nroSuborden ?>" role="button" data-toggle="modal" class="btn btn-default btn-flat"><i class="fa fa-eye"></i></a></span></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>     
                    </div>
                </div>
                <div class="row">
                    <!--
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> Modificar Subordenes por ciclo</button>
                    </div> 
                    -->
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
                        <!--<div class="col-md-6">
                            <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo (!isset($ordenTrabajoEdit)) ? 'Agregar Clientes a la Orden de Trabajo' : 'Actualizar Orden de Trabajo'; ?></button>
                        </div>-->
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
        $("#detalleCiclo").DataTable({
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]]
        });
        actualizarDatos();
        $(document).on("click", "#detalle", function ()
        {
            var nroDia = <?php echo $nroDia; ?>;
            var nroSuborden = $(this).attr('subOrden');
            $.ajax({
                url: "<?php echo base_url() . 'toma_estado/get_detalle_x_suborden_json'; ?>",
                type: "POST",
                data: ({nroDia: nroDia, nroSuborden: nroSuborden}),
                dataType: "html",
                success: function (data)
                {
                    console.log(data);
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
                            trHTML += '<tr><td>' + (i + 1) + '</td><td>' + item.ProCodFc + '</td><td>' +
                            item.ProNom + '</td><td>' + (( item.UrbDes == null ) ? '--' : item.UrbDes) + '</td><td>' +
                            ((item.CalDes == null ) ? '--' : item.CalDes)  + '</td><td>' + item.PreNumMc + '</td><td>' +
                            item.MedNum + '</td>';
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
            $("#tbodyDetalle :input").prop("disabled", true);
    } else {
            $("#tbodyDetalle :input").prop("disabled", false);
    }
}
</script> 