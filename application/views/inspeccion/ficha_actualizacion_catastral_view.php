<!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datatables/buttons.dataTables.min.css">
<style>
    .modal .modal-dialog { width: 95%; }
</style>
<section class="content">
    <div class="row">
        
        <form method="post"> 
            
            <div class="col-xs-12">
                <?php if (isset($_SESSION['mensaje2'])) { ?>
                    <div class="alert alert-<?php echo ($_SESSION['mensaje2'][0] == 'error') ? 'danger' : 'success'; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $_SESSION['mensaje2'][1]; ?>
                    </div>
                <?php } ?>
                
                <center><h4 class="modal-title"><b>FIFHA DE EMPADRONAMIENTO/ACTUALIZACION CATASTRAL - 2016</b></h4></center>
                
                <div class="row">   
                    <div class="col-md-4">
                        <img style="width: 210px; height: 45px;" src="<?php echo base_url() . $userdata['contratante']['CntLog1'] ?>" alt="<?php echo $userdata['contratante']['EmpRaz'] ?>"> 
                    </div>   
                    <div class="col-md-4"></div> 
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-3">CÓDIGO<BR>CENSAL</div> 
                            <div class="col-md-1"><input name="codCensal" id="cdoSensal" type="text"></div> 
                        </div>
                    </div> 
                </div>
                <div class="row">   
                    <div class="col-md-4">
                        <img style="width: 210px; height: 45px;" src="<?php echo base_url() . $userdata['contratista']['CstLog1'] ?>" alt="<?php echo $userdata['contratista']['EmpRaz'] ?>"> 
                    </div>   
                    <div class="col-md-4">
                        <img src="<?php echo base_url() ?>generar_cod_barras_code39/<?php echo $usuario['CliCodFac'];?>">
                    </div> 
                    <div class="col-md-4">
                        <div class="row">
                            <input id="FechaEmpadronamiento" name="FechaEmpadronamiento" type="text" data-toggle="tooltip" title="Fecha de Empadronamiento" value="<?php echo date('d-m-Y') ?>"/> 
                        </div>
                        <div class="row">Fecha de Empadronamiento</div>
                    </div> 
                </div>
                
                <div class="box box-default color-palette-box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Informacion Referencial (BD)</h3>
                    </div>
                    <div class="box-body">
                      <div class="row">
                            <div class="col-md-6">
                                <b>CODIGO:</b><?php echo $usuario['CliCodFac'];?>
                            </div>
                            <div class="col-md-6">
                                <b>LOTE:</b><?php echo $usuario['CliMun'];?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <b>NOMBRE:</b><?php echo $usuario['CliNom'];?>
                            </div>
                            <div class="col-md-6">
                                <b>CICLO:</b><?php echo $usuario['CliCic'];?>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <b>URBANIZACION:</b><?php echo $usuario['CliUrb'];?>
                            </div>
                            <div class="col-md-6">
                                <b>N.MUNIC/INT/LOTE:</b><?php echo $usuario['CliMun'];?>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <b>CALLE:</b><?php echo $usuario['CliCal'];?>
                            </div>
                            <div class="col-md-6">
                                <b>MEDIDOR:</b><?php echo $usuario['CliMed'];?>
                            </div>  
                        </div>

                      <!-- /.row -->
                    </div>
                    <!-- /.box-body -->
                </div>
                
            <div class="box box-default color-palette-box">
            <div class="box-header with-border">
              <h3 class="box-title">Informacion Actualizada de Campo</h3>
            </div>
            <div class="box-body">
              
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">NOMBRE</label>
                        <div class="col-sm-8">
                          <input type="text" name="nombre" id="nombre" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">DNI</label>
                        <div class="col-sm-6">
                          <input name="dni" id="dni" type="text" class="form-control" id="dni" data-inputmask='"mask": "99999999"' data-mask>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">REFERENCIA UBICACION PREDIO</label>
                        <div class="col-sm-6">
                        <textarea class="form-control" name="ref_ubi" id="ref_ubi" type="text"></textarea>
                        </div>
                    </div>
                </div>  
            </div>
                
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">DIRECCION</label>
                        <div class="col-sm-8">
                          <input type="text" name="direccion" id="direccion" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">N° MUNICIPAL</label>
                        <div class="col-sm-6">
                          <input type="text" name="num_munic" id="num_munic" class="form-control">
                        </div>
                    </div>
                </div> 
            </div>
                
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">URB.</label>
                        <div class="col-sm-8">
                          <input type="text" name="urb" id="urb" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    SECTOR<br>CENSAL <input type="number" step="1" min="0" max="1000000" name="sect_censal" id="" class="form-control">
                </div>
                <div class="col-md-2">
                    MZ<br>CENSAL<input type="number" step="1" min="0" max="1000000" name="mnz_censal" id="mzn_censal" class="form-control">
                </div>
                <div class="col-md-2">
                   LOTE<br>CENSAL<input type="number" step="1" min="0" max="1000000" name="lt_censal" id="lt_censal" class="form-control">
                </div> 
            </div>    
          </div>
        </div>
                
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <center><h3 class="box-title"><b>PREDIO-INMUEBLE</b></h3></center>
            </div>
            <div class="box-body">
              
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>008. ESTADO DEL PREDIO</label>
                        <select name="estado_predio" id="estado_predio" class="form-control">
                          <option value="1">1. CONSTRUIDO OCUPADO</option>
                          <option value="2">2. CONSTRUIDO DESOCUPADO</option>
                          <option value="3">3. SIN CONSTRUIR BALDIO</option>
                          <option value="4">4. EN CONSTRUCCION OCUPADO</option>
                          <option value="5">5. EN CONSTRUCCION DESOCUPADO</option>
                          <option value="6">6. CERCADO OCUPADO</option>
                          <option value="7">7. CERCADO DESOCUPADO</option>
                          <option value="8">8. AREA VERDE</option>
                        </select>
                    </div>
                </div>      
                <div class="col-md-4">
                    <dl class="dl-horizontal">
                        <dt>013. Nº PISOS</dt><dd><input type="number" name="nro_pisos" id="nro_pisos" step="1" min="0" max="1000000" class="form-control"></dd>
                        <dt>019. Nº CONEXIONES<br>DE AGUA</dt><dd><input  type="number" name="nro_conex_agua" id="nro_conex_agua" step="1" min="0" max="1000000"  class="form-control"></dd>
                        <dt>020. Nº CONEXIONES<br>DE DESAGUE</dt><dd><input  type="number" name="nro_conex_desague" id="nro_conex_desague" step="1" min="0" max="1000000"  class="form-control"></dd>
                    </dl>
                </div>
            </div>
                
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>017. TIPO DE ALMACENAMIENTO</label>
                        <select name="tipo_almacenamiento" id="tipo_almacenamiento" class="form-control">
                          <option value="1">1. SIN ALMACENAMIENTO</option>
                          <option value="2">2. CISTERNA</option>
                          <option value="3">3. TANQUE ELEVADO</option>
                          <option value="4">4. 2 y 3</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <dl class="dl-horizontal">
                        <dt>028. CON PAVIMENTO</dt><dd>SI : <input name="con_pavimento" id="con_pavimento" type="radio" value="1"> , NO : <input name="con_pavimento" id="con_pavimento" type="radio" value="2"></dd>
                        <dt>029. PASA RED DE AGUA</dt><dd>SI : <input name="pasa_red_agua" id="pasa_red_agua" type="radio" value="1"> , NO : <input name="pasa_red_agua" id=pasa_red_agua" type="radio" value="2"></dd>
                        <dt>030. PASA RED DE DESAGUE</dt><dd> SI : <input name="pasa_red_desague" id="pasa_red_desague" type="radio" value="1"> , NO : <input name="pasa_red_desague" id=pasa_red_desague" type="radio" value="2"></dd>
                    </dl>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label>027. MATERIAL VEREDA</label>
                        <select name="material_vereda" id="" class="form-control">
                          <option value="1">1. TIERRA</option>
                          <option value="2">2. LOSETA</option>
                          <option value="3">3. CONCRETO</option>
                          <option value="5">5. ADOQUIN</option>
                        </select>
                    </div>
                </div> 
            </div>

          </div>
        </div>
        
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <center><h3 class="box-title"><b>UNIDADES DE USO (CLIENTE)</b></h3></center>
            </div>
            <div class="box-body">
              
            <div class="row">
                <div class="col-md-3">
                    <dl class="dl-horizontal">
                        <dt>018. Nº UNIDADES<br>DE USO </dt><dd><input type="number" name="nro_unidades_uso" id="" step="1" min="0" max="1000000" class="form-control"></dd>
                    </dl>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">120. USO DEL INMUEBLE</label>
                        <div class="col-sm-8">
                            <input type="text" name="uso_inmueble" id="" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

          </div>
        </div>      
        
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <center><h3 class="box-title"><b>DATOS DEL MEDIDOR</b></h3></center>
            </div>
            <div class="box-body">
              
            <div class="row">
                <div class="col-md-8">
                <div class="row">
                    <div class="form-group">
                        <label>408. ESTADO ACTUAL DEL MEDIDOR</label>
                        <select name="estado_actual_medidor" id="" class="form-control">
                          <option value="0">0. OPERATIVO</option>
                          <option value="1">1. ENTERRADO</option>
                          <option value="2">2. LUNETA EMPAÑADA</option>
                          <option value="3">3. LUNETA ROTA ó PERFORADA</option>
                          <option value="4">4. MANIPULADO POR EL USUARIO</option>
                          <option value="5">5. INVERTIDO</option>
                          <option value="6">6. PARALIZADO</option>
                          <option value="7">8. SIN MEDIDOR</option>
                          <option value="8">9.IMPOSIBILIDAD</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <dl class="dl-horizontal">
                            <dt>LECTURA<br>DEL MEDIDOR</dt><dd><input type="number" name="lectura_medidor" id="" step="1" min="0" max="1000000" class="form-control"></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="dl-horizontal">
                            <dt>409. NUMERO <br>DEL MEDIDOR</dt><dd><input type="text" name="nro_medidor" id="" class="form-control"></dd>
                        </dl>
                    </div>
                </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>405. DIAMETRO DEL MEDIDOR</label>
                        <select name="diametro_medidor" id="" class="form-control">
                          <option value="1">1.    1/2"</option>
                          <option value="2">2.    3/4"</option>
                          <option value="3">3.    1"</option>
                          <option value="4">4.    1  1/2"</option>
                          <option value="5">5.    2"</option>
                          <option value="6">6.    3"</option>
                          <option value="7">7.    4"</option>
                          <option value="8">8.    6"</option>
                          <option value="9">9.    8"</option>
                        </select>
                    </div>
                </div>
            </div>

          </div>
        </div>
                
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <center><h3 class="box-title"><b>CONEXIÓN DE AGUA POTABLE</b></h3></center>
            </div>
            <div class="box-body">
            <div class="row">
                <dl class="dl-horizontal">
                    <dt>212. TIENE CAJA</dt><dd>SI : <input name="tiene_caja" id="tiene_caja" type="radio" value="1"> , NO : <input name="tiene_caja" id="tiene_caja" type="radio" value="2"></dd>
                </dl>
            </div>  
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>213. ESTADO DE LA CAJA</label>
                        <select name="estado_caja_agua" id="" class="form-control">
                          <option value="1">1. BUENO</option>
                          <option value="2">2. SELLADA</option>
                          <option value="3">3. DETERIORADA</option>
                          <option value="4">4. CAJA REGISTRO ENTERRADA</option>
                          <option value="5">5. CAJA DE REGISTRO CON FUGA</option>
                          <option value="6">6. NO EXISTE</option>
                          <option value="7">7. POR VERIFICAR</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>215. MAT. CAJA DE AGUA</label>
                        <select name="material_caja_agua" id="" class="form-control">
                          <option value="1">1. CONCRETO</option>
                          <option value="2">2. METAL</option>
                          <option value="3">3. POR VERFICAR</option>
                          <option value="4">4. NO EXISTE</option>
                          <option value="5">5. TERMOPLASTICO</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>217. UBICACIÓN DE LA CAJA</label>
                        <select name="ubicacion_caja_agua" id="" class="form-control" tabindex="-1" aria-hidden="true">
                          <optgroup label="1. EN VEREDA">
                            <option value="1">A. FRONTIS</option>
                            <option value="1">B. LATERAL</option>
                            <option value="1">C. ESPALDA DEL PREDIO</option>
                          </optgroup>
                          <option value="2">2. EN INTERIOR LIBRE ACCESO</option>
                          <option value="3">3. INTERIOR CERCO DE INMUEBLE</option>
                          <option value="4">4. EN INTERIOR DEL INMUEBLE</option>
                          <option value="6">6. NO EXISTE</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>218. ESTADO DE LA CONEXIÓN DE AGUA</label>
                        <select name="estado_conexion_agua" id="" class="form-control">
                          <option value="1">1. ACTIVA</option>
                          <option value="2">2. INACTIVA</option>
                          <option value="3">3. CORTADA</option>
                          <option value="4">4. LEVANTADA</option>
                          <option value="5">5. NO EXISTE</option>
                          <option value="6">6. POR VERIFICAR</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>225. MATERIAL DE LA TAPA DE AGUA</label>
                        <select name="material_tapa_agua" id="" class="form-control">
                          <option value="1">1. NO EXISTE</option>
                          <option value="2">2. F, FUNDIDO</option>
                          <option value="3">3. CONCRETO</option>
                          <option value="4">4. MADERA</option>
                          <option value="5">5. F. GALVANIZADO</option>
                          <option value="6">6. T. PLASTICO SIMPLE</option>
                          <option value="7">7. T.PLASTICO MAG.</option>
                          <option value="8">8. POR VERIFICAR</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>226. ESTADO DE LA TAPA DE AGUA</label>
                        <select name="estado_tapa_agua" id="" class="form-control">
                          <option value="1">1. NO TIENE</option>
                          <option value="2">2. B. CONDICION</option>
                          <option value="3">3. TAPA ROTA</option>
                          <option value="4">4. TAPA SELLADA</option>
                          <option value="5">5. POR VERIFICAR</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                        <label>210. DIAMETRO DE LA CONEXIÓN AGUA</label>
                        <select name="diametro_conexion_agua" id="" class="form-control">
                          <option value="1">1.    1/2"</option>
                          <option value="2">2.    3/4"</option>
                          <option value="3">3.    1"</option>
                          <option value="4">4.    1  1/2"</option>
                          <option value="5">5.    2"</option>
                          <option value="6">6.    3""</option>
                          <option value="7">7.    4"</option>
                          <option value="8">8.    6"</option>
                          <option value="9">9.    8"</option>
                        </select>
                </div>
                </div>   
            </div>

        </div>
                
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <center><h3 class="box-title"><b>CONEXIÓN DE DESAGUE</b></h3></center>
            </div>
            <div class="box-body">
            <div class="row">
                <dl class="dl-horizontal">
                    <dt>311. TIENE CAJA </dt><dd>SI : <input name="tiene_caja_desague" id="tiene_caja_desague" type="radio" value="1"> , NO : <input name="tiene_caja_desague" id="tiene_caja_desague" type="radio" value="2"></dd>
                </dl>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>318. UBICACIÓN DE LA CAJA</label>
                        <select name="ubicacion_caja_desague" id="" class="form-control" tabindex="-1" aria-hidden="true">
                          <optgroup label="1. EN VEREDA">
                            <option value="1">A. FRONTIS</option>
                            <option value="1">B. LATERAL</option>
                            <option value="1">C. ESPALDA DEL PREDIO</option>
                          </optgroup>
                          <option value="2">2. EN INTERIOR LIBRE ACCESO</option>
                          <option value="3">3. INTERIOR CERCO DE INMUEBLE</option>
                          <option value="4">4. EN INTERIOR DEL INMUEBLE</option>
                          <option value="5">5. POR VERIFICAR</option>
                          <option value="6">6. NO EXISTE</option>
                        </select>
                    </div>
                </div>   

                <div class="col-md-4">
                    <div class="row">
                        <div class="form-group">
                            <label>321. ESTADO DE LA TAPA DE DESAGUE</label>
                            <select name="estado_caja_desague" id="" class="form-control">
                              <option value="1">1. NO TIENE</option>
                              <option value="2">2. B. CONDICION</option>
                              <option value="3">3. T, ROTA</option>
                              <option value="4">4. T, SELLADA</option>
                              <option value="5">5. POR VERIFICAR</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label>316. ESTADO DE LA CONEXIÓN DE DESAGUE</label>
                            <select name="estado_conexion_desague" id="" class="form-control">
                              <option value="1">1. ACTIVA</option>
                              <option value="2">2. INACTIVA</option>
                              <option value="3">3. CORTADA</option>
                              <option value="4">4. LEVANTADA</option>
                              <option value="5">5. NO EXISTE</option>
                              <option value="6">6. POR VERIFICAR</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                            <label>320. MAT. DE LA TAPA</label>
                            <select name="material_tapa_desague" id="" class="form-control">
                              <option value="1">1. NO EXISTE</option>
                              <option value="2">2. FIERRO</option>
                              <option value="3">3. CONCRETO</option>
                              <option value="4">4. MADERA</option>
                              <option value="5">5. POR VERIFICAR</option>
                            </select>
                        </div>
                </div>
            </div>
            </div>
        </div>            
                
        <div class="row">   
            <div class="col-md-8">
                OBSERVACIONES :
                <textarea class="form-control" type="text" name="observaciones" id=""></textarea>
            </div>   
            <div class="col-md-4">
                <center>Numero municipal/ Numero Lote</center><br>
                <div class="col-md-3">
                    Derecho <input type="text" name="num_munic_derech" id="" class="form-control">
                </div>
                <div class="col-md-3">
                    Censado <input type="text" name="num_munic_censado" id="" class="form-control">
                </div>
                <div class="col-md-3">
                    Izquierdo<input type="text" name="num_munic_izquierdo" id="" class="form-control">
                </div> 
            </div> 
        </div>  
        <hr style="margin-top:10px;margin-bottom: 10px;">
        <div class="row">   
            <div class="col-md-8">
                <dl class="dl-horizontal">
                    <dt>ENCUESTADO :</dt><dd><input type="text" class="form-control" name="encuestado" id=""></dd>
                    <dt>DNI : </dt><dd><input type="text" class="form-control" name="dni_encuestado" id="dni_encuestado"></dd>
                    <dt>PARENTESCO :</dt><dd><input type="text" class="form-control" name="parentesco_encuestado" id=""></dd>
                </dl>
            </div> 
            <div class="col-md-4">
                <div class="row"><b>NOMBRE DEL EMPADRONADOR/INSPECTOR :</b> SOTILLA REYES</div>
                <div class="row"><b>CODIGO DEL EMPADRONADOR/INSPECTOR :</b> 44023</div>
                Marcial Javier Grimaldo García<br>
                <b>SUPERVISOR INSPECCION COMERCIAL</b>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-6">
                <button type="submit" name="guardar_ficha_act_cat" class="btn btn-block btn-primary btn-flat"><i class="fa fa-pencil"></i>Guardar</button>
            </div>
            <div class="col-md-6">
                <button type="button" onclick="window.close();" class="btn btn-block btn-default btn-flat">Cerrar</button>
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

<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() ?>frontend/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url() ?>frontend/plugins/fastclick/fastclick.js"></script>

<!-- InputMask -->
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>frontend/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>


<!-- date-range-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url() ?>frontend/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- date-picker -->
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url() ?>frontend/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

<!-- Select2 -->
<script src="<?php echo base_url() ?>frontend/plugins/select2/select2.full.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>frontend/plugins/select2/select2.min.css">

<script>
$(document).ready(function ()
{
    $("#detalleCiclo").DataTable({
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]]
    });
    
    $(".select2").select2();
    $("#dni").inputmask("99999999");
    $("#dni_encuestado").inputmask("99999999");
    
    var fechaMax = "'"+'31-12-'+<?php echo date('Y') ?>+"'";
    $("#OrtFchEj").inputmask("d-m-y");
    var valor = "'" + $('#OrtFchEj').val() + "'";
            var start = new Date();
            var end = new Date(new Date().setYear(start.getFullYear() + 1));
            var fechaServ = "'" + '<?php echo date('d-m-Y') ?>' + "'";
            $('#OrtFchEj').daterangepicker({
                "showDropdowns": true,
                "singleDatePicker": true,
                "autoApply": false,
                "autoclose": true,
                "format": "DD-MM-YYYY",
                "separator": " - ",
                "startDate": valor,
                "minDate": fechaServ,
                "maxDate" : fechaMax,
                "endDate": end,
                "locale": {
                    "format": "DD-MM-YYYY",
                    "separator": " - ",
                    "applyLabel": "Aceptar",
                    "cancelLabel": "Cancelar",
                    "customRangeLabel": "Custom",
                    "autoclose": true,
                    "daysOfWeek": [
                        "Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"
                    ],
                    "monthNames": [
                        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                        "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                    ],
                    "firstDay": 1
                }
            });
});
</script> 