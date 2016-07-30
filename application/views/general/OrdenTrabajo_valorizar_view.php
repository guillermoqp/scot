<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
        <form role="form" accept-charset="utf-8" method="post" action="<?php echo str_replace('/index.php', '', current_url())."/guardar" ; ?>">
            <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Datos de Orden de Trabajo de Toma de Estado de suministros</h3>              
                    </div><!-- /.box-header -->
                    <?php 
                    if(isset($accion))
                    {
                       if($accion == "valorizar")
                       { ?>
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
                                                <input type="text" class="form-control" value="<?php echo (isset($Orden)) ? $Orden['OrtNum'] : ''; ?>" disabled/>
                                                <input name="OrtId" id="OrtId" type="hidden" class="form-control" value="<?php echo $Orden['OrtId']; ?>"/>
                                            </div><!-- /.input group -->
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Valorización:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                            <input type="text" class="form-control" value="<?php echo date("Y-m-d H:i:s"); ?>" disabled/>
                                            <input name="FchVal" type="hidden" class="form-control" value="<?php echo date("Y-m-d H:i:s"); ?>"/>
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div> 
                        </div>
                    </div>
                    <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Metrado Ejecutado de la Orden de Trabajo por Sub Actividad:</h3>                                   
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="valorizaciones" class="table table-bordered" aria-describedby="example1_info">
                            <thead>
                                <tr Class="info" role="row">
                                    <th>Sub Actividad</th>
                                    <th>Metrado</th>
                                </tr>
                            </thead>
                            <tbody class="tbodyP" role="alert">
                                <?php foreach ($subactividades as $subactividad): ?>
                                    <?php foreach ($valorizacionesEdit as $valorizacionEdit){ ?>
                                        <?php  if($valorizacionEdit['OtsSacId']==$subactividad['SacId']){ ?>
                                        <tr>
                                            <td>
                                                <?php echo $subactividad['SacId']; ?> - 
                                                <?php echo $subactividad['SacDes']; ?>    
                                            </td>
                                            <div class="controls">
                                                <td id="<?php echo $subactividad['SacId']; ?>">
                                                    <input id="<?php echo 'val_' . $subactividad['SacId']; ?>" 
                                                           name="<?php echo 'val_' . $subactividad['SacId']; ?>" 
                                                           value="<?php echo $valorizacionEdit['OtsMonEj']; ?>" 
                                                           type="number" max="1500000" min="1000" step="1" required="">
                                                </td>
                                                <p class="help-block"></p>
                                            </div>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>  
            </div><!-- /.box -->
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php if($accion == "valorizar"){ echo 'Guardar Valorizacion de Orden de Trabajo';} ?></button>
                </div>
                <div class="col-md-6">
                    <button type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
                </div>
            </div>
        </form
        <?php   }
        }
        ?>
        </div>
    </div>
</section>
<!-- VALIDACION DE FORMUALRIOS  -->
<script src="<?php echo base_url() ?>frontend/plugins/validation/jqBootstrapValidation.js"></script>

<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

<!-- page script -->
<script type="text/javascript">
    $(function () 
    {
        $("#valorizaciones").dataTable();
    });
</script>
<script>
$(document).ready(function()
{
    $("input,textarea,select").jqBootstrapValidation
    ({
        preventSubmit: true,
        submitError: function($form, event, errors) 
        {
            alert("Porfavor, corrija los errores.");
        },
        filter: function() {
            return $(this).is(":visible");
        }
    });
});
</script> 
