<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="control-group">
                            <a href="<?php echo base_url() ?>contrato/nuevo" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar Nuevo Contrato</a>
                        </div>
                    </div>
                </div> 
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Contratos registrados</h3>                                   
                </div><!-- /.box-header -->
               <div class="box-body table-responsive">
                    <table id="contratos" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" >
                                <th>N° Contrato</th>
                                <th>Concurso Público N°</th>
                                <th>RUC</th>
                                <th>Razón Social</th>
                                <th>Estado</th>
                                <th>Duración</th>
                                <th style="width: 150px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($contratos)) { 
                                foreach ($contratos as $contrato):?>
                                <tr>
                                    <td><?php echo $contrato['ConNum']; ?></td>
                                    <td><?php echo $contrato['ConDes']; ?></td>
                                    <td><?php echo $contrato['CstRuc']; ?></td>
                                    <td><?php echo $contrato['CstRaz']; ?></td> 
                                    <td><?php echo ($contrato['ConVig']=='f') ? 'No Vigente' : 'Vigente'; ?></td> 
                                    
                                    <td>Inicio: <?php echo date('d/m/Y', strtotime($contrato['ConFchIn'])); ?><br>Fin: <?php echo date('d/m/Y', strtotime($contrato['ConFchFn'])); ?></td>
                                    <td>
                                        
                                        <a href="<?php echo base_url() . 'contrato/'.$contrato['ConId'].'/precios_penalidades' ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Precios Penalidades">
                                            <i class="fa fa-legal"></i>
                                        </a>
                                        
                                        <a id="detalle" name="detalle" role="button" contrato="<?php echo $contrato['ConId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Detalle"> 
                                            <i class="fa fa-info"></i>
                                        </a>
                                       
                                        <a href="<?php echo base_url() . 'contrato/editar/' . $contrato['ConId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        
                                        <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                            <a href="#modal-eliminar" role="button" data-toggle="modal" contrato="<?php echo $contrato['ConId']; ?>" class="btn btn-default btn-flat">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; }?>
                        </tbody>
                    </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        
    <div id='show-me' style='display:none'>  
        <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Detalle de Actividades del Contrato</h3>                                   
                </div><!-- /.box-header -->
               <div class="box-body table-responsive">
                    
                    <div class="row">   
                        <div class="col-md-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Número de Contrato:</label>
                                <input type="text" name="numcontrato" id="numcontrato" class="form-control" disabled/>
                            </div>
                        </div> 
                        
                        <div class="col-md-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Número de Concurso Público:</label>
                                <input type="text" name="numconcurso" id="numconcurso" class="form-control" disabled/>
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
                                    <input type="text" name="FchIn" id="FchIn" class="form-control pull-right" disabled>
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
                                    <input type="text" name="FchFn" id="FchFn" class="form-control pull-right" disabled>
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
                                                <textarea id="descripcion" name="descripcion" class="form-control" rows="3" disabled></textarea>
                                            </div>       
                                        </div>
                                    </div> 
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Metrado, Precios Unitarios y Rendimientos por Sub Actividad.</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                   <table id="detalleContrato" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" >
                                <th>Actividad </th>
                                <th>Sub Actividad</th>
                                <th>Metrado</th>
                                <th>Precio Unitario</th>
                                <th>Rendimiento  dia/trabajador</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyDetalle">
                        </tbody>
                    </table>
            </div><!-- /.box-body -->
            </div>
        </div><!-- /.box -->
       </div>
    </div>
</section>

<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- date-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<!-- date-range-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/timepicker/bootstrap-timepicker.min.css">
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/daterangepicker/daterangepicker-bs3.css">
<!-- page script -->
<!-- MODAL : Eliminar Contrato  -->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="idelim" method="post">    
    <div class="modal-dialog">
        <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-edit"></i>Eliminar Contrato</h4>
    </div>
<div class="modal-body">
    <h5 style="text-align: center">¿Realmente deseas eliminar este Contrato y los datos asociados a el?</h5>
</div>     
<div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button>
    <button type="submit" class="btn btn-danger">Eliminar</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</form>
</div><!-- /.modal -->


<script type="text/javascript">
$(document).ready(function()
{
    
    $('#contratos').DataTable();

    $(document).on('click', 'a', function(event) 
    {
        var contrato = $(this).attr('contrato');
        $('#idelim').attr('action',"<?php echo base_url() . 'contrato/eliminar/'; ?>"+contrato);
    });
    $("#show-me").hide('slow');
    
    $(document).on("click","#detalle",function()
    {
        var idContrato = $(this).attr('contrato');
        console.log(idContrato)
        $.ajax({   
               url: "<?php echo base_url().'contrato/get_contrato_x_id_json';?>",
               type: "POST",
               data: ({idContrato: idContrato}),
               dataType: "html", 
                success: function(data) 
                {
                    var contrato = JSON.parse(data);
                    console.log(contrato);
                    $("#show-me").show('slow');
                    $('#numconcurso').val("");
                    $('#numcontrato').val("");
                    $('#FchIn').val("");
                    $('#FchFn').val("");
                    $('#descripcion').val("");
                    
                    var str = contrato['ConDes'];
                    var res = str.substr(0, 20);
                    
                    $('#numconcurso').val(res);
                    $('#numcontrato').val(contrato['ConNum']);
                    $('#descripcion').val(contrato['ConDes']);
                    
                    var FchIn = new Date(contrato['ConFchIn']); 
                    var FchFn = new Date(contrato['ConFchFn']); 
                  
                    FchIn.setDate(FchIn.getDate() + 1);
                    FchFn.setDate(FchFn.getDate() + 1);

                    $('#FchIn').datepicker('setDate', FchIn );
                    $('#FchFn').datepicker('setDate', FchFn );
                },
                error: function (xhr, ajaxOptions, thrownError) 
                {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })  
            $.ajax({   
                url: "<?php echo base_url().'contrato/get_detalle_contrato_x_id_json';?>",
                type: "POST",
                data: ({idContrato: idContrato}),
                dataType: "html", 
                beforeSend: function() 
                {
                    //alert('Cargando...');
                },
                success: function(data) 
                {
                    if (JSON.parse(data)==null) 
                    {
                        var oTable = $("#detalleContrato").DataTable();
                        oTable.clear();
                        oTable.draw();
                    }
                    else
                    {
                        var oTable = $("#detalleContrato").DataTable();
                        oTable.destroy();
                        $("#tbodyDetalle").empty();
                        //$('#detalleContrato tr:fisrt').remove();
                        var detalleContrato = JSON.parse(data);
                        console.log(detalleContrato);
                        var trHTML = '';
                        $.each(detalleContrato, function (i, item) 
                        {
                            trHTML += '<tr><td>' + item.ActDes + '</td><td>' + item.SacDes + '</td><td>' + item.DcsMet + '</td><td>' + item.DcsPreUn + '</td><td>' + item.DcsRen + '</td></tr>';
                        });
                        $('#detalleContrato').append(trHTML);
                        $("#detalleContrato").DataTable();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) 
                {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })                   
    });
    $('#FchIn').datepicker({
        format: "dd/mm/yyyy",
        todayBtn: true,
        language: "es",
        autoclose: true,
        todayHighlight: true
    });
    $('#FchFn').datepicker({
        format: "dd/mm/yyyy",
        todayBtn: true,
        language: "es",
        autoclose: true,
        todayHighlight: true
    }); 
});
</script>
