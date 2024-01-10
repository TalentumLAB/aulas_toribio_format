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
// MERCHANsILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
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

require_once($CFG->dirroot.'/course/format/topics/renderer.php');

/**
 * format_aulas_toribio_renderer
 *
 * @package    format_aulas_toribio
 * @author     Rodrigo Brandão (rodrigobrandao.com.br)
 * @copyright  2017 Rodrigo Brandão
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class format_aulas_toribio_renderer extends format_topics_renderer{
   
    /**
     * get_button_section
     *
     * @param stdclass $course
     * @param string $name
     * @return string
     */
    protected function get_color_config($course, $name)
    {
        $return = false;
        if (isset($course->{$name})) {
            $color = str_replace('#', '', $course->{$name});
            $color = substr($color, 0, 6);
            if (preg_match('/^#?[a-f0-9]{6}$/i', $color)) {
                $return = '#'.$color;
            }
        }
        return $return;
    }

    /**
     * get_button_section
     *
     * @param stdclass $course
     * @param string $sectionvisible
     * @return string
     */
    protected function get_button_section($course, $sectionvisible)
    {
        global $PAGE;
        global $DB;
        global $CFG;
        global $USER;
        echo '<style>';
        include 'styles.css';
        echo '</style>';

        echo '<script>';
        include 'module.js';
        echo '</script>';
        $context = context_course::instance($course->id);
        $html = '';
        $videocontainerhtml='';
        $modulevideohtml = '';
        $cardscontainerhtml= '';
        $modulecardhtml = '';
        $backnavhtml= '';
        $containerModule='';
        $containerModuleInternal='';
        $css = '';
        $container_home= '';
        $button_program_course= '';
        $button_proccess= '';
        $button_Forum= '';
        $button_meeting= '';
        $button_Doubts= '';
        $module = -1;
        $modules = -1;
        $sectionstyle = '';
        if ($colorcurrent = $this->get_color_config($course, 'colorcurrent')) {
            $css .=
            '#aulas_toribio-container-section .aulas_toribio-button-section.current {
                background: ' . $colorcurrent . ';
            }
            ';
        }
        if ($colorvisible = $this->get_color_config($course, 'colorvisible')) {
            $css .=
            '#aulas_toribio-container-section .buttonsection.sectionvisible {
                background: ' . $colorvisible . ';
            }
            ';
        }
        if ($css) {
            $html .= html_writer::tag('style', $css);
        }
        /*$withoutdivisor = true;
        for ($k = 1; $k <= 6; $k++) {
            if ($course->{'moduleimg' . $k}) {
                $withoutdivisor = false;
            }
        }
        if ($withoutdivisor) {
            $course->divisor1 = 999;
        }*/
        $img = 1;
        $divisorshow = false;
        $count = 1;
        $count1 = 1;
        $count2 = 1;
        $count3 = 1;
        $sec= 1;
        $currentdivisor = 1;
        $currentdivisor1 = 1;
        $currentdivisor2 = 1;
        $divisorvideos = 1;
        $modinfo = get_fast_modinfo($course);
        $inline = '';
        $container_home .= html_writer::start_tag('div',['class'=>'container-fluid','id' => 'aulas_toribio-modules-container']);
        $container_home .= html_writer::start_tag('div',['class'=>'row featurette']);

        /** Modules title */
        $container_home .= html_writer::start_tag('div');
        $container_home .= html_writer::end_tag('div');
        /**
        * Se crea boton para regresar a las guias
        */
        $course_btn_back_lang_guides = get_string('back_guides', 'format_aulas_toribio');
        $cardscontainerhtml .= html_writer::start_tag('div',['class'=>'aulas_toribio-back-navbar']);
        $cardscontainerhtml .= html_writer::start_tag('a',  ['class' => 'aulas_toribio-back-nav', 'href' => $CFG->wwwroot.'/my','role' => 'link', 'aria-label'=> 'regresar a los cursos']);
        $cardscontainerhtml .= html_writer::tag('i', '', ['class' => 'fa fa-arrow-left']);
        $cardscontainerhtml .= $course_btn_back_lang_guides;
        $cardscontainerhtml .= html_writer::end_tag('a');
        $cardscontainerhtml .= html_writer::end_tag('div');

        /* cards */
        $cardscontainerhtml .= html_writer::start_tag('div', ['class' => "col-md-12 aulas_toribio-container-cards",'style' => "margin:1rem 0;display:flex"]);
        
        /**
         * Saber si es docente
         */
        $roleid = $DB->get_field('role', 'id', ['shortname' => 'editingteacher']);
        $iseditingteacheranywhere = $DB->record_exists('role_assignments', ['userid' => $USER->id, 'roleid' => $roleid]);

        $roleidteacher = $DB->get_field('role', 'id', ['shortname' => 'teacher']);
        $isteacheranywhere = $DB->record_exists('role_assignments', ['userid' => $USER->id, 'roleid' => $roleidteacher]);

        /**
         * Imagen de bienvenida
         */
        $cardscontainerhtml .= html_writer::start_tag('div', ['class' => "col-md-12 p-0"]);
        $tabsEquival = 3424;
        try {
            $courseimage = '';
            $fs = get_file_storage();
            $files1 = $fs->get_area_files($context->id, 'format_aulas_toribio', 'images', $tabsEquival);
            if ($files1) {  
                foreach ($files1 as $file1) {
                    $isimage = $file1->is_valid_image();
                    if ($isimage) {
                        $courseimage = moodle_url::make_pluginfile_url(
                            $file1->get_contextid(),
                            $file1->get_component(),
                            $file1->get_filearea(),
                            $tabsEquival,
                            $file1->get_filepath(),
                            $file1->get_filename(),
                            false
                        );

                        $cardscontainerhtml .= html_writer::empty_tag('img', array('src' => $courseimage,'alt'=>'imagen de bienvenida del curso','id'=>'module-image-bienvenidos', 'class'=>'w-100','role'=>'img'));
                    }
                    else{
                        //no hacer nada
                    }
                }
            }
        } catch (Exception $th) {
            echo ' error en la prueba de robin', $th->getMessage();
        }

        $cardscontainerhtml .= html_writer::end_tag('div');

        /**
        * Objetivo principal o descripciòn del curso
        */
        $cardscontainerhtml .= html_writer::start_tag('div',['class' => "row"]);
        try {
            $idCourse = (int)$course->id;
            $nameeditor = 'editor_tabs_home';
            $valueFile = $DB->get_record_sql("SELECT value FROM mdl_course_format_options  where name='$nameeditor' and courseid=$idCourse");
            $editorcontent = '';
            if(empty($course->{'Modulevideohome'}) || $course->{'Modulevideohome'} == ' '){          
            $cardscontainerhtml .= html_writer::start_tag('div',['class' => "col-sm-12 justify-text", 'style'=>"margin-bottom: 2rem;", 'aria-labelledby' => 'descripción del curso']);
            }else{
            $cardscontainerhtml .= html_writer::start_tag('div',['class' => "col-sm-6 justify-text", 'style'=>"margin-bottom: 2rem;", 'aria-labelledby' => 'descripción del curso']);
            }
            $cardscontainerhtml .= $valueFile->value;
            $cardscontainerhtml .= html_writer::end_tag('div');
                                
        } catch (Exception $th) {
            echo ' error en el editor de bienvenidos', $th->getMessage();
        }
        /**
        * 
        * Video principal
        * 
        */
                    
        $cardscontainerhtml .= html_writer::start_tag('div',['class' => "col-sm-6", 'style'=>"margin-bottom: 2rem;", 'role' => 'presentation']);
        if(empty($course->{'Modulevideohome'}) || $course->{'Modulevideohome'} == ' '){
            $modulevideohtml .= html_writer::start_tag('div', ['style'=>'display:none']);
            $modulevideohtml .= html_writer::end_tag('div');
        }
        else{
            $modulevideohtml .= html_writer::start_tag('div',['class' => 'video-tabs']);
            if (strpos($course->{'Modulevideohome'}, 'youtube') !== false) {
                $modulevideohtml .= html_writer::start_tag('iframe',['src'=>"https://www.youtube-nocookie.com/embed/".substr($course->{'Modulevideohome'},32,11)."?rel=0&showinfo=0&modestbranding=1", 'frameborder'=>'0','allow'=>'autoplay; encrypted-media','allowfullscreen'=>'true','webkitallowfullscreen'=>'true','id'=>'video-tabs-1', 'title'=>'video presentacion pestaña '.$course->$tabsname]);
                $modulevideohtml .= html_writer::end_tag('iframe');
            }else{
                $modulevideohtml .= html_writer::start_tag('iframe',['src'=>"http://tumlab.local:8082/embed?m=".substr($course->{'Modulevideohome'},32)."", 'id'=>'video-tabs-1', 'title'=>'video presentacion pestaña '.$course->$tabsname]);
                $modulevideohtml .= html_writer::end_tag('iframe');
            }
            $modulevideohtml .= html_writer::end_tag('div');
        }
        $cardscontainerhtml  .= $modulevideohtml;
        $cardscontainerhtml .= html_writer::end_tag('div');

        $cardscontainerhtml .= html_writer::end_tag('div');
        
        /**
        * 
        * Imagen de la metafora
        * 
        */
        $cardscontainerhtml .= html_writer::start_tag('div',['class' => 'col-sm-12', 'style'=>'position:relative', 'rol'=>'figure']);

        $tabsEquival = 13520;
        try {
            $courseimage = '';
            $fs = get_file_storage();
            $files1 = $fs->get_area_files($context->id, 'format_aulas_toribio', 'images', $tabsEquival);
            if ($files1) {  
                foreach ($files1 as $file1) {
                    $isimage = $file1->is_valid_image();
                    if ($isimage) {
                        $courseimage = moodle_url::make_pluginfile_url(
                            $file1->get_contextid(),
                            $file1->get_component(),
                            $file1->get_filearea(),
                            $tabsEquival,
                            $file1->get_filepath(),
                            $file1->get_filename(),
                            false
                        );

                        $cardscontainerhtml .=html_writer::empty_tag('img', array('src' => $courseimage,'alt'=>'metafora del curso','id'=>'', 'class'=>'w-100'));
                    }
                    else{
                        //no hacer nada
                    }
                }
            }
        } catch (Exception $th) {
            echo ' error en la prueba de robin', $th->getMessage();
        }
        
       
        /**
        * Modales de la metafora
        */
        $cardscontainerhtml .= html_writer::start_tag('div',['class'=>'modal-btn-container']);
                
        for($totaltabs=1; $totaltabs <= $course->numsections; $totaltabs++ ){
            // Button trigger modal 
            $cardscontainerhtml .= html_writer::start_tag('a',['class'=>'btn btn-modal-display','data-toggle'=>'modal','data-target'=>'#exampleModal'.$totaltabs, 'role'=>'button', 'aria-label'=> 'boton para mostrar metafora guía'.$totaltabs]);
            // $modulecardhtml .= 'modal'.$totaltabs;
            $cardscontainerhtml .= html_writer::end_tag('a');

        }
        for($totalmodals=1; $totalmodals <= $course->numsections; $totalmodals++ ){
            //venanas modales
            $cardscontainerhtml .= html_writer::start_tag('div', ['class'=>'modal fade','id'=>'exampleModal'.$totalmodals, 'tabindex'=>'-1', 'aria-hidden'=>'true']);
            $cardscontainerhtml .= html_writer::start_tag('div', ['class'=>'modal-dialog modal-dialog-centered']);
            //modal content
            $cardscontainerhtml .= html_writer::start_tag('div', ['class'=>'modal-content']);
            $cardscontainerhtml .= html_writer::start_tag('div',['class'=>'modal-body p-0']);
            //se imprimen las imagenes de los modales
            $tabsEquival = 135201 + $totalmodals;
            try {
                $courseimage = '';
                $fs = get_file_storage();
                $files1 = $fs->get_area_files($context->id, 'format_aulas_toribio', 'images', $tabsEquival);
                if ($files1) {  
                    foreach ($files1 as $file1) {
                        $isimage = $file1->is_valid_image();
                        if ($isimage) {
                            $courseimage = moodle_url::make_pluginfile_url(
                                $file1->get_contextid(),
                                $file1->get_component(),
                                $file1->get_filearea(),
                                $tabsEquival,
                                $file1->get_filepath(),
                                $file1->get_filename(),
                                false
                            );

                            $cardscontainerhtml .=html_writer::empty_tag('img', array('src' => $courseimage,'alt'=>'metafora guía'.$totalmodals,'id'=>'', 'class'=>'w-100','role'=> 'img'));
                        }
                        else{
                            //no hacer nada
                        }
                    }
                }
            } catch (Exception $th) {
                echo ' error en la prueba de robin', $th->getMessage();
            }
            $cardscontainerhtml .= html_writer::end_tag('div');
            $cardscontainerhtml .= html_writer::end_tag('div');
            $cardscontainerhtml .= html_writer::end_tag('div');
            $cardscontainerhtml .= html_writer::end_tag('div');
        }
        $cardscontainerhtml .= html_writer::end_tag('div');
        $cardscontainerhtml .= html_writer::end_tag('div');

        $cardscontainerhtml .= html_writer::tag('h5','Por favor selecciona una guía',['class' => "aulas_toribio-title-dashboard w-100 text-center",]);

        $cardscontainerhtml .= html_writer::start_tag('div', ['class' => "col-md-12 p-0 d-flex flex-wrap justify-content-center"]);
        
        /**
         * Contador de las imagenes de las guias
         */
        $countguides=0;
        
        foreach ($modinfo->get_section_info_all() as $section => $thissection) {

            
            $module = $module + 1;
            if ($section == 0) {
                continue;
            }
            if ($section > $course->numsections) {
                continue;
            }
            if ($course->hiddensections && !(int)$thissection->visible) {
                continue;
            }
            if (isset($course->{'tabsTitle' . $currentdivisor}) &&
                $count > $course->{'tabsTitle' . $currentdivisor}) {
                $currentdivisor++;
                $count = 1;
            }
            if (isset($course->{'tabsTitle' . $currentdivisor}) &&
                $course->{'tabsTitle' . $currentdivisor} != 0 &&
                !isset($divisorshow[$currentdivisor])) {
                $currentdivisorhtml = $course->{'tabsTitle' . $currentdivisor};
                $currentdivisorhtml = str_replace('[br]', '<br>', $currentdivisorhtml);
                $currentdivisorhtml = html_writer::tag('div', $currentdivisorhtml, ['class' => 'tabsTitle']);
                
                $divisorshow[$currentdivisor] = true;
            }
            $id = 'aulas_toribio-button-section-' . $section;
                    
            if (isset($course->{'tabsTitle' . $currentdivisor}) &&
                $course->{'tabsTitle' . $currentdivisor} == 1) {
                $name = (string)$course->{'tabsTitle' . $section};
                $desc = (string)$course->{'modulename' . ($currentdivisor-1)};

            } else {
                $name = (string)$course->{'tabsTitle' . $section};
                $desc = (string)$course->{'modulename' . ($currentdivisor-1)};

            }
            
            /**
             * Se abre la tarjeta  que permitirá visualizar la descripción de un módulo
             */
            
            $link_img_base='../course/format/aulas_toribio/img/';
                
            $modulecardhtml .= html_writer::start_tag('div', ['class' => "card aulas_toribio-card-module   col-md-4 ",'style'=>'margin-left: 0.2rem;margin-right: 0.5rem;', 'id'=>'aulas_toribio-card-module-'.$module]);
                
                $user = $USER->id;
                $class = 'aulas_toribio-button-section';
                
                
                //div con las imagenes de los modulos
                $onclick = 'M.format_aulas_toribio.show(' . $section . ',' . $course->id . ')';   
                
                if (!$thissection->available && !empty($thissection->availableinfo)) {
                        $class .= ' sectionhidden';
                } 
                
                if ($sectionvisible == $section) {
                    $class .= ' sectionvisible';

                }
                if ($PAGE->user_is_editing()) {
                    $onclick = false;
                }

            /**
            * Cards de las guias
            */
            $modulecardhtml .= html_writer::start_tag('div', ['id' => $id, 'class' => "card-body aulas_toribio-card-module-body", 'role'=>'figure']);
            $modulecardhtml .= html_writer::start_tag('a', ['onclick' => $onclick, 'role'=>'link', 'aria-label'=> 'enlace a la guía '.$module]);
            /**
             * Imagen de las guías
             */
            $tabsEquivalguides = 72194;
            $countguides++;
            try {
                $courseimage = '';
                $fs = get_file_storage();
                $files1 = $fs->get_area_files($context->id, 'format_aulas_toribio', 'images',$tabsEquivalguides + $countguides);
                if ($files1) {  
                    foreach ($files1 as $file1) {
                        $isimage = $file1->is_valid_image();
                        if ($isimage) {
                            $courseimage = moodle_url::make_pluginfile_url(
                                $file1->get_contextid(),
                                $file1->get_component(),
                                $file1->get_filearea(),
                                $tabsEquivalguides + $countguides,
                                $file1->get_filepath(),
                                $file1->get_filename(),
                                false
                            );

                            $modulecardhtml .=html_writer::empty_tag('img', array('src' => $courseimage,'alt'=>'imagen de guía '.$module ,'id'=>'', 'class'=>'', 'role'=>'img'));
                        }
                        else{
                            //no hacer nada
                        }
                    }
                }
            } catch (Exception $th) {
                echo ' error en la prueba de robin', $th->getMessage();
            }
                   
            $modulecardhtml .= html_writer::end_tag('a');
            /**
             * Titulo de la guía
             */
            $cant_char_name = strlen($name);

            if($cant_char_name > 30){
                $modulecardhtml .= html_writer::start_tag('a', ['onclick' => $onclick, 'role'=>'link', 'aria-label'=>$name]);
                $modulecardhtml .= html_writer::tag('h5',substr($name, 0, 30).'...',['class' => "card-title_aulas_toribio"]);
                $modulecardhtml .= html_writer::end_tag('a');
            }
            else{
                $modulecardhtml .= html_writer::start_tag('a', ['onclick' => $onclick, 'role'=>'link', 'aria-label'=>$name]);
                $modulecardhtml .= html_writer::tag('h5',$name,['class' => "card-title_aulas_toribio"]);
                $modulecardhtml .= html_writer::end_tag('a');
            }
                    
            $cant_char =strlen($desc);
            if($cant_char > 100){
                $modulecardhtml .= html_writer::tag('p',substr($desc, 0, 100).'...',['class' => "card-text_aulas_toribio"]);
            }
            else{
                $modulecardhtml .= html_writer::tag('p',$desc,['class' => "card-text_aulas_toribio"]);
            }
  
            $modulecardhtml .= html_writer::end_tag('div');
            $modulecardhtml .= html_writer::end_tag('div');
            $cardscontainerhtml  .= $modulecardhtml;
            
            $modulecardhtml = '';
            $courseimage = '';
            $count++;
            $img++;
            
           
        }
        $cardscontainerhtml .= html_writer::end_tag('div');

        /**
         * Se cierra la construcción del contenedor de tarjetas
         */
        $cardscontainerhtml .= html_writer::end_tag('div');
        $cardscontainerhtml .= html_writer::end_tag('div');   
        $cardscontainerhtml .= html_writer::end_tag('div');
        $container_home .= $cardscontainerhtml;     
        $html .= $container_home;
        
        /**
         * Se construye la barra de navegación para regresar desde una sección incluyendo el evento para regresar de una sección
         */
        foreach ($modinfo->get_section_info_all() as $section => $thissection) {

            $modules = $modules + 1;
            if ($section == 0) {
                continue;
            }
            if ($section > $course->numsections) {
                continue;
            }
            if ($course->hiddensections && !(int)$thissection->visible) {
                continue;
            }
           
            $containerModule .= html_writer::start_tag('div',['id' => 'container-'.$modules.'','style'=>'margin-top:2em']);

            

            $containerModule .= html_writer::end_tag('div'); 

            $course_btn_back_lang = get_string('back', 'format_aulas_toribio');
            $onbackclick = 'M.format_aulas_toribio.back(' . $course->id . ')';
            $backnavhtml .= html_writer::start_tag('nav', ['class' => "navbar sticky-top navbar-light aulas_toribio-back-navbar", 'id' => 'aulas_toribio-back-nav-'.$modules, 'role'=>'navigation', 'aria-label'=> 'navegacion a la página principal de la guía']);
            $backnavhtml .= html_writer::start_tag('a',  ['class' => 'aulas_toribio-back-nav', 'onclick' => $onbackclick, 'role'=>'link', 'aria-label'=>'regresar a la pagina principal de la guía']);
            $backnavhtml .= html_writer::tag('i', '', ['class' => 'fa fa-arrow-left']);
            $backnavhtml .= $course_btn_back_lang;
            $backnavhtml .= html_writer::end_tag('a');
            // $aulas_toribio_images_activities = 'format/aulas_toribio/img/course-activities.png';
            // $img_aulas_toribio = '<img src="' . $aulas_toribio_images_activities . '" id="module-image-balboa" />';
            // $backnavhtml .= $img_aulas_toribio;
            /** lessons title  */
            // $backnavhtml .= html_writer::start_tag('div');
            // $title ='moduletitle'.strval($section);
            // $backnavhtml .= html_writer::tag('h5',$course->$title,['class' => "mt-4 aulas_toribio-title-modules",]);
            // $backnavhtml .= html_writer::tag('p','En esta sección apóyate en las indicaciones brindadas por tu docente',['class' => "aulas_toribio-subtitle-module",]);
            

            // $backnavhtml .= html_writer::end_tag('div');
            
            $backnavhtml .= html_writer::end_tag('nav');
            
            
            $backnavhtml .= $containerModule;

            $divisorvideos++;
            $containerModule ='';
            $count1++;
            // }
           }
           /**
         * Se cierra la construcción de los contenedores internos
         */
           $html .= $backnavhtml;
           $html = html_writer::tag('div', $html, ['id' => 'aulas_toribio-container-section']);
           if ($PAGE->user_is_editing()) {
               $load = 'M.format_aulas_toribio.showmodule()';
               $html .= html_writer::tag('div', get_string('editing', 'format_aulas_toribio'), ['class' => 'alert alert-warning alert-block fade in']);
               
           }
        return $html;
    }

    /**
     * number_to_roman
     *
     * @param integer $number
     * @return string
     */
    protected function number_to_roman($number)
    {
        $number = intval($number);
        $return = '';
        $romanarray = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];
        foreach ($romanarray as $roman => $value) {
            $matches = intval($number / $value);
            $return .= str_repeat($roman, $matches);
            $number = $number % $value;
        }
        return $return;
    }

    /**
     * number_to_alphabet
     *
     * @param integer $number
     * @return string
     */
    protected function number_to_alphabet($number)
    {
        $number = $number - 1;
        $alphabet = range("A", "Z");
        if ($number <= 25) {
            return $alphabet[$number];
        } elseif ($number > 25) {
            $dividend = ($number + 1);
            $alpha = '';
            while ($dividend > 0) {
                $modulo = ($dividend - 1) % 26;
                $alpha = $alphabet[$modulo] . $alpha;
                $dividend = floor((($dividend - $modulo) / 26));
            }
            return $alpha;
        }
    }

    /**
     * start_section_list
     *
     * @return string
     */
    protected function start_section_list()
    {   
        global $PAGE;
        if ($PAGE->user_is_editing()) {
            $class_editing ='aulas_toribio-editing';
            return html_writer::start_tag('ul', ['class' => 'buttons row '.$class_editing. '','id'=>'rowinit']);
        }
        return html_writer::start_tag('ul', ['class' => 'buttons row aulas_toribio-no-editing','id'=>'rowinit']);
    }

    public $content_internal = '';
    
    protected function get_contenttext(){
        return $this->content_internal;
    }

    /**
     * section_header
     *
     * @param stdclass $section
     * @param stdclass $course
     * @param bool $onsectionpage
     * @param int $sectionreturn
     * @return string
     */
    protected function section_header($section, $course, $onsectionpage, $sectionreturn = null)
    {
        global $PAGE, $CFG;
        $contenttext = $this->get_contenttext();
        $o = '';
        $containerText  = '';
        $currenttext = '';
        $sectionstyle = '';
        
        if ($section->section != 0) {
            if (!$section->visible) {
                $sectionstyle = ' hidden';
            } elseif (course_get_format($course)->is_section_current($section)) {
                $sectionstyle = ' current';
            }
        }
        $o .= html_writer::start_tag('li', ['id' => 'section-'.$section->section,
        'class' => 'section main clearfix col-sm-12'.$sectionstyle,
        'role' => 'region', 'aria-label' => get_section_name($course, $section),'style'=>'display:block']);
        $o .= html_writer::tag('span', $this->section_title($section, $course), ['class' => 'hidden sectionname']);
        $leftcontent = $this->section_left_content($section, $course, $onsectionpage);
        $o .= html_writer::tag('div', $leftcontent, ['class' => 'left side']);
        $rightcontent = $this->section_right_content($section, $course, $onsectionpage);
        $o .= html_writer::tag('div', $rightcontent, ['class' => 'right side']);
        $o .= html_writer::start_tag('div', ['class' => 'content']);
        $hasnamenotsecpg = (!$onsectionpage && ($section->section != 0 || !is_null($section->name)));
        $hasnamesecpg = ($onsectionpage && ($section->section == 0 && !is_null($section->name)));
        $classes = ' accesshide';
        if ($hasnamenotsecpg || $hasnamesecpg) {
            $classes = '';
        }
        $sectionname = html_writer::tag('span', $this->section_title($section, $course));
        if ($course->showdefaultsectionname) {
            $o .= $this->output->heading($sectionname, 3, 'sectionname' . $classes);
        }       
        $o .= html_writer::start_tag('div', ['class' => 'summary','id'=>'summary','style'=>'display:none']);
        //if ((int)$section != 0) {
        if ($section->section != 0) {
            $o .= $contenttext;
        }
        $o .= $this->format_summary_text($section);
        $context = context_course::instance($course->id);
        $o .= html_writer::end_tag('div');
        $o .= $this->section_availability_message($section, has_capability('moodle/course:viewhiddensections', $context));
        return $o;
    }
    
    

    /**
     * print_multiple_section_page
     *
     * @param stdclass $course
     * @param array $sections (argument not used)
     * @param array $mods (argument not used)
     * @param array $modnames (argument not used)
     * @param array $modnamesused (argument not used)
     */
    public function print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused)
    {
        global $PAGE,$DB;
        $onclick = 'M.format_aulas_toribio.showmoment';   
        $link_img_base='../course/format/aulas_toribio/img/';
        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();
        $context = context_course::instance($course->id);
        $completioninfo = new completion_info($course);
        $divisorshow = false;
        $count1 = 1;
        $count2 = 1;
        $currentdivisor1 = 1;
        $currentdivisor2 = 1;
        if (isset($_COOKIE['sectionvisible_'.$course->id])) {
            $sectionvisible = $_COOKIE['sectionvisible_'.$course->id];
        } elseif ($course->marker > 0) {
            $sectionvisible = $course->marker;
        } else {
            $sectionvisible = 1;
        }
        $htmlsection = false;

        foreach ($modinfo->get_section_info_all() as $section => $thissection) {

            

            $htmlsection[$section+1] = '';
            $contenttext ='';
            if ($section == 0) {
                $section0 = $thissection;
                continue;
            }
            if ($section > $course->numsections) {
                continue;
            }
            //format text
            //nombre de los modulos
            if (isset($course->{'moduletitle' . $currentdivisor1}) &&
            $count1 > $course->{'moduletitle' . $currentdivisor1}) {
            $currentdivisor1++;
            $count1 = 1;
        }
        if (isset($course->{'moduletitle' . $currentdivisor1}) &&
            $course->{'moduletitle' . $currentdivisor1} != 0 &&
            !isset($divisorshow[$currentdivisor1])) {
            $currentdivisor1html = $course->{'moduletitle' . $currentdivisor1};
            $currentdivisor1html = str_replace('[br]', '<br>', $currentdivisor1html);
            $currentdivisor1html = html_writer::tag('div', $currentdivisor1html, ['class' => 'moduletitle']);
            
            $divisorshow[$currentdivisor1] = true;
        }
    
        if (isset($course->{'moduletitle' . $currentdivisor1}) &&
            $course->{'moduletitle' . $currentdivisor1} == 1) {
            $name = (string)$course->{'moduletitle' . ($currentdivisor1-1)};
            $desc = (string)$course->{'modulename' . ($currentdivisor1-1)};

        } else {
            $name = (string)$course->{'moduletitle' . ($currentdivisor1-1)};
            $desc = (string)$course->{'modulename' . ($currentdivisor1-1)};

        }
        /**
         * 
         * Imagen interna de los modulos
         * 
         */
        $contenttext .= html_writer::start_tag('div',['class'=>'d-flex flex-wrap editing-modules']);

        $contenttext .= html_writer::start_tag('div', ['class' => "w-100 container-fluid p-0",'style'=>'', 'id'=>'']);
        /**
        * Imagen de las guías
        */
        $tabsEquivalguides = 7219413;
        try {
            $courseimage = '';
            $fs = get_file_storage();
            $files1 = $fs->get_area_files($context->id, 'format_aulas_toribio', 'images',$tabsEquivalguides + $section);
                if ($files1) {  
                    foreach ($files1 as $file1) {
                        $isimage = $file1->is_valid_image();
                        if ($isimage) {
                            $courseimage = moodle_url::make_pluginfile_url(
                                $file1->get_contextid(),
                                $file1->get_component(),
                                $file1->get_filearea(),
                                $tabsEquivalguides + $section,
                                $file1->get_filepath(),
                                $file1->get_filename(),
                                false
                            );

                            $contenttext .=html_writer::empty_tag('img', array('src' => $courseimage,'alt'=>'imagen principal guía'.$name,'id'=>'', 'class'=>'w-100','role'=>'img'));
                        }
                        else{
                            //no hacer nada
                        }
                    }
                }
            } catch (Exception $th) {
                echo ' error en la prueba de robin', $th->getMessage();
            }
        $contenttext .= html_writer::end_tag('div');

        /**
        * Objetivo de la guia
        */
        $contenttext .= html_writer::start_tag('div',['class' => "row mt-4"]);
        try {
            $idCourse = (int)$course->id;
            $nameeditor = 'editor_tabs'.$section;
            $valueFile = $DB->get_record_sql("SELECT value FROM mdl_course_format_options  where name='$nameeditor' and courseid=$idCourse");
            $editorcontent = '';
                        
            if(empty($course->{'tabsVideo'.$section}) || $course->{'tabsVideo'.$section} == ' '){
                $contenttext .= html_writer::start_tag('div',['class' => "col-sm-12 justify-text", 'style'=>"margin-bottom: 2rem;", 'aria-labelledby' => 'descripción de la guía'.$name]);
            }else{
                $contenttext .= html_writer::start_tag('div',['class' => "col-sm-6 justify-text", 'style'=>"margin-bottom: 2rem;", 'aria-labelledby' => 'descripción de la guía'.$name]);
            }
            $contenttext .= $valueFile->value;
            $contenttext .= html_writer::end_tag('div');
                                
        } catch (Exception $th) {
            echo ' error en el editor de bienvenidos', $th->getMessage();
        }
        /**
        * 
        * Video de la guía
        * 
        */
                    
        $contenttext .= html_writer::start_tag('div',['class' => "col-sm-6", 'style'=>"margin-bottom: 2rem;"]);
        if(empty($course->{'tabsVideo'.$section}) || $course->{'tabsVideo'.$section} == ' '){
            $contenttext .= html_writer::start_tag('div', ['style'=>'display:none']);
            $contenttext .= html_writer::end_tag('div');
        }
        else{
            $contenttext .= html_writer::start_tag('div',['class' => 'video-tabs', 'role'=> 'presentation']);
            if (strpos($course->{'tabsVideo'.$section}, 'youtube') !== false) {
                $contenttext .= html_writer::start_tag('iframe',['src'=>"https://www.youtube-nocookie.com/embed/".substr($course->{'tabsVideo'.$section},32,11)."?rel=0&showinfo=0&modestbranding=1", 'frameborder'=>'0','allow'=>'autoplay; encrypted-media','allowfullscreen'=>'true','webkitallowfullscreen'=>'true','id'=>'video-tabs-'.$section, 'title'=>'video presentacion pestaña '.$course->$tabsname]);
                $contenttext .= html_writer::end_tag('iframe');
            }else{
                $contenttext .= html_writer::start_tag('iframe',['src'=>"http://tumlab.local:8082/embed?m=".substr($course->{'tabsVideo'.$section},32)."", 'id'=>'video-tabs-'.$section, 'title'=>'video presentacion pestaña '.$course->$tabsname]);
                $contenttext .= html_writer::end_tag('iframe');
            }
            $contenttext .= html_writer::end_tag('div');
        }
        // $contenttext  .= $modulevideohtml;
        $contenttext .= html_writer::end_tag('div');

        $contenttext .= html_writer::end_tag('div');
        
        /**
         * Titulo de los momentos
         */
        $contenttext .= html_writer::start_tag('div', ['class'=>'w-100 text-center title-momentos']);
        $contenttext .= html_writer::tag('h4',get_string('title-moments', 'format_aulas_toribio'));
        // $contenttext .= html_writer::tag('h4','Elige un momento del Design Thinking para continuar');
        $contenttext .= html_writer::end_tag('div');
        /**
         * Imagenes de los momentos
         */
        $contenttext .= html_writer::start_tag('div', ['class'=>'row w-100 card-moment-content']);

        /**
         * Empatizar
         */
        $contenttext .= html_writer::start_tag('div', ['class'=>'d-flex flex-column align-items-center','id'=> 'card-empatizar', 'role'=>'figure']);
        $contenttext .= html_writer::empty_tag('img', array('src' => $link_img_base.'arrow-selector.svg','class' => '', 'alt' => 'indicacion de que se encuentra seleccionado el momento empatizar','id'=>'arrow-image-empatizar', 'style'=>'display:none'));
        $contenttext .= html_writer::start_tag('div', ['class'=>'card mt-3']);
        if(file_exists(__DIR__.'/img/empatizar.svg')){
            if($section == 1){
                $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',1)', 'role'=> 'button']);
            }else if($section == 2){
                $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',1)', 'role'=> 'button']);
            }else{
                $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',1)', 'role'=> 'button']);
            }
            $contenttext .= html_writer::empty_tag('img', array('src' => $link_img_base.'empatizar.svg','class' => '', 'alt' => 'momento empatizar','id'=>'module-image-1'));
            $contenttext .= html_writer::end_tag('a');
            
        }
        $contenttext .= html_writer::start_tag('div', ['class'=>'card-body']);
        if($section == 1){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',1)', 'role'=> 'button']);
        }else if($section == 2){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',1)', 'role'=> 'button']);
        }else{
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',1)', 'role'=> 'button']);
        }
        
        $contenttext .= html_writer::tag('h5','Empatizar', ['class'=>'card-title_aulas_toribio']);
        $contenttext .=html_writer::empty_tag('img', array('src' => $link_img_base.'arrow.svg','alt'=>'flecha para dirigirse al momento empatizar','id'=>'', ));
        $contenttext .= html_writer::end_tag('a');
        $contenttext .= html_writer::end_tag('div');
        $contenttext .= html_writer::end_tag('div');
        $contenttext .= html_writer::end_tag('div');
        /**
         * Definir
         */
        $contenttext .= html_writer::start_tag('div', ['class'=>'d-flex flex-column align-items-center','id'=> 'card-definir', 'role'=>'figure']);
        $contenttext .= html_writer::empty_tag('img', array('src' => $link_img_base.'arrow-selector.svg','class' => '', 'alt' => 'indicacion de que se encuentra seleccionado el momento definir','id'=>'arrow-image-definir', 'style'=>'display:none'));
        $contenttext .= html_writer::start_tag('div', ['class'=>'card mt-3']);
        if(file_exists(__DIR__.'/img/definir.svg')){
            if($section == 1){
                $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',2)', 'role'=> 'button']);
            }else if($section == 2){
                $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',2)', 'role'=> 'button']);
            }else{
                $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',2)', 'role'=> 'button']);
            }
            $contenttext .= html_writer::empty_tag('img', array('src' => $link_img_base.'definir.svg','class' => '', 'alt' => 'momento definir','id'=>'module-image-1'));
            $contenttext .= html_writer::end_tag('a');
        }
        $contenttext .= html_writer::start_tag('div', ['class'=>'card-body']);
        if($section == 1){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',2)', 'role'=> 'button']);
        }else if($section == 2){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',2)', 'role'=> 'button']);
        }else{
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',2)', 'role'=> 'button']);
        }
        $contenttext .= html_writer::tag('h5','Definir', ['class'=>'card-title_aulas_toribio']);
        $contenttext .=html_writer::empty_tag('img', array('src' => $link_img_base.'arrow.svg','alt'=>'flecha para dirigirse al momento definir','id'=>'', ));
        $contenttext .= html_writer::end_tag('a');
        $contenttext .= html_writer::end_tag('div');
        $contenttext .= html_writer::end_tag('div');
        $contenttext .= html_writer::end_tag('div');
        /**
         * Idear
         */
        $contenttext .= html_writer::start_tag('div', ['class'=>'d-flex flex-column align-items-center','id'=> 'card-idear', 'role'=>'figure']);
        $contenttext .= html_writer::empty_tag('img', array('src' => $link_img_base.'arrow-selector.svg','class' => '',  'alt' => 'indicacion de que se encuentra seleccionado el momento idear','id'=>'arrow-image-idear', 'style'=>'display:none'));
        $contenttext .= html_writer::start_tag('div', ['class'=>'card mt-3']);
        if(file_exists(__DIR__.'/img/idear.svg')){
            if($section == 1){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',3)', 'role'=> 'button']);
        }else if($section == 2){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',3)', 'role'=> 'button']);
        }else{
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',3)', 'role'=> 'button']);
        }
            $contenttext .= html_writer::empty_tag('img', array('src' => $link_img_base.'idear.svg','class' => '', 'alt' => 'momento idear','id'=>'module-image-1'));
            $contenttext .= html_writer::end_tag('a');
        }
        $contenttext .= html_writer::start_tag('div', ['class'=>'card-body']);
        if($section == 1){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',3)', 'role'=> 'button']);
        }else if($section == 2){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',3)', 'role'=> 'button']);
        }else{
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',3)', 'role'=> 'button']);
        }
        $contenttext .= html_writer::tag('h5','Idear', ['class'=>'card-title_aulas_toribio']);
        $contenttext .=html_writer::empty_tag('img', array('src' => $link_img_base.'arrow.svg','alt'=>'flecha para dirigirse al momento idear','id'=>'', ));
        $contenttext .= html_writer::end_tag('a');
        $contenttext .= html_writer::end_tag('div');
        $contenttext .= html_writer::end_tag('div');
        $contenttext .= html_writer::end_tag('div');
        /**
         * Prototipar
         */
        $contenttext .= html_writer::start_tag('div', ['class'=>'d-flex flex-column align-items-center','id'=> 'card-prototipar', 'role'=>'figure']);
        $contenttext .= html_writer::empty_tag('img', array('src' => $link_img_base.'arrow-selector.svg','class' => '', 'alt' => 'indicacion de que se encuentra seleccionado el momento prototipar','id'=>'arrow-image-prototipar', 'style'=>'display:none'));
        $contenttext .= html_writer::start_tag('div', ['class'=>'card mt-3']);
        if(file_exists(__DIR__.'/img/prototipar.svg')){
            if($section == 1){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',4)', 'role'=> 'button']);
        }else if($section == 2){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',4)', 'role'=> 'button']);
        }else{
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',4)', 'role'=> 'button']);
        }
            $contenttext .= html_writer::empty_tag('img', array('src' => $link_img_base.'prototipar.svg','class' => '',  'alt' => 'momento prototipar','id'=>'module-image-1'));
            $contenttext .= html_writer::end_tag('a');
        }
        $contenttext .= html_writer::start_tag('div', ['class'=>'card-body']);
        if($section == 1){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',4)', 'role'=> 'button']);
        }else if($section == 2){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',4)', 'role'=> 'button']);
        }else{
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',4)', 'role'=> 'button']);
        }
        $contenttext .= html_writer::tag('h5','Prototipar', ['class'=>'card-title_aulas_toribio']);
        $contenttext .=html_writer::empty_tag('img', array('src' => $link_img_base.'arrow.svg','alt'=>'flecha para dirigirse al momento prototipar','id'=>'', ));
        $contenttext .= html_writer::end_tag('a');
        $contenttext .= html_writer::end_tag('div');
        $contenttext .= html_writer::end_tag('div');
        $contenttext .= html_writer::end_tag('div');
        /**
         * Testear
         */
        $contenttext .= html_writer::start_tag('div', ['class'=>'d-flex flex-column align-items-center', 'id'=> 'card-testear', 'role'=>'figure']);
        $contenttext .= html_writer::empty_tag('img', array('src' => $link_img_base.'arrow-selector.svg','class' => '', 'alt' => 'indicacion de que se encuentra seleccionado el momento testear','id'=>'arrow-image-testear', 'style'=>'display:none'));
        $contenttext .= html_writer::start_tag('div', ['class'=>'card mt-3']);
        if(file_exists(__DIR__.'/img/testear.svg')){
            if($section == 1){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',5)', 'role'=> 'button']);
        }else if($section == 2){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',5)', 'role'=> 'button']);
        }else{
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment card-moment-image', 'onclick' => $onclick.'('.$section.',5)', 'role'=> 'button']);
        }
            $contenttext .= html_writer::empty_tag('img', array('src' => $link_img_base.'testear.svg','class' => '', 'alt' => 'momento testear','id'=>'module-image-1'));
            $contenttext .= html_writer::end_tag('a');
        }
        $contenttext .= html_writer::start_tag('div', ['class'=>'card-body']);
        if($section == 1){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',5)', 'role'=> 'button']);
        }else if($section == 2){
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',5)', 'role'=> 'button']);
        }else{
            $contenttext .= html_writer::start_tag('a',  ['class' => 'card-moment', 'onclick' => $onclick.'('.$section.',5)', 'role'=> 'button']);
        }
        $contenttext .= html_writer::tag('h5','Testear', ['class'=>'card-title_aulas_toribio']);
        $contenttext .=html_writer::empty_tag('img', array('src' => $link_img_base.'arrow.svg','alt'=>'flecha para dirigirse al momento testear','id'=>'', ));
        $contenttext .= html_writer::end_tag('a');
        $contenttext .= html_writer::end_tag('div');
        $contenttext .= html_writer::end_tag('div');
        $contenttext .= html_writer::end_tag('div');

        $contenttext .= html_writer::end_tag('div');

        /**
         * Impresion de los 15 momentos
         */

         /**
          * Primeros 5 momentos de la primera guia
          */
          
          if($section == 1){
            for($i = 1;$i <=5; $i++){
                $contenttext .= html_writer::start_tag('div', ['class'=>'container-fluid mt-5', 'id'=>'momento'.$i,'style'=>'display:none']);
                /**
                * Descripción del momento
                */
                try {
                    $idCourse = (int)$course->id;
                    $nameeditor = 'editor_tabs_moment'.$i;
                    $valueFile = $DB->get_record_sql("SELECT value FROM mdl_course_format_options  where name='$nameeditor' and courseid=$idCourse");
                    $editorcontent = '';
                                
                    $contenttext .= html_writer::start_tag('div',['class' => "col-sm-12 justify-text", 'style'=>"margin-bottom: 2rem;"]);
                    $contenttext .= $valueFile->value;
                    $contenttext .= html_writer::tag('p','Selecciona una actividad',['id'=>'acivity-selecct-title', 'aria-labelledby'=>'Selecciona una actividad']);
                    $contenttext .= html_writer::end_tag('div');
                                        
                } catch (Exception $th) {
                    echo ' error en el editor de bienvenidos', $th->getMessage();
                }
                $contenttext .= html_writer::end_tag('div');

            }
          }else if($section == 2){
            for($i = 6;$i <=10; $i++){
                $contenttext .= html_writer::start_tag('div', ['class'=>'container-fluid', 'id'=>'momento'.$i,'style'=>'display:none']);
                /**
                * Descripción del momento
                */
                try {
                    $idCourse = (int)$course->id;
                    $nameeditor = 'editor_tabs_moment'.$i;
                    $valueFile = $DB->get_record_sql("SELECT value FROM mdl_course_format_options  where name='$nameeditor' and courseid=$idCourse");
                    $editorcontent = '';
                                
                    $contenttext .= html_writer::start_tag('div',['class' => "col-sm-12 justify-text", 'style'=>"margin-bottom: 2rem;", 'aria-labelledby'=>'descripción del momento seleccionado']);
                    $contenttext .= $valueFile->value;
                    $contenttext .= html_writer::end_tag('div');
                                        
                } catch (Exception $th) {
                    echo ' error en el editor de bienvenidos', $th->getMessage();
                }
                $contenttext .= html_writer::end_tag('div');

            }
          }else{
            for($i = 11;$i <=15; $i++){
                $contenttext .= html_writer::start_tag('div', ['class'=>'container-fluid', 'id'=>'momento'.$i,'style'=>'display:none']);
                /**
                * Descripción del momento
                */
                try {
                    $idCourse = (int)$course->id;
                    $nameeditor = 'editor_tabs_moment'.$i;
                    $valueFile = $DB->get_record_sql("SELECT value FROM mdl_course_format_options  where name='$nameeditor' and courseid=$idCourse");
                    $editorcontent = '';
                                
                    $contenttext .= html_writer::start_tag('div',['class' => "col-sm-12 justify-text", 'style'=>"margin-bottom: 2rem;", 'aria-labelledby'=>'descripción del momento seleccionado']);
                    $contenttext .= $valueFile->value;
                    $contenttext .= html_writer::end_tag('div');
                                        
                } catch (Exception $th) {
                    echo ' error en el editor de bienvenidos', $th->getMessage();
                }
                $contenttext .= html_writer::end_tag('div');

            }
          }
          
        /**
         * llamo a content text
         */
        $this->content_internal=$contenttext;
        $count1++;
        $count2++;
            /* if is not editing verify the rules to display the sections */
            if (!$PAGE->user_is_editing()) {
                if ($course->hiddensections && !(int)$thissection->visible) {
                    continue;
                }
                if (!$thissection->available && !empty($thissection->availableinfo)) {
                    // $htmlsection[$section] .= $this->section_header($thissection, $course, false, 0, $contenttext);
                    $htmlsection[$section] .= $this->section_header($thissection, $course, false, 0);
                    continue;
                }
                if (!$thissection->uservisible || !$thissection->visible) {
                    $htmlsection[$section] .= $this->section_hidden($section, $course->id);
                    continue;
                }
            }
            
            // $htmlsection[$section] .= $this->section_header($thissection, $course, false, 0, $contenttext);
            $htmlsection[$section] .= $this->section_header($thissection, $course, false, 0);
            if ($thissection->uservisible) {
                $htmlsection[$section] .= $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                $htmlsection[$section] .= $this->courserenderer->course_section_add_cm_control($course, $section, 0);
            }
            $htmlsection[$section] .= $this->section_footer();
        }
       // echo "<p id='charge'>".get_string('cargando', 'format_aulas_toribio')."</p>";
        echo "<div id='charge' class='lds-roller'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>";
        
        if ($section0->summary || !empty($modinfo->sections[0]) || !$PAGE->user_is_editing()) {
            // $htmlsection0 = $this->section_header($section0, $course, false, 0, $contenttext);
            $htmlsection0 = $this->section_header($section0, $course, false, 0);
            $htmlsection0 .= $this->courserenderer->course_section_cm_list($course, $section0, 0);
            $htmlsection0 .= $this->courserenderer->course_section_add_cm_control($course, 0, 0);
            $htmlsection0 .= $this->section_footer();
        }
        echo $completioninfo->display_help_icon();
        echo $this->output->heading($this->page_title(), 2, 'accesshide');
        echo $this->course_activity_clipboard($course, 0);        
        echo $this->start_section_list();  
        echo $this->get_button_section($course, 0);
        echo html_writer::tag('span', $htmlsection0, ['class' => 'above','id'=>'above', 'style'=>'width:100%']);
        
        

        foreach ($htmlsection as $current) {
            echo $current;
        }

        if ($PAGE->user_is_editing() and has_capability('moodle/course:update', $context)) {

            echo html_writer::tag('style', '#rowinit { display: block !important; }');            
            echo html_writer::tag('style', '.course-content ul.buttons #section-'.$sectionvisible.' { display: block;flex: 0 0 100%;max-width: 100%;  }');
            echo html_writer::tag('style', '.course-content ul.buttons li.section.main { max-width: 100%; }');
            echo html_writer::tag('style', '#charge { display: none; }');
            echo html_writer::tag('style', '#summary { display: block !important; }');
            foreach ($modinfo->get_section_info_all() as $section => $thissection) {
                if ($section <= $course->numsections or empty($modinfo->sections[$section])) {
                    continue;
                }
                echo $this->stealth_section_header($section);
                echo $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                echo $this->stealth_section_footer();
            }
            echo $this->end_section_list();
            
            echo html_writer::start_tag('div', ['id' => 'changenumsections', 'class' => 'mdl-right']);
            $straddsection = get_string('increasesections', 'moodle');
            $url = new moodle_url('/course/changenumsections.php', ['courseid' => $course->id,
                'increase' => true, 'sesskey' => sesskey()]);
            $icon = $this->output->pix_icon('t/switch_plus', $straddsection);
            echo html_writer::link($url, $icon.get_accesshide($straddsection), ['class' => 'increase-sections']);
            if ($course->numsections > 0) {
                $strremovesection = get_string('reducesections', 'moodle');
                $url = new moodle_url('/course/changenumsections.php', ['courseid' => $course->id,
                    'increase' => false, 'sesskey' => sesskey()]);
                $icon = $this->output->pix_icon('t/switch_minus', $strremovesection);
                echo html_writer::link(
                    $url,
                    $icon.get_accesshide($strremovesection),
                ['class' => 'reduce-sections']
                );
            }
            echo html_writer::end_tag('div');
        } else {
            echo $this->end_section_list();
        }
        echo html_writer::tag('style', '.course-content ul.buttons #section-'.$sectionvisible.' { display: none; }');
        echo html_writer::tag('style', '#completionprogressid { display: none; }');
        echo html_writer::tag('style', '#rowinit { display: none; }');

        if (!$PAGE->user_is_editing()) {
            $PAGE->requires->js_init_call('M.format_aulas_toribio.init', [$course->numsections,$course->id]);
            echo html_writer::tag('style', '#summary { display: block !important; }');

        }
    }
  


}