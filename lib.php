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

require_once($CFG->dirroot. '/course/format/topics/lib.php');
require_once($CFG->dirroot. '/course/format/lib.php');

class format_aulas_toribio extends format_topics 
{
    
     /**
     * course_format_options
     *
     * @param bool $foreditform
     * @return array
     * @param stdclass|array $data
     */
    public function course_format_options($foreditform = false){
        
        global $PAGE,$DB;
        $course = $this->get_course();
        static $courseformatoptions = false;
        if ($courseformatoptions === false) {
            $courseconfig = get_config('moodlecourse');
            /**
             * Numero de modulos o guías que va a tener el curso
             */
            $courseformatoptions['numsections'] = array(
                'default' => $courseconfig->numsections,
                'type' => PARAM_INT,
            );
            $courseformatoptions['hiddensections'] = array(
                'default' => $courseconfig->hiddensections,
                'type' => PARAM_INT,    
            );
            $courseformatoptions['showdefaultsectionname'] = array(
                'default' => get_config('format_aulas_toribio', 'showdefaultsectionname'),
                'type' => PARAM_INT,
            );
            
            /** 
             * file picker para el banner del curso 
            */
            $courseformatoptions['filepicker_tab_home_filemanager'] = array(
                'label' => get_string('tabsimagehome', 'format_aulas_toribio'),
                'help' => 'tabsimagehome',
                'help_component' => 'format_aulas_toribio',
                'element_type' => 'filemanager',
                'element_attributes' => array(
                    null,
                    array(
                        'subdirs' => 0,
                        'maxbytes' => 2000000,
                        'maxfiles' => 1,
                        'accepted_types' => array(
                            '.jpg',
                            '.png',
                            '.jpeg',
                            '.svg',
                            '.gif',
                        ),
                    ),
                    
                ),
                );
                $optionsfile =array(
                    'subdirs' => 0,
                    'maxbytes' => 2000000,
                    'maxfiles' => 1,
                    'accepted_types' => array(
                        '.jpg',
                        '.png',
                        '.jpeg',
                        '.svg',
                        '.gif',
                    ),
                );
                if($this->courseid != null){
                    $context = context_course::instance($this->courseid);
                    $name = 'filepicker_tab_home_filemanager';
                    $tabsEquival = 3424;
                    $draftid_editor_file = file_get_submitted_draft_itemid('filepicker_tab_home_filemanager');
                    
                    file_prepare_draft_area($draftid_editor_file, $context->id, 'format_aulas_toribio', 'images',$tabsEquival, $optionsfile);
                    //get the id of all section
                    $idfile = $DB->get_record_sql("SELECT id FROM mdl_course_format_options  where name='$name' and courseid=$course->id");
                    $idelementfile = (int)$idfile->id;
                    $newvalueid = new stdClass();
                    $newvalueid->id = $idelementfile;
                    $newvalueid->value =  $draftid_editor_file;
                    $DB->update_record('course_format_options', $newvalueid);
                    }
                /**
                 * Objetivo principal
                 */
                 $context_editor = context_course::instance($this->courseid);
                 $courseformatoptions['editor_tabs_home_editor'] = array(
                    'label' => get_string('tabseditor_home', 'format_aulas_toribio',$i),
                    'help' => 'tabseditor_home',
                    'help_component' => 'format_aulas_toribio',
                    'element_type' => 'editor',
                    'element_attributes' => array(
                        null,
                        array(
                            'trusttext' => 0,
                            'subdirs' => 0,
                            'maxfiles' => 2,
                            'maxbytes' => 2000000,
                            'context' => $context_editor,
                            'return_types' => 15,
                            'enable_filemanagement' => true,
                            'noclean' => 1,
                            'changeformat' => 0,
                        ),
                        
                    ),
                    );
                    $optionsfile =array(
                        'trusttext' => 0,
                        'subdirs' => 0,
                        'maxfiles' => 2,
                        'maxbytes' => 2000000,
                        'context' => $context_editor,
                        'return_types' => 15,
                        'enable_filemanagement' => true,
                        'noclean' => 1,
                        'changeformat' => 0,
                    );
                    if($this->courseid != null){
                    $context = context_course::instance($this->courseid);
                    $name = 'editor_tabs_home_editor';
                    $draftid_editor_file = file_get_submitted_draft_itemid('editor_tabs_home_editor');
                    
                    $currenttext  = file_prepare_draft_area($draftid_editor_file, $context->id, 'format_aulas_toribio', 'editortabs',1, $optionsfile);
                    
                    //get the id of all section
                    $idfile = $DB->get_record_sql("SELECT id FROM mdl_course_format_options  where name='$name' and courseid=$course->id");
                    $idelementfile = (int)$idfile->id;
                    $newvalueid = new stdClass();
                    $newvalueid->id = $idelementfile;
                    $newvalueid->value =  $draftid_editor_file;
                    $DB->update_record('course_format_options', $newvalueid);
                    }

            /**
             * Url del video del home
             */
            $courseformatoptions['Modulevideohome'] = array(
                'default' => get_config('format_aulas_toribio', 'Modulevideohome'),
                'type' => PARAM_TEXT,
            );

            /**
             * Metafora del curso
             */
            $courseformatoptions['filepicker_metaf_home_filemanager'] = array(
                'label' => get_string('tabsimagemetaf', 'format_aulas_toribio',$i),
                'help' => 'tabsimagemetaf',
                'help_component' => 'format_aulas_toribio',
                'element_type' => 'filemanager',
                'element_attributes' => array(
                    null,
                    array(
                        'subdirs' => 0,
                        'maxbytes' => 2000000,
                        'maxfiles' => 1,
                        'accepted_types' => array(
                            '.jpg',
                            '.png',
                            '.jpeg',
                            '.svg',
                            '.gif',
                        ),
                    ),
                    
                ),
                );
                $optionsfile =array(
                    'subdirs' => 0,
                    'maxbytes' => 2000000,
                    'maxfiles' => 1,
                    'accepted_types' => array(
                        '.jpg',
                        '.png',
                        '.jpeg',
                        '.svg',
                        '.gif',
                    ),
                );
                if($this->courseid != null){
                $context = context_course::instance($this->courseid);
                $name = 'filepicker_metaf_home_filemanager';
                $tabsEquival = 13520;
                $draftid_editor_file = file_get_submitted_draft_itemid('filepicker_metaf_home_filemanager');
                
                file_prepare_draft_area($draftid_editor_file, $context->id, 'format_aulas_toribio', 'images', $tabsEquival, $optionsfile);
                //get the id of all section
                $idfile = $DB->get_record_sql("SELECT id FROM mdl_course_format_options  where name='$name' and courseid=$course->id");
                $idelementfile = (int)$idfile->id;
                $newvalueid = new stdClass();
                $newvalueid->id = $idelementfile;
                $newvalueid->value =  $draftid_editor_file;
                $DB->update_record('course_format_options', $newvalueid);
                }
                
                for ($j = 1; $j <=  3; $j++) {
                    /**
                     * imagenes de las ventanas modales de la metafora
                     */
                    $courseformatoptions['filepicker_metaf_pop'.$j.'_filemanager'] = array(
                        'label' => get_string('tabsimagemetafpop', 'format_aulas_toribio',$j),
                        'help' => 'tabsimagemetafpop',
                        'help_component' => 'format_aulas_toribio',
                        'element_type' => 'filemanager',
                        'element_attributes' => array(
                            null,
                            array(
                                'subdirs' => 0,
                                'maxbytes' => 2000000,
                                'maxfiles' => 1,
                                'accepted_types' => array(
                                    '.jpg',
                                    '.png',
                                    '.jpeg',
                                    '.svg',
                                    '.gif',
                                ),
                            ),
                            
                        ),
                        );
                        $optionsfile =array(
                            'subdirs' => 0,
                            'maxbytes' => 2000000,
                            'maxfiles' => 1,
                            'accepted_types' => array(
                                '.jpg',
                                '.png',
                                '.jpeg',
                                '.svg',
                                '.gif',
                            ),
                        );
                        if($this->courseid != null){
                        $context = context_course::instance($this->courseid);
                        $name = 'filepicker_metaf_pop'.$j.'_filemanager';
                        $tabsEquival = 135201;
                        $draftid_editor_file = file_get_submitted_draft_itemid('filepicker_metaf_pop'.$j.'_filemanager');
                        
                        file_prepare_draft_area($draftid_editor_file, $context->id, 'format_aulas_toribio', 'images', $tabsEquival += $j , $optionsfile);
                        //get the id of all section
                        $idfile = $DB->get_record_sql("SELECT id FROM mdl_course_format_options  where name='$name' and courseid=$course->id");
                        $idelementfile = (int)$idfile->id;
                        $newvalueid = new stdClass();
                        $newvalueid->id = $idelementfile;
                        $newvalueid->value =  $draftid_editor_file;
                        $DB->update_record('course_format_options', $newvalueid);
                        }
                }

            $courseformatoptions['title'] = array(
                'label' => get_string('title_tabs', 'format_aulas_toribio'),
                'element_type' => 'header',
            );

            $editormomet = 0;
            for ($i = 1; $i <=  3; $i++) {

                /**
                 * Titulo de la guía
                 */
                $courseformatoptions['tabsTitle'.$i] = array(
                    'default' => get_config('format_aulas_toribio', 'tabsTitle'),
                    'type' => PARAM_TEXT,
                );

                /** file picker para la imagen de la guia */
                $courseformatoptions['filepicker_tabs'.$i.'_filemanager'] = array(
                    'label' => get_string('tabsimageguide', 'format_aulas_toribio',$i),
                    'help' => 'tabsimageguide',
                    'help_component' => 'format_aulas_toribio',
                    'element_type' => 'filemanager',
                    'element_attributes' => array(
                        null,
                        array(
                            'subdirs' => 0,
                            'maxbytes' => 2000000,
                            'maxfiles' => 1,
                            'accepted_types' => array(
                                '.jpg',
                                '.png',
                                '.jpeg',
                                '.svg',
                                '.gif',
                            ),
                        ),
                    ),
                );
                $optionsfile =array(
                    'subdirs' => 0,
                    'maxbytes' => 2000000,
                    'maxfiles' => 1,
                    'accepted_types' => array(
                        '.jpg',
                        '.png',
                        '.jpeg',
                        '.svg',
                        '.gif',
                    ),
                );
                if($this->courseid != null){
                $context = context_course::instance($this->courseid);
                $name = 'filepicker_tabs'.$i.'_filemanager';
                /**
                * cada numero es una letra que representa la letra
                * g=7
                * u=21
                * i=9
                * d=4
                */
                $tabsEquivalguides = 72194;
                $draftid_editor_file = file_get_submitted_draft_itemid('filepicker_tabs'.$i.'_filemanager');
                file_prepare_draft_area($draftid_editor_file, $context->id, 'format_aulas_toribio', 'images', $tabsEquivalguides += $i , $optionsfile);
                //get the id of all section
                $idfile = $DB->get_record_sql("SELECT id FROM mdl_course_format_options  where name='$name' and courseid=$course->id");
                $idelementfile = (int)$idfile->id;
                $newvalueid = new stdClass();
                $newvalueid->id = $idelementfile;
                $newvalueid->value =  $draftid_editor_file;
                $DB->update_record('course_format_options', $newvalueid);
                }

                 /** 
                  * file picker para el banner de los modulos 
                  */
                 $courseformatoptions['filepicker_tabs_module'.$i.'_filemanager'] = array(
                    'label' => get_string('tabsimage', 'format_aulas_toribio',$i),
                    'help' => 'tabsimage',
                    'help_component' => 'format_aulas_toribio',
                    'element_type' => 'filemanager',
                    'element_attributes' => array(
                        null,
                        array(
                            'subdirs' => 0,
                            'maxbytes' => 2000000,
                            'maxfiles' => 1,
                            'accepted_types' => array(
                                '.jpg',
                                '.png',
                                '.jpeg',
                                '.svg',
                                '.gif',
                            ),
                        ),
                    ),
                );
                $optionsfile =array(
                    'subdirs' => 0,
                    'maxbytes' => 2000000,
                    'maxfiles' => 1,
                    'accepted_types' => array(
                        '.jpg',
                        '.png',
                        '.jpeg',
                        '.svg',
                        '.gif',
                    ),
                );
                if($this->courseid != null){
                $context = context_course::instance($this->courseid);
                $name = 'filepicker_tabs_module'.$i.'_filemanager';
                /**
                * cada numero es una letra que representa la letra
                * g=7
                * u=21
                * i=9
                * d=4
                * m=13
                */
                $tabsEquivalguides = 7219413;
                $draftid_editor_file = file_get_submitted_draft_itemid('filepicker_tabs_module'.$i.'_filemanager');
                file_prepare_draft_area($draftid_editor_file, $context->id, 'format_aulas_toribio', 'images', $tabsEquivalguides += $i , $optionsfile);
                //get the id of all section
                $idfile = $DB->get_record_sql("SELECT id FROM mdl_course_format_options  where name='$name' and courseid=$course->id");
                $idelementfile = (int)$idfile->id;
                $newvalueid = new stdClass();
                $newvalueid->id = $idelementfile;
                $newvalueid->value =  $draftid_editor_file;
                $DB->update_record('course_format_options', $newvalueid);
                }
               
                /** 
                 * 
                 * editor  para la descripcion de los modulos
                 * 
                 */

                $courseformatoptions['editor_tabs'.$i.'_editor'] = array(
                    'label' => get_string('tabseditor', 'format_aulas_toribio',$i),
                    'help' => 'tabseditor',
                    'help_component' => 'format_aulas_toribio',
                    'element_type' => 'editor',
                    'element_attributes' => array(
                        null,
                        array(
                            'trusttext' => 0,
                            'subdirs' => 0,
                            'maxfiles' => 2,
                            'maxbytes' => 2000000,
                            'context' => $context,
                            'return_types' => 15,
                            'enable_filemanagement' => true,
                            'removeorphaneddrafts' => false,
                            'noclean' => 0,
                            'changeformat' => 0,
                        ),
                        
                    ),
                    );
                    $optionsfile =array(
                        'trusttext' => 0,
                        'subdirs' => 0,
                        'maxfiles' => 2,
                        'maxbytes' => 2000000,
                        'context' => $context,
                        'return_types' => 15,
                        'enable_filemanagement' => true,
                        'removeorphaneddrafts' => false,
                        'noclean' => 0,
                        'changeformat' => 0,
                    );
                    if($this->courseid != null){
                    $context = context_course::instance($this->courseid);
                    $name = 'editor_tabs'.$i.'_editor';
                    $draftid_editor_file = file_get_submitted_draft_itemid('editor_tabs'.$i.'_editor');
                    
                    $currenttext  = file_prepare_draft_area($draftid_editor_file, $context->id, 'format_aulas_toribio', 'editortabs', $i, $optionsfile);
                    
                    //get the id of all section
                    $idfile = $DB->get_record_sql("SELECT id FROM mdl_course_format_options  where name='$name' and courseid=$course->id");
                    $idelementfile = (int)$idfile->id;
                    $newvalueid = new stdClass();
                    $newvalueid->id = $idelementfile;
                    $newvalueid->value =  $draftid_editor_file;
                    $DB->update_record('course_format_options', $newvalueid);
                    }

                    /**
                     * videos de los modulos
                     */
                    $courseformatoptions['tabsVideo'.$i] = array(
                        'default' => get_config('format_aulas_toribio', 'tabsVideo'),
                        'type' => PARAM_TEXT,
                    );
                    

                    
                    for ($j = 1; $j <=  3; $j++) {
                        $editormomet++;
                        
                        /**
                        * Descripcion del momento del modulo
                        */
                        $courseformatoptions['editor_tabs_moment'.$editormomet.'_editor'] = array(
                            'label' => get_string('tabseditormoment', 'format_aulas_toribio',$editormomet),
                            'help' => 'tabseditormoment',
                            'help_component' => 'format_aulas_toribio',
                            'element_type' => 'editor',
                            'element_attributes' => array(
                                null,
                                array(
                                    'trusttext' => true,
                                    'subdirs' => false,
                                    'maxfiles' => 0,
                                    'maxbytes' => 0,
                                    'context' => $context,
                                    'enable_filemanagement' => false
                                ),
                                
                            ),
                        );
                        $optionsfile =array(
                            'trusttext' => true,
                            'subdirs' => false,
                            'maxfiles' => 0,
                            'maxbytes' => 0,
                            'context' => $context,
                            'enable_filemanagement' => false
                        );
                        if($this->courseid != null){
                            $context = context_course::instance($this->courseid);
                            $name = 'editor_tabs_moment'.$editormomet.'_editor';
                            $draftid_editor_file = file_get_submitted_draft_itemid('editor_tabs_moment'.$editormomet.'_editor');
                            
                            $currenttext  = file_prepare_draft_area($draftid_editor_file, $context->id, 'format_aulas_valle', 'editortabsmoment', $editormomet, $optionsfile);
                            
                            //get the id of all section
                            $idfile = $DB->get_record_sql("SELECT id FROM mdl_course_format_options  where name='$name' and courseid=$course->id");
                            $idelementfile = (int)$idfile->id;
                            $newvalueid = new stdClass();
                            $newvalueid->id = $idelementfile;
                            $newvalueid->value =  $draftid_editor_file;
                            $DB->update_record('course_format_options', $newvalueid);
                        }
                    }

                    
                
            }
            
            
            // for ($i = 1; $i <= 4; $i++) {
            //     $divisortext = get_config('format_aulas_toribio', 'moduletitle'.$i);
            //     $divisortextvideo = get_config('format_aulas_toribio', 'modulevideo'.$i);
            //     $divisortextobj = get_config('format_aulas_toribio', 'moduleobjetive'.$i);
            //     $divisorname = get_config('format_aulas_toribio', 'modulename'.$i);
            //     if (!$divisortext) {
            //         $divisortext = '';
            //         $divisortextrule = '';
            //         $divisorname = '';
            //         $divisortextvideo = '';
            //         $divisortextobj = '';
            //     }
            //     // Titulo del modulo
            //     $courseformatoptions['moduletitle'.$i] = array(
            //         'default' => $divisortext,
            //         'type' => PARAM_TEXT,
            //     );
            //     // Nombre del modulo
            //     $courseformatoptions['modulename'.$i] = array(
            //         'default' => $divisorname,
            //         'type' => PARAM_TEXT,                    
            //     );
                
            // }
        }

        if ($foreditform && !isset($courseformatoptions['coursedisplay']['label'])) {
            $courseconfig = get_config('moodlecourse');
            $max = $courseconfig->maxsections;
            if (!isset($max) || !is_numeric($max)) {
                $max = 4;
            }
            $max = 4;
            $sectionmenu = array();
            for ($i = 0; $i <= $max; $i++) {
                $sectionmenu[$i] = "$i";
            }
            $courseformatoptionsedit['numsections'] = array(
                'label' => get_string('numsections', 'format_aulas_toribio'),
                'element_type' => 'select',
                'element_attributes' => array($sectionmenu),
            );
            $courseformatoptionsedit['hiddensections'] = array(
                'label' => new lang_string('hiddensections'),
                'help' => 'hiddensections',
                'help_component' => 'moodle',
                'element_type' => 'select',
                'element_attributes' => array(
                    array(
                        0 => new lang_string('hiddensectionscollapsed'),
                        1 => new lang_string('hiddensectionsinvisible')
                    )
                ),
            );
            
            $courseformatoptionsedit['showdefaultsectionname'] = array(
                'label' => get_string('showdefaultsectionname', 'format_aulas_toribio'),
                'help' => 'showdefaultsectionname',
                'help_component' => 'format_aulas_toribio',
                'element_type' => 'select',
                'element_attributes' => array(
                    array(
                        1 => get_string('yes', 'format_aulas_toribio'),
                        0 => get_string('no', 'format_aulas_toribio'),
                    ),
                ),
            );
            $courseformatoptionsedit['Modulevideohome'] = array(
                'label' => get_string('Modulevideohome', 'format_aulas_toribio'),
                'help' => 'Modulevideohome',
                'help_component' => 'format_aulas_toribio',
                'element_type' => 'text',
            );

            // $courseformatoptionsedit['MainObjective'] = array(
            //     'label' => get_string('MainObjective', 'format_aulas_toribio'),
            //     'help' => 'MainObjective',
            //     'help_component' => 'format_aulas_toribio',
            //     'element_type' => 'text',
            // );

            for ($i = 1; $i <= 3; $i++) {
                // titulo del modulo
                $courseformatoptionsedit['tabsTitle'.$i] = array(
                    'label' => get_string('tabsTitle', 'format_aulas_toribio', $i),
                    'help' => 'tabsTitle',
                    'help_component' => 'format_aulas_toribio',
                    'element_type' => 'text',
                                       
                );
                
                /**
                 * Video de los modulos
                 */
                $courseformatoptionsedit['tabsVideo'.$i] = array(
                    'label' => get_string('tabsVideo', 'format_aulas_toribio',$i),
                    'help' => 'tabsVideo',
                    'help_component' => 'format_aulas_toribio',
                    'element_type' => 'text',
                );

                
                
                // Descripcion del modulo
                // $courseformatoptionsedit['modulename'.$i] = array(
                //     'label' => get_string('modulename', 'format_aulas_toribio', $i),
                //     'help' => 'modulename',
                //     'help_component' => 'format_aulas_toribio',
                //     'element_type' => 'text',
                // ); 
               
                // $courseformatoptionsedit['modulevideo'.$i] = array(
                //     'label' => get_string('modulevideo', 'format_aulas_toribio', $i),
                //     'help' => 'modulevideo',
                //     'help_component' => 'format_aulas_toribio',
                //     'element_type' => 'text',
                // );
                // $courseformatoptionsedit['moduleobjetive'.$i] = array(
                //     'label' => get_string('moduleobjetive', 'format_aulas_toribio', $i),
                //     'help' => 'moduleobjetive',
                //     'help_component' => 'format_aulas_toribio',
                //     'element_type' => 'text',
                // );
            }
            
            $courseformatoptions = array_merge_recursive($courseformatoptions, $courseformatoptionsedit);
        }
        
        return $courseformatoptions;
        
    }
    /**
     * update_course_format_options
     *
     * @param stdclass|array $data
     * @param stdClass $oldcourse
     * @return bool
     */
    public function update_course_format_options($data, $oldcourse = null){
        global $DB;
        $data = (array)$data;
        $context = context_course::instance($this->courseid);
        /** prepare files to save
         * cuando se actualiza el formato de curso las imagenes se guardan
         */
        /**
         * Imagen del banner principal
         */
        $tabsEquival = 3424;
        $draftitemid = file_get_submitted_draft_itemid('filepicker_tab_home_filemanager');

        // ... store or update $entry
        $filesoptions = array(
            'subdirs' => 0,
            'maxbytes' => 2000000,
            'maxfiles' => 1,
            'accepted_types' => array(
                '.jpg',
                '.png',
                '.jpeg',
                '.svg',
                '.gif',
            ),
        );
                    
        $entry = file_save_draft_area_files($draftitemid, $context->id, 'format_aulas_toribio', 'images', $tabsEquival, $filesoptions);
        //fin de la imagen principal

        /**
         * editor del home
         */

        $draftitemideditor = file_get_submitted_draft_itemid('editor_tabs_home_editor');

        // ... store or update $entry
        $filesoptionseditor =array( 
            'trusttext' => 0,
            'subdirs' => 0,
            'maxfiles' => 2,
            'maxbytes' => 2000000,
            'context' => $context->id,
            'return_types' => 15,
            'enable_filemanagement' => true,
            'noclean' => 1,
            'changeformat' => 0,
        );
                    
        $entryeditor = file_save_draft_area_files($draftitemideditor, $context->id, 'format_aulas_toribio', 'editortabs',1, $filesoptionseditor);

        /**
        * 
        * imagen de la metafora
        */
        
        $tabsEquivalmetaf = 13520;
        $draftitemid = file_get_submitted_draft_itemid('filepicker_metaf_home_filemanager');
    
        // ... store or update $entry
        $filesoptions =array(
            'subdirs' => 0,
            'maxbytes' => 2000000,
            'maxfiles' => 1,
            'accepted_types' => array(
                '.jpg',
                '.png',
                '.jpeg',
                '.svg',
                '.gif',
            ),
        );
                        
        $entry = file_save_draft_area_files($draftitemid, $context->id, 'format_aulas_toribio', 'images', $tabsEquivalmetaf, $filesoptions);

        //popups de la metafora
        /**
        * cada numero es una letra que representa la letra
        * m=13
        * e=5
        * t=20
        * a=1
        */
        for ($i=1; $i <=3; $i++) { 
            $tabsEquivalmetaf = 135201;
            $draftitemid = file_get_submitted_draft_itemid('filepicker_metaf_pop'.$i.'_filemanager');

            // ... store or update $entry
            $filesoptions =array(
                'subdirs' => 0,
                'maxbytes' => 2000000,
                'maxfiles' => 1,
                'accepted_types' => array(
                    '.jpg',
                    '.png',
                    '.jpeg',
                    '.svg',
                    '.gif',
                ),
            );
                        
            $entry = file_save_draft_area_files($draftitemid, $context->id, 'format_aulas_toribio', 'images', $tabsEquivalmetaf += $i, $filesoptions);
        }

        //fin de los popups

        /**
         * Imagenes de los modulos
         */
        // $tabsEquivalguides = 72194;
        for($i=1; $i <=3; $i++){
            $tabsEquivalguides = 72194;
            $draftitemid = file_get_submitted_draft_itemid('filepicker_tabs'.$i.'_filemanager');

            // ... store or update $entry
            $filesoptions =array('subdirs' => 0,'maxbytes' => 2000000,'maxfiles' => 1,
                'accepted_types' => array(
                            '.jpg',
                            '.png',
                            '.jpeg',
                            '.svg',
                            '.gif',
                        ),
                );
                        
            $entry = file_save_draft_area_files($draftitemid, $context->id, 'format_aulas_toribio', 'images', $tabsEquivalguides += $i, $filesoptions);

            $tabsEquivalguidesmodule = 7219413;
            $draftitemid = file_get_submitted_draft_itemid('filepicker_tabs_module'.$i.'_filemanager');

            // ... store or update $entry
            $filesoptions =array('subdirs' => 0,'maxbytes' => 2000000,'maxfiles' => 1,
                'accepted_types' => array(
                            '.jpg',
                            '.png',
                            '.jpeg',
                            '.svg',
                            '.gif',
                        ),
                );
                        
            $entry = file_save_draft_area_files($draftitemid, $context->id, 'format_aulas_toribio', 'images', $tabsEquivalguidesmodule += $i, $filesoptions);

            /**
             * Descripcion de la guia
             */

            $draftitemideditor = file_get_submitted_draft_itemid('editor_tabs'.$i.'_editor');

            // ... store or update $entry
            $filesoptionseditor =array( 
                'trusttext' => true,
                'subdirs' => false,
                'maxfiles' => 0,
                'maxbytes' => 0,
                'context' => $context,
                'enable_filemanagement' => false
            );
                        
            $entryeditor = file_save_draft_area_files($draftitemideditor, $context->id, 'format_aulas_toribio', 'editortabs',$i, $filesoptionseditor);

            
        }
        for ($j=1; $j <=15 ; $j++) { 
            /**
             * Descripción del momento
             */
            $draftitemideditor = file_get_submitted_draft_itemid('editor_tabs_moment'.$j.'_editor');

            // ... store or update $entry
            $filesoptionseditor =array( 
                'trusttext' => true,
                'subdirs' => false,
                'maxfiles' => 0,
                'maxbytes' => 0,
                'context' => $context,
                'enable_filemanagement' => false
            );
                        
            $entryeditor = file_save_draft_area_files($draftitemideditor, $context->id, 'format_aulas_toribio', 'editortabsmoment',$j, $filesoptionseditor);
        }

        if ($oldcourse !== null) {
            $oldcourse = (array)$oldcourse;
            $options = $this->course_format_options();
            foreach ($options as $key => $unused) {
                if (!array_key_exists($key, $data)) {
                    if (array_key_exists($key, $oldcourse)) {
                        $data[$key] = $oldcourse[$key];
                    } else if ($key === 'numsections') {
                        $maxsection = $DB->get_field_sql('SELECT max(section) from {course_sections} WHERE course = ?', array($this->courseid));
                        if ($maxsection) {
                            $data['numsections'] = $maxsection;
                        }
                    }
                }
            }
        }
        $changed = $this->update_format_options($data);
        if ($changed && array_key_exists('numsections', $data)) {
            $numsections = (int)$data['numsections'];
            $maxsection = $DB->get_field_sql('SELECT max(section) from {course_sections} WHERE course = ?', array($this->courseid));
            for ($sectionnum = $maxsection; $sectionnum > $numsections; $sectionnum--) {
                if (!$this->delete_section($sectionnum, false)) {
                    break;
                }
            }
        }
       
        return $changed;
    }

    /**
     * get_view_url
     *
     * @param int|stdclass $section
     * @param array $options
     * @return null|moodle_url
     */
    public function get_view_url($section, $options = array()){
        global $CFG;
        $course = $this->get_course();
        $url = new moodle_url('/course/view.php', array('id' => $course->id));

        $sr = null;
        if (array_key_exists('sr', $options)) {
            $sr = $options['sr'];
        }
        if (is_object($section)) {
            $sectionno = $section->section;
        } else {
            $sectionno = $section;
        }
        if ($sectionno !== null) {
            if ($sr !== null) {
                if ($sr) {
                    $usercoursedisplay = COURSE_DISPLAY_MULTIPAGE;
                    $sectionno = $sr;
                } else {
                    $usercoursedisplay = COURSE_DISPLAY_SINGLEPAGE;
                }
            } else {
                // $usercoursedisplay = 0;
                $usercoursedisplay = $course->coursedisplay ?? COURSE_DISPLAY_SINGLEPAGE;
            }
            if ($sectionno != 0 && $usercoursedisplay == COURSE_DISPLAY_MULTIPAGE) {
                $url->param('section', $sectionno);
            } else {
                if (empty($CFG->linkcoursesections) && !empty($options['navigation'])) {
                    return null;
                }
                $url->set_anchor('section-'.$sectionno);
            }
        }
        return $url;
    }

}

/**
 * Implements callback inplace_editable() allowing to edit values in-place
 *
 * @param string $itemtype
 * @param int $itemid
 * @param mixed $newvalue
 * @return \core\output\inplace_editable
 */
function format_aulas_toribio_inplace_editable($itemtype, $itemid, $newvalue){
    
    global $DB, $CFG;
    require_once($CFG->dirroot . '/course/lib.php');
    if ($itemtype === 'sectionname' || $itemtype === 'sectionnamenl') {
        $section = $DB->get_record_sql(
            'SELECT s.* FROM {course_sections} s JOIN {course} c ON s.course = c.id WHERE s.id = ? AND c.format = ?',
            array($itemid, 'aulas_toribio'),
            MUST_EXIST
        );
        return course_get_format($section->course)->inplace_editable_update_section_name($section, $itemtype, $newvalue);
    }
}
/**---------------------------------------------- */
/**
 * return url image for display
 */
  /** get items by the itemid field in the mdl_files db table
   * @param $itemid an itemid defined in the mdl_files table 
   * @return array an array of file objects to play with
   */
// function get_files_by_itemid($itemid) {
//         global $DB;
//         $fs = get_file_storage();
//         // Preallocate the result array
//         $result = array();
//         // Conditions for teh database call
//     if ($itemid !== false) {
//         $conditions['itemid'] = $itemid;
//     }
//         $sort = "sortorder, itemid, filepath, filename";
//         // Get the file records from the database 
//         $file_records = $DB->get_records('files', $conditions, $sort);
//         // Loop through
//     foreach ($file_records as $file_record) {
//         // If there's content- for some reason there is stacks of these rows with nothing in them
//         if ($file_record->filename === '.') {
//             continue;
//         }
//         $result[$file_record->pathnamehash] = new stored_file($fs, $file_record);
//     } 
//         return $result;
// } 

/*
 * Serve the files from the MYPLUGIN file areas
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function format_aulas_toribio_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    // Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
    
    if ($context->contextlevel != CONTEXT_COURSE) {
        print_error('context');
    }
 
    // Make sure the filearea is one of those used by the plugin.
    if ($filearea !== 'images' && $filearea !== 'editortabs' && $filearea !== 'editormodules' && $filearea !== 'audios') {
        print_error('filearea');
    }
 
    // Make sure the user is logged in and has access to the module (plugins that are not course modules should leave out the 'cm' part).
    require_login($course, true, $cm);
 
 
    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    $itemid = array_shift($args); // The first item in the $args array.
 
    // Use the itemid to retrieve any relevant data records and perform any security checks to see if the
    // user really does have access to the file in question.
 
    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/'; // $args is empty => the path is '/'
    } else {
        $filepath = '/'.implode('/', $args).'/'; // $args contains elements of the filepath
    }
 
    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'format_aulas_toribio', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
        print_error('file does exist');

    }
 
    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering. 
    send_stored_file($file, 0, 0, $forcedownload, $options);
}
/**
 * get the id of all section in the course
 *  $courseid  the course id
 */

 function progress_percentage_aulas_toribio($courseid) {
    global $DB;
    $activities = array();
    //get the id of all section
    $sections = $DB->get_records_sql('SELECT id as section FROM mdl_course_sections  where course='.$courseid.'');
    
    
    foreach($sections as $a){
        
        $activities[] = $a;
        
    }
   
    return $activities;
} 
/**
 * Get all activities of a section
 * $course the id of course
 * $section the id of section
 */
function activities_aulas_toribio($course,$section,$user) {
    global $DB; 
    $completecount = 0;
    $complete_act = 0;
    //get the count of the active activities of the section
    $sections = $DB->get_records_sql('SELECT count(module) as c from mdl_course_modules where course='.$course.' and section='.$section.' and deletioninprogress = 0 and completion != 0');
    //get the count of the complete activities of the section
    $complete = $DB->get_records_sql('SELECT count(module) as module  FROM mdl_course_modules_completion as complete JOIN mdl_course_modules as module WHERE module.id = complete.coursemoduleid and course='.$course.' and section='.$section.' and completionstate = 1 and complete.userid='.$user.'');
    
    foreach($sections as $a){
        $completecount = $a->c;
    }
    foreach($complete as $a){
        $complete_act=$a->module;
    }
    if ($completecount == 0) {
        $total = "No hay actividades";
    }
    //obtain the module progress percent
    else {
    $total = round(($complete_act * 100) / $completecount);
    }
    return $total;

}