<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
        <form name="fcontrato" role="form" method="post" action="<?php echo str_replace('/index.php', '', current_url())."/guardar" ;?>">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Datos de Contratista</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">   
                        <div class="col-md-8">
                            <div class="form-group"> 
                                <div class="control-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-search"></i>
                                        </div>
                                        <input id="contratista" type="text" class="form-control" rel="contratista" title="Ingrese Razon Social Para buscar en las contratistas registradas" data-toggle="tooltip" data-placement="top" required/>
                                        <input id="contratista_id" name="contratista_id" class="span12" type="hidden" value="<?php echo (isset($contratoEdit)) ? $contratoEdit['ConCstId'] : set_value('contratista_id'); ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="control-group">
                                    <label>RUC:</label>
                                    <div class="controls">
                                        <input id="ruc" type="text" class="form-control" disabled/>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>    
                        </div>
                                     <div class="col-md-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Razón Social:</label>
                                            <input id="razSocial" type="text" class="form-control" disabled/>
                                        </div>
                                     </div>       
                                </div>
                    <div class="row">   
                        <div class="col-md-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Dirección:</label>
                                <input id="direccion" type="text" class="form-control" disabled/>
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
                                            <input id="tel" type="text" class="form-control" disabled/>
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
                                    <label>E-mail:</label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </div>
                                            <input id="email" type="email" class="form-control" disabled/>
                                        </div>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- text input -->
                            <div class="form-group">
                                <div class="control-group">
                                    <label>Representante Legal:</label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input id="CstNomRp" type="text" class="form-control" disabled/>
                                        </div>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Datos de Contrato</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php if(isset($contratoEdit))
                { ?>
                    <div class="row"> 
                        <div class="col-md-6">
                            <label>Estado del Contrato:</label>
                            <div class="form-group">
                                <div class="control-group"> 
                                    <input id="vigente" name="ConVig" type="radio" class="flat-red" value="vigente" 
                                    <?php echo ($contratoEdit['ConVig']=='t') ? 'checked' : ''; ?>>Vigente
                                    <input id="novigente" name="ConVig" type="radio" class="flat-red" value="novigente" 
                                    <?php echo ($contratoEdit['ConVig']=='f') ? 'checked' : '';?>>No Vigente             
                                </div>
                            </div>  
                        </div>
                    </div>
                <?php } ?>
                    <div class="row"> 
                        <div class="col-md-6">
                            <!-- text input -->
                            <div class="form-group">
                                <div class="control-group">
                                    <label>Número de Contrato:</label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-file-text"></i>
                                            </div>
                                            <input id="numContrato" name="numContrato" type="text" class="form-control"  data-inputmask='"mask": "999-9999"' value="<?php echo (isset($contratoEdit)) ? $contratoEdit['ConNum'] : set_value('numContrato'); ?>" data-mask required/>
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
                                    <label>Número de Concurso Público:</label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-paste"></i>
                                            </div>
                                            <input id="numConcurso" name="numConcurso" type="text" class="form-control" data-inputmask='"mask": "9999-9999-AAAAAAAAA"' value="<?php echo (isset($contratoEdit)) ? substr($contratoEdit['ConDes'], 0, 20) : set_value('numConcurso'); ?>" data-mask required/>
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
                                <label>Fecha Inicio:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input id="FchIn" name="FchIn" type="text" class="form-control pull-right" value="<?php echo (isset($contratoEdit)) ? $contratoEdit['ConFchIn'] : set_value('FchIn'); ?>" required>
                                </div>       
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Fecha Fin:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input id="FchFn" name="FchFn"  type="text" class="form-control pull-right" value="<?php echo (isset($contratoEdit)) ?  $contratoEdit['ConFchFn'] : set_value('FchFn'); ?>" required>
                                </div>       
                            </div>
                        </div> 
                    </div>       
                    <div class="row">   
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción del Contrato:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-file-text"></i>
                                    </div>
                                    <textarea id="descripcion" name="descripcion" class="form-control" rows="3"><?php echo (isset($contratoEdit)) ? $contratoEdit['ConDes'] : set_value('descripcion'); ?></textarea>
                                </div>       
                            </div>
                        </div> 
                    </div>       
                </div><!-- /.box-body -->
           </div><!-- /.box -->
            <?php if(!isset($contratoEdit))
            { ?>    
                    <!--
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Detalle de Actividades</h3>                                   
                        </div>
                        <div class="box-body">
                                    <div class="row">   
                                        <div class="col-md-6">
                                            <div class="form-group"> 
                                            <?php foreach ($actividades as $actividad): ?>
                                            <div class="checkbox class="nivel1">
                                                <label>
                                                    <input name="actividades[]" type="checkbox" id="<?php echo $actividad['ActId'] ?>" value="<?php echo $actividad['ActId'] ?>" <?php if(isset($actividadesId)){echo (in_array($actividad['ActId'],$actividadesId))?'checked':'';}?>>
                                                    <?php echo $actividad['ActDes'] ?>
                                                    <?php foreach ($actividad['subactividades'] as $subactividad) { ?>
                                                    <div class="checkbox class="nivel2">
                                                            <label>
                                                                <input name="subactividades[]" type="checkbox" id="<?php echo $subactividad['SacId'] ?>" value="<?php echo $subactividad['SacId'] ?>">
                                                                <?php echo $subactividad['SacDes'] ?>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <?php endforeach; ?>
                                            </div>
                                       </div>
                                    </div>
                            </div>
                    </div><!-- /.box -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Detalle de Actividades</h3>                                   
                </div>
                <div class="box-body">
                    <div class="row">   
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox nivel2">
                                    <label>
                                    <input type="checkbox" id="seleccionar_todos"/>
                                        Seleccionar Todos
                                    </label>
                                </div>
                                <?php foreach ($actividades as $actividad): ?>
                                <div class="checkbox nivel2">
                                    <label>
                                        <input name="actividades[]" type="checkbox" id="<?php echo $actividad['ActId'] ?>" value="<?php echo $actividad['ActId'] ?>">
                                        <?php echo $actividad['ActDes'] ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?> 
            <?php if(!isset($contratoEdit)){ ?> 
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Metrado, Precios Unitarios y Rendimientos por Sub Actividad.</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tableID" class="table table-bordered" >
                        <thead>
                            <tr Class="info" id="cabecera">
                                <th>Servicios Comerciales</th>
                                <th>Unidad de Medida</th>
                                <th>Metrado</th>
                                <th>Precio Unitario</th>
                                <th>Rendimiento</th>
                            </tr>
                        </thead>
                        <tbody class="tbodyP" role="alert">
                        <?php foreach ($actividades as $actividad): ?>
                            <tr id="<?php echo $actividad['ActId']; ?>" class="nivel1">
                                <td id="<?php echo $actividad['ActId']; ?>"><?php echo $actividad['ActDes'] ?></td>
                                <td id="<?php echo $actividad['ActId']; ?>"></td>
                                <td id="<?php echo $actividad['ActId']; ?>"></td>
                                <td id="<?php echo $actividad['ActId']; ?>"></td>
                                <td id="<?php echo $actividad['ActId']; ?>"></td> 
                            </tr>
                          <?php foreach ($actividad['subactividades'] as $subactividad) { ?>
                            <tr id="<?php echo $actividad['ActId']; ?>">
                                <td id="<?php echo $actividad['ActId']; ?>"><span class="nivel2"><?php echo $subactividad['SacDes']; ?></span></td>
                                <td id="<?php echo $actividad['ActId']; ?>"><?php echo $subactividad['auxUniDes']; ?></td>
                                <td id="<?php echo $actividad['ActId']; ?>"><input id="<?php echo 'met_' . $subactividad['SacId']; ?>" name="<?php echo 'met_' . $subactividad['SacId']; ?>" type="number" max="2000000" min="2" step="any"></td>
                                <td id="<?php echo $actividad['ActId']; ?>"><input id="<?php echo 'pru_' . $subactividad['SacId']; ?>" name="<?php echo 'pru_' . $subactividad['SacId']; ?>" type="number" max="250.0" min="0.01" step="any"></td>
                                <td id="<?php echo $actividad['ActId']; ?>"><input id="<?php echo 'ren_' . $subactividad['SacId']; ?>" name="<?php echo 'ren_' . $subactividad['SacId']; ?>" type="number" max="490" min="2" step="any"></td>
                            </tr>
                          <?php } ?>  
                        <?php endforeach; ?> 
                        </tbody>
                    </table> 
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <?php } else { ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Metrado, Precios Unitarios y Rendimientos por Sub Actividad.</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tableID" class="table table-bordered" >
                        <thead>
                            <tr Class="info" >
                                <th>Actividad</th>
                                <th>Sub Actividad</th>
                                <th>Unidad de Medida</th>
                                <th>Metrado</th>
                                <th>Precio Unitario</th>
                                <th>Rendimiento</th>
                             </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($detallesContrato as $detalleContrato): ?>
                            <tr id="<?php echo $detalleContrato['ActId']; ?>">
                                <td id="<?php echo $detalleContrato['ActId']; ?>"><?php echo $detalleContrato['ActDes']; ?></td>
                                <td id="<?php echo $detalleContrato['ActId']; ?>"><?php echo $detalleContrato['SacDes']; ?></td>
                                <td id="<?php echo $detalleContrato['ActId']; ?>"><?php echo $detalleContrato['UniDes']; ?></td>
                                <td id="<?php echo $detalleContrato['ActId']; ?>"><input id="<?php echo 'met_' . $detalleContrato['SacId']; ?>" name="<?php echo 'met_' . $detalleContrato['SacId']; ?>" value="<?php echo $detalleContrato['DcsMet']; ?>" type="number" max="1500000" min="2" step="any"></td>
                                <td id="<?php echo $detalleContrato['ActId']; ?>"><input id="<?php echo 'pru_' . $detalleContrato['SacId']; ?>" name="<?php echo 'pru_' . $detalleContrato['SacId']; ?>" value="<?php echo $detalleContrato['DcsPreUn']; ?>" type="number" max="250.0" min="0.01" step="any"></td>
                                <td id="<?php echo $detalleContrato['ActId']; ?>"><input id="<?php echo 'ren_' . $detalleContrato['SacId']; ?>" name="<?php echo 'ren_' . $detalleContrato['SacId']; ?>" value="<?php echo $detalleContrato['DcsRen']; ?>" type="number" max="490" step="any"></td>
                            </tr>    
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>                              
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo (!isset($contratoEdit))  ? 'Registrar Contrato' : 'Actualizar Contrato' ;?></button>
                </div>
                <div class="col-md-6">
                    <button type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
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
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

<!-- date-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

<!-- Autocomplete  -->
<link rel="stylesheet" href="<?php echo base_url();?>frontend/plugins/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script type="text/javascript" src="<?php echo base_url()?>frontend/plugins/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>


<!-- page script -->
<script type="text/javascript">
$(function () 
{
        $('input[rel="contratista"]').tooltip();
        //$("#tableID").dataTable();
});
</script>
<script>
$( document ).ready(function() 
{
    <?php if(!isset($contratoEdit)){ ?> 
          $('table tbody tr').hide();  
    <?php } ?>
    
    $('#seleccionar_todos').change(function() 
    {
        if ($(this).is(':checked')) 
        {
            $(':checkbox').prop('checked',true);
            $("#tableID tr").fadeIn("slow", function() {});
            $("#tableID td").show();
        }
        else
        {
            $(':checkbox').prop('checked',false);
            $("#tableID tr").toggle();
            $("#tableID td").toggle();
            $("#cabecera").show();
        }
    });
    
    $(":checkbox").change(function()
    {
        if ($(this).is(':checked')) 
        {
            var id = $(this).attr('id');
            console.log(id)
            if(id===1)
            {
                $("#tableID tr[id=1]").fadeIn("slow", function() {});
                $("#tableID td[id=1]").show();
            }
            
            if(id!==1)
            {
                $("#tableID tr[id="+id+"]").fadeIn("slow", function() {});
                $("#tableID td[id="+id+"]").show();
            }
            
        } else 
        {
            var id = $(this).attr('id');
            console.log(id)
            
            if(id==1)
            {
                $("#tableID tr[id=1]").toggle();
                $("#tableID td[id=1]").toggle();
            }
            
            if(id!=1)
            {
                $("#tableID tr[id="+id+"]").toggle();
                $("#tableID td[id="+id+"]").toggle();
            }
            
        }
    });
        
    $("#numContrato").inputmask("999-9999");
    $("#numConcurso").inputmask("9999-9999-AAAAAAAAA");
    $("#FchIn").inputmask("d/m/y");
    $("#FchFn").inputmask("d/m/y");
    $.datepicker.regional['es'] = 
    {
        closeText: 'Cerrar',
        prevText: 'Ant',
        nextText: 'Sig',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    
    
    $("#FchIn").datepicker({
        format: "dd/mm/yyyy",
        todayBtn: true,
        language: "es",
        autoclose: true,
        todayHighlight: true,
    }).on('changeDate', function (selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#FchFn').datepicker('setStartDate', startDate);
    }).on('clearDate', function (selected) {
        $('#FchFn').datepicker('setStartDate', null);
    });

    $("#FchFn").datepicker({
        format: "dd/mm/yyyy",
        todayBtn: true,
        language: "es",
        autoclose: true,
        todayHighlight: true,
    }).on('changeDate', function (selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#FchIn').datepicker('setEndDate', endDate);
    }).on('clearDate', function (selected) {
        $('#FchIn').datepicker('setEndDate', null);
    });
    
    $("#contratista").autocomplete(
    {
        source: "<?php echo base_url(); ?>contrato/autoCompleteContratista",
        minLength: 1,
        select: function( event, ui ) 
        {
            $("#contratista_id").val(ui.item.id);
            $("#ruc").val(ui.item.ruc);
            $("#razSocial").val(ui.item.razSocial);
            $("#direccion").val(ui.item.dir);
            $("#tel").val(ui.item.tel);
            $("#email").val(ui.item.email);
            $("#CstNomRp").val(ui.item.NomRp);
        }
    }); 
    
    <?php if(isset($contratoEdit)) { ?>;   

        var FchIn = new Date("<?php echo $contratoEdit['ConFchIn']; ?>"); 
        var FchFn = new Date("<?php echo $contratoEdit['ConFchFn']; ?>"); 
        FchIn.setDate(FchIn.getDate() + 1);
        FchFn.setDate(FchFn.getDate() + 1);
        $('#FchIn').datepicker('setDate', FchIn );
        $('#FchFn').datepicker('setDate', FchFn );
        
        var idContratista = <?php echo $contratoEdit['ConCstId']; ?>;
        $.ajax({   
               url: "<?php echo base_url().'contrato/get_contratista_contrato_x_id_json';?>",
               type: "POST",
               data: ({idContratista: idContratista }),
               dataType: "html", 
                success: function(data) 
                {
                    if (JSON.parse(data)==null) 
                    {
                        alert("Ningun dato");
                    }
                    else
                    {
                        var contratista = JSON.parse(data);
                        console.log(contratista);
                        $("#contratista").val(contratista['CstRaz']);
                        
                        $("#ruc").val(contratista['CstRuc']);
                        $("#razSocial").val(contratista['CstRaz']);
                        $("#direccion").val(contratista['CstDir']);
                        $("#tel").val(contratista['CstTel1']);
                        $("#email").val(contratista['CstCor1']);
                        $("#CstNomRp").val(contratista['CstNomRp']);
                    }  
                },
                error: function (xhr, ajaxOptions, thrownError) 
                {
                    alert(xhr.status);
                    alert(thrownError);
                }
        });
        <?php if(isset($detallesContrato)) { ?>; 
       
        <?php foreach ($detallesContrato as $detalleContrato): ?>
            $("#tableID tr[id=<?php echo $detalleContrato['ActId']; ?>]").show(1000);           
            $("#tableID td[id=<?php echo $detalleContrato['SacId']; ?>]").show(1000);           
        <?php endforeach;?>   
        <?php } }?>;  
});
</script>