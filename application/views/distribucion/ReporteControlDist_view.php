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
                        <h3 class="box-title">Control de Distribución de Recibos y Notificaciones , Periodo: <?php echo $periodo ?> , Año: <?php echo $anio ?>  </h3>                                   
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <form id="mainForm" name="prueba" role="form" method="post" action="<?php echo str_replace('/index.php', '', current_url()); ?>"> 
                        <div class="row">   
                            <div class="col-md-4">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Año: <?php echo $anio; ?></label>
                                    <select id="anio" name="anio" class="form-control" required="" onchange="cambiarAnio(this)">
                                        <?php foreach ($anios as $anioitem) { ?>
                                            <option value="<?php echo $anioitem ?>"<?php echo ($anio == $anioitem) ? 'selected' : ''; ?>><?php echo $anioitem ?></option>
                                        <?php } ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Periodo: </label>
                                    <select id="mes" name="mes" class="form-control" required="" onchange="cambiarMes(this)">
                                        <?php foreach ($periodos as $nroOrden => $periodoitem) { ?>
                                            <option value="<?php echo $nroOrden ?>"<?php echo ($periodo == $periodoitem) ? 'selected' : ''; ?>><?php echo $periodoitem ?></option>
                                        <?php } ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Nivel Comercial: </label>
                                    <select ide="nivel" name="nivel" class="form-control" required="" onchange="enviarForm('nivel')">
                                            <option value="<?php echo $niveles[0]['NgrId'] ?>" selected>Seleccione Opción</option>
                                        <?php foreach ($niveles as $nivel) { ?>
                                            <option value="<?php echo $nivel['NgrId'] ?>"<?php echo ($idNivel == $nivel['NgrId']) ? 'selected' : ''; ?>><?php echo $nivel['NgrDes'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>          
                            </div> 
                        </div>
                        </form>
                        <table id="controlDistribuciones" class="table table-bordered table-striped">
                            <thead>
                                <tr Class="info" role="row">
                                    <th><?php echo isset($nivelSeleccionado) ? $nivelSeleccionado['NgrDes'] : ''; ?></th>
                                    <th>Fecha Distribucion</th>
                                    <th>Distribuciones Programadas</th>
                                    <th>Distribuciones Ejecutadas</th>
                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php foreach ($ciclos as $ciclo) { ?>
                                    <tr>
                                        <td >
                                            <?php echo $ciclo['GprCod'] ?>
                                        </td>
                                        
                                        <td>
                                            <?php echo $ciclo['fecha'] ?>
                                        </td>
                                        <td>
                                            <?php echo $ciclo['programadas'] ?>
                                        </td>
                                        <td>
                                            <?php echo $ciclo['ejecutadas'] ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </form>
        </div>
    </div>
</section>

<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script type="text/javascript">
function enviarForm(opcion){
    hdn = document.createElement('input');
    hdn.setAttribute('name', 'opcion');
    hdn.setAttribute('type', 'hidden');
    hdn.setAttribute('value', opcion);
    frm = document.getElementById('mainForm')
    frm.appendChild(hdn);
    frm.submit();
};
function cambiarAnio(anio) {
    window.location = '<?php echo base_url() . 'distribucion/reportes/control/' ?>' + $(anio).val() + '/<?php echo $periodo[0] ?>';
};
function cambiarMes(mes) {
    window.location = '<?php echo base_url() . 'distribucion/reportes/control/' . $anio . '/' ?>' + $(mes).val();
};
</script>
<script type="text/javascript">
$(document).ready(function ()
{
    $("#controlDistribuciones").dataTable();
});
</script>