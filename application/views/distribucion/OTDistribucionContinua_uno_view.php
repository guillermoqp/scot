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
                        <h3 class="box-title">Orden de Trabajo de Distribución Continua de Recibos y Comunicaciones al Cliente</h3>              
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Por:</label>
                                <div class="form-group">
                                    <div class="control-group"> 
                                        <?php foreach ($niveles as $key => $nivel) { ?>
                                            <input id="nivel" name="nivel" type="radio" onchange="enviarForm('nivel')" value="<?php echo $nivel['NgrId'] ?>" <?php echo ($idNivel == $nivel['NgrId']) ? 'checked' : '' ?>><?php echo $nivel['NgrDes'] ?>   
                                        <?php } ?>  
                                    </div>
                                </div>  
                            </div>
                            <?php if (isset($gruposPredio)) { ?>
                            <?php #var_dump($supervision); ?>
                            <?php #var_dump($ciclo); ?>
                            <?php #var_dump($todo); ?>
                            <?php #echo " Fecha..";echo var_dump($_SESSION['ordenP']['fchEj']) ;?>
                            <?php var_dump($_SESSION['ordenD']['gruposPredio']); ?>
                            <?php #var_dump($_SESSION['ordenP']['buscarGruposPredio']); ?>
                            <?php #echo " detalleCronograma...";echo var_dump($detCron) ?>
                            <?php #echo " Sesion Orden..";echo var_dump($_SESSION['ordenP']['detalle']) ;?>
                                <?php if (isset($esUltimoNivel)) { ?>
                                    <div class="col-md-2">
                                        <label>Elija <?php echo $nivelCiclo['NgrDes']; ?>:</label>
                                        <div class="form-group">
                                            <div class="control-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-search"></i>
                                                    </div>
                                                    <select id="buscarGruposPredio" name="buscarGruposPredio" class="form-control" onchange="enviarForm('buscarGruposPredio')" <?php echo isset($_SESSION['ordenD']['bloqueado']) ? 'readonly' : '' ?> >
                                                        <?php foreach ($gruposPredio as $grupoPredio) { ?>
                                                            <option value="<?php echo $grupoPredio['GprId']; ?>" <?php echo ($_SESSION['ordenD']['buscarGruposPredio'] == $grupoPredio['GprId']) ? 'selected' : '' ?> <?php echo isset($_SESSION['ordenD']['bloqueado']) ? 'readonly' : '' ?>><?php echo $grupoPredio['GprCod']; ?></option>
                                                            <?php } ?>
                                                    </select> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (isset($subciclos)){ ?>
                                    <div class="col-md-3">
                                        <label>Elija Sub-<?php echo $nivelCiclo['NgrDes']; ?>:</label>
                                        <div class="form-group">
                                            <div class="control-group">
                                                <div class="input-group">
                                                    <select id="gruposPredio" name="gruposPredio[]" class="select2" multiple="multiple" data-placeholder="Seleccione SubCiclo" <?php echo isset($_SESSION['ordenD']['bloqueado']) ? 'readonly' : '' ?> style="width: 100%;">
                                                        <?php foreach ($subciclos as $subciclo) { ?>
                                                            <option value="<?php echo $subciclo['GprId'] ?>">
                                                                <?php echo $subciclo['GprCod'] ?>
                                                            </option>
                                                        <?php } ?>   
                                                    </select>
                                                    <div class="input-group-btn">
                                                        <button name="enviar" type="submit" class="btn btn-info btn-flat" onclick="enviarForm('gruposPredio')" type="button"><i class="fa fa-plus"></i> Agregar</button>
                                                    </div><!-- /btn-group -->
                                                </div><!-- /input-group -->
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="col-md-6">
                                        <label>Elige opción:</label>
                                        <div class="form-group">
                                            <div class="control-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-search"></i>
                                                    </div>
                                                    <select id="gruposPredio" name="gruposPredio" class="form-control" onchange="enviarForm('gruposPredio')" <?php echo isset($_SESSION['ordenD']['bloqueado']) ? 'readonly' : '' ?> >
                                                        <?php if (!isset($_SESSION['ordenD']['gruposPredio'])) { ?>
                                                            <?php foreach ($gruposPredio as $grupoPredio) { ?>
                                                            <option value="<?php echo $grupoPredio['GprId']; ?>" <?php echo ($_SESSION['ordenD']['gruposPredio'] == $grupoPredio['GprId']) ? 'selected' : '' ?> <?php echo isset($_SESSION['ordenD']['bloqueado']) ? 'readonly' : '' ?>><?php echo $grupoPredio['GprCod']; ?></option>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                        <?php foreach ($gruposPredio as $grupoPredio) { ?>
                                                            <option value="<?php echo $grupoPredio['GprId']; ?>" <?php echo ($_SESSION['ordenD']['gruposPredio'] == $grupoPredio['GprId']) ? 'selected' : '' ?> <?php echo isset($_SESSION['ordenD']['bloqueado']) ? 'readonly' : '' ?>><?php echo $grupoPredio['GprCod']; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Orden de Trabajo: </label>
                                        <div class="controls">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                </div>
                                                <input id="OrtNum" name="OrtNum" type="text" class="form-control" value="<?php echo $_SESSION['ordenD']['numero']; ?>" readonly/>
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
                                            <input id="OrtEntr" name="OrtEntr" type="text" class="form-control" value="<?php echo $_SESSION['ordenD']['entregado']; ?>" readonly=""/>
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
                                        <textarea id="OrtDes" name="OrtDes" class="form-control" rows="3" ><?php echo $_SESSION['ordenD']['descripcion']; ?></textarea>
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
                                            <select id="OrtAnio" name="OrtAnio" class="form-control" onchange="enviarForm('anio')" <?php echo isset($_SESSION['ordenD']['bloqueado']) ? 'readonly' : '' ?> >
                                                <?php foreach ($anios as $anioitem) { ?>
                                                    <option value="<?php echo $anioitem ?>"<?php echo ($_SESSION['ordenD']['anio'] == $anioitem) ? 'selected' : ''; ?>><?php echo $anioitem ?></option>
                                                <?php } ?>
                                            </select>
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
                                            <select id="OrtPrd" name="OrtPrd" class="form-control" onchange="enviarForm('periodo')" <?php echo isset($_SESSION['ordenD']['bloqueado']) ? 'readonly' : '' ?> >
                                                <?php foreach ($periodos as $periodo) { ?>
                                                    <option value="<?php echo $periodo['PrdId'] ?>"<?php echo ($_SESSION['ordenD']['periodo'] == $periodo['PrdId']) ? 'selected' : ''; ?>><?php echo $periodo['PrdCod'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Distribución:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input id="OrtFchEj" name="OrtFchEj" type="text" class="form-control" onchange="enviarForm('fecha')" data-toggle="tooltip" title="Dato necesario para ingresar Fecha y hora Máxima de Recepción"  <?php echo isset($_SESSION['ordenD']['bloqueado']) ? 'readonly' : '' ?>/>   
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Distribuciones de la Orden de Trabajo de Distribución de Recibos y Comunicaciones al Cliente</h3>              
                        </div>
                        <div class="box-body">
                            <div class="row"> 
                                <div class="col-md-3">
                                    Rendimiento promedio:<input type="text" class="form-control" value="<?php echo $_SESSION['ordenD']['rendimiento']; ?>" disabled=""/>
                                </div>    
                                <div class="col-md-3">
                                    N° Total de Distribuidores :<input type="text" class="form-control" value="<?php echo $_SESSION['ordenD']['totalLecturistas']; ?>" disabled=""/>
                                </div>
                                <div class="col-md-3">
                                    N° Subordenes:<input type="text" class="form-control" value="<?php echo $_SESSION['ordenD']['subordenesAcumulado']; ?>" disabled=""/>
                                </div> 
                                <div class="col-md-3">
                                    Distribuciones Totales de la Orden:<input type="text" class="form-control" value="<?php echo $_SESSION['ordenD']['lecturasAcumulado']; ?>" disabled=""/>
                                </div> 
                            </div><br>
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <table id="ciclos" class="table table-bordered table-striped">
                                        <thead>        
                                            <tr Class="info" >
                                                <th>Dia</th>
                                                <th>Fecha ejecución</th>
                                                <th>N° Recibos asignadas</th>
                                                <th>N° Distribuidores</th>
                                                <!-- <th>Asignación</th> -->
                                                <th>Acción</th>
                                            </tr>
                                        </thead>        
                                        <tbody>
                                            <?php
                                            $cant = 0;
                                            foreach ($_SESSION['ordenD']['detalle'] as $dia => $ordenDia) {
                                                ?>
                                                <tr>
                                                    <td> <?php echo $dia ?></td>
                                                    <td> <?php echo $ordenDia['fecha'] ?></td>
                                                    <td> <?php echo $ordenDia['lecturasRealizar'] ?></td>
                                                    <td> <?php echo $ordenDia['nroLecturistas'] ?></td>
                                                    <!-- <td> <?php echo $ordenDia['aleatorio'] ? 'Aleatoria' : 'Manual' ?></td> -->
                                                    <td class=" ">
                                                        <span data-toggle="tooltip" data-placement="bottom" title="Ver Sub-Ordenes Generadas">
                                                            <a id="detalle" name="detalle" ciclo="<?php echo $dia; ?>" class="btn btn-default btn-flat">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </span>
                                                        <?php if(isset($grupoPredioSeleccionado)) { ?>
                                                        <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                                            <button name="eliminar" type="submit" title="Eliminar" class="btn btn-default btn-flat" value="<?php echo $grupoPredioSeleccionado['GprId']; ?>"><i class="fa fa-trash-o"></i></button>
                                                        </span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php $cant+=1;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                        <input id="OrtFchEm" name="OrtFchEm" type="text" class="form-control" data-toggle="tooltip" title="Para Fecha y hora Máxima de Recepción es necesaria ingresar Fecha de Ejecución" />  
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
                                        <textarea id="OrtObs" name="OrtObs" class="form-control" rows="3"><?php echo $_SESSION['ordenD']['observaciones']; ?></textarea>
                                    </div>       
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="checkbox" value="suborden" name="supervision" class="flat-red" <?php echo $_SESSION['ordenD']['supervision'] ? 'checked' : '' ?>>Añadir Suborden de Supervisión y Control de Calidad de las Distribuciones.
                                    <br> ( <b><?php echo isset($_SESSION['ordenD']['tamanioMuestra']) ? 'Tamaño Muestra : '.$_SESSION['ordenD']['tamanioMuestra'] : '' ?> Distribuciones (Entrega aleatoria de envíos con constancia de entrega por lotes de distribución). </b> )
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button id="btn_guardar" name="btn_guardar" type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> Registrar</button>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo base_url() ?>distribucion/orden/limpiar" type="reset" class="btn btn-block btn-default btn-flat">Limpiar</a>
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

<script type="text/javascript">
$(function () {
    $("#detalleCicloSubOrdenes").dataTable();
});
</script>
<script>  
function enviarForm(opcion) {
    hdn = document.createElement('input');
    hdn.setAttribute('name', 'opcion');
    hdn.setAttribute('type', 'hidden');
    hdn.setAttribute('value', opcion);
    frm = document.getElementById('mainForm');
    frm.appendChild(hdn);
    frm.submit();
};
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

    var SubCiclos = $(".select2").select2({
        language: "es"
    });
    
    <?php if(is_array($_SESSION['ordenD']['gruposPredio'])) { ?>
    var subciclosSeleccionados = <?php echo json_encode($_SESSION['ordenD']['gruposPredio']); ?>; 
    SubCiclos.val(subciclosSeleccionados).trigger("change");
    <?php } ?>                    
    
    $('#ciclos').DataTable();
    $(document).on("click", "#detalle", function ()
    {
        var idCiclo = $(this).attr('ciclo');
        window.open("<?php echo base_url() . 'distribucion/orden/nuevo/detalle_ciclo/'; ?>" + idCiclo, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=50, left=80, width=1100, height=800");
    });
    $(document).on("click", "#eliminar", function ()
    {
        var idCiclo = $(this).attr('ciclo');
        window.location.replace("<?php echo base_url() . 'distribucion/orden/nuevo?eliminar=' ?>" + idCiclo);
    });
    <?php if (isset($_SESSION['ordenD']['fchEj'])) { ?>;
        $('#OrtFchEj').val("<?php echo $_SESSION['ordenD']['fchEj']; ?>");
    <?php } ?>;
            var anioMax = $('#OrtAnio').val();
            var fechaMax = "'"+'31-12-'+anioMax+"'";
            
            var valor = "'" + $('#OrtFchEj').val() + "'";
            var start = new Date();
            var end = new Date(new Date().setYear(start.getFullYear() + 1));
            var fechaServ = "'" + '<?php echo date('d-m-Y') ?>' + "'";
            $('#OrtFchEj').daterangepicker({
                "showDropdowns": true,
                "singleDatePicker": true,
                "autoApply": false,
                "autoclose": true,
                "format": "DD-MM-YYYY",
                "separator": " - ",
                "startDate": valor,
                "minDate": fechaServ,
                "maxDate" : fechaMax,
                "endDate": end,
                "locale": {
                    "format": "DD-MM-YYYY",
                    "separator": " - ",
                    "applyLabel": "Aceptar",
                    "cancelLabel": "Cancelar",
                    "customRangeLabel": "Custom",
                    "autoclose": true,
                    "daysOfWeek": [
                        "Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"
                    ],
                    "monthNames": [
                        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                        "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                    ],
                    "firstDay": 1
                }
            });
            $('#OrtFchEm').val('');
            var anioActual = parseInt(anioMax);
            var suma = anioActual+1;
            var fechaMax2 = "'"+'31-01-'+suma+"'";
            
            var fechEjec = $('#OrtFchEj').val();
            var ini = moment(fechEjec, "DD-MM-YYYY");
            var fechaMin = moment(ini, "DD-MM-YYYY").add(<?php echo $cant; ?>, 'days');
            $('#OrtFchEm').daterangepicker({
                "showDropdowns": true,
                "autoApply": true,
                "timePickerIncrement": 1,
                "singleDatePicker": true,
                "timePicker": true,
                "timePicker12Hour": false,
                "timePicker24Hour": true,
                "timePickerSeconds": true,
                "autoclose": true,
                "format": "DD-MM-YYYY HH:mm:ss",
                "startDate": fechaMin,
                "minDate": fechaMin,
                "maxDate" : fechaMax2,
                "locale": {
                    "format": "DD-MM-YYYY HH:mm:ss",
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
                        "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                    ],
                    "firstDay": 1
                }
            });
});
</script>