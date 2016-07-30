<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
        <form role="form" method="post">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Datos del Grupo de Penalidad</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body">
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Código:</label>
                                    <input name="codigo" type="text" class="form-control" placeholder="1" required=""
                                           value="<?php echo (isset($grpPenalidad)) ? $grpPenalidad['GpeCod'] : set_value('codigo'); ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <input name="descripcion" type="text" class="form-control" placeholder="Herramientas" required=""
                                           value="<?php echo (isset($grpPenalidad)) ? $grpPenalidad['GpeDes'] : set_value('descripcion'); ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>    
                        </div>
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
        </form>
       </div>
    </div>

</section>