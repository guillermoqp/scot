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
                 <div class="box-header">
                     <h3 class="box-title">Datos de <?php echo $nivelGrupo['NgrDes'].' '.$grupoPredio['GprDes']; ?> 
                        <?php if(empty($SubNivel)){ ?>
                            <small style="color: #f00;">* Pertenece al Último Nivel Comercial.</small>
                        <?php } ?>
                     </h3>
                 </div><!-- /.box-header -->
                <div class="box-body">
                    <form role="form" accept-charset="utf-8" method="post" action="<?php echo str_replace('/index.php', '', current_url()); ?>">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>Código:</label>
                                        <div class="controls">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                </div>
                                                <input name="GprCod" type="number" min="0" max="100000" step="1" class="form-control" value="<?php echo $grupoPredio['GprCod']; ?>">
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
                                                <input name="GprDes" type="text" class="form-control" value="<?php echo $grupoPredio['GprDes']; ?>">
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
                                                <button type="submit" id="btn_actualizar_subnivel" name="btn_actualizar_subnivel" class="btn btn-info btn-flat"><i class="fa fa-refresh"></i> Actualizar</button>
                                                <button style="margin-left: 5px" type="reset" class="btn btn-default btn-flat">Limpiar</button>
                                            </div><!-- /.input group -->
                                        </div>
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </form>   
                </div>
            </div>
            <?php
                if(empty($SubNivel)&&isset($predios))
                { ?>
                    <div class="table-responsive">
                                <table id="predios" class="table table-bordered table-striped">
                                    <thead>
                                        <tr Class="info"  role="row">
                                            <th>Código Fac.</th>
                                            <th>Nombre/Raz. Social</th>
                                            <th>Urbanización</th>
                                            <th>Calle</th>
                                            <th>Numero</th>
                                            <th>Medidor</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($predios as $predio) { ?>
                                            <tr>
                                                <td><?php echo $predio['ProCodFc']; ?></td>
                                                <td><?php echo $predio['ProNom']; ?></td>
                                                <td><?php echo $predio['UrbDes']; ?></td>
                                                <td><?php echo $predio['CalDes']; ?></td>
                                                <td><?php echo $predio['PreNumMc']; ?></td>
                                                <td><?php echo $predio['MedNum']; ?></td>
                                                <td>
                                                   <span data-toggle="tooltip" data-placement="bottom" title="Detalle"><a href="#" id="detalle" name="detalle" role="button" class="btn btn-default btn-flat"><i class="fa fa-eye"></i></a></span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
               <?php } 
                else{
                    $letra = substr($SubNivel['NgrDes'], -1);
                    if (preg_match('/[aeiouáéíóúü]/i',$letra)){$letras = "s";}
                    else if (preg_match('/[a-z]/i',$letra)){$letras = "es";}
                    $Subnivel = $SubNivel['NgrDes'].$letras;?>
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"><?php echo $Subnivel; ?> Registrados en <?php echo $nivelGrupo['NgrDes'].' '.$grupoPredio['GprDes']; ?></h3>                                   
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          <h4>Asignados</h4>
                          <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr Class="info" role="row">
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th style="width: 210px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subNivelesAsignados as $subNivelAsignado) { ?>
                                        <tr>
                                            <td><?php echo $subNivelAsignado['GprCod']; ?></td>
                                            <td><?php echo $subNivelAsignado['GprDes']; ?></td>
                                            <td>
                                                <?php if ($this->acceso_cls->tienePermiso('editar_nivel_comercial')) { ?>
                                                    <a href="<?php echo base_url() . 'configuracion/nivel_comercial/subnivel/'.$grupoPredio['GprId'].'/desagrupar/'.$subNivelAsignado['GprId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Quitar del <?php echo $nivelGrupo['NgrDes']; ?>">
                                                        <i class="fa fa-minus"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                          </div>
                          <h4>No Asignados</h4>
                          <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr Class="info" role="row">
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th style="width: 210px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subNivelesNoAsignados as $subNivelNoAsignado) { ?>
                                        <tr>
                                            <td><?php echo $subNivelNoAsignado['GprCod']; ?></td>
                                            <td><?php echo $subNivelNoAsignado['GprDes']; ?></td>
                                            <td>
                                                <?php if ($this->acceso_cls->tienePermiso('editar_nivel_comercial')) { ?>
                                                    <a href="<?php echo base_url() . 'configuracion/nivel_comercial/subnivel/'.$grupoPredio['GprId'].'/agrupar/'.$subNivelNoAsignado['GprId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Agregar al <?php echo $nivelGrupo['NgrDes']; ?>">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                          </div>
                        </div>
                    </div><!-- /.box-body -->
                <?php } ?>
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
    
    $("#predios").dataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "bSort": false
    });
    
});
</script>