<style type="text/css">
#map_canvas{
    width:50%;
    height:300px !important;
}
</style>
<!-- REPORTE DE ORDEN DE TRABAJO -->
<section class="invoice">
    <?php if (isset($_SESSION['mensaje'])) { ?>
        <div class="alert alert-<?php echo ($_SESSION['mensaje'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $_SESSION['mensaje'][1]; ?>
        </div>
    <?php } ?>
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
                    <b><i class="fa fa-calendar"></i> Fecha Inicio Ejecución OT:</b> <?php echo date('d-m-Y', strtotime($OTTomaEstado["OrtFchEj"])) ?> <br>
                    <b><i class="fa fa-calendar"></i> Fecha Máxima de Recepción OT:</b> <?php echo date('d-m-Y H:i:s', strtotime($OTTomaEstado["OrtFchEm"])) ?> <br>
                    <b><i class="fa fa-calendar"></i> Mes de Facturación:</b> <?php echo $mesFact; ?> / <?php echo $anioFact; ?><br>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <hr style="margin-top:10px;margin-bottom: 10px;">
            
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                <b><i class="fa fa-bars"></i> Codigo de SubOrden :</b> <?php echo $subOrden["SolCod"]; ?>
                </div>
                <div class="col-sm-4 invoice-col">
                <b><i class="fa fa-calendar"></i> Fecha Ejecución de SubOrden :</b> <?php echo date('d-m-Y', strtotime($subOrden["SolFchEj"])) ?>
                </div>
             </div>
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
                    <div id="map_canvas" style="width: 900px; height: 250px;"></div>
                    <!-- /.col -->
                </div>
            </div>
        </div> 
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
                    <img class="img-responsive" src="" id="imagepreview" style="width: 400px; height: 264px;">
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
$(document).ready(function ()
{
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
<?php if(is_array($lecturas)) { ?>
    var latitudes = <?php echo json_encode(array_column($lecturas, 'LecPosLt')); ?>;
    var longitudes = <?php echo json_encode(array_column($lecturas,'LecPosLg')); ?>;
    var tiempos = <?php echo json_encode(array_column($lecturas,'LecFchRgMov')); ?>;
    var calles = <?php echo json_encode(array_column($lecturas,'LecCal')); ?>;
    var numeroMunic = <?php echo json_encode(array_column($lecturas,'LecMun')); ?>;
    var medidores = <?php echo json_encode(array_column($lecturas,'LecMed')); ?>;
<?php } ?>
var userCoor = new Array();
var userCoorPath = new Array();

for(var i = 0; i < latitudes.length; i++){
    var n = i+1;
    userCoor.push( new Array( '<b>Lectura N°'+n.toString()+'</b><br><b>Direccion :</b>'+calles[i]+',<b>Nro :</b>'+numeroMunic[i]+'<br><b>Tiempo :</b>'+tiempos[i] , latitudes[i] , longitudes[i]) );
    userCoorPath.push( new google.maps.LatLng(latitudes[i],longitudes[i]) );
}
var map,centroLatitud,centroLongitud;
/*
if(userCoor[0][1]!=null&&userCoor[0][2]!=null)
{
    centroLatitud = userCoor[0][1];
    centroLongitu = userCoor[0][2];
}
else{
    centroLatitud = -8.110706;
    centroLongitud = -79.032943;
}
*/
var mapOptions = {
    center: new google.maps.LatLng(-8.110706,-79.032943), 
    zoom: 25,
    navigationControl: false,
    mapTypeControl: false,
    scaleControl: false,
    mapTypeId: google.maps.MapTypeId.HYBRID
};
function initialize() {
map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
var userCoordinate = new google.maps.Polyline({
path: userCoorPath,
strokeColor: "#FF0000",
strokeOpacity: 5,
strokeWeight: 6
});
userCoordinate.setMap(map);
var infowindow = new google.maps.InfoWindow();
var marker, i;

for (i = 0; i < userCoor.length; i++) {  
  
    marker = new google.maps.Marker({
        position: new google.maps.LatLng(userCoor[i][1], userCoor[i][2]),
        map: map
    });
    google.maps.event.addListener(marker, 'click', (function(marker, i) {
    return function() {
      infowindow.setContent(userCoor[i][0]);
      infowindow.open(map, marker);
    }
  })(marker, i));
}

}
google.maps.event.addDomListener(window, 'load', initialize);
</script>

