<section class="content">
    <div class="row">
      <div class="col-xs-12">   
        <form role="form" accept-charset="utf-8" method="post" action="<?php echo str_replace('/index.php', '', current_url()); ?>">
        <?php if (isset($_SESSION['mensaje'])) { ?>
            <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $_SESSION['mensaje'][1]; ?>
            </div>
        <?php } ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-file"></i> ORDEN DE TRABAJO N°: <?php echo $OTTomaEstado["OrtNum"]; ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <small class="pull-right">Fecha de Envio: <?php echo date('d-m-Y',  strtotime($OTTomaEstado["OrtFchEn"]))?></small>
                            <img style="width: 210px; height: 50px; background-color: #FFF" src="<?php echo base_url().$userdata['contratante']['CntLog1']; ?>" alt="<?php echo $userdata['contratante']['EmpRaz']?>">
                            <br><hr style="margin-top:10px;margin-bottom: 10px;">
                            <p style="margin: 0px;"><b><?php echo $userdata['contratante']['EmpRaz']; ?></b> - <b> RUC: </b> <span><?php echo $userdata['contratante']['EmpRuc'];  ?></span>- <b>Dirección:</b> <?php echo $userdata['contratante']['EmpDir']; ?></p> 
                            <p style="margin: 0px;"><b>E-mail:</b><?php echo $userdata['contratante']['EmpCor']; ?> <b>Teléfono: </b><?php echo $userdata['contratante']['EmpTel']; ?>
                            <b>Representante:</b> <?php echo $userdata['contratante']['EmpNomRp']; ?> <b>D.N.I:</b> <?php echo $userdata['contratante']['EmpDniRp']; ?></p>
                        </div>
                    </div>
                    <hr style="margin-top:10px;margin-bottom: 10px;">
                    <!-- info row -->
                    <div class="row">
                        <div class="col-sm-4">
                            <b>De:</b>
                            <address>
                             <i><?php echo $UsrEn['UsrNomPr'] . ' ' . $UsrEn['UsrApePt']. ' ' . $UsrEn['UsrApeMt']; ?></i><br>
                             <b>Contratante:</b> <?php echo $UsrEn['EmpRaz']; ?><br>
                             <b>Cargo:</b> <?php echo $UsrEn['RolDes']; ?><br>
                             <b>Teléfono:</b> <?php echo $UsrEn['UsrTel']; ?><br>
                             <b>E-mail:</b> <?php echo $UsrEn['UsrCor']; ?>
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <b>Para:</b>
                            <address>
                             <i><?php echo $UsrRs['UsrNomPr'] . ' ' . $UsrRs['UsrApePt']. ' ' . $UsrRs['UsrApeMt']; ?></i><br>
                             <b>Contratista:</b> <?php echo $UsrRs['CstRaz']; ?><br>
                             <b>Cargo:</b> <?php echo $UsrRs['RolDes']; ?><br>
                             <b>Teléfono:</b> <?php echo $UsrRs['UsrTel']; ?><br>
                             <b>E-mail:</b> <?php echo $UsrRs['UsrCor']; ?>
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <b><i class="fa fa-calendar"></i> Fecha Ejecución:</b> <?php echo date('d-m-Y',  strtotime($OTTomaEstado["OrtFchEj"]))?> <br>
                            <b><i class="fa fa-calendar"></i> Fecha Máxima de Recepción:</b> <?php echo date('d-m-Y H:i:s',  strtotime($OTTomaEstado["OrtFchEm"]))?> <br>
                            <b><i class="fa fa-calendar"></i> Mes de Facturación:</b> <?php echo $OTTomaEstado['periodo']['PrdCod'].' / '.$OTTomaEstado['periodo']['PrdAni'] ?><br>
                            <b>Descripción del Trabajo:</b> <?php echo $OTTomaEstado['OrtDes']; ?><br>
                            <b>Actividad:</b> <?php echo $proceso; ?>  <br>
                            <b>Sub Actividades:</b>
                        </div>
                    </div>
                    <hr style="margin-top:10px;margin-bottom: 10px;">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="box-title"><?php echo $NivelGrupo['NgrDes']; ?>   :  <?php echo $cicloOrden['GprCod']; ?> | <?php echo $SubNivel['NgrDes']; ?>   :  <?php echo implode(",", array_column($Subciclos, 'GprCod')) ?></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Seleccione Tipo de Busqueda de Conexiones: </label>
                            <div class="form-group">
                                <div class="control-group">
                                    <input id="CodFc" name="tipoBusqueda" type="radio" class="flat-red" value="CodFc" required>Codigo Facturación
                                    <input id="nombres" name="tipoBusqueda" type="radio" class="flat-red" value="nombres" selected="" required>Nombres
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="campoBusqueda" id="campoBusqueda" class="form-control"/>
                                    <div class="input-group-btn">
                                        <button name="enviar" id="boton_buscar" class="btn btn-disabled btn-flat" type="submit"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>    
                       </div>
                    </div>
                    <?php if(isset($resultados)) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Seleccione Conexiones: </label>
                            <div class="table-responsive">
                                <table id="tablaBusqueda" class="table table-bordered table-striped">
                                    <thead>
                                        <tr Class="info"  role="row">
                                            <th>Código Fac.</th>
                                            <th>Nombre/Raz. Social</th>
                                            <th>Urbanización</th>
                                            <th>Calle</th>
                                            <th>Numero</th>
                                            <th>Medidor</th>
                                            <th>Seleccionar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($resultados as $resultado) { ?>
                                            <tr>
                                                <td><?php echo $resultado['LecCodFc']; ?></td>
                                                <td><?php echo $resultado['LecNom']; ?></td>
                                                <td><?php echo $resultado['LecUrb']; ?></td>
                                                <td><?php echo $resultado['LecCal']; ?></td>
                                                <td><?php echo $resultado['LecMun']; ?></td>
                                                <td><?php echo $resultado['LecMed']; ?></td>
                                                <td>
                                                   <input id="idLecturas" name="idLecturas[]" type="checkbox" class="flat-red idLecturas" id="<?php echo $resultado['LecId']; ?>" value="<?php echo $resultado['LecId']; ?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if(!empty($resultados)) { ?>
                    <div class="row">
                        <div class="col-md-4">
                            <button id="agregar_lista" name="agregar_lista" type="submit" class="btn btn-disabled btn-flat"><i class="fa fa-save"></i> Añadir Seleccionados a la Lista</button>
                        </div>
                    </div> 
                    <?php } ?>
                <?php } ?>
                <?php if(isset($_SESSION['listaAtipicos']) && !empty($_SESSION['listaAtipicos'])) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="box-title">Lista de Lecturas Atipicas de la Orden de Trabajo N° <?php echo $OTTomaEstado["OrtNum"]; ?></h4>
                        <div class="table-responsive">
                            <table id="listaAtipicos" class="table table-bordered table-striped">
                                <thead>
                                    <tr Class="info" role="row">
                                        <th>N°</th>
                                        <th>#Referencia ID</th>
                                        <th>Código</th>
                                        <th>Nombre/Raz. Social</th>
                                        <th>Urbanización</th>
                                        <th>Calle</th>
                                        <th>Num. Munic.</th>
                                        <th>Medidor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for ($i = 0; $i < count($_SESSION['listaAtipicos']); $i++) 
                                    { 
                                        $atipico = $_SESSION['listaAtipicos'][$i]; ?>
                                    <tr role="row">  
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $atipico['LecId']; ?></td>
                                        <td><?php echo $atipico['LecCodFc']; ?></td>
                                        <td><?php echo $atipico['LecNom']; ?></td>
                                        <td><?php echo $atipico['LecUrb']; ?></td>
                                        <td><?php echo $atipico['LecCal']; ?></td>
                                        <td><?php echo $atipico['LecMun']; ?></td>
                                        <td><?php echo $atipico['LecMed']; ?></td>
                                    </tr>    
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?>
                </div>
                <hr style="margin-top:10px;margin-bottom: 10px;">
                <div class="row">
                    <div class="col-md-6">
                        <button id="guardar_lista_atipicos" name="guardar_lista_atipicos" type="submit" class="btn btn-block btn-primary btn-flat"<?php echo empty($_SESSION['listaAtipicos']) ? 'disabled' : ''; ?>><i class="fa fa-pencil"></i> Guardar Lista de Atipicos</button>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo base_url() ?>toma_estado/orden/<?php echo $OTTomaEstado["OrtId"]; ?>/atipicos/limpiar" type="reset" class="btn btn-block btn-default btn-flat">Limpiar Lista de Atipicos</a>
                    </div>
                </div>
            </div><!-- /.box -->
        </form>
      </div>
    </div>
</section>
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/iCheck/all.css">
<script src="<?php echo base_url() ?>frontend/plugins/iCheck/icheck.min.js"></script>

<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function ()
{
    $('#boton_buscar').prop('disabled', 'disabled');
    $('#boton_buscar').attr('class', "btn btn-info btn-flat disabled");
    $('#campoBusqueda').on('keyup blur', function () { 
        if ($("#campoBusqueda").val()!='') {                 
            $('#boton_buscar').prop('disabled', false);  
            $('#boton_buscar').attr('class', "btn btn-info btn-flat"); 
        } else {
            $('#boton_buscar').prop('disabled', 'disabled');
            $('#boton_buscar').attr('class', "btn btn-info btn-flat disabled");
        }
    });
   
    $('#agregar_lista').prop('disabled', 'disabled');    
    $('#agregar_lista').attr('class', "btn btn-info btn-flat disabled");
    $('.idLecturas').on('ifChecked', function(event)
    {
        $('#agregar_lista').prop('disabled', false); 
        $('#agregar_lista').attr('class', "btn btn-info btn-flat"); 
    });
    
    $('.idLecturas').on('ifUnchecked', function(event)
    {
        if($("input:checkbox:checked").length<=0)
        {
            $('#agregar_lista').prop('disabled', 'disabled');
            $('#agregar_lista').attr('class', "btn btn-info btn-flat disabled");
        }
    }); 

    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });
    $("#nombres").iCheck('check');
    
    $("#tablaBusqueda").dataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "bSort": false
    });
    
    $("#listaAtipicos").dataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "bSort": false
    });
});
</script>

