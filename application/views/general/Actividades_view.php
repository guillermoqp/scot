<!-- sweetAlert. -->
<link rel="stylesheet" href="<?php echo base_url()?>frontend/plugins/sweetalert/sweetalert2.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url()?>frontend/plugins/sweetalert/sweetalert2.min.js"></script> 
       
<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <!--
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
                -->
                <script>
                    swal("Actividades Comerciales", "<?php echo $_SESSION['mensaje'][1]; ?>" , "<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'warning' : 'success'; ?>"); 
                </script>
            <?php } ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="control-group">
                            <a href="<?php echo base_url() ?>configuracion/actividad/nuevo" class="btn btn-info btn-flat"><i class="fa fa-pencil"></i> Registrar Nueva Actividad Comercial</a>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Actividades Comerciales Registradas</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr Class="info" role="row">
                                <th>Nombre</th>
                                <th style="width: 70px;">Acción</th>
                            </tr>
                        </thead>

                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($actividades as $actividad): ?>
                                <tr>
                                    <td class=" "><?php echo $actividad['ActDes']; ?></td>
                                    <td class=" ">
                                        <a href="<?php echo base_url() . 'configuracion/actividad/editar/' . $actividad['ActId']; ?>" class="btn btn-default btn-flat" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <span data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                        <a href="#modal-eliminar" role="button" data-toggle="modal" actividad="<?php echo $actividad['ActId']; ?>" class="btn btn-default btn-flat">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
       </div>
    </div>
</section>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- page script -->
<script type="text/javascript">
    $(function () 
    {
        $("#example1").dataTable();
    });
</script>


<!-- MODAL : Eliminar Actividad  -->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-hidden="true">
<form id="idelim" method="post">    
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-edit"></i> Eliminar Actividad Comercial</h4>
    </div>
    <div class="modal-body">
        <h5 style="text-align: center">¿Realmente deseas eliminar esta Actividad Comercial y los datos asociados?</h5>
    </div>     
    <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button>
        <button type="submit" class="btn btn-danger">Eliminar</button>
    </div>
    </div>
    </div>
</form>
</div>


<script type="text/javascript">
$(document).ready(function()
{
    $('[data-toggle="tooltip"]').tooltip();
    $(document).on('click', 'a', function(event) 
    {
        var actividad = $(this).attr('actividad');
        $('#idelim').attr('action',"<?php echo base_url() . 'configuracion/actividad/eliminar/'; ?>"+actividad);
    });
});
</script>

<!--
<script>
function eliminar()
{
    $(document).on('click', 'a', function(event) 
    {  
        var actividad = $(this).attr('actividad');     
        swal({   title: "Eliminar Actividad Comercial",   
                    text: "¿Realmente deseas eliminar esta Actividad Comercial y los datos asociados?",   
                    type: "error",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "Eliminar",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true }, 
        function(isConfirm)
        {   
            if(isConfirm)
            {
                window.location.href = "<?php echo base_url() . 'actividad/eliminar/'; ?>"+actividad;
            }
        });
    });
}
</script>--> 