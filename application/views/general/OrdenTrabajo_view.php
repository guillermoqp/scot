<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
       <?php echo form_error('archivos[]'); ?>
        <form role="form" accept-charset="utf-8" enctype="multipart/form-data" method="post" action="<?php echo str_replace('/index.php', '', current_url())."/guardar" ; ?>">
            <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Datos de Orden de Trabajo de <?php echo $proceso; ?></h3>              
                    </div><!-- /.box-header -->
                    <?php 
                    if(isset($ordenTrabajoEdit))
                    {
                        $campoDes = $ordenTrabajoEdit['OrtDes'];
                        $porciones = explode("<br>", $campoDes);
                        $entregadoA = $porciones[0];
                        $descripcion = $porciones[1];
                        $mes = $porciones[2];
                        $anio = $porciones[3];
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
                                                <?php if (isset($ordenTrabajoEdit)) { ?>
                                                <input id="OrtNum" name="OrtNum" type="text" class="form-control" value="<?php echo $ordenTrabajoEdit['OrtNum']; ?>" data-mask required readonly=""/>
                                                <?php } else {?>
                                                <input id="OrtNum" name="OrtNum" type="text" class="form-control" value="<?php echo set_value('OrtNum'); ?>" data-mask required/>
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
                                            <input id="OrtEntr" name="OrtEntr" type="text" class="form-control" value="<?php echo (isset($ordenTrabajoEdit)) ? $entregadoA : $entregables; ?>" readonly="" required/>
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
                                        <textarea id="OrtDes" name="OrtDes" class="form-control" rows="3" required><?php echo (isset($ordenTrabajoEdit)) ? $descripcion : set_value('OrtDes'); ?></textarea>
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
                                                <select id="OrtAnio" name="OrtAnio" class="form-control" onchange="enviarForm('anio')" <?php echo isset($_SESSION['orden']['bloqueado']) ? 'readonly' : '' ?>>
                                                    <?php foreach ($anios as $anioitem) { ?>
                                                        <option value="<?php echo $anioitem ?>"<?php echo (date('Y') == $anioitem) ? 'selected' : ''; ?>><?php echo $anioitem ?></option>
                                                    <?php } ?>
                                                </select>
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
                                            <?php if(isset($ordenTrabajoEdit)){ ?>
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
                                            <?php } else{?>
                                            <select id="OrtMes" name="OrtMes" class="form-control" required <?php echo isset($_SESSION['orden']['bloqueado'])?'readonly':'' ?>>
                                                    <option value="<?php echo $Mes; ?>" selected><?php echo $Mes; ?></option>
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
                                        <?php } else { ?>
                                            <input id="OrtFchEj" name="OrtFchEj" type="text" class="form-control" value="<?php echo $fchEj?>" data-toggle="tooltip" title="Dato necesario para ingresar Fecha y hora Máxima de Recepción" required <?php echo isset($_SESSION['orden']['bloqueado'])?'readonly':'' ?>/>
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
                                            <input id="OrtFchEm" name="OrtFchEm" type="text" class="form-control" data-toggle="tooltip" title="Para Fecha y hora Máxima de Recepción es necesaria ingresar Fecha de Ejecución"/>
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
                                        <textarea id="OrtObs" name="OrtObs" class="form-control" rows="3" ><?php echo (isset($ordenTrabajoEdit)) ? $ordenTrabajoEdit['OrtObs'] : set_value('OrtObs'); ?></textarea>
                                    </div>       
                                </div>
                            </div> 
                        </div>
                    </div>
            </div><!-- /.box -->
            <!--Ordenes con Archivos -->
            <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Archivos de Orden de Trabajo de <?php echo $proceso; ?></h3>              
                    </div>
                    <?php
                    if(!empty($archivosEnv))
                    { ?>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Archivo</th>
                                <th>Fecha de Subida</th>
                                <th></th>
                            </tr>
                        </thead>
                          <tbody>
                          <?php foreach ($archivosEnv  as $archivoEnv ): ?>
                          <tr>
                            <td class="mailbox-name"><?php echo $archivoEnv['ArcNomOr']; ?></td>
                            <td class="mailbox-date"><?php echo date('d-m-Y H:i:s',  strtotime($archivoEnv['ArcFchSu']))?></td>
                            <td>
                                <a href="<?php echo base_url().str_replace("%2F", "/", $archivoEnv['ArcRutUr']); ?>" download="<?php echo $archivoEnv['ArcNomOr']; ?>">
                                    <i class="fa fa-fw fa-download"></i>
                                </a>
                                <a href="#modal-eliminar" role="button" data-toggle="modal" archivoId="<?php echo $archivoEnv['ArcId']; ?>">
                                    <i class="fa fa-fw fa-trash-o"></i>
                                </a>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                          </tbody>
                        </table>
                        <br>
                        <h4>Subir nuevos archivos de Orden de Trabajo de <?php echo $proceso; ?></h4>  
                        <fieldset>
                            <section>
                                <div class="row invoice-info">
                                    <div class="col-md-6 invoice-col">
                                        <input type="file" name="upload_file1" id="upload_file1" readonly="true" required/>
                                    </div>
                                    <div class="col-md-6 invoice-col">
                                        <div id="moreImageUploadLink" style="display:none;margin-left: 10px;">
                                            <a href="javascript:void(0);" id="attachMore" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> Añadir otro Archivo</a>
                                        </div>
                                    </div>
                                </div><br>
                                <div style="clear:both;"></div>
                                <div id="moreImageUpload" style="margin-bottom: 20px;"></div>
                            </section>
                        </fieldset>
                    </div>
                   <?php }else { ?>
                     <div class="box-body">
                        <fieldset>
                            <section>
                                <div class="row invoice-info">
                                    <div class="col-md-6 invoice-col">
                                        <input type="file" name="upload_file1" id="upload_file1" readonly="true" required/>
                                    </div>
                                    <div class="col-md-6 invoice-col">
                                        <div id="moreImageUploadLink" style="display:none;margin-left: 10px;">
                                            <a href="javascript:void(0);" id="attachMore" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> Añadir otro Archivo</a>
                                        </div>
                                    </div>
                                </div><br>
                                <div style="clear:both;"></div>
                                <div id="moreImageUpload" style="margin-bottom: 20px;"></div>
                            </section>
                        </fieldset>
                    </div>
                   <?php } ?>
            </div>
            <?php if (!isset($ordenTrabajoEdit)) { ?>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Metrado por Subactividades de la Orden de Trabajo de <?php echo $proceso; ?></h3>              
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Sub Actividad</th>
                                <th>Metrado</th>
                            </tr>
                        </thead>
                          <tbody>
                          <?php foreach ($subactividades  as $subactividad ): ?>
                          <tr>
                            <td>
                                <?php echo $subactividad['SacId']; ?> - 
                                <?php echo $subactividad['SacDes']; ?>    
                            </td>
                            <td id="<?php echo $subactividad['SacId']; ?>">
                                <input id="<?php echo 'val_' . $subactividad['SacId']; ?>" 
                                       name="<?php echo 'val_' . $subactividad['SacId']; ?>"
                                       type="number" max="1500000" min="100" step="1">
                            </td>
                            <p class="help-block"></p>
                            </div>
                          </tr>
                          <?php endforeach; ?>
                          </tbody>
                        </table>
                        <br>
                    </div>
                </div>
            <?php } else { ?>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Metrado por Subactividades de la Orden de Trabajo de <?php echo $proceso; ?></h3>              
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Sub Actividad</th>
                                <th>Metrado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($detallesOrden as $detalleOrden): ?>
                            <tr id="<?php echo $detalleOrden['SacId']; ?>">
                                <td id="<?php echo $detalleOrden['SacId']; ?>">
                                    <?php echo $detalleOrden['SacId']; ?> - 
                                    <?php echo $detalleOrden['SacDes']; ?>
                                </td>
                                <td id="<?php echo $detalleOrden['SacId']; ?>">
                                    <input id="<?php echo 'val_' . $detalleOrden['SacId']; ?>" 
                                           name="<?php echo 'val_' . $detalleOrden['SacId']; ?>" 
                                           value="<?php echo $detalleOrden['OtsMetPl']; ?>" 
                                           type="number" max="1500000" min="100" step="1">
                                </td>
                            </tr>    
                        <?php endforeach; ?>
                        </tbody>
                        </table>
                        <br>
                    </div>
                </div>
            <?php } ?> 
            <div class="row">
                <div class="col-md-6">
                    <button name="file_upload" type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo (!isset($ordenTrabajoEdit))? 'Registrar' : 'Actualizar' ;?></button>
                </div>
                <div class="col-md-6">
                    <button type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
                </div>
            </div>
        </form>
        <?php echo form_close();?>    
        </div>
    </div>
</section>


<!-- MODAL :   Eliminar Archivo de OT -->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim" method="post">    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Eliminar Archivo de la Orden de Trabajo</h4>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: center">¿Realmente deseas eliminar este archivo y los datos asociados con él?</h5>
                </div>     
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->


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
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    <?php if (isset($ordenTrabajoEdit)) { ?>
        $(document).on('click', 'a', function (event)
        {
            var archivoId = $(this).attr('archivoId');
            $('#idelim').attr('action', "<?php echo base_url() . $padre. '/' .$hijo. '/' .$ordenTrabajoEdit['OrtId'].'/archivo/'; ?>" + archivoId + "/eliminar");
        });
    <?php } ?>
    });
</script>


<script type="text/javascript">
$(document).ready(function() 
{
        $("input[id^='upload_file']").each(function() {
            var id = parseInt(this.id.replace("upload_file", ""));
            $("#upload_file" + id).change(function() {
                if ($("#upload_file" + id).val() !== "") {
                    $("#moreImageUploadLink").show();
                }
            });
        });
});
</script>
<script type="text/javascript">
$(document).ready(function() 
{
        var upload_number = 2;
        $('#attachMore').click(function() {
            //añadir mas archivos
            var moreUploadTag = '<div class="row invoice-info">';
            moreUploadTag += '<div class="col-md-6 invoice-col"><label for="upload_file"' + upload_number + '>Archivo N° ' + upload_number + '</label><input type="file" id="upload_file' + upload_number + '" name="upload_file' + upload_number + '"/></div>';
            moreUploadTag += '<div class="col-md-6 invoice-col" style="margin-top: 13px;"><div style="margin-left: 10px;"><a href="javascript:del_file(' + upload_number + ')" style="cursor:pointer;" onclick="" class="btn btn-danger btn-flat"><i class="fa fa-trash-o"></i> Eliminar ' + upload_number + '</a></div></div></div>';
            $('<dl id="delete_file' + upload_number + '">' + moreUploadTag + '</dl>').fadeIn('slow').appendTo('#moreImageUpload');
            upload_number++;
        });
});
</script>
<script type="text/javascript">
    function del_file(eleId) {
        var ele = document.getElementById("delete_file" + eleId);
        ele.parentNode.removeChild(ele);
    }
</script>
<script>
$(document).ready(function(){
    $("#OrtNum").inputmask("d-m-y");
    $("#OrtFchEm").inputmask("d-m-y h:s:s");
    $("#OrtFchEm2").inputmask("y-m-d h:s:s");
    $("#OrtFchEj").inputmask("d-m-y");
    $("#OrtAnio").inputmask("9999");
  
    <?php if (isset($ordenTrabajoEdit)) { ?>;     
        $('#OrtFchEj2').datepicker({
            format: "YYYY-MM-DD",
            todayBtn: true,
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
        var fechaEjec = '<?php echo $ordenTrabajoEdit['OrtFchEj']; ?>';
        var fechaServidor ='<?php echo date('Y-m-d') ?>';
        if(fechaEjec === fechaServidor)
        {
             var horaMin = '<?php echo date('H:i:s') ?>'+"'";
        }
        else
        {
             var horaMin = '00:00:00'+"'";
        }
        var fechaMin= "'"+fechaEjec+" "+horaMin;
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
                "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
            ],
            "monthNames": [
                "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                "Augosto","Septiembre","Octubre","Noviembre","Diciembre"
            ],
            "firstDay": 1
        }
        });
        //var OrtFchEj = new Date("<?php echo $ordenTrabajoEdit['OrtFchEj']; ?>");
        //OrtFchEj.setDate(OrtFchEj.getDate() + 1);
        //$('#OrtFchEj2').datepicker('setDate', OrtFchEj);
    <?php } else { ?>;
        var valor = "'"+ $('#OrtFchEj').val() +"'";
        // set default dates
        var start = new Date();
        // set end date to max one year period:
        var end = new Date(new Date().setYear(start.getFullYear()+1));
        //var cad=start.getHours()+":"+start.getMinutes()+":"+start.getSeconds();
        var fechaServ ="'"+ '<?php echo date('d-m-Y') ?>'+"'";

        $('#OrtFchEj').daterangepicker({
            "singleDatePicker": true,
            "autoApply": false,
            "autoclose": true,
            "format": "DD-MM-YYYY",
            "separator": " - ",
            "startDate": valor,
            "minDate": fechaServ,
            "endDate": end,
            "locale": {
                "format": "DD-MM-YYYY",
                "separator": " - ",
                "applyLabel": "Aceptar",
                "cancelLabel": "Cancelar",
                "customRangeLabel": "Custom",
                "autoclose": true,
                "daysOfWeek": [
                    "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
                ],
                "monthNames": [
                    "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                    "Augosto","Septiembre","Octubre","Noviembre","Diciembre"
                ],
                "firstDay": 1
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            var fechaOrtFchEj = picker.startDate.format('DD-MM-YYYY');
            var fechaServidor ='<?php echo date('d-m-Y') ?>';
            if(fechaOrtFchEj === fechaServidor)
            {
                var horaMin = '<?php echo date('H:i:s') ?>'+"'";
            }
            else
            {
                var horaMin = '00:00:00'+"'";
            }
            var fechaMin= "'"+fechaOrtFchEj+" "+horaMin;
            $('#OrtFchEm').val('');
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
            "startDate": fechaMin,
            "minDate": fechaMin,
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
                    "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
                ],
                "monthNames": [
                    "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                    "Augosto","Septiembre","Octubre","Noviembre","Diciembre"
                ],
                "firstDay": 1
            }
            });
          });
    <?php } ?>;
});
</script>
