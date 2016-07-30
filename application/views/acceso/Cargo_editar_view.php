<section class="content">
    <div class="row">
        <form role="form" method="post">
            <div class="col-xs-12">
                <?php if (null!=$this->session->flashdata('mensaje')) { ?>
                    <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $_SESSION['mensaje'][1]; ?>
                    </div>
                <?php } ?>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Datos del Cargo</h3>                                   
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php if (!isset($cargo)) { ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- radio -->
                                    <div class="form-group">
                                        <div class="control-group">
                                            <label>El Nuevo Cargo pertenece a : </label>
                                            <input id="tipo" name="tipo" type="radio" value="0" required="" <?php
                                            if (isset($cargo)) {
                                                echo ($cargo['CarCst'] == FALSE) ? 'checked' : '';
                                            }
                                            ?>/>EPS
                                            <input id="tipo" name="tipo" type="radio" value="1" <?php
                                            if (isset($cargo)) {
                                                echo ($cargo['CarCst'] == TRUE) ? 'checked' : '';
                                            }
                                            ?>/>Contratista
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="row">   
                                <div class="col-md-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Nivel Organizacional:</label>
                                        <select id="nivel" name="nivel" class="form-control" required="">
                                            <?php foreach ($niveles as $nivel) { ?>
                                                <option value="<?php echo $nivel['NvlId'] ?>"<?php
                                                if (isset($rol)) {
                                                    echo ($observacion['ObsGoaId'] == $grupoObservacion['GoaId']) ? 'selected' : '';
                                                }
                                                ?>><?php echo $nivel['NvlDes'] ?></option>
                                                    <?php } ?>
                                        </select>
                                        <p class="help-block"></p>
                                    </div>
                                </div>    
                            </div>
                        <?php } ?>
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <input name="descripcion" type="text" class="form-control" placeholder="Gerente Comercial"
                                           value="<?php echo set_value('descripcion', (isset($cargo)) ? $cargo['CarDes'] : ''); ?>" required>
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
            </div>
        </form>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {
        $('input[name=tipo]:radio').change(function () {
            $("#nivel > option").remove();
            var tipo = $('input[name=tipo]:checked').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . 'permisos/cargo/obtener_niveles/' ?>" + tipo,
                success: function (niveles)
                {
                    $.each(niveles, function (clave, valor)
                    {
                        var opt = $('<option />');
                        opt.val(valor.NvlId);
                        opt.text(valor.NvlDes);
                        $('#nivel').append(opt);
                    });
                }

            });

        });
    });
    // ]]>
</script>