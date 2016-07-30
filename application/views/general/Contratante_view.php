<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if(isset($_SESSION['mensaje'])){ ?>
            <div class="alert alert-<?php echo ($_SESSION['mensaje'][0]=='error')? 'danger' : 'success'; ?> alert-dismissible">
                <button  class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $_SESSION['mensaje'][1] ;?>
            </div>
            <?php } ?>
    <form role="form" enctype="multipart/form-data" method="post" action="<?php echo str_replace('/index.php', '', current_url())."/guardar" ; ?>">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Datos de Contratante</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                
                    <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                        <label>RUC de Empresa:</label>
                                        <div class="input-group">
                                            <input type="text" name="ruc" id="ruc" class="form-control" data-inputmask='"mask": "99999999999"' data-mask value="<?php echo (isset($contratanteEdit)) ? $contratanteEdit['EmpRuc'] : set_value('ruc'); ?>" required/>
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info btn-flat" id="buscarRuc"><i class="fa fa-search"></i></button>
                                            </div><!-- /btn-group -->
                                        </div><!-- /input-group -->
                                </div>    
                            </div>      
                    </div>
                    <div class="row"> 
                           <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Razón Social:</label>
                                        <input type="text" name="razSocial" id="razSocial" class="form-control" value="<?php echo (isset($contratanteEdit)) ? $contratanteEdit['EmpRaz'] : set_value('razSocial'); ?>" required/>
                                    </div>
                            </div>  
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Dirección:</label>
                                    <input type="text" name="direccion" id="direccion" class="form-control" value="<?php echo (isset($contratanteEdit)) ? $contratanteEdit['EmpDir'] : set_value('direccion'); ?>" required/>
                                </div>
                            </div>   
                    </div>
                    <div class="row">
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
                                                <input type="text" name="tel11" id="tel11" class="form-control" data-inputmask='"mask": "(999) 99-99-99"' data-mask  value="<?php echo (isset($contratanteEdit)) ? $contratanteEdit['EmpTel'] : set_value('tel1'); ?>" required/>
                                            </div><!-- /.input group -->
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                </div>
                        </div>
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
                                                <input type="email" name="email11" id="email11" class="form-control" data-inputmask='alias: "email"' data-mask value="<?php echo (isset($contratanteEdit)) ? $contratanteEdit['EmpCor'] : set_value('email11'); ?>" required/>
                                            </div>
                                            <p class="help-block"></p>
                                        </div>
                                        </div>

                                    </div>
                            </div>
                        </div>  
                    </div>
                </div><!-- fin box body -->
            </div><!-- fin box-->
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
                                        <input name="dni1" id="dni1" type="text" class="form-control" data-inputmask='"mask": "99999999"' data-mask value="<?php echo (isset($contratanteEdit)) ? $contratanteEdit['EmpDniRp'] : set_value('dni1'); ?>" required/>
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
                                            <input name="nombres1" id="nombres1" type="text" class="form-control" value="<?php echo (isset($contratanteEdit)) ? $contratanteEdit['EmpNomRp'] : set_value('nombres1'); ?>" required>
                                        </div>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                        </div>      
                    </div>
                </div><!-- fin box body -->
            </div><!-- fin box-->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Logos para Formatos Impresos</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="control-group">
                                    <label for="exampleInputFile">Logo1:</label><br>
                                    <!--<?php if(isset($contratanteEdit)){ ?>
                                <div class="box box-solid">
                                    <div class="box-header">
                                        <h5 class="box-title">Logo 1:</h5>
                                        <div class="box-tools pull-right">
                                            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            <button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <img src="<?php echo $contratanteEdit['CntLog1']; ?>" alt="Logo Contratista" width="250" height="80"/>
                                        <p> Url: <code><?php echo $contratanteEdit['CntLog1']; ?></code> </p>
                                    </div>
                                </div>
                                <?php } ?>-->
                                    <?php if(isset($contratanteEdit)){ ?>
                                        <img src="<?php echo base_url().$contratanteEdit['CntLog1']; ?>" alt="Logo Contratante" width="250" height="80"/><br><br>
                                    <?php } ?>
                                    <input type="file" name="log1" id="log1" <?php echo (isset($contratanteEdit)) ? '' : 'required'; ?>>   
                                    <input type="hidden" name="log1Oculto" id="log1Oculto" value="<?php echo (isset($contratanteEdit)) ? $contratanteEdit['CntLog1'] : set_value('log1Oculto'); ?>">
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="control-group">
                                    <label for="exampleInputFile">Logo2:</label><br>
                                    <?php if(isset($contratanteEdit)){ ?>
                                        <img src="<?php echo base_url().$contratanteEdit['CntLog2']; ?>" alt="Logo Contratante" width="250" height="80"/><br><br>
                                    <?php } ?>
                                    <input type="file" name="log2" id="log2"> 
                                    <input type="hidden" name="log2Oculto" id="log2Oculto" value="<?php echo (isset($contratanteEdit)) ? $contratanteEdit['CntLog2'] : set_value('log2Oculto'); ?>">
                                </div>
                            </div>    
                        </div>      
                    </div>
                </div><!-- fin box body -->
            </div><!-- fin box-->
            <div class="row">
                <div class="col-md-6">
                    <button id="botonRegistrarContratante" type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo (!isset($contratanteEdit))? 'Registrar' : 'Actualizar' ;?></button>
                </div>
                <div class="col-md-6">
                    <button id="botonLimpiarContratante" type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
                </div>
            </div>
            </form>
       </div>
    </div>
</section>
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
<script src="<?php echo base_url()?>frontend/plugins/sweetalert/sweetalert2.min.js" type="text/javascript"></script> 
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

        /*  FORMULARIO PRINCIPAL */
        $("#ruc").inputmask("999999999999");
        $("#tel11").inputmask("(999) 99-99-99");
        $("#dni1").inputmask("99999999");
        $('#botonLimpiarContratante').click(function () 
        {
            $('#botonRegistrarContratante').prop('disabled',false);
        });
        $("#buscarRuc").click( function()
          {         
            var ruc = $('#ruc').val();
            $.ajax({   
               url: "<?php echo base_url().'configuracion/contratante/validar_empresa_libre';?>",
               type: "POST",
               data: ({ruc: ruc }),
               dataType: "html", 
                success: function(data) 
                {
                    var resultado = JSON.parse(data);
                    
                    if(resultado['result']==false)
                    {
                        swal("Error", "La empresa es una Contratista, es parte de un Consorcio o ya es una empresa Contratante (EPS). No puede ser registrada como Contratante.","error")
                        $('#razSocial').val("");
                        $('#direccion').val("");
                        $('#tel11').val("");
                        $('#email11').val("");
                        $('#nombres1').val("");
                        $('#dni1').val("");
                        $('#botonRegistrarContratante').prop('disabled', true);
                    }
                    
                    if(resultado['result']==true) 
                    {
                        swal("Error", "La Empresa no ha sido registrada, puede registrarla y hacerla nueva Contratante.","error")
                        $('#razSocial').val("");
                        $('#direccion').val("");
                        $('#tel11').val("");
                        $('#email11').val("");
                        $('#nombres1').val("");
                        $('#dni1').val("");
                        $('#botonRegistrarContratante').prop('disabled',false);
                    }
                    
                    if((resultado['result']==true)&&(resultado['empresa']!=null))
                    {
                        swal("Exito", "La Empresa esta registrada, puede registrarla como Contratante.","success")
                        console.log(data)
                        //var empresa = JSON.parse(resultado['empresa']);
                        //console.log(empresa);
                        $('#razSocial').val(resultado['empresa']['EmpRaz']);
                        $('#direccion').val(resultado['empresa']['EmpDir']);
                        $('#tel11').val(resultado['empresa']['EmpTel']);
                        $('#email11').val(resultado['empresa']['EmpCor']);
                        $('#nombres1').val(resultado['empresa']['EmpNomRp']);
                        $('#dni1').val(resultado['empresa']['EmpDniRp']);
                        $('#botonRegistrarContratante').prop('disabled',false);
                    }
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
