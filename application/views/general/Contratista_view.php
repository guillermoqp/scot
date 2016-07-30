<section class="content">
    <div class="row">
        <div class="col-xs-12">
        <?php if(isset($_SESSION['mensaje'])){ ?>
        <div class="alert alert-<?php echo ($_SESSION['mensaje'][0]=='error')? 'danger' : 'success'; ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $_SESSION['mensaje'][1] ;?>
        </div>
        <?php } ?>
    <form name="fcontratista" role="form" enctype="multipart/form-data" method="post" action="<?php echo str_replace('/index.php', '', current_url())."/guardar" ;?>">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Datos de Contratista / Consorcio</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Seleccione el Tipo de Contratista:</label>
                        <div class="form-group">
                            <div class="control-group"> 
                                <input id="individual" name="CstCio" type="radio" required value="individual" 
                                    <?php if(isset($contratistaEdit)){ echo ($contratistaEdit['CstCio']=='f') ? 'checked' : '';} ?>>Individual
                                    <input id="consorcio" name="CstCio" type="radio" required value="consorcio" 
                                    <?php 
                                    if(isset($contratistaEdit))
                                    { 
                                        echo ($contratistaEdit['CstCio']=='t') ? 'checked' : '';
                                    }
                                    if(count($_SESSION['empresas'])>0)
                                    {
                                            echo 'checked';    
                                    }
                                    ?>> Consorcio              
                            </div>
                        </div>  
                    </div>
                </div> 
                <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                    <label>RUC:</label>
                                    <div class="input-group">
                                        <input type="text" name="ruc" id="ruc" class="form-control" data-inputmask='"mask": "99999999999"' value="<?php echo (isset($contratistaEdit)) ? $contratistaEdit['CstRuc'] : set_value('ruc'); ?>" data-toggle="tooltip" data-placement="bottom" title="*Ingrese un RUC Para validar que no sea una contratista ya registrada" data-mask required/>
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-info btn-flat" id="buscarRuc"><i class="fa fa-search"></i></button>
                                        </div><!-- /btn-group -->
                                    </div><!-- /input-group -->
                            </div>    
                        </div>
                        <div class="col-md-9">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Razón Social:</label>
                                    <input type="text" name="razSocial" id="razSocial" class="form-control" value="<?php echo (isset($contratistaEdit)) ? $contratistaEdit['CstRaz'] : set_value('razSocial'); ?>" required/>
                                </div>
                        </div>       
                    </div>
                <div class="row">   
                        <div class="col-md-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Dirección:</label>
                                <input type="text" name="direccion" id="direccion" class="form-control" value="<?php echo (isset($contratistaEdit)) ? $contratistaEdit['CstDir'] : set_value('direccion'); ?>" required/>
                            </div>
                        </div>   
                        <div class="col-md-3">
                            <!-- text input -->
                            <div class="form-group">
                                <div class="control-group">
                                    <label>Teléfono 1:</label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input type="text" name="tel11" id="tel11" class="form-control" data-inputmask='"mask": "(999) 99-99-99"' data-mask value="<?php echo (isset($contratistaEdit)) ? $contratistaEdit['CstTel1'] : set_value('tel1'); ?>" required/>
                                        </div><!-- /.input group -->
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <!-- text input -->
                            <div class="form-group">
                                <div class="control-group">
                                    <label>Teléfono 2:</label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input type="text" name="tel12" id="tel12" class="form-control" data-inputmask='"mask": "(999) 99-99-99"' data-mask value="<?php echo (isset($contratistaEdit)) ? $contratistaEdit['CstTel2'] : set_value('tel2'); ?>" required/>
                                        </div><!-- /.input group -->
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                        </div> 
                </div>
                <div class="row"> 
                    <div class="col-md-6">
                        <!-- text input -->
                        <div class="form-group">
                            <div class="control-group">
                                <label>E-mail 1:</label>
                                <div class="controls">
                                    <div class="controls">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </div>
                                            <input type="email" name="email11" id="email11" class="form-control" data-inputmask='alias: "email"' data-mask value="<?php echo (isset($contratistaEdit)) ? $contratistaEdit['CstCor1'] : set_value('email11'); ?>" required/>
                                        </div>
                                        <p class="help-block"></p>
                                    </div>
                                    </div>

                                </div>
                        </div>
                    </div>  
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="control-group">
                                <label>E-mail 2:</label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </div>
                                            <input type="email" name="email12" id="email12" class="form-control" data-inputmask='alias: "email"' data-mask value="<?php echo (isset($contratistaEdit)) ? $contratistaEdit['CstCor2'] : set_value('email12'); ?>" required/>
                                        </div>
                                        <p class="help-block"></p>
                                    </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Datos del Representante Legal</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row"> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="control-group">
                                <label>DNI:</label>
                                <div class="controls">
                                    <input name="dni1" id="dni1" type="text" class="form-control" data-inputmask='"mask": "99999999"' data-mask value="<?php echo (isset($contratistaEdit)) ? $contratistaEdit['CstDniRp'] : set_value('dni1'); ?>" required/>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                        </div>    
                    </div> 
                    <div class="col-md-6">
                        <!-- text input -->
                        <div class="form-group">
                            <div class="control-group">
                                <label>Nombres y Apellidos:</label>
                                <div class="controls">
                                   <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input name="nombres1" id="nombres1" type="text" class="form-control" value="<?php echo (isset($contratistaEdit)) ? $contratistaEdit['CstNomRp'] : set_value('nombres1'); ?>" required>
                                    </div>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                        </div>
                    </div>      
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Logos para Formatos Impresos</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row"> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="control-group">
                                <label for="exampleInputFile">Logo 1:</label><br>
                                    <?php if(isset($contratistaEdit)){ ?>
                                        <img src="<?php echo base_url().$contratistaEdit['CstLog1']; ?>" alt="Logo Contratante" width="250" height="80"/><br><br>
                                        <!--<p> Url: <code><?php echo base_url().$contratistaEdit['CstLog1']; ?></code> </p>-->
                                    <?php } ?>
                                
                                <input type="file" name="log1" id="log1" <?php echo (isset($contratistaEdit)) ? '' : 'required'; ?>>   
                                <input type="hidden" name="log1Oculto" id="log1Oculto" value="<?php echo (isset($contratistaEdit)) ? $contratistaEdit['CstLog1'] : set_value('log1Oculto'); ?>">
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="control-group">
                                <label for="exampleInputFile">Logo 2:</label><br>
                                <?php if(isset($contratistaEdit)){ ?>
                                        <img src="<?php echo base_url().$contratistaEdit['CstLog2']; ?>" alt="Logo Contratante" width="250" height="80"/><br><br>
                                        <!--<p> Url: <code><?php echo base_url().$contratistaEdit['CstLog2']; ?></code> </p>-->
                                <?php } ?>
                                <input type="file" name="log2" id="log2">   
                                <input type="hidden" name="log2Oculto" id="log2Oculto" value="<?php echo (isset($contratistaEdit)) ? $contratistaEdit['CstLog2'] : set_value('log2Oculto'); ?>">
                            </div>
                        </div>    
                    </div>      
                </div>
            </div>
        </div>
        <div id='show-me' style='display:none'>
            <p><b>* Recordar que no podra eliminar las empresas consorciadas a una contratista hasta que no sean guardadas.</b></p>
            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <a class="btn btn-info btn-flat" data-toggle="modal" data-target="#registro-consorciado"><i class="fa fa-pencil"></i> Agregar Consorciados</a>
                                    </div>
                                </div>
                            </div> 
                        </div>
            <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Consorciados registrados</h3>              
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="consorciados" class="table table-bordered table-striped">          
                            <thead>
                                <tr Class="info">
                                    <th>RUC</th>
                                    <th>Razón Social</th>
                                    <th>% Participación</th>
                                    <th>Rrepresentante Legal</th>
                                    <th>E-mail</th>
                                    <th>Teléfono</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php if(isset($_SESSION['empresas'])){ ?>
                                <?php foreach ($_SESSION['empresas'] as $empresa): ?>
                                    <tr>                                 
                                        <td><?php echo $empresa['ruc']; ?></td>
                                        <td><?php echo $empresa['razSocial']; ?></td>
                                        <td><?php echo $empresa['porcentaje']; ?></td>
                                        <td><?php echo $empresa['repLegalNomb']; ?></td>
                                        <td><?php echo $empresa['email']; ?></td>
                                        <td><?php echo $empresa['tel']; ?></td>
                                        <td>
                                            <span data-toggle="tooltip" data-placement="bottom" title="Editar">
                                            <a id="editarConsorciado" data-toggle="modal" data-target="#registro-consorciado" role="button" empresa="<?php echo $empresa['ruc']; ?>" class="btn btn-default btn-flat disabled">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            </span>
                                            <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                            <a href="#modal-eliminar" role="button" data-toggle="modal" empresa="<?php echo $empresa['ruc']; ?>" class="btn btn-default btn-flat disabled">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                            </span>    
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php }?>
                            <?php if(isset($empresasId) && isset($contratistaEdit)){ ?>
                                <?php foreach ($empresasId as $empresa): ?>
                                    <tr>                                 
                                        <td><?php echo $empresa['EmpRuc']; ?></td>
                                        <td><?php echo $empresa['EmpRaz']; ?></td>
                                        <td><?php echo $empresa['DcoPrt']; ?></td>
                                        <td><?php echo $empresa['EmpNomRp']; ?></td>
                                        <td><?php echo $empresa['EmpCor']; ?></td>
                                        <td><?php echo $empresa['EmpTel']; ?></td>
                                        <td>
                                            <span data-toggle="tooltip" data-placement="bottom" title="Editar">
                                            <a id="editarConsorciado" data-toggle="modal" data-target="#registro-consorciado" role="button" empresa="<?php echo $empresa['EmpId']; ?>" class="btn btn-default btn-flat"
                                               ><i class="fa fa-pencil"></i>
                                            </a>
                                            </span>
                                            <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                            <a href="#modal-eliminar" role="button" data-toggle="modal" empresa="<?php echo $empresa['EmpId']; ?>" class="btn btn-default btn-flat">
                                                <i class="fa fa-trash-o"></i>
                                            </a> 
                                            </span>
                                        </td>
                                    </tr>
                                    <?php 
                                    $empresaSesion = array("ruc" => $empresa['EmpRuc'],"razSocial" => $empresa['EmpRaz'],"direccion" => $empresa['EmpDir'],"tel" => $empresa['EmpTel'],"email" => $empresa['EmpCor'],"repLegalDni" => $empresa['EmpNomRp'],"repLegalNomb" =>$empresa['EmpNomRp'] ,"porcentaje" => $empresa['DcoPrt']);
                                    array_push($_SESSION['empresas'] , $empresaSesion); ?>
                                <?php endforeach; ?>
                            </tbody>
                            <?php }?>
                        </table>
                    </div> 
                </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <button id="botonRegistrarContratista" type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo (!isset($contratistaEdit))? 'Registrar' : 'Actualizar' ;?></button>
            </div>
            <div class="col-md-6">
                <button id="botonLimpiarContratista" type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
            </div>
        </div>  
        </form>
        </div>
    </div>
</section>
<!-- MODAL :  Modal : Eliminar Empresa Consorciada    -->
    <div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
        <form id="idelim" method="post">    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-trash-o"></i> Eliminar Empresa</h4>
            </div>
            <div class="modal-body">
                <h5 style="text-align: center">¿Realmente deseas Eliminar Empresa Consorciada y los datos asociados con ella?</h5>
            </div>     
            <div class="modal-footer">
                <button class="btn btn-default btn-flat" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                <button type="submit" class="btn btn-danger btn-flat">Eliminar</button>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </form>
    </div><!-- /.modal -->
<script type="text/javascript">
$(document).ready(function()
{
   $(document).on('click', 'a', function(event) 
   {
        var empresa = $(this).attr('empresa');
        <?php 
        $url = "#";
        if(isset($contratistaEdit)) 
        {
            $idContratista = $contratistaEdit['CstId'];
            //echo "Id contratista : ".$idContratista;
            $url = base_url()."consorciado/eliminar/".$idContratista."/";
        }
        ?>
        $('#idelim').attr('action',"<?php echo $url; ?>"+empresa);
    });
});
</script>


<!-- MODAL :  Modal : Registro Consorciado  -->
<div class="modal fade" id="registro-consorciado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                
                <h4 class="modal-title"><i class="fa fa-pencil"></i> Registro Consorciado</h4>
            </div>
        <form role="form" method="post" action="
        <?php 
        if(isset($contratistaEdit))
        {
           echo base_url().'/contratista/editar/'.$contratistaEdit['CstId'].'/empresas';      
        }
        else
        {
            echo base_url().'contratista/nuevo/empresas'; 
        }
        ?>">
                <div class="modal-body">
                    <div class="row">   
                        <div class="col-md-6">
                            <label>RUC:</label>   
                            <div class="input-group">
                                <input name="ruc2" id="ruc2" type="text" class="form-control" required/>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" id="buscarRuc2"><i class="fa fa-search"></i></button>
                                </div><!-- /btn-group -->
                            </div><!-- /input-group -->
                        </div> 
                        <div class="col-md-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Razón Social:</label>
                                <input name="razSocial2" id="razSocial2" type="text" class="form-control" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-12"> 
                            <div class="form-group">
                                <label>Dirección:</label>
                                <input name="direccion2" id="direccion2" type="text" class="form-control" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>E-mail:</label>
                                        <div class="controls">
                                            <div class="controls">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-envelope"></i>
                                                    </div>
                                                    <input name="email2" id="email2" type="email" class="form-control" data-inputmask='alias: "email"' data-mask required/>
                                                </div>
                                                <p class="help-block"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                            <!-- text input -->
                            <div class="form-group">
                                <div class="control-group">
                                    <label>Teléfono:</label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input name="tel2" id="tel2" type="text" class="form-control" required/>
                                        </div><!-- /.input group -->
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                              </div>
                        </div> 
                        </div>
                    <div class="row"> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="control-group">
                                            <label>DNI Representante Legal:</label>
                                            <div class="controls">
                                                <input name="rldni" id="rldni2" type="text" class="form-control" required/>
                                                <p class="help-block"></p>
                                            </div>
                                        </div>
                                    </div>    
                                </div> 
                                <div class="col-md-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <div class="control-group">
                                            <label>Nombres y Apellidos Representante Legal:</label>
                                            <div class="controls">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-user"></i>
                                                    </div>
                                                    <input name="rlnombres" id="rlnombres2" type="text" class="form-control" required>
                                                </div>
                                                <p class="help-block"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>      
                            </div>
                    <div class="row"> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="control-group">
                                        <label>% Participación de la Empresa Consorciada:</label>
                                        <input name="porcentaje" type="text" id="porcentaje" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                    <div class="modal-footer clearfix">
                        <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-paste "></i> Registrar Consorciado</button>
                        <button type="reset" class="btn btn-danger btn-flat"><i class="fa fa-times"></i> Limpiar</button>
                    </div> 
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<!-- InputMask -->
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>

<!-- VALIDACION DE FORMUALRIOS  -->
<script src="<?php echo base_url() ?>frontend/plugins/validation/jqBootstrapValidation.js"></script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

<!-- sweetAlert. -->
<link rel="stylesheet" href="<?php echo base_url()?>frontend/plugins/sweetalert/sweetalert2.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url()?>frontend/plugins/sweetalert/sweetalert2.min.js"></script> 

<!-- page script -->
<script type="text/javascript">
    $(function () 
    {
        $("#consorciados").dataTable();
    });
</script>

<script>
$(document).ready(function()
{
    $('[data-toggle="tooltip"]').tooltip();
        
    $('#consorciados-registrados').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false
    });

        /*  FORMULARIO PRINCIPAL */
        $("#ruc").inputmask("999999999999");
        $("#tel11").inputmask("(999) 99-99-99");
        $("#tel12").inputmask("(999) 99-99-99");
        $("#dni1").inputmask("99999999");

        /*  FORMULARIO MODAL  */
        $("#ruc2").inputmask("999999999999");
        $("#rldni2").inputmask("99999999");  
        $("#tel2").inputmask("(999) 99-99-99");
        $("#porcentaje").inputmask("99");
        
        $('#botonLimpiarContratista').click(function () 
        {
            $('#botonRegistrarContratista').prop('disabled',false);
        });
        
        /* NUEVA CONTRATISTA    */
        $('input[name=CstCio]').click(function () 
        {
                if (this.id == "consorcio") {
                    $("#show-me").show('slow');
                } else {
                    $("#show-me").hide('slow');
                }
        });
        /* EDITAR CONTRATISTA    */
        if($("#consorcio").is(':checked')) 
        {
           $("#show-me").show('slow');
           $("#individual").attr('disabled',true);
        }
        if($("#individual").is(':checked')) 
        {
           $("#consorcio").attr('disabled',true);
        }

        /* BUSCAR POR RUC SOLO CONTRATISTAS */   
        $("#buscarRuc").click( function()
          {         
            var ruc = $('#ruc').val();
            if($("#individual").is(':checked')||$("#consorcio").is(':checked')) 
            {
            /* 
             * VALIDAR QUE EL RUC QUE SE INGRESE DE LA EMPRESA A REGISTRAR COMO CONTRATISTA NO PERTENEZCA A UN CONSORCIO
              VALIDAR QUE EL RUC QUE SE INGRESE DE LA EMPRESA INDIVIDUAL A REGISTRAR COMO CONTRATISTA NO PERTENESCA A UNA CONTRATANTE
            */
            $.ajax({   
               url: "<?php echo base_url().'contratista/validar_empresa_libre';?>",
               type: "POST",
               data: ({ruc: ruc }),
               dataType: "html", 
                success: function(data) 
                {
                    var resultado = JSON.parse(data);
                    
                    if(resultado['result']==false)
                    {
                        swal("Error", "La empresa es una Contratista, es parte de un Consorcio o es una empresa Contratante (EPS). No puede ser registrada como Contratista.","error")
                        $('#razSocial').val("");
                        $('#direccion').val("");
                        $('#tel11').val("");
                        $('#tel12').val("");
                        $('#email11').val("");
                        $('#email12').val("");
                        $('#nombres1').val("");
                        $('#dni1').val("");
                        $('#botonRegistrarContratista').prop('disabled',true);
                    }
                    
                    
                    if(resultado['result']==true) 
                    {
                        swal("Exito", "La Empresa no ha sido registrada, puede registrarla y hacerla nueva Contratista.","success")
                        $('#razSocial').val("");
                        $('#direccion').val("");
                        $('#tel11').val("");
                        $('#tel12').val("");
                        $('#email11').val("");
                        $('#email12').val("");
                        $('#nombres1').val("");
                        $('#dni1').val("");
                        $('#botonRegistrarContratista').prop('disabled',false);
                    }
                    
                    if((resultado['result']==true)&&(resultado['empresa']!=null))
                    {
                        swal("Exito", "La Empresa esta registrada, puede registrarla como Contratista.","success")
                        console.log(data)
                        //var empresa = JSON.parse(resultado['empresa']);
                        //console.log(empresa);
                        $('#razSocial').val(resultado['empresa']['EmpRaz']);
                        $('#direccion').val(resultado['empresa']['EmpDir']);
                        $('#tel11').val(resultado['empresa']['EmpTel']);
                        $('#email11').val(resultado['empresa']['EmpCor']);
                        $('#nombres1').val(resultado['empresa']['EmpNomRp']);
                        $('#dni1').val(resultado['empresa']['EmpDniRp']);
                        $('#botonRegistrarContratista').prop('disabled',false);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) 
                {
                    alert(xhr.status);
                    alert(thrownError);
                }
                })  
               /*
               $.ajax({   
               url: "<?php echo base_url().'contratista/get_empresa_x_ruc_json';?>",
               type: "POST",
               data: ({ruc: ruc }),
               dataType: "html", 
                success: function(data) 
                {
                    if (JSON.parse(data)==null) 
                    {
                        $('#modal-error-ruc-empresa').modal('show'); 
                        $('#razSocial').val("");
                        $('#direccion').val("");
                        $('#tel11').val("");
                        $('#tel12').val("");
                        $('#email11').val("");
                        $('#email12').val("");
                        $('#nombres1').val("");
                        $('#dni1').val("");
                    }
                    else
                    {
                        var empresa = JSON.parse(data);
                        console.log(empresa);

                        $('#ruc').val(empresa['EmpRuc']);
                        $('#razSocial').val(empresa['EmpRaz']);
                        $('#direccion').val(empresa['EmpDir']);
                        $('#tel11').val(empresa['EmpTel']);
                        $('#email11').val(empresa['EmpCor']);
                        $('#nombres1').val(empresa['EmpNomRp']);
                        $('#dni1').val(empresa['EmpDniRp']);                      
                    }  
                },
                error: function (xhr, ajaxOptions, thrownError) 
                {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })
            */
            }
            else
            {
            $.ajax({   
               url: "<?php echo base_url().'contratista/get_contratista_x_ruc_json';?>",
               type: "POST",
               data: ({ruc: ruc }),
               dataType: "html", 
                success: function(data) 
                {
                    if (JSON.parse(data)==null) 
                    {
                        
                        $('#botonRegistrarContratista').prop('disabled',false);
                        swal("EXITO", "El ruc ingresado no esta registrado en Contratistas, puede registrarla como contratista","success")
                        $('#razSocial').val("");
                        $('#direccion').val("");
                        $('#tel11').val("");
                        $('#tel12').val("");
                        $('#email11').val("");
                        $('#email12').val("");
                        $('#nombres1').val("");
                        $('#dni1').val("");
                    }
                    else
                    {
                        var contratista = JSON.parse(data);
                        console.log(contratista);
                        $('#ruc').val(contratista['CstRuc']);
                        $('#razSocial').val(contratista['CstRaz']);
                        $('#direccion').val(contratista['CstDir']);
                        $('#tel11').val(contratista['CstTel1']);
                        $('#tel12').val(contratista['CstTel2']);
                        $('#email11').val(contratista['CstCor1']);
                        $('#email12').val(contratista['CstCor2']);

                        $('#nombres1').val(contratista['CstNomRp']);
                        $('#dni1').val(contratista['CstDniRp']);
                        
                        swal("ERROR", "El ruc ingresado ya pertenece a una Contratista","error")
                        $('#botonRegistrarContratista').prop('disabled',true);
                        
                    }  
                },
                error: function (xhr, ajaxOptions, thrownError) 
                {
                    alert(xhr.status);
                    alert(thrownError);
                }
            }) 
            }
        });





        /* MODAL : BUSCAR POR RUC EN EMPRESA */
        $("#buscarRuc2").click( function()
        {         
            var ruc2 = $('#ruc2').val();
            $.ajax({   
               url: "<?php echo base_url().'contratista/get_empresa_x_ruc_json';?>",
               type: "POST",
               data: ({ruc: ruc2 }),
               dataType: "html", 
                success: function(data) 
                {
                    if (JSON.parse(data)==null) 
                    {
                        $('#modal-error-ruc-empresa').modal('show'); 

                        $('#ruc2').val("");
                        $('#razSocial2').val("");
                        $('#direccion2').val("");
                        $('#tel2').val("");
                        $('#email2').val("");
                        $('#rlnombres2').val("");
                        $('#rldni2').val(""); 
                        $('#porcentaje').val("");
                    }
                    else
                    {
                        var empresa = JSON.parse(data);
                        console.log(empresa);
                    
                        var IdEmpresa = empresa['EmpRuc'];
                        $('#ruc2').val(empresa['EmpRuc']);
                        $('#razSocial2').val(empresa['EmpRaz']);
                        $('#direccion2').val(empresa['EmpDir']);
                        $('#tel2').val(empresa['EmpTel']);
                        $('#email2').val(empresa['EmpCor']);
                        $('#rlnombres2').val(empresa['EmpNomRp']);
                        $('#rldni2').val(empresa['EmpDniRp']);                      
                    }  
                },
                error: function (xhr, ajaxOptions, thrownError) 
                {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })
          });
         
        /* EDICION DE LA TABLA DE CONSORCIADOS */
        $(document).on('click', '#editarConsorciado', function(event) 
        {
            var empresaId = $(this).attr('empresa');
            $.ajax({   
               url: "<?php echo base_url().'contratista/get_one_empresa_detallecontratista_json';?>",
               type: "POST",
               data: ({empresaId: empresaId }),
               dataType: "html", 
                success: function(data) 
                {
                        var empresa = JSON.parse(data);
                        console.log(empresa);

                        $('#ruc2').val(empresa['EmpRuc']);
                        $('#ruc2').attr('for',"inputSuccess");
                        
                        $('#razSocial2').val(empresa['EmpRaz']);
                        $('#direccion2').val(empresa['EmpDir']);
                        $('#tel2').val(empresa['EmpTel']);
                        $('#email2').val(empresa['EmpCor']);
                        $('#rlnombres2').val(empresa['EmpNomRp']);
                        $('#rldni2').val(empresa['EmpDniRp']);  
                        $('#porcentaje').val(empresa['DcoPrt']);  

                },
                error: function (xhr, ajaxOptions, thrownError) 
                {
                    alert(xhr.status);
                    alert(thrownError);
                }
            }) 
        });
    });
</script>
