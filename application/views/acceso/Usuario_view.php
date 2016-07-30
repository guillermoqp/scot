<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <form role="form" method="post" enctype="multipart/form-data">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Datos Personales</h3>                                   
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Apellido Paterno:</label>
                                    <input name="apellidoPat" value="<?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrApePt'] : set_value('apellidoPat'); ?>" type="text" class="form-control" required="">
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Apellido Materno:</label>
                                    <input name="apellidoMat" value="<?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrApeMt'] : set_value('apellidoMat'); ?>" type="text" class="form-control" required="">
                                    <p class="help-block"></p>
                                </div>
                            </div>        
                        </div>
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Nombres:</label>
                                    <input name="nombres" value="<?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrNomPr'] : ''; ?>" type="text" class="form-control" required="">
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>DNI:</label>
                                        <div class="controls">
                                            <input name="dni" value="<?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrDni'] : ''; ?>" type="text" class="form-control" id="dni" data-inputmask='"mask": "99999999"' data-mask/>
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                </div>    
                            </div>       
                        </div>
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sexo:</label>
                                    <?php if (!isset($usuarioEdit)) { ?>
                                        <div class="form-group">
                                            <div class="control-group">
                                                <input id="sexoF" name="UsrSex" type="radio" class="flat-red" value="f" required>Femenino
                                                <input id="sexoM" name="UsrSex" type="radio" class="flat-red" value="m" required>Masculino
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <h4><?php echo ($usuarioEdit['UsrSex']=='m') ? 'Masculino' : 'Femenino'; ?></h4>
                                    <?php } ?>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Nacimiento:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <?php if (!isset($usuarioEdit)) { ?>
                                            <input id="UsrFchNac" name="UsrFchNac" type="text" class="form-control pull-right" value="<?php set_value('UsrFchNac'); ?>" required>
                                        <?php } else { ?>
                                            <input id="UsrFchNac" name="UsrFchNac" type="text" class="form-control pull-right" value="<?php echo $usuarioEdit['UsrFchNac']; ?>" readonly>
                                        <?php } ?>
                                    </div>       
                                </div>
                            </div>       
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>E-mail:</label>
                                        <div class="controls">
                                            <div class="controls">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-envelope"></i>
                                                    </div>
                                                    <input type="email" name="email" id="email" class="form-control" data-inputmask='alias: "email"' data-mask value="<?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrCor'] : set_value('email'); ?>" required/>
                                                </div>
                                                <p class="help-block"></p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Teléfono:</label>
                                        <div class="controls">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-phone"></i>
                                                </div>
                                                <input type="text" name="telefono" id="telefono" class="form-control" data-inputmask='"mask": "(999) 99-99-99"' data-mask value="<?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrTel'] : set_value('telefono'); ?>" required/>
                                            </div><!-- /.input group -->
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número de Movil 1:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-mobile"></i>
                                        </div>
                                        <input id="UsrMov1" name="UsrMov1" type="text" class="form-control pull-right" value="<?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrMov1'] : set_value('UsrMov1'); ?>">
                                    </div>       
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número de Movil 2:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-mobile"></i>
                                        </div>
                                        <input id="UsrMov2" name="UsrMov2" type="text" class="form-control pull-right" value="<?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrMov2'] : set_value('UsrMov2'); ?>">
                                    </div>       
                                </div>
                            </div>       
                        </div>
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Expiración:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input id="FchEx" name="fchEx" type="text" class="form-control pull-right" value="<?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrFchEx'] : set_value('fchEx'); ?>" required>
                                    </div>       
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label for="exampleInputFile">Foto:</label><br>
                                        <?php if (isset($usuarioEdit)) { ?>
                                            <img src="<?php echo base_url() . $usuarioEdit['UsrFot']; ?>" alt="Foto Usuario" width="100" height="100"/><br><br>
                                        <?php } ?>
                                        <input type="file" name="foto" id="foto" <?php echo (isset($usuarioEdit)) ? '' : 'required'; ?>>   
                                    </div>
                                </div>
                            </div>       
                        </div>
                    </div>
                </div>
                <div class  ="box">
                    <div class="box-header">
                        <h3 class="box-title">Datos de la Cuenta</h3>                                   
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php if (!isset($usuarioEdit)) { ?>
                            <div class="row">   
                                <div class="col-md-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Nombre de usuario:</label>
                                        <input name="nombreUsuario" type="text" maxlength="20" class="form-control" placeholder="dtrevistan..." required="">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="control-group">
                                        <label class="control-label" for="password">Contraseña:</label>
                                        <div class="controls">
                                            <input class="form-control" type="password" name="password" required="" aria-invalid="false">
                                            <p class="help-block">Ingrese su Contraseña...</p>
                                        </div>
                                    </div>       
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="control-group">
                                            <h4><label><b>Usuario: </b><?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrNomCt'] : ''; ?></label></h4>
                                        </div>
                                    </div>
                                </div> 
                            </div> 
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Usuario de:</label>
                                <div class="form-group">
                                    <div class="control-group"> 
                                        <input name="tipo" type="radio" required value="1" <?php echo (isset($usuarioEdit)) ? ((is_null($usuarioEdit['UsrCstId'])) ? 'checked' : '') : set_checkbox('tipo', 1, TRUE) ?>>EPS   
                                        <input name="tipo" type="radio" required value="0" <?php echo (isset($usuarioEdit)) ? ((is_null($usuarioEdit['UsrCstId'])) ? '' : 'checked') : set_checkbox('tipo', 0) ?>>Contratista    
                                    </div>
                                </div>  
                            </div>
                            <div id="contratante" class="col-md-6" <?php if (isset($usuarioEdit)) {
                            echo (is_null($usuarioEdit['UsrCstId'])) ? '' : 'style="display: none"';
                        } ?>>
                                <!-- text input -->
                                <div class="form-group">
                                    <label>EPSs:</label>
                                    <select name="idContratante" class="form-control" required="">
                                        <?php
                                        foreach ($contratantes as $contratante) {
                                            if (isset($contratante['CntId'])) {
                                                ?>
                                                <option value="<?php echo $contratante['CntId'] ?>"<?php echo ($contratante == '') ? 'selected' : ''; ?>><?php echo $contratante['EmpRaz'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div id="contratista" class="col-md-6" <?php if (isset($usuarioEdit)) {
                                            echo (is_null($usuarioEdit['UsrCstId'])) ? 'style="display: none"' : '';
                                        } else {
                                            echo 'style="display: none"';
                                        } ?>>
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Contratista:</label>
                                    <select name="idContratista" class="form-control" required="">
                                        <?php
                                        foreach ($contratistas as $contratista) {
                                            if (isset($contratista['CstId'])) {
                                                ?>
                                                <option value="<?php echo $contratista['CstId'] ?>"<?php echo ($contratista == '') ? 'selected' : ''; ?>><?php echo $contratista['CstRaz'] ?></option>
                                        <?php } }?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Cargo:</label>
                                    <select id="cargo" name="cargo" class="form-control" required="">
                                        <?php
                                        foreach ($cargos as $cargo) {
                                            if (isset($cargo['CarId'])) {
                                                ?>
                                                <option value="<?php echo $cargo['CarId'] ?>"<?php if (isset($usuarioEdit)) {
                                            echo ($usuarioEdit['CarId'] == $cargo['CarId']) ? 'selected' : '';
                                        } ?>><?php echo $cargo['CarDes'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Seleccione Perfil:</label>
                                        <div class="form-group">
                                            <div id="roles" class="checkbox nivel2">
                                                <?php foreach ($roles as $rol): ?>
                                                    <label>
                                                        <input name="roles[]" type="checkbox" value="<?php echo $rol['RolId'] ?>" <?php
                                                    if (isset($rolesId)) {
                                                        echo (in_array($rol['RolId'], $rolesId)) ? 'checked' : '';
                                                    }
                                                    ?>>
                                                    <?php echo $rol['RolDes'] ?>
                                                    </label><br>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo (!isset($usuarioEdit)) ? 'Registrar' : 'Actualizar'; ?></button>
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
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/iCheck/all.css">
<script src="<?php echo base_url() ?>frontend/plugins/iCheck/icheck.min.js"></script>

<!-- VALIDACION DE FORMUALRIOS  -->
<script src="<?php echo base_url() ?>frontend/plugins/validation/jqBootstrapValidation.js"></script>
<script src="<?php echo base_url() ?>frontend/plugins/validation/validate.js"></script>
<!-- date-range-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- date-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>



<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#dni").inputmask("99999999");
        $("#telefono").inputmask("(999) 99-99-99");
        $("#UsrMov1").inputmask("999999999");
        $("#UsrMov2").inputmask("999999999");
        
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });
        $("#sexoM").iCheck('check');
    
        $("input,textarea,select").jqBootstrapValidation
        ({
            preventSubmit: true,
            submitError: function ($form, event, errors)
            {
                alert("Porfavor, corrija los errores.");
            },
            filter: function ()
            {
                return $(this).is(":visible");
            }
        });
        $('input[name=tipo]:radio').change(function ()
        {
            var tipo = $('input[name=tipo]:checked').val();
            if (tipo == 1) {
                $("#contratista").hide();
                $("#contratante").show();
            } else {
                $("#contratista").show();
                $("#contratante").hide();
            }
            ;
            $("#cargo > option").remove();
            var tipo = $('input[name=tipo]:checked').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . 'permisos/usuario/obtener_cargos/' ?>" + tipo,
                success: function (cargos)
                {
                    $.each(cargos, function (clave, valor)
                    {
                        var opt = $('<option />');
                        opt.val(valor.CarId);
                        opt.text(valor.CarDes);
                        $('#cargo').append(opt);
                    });
                }

            });
        });
    });
</script>
<script type="text/javascript">
$(document).ready(function ()
{
<?php if (isset($usuarioEdit)) { ?>;
        $('#FchEx').datepicker({
            format: "dd-mm-yyyy",
            separator: ' - ',
            todayBtn: true,
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
        var FchEx = new Date("<?php echo $usuarioEdit['UsrFchEx']; ?>");
        FchEx.setDate(FchEx.getDate() + 1);
        $('#FchEx').datepicker('setDate', FchEx);
<?php } else { ?>
   
    var fechaServ = "'" + '<?php echo date('d-m-Y') ?>' + "'";
    var ini = moment(fechaServ, "DD-MM-YYYY");
    var fchServ = moment(ini, "DD-MM-YYYY").add(1, 'days');
    var start = new Date();
    var end = new Date(new Date().setYear(start.getFullYear() + 1));
    
    
    $('#FchEx').daterangepicker({
        "showDropdowns": true,
        "singleDatePicker": true,
        "autoApply": false,
        "autoclose": true,
        "format": "DD-MM-YYYY",
        "separator": " - ",
        "startDate": fchServ,
        "minDate": fchServ,
        "endDate": end,
        "locale": {
            "format": "DD-MM-YYYY",
            separator: ' - ',
            autoclose: true,
            daysOfWeek: [
                'Dom','Lun','Mar','Mie','Jue','Vie','Sab'
                ],
            monthNames: [
                'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio',
                'Agosto','Septiembre','Octubre','Noviembre','Diciembre'
                ],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekHeader: 'Sm',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        }
    });
  
    $('#UsrFchNac').daterangepicker({
        "showDropdowns": true,
        "singleDatePicker": true,
        "autoApply": false,
        "autoclose": true,
        "format": "DD-MM-YYYY",
        "separator": " - ",
        "startDate": moment(),
        "minDate": '01-01-1940',
        "endDate": end,
        "locale": {
            "format": "DD-MM-YYYY",
            separator: ' - ',
            autoclose: true,
            daysOfWeek: [
                'Dom','Lun','Mar','Mie','Jue','Vie','Sab'
                ],
            monthNames: [
                'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio',
                'Agosto','Septiembre','Octubre','Noviembre','Diciembre'
                ],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekHeader: 'Sm',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        }
    });
<?php } ?>
});
</script>