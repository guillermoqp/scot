<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (null != $this->session->flashdata('mensaje')) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form role="form" accept-charset="utf-8" method="post" action="<?php echo str_replace('/index.php', '', current_url()); ?>">
                            <div class="form-group">
                                <label>Nombre:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                    <input name="NgrDes" type="text" class="form-control" value="<?php echo $nivelGrupo['NgrDes']; ?>">
                                    <div class="input-group-btn">
                                        <button name="btn_actualizar_nombre_nivel" type="submit" class="btn btn-info btn-flat"><i class="fa fa-refresh"></i> Actualizar</button>
                                        <button style="margin-left: 5px" type="reset" class="btn btn-default btn-flat">Limpiar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form> 
                </div><!-- /.box-body -->
            </div><!-- /.box-body -->
            <div class="box">
                <?php
                $letra = substr($nivelGrupo['NgrDes'], -1);
                if (preg_match('/[aeiouáéíóúü]/i',$letra))
                {
                    $letras = "s";
	        } else if (preg_match('/[a-z]/i',$letra))
		{
	            $letras = "es";
		}
                ?>
                <div class="box-header">
                    <h3 class="box-title"><?php echo $nivelGrupo['NgrDes'].$letras; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="nivelesComerciales" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Código</th>
                                <th>Nombre</th>
                                <th style="width: 210px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gruposPredio as $grupoPredio) { ?>
                                <tr>
                                    <td><?php echo $grupoPredio['GprCod']; ?></td>
                                    <td><?php echo $grupoPredio['GprDes']; ?></td>
                                    <td>
                                        <?php if ($this->acceso_cls->tienePermiso('editar_nivel_comercial')) { ?>
                                            <a href="<?php echo base_url() . 'configuracion/nivel_comercial/subnivel/'.$grupoPredio['GprId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar / Detalle">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.box-body -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Agregar <?php echo $nivelGrupo['NgrDes']; ?> </h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                            <form role="form" accept-charset="utf-8" method="post" action="<?php echo str_replace('/index.php', '', current_url()); ?>">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="control-group">
                                            <label>Código:</label>
                                            <div class="controls">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-file-text"></i>
                                                    </div>
                                                    <input id="GprCod" name="GprCod" type="number" min="0" max="100000" step="1" class="form-control" placeholder="Código del Nuevo <?php echo $nivelGrupo['NgrDes']; ?>" required="">
                                                </div><!-- /.input group -->
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="control-group">
                                            <label>Nombre:</label>
                                            <div class="controls">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-file-text-o"></i>
                                                    </div>
                                                    <input id="GprDes" name="GprDes" type="text" class="form-control" placeholder="Nombre de Nuevo <?php echo $nivelGrupo['NgrDes']; ?>" required="">
                                                </div><!-- /.input group -->
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="control-group">
                                            <label style="height: 15px"></label>
                                            <div class="controls">
                                                <div class="input-group-btn">
                                                    <button type="submit" id="btn_nuevo_nivel_detalle" name="btn_nuevo_nivel_detalle" class="btn btn-primary btn-flat" data-toggle="tooltip" data-placement="bottom" title="Registrar <?php echo $nivelGrupo['NgrDes']; ?>"><i class="fa fa-pencil"></i></button>
                                                    <button type="reset" class="btn btn-default btn-flat">Limpiar</button>
                                                </div><!-- /.input group -->
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            </form>     
                    </div>        
                </div><!-- /.box-body -->
            </div><!-- /.box-body -->
        </div>
    </div>
</section>

<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- page script -->


<script type="text/javascript">
$(document).ready(function ()
{
    $("#nivelesComerciales").dataTable({
        "bSort": false
    });
});
</script>