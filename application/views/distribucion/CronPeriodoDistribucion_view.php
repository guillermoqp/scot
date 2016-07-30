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
                        <h3 class="box-title">Datos del Cronograma de Distribución de Recibos y Comunicaciones al Cliente por Periodo</h3>                                   
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php if (!isset($cronograma)) { ?>
                            <div class="row"> 
                                <div class="col-md-8">
                                    <label>Por:</label>
                                    <div class="form-group">
                                        <div class="control-group"> 
                                            <?php foreach ($niveles as $key => $nivel) { 
                                                $cant = 0;?>
                                                <?php foreach ($nivelesPosibles as $nivelPosible)
                                                      {
                                                        if($nivel['NgrId']==$nivelPosible){ $cant++; }
                                                      }
                                                      if($cant == 0)
                                                      {?>
                                                        <input name="nivel" onchange="enviarForm()" type="radio" value="<?php echo $nivel['NgrId'] ?>" <?php echo ($idNivel == $nivel['NgrId']) ? 'checked' : '' ?> disabled=""> <?php echo $nivel['NgrDes'] ?>
                                                <?php } else { ?>
                                                        <input name="nivel" onchange="enviarForm()" type="radio" value="<?php echo $nivel['NgrId'] ?>" <?php echo ($idNivel == $nivel['NgrId']) ? 'checked' : '' ?>> <?php echo $nivel['NgrDes'] ?>
                                                <?php }
                                            } ?>
                                         </div>
                                    </div>  
                                </div>
                            </div>        
                            <?php } ?>  
                            <div class="row">   
                                <div class="col-md-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Año: <?php if ($accion == 'editar') echo $anio; ?></label>
                                        <?php if ($accion != 'editar') { ?>
                                            <select id="anio" name="anio" class="form-control" required="" onchange="cambiarAnio(this)">
                                                <?php foreach ($anios as $anioitem) { ?>
                                                    <option value="<?php echo $anioitem ?>"<?php echo ($anio == $anioitem) ? 'selected' : ''; ?>><?php echo $anioitem ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                        <p class="help-block"></p>
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Periodo: <?php echo ($accion == 'editar') ? $mes.' ('.$this->utilitario_cls->nombreMes($mes).' )' : ''; ?></label>
                                        <?php if ($accion != 'editar') { ?>
                                            <select id="mes" name="mes" class="form-control" required="" onchange="cambiarMes(this)">
                                                <option value="<?php echo (intval(date('m'), 10)); ?>"<?php echo (intval(date('m'), 10) == $mes) ? 'selected' : ''; ?>><?php echo $this->utilitario_cls->nombreMes((intval(date('m'), 10))) ?></option>
                                                <option value="<?php echo (intval(date('m'), 10) + 1); ?>"<?php echo ((intval(date('m'), 10) + 1) == $mes) ? 'selected' : ''; ?>><?php echo $this->utilitario_cls->nombreMes((intval(date('m'), 10)) + 1) ?></option>
                                            </select>
                                        <?php } ?>
                                        <p class="help-block"></p>
                                    </div>
                                </div>  
                            </div>
                        <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped" aria-describedby="example1_info">
                            <thead>
                                <tr Class="info" role="row">
                                    <th rowspan="2">
                                        <?php echo $NivelSeleccionado['NgrDes'] ?>
                                    </th>
                                    <?php if(!is_null($SubNivel)) {?>
                                    <th rowspan="2">
                                        <?php echo $SubNivel['NgrDes'] ?>
                                    </th>
                                    <?php } ?>
                                    <th rowspan="2">
                                        Suministros
                                    </th>
                                    <th rowspan="2">
                                        Fecha de Lectura
                                    </th>
                                    <th rowspan="2">
                                        Fecha de Consistencia de Lecturas 
                                    </th>
                                    <th colspan="2">
                                        Carga Data P/Fact. 
                                    </th>
                                    <th rowspan="2">
                                        CALCULO, Control Anomalias 
                                    </th>
                                    <th colspan="2">
                                        Impresión, Emision Recibos 
                                    </th>
                                    <th rowspan="2">
                                        Distribuc. Recibos / Actul. en red datos
                                    </th>
                                </tr>
                                <tr Class="info" role="row">
                                    <th>fecha</th>
                                    <th>días</th>
                                    <th>fecha</th>
                                    <th>días</th>
                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php foreach ($gruposPredio as $grupoPredio) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $grupoPredio['GprCod'] ?>
                                        </td>
                                        <?php if(!empty($grupoPredio['subniveles'])) {?>
                                        <td>
                                            <?php 
                                            $subniveles = array_column($grupoPredio['subniveles'], 'GprCod');
                                            echo implode(',', $subniveles) ?>
                                        </td>
                                        <?php } ?>
                                        <td><?php echo $grupoPredio['cantidad'] ?></td>
                                        <td><?php echo $lecturaAnual[$grupoPredio['GprId']]['DlaFchTm']; ?></td>
                                        <td><?php echo $lecturaAnual[$grupoPredio['GprId']]['AuxFchCo']; ?></td>
                                        <td>
                                            <span id="<?php echo 'cargaData_' . $grupoPredio['GprId']; ?>"><?php echo $lecturaAnual[$grupoPredio['GprId']]['AuxFchCo']; ?></span>
                                        </td>
                                        <td>
                                            <input id="<?php echo 'diasCrg_' . $grupoPredio['GprId'] ?>" name="<?php echo 'diasCrg_' . $grupoPredio['GprId'] ?>"
                                                   onchange="actualizarFila(<?php echo $grupoPredio['GprId'] ?>)" type="number" max="3" min="1" 
                                                   value="<?php echo set_value('diasCrg_' . $grupoPredio['GprId'], ((!isset($cronograma)) ? 1 : $cronograma[$grupoPredio['GprId']]['DdpDiaCg'])); ?>">
                                        </td>
                                        <td>
                                            <input type="text" class="borderless" id="<?php echo 'fchCal_' . $grupoPredio['GprId']; ?>" name="<?php echo 'fchCal_' . $grupoPredio['GprId']; ?>" 
                                                   value="<?php echo set_value('fchCal_' . $grupoPredio['GprId'], ((!isset($cronograma)) ? $lecturaAnual[$grupoPredio['GprId']]['AuxFchCo'] : $cronograma[$grupoPredio['GprId']]['DdpFchCal'])); ?>" readonly="true">
                                        </td>
                                        <td>
                                            <input type="text" class="borderless" id="<?php echo 'fchImp_' . $grupoPredio['GprId']; ?>" name="<?php echo 'fchImp_' . $grupoPredio['GprId']; ?>" 
                                                   value="<?php echo set_value('fchImp_' . $grupoPredio['GprId'], ((!isset($cronograma)) ? $lecturaAnual[$grupoPredio['GprId']]['AuxFchCo'] : $cronograma[$grupoPredio['GprId']]['DdpFchImp'])); ?>" readonly="true">
                                        </td>
                                        <td>
                                            <input id="<?php echo 'diasImp_' . $grupoPredio['GprId'] ?>" name="<?php echo 'diasImp_' . $grupoPredio['GprId'] ?>"
                                                   onchange="actualizarFila(<?php echo $grupoPredio['GprId'] ?>)" type="number" max="3" min="1" 
                                                   value="<?php echo set_value('diasImp_' . $grupoPredio['GprId'], ((!isset($cronograma)) ? 1 : $cronograma[$grupoPredio['GprId']]['DdpDiaImp'])); ?>">
                                        </td>
                                        <td class="text-red">
                                            <b>
                                            <input type="text" class="borderless" id="<?php echo 'fchDis_' . $grupoPredio['GprId']; ?>" name="<?php echo 'fchDis_' . $grupoPredio['GprId']; ?>" 
                                                   value="<?php echo set_value('fchDis_' . $grupoPredio['GprId'], ((!isset($cronograma)) ? $lecturaAnual[$grupoPredio['GprId']]['AuxFchCo'] : $cronograma[$grupoPredio['GprId']]['DdpFchDis'])); ?>" readonly="true">
                                            </b>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        </div>    
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
                <div class="row">
                    <div class="col-md-6">
                        <button name="btn_guardar_cron_distribucion_periodo" type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo ($accion == 'nuevo') ? 'Registrar' : 'Actualizar'; ?></button>
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
<!-- page script -->
<script type="text/javascript">
function enviarForm() {
    document.getElementById("mainForm").submit();
};
function actualizarFila(grupoPredio) {
	var fchTemp = $('#cargaData_' +
		grupoPredio.toString()).text();
	var parts = fchTemp.split('/');
	fchTemp = new Date(parts[2], parts[1] -
		1, parts[0]);
	//Fecha de CALCULO, Control Anomalias
	dias = parseInt($('#diasCrg_' + grupoPredio
		.toString()).val());
	fchTemp.setDate(fchTemp.getDate() +
		dias);
	$('#fchCal_' + grupoPredio.toString()).val(
		ceros(fchTemp.getDate()) + '/' +
		ceros(fchTemp.getMonth() + 1) + '/' +
		fchTemp.getFullYear());
        $('#fchImp_' + grupoPredio.toString()).val(
		ceros(fchTemp.getDate()) + '/' +
		ceros(fchTemp.getMonth() + 1) + '/' +
		fchTemp.getFullYear());
	//Fecha Distribución
        diasDis = parseInt($('#diasImp_' +
		grupoPredio.toString()).val());
	fchTemp.setDate(fchTemp.getDate() + diasDis);
	$('#fchDis_' + grupoPredio.toString()).val(
		ceros(fchTemp.getDate()) + '/' +
		ceros(fchTemp.getMonth() + 1) + '/' +
		fchTemp.getFullYear());

	function ceros(num) {
		return (num < 10) ? '0' + num : num;
	}
}                                         
</script>

<script type="text/javascript">
$(document).ready(function ()
{
    <?php
    if (!isset($cronograma)) {
        foreach ($gruposPredio as $grupoPredio) {
            ?>
                    actualizarFila(<?php echo $grupoPredio['GprId'] ?>);
            <?php }
    } ?>  
});
    function cambiarAnio(anio) {
        var r = confirm("Se perderán todos los datos no guardados. ¿Desea continuar?")
        if (r == true) {
            window.location = '<?php echo base_url() . 'toma_estado/cronograma_periodo/nuevo/' ?>' + $(anio).val() + '/<?php echo $mes ?>';
        } else {
            $('#anio').val(<?php echo $anio ?>);
            return false;
        }
    }
    ;
    function cambiarMes(mes) {
        var r = confirm("Se perderán todos los datos no guardados. ¿Desea continuar?")
        if (r == true) {
            window.location = '<?php echo base_url() . 'toma_estado/cronograma_periodo/nuevo/' . $anio . '/' ?>' + $(mes).val();
        } else {
            $('#mes').val(<?php echo $mes ?>);
            return false;
        }
    }
    ;
</script>