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
 * Defines the editing form for the type_calc_sheet question type.
 *
 * @package    qtype
 * @subpackage type_calc_sheet
 * @copyright  2007 Jamie Pratt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Short answer question editing form definition.
 *
 * @copyright  2007 Jamie Pratt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_type_calc_sheet_edit_form extends question_edit_form {

    protected function definition_inner($mform) {
        $mform->addElement('html', "
            <style>
                #id_usecase{
                    display:none;
                }
            </style>
        ");
        //var_dump($this);
        $menu = array(
            get_string('soloyes', 'qtype_type_calc_sheet'),
            get_string('solono', 'qtype_type_calc_sheet'),
            get_string('soloform', 'qtype_type_calc_sheet')
        );
        $mform->addElement('select', 'solovalor',
                get_string('solovalor', 'qtype_type_calc_sheet'), $menu);
        $mform->addElement('text', 'usecase',
                get_string('prellenado', 'qtype_type_calc_sheet'));
        if(isset($this->question->options->usecase)){
	$prellenado = $this->question->options->usecase;
}
	//var_dump( $this->question);
     global $CFG;
	 if(!isset($prellenado)){
$mform->addElement('html', "</br><link rel=\"stylesheet\" type=\"text/css\" href=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-ui/theme/jquery-ui.min.css\" />
    	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-1.8.3.min.js\"></script>
    	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-ui/ui/jquery-ui.min.js\"></script>
    	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery.sheet.js\"></script>
    	<script type=\"text/javascript\">
    		
			
			$.sheet.preLoad('".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/');
			
            $(function() {
			
                $('#sheetParentPlantilla').sheet();
                $(\"form\").submit(function(){
					var jS = $(\"#sheetParentPlantilla\").getSheet();
					var json;
                  json = $.sheet.dts.fromTables.json(jS);
                  $(\"#id_usecase\").val(JSON.stringify(json));
                });
            });
        </script>
        <style>
            #id_usecase{
                width:800px;
            }
		</style>
 
        <div id='general' style='margin-top: -33px;'>
            <div id='sheetParentPlantilla' title='Hoja de prellenado' style='height: 315px; width: 666px; margin-left: 265px;'>
                <table title='Hoja 1'>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>                   
                </table>
            </div>
        </div>
    
");       }else{
$mform->addElement('html', "</br><link rel=\"stylesheet\" type=\"text/css\" href=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-ui/theme/jquery-ui.min.css\" />
    	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-1.8.3.min.js\"></script>
    	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery-ui/ui/jquery-ui.min.js\"></script>
    	<script type=\"text/javascript\" src=\"".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/jquery.sheet.js\"></script>
    	<script type=\"text/javascript\">
    			
        $.sheet.preLoad('".$CFG->wwwroot ."/question/type/type_calc_sheet/jquery.sheet/');
		
            $(function () {
                
                var json = jQuery.parseJSON('".$prellenado."');
                var tables = $.sheet.dts.toTables.json(json);

                $('#sheetParentPlantilla').html(tables).sheet();
				
				$(\"form\").submit(function(){
					var jS = $(\"#sheetParentPlantilla\").getSheet();
					var json;
                  json = $.sheet.dts.fromTables.json(jS);
                  $(\"#id_usecase\").val(JSON.stringify(json));
                });
            });
    </script>
	<style>
            #id_usecase{
                width:650px;
            }
		</style>
    
        <div id='general'>
            <div id='sheetParentPlantilla' title='Hoja de prellenado'  style='height: 315px; width: 666px; margin-left: 265px;' ></div>
        </div>
    
");

}
        
        
        $mform->addElement('static', 'answersinstruct',
                get_string('correctanswers', 'qtype_type_calc_sheet'),
                get_string('filloutoneanswer', 'qtype_type_calc_sheet'));
        $mform->closeHeaderBefore('answersinstruct'); 					//elemento que sirve para mostrar el mensaje de descripcion de respuesta

        $this->add_per_answer_fields($mform, get_string('answerno', 'qtype_type_calc_sheet', '{no}'),
                question_bank::fraction_options(),5,1);						//elemento para ingresar las respuestas 

        $this->add_interactive_settings();
    }
protected function get_per_answer_fields($mform, $label, $gradeoptions,
            &$repeatedoptions, &$answersoption) {
    //var_dump($this->context);
        $repeated = array();
        $repeated[] = $mform->createElement('header', 'answerhdr', $label);
 global $CFG;
	
        $repeated[]=$mform->createElement('html', "</br>
            <script type=\"text/javascript\">
        
                $(function() {
                    {obtenjson}
                    $('#sheetParent-{no}'){html}.sheet();
                    
                        $(\"form\").submit(function(){
                        var jS = $(\"#sheetParent-{no}\").getSheet();
                        var json;
                        json = $.sheet.dts.fromTables.json(jS);
                        $(\"#id_answer_{no}\").val(JSON.stringify(json));
                    });
                    
                    
                });
            </script>
            <style>
                #id_answer_{no}{
                    width:650px;
                }
            </style>

            <div id='general' style='margin-bottom: -22px;'>
                <div id='sheetParent-{no}' title='Hoja de Calculo' style='height: 315px; width: 666px;margin-left: 260px;'>{table}</div>
            </div>    
        "); 

        $repeated[] = $mform->createElement('textarea', 'answer',
                get_string('answer', 'question'), array('rows' => 4, 'style'=>'display:none'), $this->editoroptions);
        $repeated[] = $mform->createElement('select', 'fraction',
                get_string('valor','qtype_type_calc_sheet'), $gradeoptions);
        $repeated[] = $mform->createElement('editor', 'feedback',
                get_string('feedback', 'question'), array('rows' => 1), $this->editoroptions);
        $repeatedoptions['answer']['type'] = PARAM_RAW;
        $repeatedoptions['fraction']['default'] = 0;
        $answersoption = 'answers';
        return $repeated;
    }
    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);				//Realiza el procedimiento necesario para pasarlo a  set_data() before it is used to initialise the form.
        $question = $this->data_preprocessing_answers($question);		//Realiza el procesamiento necesario del campo add_per_answer_fields().
        $question = $this->data_preprocessing_hints($question);			//Perform the necessary preprocessing for the hint fields.



        return $question;
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);//Valida y regresa los errores de question_edit_form 
        $answers = $data['answer'];					//Extrae las respuestas del array data
        $answercount = 0; 							//Inicializa el contador de respuesta
        $maxgrade = false;							//Se lo asigna a la respuesta mas correcta jaja osea con 1
        foreach ($answers as $key => $answer) {		//question/type/type_calc_sheetxtrae todas las respuestas de answers
            $trimmedanswer = trim($answer);			//Recorta espacios al inicio y al final de la respuesta
            if ($trimmedanswer !== '') {			//Se compara la respuesta con caracter de espacio
                $answercount++;						//Se aumenta el contador de respuesta
                if ($data['fraction'][$key] == 1) {	// Compara si la respuesta serÃ¡ la de mayor puntuacion.
                    $maxgrade = true;				//Levanta la bandera de maxgrade.
                }
            } else if ($data['fraction'][$key] != 0 ||
                    !html_is_blank($data['feedback'][$key]['text'])) {//Mientras el valor de la respuesta sea mayor a 0 o el texto de retroalimentacion es cero
                $errors["answer[$key]"] = get_string('answermustbegiven', 'qtype_type_calc_sheet');//Se asigna a el error al indice de la respuesta
                $answercount++; 					//Se aumenta el contador de respuesta.
            }
        }
        if ($answercount==0) {						//Se pregunta si ya se a dado una respuesta
            $errors['answer[0]'] = get_string('notenoughanswers', 'qtype_type_calc_sheet', 1);//Se manda error de que no se han dado respuestas suficientes
        }
        if ($maxgrade == false) {					//Si no se levanto la bandera de existencia de respuesta con mayor puntuacion.
            $errors['fraction[0]'] = get_string('fractionsnomax', 'question');//Se manda error de que no existe una respuesta con puntuacion 1
        }
        return $errors;
    }
    function repeat_elements_fix_clone($i, $elementclone, &$namecloned) {
        $name = $elementclone->getName();//el nombre del elemento, ejemplo answer feedback fraction
        $namecloned[] = $name;

        if (!empty($name)) {
            $elementclone->setName($name."[$i]");
        }
        if(is_a($elementclone, 'HTML_QuickForm_html')){
            $value = $elementclone->_text;
            if(isset($this->question->options->answers)){
            $arrkey=array_keys($this->question->options->answers);
            }
            if(isset($arrkey[$i])){
                $idPregunta=$arrkey[$i];
                    //var_dump($this->question->options);
                $rellenaJson="var json = jQuery.parseJSON('".$this->question->options->answers[$idPregunta]->answer."');
                var tables = $.sheet.dts.toTables.json(json);";
                $elementclone->setValue(str_replace('{obtenjson}',$rellenaJson, $value));
                $value = $elementclone->_text;
                $elementclone->setValue(str_replace('{no}',$i, $value));
                $value = $elementclone->_text;
                $elementclone->setValue(str_replace('{table}','', $value));
                $value = $elementclone->_text;
                $elementclone->setValue(str_replace('{html}','.html(tables)', $value));
                    //var_dump($value);
            }else{
                $elementclone->setValue(str_replace('{no}',$i, $value));
                $value = $elementclone->_text;
                $table="<table title='Hoja 1'><tr><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td></tr></table>";
                $elementclone->setValue(str_replace('{table}',$table, $value));
                $value = $elementclone->_text;
                $elementclone->setValue(str_replace('{html}','', $value));
                $value = $elementclone->_text;
                $elementclone->setValue(str_replace('{obtenjson}','', $value));
                
            }
            
        }else if (is_a($elementclone, 'HTML_QuickForm_header')) {
            $value = $elementclone->_text;
            
            $elementclone->setValue(str_replace('{no}', ($i+1), $value));

        } else if (is_a($elementclone, 'HTML_QuickForm_submit') || is_a($elementclone, 'HTML_QuickForm_button')) {
            $elementclone->setValue(str_replace('{no}', ($i+1), $elementclone->getValue()));

        } else {
            $value=$elementclone->getLabel();
            $elementclone->setLabel(str_replace('{no}', ($i+1), $value));
        }
    }
    public function qtype() { 						//funcion que regresa que este tipo de pregunta es un short answer.
        return 'type_calc_sheet';
    }
}
