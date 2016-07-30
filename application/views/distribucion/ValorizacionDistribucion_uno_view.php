<section class="content">
   <div class="row">
        <div class="col-xs-12">
              <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
        <form role="form" accept-charset="utf-8" method="post">
            <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Datos de la Valorización: Distribución de Recibos y Comunicaciones</h3>              
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        
                        <div class="row">   
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Valorización:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-barcode"></i>
                                            </div>
                                            <input id="numero" name="numero" type="text" class="form-control" value="<?php echo (isset($valorizacion)) ? $valorizacion['dato'] : $nroValorizacion; ?>" required readonly=""/>
                                        </div>
                                        <p class="help-block"></p>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Año:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input id="anio" name="anio" type="text" class="form-control" value="<?php echo (isset($valorizacion)) ? $valorizacion['dato']: (isset($anio)?$anio:set_value('anio')); ?>" required/>
                                        </div>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Periodo:</label>
                                        <div class="input-group">
                                            <select id="mes" name="mes" class="form-control">
                                                <?php echo isset($mes)?'<option value="'.$mes.'" selected>'.$mes.'</option>':'' ?>
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
                                        </div>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Inicio:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input id="PrlFchIn" name="PrlFchIn" type="text" class="form-control" required/>
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
                                        <input id="PrlFchFn" name="PrlFchFn" type="text" class="form-control" required/>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>
                        </div>
            <div class="row">
                <div class="col-md-6">
                    <button name="btn_valorizacion_guardar" type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo (!isset($ordenTrabajoEdit))? 'Registrar' : 'Actualizar' ;?></button>
                </div>
                <div class="col-md-6">
                    <button type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
                </div>
            </div>           
            </div><!-- /.box -->
        </div>
    </form>
    </div>
</section>

<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- InputMask -->
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<!-- VALIDACION DE FORMUALRIOS  -->
<script src="<?php echo base_url() ?>frontend/plugins/validation/jqBootstrapValidation.js"></script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- date-range-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- date-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker3.min.css">
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<!--<script>
    function suma_y_muestra() {
        var $meses = array(0 => "Diciembre", 1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8=> "Agosto", 9=> "Septiembre", 10=>"Octubre", 11=>"Noviembre", 12=>"Diciembre", 13=>"Enero");
      }
</script>-->
<script>
$(document).ready(function()
{
    $("#PrlFchIn").inputmask("dd-mm-yyyy");
    $("#PrlFchFn").inputmask("dd-mm-yyyy");
    $("#anio").inputmask("9999");
    
    <?php if(isset($ordenTrabajoEdit)) { ?>    
        $('#OrtFchEj2').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: true,
            language: "es",
            autoclose: true,
            todayHighlight: true
        });

        $('#OrtFchEm2').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: true,
            language: "es",
            autoclose: true,
            todayHighlight: true
        });

        var OrtFchEj = new Date("<?php echo $ordenTrabajoEdit['OrtFchEj']; ?>"); 
        var OrtFchEm = new Date("<?php echo $ordenTrabajoEdit['OrtFchEm']; ?>"); 
        
        OrtFchEj.setDate(OrtFchEj.getDate() + 1);
        OrtFchEm.setDate(OrtFchEm.getDate() + 1);
        $('#OrtFchEj2').datepicker('setDate', OrtFchEj );
        $('#OrtFchEm2').datepicker('setDate', OrtFchEm );
        
    <?php }else { ?>
        var anio = $("#anio").val();
        var mes = $("#mes").val();
        var anioActual = parseInt(anio);
        var fechaStart = "'"+'01-01-'+anioActual+"'";
        var resta = anioActual-1;
        var suma = anioActual+1;
        var fechaMin = "'"+'01-12-'+resta+"'";
        var fechaMax = "'"+'31-12-'+anioActual+"'";
        var fechaMax2 = "'"+'31-01-'+suma+"'";
        $('#PrlFchIn').daterangepicker({
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
            var fechaPrlFchIn = picker.startDate.format('DD-MM-YYYY');
            var ini = moment(fechaPrlFchIn, "DD-MM-YYYY");
            var fechaMin = moment(ini, "DD-MM-YYYY").add( 1 , 'days');
            $('#PrlFchFn').val('');
            $('#PrlFchFn').daterangepicker({
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
            $('#PrlFchIn').val('');
            $('#PrlFchFn').val('');
            var anioActual = parseInt(anio);
            var fechaStart = "'"+'01-01-'+anioActual+"'";
            var resta = anioActual-1;
            var suma = anioActual+1;
            var fechaMin = "'"+'01-12-'+resta+"'";
            var fechaMax = "'"+'31-12-'+anioActual+"'";
            var fechaMax2 = "'"+'31-01-'+suma+"'";
            $('#PrlFchIn').daterangepicker({
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
                var fechaPrlFchIn = picker.startDate.format('DD-MM-YYYY');
                var ini = moment(fechaPrlFchIn, "DD-MM-YYYY");
                var fechaMin = moment(ini, "DD-MM-YYYY").add( 1 , 'days');
                $('#PrlFchFn').val('');
                $('#PrlFchFn').daterangepicker({
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