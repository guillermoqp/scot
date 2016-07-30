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
                        <h3 class="box-title">Datos del Perfil</h3>                                   
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">   
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <input name="descripcion" type="text" class="form-control" placeholder="Administrador"
                                           value="<?php echo set_value('descripcion', (isset($rol)) ? $rol['RolDes'] : ''); ?>" required>
                                    <p class="help-block"></p>
                                </div>
                            </div>  
                            <?php if (!isset($rol)) { ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Código:</label>
                                    <input name="codigo" type="text" class="form-control" placeholder="administrador"
                                               value="<?php echo set_value('codigo', (isset($rol)) ? $rol['RolDes'] : ''); ?>" required>
                                    <p class="help-block"></p>
                                </div>
                            </div>    
                            <?php } ?>
                        </div>  
                        <div class="row">   
                            <div class="col-md-12">
                                <div class="control-group">
                                    <label>Seleccione Permisos:</label>
                                    <div class="form-group table-responsive">
                                        <table class="table">
                                            <?php if(isset($rol['permisos'])){ $permisosTemp = array_column($rol['permisos'], 'PerId'); }
                                            foreach ($permisos as $grupo) { ?>
                                                <tr>
                                                    <td colspan="3" class="nivel1"><?php echo $grupo['GpeDes'] ?>
                                                    </td>
                                                </tr>
                                                <?php foreach ($grupo['subgrupos'] as $subgrupo) { ?>
                                                    <tr>
                                                        <td colspan="3" class="nivel2"><b><?php echo $subgrupo['GpeDes'] . ':' ?></b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <?php foreach ($subgrupo['permisos'] as $permiso) { ?>
                                                            <td class="nivel3checks">
                                                                <input name="permisos[]" type="checkbox" value="<?php echo $permiso['PerId'] ?>" 
                                                                <?php echo set_checkbox('permisos', $permiso['PerId'], (isset($rol['permisos']) ? (in_array($permiso['PerId'], $permisosTemp) ? TRUE : FALSE) : FALSE)) . '> ' . $permiso['PerDes']; ?>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
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
                url: "<?php echo base_url() . 'permisos/rol/obtener_niveles/' ?>" + tipo,
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