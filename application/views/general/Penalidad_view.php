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
                    <h3 class="box-title">Datos de la Penalidad</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body">
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Grupo:</label>
                                    <select name="grupo" class="form-control" required="">
                                        <?php foreach ($gruposPenalidad as $grupoPenalidad) { ?>
                                        <option value="<?php echo $grupoPenalidad['GpeId'] ?>"<?php if(isset($penalidad)){ echo ($grupoPenalidad['GpeId']==$penalidad['PenGpeId'])?'selected':'';} ?>><?php echo $grupoPenalidad['GpeCod'] . ' ' . $grupoPenalidad['GpeDes'] ?></option>
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
                                    <label>Código:</label>
                                    <input name="codigo" type="text" class="form-control" placeholder="1.1"
                                           value="<?php echo (isset($penalidad)) ? $penalidad['PenCod'] : set_value('codigo'); ?>" required>
                                    <p class="help-block"></p>
                                </div>
                            </div>     
                        </div>    
                        <div class="row">   
                            <div class="col-md-12">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Ocurrencia:</label>
                                    <textarea name="descripcion" class="form-control" rows="5" required=""><?php echo (isset($penalidad)) ? $penalidad['PenDes'] : set_value('descripcion'); ?></textarea>
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