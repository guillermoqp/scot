<section class="content">
   <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            <form role="form" method="post">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Datos de Observaciones</h3>                                   
                </div><!-- /.box-header -->
                <div class="box-body">
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Actividad:</label>
                                    <select id="actividad" name="actividad" class="form-control" required="">
                                        <?php foreach ($actividades as $actividad) { ?>
                                            <option value="<?php echo $actividad['ActId'] ?>"<?php if (isset($observacion)) {
                                            echo ($observacion['AuxActId'] == $actividad['ActId']) ? 'selected' : '';
                                        } ?>><?php echo $actividad['ActDes'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>    
                        </div>
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Grupo:</label>
                                    <select id="grupo" name="grupo" class="form-control" required="">
                                        <?php foreach ($gruposObservacion as $grupoObservacion) { ?>
                                            <option value="<?php echo $grupoObservacion['GoaId'] ?>"<?php if (isset($observacion)) {
                                            echo ($observacion['ObsGoaId'] == $grupoObservacion['GoaId']) ? 'selected' : '';
                                        } ?>><?php echo $grupoObservacion['GoaDes'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>    
                        </div>
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Codigo:</label>
                                    <input name="codigo" type="text" class="form-control" placeholder="1"
                                           value="<?php echo (isset($observacion)) ? $observacion['ObsCod'] : set_value('codigo'); ?>" required>
                                    <p class="help-block"></p>
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Nombre :</label>
                                    <input name="descripcion" type="text" class="form-control" placeholder="General"
                                           value="<?php echo (isset($observacion)) ? $observacion['ObsDes'] : set_value('descripcion'); ?>" required>
                                    <p class="help-block"></p>
                                </div>
                            </div>  
                        </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo ($accion == 'nuevo') ? 'Registrar' : 'Actualizar'; ?></button>
                </div>
                <div class="col-md-6">
                    <button type="reset" class="btn btn-block btn-default btn-flat">Limpiar</button>
                </div>
            </div>
          </form>
       </div>
    </div>
</section>

<script type="text/javascript">// <![CDATA[
    $(document).ready(function () {
        $('#actividad').change(function () { //any select change on the dropdown with id country trigger this code
            $("#grupo > option").remove(); //first of all clear select items
            var idActividad = $('#actividad').val(); // here we are taking country id of the selected one.
            $.ajax({
                type: "POST",
                url: "<?php echo base_url().'configuracion/observacion/obtener_grupos/' ?>" + idActividad, //here we are calling our user controller and get_cities method with the country_id

                success: function (grupos) //we're calling the response json array 'cities'
                {
                    $.each(grupos, function(clave, valor) //here we're doing a foeach loop round each city with id as the key and city as the value
                    {
                        var opt = $('<option />'); // here we're creating a new select option with for each city
                        opt.val(valor.GoaId);
                        opt.text(valor.GoaDes);
                        $('#grupo').append(opt); //here we will append these new select options to a dropdown with the id 'cities'
                    });
                }

            });

        });
    });
    // ]]>
</script>