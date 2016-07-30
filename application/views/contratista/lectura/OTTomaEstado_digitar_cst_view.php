<!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datatables/buttons.dataTables.min.css">
<section class="content">
    <div class="row">
        <form role="form" method="post">
            <div class="col-xs-12">
               <div class="row">
                    <div class="col-md-4"><img style="width: 150px; height: 40px;" src="<?php echo base_url() . $userdata['contratante']['CntLog1'] ?>" alt="<?php echo $userdata['contratante']['EmpRaz'] ?>" style="position:absolute; margin-top:0; margin-bottom: 5px; left:0;" /></div>
                    <div class="col-md-4"><h4><b>TOMA DE ESTADO - LECTURAS DE CAMPO</b></h4></div>
                    <div class="col-md-4"><b>Lecturista:</b> <?php echo $lecturista['UsrApePt'].'  '.$lecturista['UsrApeMt'].'  '.$lecturista['UsrNomPr']; ?></div>
                </div>
                <div class="row">
                    <div class="col-md-4">SCOT-Sistema de Control de Ordenes de Trabajo</div>
                    <div class="col-md-4"><h5 class="modal-title"><b>Localidades: </b><?php echo $ciclo; ?></h5></div>
                    <div class="col-md-4"><b><i class="fa fa-calendar"></i> Fecha Ejecución de Suborden:</b> <?php echo date('d-m-Y',  strtotime($Suborden["SolFchEj"]))?></div>
                </div>
                <div class="row">
                    <div class="col-md-4">SubSistema de Toma de Estado de Suministros</div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4"><b>Nro de Suministros:</b> <?php echo $nroSuministros; ?></div>
                    <div class="col-md-4"><b>Página: </b><?php echo $paginaActual . ' de ' . $nroPaginas; ?></div>
                </div>   
                <div class="row">
                    <div class="col-md-12">
                         <?php echo $this->pagination->create_links(); ?>
                        <div class="table-responsive">  
                            <table id="detalleCicloSubOrden" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <tr>
                                            <th rowspan="2">ITEM</th>
                                            <th rowspan="2"><center>CÓDIGO</center></th>
                                            <th rowspan="2">NOMBRE/RAZÓN SOCIAL</th>
                                            <th colspan="3"><center>DIRECCIÓN</center></th>
                                            <th rowspan="2">MEDIDOR</th>
                                            <th rowspan="2">LECTURA</th>
                                            <th rowspan="2">OBSERVACIÓN</th>
                                            <th rowspan="2">Mensaje</th>
                                        </tr>
                                        <tr>
                                            <th>URBANIZACIÓN</th>
                                            <th>CALLE/JR./AV.</th>
                                            <th>NÚMERO</th>
                                        </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $lectura) { ?>
                                        <tr class="<?php echo isset($lectura['color'])?$lectura['color']:''?>">
                                                <td><?php echo $lectura['LecId']; ?></td>
                                                <td><?php echo $lectura['LecCodFc']; ?></td>
                                                <td><?php echo $lectura['LecNom']; ?></td>
                                                <td><?php echo $lectura['LecUrb']; ?></td>
                                                <td><?php echo $lectura['LecCal']; ?></td>
                                                <td><?php echo $lectura['LecMun']; ?></td>
                                                <td><?php echo $lectura['LecMed']; ?></td>
                                                <td><input name="<?php echo  'LecVal_' . $lectura['LecId']; ?>" id="<?php echo  'LecVal_' . $lectura['LecId']; ?>" type="number" min="0" step="1" class="form-control" value="<?php echo $lectura['LecVal']; ?>" <?php echo isset($lectura['bloqueado'])?'disabled':''?>></td>
                                                <td> 
                                                    <select name="<?php echo  'LecObs_' . $lectura['LecId'] . '[]' ?>" id="<?php echo  'LecObs_' . $lectura['LecId'] . '[]' ?>" class="multiple form-control select2" multiple="" tabindex="-1" aria-hidden="true" <?php echo isset($lectura['bloqueado'])?'disabled':''?>>
                                                        <?php foreach ($GrupoObservaciones as $GrupoObservacion) { ?>    
                                                            <optgroup label="<?php echo $GrupoObservacion['GoaDes']; ?>">
                                                              <?php $observaciones = $this->Observacion_model->get_observacion_x_grupo($GrupoObservacion['GoaId']); ?>
                                                              <?php foreach ($observaciones  as $observacion) { ?>
                                                                <option value="<?php echo $observacion['ObsId']; ?>" <?php echo in_array($observacion['ObsId'], $lectura['observaciones'])?'selected':''; ?>><?php echo $observacion['ObsCod']; ?> - <?php echo $observacion['ObsDes']; ?></option>
                                                              <?php } ?>
                                                            </optgroup>
                                                        <?php } ?>
                                                    </select> 
                                                </td>
                                                <td>
                                                    <?php echo isset($lectura['mensaje'])?$lectura['mensaje']:''?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                            </table>
                        </div>     
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i> <?php echo ($accion == 'digitar') ? 'Guardar' : 'Actualizar'; ?></button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" onclick="window.close();" class="btn btn-block btn-default btn-flat">Cerrar</button>
                    </div>
                </div>  
            </div>
        </form>
    </div>
</section>

<!-- Select2 -->
<script src="<?php echo base_url() ?>frontend/plugins/select2/select2.full.min.js"></script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/buttons.flash.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jszip.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/pdfmake.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/vfs_fonts.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/buttons.html5.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/buttons.print.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.select.min.js" type="text/javascript"></script>

<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() ?>frontend/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url() ?>frontend/plugins/fastclick/fastclick.js"></script>

<script>
<?php if (isset($cerrar)) { ?>
window.onunload = refreshParent;
function refreshParent() {
    window.opener.location.reload();
}
window.close();
<?php } ?>
</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/es.js"></script>
<script type="text/javascript">
$(function () 
{
    $(".select2").select2();
    $(".multiple").select2({
        placeholder: "Elija Observación de Lectura",
        allowClear: true,
        tags: true,
        language: "es",
        maximumSelectionLength: 3,
        formatSelectionTooBig: function (limit) 
        {
            return 'Solo Puedes elegir 3 Observaciones';
        }
    });
    
    $("#detalleCicloSubOrden").dataTable({
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
    "bSort": false, "paging": false,
    "iDisplayLength" : 26,
    "buttons": 
        ['excel', 'pdf'],
        "select": true
        });
    });
</script>
 
