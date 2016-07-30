<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
        <form role="form" method="post">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Datos de Período</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body">
                        <div class="row">   
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <input name="PrdCod" type="text" class="form-control" required=""
                                           value="<?php echo (isset($periodo)) ? $periodo['PrdCod'] : set_value('PrdCod'); ?>" required>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Año:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input id="anio" name="PrdAni" type="text" class="form-control" value="<?php echo (isset($periodo)) ? $periodo['PrdAni']: $anio; ?>" <?php echo (isset($periodo)) ? 'readonly' : ''; ?> required/>
                                        </div>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Orden / Número Mes:</label>
                                    <input name="PrdOrd" type="number" step="1" min="1" max="13" class="form-control" required=""
                                           value="<?php echo (isset($periodo)) ? $periodo['PrdOrd'] : set_value('PrdOrd'); ?>" <?php echo (isset($periodo)) ? 'readonly' : ''; ?> required>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                        </div>
                    <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Inicio:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <?php if(isset($periodo)) { ?>
                                        <input id="PrdFchIn2" name="PrdFchIn2" type="text" class="form-control" value="<?php echo date('d-m-Y', strtotime($periodo['PrdFchIn'])) ?>" required/>
                                        <?php } else { ?>
                                        <input id="PrdFchIn" name="PrdFchIn" type="text" class="form-control" required/>
                                        <?php } ?>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Fin:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <?php if(isset($periodo)) { ?>
                                        <input id="PrdFchFn2" name="PrdFchFn2" type="text" class="form-control" value="<?php echo date('d-m-Y', strtotime($periodo['PrdFchFn'])) ?>" required/>
                                        <?php } else { ?>
                                        <input id="PrdFchFn" name="PrdFchFn" type="text" class="form-control" required/>
                                        <?php } ?>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>
                        </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo ($accion == 'nuevo') ? 'Registrar' : 'Actualizar'; ?></button>
                </div>
                <div class="col-md-6">
                    <button type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
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
<!-- date-range-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- date-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker3.min.css">
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script>
$(document).ready(function()
{
    $("#PrdFchIn").inputmask("dd-mm-yyyy");
    $("#PrdFchFn").inputmask("dd-mm-yyyy");
    $("#PrdFchIn2").inputmask("dd-mm-yyyy");
    $("#PrdFchFn2").inputmask("dd-mm-yyyy");
    $("#anio").inputmask("9999");
    
    <?php if(isset($periodo)) { ?>
//        $('#PrdFchIn2').datepicker({
//            format: "dd-mm-yyyy",
//            todayBtn: true,
//            language: "es",
//            autoclose: true,
//            todayHighlight: true
//        });
//
//        $('#PrdFchFn2').datepicker({
//            format: "dd-mm-yyyy",
//            todayBtn: true,
//            language: "es",
//            autoclose: true,
//            todayHighlight: true
//        });
        
        var anio = $("#anio").val();
        var anioActual = parseInt(anio);
        var fechaStart = "'"+'<?php echo date('d-m-Y', strtotime($periodo['PrdFchIn'])) ?>'+"'";
        var resta = anioActual-1;
        var suma = anioActual+1;
        var fechaMin = "'"+'01-12-'+resta+"'";
        var fechaMax = "'"+'31-12-'+anioActual+"'";
        var fechaMax2 = "'"+'31-01-'+suma+"'";
        $('#PrdFchIn2').daterangepicker({
            "showDropdowns": true,
            "singleDatePicker": true,
            "autoApply": false,
            "autoclose": true,
            "format": "DD-MM-YYYY",
            "separator": " - ",
            "startDate": fechaStart,
            "minDate": fechaMin,
            "maxDate" : fechaMax,
            "locale": {
                "format": "DD-MM-YYYY",
                "separator": " - ",
                "autoclose": true,
                "daysOfWeek": [
                    "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
                ],
                "monthNames": [
                    "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                    "Agosto","Septiembre","Octubre","Noviembre","Diciembre"
                ],
                "firstDay": 1
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            var fechaPrdFchIn = picker.startDate.format('DD-MM-YYYY');
            var ini = moment(fechaPrdFchIn, "DD-MM-YYYY");
            var fechaMin = moment(ini, "DD-MM-YYYY").add( 1 , 'days');
            var fechaStart2 = "'"+'<?php echo date('d-m-Y', strtotime($periodo['PrdFchFn'])) ?>'+"'";
            $('#PrdFchFn2').val('');
            $('#PrdFchFn2').daterangepicker({
            "showDropdowns": true,
            "singleDatePicker": true,
            "autoApply": false,
            "autoclose": true,
            "format": "DD-MM-YYYY",
            "separator": " - ",
            "startDate": fechaStart2,
            "minDate": fechaMin,
            "maxDate" : fechaMax2,
            "locale": {
                "format": "DD-MM-YYYY",
                "separator": " - ",
                "autoclose": true,
                "daysOfWeek": [
                    "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
                ],
                "monthNames": [
                    "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                    "Agosto","Septiembre","Octubre","Noviembre","Diciembre"
                ],
                "firstDay": 1
            }
            });
        });

        $("#anio").change(function() 
        { 
            var anio = $("#anio").val();
            $('#PrdFchIn2').val('');
            $('#PrdFchFn2').val('');
            var anioActual = parseInt(anio);
            var fechaStart = "'"+'<?php echo date('d-m-Y', strtotime($periodo['PrdFchIn'])) ?>'+"'";
            var resta = anioActual-1;
            var suma = anioActual+1;
            var fechaMin = "'"+'01-12-'+resta+"'";
            var fechaMax = "'"+'31-12-'+anioActual+"'";
            var fechaMax2 = "'"+'31-01-'+suma+"'";
            $('#PrdFchIn2').daterangepicker({
                "showDropdowns": true,
                "singleDatePicker": true,
                "autoApply": false,
                "autoclose": true,
                "format": "DD-MM-YYYY",
                "separator": " - ",
                "startDate": fechaStart,
                "minDate": fechaMin,
                "maxDate" : fechaMax,
                "locale": {
                    "format": "DD-MM-YYYY",
                    "separator": " - ",
                    "autoclose": true,
                    "daysOfWeek": [
                        "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
                    ],
                    "monthNames": [
                        "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                        "Agosto","Septiembre","Octubre","Noviembre","Diciembre"
                    ],
                    "firstDay": 1
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                var fechaPrdFchIn = picker.startDate.format('DD-MM-YYYY');
                var ini = moment(fechaPrdFchIn, "DD-MM-YYYY");
                var fechaMin = moment(ini, "DD-MM-YYYY").add( 1 , 'days');
                var fechaStart2 = "'"+'<?php echo date('d-m-Y', strtotime($periodo['PrdFchFn'])) ?>'+"'";
                $('#PrdFchFn2').val('');
                $('#PrdFchFn2').daterangepicker({
                    "showDropdowns": true,
                    "singleDatePicker": true,
                    "autoApply": false,
                    "autoclose": true,
                    "format": "DD-MM-YYYY",
                    "separator": " - ",
                    "startDate": fechaStart2,
                    "minDate": fechaMin,
                    "maxDate" : fechaMax2,
                    "locale": {
                        "format": "DD-MM-YYYY",
                        "separator": " - ",
                        "autoclose": true,
                        "daysOfWeek": [
                            "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
                        ],
                        "monthNames": [
                            "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                            "Agosto","Septiembre","Octubre","Noviembre","Diciembre"
                        ],
                        "firstDay": 1
                    }
                });
            });
        });

//        var PrdFchIn = new Date("<?php echo $periodo['PrdFchIn']; ?>"); 
//        var PrdFchFn = new Date("<?php echo $periodo['PrdFchFn']; ?>"); 
//        
//        PrdFchIn.setDate(PrdFchIn.getDate() + 1);
//        PrdFchFn.setDate(PrdFchFn.getDate() + 1);
//        $('#PrdFchIn2').datepicker('setDate', PrdFchIn );
//        $('#PrdFchFn2').datepicker('setDate', PrdFchFn );
        
    <?php }else { ?>
        var anio = $("#anio").val();
        var anioActual = parseInt(anio);
        var fechaStart = "'"+'01-01-'+anioActual+"'";
        var resta = anioActual-1;
        var suma = anioActual+1;
        var fechaMin = "'"+'01-12-'+resta+"'";
        var fechaMax = "'"+'31-12-'+anioActual+"'";
        var fechaMax2 = "'"+'31-01-'+suma+"'";
        $('#PrdFchIn').daterangepicker({
            "showDropdowns": true,
            "singleDatePicker": true,
            "autoApply": false,
            "autoclose": true,
            "format": "DD-MM-YYYY",
            "separator": " - ",
            "startDate": fechaStart,
            "minDate": fechaMin,
            "maxDate" : fechaMax,
            "locale": {
                "format": "DD-MM-YYYY",
                "separator": " - ",
                "autoclose": true,
                "daysOfWeek": [
                    "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
                ],
                "monthNames": [
                    "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                    "Agosto","Septiembre","Octubre","Noviembre","Diciembre"
                ],
                "firstDay": 1
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            var fechaPrdFchIn = picker.startDate.format('DD-MM-YYYY');
            var ini = moment(fechaPrdFchIn, "DD-MM-YYYY");
            var fechaMin = moment(ini, "DD-MM-YYYY").add( 1 , 'days');
            $('#PrdFchFn').val('');
            $('#PrdFchFn').daterangepicker({
            "showDropdowns": true,
            "singleDatePicker": true,
            "autoApply": false,
            "autoclose": true,
            "format": "DD-MM-YYYY",
            "separator": " - ",
            "startDate": fechaMin,
            "minDate": fechaMin,
            "maxDate" : fechaMax2,
            "locale": {
                "format": "DD-MM-YYYY",
                "separator": " - ",
                "autoclose": true,
                "daysOfWeek": [
                    "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
                ],
                "monthNames": [
                    "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                    "Agosto","Septiembre","Octubre","Noviembre","Diciembre"
                ],
                "firstDay": 1
            }
            });
        });

        $("#anio").change(function() 
        { 
            var anio = $("#anio").val();
            $('#PrdFchIn').val('');
            $('#PrdFchFn').val('');
            var anioActual = parseInt(anio);
            var fechaStart = "'"+'01-01-'+anioActual+"'";
            var resta = anioActual-1;
            var suma = anioActual+1;
            var fechaMin = "'"+'01-12-'+resta+"'";
            var fechaMax = "'"+'31-12-'+anioActual+"'";
            var fechaMax2 = "'"+'31-01-'+suma+"'";
            $('#PrdFchIn').daterangepicker({
                "showDropdowns": true,
                "singleDatePicker": true,
                "autoApply": false,
                "autoclose": true,
                "format": "DD-MM-YYYY",
                "separator": " - ",
                "startDate": fechaStart,
                "minDate": fechaMin,
                "maxDate" : fechaMax,
                "locale": {
                    "format": "DD-MM-YYYY",
                    "separator": " - ",
                    "autoclose": true,
                    "daysOfWeek": [
                        "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
                    ],
                    "monthNames": [
                        "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                        "Agosto","Septiembre","Octubre","Noviembre","Diciembre"
                    ],
                    "firstDay": 1
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                var fechaPrdFchIn = picker.startDate.format('DD-MM-YYYY');
                var ini = moment(fechaPrdFchIn, "DD-MM-YYYY");
                var fechaMin = moment(ini, "DD-MM-YYYY").add( 1 , 'days');
                $('#PrdFchFn').val('');
                $('#PrdFchFn').daterangepicker({
                    "showDropdowns": true,
                    "singleDatePicker": true,
                    "autoApply": false,
                    "autoclose": true,
                    "format": "DD-MM-YYYY",
                    "separator": " - ",
                    "startDate": fechaMin,
                    "minDate": fechaMin,
                    "maxDate" : fechaMax2,
                    "locale": {
                        "format": "DD-MM-YYYY",
                        "separator": " - ",
                        "autoclose": true,
                        "daysOfWeek": [
                            "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
                        ],
                        "monthNames": [
                            "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                            "Agosto","Septiembre","Octubre","Noviembre","Diciembre"
                        ],
                        "firstDay": 1
                    }
                });
            });
        });
    <?php } ?>
});
</script> 