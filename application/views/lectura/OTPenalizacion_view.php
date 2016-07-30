<section class="content">
    <div class="row">
        <form role="form" accept-charset="utf-8" method="post" action="<?php echo str_replace('/index.php', '', current_url())."/guardar" ; ?>">
            <div class="col-xs-12">
                
                <?php if (null!=$this->session->flashdata('mensaje')) { ?>
                    <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $_SESSION['mensaje'][1]; ?>
                    </div>
                <?php } ?>
                
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Datos de Penalización</h3>                                   
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php   if($accion=='editar') { ?>
                            <div class="row">   
                                <div class="col-md-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Código de Penalización:</label>
                                        <input id="PnzCod" name="PnzCod" type="text" class="form-control" value="<?php echo (isset($penalizacionEdit)) ? $penalizacionEdit['PnzCod'] : set_value('PnzCod'); ?>" required>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Descripción de Penalización:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-file-text"></i>
                                            </div>
                                            <textarea id="PnzDes" name="PnzDes" class="form-control" rows="3" required><?php echo (isset($penalizacionEdit)) ? $penalizacionEdit['PnzDes'] : set_value('PnzDes'); ?></textarea>
                                        </div>       
                                    </div>
                                </div> 
                            </div>    

                            <div class="row">   
                                 <div class="col-md-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Número de Casos Suscitados:</label>
                                        <input id="PnzCas" name="PnzCas" type="number" max="100" min="1" class="form-control" value="<?php echo (isset($penalizacionEdit)) ? $penalizacionEdit['PnzCas'] : set_value('PnzCas'); ?>" required>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Número de Días de retraso hasta la subsanación pertinente:</label>
                                        <input id="PnzDia" name="PnzDia" type="number" max="366" min="1" class="form-control" value="<?php echo (isset($penalizacionEdit)) ? $penalizacionEdit['PnzDia'] : set_value('PnzDia'); ?>" required>
                                        <p class="help-block"></p>
                                        <!--<input id="DcpId" name="DcpId" type="hidden" value="<?php echo (isset($penalizacionEdit)) ? $penalizacionEdit['PnzDcpId'] : ''; ?>">-->
                                    </div>
                                </div>
                            </div>         
                        <?php } ?>
                        <?php   if($accion=='nuevo') { ?>
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Código de Penalización:</label>
                                    <input id="PnzCod" name="PnzCod" type="text" class="form-control" required>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label>Descripción de Penalización:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-file-text"></i>
                                        </div>
                                        <textarea id="PnzDes" name="PnzDes" class="form-control" rows="3" required></textarea>
                                    </div>       
                                </div>
                            </div> 
                        </div>    
                        <div class="row">   
                             <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Número de Casos Suscitados:</label>
                                    <input id="PnzCas" name="PnzCas" type="number" max="100" min="1" class="form-control" required>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Número de Días de retraso hasta la subsanación pertinente:</label>
                                    <input id="PnzDia" name="PnzDia" type="number" max="366" min="1" class="form-control" required>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Grupo Penalidad:</label>
                                    <select id="grupoPenalidad" name="grupoPenalidad" class="form-control" required="">
                                        <?php foreach ($gruposPenalidad as $grupoPenalidad) { ?>
                                            <option value="<?php echo $grupoPenalidad['GpeId'] ?>">
                                                <?php echo $grupoPenalidad['GpeDes'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>    
                        </div>
                        
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Multa por Penalidad:</label>
                                    <select id="DcpId" name="DcpId" class="form-control" required="">
                                        <?php foreach ($penalidades as $penalidad) { ?>
                                            <?php foreach ($detallesContratoPenalidad as $detalleContratoPenalidad) { ?>
                                                <?php if($detalleContratoPenalidad['PenId']==$penalidad['PenId']) { ?>
                                                <option value="<?php echo $detalleContratoPenalidad['DcpId'] ?>">
                                                   Multa: <?php echo $detalleContratoPenalidad['DcpVal'] ?> x K |  <?php echo $detalleContratoPenalidad['PenDes'] ?>
                                                </option>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
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
            </div>
        </form>
    </div>
</section>
<!-- InputMask -->
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () 
{
    $("#PnzCod").inputmask("999-99");     
});
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#grupoPenalidad').change(function () {
            $("#DcpId > option").remove();
            var idGrupoPenalidad = $('#grupoPenalidad').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url().'configuracion/penalidades/obtener_penalidades_x_grupo/' ?>" + idGrupoPenalidad, 

                success: function (penalidades) 
                {
                    $.each(penalidades, function(clave, valor) 
                    {
                        var opt = $('<option />');
                        <?php   if($accion=='nuevo') { ?>
                        <?php foreach ($penalidades as $penalidad) { ?>
                        <?php foreach ($detallesContratoPenalidad as $detalleContratoPenalidad) { ?>
                            if(<?php echo $detalleContratoPenalidad['PenId'] ?>==valor.PenId)
                            {
                               opt.val(<?php echo $detalleContratoPenalidad['DcpId'] ?>);
                               opt.text("<?php echo 'Multa: '.$detalleContratoPenalidad['DcpVal'].' x K | '.$detalleContratoPenalidad['PenDes'] ?>");
                               $('#DcpId').append(opt);
                            }
                            <?php } ?>
                        <?php } }?> 
                    });
                }
            });
        });
    });
</script>