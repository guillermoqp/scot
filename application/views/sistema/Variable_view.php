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
                    <h3 class="box-title">Datos de la Variable</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body">
                        <div class="row">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <input name="VarDes" type="text" class="form-control" required=""
                                           value="<?php echo (isset($variable)) ? $variable['VarDes'] : set_value('descripcion'); ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Periodo :</label>
                                    <select id="VarPrdId" name="VarPrdId" class="form-control">
                                        <?php foreach ($periodos as $periodo) { ?>
                                            <option value="<?php echo $periodo['PrdId'] ?>" <?php echo (isset($variable)&&$variable['VarPrdId'] == $periodo['PrdId']) ? 'selected' : ''; ?>><?php echo $periodo['PrdCod'].' - '.$periodo['PrdAni'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>    
                        <div class="row">    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Código:</label>
                                    <input name="VarCod" type="text" class="form-control" required=""
                                           value="<?php echo (isset($variable)) ? $variable['VarCod'] : set_value('codigo'); ?>"
                                           <?php echo (isset($variable)) ? 'readonly' : ''; ?>
                                           >
                                    <p class="help-block"></p>
                                </div>
                            </div>   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Valor:</label>
                                    <input name="VarVal" type="text" class="form-control" required=""
                                           value="<?php echo (isset($variable)) ? $variable['VarVal'] : set_value('valor'); ?>">
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