 <!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Sistema de Control de Ordenes de Trabajo - SCOT | Sedalib S.A.</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        
        <meta name="description" content="Sistema de Control de Ordenes de Trabajo - SCOT | Sedalib S.A.">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="index,follow,noodp,noydir"/>
        <meta name="google-site-verification" content="T_M_Ym5DQ-cEQQhx_jswyCBTssIdgtewICcvb3sgh8g" />
        <meta name="Keywords" content="Sistema,Control,Ordenes,Trabajo,Sedalib" />
        <meta property="og:title" content="Sistema de Control de Ordenes de Trabajo - SCOT | Sedalib S.A." />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="icon.png" />
        <meta property="og:url" content="http://land.sedalib.com.pe:81/moviles/" />
        <meta property="og:description" content="Sistema de Control de Ordenes de Trabajo - SCOT | Sedalib S.A." />
        <meta property="og:site_name" content="http://land.sedalib.com.pe:81/moviles/" />

        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?php echo base_url()?>frontend/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url()?>frontend/dist/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url()?>frontend/dist/css/AdminLTE.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url()?>frontend/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo base_url()?>frontend/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url()?>frontend/dist/css/estilos.css" rel="stylesheet" type="text/css">
        
        <!-- sweetAlert. -->
        <link rel="stylesheet" href="<?php echo base_url()?>frontend/plugins/sweetalert/sweetalert2.css" rel="stylesheet" type="text/css">
        <script src="<?php echo base_url()?>frontend/plugins/sweetalert/sweetalert2.min.js"></script> 
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body id="fondo" class="hold-transition login-page">
        <div class="login-box">
          <div class="login-logo">
            <a href="<?php echo base_url()?>login" ><b>Sistema de Control de Ordenes de Trabajo </b>SCOT</a>
          </div>
          <!-- /.login-logo -->
          <div class="login-box-body">
            <p class="login-box-msg">Inicie sesión para acceder al sistema</p>
            <?php if($this->session->flashdata('error') != null)
                {?>
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $this->session->flashdata('error');?>
                </div>
                <?php 
                }?>
            <form id="formLogin" method="post">
              <div class="form-group has-feedback">
               <label class="control-label" for="username">Usuario</label>
                <!--<input type="email" class="form-control" placeholder="Email">-->
                <input class="form-control" type="username" id="username" name="username">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                <p class="help-block">Ingrese su Usuario...</p>
              </div>
              <div class="form-group has-feedback">
                <label class="control-label" for="emailAgain">Contraseña</label>
                <!--<input type="password" class="form-control" placeholder="Password">-->
                <input class="form-control" type="password" id="emailAgain" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <!--<p class="help-block">Ingrese su Contraseña...</p>-->
              </div>
              <div class="footer">
                <button type="submit" class="btn bg-blue btn-block btn-flat"><font size="4">Ingresar</font></button>  
              </div>
            </form>
          </div>
          <!-- /.login-box-body -->
        </div>
        <!--
        <div id="footer">
            <center>
               <p style="color: red"> <?php echo "IP : ".$this->input->ip_address().' - Navegador : '.$this->input->user_agent();   ?></p>
            </center>
            <center>
                <h6 id="footer1">&copy; Gerencia Comercial</h6>
                <ul class="social-nav model-2">
                    <li>
                        <a href="https://www.facebook.com/Sedalib-SA-1451586228424087/" class="facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>
                    
                    <li>
                        <a href="#" class="twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="google-plus">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    </li>
                    <li>
                        <a class="linkedin">
                            <i class="fa fa-linkedin"></i>
                        </a>
                    </li>
                </ul>
            </center>
        </div>-->
        
<!-- jQuery 2.1.4 -->
<script src="<?php echo base_url()?>frontend/plugins/jQuery/jQuery-2.1.4.min.js"></script>

<!-- Bootstrap 3.3.5 -->
<!--  <script src="<?php echo base_url()?>frontend/bootstrap/js/bootstrap.min.js"></script> -->

<!-- iCheck -->
<script src="<?php echo base_url()?>frontend/plugins/iCheck/icheck.min.js"></script>

<!-- VALIDACION DE FORMUALRIOS  -->
<script src="<?php echo base_url()?>frontend/plugins/validation/jqBootstrapValidation.js"></script>
<script src="<?php echo base_url()?>frontend/plugins/validation/validate.js"></script>

<!-- Autocomplete  -->
<link rel="stylesheet" href="<?php echo base_url();?>frontend/plugins/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script type="text/javascript" src="<?php echo base_url()?>frontend/plugins/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

<script>
$(document).ready(function() 
{
    $('#username').focus();
    $("#formLogin").validate(
    {
        rules :{
            username: { required: true},
            password: { required: true}
        },
        messages:{
            username: { required: 'Ingrese su Nombre de Usuario Corerctamente.'},
            password: { required: 'Ingrese su Contraseña Corerctamente.'}
        },
        submitHandler: function( form )
        {       
            var datos = $(form).serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>ingresar?ajax=true",
                    data: datos,
                    dataType: 'json',
                    success: function(data)
                    {
                        if(data.result == true)
                        {
                            window.location.href = "<?php echo base_url();?>inicio?bienvenido";
                        }
                        else
                        {
                            sweetAlert("Sistema de Control de Ordenes de Trabajo - SCOT", data.mensaje, "error");
                        }
                    }
            });
            return false;
        },
        errorClass:'help-inline',
        validClass:'success',
        errorElement:'span',
        highlight:function(element, errorClass, validClass) 
        {
            //$(element).parents('.control-group').addClass('error');
            $(element).parents("div.control-group").addClass("error"); 
        },
        unhighlight: function(element, errorClass, validClass) 
        {
            //$(element).parents('.control-group').removeClass('error');
            //$(element).parents('.control-group').addClass('success');
            $(element).parents(".error").removeClass("error"); 
        }
    });
    
    
    $("input,textarea,select").jqBootstrapValidation(
    {
        preventSubmit: true,
        submitError: function($form, event, errors) 
        {
            sweetAlert("Sistema de Control de Ordenes de Trabajo - SCOT", "Porfavor, corrija sus errores." , "error");     
        },
        submitSuccess: function($form, event) 
        {
            event.preventDefault();
        },
        filter: function() 
        {
            return $(this).is(":visible");
        }
    }
    );
    
    $("#username").autocomplete(
    {
        source: "<?php echo base_url(); ?>autoCompleteUsuario",
        minLength: 1,
        select: function( event, ui ) 
        {
            $("#username").val(ui.item.usuarioSET);
        }
    }); 
    
});
    </script>
   </body>
</html>
