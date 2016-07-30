<section class="content">
   <div class="row">
       <form role="form" method="post">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Datos del Nivel Organizacional</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body">
                        <?php if(!isset($nivel)){ ?>
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Tipo de Organización:</label>
                                    <div class="control-group">
                                    <input name="tipoOrg" type="radio"  id="tipoOrg" required="" value="0"
                                           <?php if(isset($nivel)){echo ($nivel['NvlCst']=='f')?' checked':'';}?>>
                                    Contratante
                                    <input name="tipoOrg" type="radio" id="tipoOrg" required=""  value="1"
                                          <?php if(isset($nivel)){echo ($nivel['NvlCst']=='t')?' checked':'';}?>>
                                    Contratatista
                                    <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>     
                        </div>
                        <?php } ?>
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <input name="descripcion" type="text" class="form-control" placeholder="Operadores" required=""
                                           value="<?php echo (isset($nivel)) ? $nivel['NvlDes'] : set_value('descripcion'); ?>">
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
       </div>
       </form>
    </div>
</section>