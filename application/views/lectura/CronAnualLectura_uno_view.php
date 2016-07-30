<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <form id="mainForm" role="form" method="post" enctype="multipart/form-data">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Datos del Cronograma para Toma de Lectura</h3>                                   
                    </div>
                    <?php if (!isset($cronograma)) { ?>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <label>Por:</label>
                                    <div class="form-group">
                                        <div class="control-group"> 
                                            <?php foreach ($niveles as $key => $nivel) { ?>
                                                <input name="nivel" onchange="enviarForm()" type="radio" required value="<?php echo $nivel['NgrId'] ?>" <?php echo ($idNivel == $nivel['NgrId']) ? 'checked' : '' ?>><?php echo $nivel['NgrDes'] ?>   
                                            <?php } ?>  
                                        </div>
                                    </div>  
                                </div>
                                <!--
                                <div class="col-md-4">
                                    <label>Aprobado: </label>
                                    <div class="form-group">
                                        <div class="control-group">
                                            <input id="aprobadoSi" name="aprobado" type="radio" class="flat-red" value="si" selected="" required>Si
                                            <input id="aprobadoNo" name="aprobado" type="radio" class="flat-red" value="no" required>No
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Vigente: </label>
                                    <div class="form-group">
                                        <div class="control-group">
                                            <input id="vigenteSi" name="vigente" type="radio" class="flat-red" value="si" selected="" required>Si
                                            <input id="vigenteNo" name="vigente" type="radio" class="flat-red" value="no" required>No
                                        </div>
                                    </div>
                                </div>
                                -->
                            </div>
                            <div class="row">   
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Año :</label>
                                        <select id="anio" name="anio" class="form-control" required="" onchange="cambiarAnio(this)">
                                            <?php foreach ($anios as $anioitem) { ?>
                                                <option value="<?php echo $anioitem ?>"<?php echo ($anio == $anioitem) ? 'selected' : ''; ?>><?php echo $anioitem ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="help-block"></p>
                                    </div>
                                </div> 
                                <div class="col-md-2 col-md-offset-2">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Nro. Periodos :</label>
                                        <input id="periodos" name="periodos" class="form-control" onchange="enviarForm()" type="number" max="13" min="12" value="<?php echo $periodos ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div> 
                            </div>
                            <?php
                            if (empty($ultimaLectura)) {
                                echo 'No hay datos de lectura anterior, por favor ingrese las últimas fechas de lectura.';
                            }
                            ?>
                            <div class="table-responsive">
                                <table id="CronogramaAnualDetalle" class="table table-bordered table-striped">
                                    <thead>
                                        <tr Class="info" role="row">
                                            <th>
                                                AÑO
                                            </th>
                                            <th colspan="2">
                                                <?php echo $anio - 1; ?>
                                            </th>
                                            <th colspan="<?php echo ($periodos * 2) ?>">
                                                <?php echo $anio; ?>
                                            </th>
                                            <th rowspan="3">Total Días</th>
                                        </tr>
                                        <tr Class="info" role="row">
                                            <th rowspan="2">
                                               <?php echo isset($nivelSeleccionado) ? $nivelSeleccionado['NgrDes'] : 'Zona'; ?>
                                            </th>
                                            <th colspan="2">
                                                <?php echo isset($ultimoPeriodo) ? $ultimoPeriodo['PrdCod'] : ''; ?>
                                            </th>
                                            <!--
                                            <?php for ($i = 1; $i <= $periodos; $i++) { ?>
                                                <th colspan="2">
                                                    <input type="text" id="periodo_<?php echo $i ?>" name="periodo_<?php echo $i ?>" value="<?php echo set_value('periodo_' . $i, $i); ?>" required="">
                                                </th>
                                            <?php } ?>
                                            -->
                                            <?php for ($i = 1; $i <= $periodos; $i++) { ?>
                                            <th colspan="2">
                                                <div class="form-group">
                                                    <select class="periodos form-control" name="periodo_<?php echo $i ?>">
                                                            <option value=""> Seleccione Mes...</option>
                                                            <?php foreach ($periodosRegistrados as $periodoId => $periodo) { ?>
                                                                <option value="<?php echo $periodo['PrdId'] ?>" <?php echo set_select('periodo_' . $i , $periodo['PrdId'] , -1==$periodo['PrdId'] ); ?> required="">
                                                                    <?php echo $periodo['PrdCod'] ?>
                                                                </option>
                                                            <?php } ?>
                                                    </select>
                                                </div>
                                            </th>
                                            <?php } ?>
                                        </tr>
                                        <tr Class="info" role="row">
                                            <th>
                                                <i class="fa fa-calendar"></i> Toma 
                                            </th>
                                            <th >
                                                Total Usuarios
                                            </th>
                                            <?php for ($i = 1; $i <= $periodos; $i++) { ?>
                                                <th> <i class="fa fa-calendar"></i> Toma </th><th># Días</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($gruposPredio as $grupoPredio) { ?>
                                            <tr>
                                                <td >
                                                    <?php echo $grupoPredio['GprCod'] ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if (isset($ultimaLectura[$grupoPredio['GprId']])) {
                                                        $fecha = $ultimaLectura[$grupoPredio['GprId']];
                                                    } else {
                                                        $fecha = date('d/m/Y', strtotime('01-11-' . ($anio - 1)));
                                                    }
                                                    ?>
                                                    <input class="fch_inicial" type="text" <?php echo isset($ultimaLectura[$grupoPredio['GprId']]) ? 'class="borderless" readonly="true"' : '' ?> id="<?php echo 'fch_inicial_' . $grupoPredio['GprId']; ?>" name="<?php echo 'fch_inicial_' . $grupoPredio['GprId']; ?>" 
                                                           value="<?php echo set_value('fch_inicial_' . $grupoPredio['GprId'], $fecha); ?>" onchange="actualizarFila(<?php echo $grupoPredio['GprId'] ?>)" required="">
                                                </td>
                                                <td>
                                                    <?php echo $grupoPredio['cantidad'] ?>
                                                </td>
                                                <?php $nroMes = ($diasAnio == 365) ? 8 : 7; ?>
                                                <?php
                                                for ($i = 1; $i <= $periodos; $i++) {
                                                    $dias = ($i < $nroMes) ? 30 : 31;
                                                    ?>
                                                    <td><input type="text" class="borderless" id="<?php echo 'fch_' . $grupoPredio['GprId'] . '_' . $i; ?>" name="<?php echo 'fch_' . $grupoPredio['GprId'] . '_' . $i; ?>" 
                                                               value="<?php
                                                               $parts = explode('/', $fecha);
                                                               $fecha = date('d/m/Y', strtotime($parts[1] . '/' . $parts[0] . '/' . $parts[2] . ' + ' . $dias . ' days'));
                                                               echo set_value('fch_' . $grupoPredio['GprId'] . '_' . $i, $fecha);
                                                               ?>" readonly="true">
                                                    </td>
                                                    <td><input id="<?php echo 'dias_' . $grupoPredio['GprId'] . '_' . $i ?>" name="<?php echo 'dias_' . $grupoPredio['GprId'] . '_' . $i ?>"
                                                               onchange="actualizarFila(<?php echo $grupoPredio['GprId'] ?>)" type="number" max="32" min="28" 
                                                               value="<?php echo set_value('dias_' . $grupoPredio['GprId'] . '_' . $i, $dias) ?>"></td>
                                                    <?php } ?>
                                                <td><input type="text" class="borderless" id="<?php echo 'anio_' . $grupoPredio['GprId'] ?>" name="<?php echo 'anio_' . $grupoPredio['GprId'] ?>" 
                                                           value="<?php echo set_value('anio_' . $grupoPredio['GprId'], $diasAnio) ?>" readonly="true"></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.box-body -->
                    <?php } ?>

                    <?php if (isset($cronograma)) { ?>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="CronogramaAnualDetalle" class="table table-bordered table-striped" aria-describedby="CronogramaAnualDetalle_info">
                                    <thead>
                                        <tr Class="info" role="row">
                                            <th>
                                                AÑO
                                            </th>
                                            <th colspan="2">
                                                <?php echo $anio - 1; ?>
                                            </th>
                                            <th colspan="<?php echo (count($periodos) * 2) ?>">
                                                <?php echo $anio; ?>
                                            </th>
                                            <th rowspan="3">Total Días</th>
                                        </tr>
                                        <tr Class="info" role="row">
                                            <th rowspan="2">
                                                <?php echo $cronograma['NgrDes']; ?>
                                            </th>
                                            <th colspan="2">
                                                <?php echo $ultimoPeriodo['PrdCod']; ?>
                                            </th>
                                            <?php for ($i = 0; $i < count($periodos); $i++) { ?>
                                                <th colspan="2">
                                                    <?php echo $periodos[$i]["PrdCod"]; ?>
                                                </th>
                                            <?php } ?>
                                        </tr>
                                        <tr Class="info" role="row">
                                            <th>
                                                <i class="fa fa-calendar"></i> Toma 
                                            </th>
                                            <th >
                                                Total Usuarios
                                            </th>
                                            <?php for ($i = 0; $i < count($periodos); $i++) { ?>
                                                <th> <i class="fa fa-calendar"></i> Toma </th><th># Días</th>
                                            <?php } ?>    
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($gruposPredio as $grupoPredio) { ?>
                                            <tr>
                                                <td >
                                                    <?php echo $grupoPredio['GprCod'] ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if (isset($ultimaLectura[$grupoPredio['GprId']])) {
                                                        $fecha = $ultimaLectura[$grupoPredio['GprId']];
                                                    } else {
                                                        $fecha = date('d/m/Y', strtotime('01-11-' . ($anio - 1)));
                                                    }
                                                    ?>
                                                    <input class="fch_inicial" type="text" <?php echo isset($ultimaLectura[$grupoPredio['GprId']]) ? 'class="borderless" readonly="true"' : '' ?> id="<?php echo 'fch_inicial_' . $grupoPredio['GprId']; ?>" name="<?php echo 'fch_inicial_' . $grupoPredio['GprId']; ?>" 
                                                           value="<?php echo set_value('fch_inicial_' . $grupoPredio['GprId'], $fecha); ?>" onchange="actualizarFila(<?php echo $grupoPredio['GprId'] ?>)" required="">
                                                </td>
                                                <td>
                                                    <?php echo $grupoPredio['cantidad'] ?>
                                                </td>
                                                <?php $nroMes = ($diasAnio == 365) ? 8 : 7; ?>
                                                <?php
                                                for ($i = 1; $i <= count($periodos); $i++) {
                                                    $dias = $cronograma['detalle'][$grupoPredio['GprId']][$i];
                                                    ?>
                                                    <td><input type="text" class="borderless" id="<?php echo 'fch_' . $grupoPredio['GprId'] . '_' . $i; ?>" name="<?php echo 'fch_' . $grupoPredio['GprId'] . '_' . $i; ?>" 
                                                               value="<?php
                                                               $parts = explode('/', $fecha);
                                                               $fecha = date('d/m/Y', strtotime($parts[1] . '/' . $parts[0] . '/' . $parts[2] . ' + ' . $dias . ' days'));
                                                               echo set_value('fch_' . $grupoPredio['GprId'] . '_' . $i, $fecha);
                                                               ?>" readonly="true">
                                                    </td>
                                                    <td><input id="<?php echo 'dias_' . $grupoPredio['GprId'] . '_' . $i ?>" name="<?php echo 'dias_' . $grupoPredio['GprId'] . '_' . $i ?>"
                                                               onchange="actualizarFila(<?php echo $grupoPredio['GprId'] ?>)" type="number" max="32" min="28" 
                                                               value="<?php echo set_value('dias_' . $grupoPredio['GprId'] . '_' . $i, $dias) ?>"></td>
                                                    <?php } ?>
                                                <td><input type="text" class="borderless" id="<?php echo 'anio_' . $grupoPredio['GprId'] ?>" name="<?php echo 'anio_' . $grupoPredio['GprId'] ?>" 
                                                           value="<?php echo set_value('anio_' . $grupoPredio['GprId'], $diasAnio) ?>" readonly="true"></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.box-body -->
                    <?php } ?>
                </div><!-- /.box -->
                <div class="row">
                    <div class="col-md-6">
                        <button id="btn_guardar" name="btn_guardar" type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo ($accion == 'nuevo') ? 'Registrar' : 'Actualizar'; ?></button>
                    </div>
                    <div class="col-md-6">
                        <button type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- date-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/iCheck/all.css">
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() ?>frontend/plugins/iCheck/icheck.min.js"></script>

<!-- Validar COMBO-BOX UNICOS SELECT -->
<script src="<?php echo base_url() ?>frontend/plugins/validation/unique.js" type="text/javascript"></script>


<script type="text/javascript">
$(function () {
$("#CronogramaAnualDetalle").dataTable({
    "bSort": false,
    "paging": false
    });
});
function enviarForm() {
    document.getElementById("mainForm").submit();
};
function actualizarFila(ciclo) {
    var fecha = $('#fch_inicial_' +
    ciclo.toString()).val();
    var i;
    var parts = fecha.split('/');
    var total = 0;
    var dias = 0;
    fecha = new Date(parts[2], parts[1] - 1, parts[0]);
    for (i = 1; i <=<?php echo (!isset($cronograma)) ? $periodos : '12'; ?>; i++) {
    dias = parseInt($('#dias_' + ciclo.toString() + '_' + i.toString()).val());
    fecha.setDate(fecha.getDate() + dias);
    $('#fch_' + ciclo.toString() + '_' +i.toString()).val(ceros(fecha.getDate()) +'/' + ceros(fecha.getMonth() + 1) +'/' + fecha.getFullYear());
    total = total + dias;
    }
    $('#anio_' + ciclo.toString()).val(
    total.toString());
    function ceros(num) {
        return (num < 10) ? '0' + num : num;
    }
}
</script>
<script type="text/javascript">
$(document).ready(function ()
{
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-blue',
          radioClass: 'iradio_flat-blue'
    });  
    
    var k = new Unique('.periodos','null');
    
    <?php foreach ($gruposPredio as $grupoPredio) { ?>
        actualizarFila(<?php echo $grupoPredio['GprId'] ?>);
    <?php } ?>


<?php if(!isset($ultimaLectura)) { ?>
var anio = parseInt(<?php echo $anio; ?>) - 1;
$('.fch_inicial').datepicker
({
    setDate: "01/11/" + anio,
    format: "dd/mm/yyyy",
    startDate: "01/11/" + anio,
    endDate: "31/12/" + anio,
    language: "es",
    autoclose: true
});
<?php } ?>


});
function cambiarAnio(anio) {
var r = confirm("Se perderán todos los datos no guardados. ¿Desea continuar?")
if (r == true) {
    window.location = '<?php base_url() . 'toma_estado/cronograma_anual/nuevo/' ?>' + $(anio).val();
} else {
    $('#anio').val(<?php echo $anio ?>);
    return false;
}
};
</script>