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
                        <h3 class="box-title">Datos del Cronograma de Lectura por Periodo</h3>                                   
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
                                        Fecha Lectura Anterior 
                                    </th>
                                    <th rowspan="2">
                                        Fecha Entrega a la Service 
                                    </th>
                                    <th colspan="2">Fecha de Lectura</th>
                                    <th colspan="2">Fecha de Consistencia de Lecturas</th>
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
                                        <td>
                                            <span id="<?php echo 'fchAnterior_' . $grupoPredio['GprId']; ?>"> <?php echo $lecturaAnual[$grupoPredio['GprId']]['AuxFchAn']; ?> </span>
                                        </td>
                                        <td>
                                            <input type="text" class="borderless" id="<?php echo 'fchEntrega_' . $grupoPredio['GprId']; ?>" name="<?php echo 'fchEntrega_' . $grupoPredio['GprId']; ?>" 
                                                   value="<?php echo set_value('fchEntrega_' . $grupoPredio['GprId'], ((!isset($cronograma)) ? $lecturaAnual[$grupoPredio['GprId']]['AuxFchEn'] : $cronograma[$grupoPredio['GprId']]['DlpFchEn'])); ?>" readonly="true">
                                        </td>
                                        <td class="text-red">
                                            <b>
                                            <input type="text" class="borderless" id="<?php echo 'fchToma_' . $grupoPredio['GprId']; ?>" name="<?php echo 'fchToma_' . $grupoPredio['GprId']; ?>" 
                                                   value="<?php echo set_value('fchToma_' . $grupoPredio['GprId'], ((!isset($cronograma)) ? $lecturaAnual[$grupoPredio['GprId']]['DlaFchTm'] : $cronograma[$grupoPredio['GprId']]['DlpFchTm'])); ?>" readonly="true">
                                            </b>
                                        </td>
                                        <td>
                                            <input id="<?php echo 'diasToma_' . $grupoPredio['GprId'] ?>" name="<?php echo 'diasToma_' . $grupoPredio['GprId'] ?>"
                                                   onchange="actualizarFila(<?php echo $grupoPredio['GprId'] ?>)" type="number" max="32" min="28" 
                                                   value="<?php echo set_value('diasToma_' . $grupoPredio['GprId'], ((!isset($cronograma)) ? $lecturaAnual[$grupoPredio['GprId']]['DlaDia'] : $cronograma[$grupoPredio['GprId']]['DlpDiaTm'])); ?>">
                                        </td>
                                        <td>
                                            <input type="text" class="borderless" id="<?php echo 'fchCon_' . $grupoPredio['GprId']; ?>" name="<?php echo 'fchCon_' . $grupoPredio['GprId']; ?>" 
                                                   value="<?php echo set_value('fchCon_' . $grupoPredio['GprId'], ((!isset($cronograma)) ? $lecturaAnual[$grupoPredio['GprId']]['AuxFchCo'] : $cronograma[$grupoPredio['GprId']]['DlpFchCo'])); ?>" readonly="true">
                                        </td>
                                        <td>
                                            <input id="<?php echo 'diasCon_' . $grupoPredio['GprId'] ?>" name="<?php echo 'diasCon_' . $grupoPredio['GprId'] ?>"
                                                   onchange="actualizarFila(<?php echo $grupoPredio['GprId'] ?>)" type="number" max="3" min="1" 
                                                   value="<?php echo set_value('diasCon_' . $grupoPredio['GprId'], ((!isset($cronograma)) ? 1 : $cronograma[$grupoPredio['GprId']]['DlpDiaCo'])); ?>">
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
                        <button name="btn_guardar_cron_lectura_periodo" type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo ($accion == 'nuevo') ? 'Registrar' : 'Actualizar'; ?></button>
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
	var fchTemp = $('#fchAnterior_' +
		grupoPredio.toString()).text();
	var parts = fchTemp.split('/');
	fchTemp = new Date(parts[2], parts[1] -
		1, parts[0]);
	//Fecha de Toma
	dias = parseInt($('#diasToma_' + grupoPredio
		.toString()).val());
	fchTemp.setDate(fchTemp.getDate() +
		dias);
	$('#fchToma_' + grupoPredio.toString()).val(
		ceros(fchTemp.getDate()) + '/' +
		ceros(fchTemp.getMonth() + 1) + '/' +
		fchTemp.getFullYear());
	//Fecha de Entrega
	fchTemp.setDate(fchTemp.getDate() - 1);
	$('#fchEntrega_' + grupoPredio.toString()).val(
		ceros(fchTemp.getDate()) + '/' +
		ceros(fchTemp.getMonth() + 1) + '/' +
		fchTemp.getFullYear());
	//Fecha Consistencia
	diasCon = parseInt($('#diasCon_' +
		grupoPredio.toString()).val());
	fchTemp.setDate(fchTemp.getDate() + (
		diasCon + 1));
	$('#fchCon_' + grupoPredio.toString()).val(
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