<!-- sweetAlert. -->
<link rel="stylesheet" href="<?php echo base_url()?>frontend/plugins/sweetalert/sweetalert2.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url()?>frontend/plugins/sweetalert/sweetalert2.min.js"></script> 
 
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
            <!--<div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $_SESSION['mensaje'][1]; ?>
            </div>-->
            <script>
                    swal("Mensaje", "<?php echo $_SESSION['mensaje'][1]; ?>" , "<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'warning' : 'success'; ?>"); 
            </script>
            <?php } ?>
            <form role="form" method="post" enctype="multipart/form-data">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Datos Personales</h3>                                   
                    </div><!-- /.box-header -->
                    <div class="box-body">
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
                                    <div class="control-group">
                                        <label for="exampleInputFile">Foto:</label><br>
                                        <?php if (isset($usuarioEdit)) { ?>
                                            <img src="<?php echo base_url() . $usuarioEdit['UsrFot']; ?>" alt="Foto Usuario" width="100" height="100"/><br><br>
                                        <?php } ?>
                                        <input type="file" name="foto" id="foto" <?php echo (isset($usuarioEdit)) ? '' : 'required'; ?>>   
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Movil 1:</label>
                                        <div class="controls">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-mobile-phone"></i>
                                                </div>
                                                <input type="text" name="UsrMov1" id="UsrMov1" class="form-control" value="<?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrMov1'] : set_value('UsrMov1'); ?>"/>
                                            </div><!-- /.input group -->
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Movil 2:</label>
                                        <div class="controls">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-mobile-phone"></i>
                                                </div>
                                                <input type="text" name="UsrMov2" id="UsrMov2" class="form-control" value="<?php echo (isset($usuarioEdit)) ? $usuarioEdit['UsrMov2'] : set_value('UsrMov2'); ?>"/>
                                            </div><!-- /.input group -->
                                            <p class="help-block"></p>
                                        </div>
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
                            <div class="row">   
                                <div class="col-md-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Contraseña Anterior:</label>
                                        <input name="password" type="password" class="form-control" aria-invalid="false">
                                        <p class="help-block">Ingrese Contraseña Anterior...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="control-group">
                                        <label class="control-label" for="password">Contraseña Nueva:</label>
                                        <div class="controls">
                                            <input class="form-control" type="password" name="nueva_contrasenia" aria-invalid="false">
                                            <p class="help-block">Ingrese Nueva Contraseña...</p>
                                        </div>
                                    </div>       
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="control-group">
                                        <label class="control-label" for="password">Escriba otra vez contraseña nueva:</label>
                                        <div class="controls">
                                            <input class="form-control" type="password" data-validation-match-match="nueva_contrasenia" name="nueva_contrasenia2" data-validation-match-message="Contraseñas no coinciden" aria-invalid="false">
                                            <p class="help-block">Confirme Nueva Contraseña...</p>
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

<!-- VALIDACION DE FORMUALRIOS  -->
<script src="<?php echo base_url() ?>frontend/plugins/validation/jqBootstrapValidation.js"></script>
<!-- date-range-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- date-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>



<script type="text/javascript">
    $(document).ready(function () {
        $("#dni").inputmask("99999999");
        $("#telefono").inputmask("(999) 99-99-99");
        $("#UsrMov1").inputmask("999999999");
        $("#UsrMov2").inputmask("999999999");
        
        $("input,textarea,select").jqBootstrapValidation
                ({
                    preventSubmit: true,
                    submitError: function ($form, event, errors)
                    {
                        swal("¡Error!", "Porfavor, corrija los errores." , "error"); 
                    },
                    filter: function () {
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
            $("#roles").html("");
            var tipo = $('input[name=tipo]:checked').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . 'permisos/usuario/obtener_roles/' ?>" + tipo,
                success: function (roles)
                {
                    $.each(roles, function (clave, valor)
                    {
                        $('#roles').append('<label> <input name="roles[]" type="checkbox" value="' + valor.RolId + '">' + valor.RolDes + '</label><br>');
                    });
                }

            });
        });
    });
    $(function () {
        
        $('#FchEx').datepicker({
            todayBtn: true,
            language: 'es',
            autoclose: true,
            singleDatePicker: true,
            autoApply: true,//false
            format: 'dd-mm-yyyy',
            prevText: 'Ant',
            nextText: 'Sig',
            currentText: 'Hoy',
            todayHighlight: true,
            "locale": {
                format: 'dd-mm-yyyy',
                separator: ' - ',
                autoclose: true,
                daysOfWeek: [
                    'Dom',
                    'Lun',
                    'Mar',
                    'Mie',
                    'Jue',
                    'Vie',
                    'Sab'
                ],
                monthNames: [
                    'Enero',
                    'Febrero',
                    'Marzo',
                    'Abril',
                    'Mayo',
                    'Junio',
                    'Julio',
                    'Augosto',
                    'Septiembre',
                    'Octubre',
                    'Noviembre',
                    'Diciembre'
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
    });
    <?php if(isset($usuarioEdit)) { ?>;   
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
        $('#FchEx').datepicker('setDate', FchEx );
    <?php } ?>
</script>