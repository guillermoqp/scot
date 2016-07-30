<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['mensaje'][1]; ?>
                </div>
            <?php } ?>
            
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Ciclos</h3>                                   
                </div>
                <div class="box-body table-responsive">
                    <table id="ciclos" class="table table-bordered table-striped">
                        <thead>
                            <tr class="info" role="row">
                                <th>codigo</th>
                                <th>nombres</th>
                                <th>urb</th>
                                <th>cal</th>
                                <th>mun</th>
                                <th>med</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($ciclos as $ciclo): ?>
                                <tr>
                                    <td class=" "><?php echo $ciclo['CliCodFac']; ?></td>
                                    <td class=" "><?php echo $ciclo['CliNom']; ?></td>
                                    <td class=" "><?php echo $ciclo['CliUrb']; ?></td>
                                    <td class=" "><?php echo $ciclo['CliCal']; ?></td>
                                    <td class=" "><?php echo $ciclo['CliMun']; ?></td>
                                    <td class=" "><?php echo $ciclo['CliMed']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!--
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Usuarios</h3>                                   
                </div>
                <div class="box-body table-responsive">
                    <table id="ciclos" class="table table-bordered table-striped">
                        <thead>
                            <tr class="info" role="row">
                                <th>COD FAC.</th>
                                <th>APE MA </th>
                                <th>APE PA</th>
                                <th>NOMBRES</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td class=" "><?php echo $usuario['CLICODFAC']; ?></td>
                                    <td class=" "><?php echo $usuario['FUSUAPEMA']; ?></td>
                                    <td class=" "><?php echo $usuario['FUSUAPEPA']; ?></td>
                                    <td class=" "><?php echo $usuario['FUSUNOMBR']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
             -->
            
        </div>
    </div>
</section>

<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>


<script type="text/javascript">
$(document).ready(function ()
{
    $("#ciclos").dataTable();
});
</script>

