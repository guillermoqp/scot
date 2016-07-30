<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// <editor-fold defaultstate="collapsed" desc="ACCESO Y AYUDA DE LOGIN">
$route['login'] = 'acceso/Inicio_ctrllr/mostrar';
$route['ingresar'] = 'acceso/Inicio_ctrllr/login';
$route['logout'] = 'acceso/Inicio_ctrllr/logout';
$route['inicio'] = 'acceso/Inicio_ctrllr/inicio';
$route['401'] = 'acceso/Inicio_ctrllr/sinPermiso';
$route['autoCompleteUsuario'] = 'acceso/Inicio_ctrllr/autoCompleteUsuario';
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="DATOS PARA APLICATIVO MOVIL : LOGIN  DE USUARIOS">
$route['login_movil'] = 'acceso/Inicio_ctrllr/login_movil'; // </editor-fold>

// <editor-fold defaultstate="collapsed" desc="DATOS PARA APLICATIVO MOVIL - TOMA DE ESTADO - DISTRIBUCION DE RECIBOS ">
$route['cst/toma_estado/obtener_lecturas_en_ejec_x_lecturista_json'] = 'contratista/lectura/ConsultasMovil_cst_ctrllr/obtener_lecturas_en_ejec_x_lecturista_json';
$route['cst/toma_estado/obtener_observaciones_lectura_json'] = 'contratista/lectura/ConsultasMovil_cst_ctrllr/obtener_observaciones_lectura_json';
$route['cst/toma_estado/sincronizar_lecturas_json'] = 'contratista/lectura/ConsultasMovil_cst_ctrllr/sincronizar_lecturas_json';
$route['cst/toma_estado/sincronizar_observaciones_lecturas_json'] = 'contratista/lectura/ConsultasMovil_cst_ctrllr/sincronizar_observaciones_lecturas_json';
//
$route['cst/distribucion/obtener_observaciones_distribucion_json'] = 'contratista/lectura/ConsultasMovil_cst_ctrllr/obtener_observaciones_distribucion_json';


//$route['cst/toma_estado/sincronizar_datos_lecturas_json'] = 'contratista/lectura/ConsultasMovil_cst_ctrllr/sincronizar_datos_lecturas_json';
//$route['cst/toma_estado/sincronizar_imagen_lecturas_json'] = 'contratista/lectura/ConsultasMovil_cst_ctrllr/sincronizar_imagen_lecturas_json'; 
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="PERMISOS">
/* -------- /PERMISOS/* ---------- */
/*  USUARIOS CONTROLLER     */
$route['permisos/usuarios'] = 'acceso/Usuario_ctrllr/usuario_listar';
$route['permisos/usuario/nuevo'] = 'acceso/Usuario_ctrllr/usuario_nuevo';
$route['permisos/usuario/editar/(:num)'] = 'acceso/Usuario_ctrllr/usuario_editar/$1';
$route['permisos/mi_usuario/editar/(:num)'] = 'acceso/Usuario_ctrllr/mi_usuario_editar/$1';
$route['permisos/usuario/eliminar/(:num)'] = 'acceso/Usuario_ctrllr/usuario_eliminar/$1';
$route['permisos/usuario/obtener_cargos/(:num)'] = 'acceso/Usuario_ctrllr/ajax_obtener_cargos/$1';

/*  DIRECTORIO TELEFONICO CONTROLLER     */
$route['directorio/eps'] = 'acceso/Usuario_ctrllr/directorio_contratante';
$route['directorio/contratista'] = 'acceso/Usuario_ctrllr/directorio_contratista';
/*  NIVELES CONTROLLER      */
$route['permisos/niveles'] = 'acceso/Nivel_ctrllr/nivel_listar';
$route['permisos/nivel/nuevo'] = 'acceso/Nivel_ctrllr/nivel_nuevo';
$route['permisos/nivel/editar/subir/(:num)'] = 'acceso/Nivel_ctrllr/nivel_subir/$1';
$route['permisos/nivel/editar/bajar/(:num)'] = 'acceso/Nivel_ctrllr/nivel_bajar/$1';
$route['permisos/nivel/editar/(:num)'] = 'acceso/Nivel_ctrllr/nivel_editar/$1';
$route['permisos/nivel/eliminar/(:num)'] = 'acceso/Nivel_ctrllr/nivel_eliminar/$1';
/*      PERFIL CONTROLLER        */
$route['permisos/perfil'] = 'acceso/Perfil_ctrllr/rol_listar';
$route['permisos/perfil/nuevo'] = 'acceso/Perfil_ctrllr/rol_nuevo';
$route['permisos/perfil/editar/(:num)'] = 'acceso/Perfil_ctrllr/rol_editar/$1';
$route['permisos/perfil/eliminar/(:num)'] = 'acceso/Perfil_ctrllr/rol_eliminar/$1';
/*      CARGOS CONTROLLER        */
$route['permisos/cargo'] = 'acceso/Cargo_ctrllr/cargo_listar';
$route['permisos/cargo/nuevo'] = 'acceso/Cargo_ctrllr/cargo_nuevo';
$route['permisos/cargo/editar/(:num)'] = 'acceso/Cargo_ctrllr/cargo_editar/$1';
$route['permisos/cargo/eliminar/(:num)'] = 'acceso/Cargo_ctrllr/cargo_eliminar/$1';
$route['permisos/cargo/obtener_niveles/(:num)'] = 'acceso/Cargo_ctrllr/ajax_obtener_niveles/$1'; 
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="CONFIGURACIÓN - PENALIDADES - OBSERVACIONES">
/* -------- /CONFIGURACIÓN/* ---------- */
//      PENALIDAD CONTROLLER
$route['configuracion/grupos_penalidad'] = 'general/Penalidad_ctrllr/grupoPenalidad_listar';
$route['configuracion/grupo_penalidad/nuevo'] = 'general/Penalidad_ctrllr/grupoPenalidad_nuevo';
$route['configuracion/grupo_penalidad/editar/(:num)'] = 'general/Penalidad_ctrllr/grupoPenalidad_editar/$1';
$route['configuracion/grupo_penalidad/eliminar/(:num)'] = 'general/Penalidad_ctrllr/grupoPenalidad_eliminar/$1';
$route['configuracion/penalidades'] = 'general/Penalidad_ctrllr/penalidad_listar';
$route['configuracion/penalidad/nuevo'] = 'general/Penalidad_ctrllr/penalidad_nuevo';
$route['configuracion/penalidad/editar/(:num)'] = 'general/Penalidad_ctrllr/penalidad_editar/$1';
$route['configuracion/penalidad/eliminar/(:num)'] = 'general/Penalidad_ctrllr/penalidad_eliminar/$1';
$route['configuracion/penalidades/obtener_penalidades_x_grupo/(:num)'] = 'general/Penalidad_ctrllr/ajax_obtener_penalidades_x_grupo/$1';
//      OBSERVACION CONTROLLER
$route['configuracion/grupos_observacion'] = 'general/Observacion_ctrllr/grupoObservacion_listar';
$route['configuracion/grupo_observacion/nuevo'] = 'general/Observacion_ctrllr/grupoObservacion_nuevo';
$route['configuracion/grupo_observacion/editar/(:num)'] = 'general/Observacion_ctrllr/grupoObservacion_editar/$1';
$route['configuracion/grupo_observacion/eliminar/(:num)'] = 'general/Observacion_ctrllr/grupoObservacion_eliminar/$1';
$route['configuracion/observaciones'] = 'general/Observacion_ctrllr/observacion_listar';
$route['configuracion/observacion/nuevo'] = 'general/Observacion_ctrllr/observacion_nuevo';
$route['configuracion/observacion/editar/(:num)'] = 'general/Observacion_ctrllr/observacion_editar/$1';
$route['configuracion/observacion/eliminar/(:num)'] = 'general/Observacion_ctrllr/observacion_eliminar/$1';
$route['configuracion/observacion/obtener_grupos/(:num)'] = 'general/Observacion_ctrllr/ajax_obtener_grupos/$1';
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="CONTRATACION">
/*       CONTRATANTE CONTROLLER     */
$route['configuracion/contratantes'] = 'general/Contratante_ctrllr/contratante_listar';
$route['configuracion/contratante/nuevo'] = 'general/Contratante_ctrllr/contratante_nuevo';
$route['configuracion/contratante/nuevo/guardar'] = 'general/Contratante_ctrllr/contratante_nuevo_guardar';
$route['configuracion/contratante/get_empresa_x_ruc_json'] = 'general/Contratante_ctrllr/get_empresa_x_ruc_json';
$route['configuracion/contratante/get_contratante_x_ruc_json'] = 'general/Contratante_ctrllr/get_contratante_x_ruc_json';
$route['configuracion/contratante/validar_empresa_libre'] = 'general/Contratante_ctrllr/validar_empresa_libre';
$route['configuracion/contratante/editar/(:num)'] = 'general/Contratante_ctrllr/contratante_editar/$1';
$route['configuracion/contratante/editar/(:num)/guardar'] = 'general/Contratante_ctrllr/contratante_editar_guardar/$1';
$route['configuracion/contratante/eliminar/(:num)'] = 'general/Contratante_ctrllr/contratante_eliminar/$1';


/*       CONTRATISTA CONTROLLER */
$route['contratistas'] = 'general/Contratista_ctrllr/contratista_listar';
$route['contratista/nuevo'] = 'general/Contratista_ctrllr/contratista_nuevo';
$route['contratista/nuevo/empresas'] = 'general/Contratista_ctrllr/contratista_nuevo_empresas';
$route['contratista/nuevo/empresas/guardar'] = 'general/Contratista_ctrllr/contratista_nuevo_guardar';
$route['contratista/get_empresa_x_ruc_json'] = 'general/Contratista_ctrllr/get_empresa_x_ruc_json';
$route['contratista/get_contratista_x_ruc_json'] = 'general/Contratista_ctrllr/get_contratista_x_ruc_json';
$route['contratista/get_one_empresa_detallecontratista_json'] = 'general/Contratista_ctrllr/get_one_empresa_detallecontratista_json';
$route['contratista/nuevo/guardar'] = 'general/Contratista_ctrllr/contratista_nuevo_guardar';
$route['contratista/editar/(:num)'] = 'general/Contratista_ctrllr/contratista_editar/$1';
$route['contratista/editar/(:num)/guardar'] = 'general/Contratista_ctrllr/contratista_editar_guardar/$1';
$route['contratista/eliminar/(:num)'] = 'general/Contratista_ctrllr/contratista_eliminar/$1';
$route['contratista/validar_empresa_libre'] = 'general/Contratista_ctrllr/validar_empresa_libre';

//  ELIMINAR ALGUNA EMPRESA DEL CONSORCIO 
$route['consorciado/eliminar/(:num)/(:num)'] = 'Contratista_ctrllr/consorciado_eliminar/$1/$2';

/* CONTRATO CONTROLLER  */
$route['contratos'] = 'general/Contrato_ctrllr/contrato_listar';
$route['contrato/nuevo'] = 'general/Contrato_ctrllr/contrato_nuevo';
$route['contrato/nuevo/guardar'] = 'general/Contrato_ctrllr/contrato_nuevo_guardar';
$route['contrato/editar/(:num)'] = 'general/Contrato_ctrllr/contrato_editar/$1';
$route['contrato/editar/(:num)/guardar'] = 'general/Contrato_ctrllr/contrato_editar_guardar/$1';
$route['contrato/eliminar/(:num)'] = 'general/Contrato_ctrllr/contrato_eliminar/$1';

$route['contrato/get_contrato_x_id_json'] = 'general/Contrato_ctrllr/get_contrato_x_id_json';
$route['contrato/autoCompleteContratista'] = 'general/Contrato_ctrllr/autoCompleteContratista';
$route['contrato/get_detalle_contrato_x_id_json'] = 'general/Contrato_ctrllr/get_detalle_contrato_x_id_json';
$route['contrato/get_contratista_contrato_x_id_json'] = 'general/Contrato_ctrllr/get_contratista_contrato_x_id_json';


//  PRECIOS DE PENALDIADES DEL CONTRATO : Detalle_contrato_penalidad
$route['contrato/(:num)/precios_penalidades'] = 'general/Contrato_ctrllr/contrato_precios_penalidades/$1';
$route['contrato/(:num)/precios_penalidades/nuevo'] = 'general/Contrato_ctrllr/contrato_precios_penalidades_nuevo/$1';
$route['contrato/(:num)/precios_penalidades/nuevo/guardar'] = 'general/Contrato_ctrllr/contrato_precios_penalidades_nuevo_guardar/$1';
$route['contrato/(:num)/precios_penalidades/editar/(:num)'] = 'general/Contrato_ctrllr/contrato_precios_penalidades_editar/$1/$2';
$route['contrato/(:num)/precios_penalidades/editar/(:num)/guardar'] = 'general/Contrato_ctrllr/contrato_precios_penalidades_editar_guardar/$1/$2';
$route['contrato/(:num)/precios_penalidades/eliminar/(:num)'] = 'general/Contrato_ctrllr/contrato_precios_penalidades_eliminar/$1/$2'; // </editor-fold>

// <editor-fold defaultstate="collapsed" desc="CONFIGURACIÓN GLOBAL">
/*       UNIDADES DE MEDIDA CONTROLLER      */
$route['configuracion/unidades'] = 'general/Unidad_ctrllr/unidad_listar';
$route['configuracion/unidad/nuevo'] = 'general/Unidad_ctrllr/unidad_nuevo';
$route['configuracion/unidad/editar/(:num)'] = 'general/Unidad_ctrllr/unidad_editar/$1';
$route['configuracion/unidad/eliminar/(:num)'] = 'general/Unidad_ctrllr/unidad_eliminar/$1';

/*      ACTIVIDAD CONTROLLER       */
$route['configuracion/actividad'] = 'general/Actividad_ctrllr/actividad_listar';
$route['configuracion/actividad/nuevo'] = 'general/Actividad_ctrllr/actividad_nuevo';
$route['configuracion/actividad/nuevo/guardar'] = 'general/Actividad_ctrllr/actividad_nuevo_guardar';
$route['configuracion/actividad/editar/(:num)'] = 'general/Actividad_ctrllr/actividad_editar/$1';
$route['configuracion/actividad/editar/(:num)/guardar'] = 'general/Actividad_ctrllr/actividad_editar_guardar/$1';
$route['configuracion/actividad/eliminar/(:num)'] = 'general/Actividad_ctrllr/actividad_eliminar/$1';

/*      SUB ACTIVIDAD CONTROLLER        */
$route['configuracion/subactividad'] = 'general/SubActividad_ctrllr/subactividad_listar';
$route['configuracion/subactividad/nuevo'] = 'general/SubActividad_ctrllr/subactividad_nuevo';
$route['configuracion/subactividad/nuevo/guardar'] = 'general/SubActividad_ctrllr/subactividad_nuevo_guardar';
$route['configuracion/subactividad/editar/(:num)'] = 'general/SubActividad_ctrllr/subactividad_editar/$1';
$route['configuracion/subactividad/editar/(:num)/guardar'] = 'general/SubActividad_ctrllr/subactividad_editar_guardar/$1';
$route['configuracion/subactividad/eliminar/(:num)'] = 'general/SubActividad_ctrllr/subactividad_eliminar/$1';

/*      VARIABLE CONTROLLER        */
$route['configuracion/variables'] = 'sistema/Variable_ctrllr/variable_listar';
$route['configuracion/variable/nuevo'] = 'sistema/Variable_ctrllr/variable_nuevo';
$route['configuracion/variable/editar/(:num)'] = 'sistema/Variable_ctrllr/variable_editar/$1';
$route['configuracion/variable/eliminar/(:num)'] = 'sistema/Variable_ctrllr/variable_eliminar/$1';

/*      NIVELES COMERCIALES CONTROLLER        */
$route['configuracion/niveles_comerciales'] = 'general/NivelGrupo_ctrllr/nivelesGrupo_listar';
$route['configuracion/nivel_comercial/(:num)/detalle'] = 'general/NivelGrupo_ctrllr/nivelGrupo_detalle/$1';
$route['configuracion/nivel_comercial/subnivel/(:num)'] = 'general/NivelGrupo_ctrllr/nivelGrupo_subnivel/$1';
$route['configuracion/nivel_comercial/subnivel/(:num)/agrupar/(:num)'] = 'general/NivelGrupo_ctrllr/nivelGrupo_subnivel_agrupar/$1/$2';
$route['configuracion/nivel_comercial/subnivel/(:num)/desagrupar/(:num)'] = 'general/NivelGrupo_ctrllr/nivelGrupo_subnivel_desagrupar/$1/$2'; 
$route['configuracion/nivel_comercial/subciclos_sin_agrupar_notificaciones_json'] = 'general/NivelGrupo_ctrllr/subciclos_sin_agrupar_notificaciones_json'; 

/*      PERIODO CONTROLLER        */
$route['configuracion/periodos'] = 'sistema/Periodo_ctrllr/periodos_listar';
$route['configuracion/periodo/nuevo'] = 'sistema/Periodo_ctrllr/periodo_nuevo';
$route['configuracion/periodo/editar/(:num)'] = 'sistema/Periodo_ctrllr/periodo_editar/$1';
$route['configuracion/periodo/eliminar/(:num)'] = 'sistema/Periodo_ctrllr/periodo_eliminar/$1';

/*      PARAMETRO - DETALLE PARAMETRO CONTROLLER        */
$route['configuracion/parametros'] = 'sistema/Parametro_ctrllr/parametros_listar';
$route['configuracion/parametro_eliminar/(:num)'] = 'sistema/Parametro_ctrllr/parametro_eliminar/$1';
$route['configuracion/(:any)'] = 'sistema/Parametro_ctrllr/detParam_listar/$1';
$route['configuracion/(:any)/nuevo'] = 'sistema/Parametro_ctrllr/detParam_nuevo/$1';
$route['configuracion/(:any)/nuevo/guardar'] = 'sistema/Parametro_ctrllr/detParam_nuevo_guardar/$1';
$route['configuracion/(:any)/editar/(:num)'] = 'sistema/Parametro_ctrllr/detParam_editar/$1/$2';
$route['configuracion/(:any)/editar/(:num)/guardar'] = 'sistema/Parametro_ctrllr/detParam_editar_guardar/$1/$2';
$route['configuracion/(:any)/eliminar/(:num)'] = 'sistema/Parametro_ctrllr/detParam_eliminar/$1/$2'; 

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="ACTIVIDAD COMERCIAL : TOMA DE ESTADO">
/*       CRONOGRAMA ANUAL DE LECTURA        */
$route['toma_estado/cronograma_anual'] = 'lectura/CronogramaLectura_ctrllr/cronograma_anual_listar';
$route['toma_estado/cronograma_anual/nuevo'] = 'lectura/CronogramaLectura_ctrllr/cronograma_nuevo_redirigir';
$route['toma_estado/cronograma_anual/nuevo/(:num)'] = 'lectura/CronogramaLectura_ctrllr/cronograma_anual_nuevo/$1';
$route['toma_estado/cronograma_anual/editar/(:num)'] = 'lectura/CronogramaLectura_ctrllr/cronograma_anual_editar/$1';
$route['toma_estado/cronograma_anual/ver/(:num)'] = 'lectura/CronogramaLectura_ctrllr/cronograma_ver/$1';
$route['toma_estado/cronograma_anual/pdf/(:num)'] = 'lectura/CronogramaLectura_ctrllr/cronograma_pdf/$1';

/*       CRONOGRAMA POR PERIODO DE LECTURA     (ANTERIOR CRONOGRAMA MENSUAL )  */
$route['toma_estado/cronograma_periodo'] = 'lectura/CronogramaLectura_ctrllr/cronograma_periodo_listar';
$route['toma_estado/cronograma_periodo/nuevo'] = 'lectura/CronogramaLectura_ctrllr/cronograma_periodo_redirigir';
$route['toma_estado/cronograma_periodo/nuevo/(:num)/(:num)'] = 'lectura/CronogramaLectura_ctrllr/cronograma_periodo_nuevo/$1/$2';
$route['toma_estado/cronograma_periodo/editar/(:num)'] = 'lectura/CronogramaLectura_ctrllr/cronograma_periodo_editar/$1';
$route['toma_estado/cronograma_periodo/ver/(:num)'] = 'lectura/CronogramaLectura_ctrllr/cronograma_periodo_ver/$1';


/*       ORDEN DE TRABAJO TOMA DE ESTADO   */
$route['toma_estado/ordenes'] = 'lectura/OTTomaEstado_ctrllr/orden_listar';
$route['toma_estado/orden/editar/(:num)'] = 'lectura/OTTomaEstado_ctrllr/orden_editar/$1';
$route['toma_estado/orden/editar/(:num)/guardar'] = 'lectura/OTTomaEstado_ctrllr/orden_editar_guardar/$1';
$route['toma_estado/orden/eliminar/(:num)'] = 'lectura/OTTomaEstado_ctrllr/orden_eliminar/$1';
$route['toma_estado/orden/(:num)/archivo/(:num)/eliminar'] = 'lectura/OTTomaEstado_ctrllr/orden_archivo_eliminar/$1/$2';
$route['toma_estado/orden/ver/(:num)'] = 'lectura/OTTomaEstado_ctrllr/orden_ver/$1';


/*    NUEVA ORDEN DE TRABAJO TOMA DE ESTADO - CON CARGA DE CICLOS/SUBCICLOS   */
$route['toma_estado/orden/nuevo'] = 'lectura/OTTomaEstado_ctrllr/orden_nuevo';
$route['toma_estado/orden/nuevo/(:num)'] = 'lectura/OTTomaEstado_ctrllr/orden_nuevoP/$1';
$route['toma_estado/orden/nuevo/detalle_ciclo/(:num)'] = 'lectura/OTTomaEstado_ctrllr/orden_nuevo_detalle_ciclo/$1';
$route['toma_estado/orden/nuevo/detalle_cicloP/(:num)'] = 'lectura/OTTomaEstado_ctrllr/orden_nuevo_detalle_cicloP/$1';
$route['toma_estado/get_detalle_x_suborden_json'] = 'lectura/OTTomaEstado_ctrllr/get_detalle_x_suborden_json';
$route['toma_estado/get_detalle_x_suborden_jsonP'] = 'lectura/OTTomaEstado_ctrllr/get_detalle_x_suborden_jsonP';
$route['toma_estado/orden/limpiar'] = 'lectura/OTTomaEstado_ctrllr/orden_limpiar';
$route['toma_estado/orden/limpiar/(:num)'] = 'lectura/OTTomaEstado_ctrllr/orden_limpiarP/$1';


/*       ORDEN DE TRABAJO TOMA DE ESTADO - AVANCE POR ORDEN   */
$route['toma_estado/orden/avance/(:num)'] = 'lectura/Orden_avance_ctrllr/avance_orden/$1';
$route['toma_estado/orden/avance/(:num)/suborden/(:num)'] = 'lectura/Orden_avance_ctrllr/avance_suborden/$1/$2';

/*       LECTURISTAS       */
$route['toma_estado/lecturista'] = 'lectura/Lecturista_ctrllr/lecturista_listar';

/*   LISTA DE ATIPICOS DE LECTURA DE LAS ORDENES DE TRABAJO TOMA DE ESTADO    */
$route['toma_estado/orden/(:num)/atipicos'] = 'lectura/OTTomaEstado_ctrllr/orden_atipicos/$1';
$route['toma_estado/orden/(:num)/atipicos/limpiar'] = 'lectura/OTTomaEstado_ctrllr/orden_atipicos_limpiar/$1';

/*    PENALIZACIONES DE LAS ORDENES DE TRABAJO TOMA DE ESTADO    */
$route['toma_estado/orden/(:num)/penalizaciones'] = 'lectura/OTTomaEstado_ctrllr/orden_penalizaciones/$1';
$route['toma_estado/orden/(:num)/penalizacion/nuevo'] = 'lectura/OTTomaEstado_ctrllr/orden_penalizaciones_nuevo/$1';
$route['toma_estado/orden/(:num)/penalizacion/nuevo/guardar'] = 'lectura/OTTomaEstado_ctrllr/orden_penalizaciones_nuevo_guardar/$1';
$route['toma_estado/orden/(:num)/penalizacion/editar/(:num)'] = 'lectura/OTTomaEstado_ctrllr/orden_penalizaciones_editar/$1/$2';
$route['toma_estado/orden/(:num)/penalizacion/editar/(:num)/guardar'] = 'lectura/OTTomaEstado_ctrllr/orden_penalizaciones_editar_guardar/$1/$2';
$route['toma_estado/orden/(:num)/penalizacion/eliminar/(:num)'] = 'lectura/OTTomaEstado_ctrllr/orden_penalizaciones_eliminar/$1/$2';

/*    CONTROL DE LECTURAS */
$route['toma_estado/reportes/control'] = 'lectura/Reporte_ctrllr/control_lectura_redirigir';
$route['toma_estado/reportes/control/(:num)/(:num)'] = 'lectura/Reporte_ctrllr/control_lectura/$1/$2';
$route['toma_estado/reportes/nuevo'] = 'lectura/Valorizacion_ctrllr/valorizacion_nuevo';

/*    VALORIZACIONES DE ACTIVIDAD TOMA DE ESTADO */
$route['toma_estado/valorizacion'] = 'lectura/Valorizacion_ctrllr/valorizacion_listar';
$route['toma_estado/valorizacion/nuevo'] = 'lectura/Valorizacion_ctrllr/valorizacion_nuevo';
$route['toma_estado/valorizacion/editar/(:num)'] = 'lectura/Valorizacion_ctrllr/valorizacion_editar/$1';
$route['toma_estado/valorizacion/eliminar/(:num)'] = 'lectura/Valorizacion_ctrllr/valorizacion_eliminar/$1';
$route['toma_estado/valorizacion/detalle/(:num)'] = 'lectura/Valorizacion_ctrllr/valorizacion_detalle/$1';
$route['toma_estado/valorizacion/detalle/(:num)/orden/(:num)'] = 'lectura/Valorizacion_ctrllr/valorizacion_orden/$1/$2';

/*   PARA LAS NOTIFICACIONES DEL MASTER     */
$route['toma_estado/obtener_ordenes_no_rec_x_usuario_json'] = 'general/OrdenTrabajo_ctrllr/obtener_ordenes_no_rec_x_usuario_json';
$route['toma_estado/obtener_ordenes_rec_x_usuario_json'] = 'general/OrdenTrabajo_ctrllr/obtener_ordenes_rec_x_usuario_json';
$route['toma_estado/obtener_ordenes_cer_x_usuario_json'] = 'general/OrdenTrabajo_ctrllr/obtener_ordenes_cer_x_usuario_json';
$route['toma_estado/obtener_ordenes_en_ejec_x_usuario_json'] = 'general/OrdenTrabajo_ctrllr/obtener_ordenes_en_ejec_x_usuario_json'; // </editor-fold>

// <editor-fold defaultstate="collapsed" desc="ACTIVIDAD COMERCIAL : DISTRIBUCION DE RECIBOS Y COMUNICACIONES">

/*       CRONOGRAMA POR PERIODO DE DISTRIBUCION     (ANTERIOR CRONOGRAMA MENSUAL )  */
$route['distribucion/cronograma_periodo'] = 'distribucion/CronogramaDistribucion_ctrllr/cronograma_periodo_listar';
$route['distribucion/cronograma_periodo/nuevo'] = 'distribucion/CronogramaDistribucion_ctrllr/cronograma_periodo_redirigir';
$route['distribucion/cronograma_periodo/nuevo/(:num)/(:num)'] = 'distribucion/CronogramaDistribucion_ctrllr/cronograma_periodo_nuevo/$1/$2';
$route['distribucion/cronograma_periodo/editar/(:num)'] = 'distribucion/CronogramaDistribucion_ctrllr/cronograma_periodo_editar/$1';
$route['distribucion/cronograma_periodo/ver/(:num)'] = 'distribucion/CronogramaDistribucion_ctrllr/cronograma_periodo_ver/$1';

/*       ORDEN DE TRABAJO : DISTRIBUCION DE RECIBOS  */
$route['distribucion/ordenes'] = 'distribucion/OTDistribucion_ctrllr/orden_listar';
$route['distribucion/orden/editar/(:num)'] = 'distribucion/OTDistribucion_ctrllr/orden_editar/$1';
$route['distribucion/orden/editar/(:num)/guardar'] = 'distribucion/OTDistribucion_ctrllr/orden_editar_guardar/$1';
$route['distribucion/orden/eliminar/(:num)'] = 'distribucion/OTDistribucion_ctrllr/orden_eliminar/$1';
$route['distribucion/orden/ver/(:num)'] = 'distribucion/OTDistribucion_ctrllr/orden_ver/$1';

/*    NUEVA ORDEN DE TRABAJO DISTRIBUCION DE RECIBOS  - CON CARGA DE CICLOS/SUBCICLOS   */
$route['distribucion/orden/nuevo'] = 'distribucion/OTDistribucion_ctrllr/orden_nuevo';
$route['distribucion/orden/nuevo/detalle_ciclo/(:num)'] = 'distribucion/OTDistribucion_ctrllr/orden_nuevo_detalle_ciclo/$1';
$route['distribucion/get_detalle_x_suborden_json'] = 'distribucion/OTDistribucion_ctrllr/get_detalle_x_suborden_json';
$route['distribucion/orden/limpiar'] = 'distribucion/OTDistribucion_ctrllr/orden_limpiar';

/*       ORDEN DE TRABAJO DISTRIBUCION DE RECIBOS - AVANCE POR ORDEN   */
$route['distribucion/orden/avance/(:num)'] = 'distribucion/Orden_avance_ctrllr/avance_orden/$1';
$route['distribucion/orden/avance/(:num)/suborden/(:num)'] = 'distribucion/Orden_avance_ctrllr/avance_suborden/$1/$2';

/*       DISTRIBUIDORES      */
$route['distribucion/distribuidor'] = 'distribucion/Distribuidor_ctrllr/distribuidor_listar';


/*    PENALIZACIONES DE LAS ORDENES DE TRABAJO   -   DISTRIBUCION DE RECIBOS  */
$route['distribucion/orden/(:num)/penalizaciones'] = 'distribucion/OTDistribucion_ctrllr/orden_penalizaciones/$1';
$route['distribucion/orden/(:num)/penalizacion/nuevo'] = 'distribucion/OTDistribucion_ctrllr/orden_penalizaciones_nuevo/$1';
$route['distribucion/orden/(:num)/penalizacion/nuevo/guardar'] = 'distribucion/OTDistribucion_ctrllr/orden_penalizaciones_nuevo_guardar/$1';
$route['distribucion/orden/(:num)/penalizacion/editar/(:num)'] = 'distribucion/OTDistribucion_ctrllr/orden_penalizaciones_editar/$1/$2';
$route['distribucion/orden/(:num)/penalizacion/editar/(:num)/guardar'] = 'distribucion/OTDistribucion_ctrllr/orden_penalizaciones_editar_guardar/$1/$2';
$route['distribucion/orden/(:num)/penalizacion/eliminar/(:num)'] = 'distribucion/OTDistribucion_ctrllr/orden_penalizaciones_eliminar/$1/$2';

/*    CONTROL DE DISTRIBUCION DE RECIBOS  */
$route['distribucion/reportes/control'] = 'distribucion/Reporte_ctrllr/control_distribucion_redirigir';
$route['distribucion/reportes/control/(:num)/(:num)'] = 'distribucion/Reporte_ctrllr/control_distribucion/$1/$2';
$route['distribucion/reportes/nuevo'] = 'distribucion/Valorizacion_ctrllr/valorizacion_nuevo';


/*    VALORIZACIONES DE ACTIVIDAD -  DISTRIBUCION DE RECIBOS */
$route['distribucion/valorizacion'] = 'distribucion/ValorizacionDistribucion_ctrllr/valorizacion_distribucion_listar';
$route['distribucion/valorizacion/nuevo'] = 'distribucion/ValorizacionDistribucion_ctrllr/valorizacion_distribucion_nuevo';
$route['distribucion/valorizacion/editar/(:num)'] = 'distribucion/ValorizacionDistribucion_ctrllr/valorizacion_distribucion_editar/$1';
$route['distribucion/valorizacion/eliminar/(:num)'] = 'distribucion/ValorizacionDistribucion_ctrllr/valorizacion_distribucion_eliminar/$1';
$route['distribucion/valorizacion/detalle/(:num)'] = 'distribucion/ValorizacionDistribucion_ctrllr/valorizacion_distribucion_detalle/$1';
$route['distribucion/valorizacion/detalle/(:num)/orden/(:num)'] = 'distribucion/ValorizacionDistribucion_ctrllr/valorizacion_distribucion_orden/$1/$2';


// </editor-fold>



$route['ciclos'] = 'sistema/Variable_ctrllr/segunda_conexion';
$route['ficha_actualizacion_catastral'] = 'inspeccion/OTInspeccion_ctrllr/ficha_actualizacionCatastral';

$route['ficha_inspeccion_interna'] = 'inspeccion/OTInspeccion_ctrllr/ficha_InspeccionInterna';

$route['generar_cod_barras_code39/(:any)'] = 'inspeccion/OTInspeccion_ctrllr/generar_cod_barras_code39/$1';


//    ********** RUTAS DEL CONTRATISTA **********    //

// <editor-fold defaultstate="collapsed" desc="CONFIGURACIONES">
/*     INICIO  */
$route['cst/inicio'] = 'contratista/acceso/Inicio_cst_ctrllr/inicio';
/*    CONFIGURACION DE USUARIO  */
$route['cst/mi_usuario/editar/(:num)'] = 'contratista/acceso/Usuario_cst_ctrllr/mi_usuario_editar/$1'; // </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" ACTIVIDAD COMERCIAL : TOMA DE ESTADO">
/*     ORDEN DE TRABAJO TOMA DE ESTADO   */
$route['cst/toma_estado/ordenes'] = 'contratista/lectura/OTTomaEstado_cst_ctrllr/orden_listar';
$route['cst/toma_estado/orden/ver/(:num)'] = 'contratista/lectura/OTTomaEstado_cst_ctrllr/orden_ver/$1';
$route['cst/toma_estado/orden/ver/(:num)/detalle_ciclo/(:any)'] = 'contratista/lectura/OTTomaEstado_cst_ctrllr/orden_ver_detalle_ciclo/$1/$2';
$route['cst/toma_estado/get_detalle_x_suborden_json'] = 'contratista/lectura/OTTomaEstado_cst_ctrllr/get_detalle_x_suborden_json';

$route['cst/toma_estado/orden/avance/(:num)'] = 'contratista/lectura/Orden_avance_cst_ctrllr/avance_orden/$1';
$route['cst/toma_estado/orden/avance/(:num)/suborden/(:num)'] = 'contratista/lectura/Orden_avance_cst_ctrllr/avance_suborden/$1/$2';
$route['cst/toma_estado/orden_avance_suborden_json'] = 'contratista/lectura/Orden_avance_cst_ctrllr/orden_avance_suborden_json';

$route['cst/toma_estado/cronograma_anual'] = 'contratista/lectura/CronogramaLectura_cst_ctrllr/cronograma_anual_listar';
$route['cst/toma_estado/cronograma_anual/ver/(:num)'] = 'contratista/lectura/CronogramaLectura_cst_ctrllr/cronograma_ver/$1';
$route['cst/toma_estado/cronograma_periodo'] = 'contratista/lectura/CronogramaLectura_cst_ctrllr/cronograma_periodo_listar';
$route['cst/toma_estado/cronograma_periodo/ver/(:num)'] = 'contratista/lectura/CronogramaLectura_cst_ctrllr/cronograma_periodo_ver/$1';

$route['cst/toma_estado/lecturista'] = 'contratista/lectura/Lecturista_cst_ctrllr/lecturista_listar';
$route['cst/toma_estado/valorizacion'] = 'contratista/lectura/Valorizacion_cst_ctrllr/valorizacion_listar';
$route['cst/toma_estado/valorizacion/detalle/(:num)'] = 'contratista/lectura/Valorizacion_cst_ctrllr/valorizacion_detalle/$1';
$route['cst/toma_estado/valorizacion/detalle/(:num)/orden/(:num)'] = 'contratista/lectura/Valorizacion_cst_ctrllr/valorizacion_orden/$1/$2';


$route['cst/toma_estado/obtener_ordenes_no_rec_x_usuario_json'] = 'contratista/OTGeneral/OrdenTrabajo_cst_ctrllr/obtener_ordenes_no_rec_x_usuario_json';
$route['cst/toma_estado/obtener_ordenes_ejecucion_x_usuario_json'] = 'contratista/OTGeneral/OrdenTrabajo_cst_ctrllr/obtener_ordenes_ejecucion_x_usuario_json';
$route['cst/toma_estado/obtener_ordenes_liq_x_usuario_json'] = 'contratista/OTGeneral/OrdenTrabajo_cst_ctrllr/obtener_ordenes_liq_x_usuario_json';


/* IMPRESION Y DIGITACION DE SUBORDENES DE TRABATO DE TOMA DE ESTADO    */
$route['cst/toma_estado/orden/(:num)/imprimir_suborden/(:num)'] = 'contratista/lectura/OTTomaEstado_cst_ctrllr/orden_imprimir_suborden/$1/$2';
$route['cst/toma_estado/orden/(:num)/imprimir_suborden/(:num)/(:num)'] = 'contratista/lectura/OTTomaEstado_cst_ctrllr/orden_imprimir_suborden/$1/$2/$3';
$route['cst/toma_estado/orden/(:num)/digitar_suborden/(:num)'] = 'contratista/lectura/OTTomaEstado_cst_ctrllr/orden_digitar_suborden/$1/$2';
$route['cst/toma_estado/orden/(:num)/digitar_suborden/(:num)/(:num)'] = 'contratista/lectura/OTTomaEstado_cst_ctrllr/orden_digitar_suborden/$1/$2/$3'; // </editor-fold>

// <editor-fold defaultstate="collapsed" desc="ACTIVIDAD COMERCIAL : DISTRIBUCION DE RECIBOS Y COMUNICACIONES">

/*       CRONOGRAMA POR PERIODO DE DISTRIBUCION     (ANTERIOR CRONOGRAMA MENSUAL )  */
$route['cst/distribucion/cronograma_periodo'] = 'contratista/distribucion/CronogramaDistribucion_cst_ctrllr/cronograma_periodo_listar';
$route['cst/distribucion/cronograma_periodo/ver/(:num)'] = 'contratista/distribucion/CronogramaDistribucion_cst_ctrllr/cronograma_periodo_ver/$1';

/*       ORDEN DE TRABAJO : DISTRIBUCION DE RECIBOS  */
$route['cst/distribucion/ordenes'] = 'contratista/distribucion/OTDistribucion_cst_ctrllr/orden_listar';
$route['cst/distribucion/orden/ver/(:num)'] = 'contratista/distribucion/OTDistribucion_cst_ctrllr/orden_ver/$1';

$route['cst/distribucion/orden/ver/(:num)/detalle_ciclo/(:any)'] = 'contratista/distribucion/OTDistribucion_cst_ctrllr/orden_ver_detalle_ciclo/$1/$2';
$route['cst/distribucion/get_detalle_x_suborden_json'] = 'contratista/distribucion/OTDistribucion_cst_ctrllr/get_detalle_x_suborden_json';

$route['cst/distribucion/orden/avance/(:num)'] = 'contratista/distribucion/Orden_avance_cst_ctrllr/avance_orden/$1';
$route['cst/distribucion/orden/avance/(:num)/suborden/(:num)'] = 'contratista/distribucion/Orden_avance_cst_ctrllr/avance_suborden/$1/$2';

/*       DISTRIBUIDORES      */
$route['cst/distribucion/distribuidor'] = 'contratista/distribucion/Distribuidor_cst_ctrllr/distribuidor_listar';

/*    PENALIZACIONES DE LAS ORDENES DE TRABAJO   -   DISTRIBUCION DE RECIBOS  */
$route['cst/distribucion/orden/(:num)/penalizaciones'] = 'contratista/distribucion/OTDistribucion_cst_ctrllr/orden_penalizaciones/$1';


/*    VALORIZACIONES DE ACTIVIDAD -  DISTRIBUCION DE RECIBOS */
$route['cst/distribucion/valorizacion'] = 'contratista/distribucion/ValorizacionDistribucion_cst_ctrllr/valorizacion_distribucion_listar';
$route['cst/distribucion/valorizacion/detalle/(:num)'] = 'contratista/distribucion/ValorizacionDistribucion_cst_ctrllr/valorizacion_distribucion_detalle/$1';
$route['cst/distribucion/valorizacion/detalle/(:num)/orden/(:num)'] = 'contratista/distribucion/ValorizacionDistribucion_cst_ctrllr/valorizacion_distribucion_orden/$1/$2';
// </editor-fold>


// <editor-fold defaultstate="collapsed" desc="DIRECTORIO TELEFONICOS POR NIVELES Y CARGOS">
/* DIRECTORIO TELEFONICOS POR NIVELES Y CARGOS  */
$route['cst/directorio/eps'] = 'contratista/acceso/Usuario_cst_ctrllr/directorio_contratante';
$route['cst/directorio/contratista'] = 'contratista/acceso/Usuario_cst_ctrllr/directorio_contratista'; // </editor-fold>

/*  
 *    ORDENES DE TRABAJO DE UNA NUEVA ACTIVIDAD CREADA  
$route['(:any)/ordenes(:any)'] = 'general/OrdenTrabajo_ctrllr/orden_listar/$1/$2';
$route['(:any)/ordenes(:any)/nuevo'] = 'general/OrdenTrabajo_ctrllr/orden_nuevo/$1/$2';
$route['(:any)/ordenes(:any)/nuevo/guardar'] = 'general/OrdenTrabajo_ctrllr/orden_nuevo_guardar/$1/$2';
$route['(:any)/ordenes(:any)/ver/(:num)'] = 'general/OrdenTrabajo_ctrllr/orden_ver/$1/$2/$3';
$route['(:any)/ordenes(:any)/editar/(:num)'] = 'general/OrdenTrabajo_ctrllr/orden_editar/$1/$2/$3';
$route['(:any)/ordenes(:any)/editar/(:num)/guardar'] = 'general/OrdenTrabajo_ctrllr/orden_editar_guardar/$1/$2/$3';
$route['(:any)/ordenes(:any)/(:num)/archivo/(:num)/eliminar'] = 'general/OrdenTrabajo_ctrllr/orden_archivo_eliminar/$1/$2/$3/$4';
$route['(:any)/ordenes(:any)/eliminar/(:num)'] = 'general/OrdenTrabajo_ctrllr/orden_eliminar/$1/$2/$3';
$route['(:any)/ordenes(:any)/(:num)/penalizaciones'] = 'general/OrdenTrabajo_ctrllr/orden_penalizaciones/$1/$2/$3';
$route['(:any)/ordenes(:any)/(:num)/penalizacion/nuevo'] = 'general/OrdenTrabajo_ctrllr/orden_penalizaciones_nuevo/$1/$2/$3';
$route['(:any)/ordenes(:any)/(:num)/penalizacion/nuevo/guardar'] = 'general/OrdenTrabajo_ctrllr/orden_penalizaciones_nuevo_guardar/$1/$2/$3';
$route['(:any)/ordenes(:any)/(:num)/penalizacion/editar/(:num)'] = 'general/OrdenTrabajo_ctrllr/orden_penalizaciones_editar/$1/$2/$3/$4';
$route['(:any)/ordenes(:any)/(:num)/penalizacion/editar/(:num)/guardar'] = 'general/OrdenTrabajo_ctrllr/orden_penalizaciones_editar_guardar/$1/$2/$3/$4';
$route['(:any)/ordenes(:any)/(:num)/penalizacion/eliminar/(:num)'] = 'general/OrdenTrabajo_ctrllr/orden_penalizaciones_eliminar/$1/$2/$3/$4';
$route['(:any)/(:any)/valorizar/(:num)'] = 'general/OrdenTrabajo_ctrllr/orden_valorizar/$1/$3';
$route['(:any)/(:any)/valorizar/(:num)/guardar'] = 'general/OrdenTrabajo_ctrllr/orden_valorizar_guardar/$1/$3';
$route['(:any)/valorizacion(:any)'] = 'general/OrdenValorizacion_ctrllr/valorizacion_listar/$1/$2';
$route['(:any)/valorizacion(:any)/nuevo'] = 'general/OrdenValorizacion_ctrllr/valorizacion_nuevo/$1/$2';
$route['cst/(:any)/(:any)'] = 'contratista/OTGeneral/OrdenTrabajo_cst_ctrllr/orden_listar/$1/$2';
$route['cst/(:any)/(:any)/ver/(:num)'] = 'contratista/OTGeneral/OrdenTrabajo_cst_ctrllr/orden_ver/$1/$2/$3';
$route['cst/(:any)/(:any)/ver/(:num)/guardar'] = 'contratista/OTGeneral/OrdenTrabajo_cst_ctrllr/orden_ver_guardar/$1/$2/$3';
 * 
 *      
*/

//DEFAULT CONTROLLER
$route['default_controller'] = 'Default_ctrllr/inicio';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

