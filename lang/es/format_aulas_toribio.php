<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * aulas_toribio_Format_renderer
 *
 * @package    aulas_toribio_Format
 * @author     aulas_toribio
 * @copyright  aulas_toribio
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


$string['modulename'] = 'Descripción del Módulo - {$a}';
$string['modulename_help'] = 'Descripción del Módulo - {$a} para contextualizar al estudiante <br> Ejemplo: Cuidado y medio ambiente';

$string['completado'] = 'Completado';
$string['cargando'] = 'Cargando...';
$string['objetivo'] = 'Objetivo general';
$string['programa'] = 'Programa de curso';
$string['proceso'] = 'Proceso evaluativo';
$string['anuncios'] = 'Anuncios';
$string['meetings'] = 'Encuentros Sincrónicos';
$string['doubts'] = 'Dudas e inquietudes';
$string['Modulo'] = 'Módulo';
$string['ir'] = 'Ir';
$string['back'] = 'Regresar a las guías';
$string['desempeno'] = 'Desempeño esperado';
$string['numsections']  = 'Seleccione la cantidad de módulos que tiene su curso';
$string['MainObjective']  = 'Objetivo principal del curso';
$string['MainObjective_help']  = 'Escriba el objetivo general del curso';
$string['Coursecontent']  = 'Enlace del boton de programa de curso';
$string['Coursecontent_help']  = 'Colocar el enlace del boton del PDF del programa de curso';
$string['Courseprocess']  = 'Enlace del boton de proceso evaluativo';
$string['Courseprocess_help']  = 'Colocar el enlace del PDF del proceso evaluativo';
$string['Forum']  = 'Enlace del boton de foro de anuncios';
$string['Forum_help']  = 'Colocar el enlace del boton del foro de anuncios';
$string['Meeting']  = 'Enlace del boton de los encuentros sincónicos';
$string['Meeting_help']  = 'Colocar el enlace del boton de los encuentros sincrónicos';
$string['doubtstext']  = 'Enlace del boton de dudas e inquietudes';
$string['doubtstext_help']  = 'Colocar el enlace del boton de dudas e inquietudes';


$string['textselect']  = 'Id del video que aparecera en el módulo - {$a}';
$string['textselect_help']  = 'Id del video que aparecera en el módulo - {$a}';


$string['above'] = 'Arriba de los botones de lista';
$string['below'] = 'Debajo de lasección visible';
$string['colorcurrent'] = 'Color del botón de la sección actual';
$string['colorcurrent_help'] = 'La sección actual es la sección marcada con resaltado.<br>Defina un color en hexadecimal.
<i>Ejemplo: #fab747</i><br>Si Usted desea usar el color por defecto, déjelo vacío.';
$string['colorvisible'] = 'Color del botón de la sección visible';
$string['colorvisible_help'] = 'LA sección visible es la sección sleccionada.<br>Defina un color en hexadecimal.
<i>Ejemplo: #747fab</i><br>Si Usted desea usar el color por defecto, déjelo vacío.';
$string['currentsection'] = 'Este tópico';
$string['deletesection'] = 'Eliminar tópico';
$string['divisor'] = 'Número de secciones a agrupar - {$a}';
$string['divisortext'] = 'Título del agrupamiento - {$a}';
$string['divisortext_help'] = 'El agrupamiento de secciones se usa para separar secciones por tipo o por módulos.
<i>Ejemplo: El curso tiene 10 secciones, divididas en dos módulos: Teórico (con 5 secciones) y Práctico (con 5 secciones).<br>
Defina el título con "Teórico" y configure el número de secciones a 5.</i><br><br>
Sugerencia: Si lo desea, use la marca (tag)  <strong>&lt;br&gt;</strong> type <strong>[br]</strong>.';
$string['editing'] = 'Los botones están deshabilitados mientras esté activo el modo de edición.';
$string['editsection'] = 'Editar tópico';
$string['hidefromothers'] = 'Ocultar tópico';
$string['no'] = 'No';
$string['pluginname'] = 'Formato Aulas Toribio';
$string['section0name'] = 'General';
$string['sectionname'] = 'Tópico';
$string['sectionposition'] = 'Posición de la sección cero';
$string['sectionposition_help'] = 'La sección cero aparecerá junto a la sección visible.<br><br>
<strong>Arriba de la lista de botone</strong><br>Use esta opción si Usted desea añadir algun texto o recurso antes de la lista de botones.
<i>Ejemplo: Defina una imagen para ilustrar el curso.</i><br><br><strong>Debajo de la sección visible</strong><br>
Use esta opción si quiere añadir un texto o recurso después de la sección visible.
<i>Ejemplo: Recursos o enlaces a mostrarse sin importar la región visible.</i><br><br>';
$string['showdefaultsectionname'] = 'Mostrar el nombre que se configura en el formato de curso';
$string['showdefaultsectionname_help'] = 'Si selecciona <strong>No</strong> tenga en cuenta que la caratula del módulo no tendrá un texto de apoyo y que esta deberá estar presente en la imagen que cargue.';
$string['showfromothers'] = 'Mostrar tópico';
$string['yes'] = 'Si';
$string['sequential'] = 'Secuencial';
$string['notsequentialdesc'] = 'Cada nuevo grupo empieza a contar secciones de uno.';
$string['sequentialdesc'] = 'Cuente los números de sección ignorando el agrupamiento.';
$string['sectiontype'] = 'Estilo de lista';
$string['numeric'] = 'Numérico';
$string['roman'] = 'Números romanos';
$string['alphabet'] = 'Alfabeto';
$string['buttonstyle'] = 'Estilo del botón';
$string['buttonstyle_help'] = 'Define la forma geométrica de los botones.';
$string['circle'] = 'Circulo';
$string['square'] = 'Plaza';
$string['inlinesections'] = 'Secciones separadas en líneas';
$string['inlinesections_help'] = 'Muestra cada sección en una línea.';
$string['moduleimg'] = 'Cargar imagen del módulo - {$a}';

/**
 * String de formato nuevo
 */
$string['tabsimagehome'] = 'Cargar imagen del banner principal';
$string['tabseditor_home'] = 'Descripción del modulo pricipal';
$string['Modulevideohome']  = 'Coloque el id del video de youtube';
$string['Modulevideohome_help']  = 'Id del video que aparecera en el inicio del curso, el id de youtube es es que aprace en la url despues de v=';
$string['tabsimagemetaf']  = 'Imagen de la metafora';
$string['tabsimagemetafpop']  = 'Imagen del popup de la metafora - {$a}';
$string['title_tabs']  = 'Configuración de las guías';

$string['tabsTitle']  = 'Título de la guía - {$a}';
$string['tabsTitle_help']  = 'Título de la guía - {$a} para contextualizar al estudiante <br> Ejemplo: Módulo 1 ó Semana 1 ó Corte 1';
$string['tabsimage']  = 'Enlace de la imágen del banner de la guía - {$a}';
$string['moduleimage_help']  = 'Enlace de la imágen del banner de la guía que quiere que aparezca en el contenedor del módulo';
$string['tabseditor']  = 'Descripción de la guía - {$a}';
$string['tabseditor_help']  = 'Descripción de la guía - {$a} que quiere que aparezca en el módulo {$a}';
$string['tabsVideo']  = 'Url del video que aparecera en la guía - {$a}';
$string['tabsVideo_help']  = 'Url del video que aparecera en la guía - {$a}';
$string['tabseditormoment']  = 'Descripción del momento - {$a} de la guía';
$string['tabseditormoment_help']  = 'Descripción del momento - {$a} que quiere que aparezca en la guía';
$string['numactivities']  = 'Seleccione la cantidad de actividades que tiene el momento - {$a}';


$string['button_modules'] = 'Empezar';
$string['back_guides'] = 'Regresar a los cursos';
/**
 * Momentos
 */
$string['title-moments'] = 'Elige un momento del Design Thinking para continuar';