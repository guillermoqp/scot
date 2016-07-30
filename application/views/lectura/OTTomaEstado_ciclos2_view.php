<style>
    .modal .modal-dialog { width: 95%; }
</style>
<!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/select2/select2.min.css">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <form role="form" method="post" action="<?php echo str_replace('/index.php', '', current_url()); ?>"> 
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
                        $mes = $porciones[2];
                        $anio = $porciones[3];
                        $observaciones = $porciones[4];
                    }
                    ?>
                    <div class="box-body">
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Orden de Trabajo N°:</label>
                                        <div class="controls">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                </div>
                                                <?php if(isset($ordenTrabajoEdit)){ ?>
                                                <input id="OrtNum" name="OrtNum" type="text" class="form-control" value="<?php echo $ordenTrabajoEdit['OrtNum']; ?>" data-mask readonly=""/>
                                                <?php } else{?>
                                                <input id="OrtNum" name="OrtNum" type="text" class="form-control" value="<?php echo (isset($detCron)) ? '01-'.$detCron['ClmMes'].'-'.$detCron['ClmAno'] : $_SESSION['orden']['numero']; ?>" data-mask/>
                                                <?php }?>
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
                                            <input id="OrtEntr" name="OrtEntr" type="text" class="form-control" value="<?php echo (isset($ordenTrabajoEdit)) ? $entregadoA : $_SESSION['orden']['entregado']; ?>" readonly=""/>
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
                                        <textarea id="OrtDes" name="OrtDes" class="form-control" rows="3" ><?php echo (isset($ordenTrabajoEdit)) ? $descripcion : $_SESSION['orden']['descripcion']; ?></textarea>
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
                                                <input id="OrtAnio" name="OrtAnio" type="text" class="form-control" data-inputmask='"mask": "9999"' value="<?php echo $anio; ?>" data-mask required readonly=""/>
                                            <?php } else{?>
                                            <input id="OrtAnio" name="OrtAnio" type="text" class="form-control" data-inputmask='"mask": "9999"' value="<?php echo (isset($detCron)) ? $detCron['ClmAno'] : $_SESSION['orden']['anio']; ?>" data-mask required <?php echo isset($_SESSION['orden']['bloqueado'])?'readonly':'' ?>/>
                                            <?php }?>
                                        </div><!-- /.input group -->
                                    </div> 
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Mes de Facturación:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <?php if (isset($ordenTrabajoEdit)) { ?>
                                            <select id="OrtMes" name="OrtMes" class="form-control" disabled="">
                                                    <option value="<?php echo $mes; ?>" selected><?php echo $mes; ?></option>
                                                    <option value="Enero">Enero</option> 
                                                    <option value="Febrero">Febrero</option>
                                                    <option value="Marzo">Marzo</option>
                                                    <option value="Abril">Abril</option>
                                                    <option value="Mayo">Mayo</option>
                                                    <option value="Junio">Junio</option>
                                                    <option value="Julio">Julio</option>
                                                    <option value="Agosto">Agosto</option>
                                                    <option value="Septiembre">Septiembre</option>
                                                    <option value="Octubre">Octubre</option>
                                                    <option value="Noviembre">Noviembre</option>
                                                    <option value="Diciembre">Diciembre</option>
                                                </select>
                                            <?php } else { ?>
                                            <select id="OrtMes" name="OrtMes" class="form-control" required <?php echo isset($_SESSION['orden']['bloqueado'])?'readonly':'' ?>>
                                                    <option value="<?php echo $_SESSION['orden']['mes']; ?>" selected><?php echo $_SESSION['orden']['mes']; ?></option>
                                                    <option value="Enero">Enero</option> 
                                                    <option value="Febrero">Febrero</option>
                                                    <option value="Marzo">Marzo</option>
                                                    <option value="Abril">Abril</option>
                                                    <option value="Mayo">Mayo</option>
                                                    <option value="Junio">Junio</option>
                                                    <option value="Julio">Julio</option>
                                                    <option value="Agosto">Agosto</option>
                                                    <option value="Septiembre">Septiembre</option>
                                                    <option value="Octubre">Octubre</option>
                                                    <option value="Noviembre">Noviembre</option>
                                                    <option value="Diciembre">Diciembre</option>
                                            </select>
                                            <?php } ?>
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
                                        <?php if (isset($detCron)) { ?>
                                            <input id="OrtFchEj2" name="OrtFchEj2" type="text" class="form-control"/>
                                        <?php } else { ?>
                                            <?php if (isset($ordenTrabajoEdit)) { ?>
                                            <input id="OrtFchEj2" name="OrtFchEj2" type="text" class="form-control" disabled=""/>
                                        <?php } else { ?>
                                            <input id="OrtFchEj" name="OrtFchEj" type="text" class="form-control" required <?php echo isset($_SESSION['orden']['bloqueado'])?'readonly':'' ?>/>
                                        <?php } ?>
                                        <?php } ?>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha y hora Máxima de Recepción de Información:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <?php if (isset($ordenTrabajoEdit)) { ?>
                                            <input id="OrtFchEm2" name="OrtFchEm2" type="text" class="form-control" value="<?php echo $ordenTrabajoEdit['OrtFchEm']; ?>"/>
                                        <?php } else { ?>
                                            <input id="OrtFchEm" name="OrtFchEm" type="text" class="form-control"/>
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
                                        <textarea id="OrtObs" name="OrtObs" class="form-control" rows="3" ><?php echo (isset($ordenTrabajoEdit)) ? $observaciones : set_value('OrtObs'); ?></textarea>
                                    </div>       
                                </div>
                            </div> 
                        </div>
                    </div>
                </div><!-- /.box -->
                    <?php
                        if(isset($ordenTrabajoEdit))
                    { ?>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Ciclos de la Orden de Trabajo de Toma de Estado de suministros</h3>              
                    </div>
                    <div class="box-body table-responsive">
                            <table id="ciclosOrden" class="table table-bordered table-striped">
                                <thead>
                                    <tr Class="info" role="row">
                                        <th>Ciclo</th>
                                        <th>Localidad</th>
                                        <th>Cantidad de Lecturistas Asignados</th>
                                    </tr>
                                </thead>
                                  <tbody>
                                    <?php foreach ($ciclosOrden  as $cicloOrden ): ?>
                                        <tr>
                                            <td><?php echo $cicloOrden['CicCod'];?></td>
                                            <td><?php echo $cicloOrden['CicDes'];?></td>
                                            <?php foreach ($LecCiclos as $LecCiclo):?>
                                                <?php if ($LecCiclo['LecCicCo']==$cicloOrden['CicCod']){?>
                                                    <td><?php echo $LecCiclo['Lecturistas'];?></td>
                                                <?php }?>
                                            <?php endforeach;?>
                                        </tr>
                                    <?php endforeach;?>
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
                                    <tr Class="info" role="row">
                                        <th>Sub Orden</th>
                                        <th>Ciclo</th>
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
                                            <?php if($suborden['LecLtaId']==$lecturista['LtaId']) { ?>
                                                    <tr>
                                                        <td><?php echo $i+1; ?></td>
                                                        <td><?php echo $suborden['LecCicCo']; ?></td>
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
                </div>
                    <?php } else {?>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Ciclos de la Orden de Trabajo de Toma de Estado de suministros</h3>              
                    </div>
                    <div class="box-body">
                        <div class="row">         
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="multi[]" class="form-control select2" multiple="multiple" data-placeholder="Seleccione el Ciclo Comercial" style="width: 100%;">
                                        <?php foreach ($ciclos as $ciclo) { ?>
                                            <option value="<?php echo $ciclo['CicCod'] ?>">
                                                <?php echo $ciclo['CicCod'] ?> <?php echo $ciclo['CicDes'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button name="enviar" type="submit" class="btn btn-info btn-flat" type="button"><i class="fa fa-plus"></i> Agregar ciclos</button>      
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-md-3">
                                Rendimiento:<input type="text" class="form-control" value="<?php echo $_SESSION['orden']['rendimiento']; ?>" disabled=""/>
                            </div>    
                            <div class="col-md-3">
                                N° Lecturistas asignados:<input type="text" class="form-control" value="<?php echo $_SESSION['orden']['lecturistasAcumulado'] . ' de ' . $_SESSION['orden']['totalLecturistas']; ?>" disabled=""/>
                            </div>
                            <div class="col-md-3">
                                N° Lecturistas disponibles:<input type="text" class="form-control" value="<?php echo $_SESSION['orden']['lecturistasDisponibles']; ?>" disabled=""/>
                            </div> 
                            <div class="col-md-3">
                                Lecturas programadas:<input type="text" class="form-control" value="<?php echo $_SESSION['orden']['lecturasAcumulado']; ?>" disabled=""/>
                            </div> 
                            <!--<div class="col-md-2">
                                Lecturas programadas :<input type="text" class="form-control" value="<?php echo $_SESSION['orden']['lecturasMaximas']; ?>" disabled=""/>
                            </div> -->
                        </div><br>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="ciclos" class="table table-bordered table-striped">
                                    <thead>        
                                        <tr Class="info" >
                                            <th>Ciclo</th>
                                            <th>Localidad</th>
                                            <th>N° Suministros totales</th>
                                            <th>N° Suministros seleccionados</th>
                                            <th>N° Lecturistas</th>
                                            <th>Asignación</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>        
                                    <tbody>
                                        <?php
                                        foreach ($_SESSION['orden']['detalle'] as $ciclo) {
                                            ?>
                                            <tr>
                                                <td> <?php echo $ciclo['CicCod'] ?></td>
                                                <td> <?php echo implode(', ', $ciclo['localidades']) ?></td>
                                                <td> <?php echo $ciclo['lecturasMaximas'] ?></td>
                                                <td> <?php echo $ciclo['lecturasRealizar'] ?></td>
                                                <td> <?php echo $ciclo['lecturistasUtilizados'] ?></td>
                                                <td> <?php echo $ciclo['aleatorio']?'Aleatoria':'Manual' ?></td>
                                                <td class=" ">
                                                    <span data-toggle="tooltip" data-placement="bottom" title="Modificar la asignación de Sub Ordenes de Trabajo">
                                                        <a id="detalle" name="detalle" ciclo="<?php echo $ciclo['CicCod']; ?>" class="btn btn-default btn-flat">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    </span>
                                                    <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                                        <button name="eliminar" type="submit" title="Eliminar" class="btn btn-default btn-flat" value="<?php echo $ciclo['CicCod']; ?>"><i class="fa fa-trash-o"></i></button>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                    <?php } ?>
                <div class="row">
                    <div class="col-md-6">
                        <button id="btn_guardar" name="btn_guardar" type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo (!isset($ordenTrabajoEdit)) ? 'Registrar' : 'Actualizar'; ?></button>
                    </div>
                    <div class="col-md-6">
                        <button id="btn_limpiar" name="btn_limpiar" type="submit" class="btn btn-block btn-default btn-flat">Limpiar</button>
                        <!--<a href="<?php echo base_url() ?>toma_estado/orden/limpiar" type="reset" class="btn btn-block btn-default btn-flat">Limpiar</a>-->
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

<!-- sweetAlert. -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/sweetalert/sweetalert2.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url() ?>frontend/plugins/sweetalert/sweetalert2.min.js"></script> 

<script type="text/javascript">
    $(function ()
    {
        $("#detalleCicloSubOrdenes").dataTable();
    });
</script>
<script>
$(document).ready(function ()
{
    <?php if (isset( $_SESSION['orden']['detalle'])) { ?>
        $(document).on("click", "#btn_guardar", function ()
        {
            swal({
                title:"",
                text:"Espere un momento...", 
                imageUrl: "<?php echo base_url() ?>frontend/dist/img/cargando.gif", 
                showConfirmButton:false,      
                allowOutsideClick:false
            });
        });
    <?php } ?>
        $(".select2").select2();
        $('#ciclos').DataTable();

        $('#seleccionar_todos').change(function ()
        {
            if ($(this).is(':checked'))
            {
                $(':checkbox').prop('checked', true);
            } else
            {
                $(':checkbox').prop('checked', false);
            }
        });

        /*  FORMULARIO PRINCIPAL */
        $("#OrtNum").inputmask("dd-mm-yyyy");
        $("#OrtFchEm").inputmask("d-m-y h:s:s");
        $("#OrtFchEm2").inputmask("y-m-d h:s:s");
        $("#OrtFchEj").inputmask("dd-mm-yyyy");
        $("#OrtAnio").inputmask("9999");
        $("#ciclo").inputmask("99");

        $('#OrtFchEj').daterangepicker({
            "singleDatePicker": true,
            "autoApply": false,
            "autoclose": true,
            "locale": {
                "format": "DD-MM-YYYY",
                "separator": " - ",
                "applyLabel": "Aceptar",
                "cancelLabel": "Cancelar",
                "customRangeLabel": "Custom",
                "autoclose": true,
                "daysOfWeek": [
                    "Dom",
                    "Lun",
                    "Mar",
                    "Mie",
                    "Jue",
                    "Vie",
                    "Sab"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Augosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            }
        });

        $('#OrtFchEm').daterangepicker({
            "autoApply": true,
            "timePickerIncrement": 1,
            "singleDatePicker": true,
            "timePicker": true,
            "timePicker12Hour": false,
            "timePicker24Hour": true,
            "timePickerSeconds": true,
            "autoclose": true,
            "format": "DD-MM-YYYY HH:mm:ss",
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
                    "Dom",
                    "Lun",
                    "Mar",
                    "Mie",
                    "Jue",
                    "Vie",
                    "Sab"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Augosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            }
        });

        $(document).on("click", "#detalle", function ()
        {
            var idCiclo = $(this).attr('ciclo');
            window.open("<?php echo base_url() . 'toma_estado/orden/nuevo_c/detalle_ciclo/'; ?>" + idCiclo, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=600, left=600, width=800, height=800");
        });
        $(document).on("click", "#eliminar", function ()
        {
            var idCiclo = $(this).attr('ciclo');
            window.location.replace("<?php echo base_url() . 'toma_estado/orden/nuevo_c?eliminar=' ?>" + idCiclo);
        });
        
        <?php if (isset($_SESSION['orden']['fchEj'])) { ?>;
            $('#OrtFchEj').val("<?php echo $_SESSION['orden']['fchEj']; ?>");
        <?php } ?>;
        <?php if (isset($_SESSION['orden']['fchEm'])) { ?>;
            $('#OrtFchEm').val("<?php echo $_SESSION['orden']['fchEm']; ?>");
        <?php } ?>;
        <?php if (isset($ordenTrabajoEdit)) { ?>;
            $('#OrtFchEj2').datepicker({
                format: "dd-mm-yyyy",
                todayBtn: true,
                language: "es",
                autoclose: true,
                todayHighlight: true
            });
            $('#OrtFchEm2').daterangepicker({
            "autoApply": true,
            "timePickerIncrement": 1, 
            "singleDatePicker": true,
            "timePicker": true,
            "timePicker12Hour": false,
            "timePicker24Hour": true,
            "timePickerSeconds": true,
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
                    "Dom",
                    "Lun",
                    "Mar",
                    "Mie",
                    "Jue",
                    "Vie",
                    "Sab"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Augosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            }
            });
            var OrtFchEj = new Date("<?php echo $ordenTrabajoEdit['OrtFchEj']; ?>");
            OrtFchEj.setDate(OrtFchEj.getDate() + 1);
            $('#OrtFchEj2').datepicker('setDate', OrtFchEj);
        <?php } ?>;
        <?php if(isset($detCron)) { ?>;     
        $('#OrtFchEj2').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: true,
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
        var OrtFchEj = new Date("<?php echo $detCron['DlmFchTm']; ?>");
        OrtFchEj.setDate(OrtFchEj.getDate() + 1);
        $('#OrtFchEj2').datepicker('setDate', OrtFchEj );
    <?php } ?>;
    });
</script> 
