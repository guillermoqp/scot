<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-4"><img style="width: 150px; height: 40px;" src="<?php echo base_url() . $userdata['contratante']['CntLog1'] ?>" alt="<?php echo $userdata['contratante']['EmpRaz'] ?>" style="position:absolute; margin-top:0; margin-bottom: 5px; left:0;" /></div>
                <div class="col-md-4"><h4><b>TOMA DE ESTADO - LECTURAS DE CAMPO</b></h4></div>
                <div class="col-md-4"><b>Lecturista:</b> <?php echo $lecturista['UsrApePt'] . '  ' . $lecturista['UsrApeMt'] . '  ' . $lecturista['UsrNomPr']; ?></div>
            </div>
            <div class="row">
                <div class="col-md-4">SCOT-Sistema de Control de Ordenes de Trabajo</div>
                <div class="col-md-4"><b><i class="fa fa-calendar"></i> Fecha Ejecución:</b> <?php echo date('d/m/Y', strtotime($OTTomaEstado["OrtFchEj"])) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4">SubSistema de Toma de Estado de Suministros</div>
                <div class="col-md-4"></div>
            </div> 
            <div class="row">
                <div class="col-md-12">
                    <?php if (isset($_SESSION['mensaje'])) { ?>
                        <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $_SESSION['mensaje'][1]; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <button type="button" onclick="window.close();" class="btn btn-block btn-primary btn-flat">Cerrar Ventana</button><br><br>
                </div>
            </div>
        </div> 
    </div>
</section>