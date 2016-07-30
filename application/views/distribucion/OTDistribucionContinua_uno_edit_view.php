<style>
    .modal .modal-dialog { width: 95%; }
</style>
<!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/select2/select2.min.css">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <form id="mainForm" name="prueba" role="form" method="post" action="<?php echo str_replace('/index.php', '', current_url()); ?>"> 
                <?php if (isset($_SESSION['mensaje'])) { ?>
                    <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $_SESSION['mensaje'][1]; ?>
                    </div>
                <?php } ?>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Datos de Orden de Trabajo de Toma de Estado de suministros</h3>              
                    </div><!-- /.box-header -->
                    <?php
                    if (isset($ordenTrabajoEdit)) {
                        $campoDes = $ordenTrabajoEdit['OrtDes'];
                        $porciones = explode("<br>", $campoDes);
                        $entregadoA = $porciones[0];
                        $descripcion = $porciones[1];
                    }
                    ?>
                    <div class="box-body">
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Orden de Trabajo: (Número/Código)</label>
                                        <div class="controls">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                </div>
                                                <?php if (isset($ordenTrabajoEdit)) { ?>
                                                <input id="OrtNum" name="OrtNum" type="text" class="form-control" value="<?php echo $ordenTrabajoEdit['OrtNum']; ?>" readonly/>
                                                <?php } ?>
                                            </div><!-- /.input group -->
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Entregado a:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <?php if (isset($ordenTrabajoEdit)) { ?>
                                            <input id="OrtEntr" name="OrtEntr" type="text" class="form-control" value="<?php echo $entregadoA; ?>" readonly=""/>
                                            <?php } ?>
                                        </div>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row">   
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción del Trabajo:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-file-text"></i>
                                        </div>
                                        <?php if (isset($ordenTrabajoEdit)) { ?>
                                        <textarea id="OrtDes" name="OrtDes" class="form-control" rows="3" ><?php echo $descripcion; ?></textarea>
                                        <?php } ?>
                                    </div>       
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <div class="control-group">
                                        <label>Año:</label>         
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <?php if(isset($ordenTrabajoEdit)){ ?>
                                            <select id="OrtAnio" name="OrtAnio" class="form-control" required="" disabled="">
                                                <option value="<?php echo $periodo['PrdAni']; ?>" selected><?php echo $periodo['PrdAni']; ?></option>
                                            </select>
                                            <?php }?>
                                        </div><!-- /.input group -->
                                    </div> 
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Periodo de Facturación:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <?php if(isset($ordenTrabajoEdit)){ ?>
                                            <select id="OrtPrd" name="OrtPrd" class="form-control" required="" disabled="">
                                                <option value="<?php echo $periodo['PrdCod']; ?>" selected><?php echo $periodo['PrdCod']; ?></option>
                                            </select>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Ejecución:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <?php if (isset($ordenTrabajoEdit)) { ?>
                                            <input id="OrtFchEj2" name="OrtFchEj2" type="text" class="form-control" value="<?php echo $ordenTrabajoEdit['OrtFchEj']; ?>" disabled=""/>
                                        <?php } ?>       
                                    </div>
                                </div>
                            </div>
                            
                        </div>    
                    </div>
                </div>
                <?php
                if (isset($ordenTrabajoEdit)) {
                    ?>
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Lecturas de la Orden de Trabajo de Toma de Estado de suministros</h3>              
                        </div>
                        <div class="box-body table-responsive">
                            <table id="ciclosOrden" class="table table-bordered table-striped">
                                <thead>
                                    <tr Class="info" role="row">
                                        <th>Sub Ciclo</th>
                                        <th>Localidad</th>
                                        <th>Cantidad de Lecturistas Asignados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($SubciclosOrden as $SubcicloOrden): ?>
                                        <tr>
                                            <td><?php echo $SubcicloOrden['GprCod']; ?></td>
                                            <td><?php echo $SubcicloOrden['GprDes']; ?></td>
                                            <?php foreach ($LecSubCiclos as $LecSubCiclo): ?>
                                                <?php if ($LecSubCiclo['GprCod'] == $SubcicloOrden['GprCod']) { ?>
                                                    <td><?php echo $LecSubCiclo['Lecturistas']; ?></td>
                                                <?php } ?>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Sub Ordenes de Trabajo de Toma de Estado de suministros ( Asignadas a cada Lecturista )</h3>              
                        </div>
                        <div class="box-body table-responsive">
                            <table id="detalleCicloSubOrdenes" class="table table-bordered table-striped">
                                <thead>
                                    <tr Class="info" >
                                        <th>Dia</th>
                                        <th>Fecha ejecución</th>
                                        <th>N° Conexiones asignadas</th>
                                        <th>N° Lecturistas</th>
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
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
                <div class="box">
                    <div class="box-body">
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha y hora Máxima de Recepción de Información:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <?php if (isset($ordenTrabajoEdit)) { ?>
                                            <input id="OrtFchEm2" name="OrtFchEm2" type="text" class="form-control" value="<?php echo $ordenTrabajoEdit['OrtFchEm']; ?>"/>
                                        <?php } ?>    
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div> 
                        </div>    
                        <div class="row">   
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observaciones:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-eye"></i>
                                        </div>
                                        <?php if (isset($ordenTrabajoEdit)) { ?>
                                        <textarea id="OrtObs" name="OrtObs" class="form-control" rows="3" ><?php echo $ordenTrabajoEdit['OrtObs']; ?></textarea>
                                        <?php } ?>
                                    </div>       
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button id="btn_guardar" name="btn_guardar" type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo (!isset($ordenTrabajoEdit)) ? 'Registrar' : 'Actualizar'; ?></button>
                    </div>
                    <div class="col-md-6">
                    <?php if (isset($ordenTrabajoEdit)) { ?>
                        <button type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
                    <?php } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- InputMask -->
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<!-- Select2 -->
<script src="<?php echo base_url() ?>frontend/plugins/select2/select2.full.min.js"></script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- date-range-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- date-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() ?>frontend/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url() ?>frontend/plugins/fastclick/fastclick.js"></script>
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/iCheck/all.css">
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() ?>frontend/plugins/iCheck/icheck.min.js"></script>

<script type="text/javascript">
$(function() {
//    $("#detalleCicloSubOrdenes").dataTable({
//        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
//        "bSort": false
//        });
});
function enviarForm(opcion) {
	hdn = document.createElement('input');
	hdn.setAttribute('name', 'opcion');
	hdn.setAttribute('type', 'hidden');
	hdn.setAttribute('value', opcion);
	frm = document.getElementById('mainForm')
	frm.appendChild(hdn);
	frm.submit();
};
</script>
<script>
$(document).ready(function ()
{
    /* MASCARAS FORMULARIO PRINCIPAL */
    //$("#OrtNum").inputmask("d-m-y");
    $("#OrtFchEm").inputmask("d-m-y h:s:s");
    $("#OrtFchEm2").inputmask("y-m-d h:s:s");
    $("#OrtFchEj").inputmask("d-m-y");
    <?php if (isset($_SESSION['ordenD']['detalle'])) { ?>
            $(document).on("click", "#btn_guardar", function ()
            {
                swal({
                    title: "",
                    text: "Espere un momento...",
                    imageUrl: "<?php echo base_url() ?>frontend/dist/img/cargando.gif",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            });
    <?php } ?>
    $(document).on("click", "#detalle", function ()
    {
        var idCiclo = $(this).attr('ciclo');
        window.open("<?php echo base_url() . 'toma_estado/orden/nuevo/detalle_ciclo/'; ?>" + idCiclo, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=600, left=600, width=800, height=800");
    });
    $(document).on("click", "#eliminar", function ()
    {
        var idCiclo = $(this).attr('ciclo');
        window.location.replace("<?php echo base_url() . 'toma_estado/orden/nuevo?eliminar=' ?>" + idCiclo);
    });
    <?php if (isset($_SESSION['ordenD']['fchEj'])) { ?>;
        $('#OrtFchEj').val("<?php echo $_SESSION['ordenD']['fchEj']; ?>");
    <?php } ?>;
<?php if (isset($ordenTrabajoEdit)) { ?>;
            $('#OrtFchEj2').datepicker({
                format: "YYYY-MM-DD",
                todayBtn: true,
                language: "es",
                autoclose: true,
                todayHighlight: true
            });
            
            var fechEjec = '<?php echo $ordenTrabajoEdit['OrtFchEj']; ?>';
            var ini = moment(fechEjec, "YYYY-MM-DD");
            var fechaMin = moment(ini, "YYYY-MM-DD").add(<?php echo $cantidad; ?>, 'days');
            $('#OrtFchEm2').daterangepicker({
                "autoApply": true,
                "timePickerIncrement": 1,
                "singleDatePicker": true,
                "timePicker": true,
                "timePicker12Hour": false,
                "timePicker24Hour": true,
                "timePickerSeconds": true,
                "minDate": fechaMin,
                "autoclose": true,
                "format": "YYYY-MM-DD HH:mm:ss",
                "locale": {
                    "format": "YYYY-MM-DD HH:mm:ss",
                    "separator": " - ",
                    "applyLabel": "Aceptar",
                    "cancelLabel": "Cancelar",
                    "fromLabel": "From",
                    "toLabel": "To",
                    "customRangeLabel": "Custom",
                    "autoclose": true,
                    "daysOfWeek": [
                        "Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"
                    ],
                    "monthNames": [
                        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                        "Augosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                    ],
                    "firstDay": 1
                }
            });
<?php } ?>;
});
</script>