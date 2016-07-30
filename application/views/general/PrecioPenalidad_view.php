<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
        <?php if(!isset($precioPenalidadEdit)) { ?>
        <form role="form" method="post" action="<?php echo str_replace('/index.php', '', current_url())."/guardar" ;?>">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Grupo Penalidades</h3>                                   
                        </div><!-- /.box-header -->
                        <div class="box-body">
                                    <div class="row">   
                                            <div class="form-group"> 
                                                <div class="checkbox nivel2">
                                                    <label>
                                                    <input type="checkbox" id="seleccionar_todos"/>
                                                        Seleccionar Todos
                                                    </label>
                                                </div>
                                                <?php foreach ($gruposPenalidad as $grupoPenalidad): ?>
                                                    <div class="checkbox nivel2">
                                                    <label>
                                                        <input name="grupoPenalidades[]" type="checkbox" id="<?php echo $grupoPenalidad['GpeId'] ?>" value="<?php echo $grupoPenalidad['GpeId'] ?>">
                                                        <?php echo $grupoPenalidad['GpeId'] ?> | <?php echo $grupoPenalidad['GpeDes'] ?>
                                                    </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                    </div>
                        </div>
                    </div>
                    <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Penalidades y Multas.</h3>                                   
                            </div>
                        <div class="box-body table-responsive">      
                            <table id="tableID" class="table table-bordered">
                                <thead>
                                    <tr Class="info" id="cabecera">
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Multa</th>
                                    </tr>
                                </thead>
                                <tbody class="tbodyP" role="alert">
                                    <?php foreach ($gruposPenalidad as $grupoPenalidad) { ?>
                                            <tr id="<?php echo $grupoPenalidad['GpeId']; ?>" class="nivel1"><b>
                                                <td id="<?php echo $grupoPenalidad['GpeId']; ?>"><?php echo $grupoPenalidad['GpeCod']; ?></td>
                                                <td id="<?php echo $grupoPenalidad['GpeId']; ?>"><?php echo $grupoPenalidad['GpeDes']; ?></td>
                                                <td id="<?php echo $grupoPenalidad['GpeId']; ?>"></td></b>
                                            </tr>
                                    <?php foreach ($grupoPenalidad['penalidades'] as $penalidad) { ?>
                                                <tr id="<?php echo $grupoPenalidad['GpeId']; ?>">
                                                    <td id="<?php echo $grupoPenalidad['GpeId']; ?>"><span class="nivel2"><?php echo $penalidad['PenCod'] ?></span></td>
                                                    <td id="<?php echo $grupoPenalidad['GpeId']; ?>" class="nivel2desc"><?php echo $penalidad['PenDes']; ?></td>
                                                    <td id="<?php echo $grupoPenalidad['GpeId']; ?>"><input id="<?php echo 'mul_' . $penalidad['PenId']; ?>" name="<?php echo 'mul_' . $penalidad['PenId']; ?>" type="number" min="0.01" max="50" step="any"></td> 
                                                </tr>
                                            <?php } ?>
                                    <?php }  ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>
        <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> Registrar Nueva Precio Penalidad</button>
                </div>
                <div class="col-md-6">
                    <button type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
                </div>
        </div>
        </form>
        <?php } else {?>    
            <form role="form" method="post" action="<?php echo str_replace('/index.php', '', current_url())."/guardar" ;?>">
                    <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Penalidades y Multas.</h3>                                   
                            </div>
                        <div class="box-body table-responsive">      
                            <table class="table table-bordered">
                                <thead>
                                    <tr Class="info" >
                                        <th>Cod. Penalidad</th>
                                        <th>Penalidad</th>
                                        <th>Cod. Grupo Penalidad</th>
                                        <th>Grupo Penalidad</th>
                                        <th>Multa</th>
                                    </tr>
                                </thead>
                                <tbody class="tbodyP" role="alert">
                                    <tr>
                                        <td><?php echo $precioPenalidadEdit['PenCod']; ?></td>
                                        <td><?php echo $precioPenalidadEdit['PenDes']; ?></td>
                                        <td><?php echo $precioPenalidadEdit['GpeCod']; ?></td>
                                        <td> <?php echo $precioPenalidadEdit['GpeDes']; ?></td>
                                        <td><input name="multa" type="number" min="0.01" max="50" step="any" value="<?php echo $precioPenalidadEdit['DcpVal']; ?>"></td> 
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
        <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> Actualizar Precio Penalidad</button>
                </div>
                <div class="col-md-6">
                    <button type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
                </div>
        </div>
        </form>
        <?php }?>     
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
        $("#contratistas").dataTable();
    });
</script>

<script>
$( document ).ready(function() 
{
   <?php if(!isset($precioPenalidadEdit)) { ?>    
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

    $(':checkbox').change(function() 
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
});
</script>
