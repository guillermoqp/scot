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
               
                <div class="row">   
                    <div class="col-md-4">
                        <img style="width: 210px; height: 45px;" src="<?php echo base_url() . $userdata['contratante']['CntLog1'] ?>" alt="<?php echo $userdata['contratante']['EmpRaz'] ?>"> 
                    </div>   
                    <div class="col-md-4">
                        <h4 class="modal-title"><b>Resumen del acta de Inspección Interna</b></h4>
                    </div> 
                    <div class="col-md-4">
                        <h4 class="modal-title">N° <?php echo str_pad( rand(2000, 2500) , 6, '0', STR_PAD_LEFT);  ?>  - CAT </h4>
                    </div> 
                </div>
                <br>
                <div class="row">   
                    <div class="col-md-6">
                        <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">N° SUMINISTRO</label>
                                <div class="col-sm-8">
                                    <input type="text" name="nombre" id="nombre" class="form-control">
                                </div>
                        </div>
                    </div>   
                    <div class="col-md-6">
                        <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">CODIGO DE RECLAMO</label>
                                <div class="col-sm-8">
                                    <input type="text" name="nombre" id="nombre" class="form-control">
                                </div>
                        </div>
                    </div> 
                </div>
                <div class="row">
                    <div class="col-md-6"><b>NOMBRE DE RECLAMANTE O REPRESENTANTE</b></div>
                    <div class="col-md-6"><b>TIPO DE RECLAMO:</b> CONSUMO PROMEDIO</div>
                </div>   
                <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">N° SUMINISTRO</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nombre" id="nombre">
                                    </div>
                            </div>
                        </div>   
                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">CODIGO DE RECLAMO</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nombre" id="nombre">
                                    </div>
                            </div>
                        </div> 
                </div>

            
           <div class="table-responsive">
            <table id="detalelInspeccionInstalaciones" class="table table-bordered table-striped">
                <thead>
                    <tr Class="info" role="row">
                        <th>Estado\Instalacion Sanit.</th>
                        <th>Inodoro</th>
                        <th>Lavatorio</th>
                        <th>Ducha</th>
                        <th>Urinario</th>
                        <th>Bidet</th>
                        <th>Grifo</th>
                        <th>Cisterna</th>
                        <th>Tanque</th>
                        <th>Piscina</th>
                    </tr>
                </thead>
                <tbody role="alert" aria-live="polite" aria-relevant="all">
                        <tr>
                            <td>Bueno</td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Con Fuga
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Reparado
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Clausurado
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Totales
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                            <td><input id="" name=""
                                       type="number" max="3" min="1" 
                                       value="">
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>      
            
            
        <div class="row">   
            <div class="col-md-8">
                OBSERVACIONES :
                <textarea class="form-control" type="text" name="observaciones" id=""></textarea>
            </div>   
            <div class="col-md-4">
                <center>Numero municipal/ Numero Lote</center><br>
                <div class="col-md-3">
                    Derecho <input type="text" name="num_munic_derech" id="" class="form-control">
                </div>
                <div class="col-md-3">
                    Censado <input type="text" name="num_munic_censado" id="" class="form-control">
                </div>
                <div class="col-md-3">
                    Izquierdo<input type="text" name="num_munic_izquierdo" id="" class="form-control">
                </div> 
            </div> 
        </div>
                
        <hr style="margin-top:10px;margin-bottom: 10px;">
        <div class="row">   
            <div class="col-md-8">
                <dl class="dl-horizontal">
                    <dt>ENCUESTADO :</dt><dd><input type="text" class="form-control" name="encuestado" id=""></dd>
                    <dt>DNI : </dt><dd><input type="text" class="form-control" name="dni_encuestado" id="dni_encuestado"></dd>
                    <dt>PARENTESCO :</dt><dd><input type="text" class="form-control" name="parentesco_encuestado" id=""></dd>
                </dl>
            </div> 
            <div class="col-md-4">
                <div class="row"><b>NOMBRE DEL EMPADRONADOR/INSPECTOR :</b> SOTILLA REYES</div>
                <div class="row"><b>CODIGO DEL EMPADRONADOR/INSPECTOR :</b> 44023</div>
                Marcial Javier Grimaldo García<br>
                <b>SUPERVISOR INSPECCION COMERCIAL</b>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-6">
                <button type="submit" name="guardar_ficha_act_cat" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i>Guardar</button>
            </div>
            <div class="col-md-6">
                <button type="button" onclick="window.close();" class="btn btn-block btn-default btn-flat">Cerrar</button>
            </div>
        </div>   
        
        </form>
    </div>
</section>


<!-- Select2 -->
<script src="<?php echo base_url() ?>frontend/plugins/select2/select2.full.min.js"></script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() ?>frontend/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url() ?>frontend/plugins/fastclick/fastclick.js"></script>

<!-- InputMask -->
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>


<!-- date-range-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- date-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

<!-- Select2 -->
<script src="<?php echo base_url() ?>frontend/plugins/select2/select2.full.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/select2/select2.min.css">

<script>
$(document).ready(function ()
{
    $("#detalleCiclo").DataTable({
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]]
    });
    
    $(".select2").select2();
    $("#dni").inputmask("99999999");
    $("#dni_encuestado").inputmask("99999999");
    
    var fechaMax = "'"+'31-12-'+<?php echo date('Y') ?>+"'";
    $("#OrtFchEj").inputmask("d-m-y");
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
});
</script> 