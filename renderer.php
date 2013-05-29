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
 * Short answer question renderer class.
 *
 * @package    qtype
 * @subpackage type_calc_sheet
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Generates the output for short answer questions.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_type_calc_sheet_renderer extends qtype_renderer {
    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {

        $question = $qa->get_question();
        //var_dump($options);
		//var_dump($question);

		//echo $prellenado;
        $currentanswer = $qa->get_last_qt_var('answer');
        //var_dump($currentanswer);
        if(empty($currentanswer)){
            $prellenado= "'".$question->usecase."'";

        }else{
            $prellenado= "'".$currentanswer."'";

        }
        $inputname = $qa->get_qt_field_name('answer');
		
		$inputnamescape= str_replace(":", "\\\:", $inputname); //input para escapar los dos puntos como el jquery exige. "q1\\:3_answer"
		
        $inputattributes = array(
            'type' => 'text',
            'name' => $inputname,
            'value' => $currentanswer,
            'id' => $inputname,
            'size' => 80,
            'style' => 'display:none',
        );

        if ($options->readonly) {
            $inputattributes['readonly'] = 'readonly';
        }

        $feedbackimg = '';
        if ($options->correctness) {
            $answer = $question->get_matching_answer(array('answer' => $currentanswer));
            if ($answer) {
                $fraction = $answer->fraction;
            } else {
                $fraction = 0;
            }
            $inputattributes['class'] = $this->feedback_class($fraction);
            $feedbackimg = $this->feedback_image($fraction);
        }

        $questiontext = $question->format_questiontext($qa);
        $placeholder = false;
        if (preg_match('/_____+/', $questiontext, $matches)) {
            $placeholder = $matches[0];
            $inputattributes['size'] = round(strlen($placeholder) * 1.1);
        }
        $input = html_writer::empty_tag('input', $inputattributes) . $feedbackimg;
		
		
		global $CFG;
		if(!isset($prellenado)){
        $HojaCalc="
        <link rel=\"stylesheet\" type=\"text/css\" href=\"".$CFG->wwwroot ."/lib/jquery.sheet/jquery-ui/theme/jquery-ui.min.css\" />
    	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/lib/jquery.sheet/jquery-1.8.3.min.js\"></script>
    	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/lib/jquery.sheet/jquery-ui/ui/jquery-ui.min.js\"></script>
    	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/lib/jquery.sheet/jquery.sheet.js\"></script>

		<script type=\"text/javascript\">
    		
			
			$.sheet.preLoad('".$CFG->wwwroot ."/lib/jquery.sheet/');
			
            $(function() {
				$('#sheetParent').sheet();
                $(\"#sheetParent\").mouseleave(function(){
					var jS = $(\"#sheetParent\").getSheet();
					var json;
                  json = $.sheet.dts.fromTables.json(jS);
                  $(\"#$inputnamescape\").val(JSON.stringify(json));
                });
				
            });
        </script> 
		<div id=\"sheetParent\" title=\"Hoja de Calculo\">
            <table title='Hoja 1'>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
        </div>
		";
		
		}else{
            if ($options->readonly) {
			 $HojaCalc="
                <link rel=\"stylesheet\" type=\"text/css\" href=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-ui/theme/jquery-ui.min.css\" />
            	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-1.8.3.min.js\"></script>
            	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-ui/ui/jquery-ui.min.js\"></script>
            	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery.sheet.js\"></script>
        		<script type=\"text/javascript\">
            		
        			
        			$.sheet.preLoad('".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/');
        			
                    $(function() {
        				 var json = jQuery.parseJSON($prellenado);
                                var tables = $.sheet.dts.toTables.json(json);

                                $('#sheetParent')
                                    .html(tables)
                                    .sheet();
                                $('#sheetParent').getSheet()
                                .toggleState();

                                
                        $(\"form\").submit(function(){
        					var jS = $(\"#sheetParent\").getSheet();
        					var json;
                          json = $.sheet.dts.fromTables.json(jS);
                          $(\"#$inputnamescape\").val(JSON.stringify(json));
                        });		
        				
                    });
                </script> 
        		<div id=\"sheetParent\" title=\"Hoja de Calculo\">
                    
                </div>
        		";
            }else{
                $HojaCalc="
                <link rel=\"stylesheet\" type=\"text/css\" href=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-ui/theme/jquery-ui.min.css\" />
                <script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-1.8.3.min.js\"></script>
                <script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-ui/ui/jquery-ui.min.js\"></script>
                <script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery.sheet.js\"></script>
                <script type=\"text/javascript\">
                    
                    
                    $.sheet.preLoad('".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/');
                    
                    $(function() {
                         var json = jQuery.parseJSON($prellenado);
                                var tables = $.sheet.dts.toTables.json(json);

                                $('#sheetParent')
                                    .html(tables)
                                    .sheet();
                                
                        $(\"form\").submit(function(){
                            var jS = $(\"#sheetParent\").getSheet();
                            var json;
                          json = $.sheet.dts.fromTables.json(jS);
                          $(\"#$inputnamescape\").val(JSON.stringify(json));
                        });     
                        
                    });
                </script> 
                <div id=\"sheetParent\" title=\"Hoja de Calculo\">
                    
                </div>
                ";
            }
		}
        if ($placeholder) {
            $inputinplace = html_writer::tag('label', get_string('answer'),
                    array('for' => $inputattributes['id'], 'class' => 'accesshide'));
            $inputinplace .= $input;
            $questiontext = substr_replace($questiontext, $inputinplace,
                    strpos($questiontext, $placeholder), strlen($placeholder));
        }

        $result = html_writer::tag('div', $questiontext, array('class' => 'qtext'));

        if (!$placeholder) {
            $result .= html_writer::start_tag('div', array('class' => 'ablock'));
            $result .= html_writer::tag('label', get_string('answer', 'qtype_type_calc_sheet',
                    html_writer::tag('span', $input, array('class' => 'answer'))),
                    array('for' => $inputattributes['id']));
            $result .= html_writer::end_tag('div');
        }

        if ($qa->get_state() == question_state::$invalid) {
            $result .= html_writer::nonempty_tag('div',
                    $question->get_validation_error(array('answer' => $currentanswer)),
                    array('class' => 'validationerror'));
        }

        return $result.$HojaCalc;
    }




}
