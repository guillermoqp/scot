<style type="text/css">
    html { height: 100% }
    body { height: 100%; margin: 5; }
    #mapDiv { width: 100%; height: 100%; }
</style>
<!-- REPORTE DE ORDEN DE TRABAJO -->
<section class="invoice">
    <?php if (isset($_SESSION['mensaje'])) { ?>
        <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $_SESSION['mensaje'][1]; ?>
        </div>
    <?php } ?>
    <form role="form" accept-charset="utf-8" enctype="multipart/form-data" method="post" action="<?php echo str_replace('/index.php', '', current_url()) . "/guardar"; ?>">
        <div id="impresion">
            <div class="row">
                <div class="col-xs-12">
                    <h5><i class="fa fa-file"></i> ORDEN DE TRABAJO N°: <?php echo $OTTomaEstado["OrtNum"] . '  -  ' . $OTTomaEstado["ActDes"]; ?></h5>
                    <small class="pull-right">Fecha de Envio: <?php echo date('d/m/Y', strtotime($OTTomaEstado["OrtFchEn"])) ?></small>
                </div>
            </div>
            <hr style="margin-top:10px;margin-bottom: 10px;">
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    <b>Contratista:</b> <?php echo $contratista['CstRaz']; ?><br>
                    <b>Teléfono:</b> <?php echo $contratista['CstTel1']; ?><br>
                    <b>E-mail:</b> <?php echo $contratista['CstCor1']; ?>
                </div>
                <!-- /.col -->
                <div class="col-sm-6 invoice-col">
                    <b><i class="fa fa-calendar"></i> Fecha Ejecución:</b> <?php echo date('d-m-Y', strtotime($OTTomaEstado["OrtFchEj"])) ?> <br>
                    <b><i class="fa fa-calendar"></i> Fecha Máxima de Recepción:</b> <?php echo date('d-m-Y H:i:s', strtotime($OTTomaEstado["OrtFchEm"])) ?> <br>
                    <b><i class="fa fa-calendar"></i> Mes de Facturación:</b> <?php echo $mesFact; ?> / <?php echo $anioFact; ?><br>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <hr style="margin-top:10px;margin-bottom: 10px;">
            <!-- Table row -->
            <div class="row invoice-info">
                <div class="col-xs-12">
                    <h4><b>Lecturista:</b> <?php echo $lecturista['UsrApePt'] . ' ' . $lecturista['UsrApeMt'] . ' ' . $lecturista['UsrNomPr'] ?></h4>
                    <div class="table-responsive">
                        <table id="lecturas" class="table table-bordered table-striped">
                            <thead>
                                <tr Class="info" role="row">
                                    <th>Código</th>
                                    <th>Nombre/Razón social</th>
                                    <th>Urbanización</th>
                                    <th>Calle/Jirón/Av.</th>
                                    <th>Número</th>
                                    <th>Medidor</th>
                                    <th>Lectura</th>
                                    <th>Observaciones</th>
                                    <th>Comentario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lecturas as $lectura): ?>
                                    <tr>
                                        <td><?php echo $lectura['LecCodFc']; ?></td>
                                        <td><?php echo $lectura['LecNom']; ?></td>
                                        <td><?php echo $lectura['LecUrb']; ?></td>
                                        <td><?php echo $lectura['LecCal']; ?></td>
                                        <td><?php echo $lectura['LecMun']; ?></td>
                                        <td><?php echo $lectura['LecMed']; ?></td>
                                        <td><?php echo isset($lectura['LecImg']) ? '<a href="#" onclick="mostrarImagen(\'' . $lectura['LecImg'] . '\')">' . $lectura['LecVal'] . '</a>' : $lectura['LecVal']; ?></td>
                                        <td><?php
                                            $obsTemp = array();
                                            if (isset($lectura['observaciones'])) {
                                                foreach ($lectura['observaciones'] as $key => $observacion) {
                                                    $obsTemp[$key] = isset($observacion['OleImg']) ? '<a href="#" onclick="mostrarImagen(\'' . $observacion['OleImg'] . '\')">' . $observacion['ObsCod'] . '</a>' : $observacion['ObsCod'];
                                                }
                                            }
                                            echo implode(', ', $obsTemp);
                                            ?></td>
                                        <td><?php echo $lectura['LecCom']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr style="margin-top:10px;margin-bottom: 10px;">
            <!-- Table row -->
            <div class="row no-print invoice-info">
                <div class="col-xs-12">
                    <h4><b>Observaciones:</b></h4>
                    <div class="table-responsive">
                        <table id="observaciones" class="table table-bordered table-striped">
                            <thead>
                                <tr Class="info" role="row">
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($observaciones as $observacion) { ?>
                                    <tr>
                                        <td><?php echo $observacion['ObsCod']; ?></td>
                                        <td><?php echo $observacion['ObsDes']; ?></td>
                                        <td><?php echo $observacion['cantidad']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.row -->
            <hr style="margin-top:10px;margin-bottom: 10px;">
            <!-- Table row -->
            <div class="row no-print invoice-info">
                <div class="col-xs-12">
                    <h4><b>Ruta de Lecturista por Geolocalizacion del Dispositivo:</b></h4>
                    <div id="mapDiv" style="width: 850px; height: 300px"></div>
                    <!-- /.col -->
                </div>
            </div>
        </div>
    </form>   
</section>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title" id="myModalLabel">Toma Fotográfica</h4>
            </div>
            <div class="modal-body">
                <center>
                    <img class="img-responsive" src="" id="imagepreview" style="width: 400px; height: 264px;" >
                </center>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url() ?>frontend/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script type="text/javascript">
$( document ).ready(function() {
        $("#lecturas").dataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "bSort": false});
        $("#observaciones").dataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "bSort": false});
});
function mostrarImagen(ruta) {
    $('#imagepreview').attr('src', '<?php echo base_url(); ?>' + ruta);
    $('#imagemodal').modal('show');
};
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyB74nn6Y3KeLpGM4VY_P6xPr0Gtzge7bgc&sensor=False"></script>
<script type="text/javascript">
var map;
var polyline;
var lineCoordinates = new Array();
var callAjax = function(){
    $.ajax({
        url: "<?php echo base_url().'cst/toma_estado/orden_avance_suborden_json'; ?>",
        type: "POST",
        data: ({idOrden: <?php echo $OTTomaEstado["OrtId"]; ?> ,subOrden: <?php echo $idSubOrden; ?> }),
        success:function(data)
        {
            if(data.result === false)
            {
                lineCoordinates = new Array();
            }
            if(data.result === true)
            {
                lineCoordinates = new Array();
                for (var i=0; i < data.LecPosLt.length ; i++) {
                var tempLatLng = new Array(data.LecPosLt[i], data.LecPosLg[i]);
                lineCoordinates.push(tempLatLng);
                }
            }
        }
    });
}

function addAnimatedPolyline () {
            var pointCount = lineCoordinates.length;
            var linePath = [];
            for (var i=0; i < pointCount; i++) {
                var tempLatLng = new google.maps.LatLng(lineCoordinates[i][0], lineCoordinates[i][1]);
                linePath.push(tempLatLng);
            }
            var arrowSymbol = {
                strokeColor: '#070',
                scale: 4,
                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
            };
            var lineOptions = {
                path: linePath,
                icons: [{
                    icon: arrowSymbol,
                    offset: '100%'
                }],
                strokeWeight: 3,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8
            }
            polyline = new google.maps.Polyline(lineOptions);
            polyline.setMap(map);
            animateArrow();
        }
        function animateArrow() {
            var counter = 0;
            var accessVar = window.setInterval(function() {
                counter = (counter + 1) % 350;
                var arrows = polyline.get('icons');
                arrows[0].offset = (counter / 2) + '%';
                polyline.set('icons', arrows);
            }, 10);
        }
        function initMap() {
            google.maps.visualRefresh = true;
            if(lineCoordinates.length === 0)
            {
                Latitud = -8.1059272;
                Longitud = -78.998292; 
            }
            else
            {
                Latitud = lineCoordinates[0][0];
                Longitud = lineCoordinates[0][1];
            }
            var mapOptions = {
                center: new google.maps.LatLng(Latitud,Longitud),
                zoom: 25,
                navigationControl: false,
                mapTypeControl: false,
                scaleControl: false,
                mapTypeId: google.maps.MapTypeId.HYBRID
            };
            var mapElement = document.getElementById('mapDiv');
            map = new google.maps.Map(mapElement, mapOptions);
            addAnimatedPolyline();
        }
        google.maps.event.addDomListener(window, 'load', initMap); 
        setInterval(callAjax,1000);
        setInterval(addAnimatedPolyline,1000);
</script>
</body>
</html>

