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
 * Short answer question definition class.
 *
 * @package    qtype
 * @subpackage shortanswer
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Represents a short answer question.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_type_calc_sheet_question extends question_graded_by_strategy
        implements question_response_answer_comparer {
    /** @var boolean whether answers should be graded case-sensitively. */
    public $usecase;
    public $solovalor;

    /** @var array of question_answer. */
    public $answers = array();

       public function __construct() {
        parent::__construct(new question_many_matching_answers_strategy($this));
        //var_dump($this->gradingstrategy);
    }

    public function get_expected_data() {
        return array('answer' => PARAM_RAW_TRIMMED);
    }

    public function summarise_response(array $response) {
        if (isset($response['answer'])) {
            return $response['answer'];
        } else {
            return null;
        }
    }
  public function get_matching_answer(array $response) {
        return $this->gradingstrategy->grade($response);
    }

    public function is_complete_response(array $response) {
        return array_key_exists('answer', $response) &&
                ($response['answer'] || $response['answer'] === '0');
    }

    public function get_validation_error(array $response) {
        if ($this->is_gradable_response($response)) {
            return '';
        }
        return get_string('pleaseenterananswer', 'qtype_type_calc_sheet');
    }

    public function is_same_response(array $prevresponse, array $newresponse) {
        return question_utils::arrays_same_at_key_missing_is_blank(
                $prevresponse, $newresponse, 'answer');
    }

    public function get_answers() {
        return $this->answers;
    }

    public function compare_response_with_answer(array $response, question_answer $answer) {
        if (!array_key_exists('answer', $response) || is_null($response['answer'])) {
            return false;
        }

        return self::compare_string_with_wildcard(
                $response['answer'], $answer->answer,$this->solovalor);
    }

    public static function compare_string_with_wildcard($string, $pattern, $solovalor) {

        // Normalise any non-canonical UTF-8 characters before we start.
        $pattern = self::safe_normalize($pattern);
        $string = self::safe_normalize($string);


        $arr_json = json_decode($pattern);
        $arr_json2 = json_decode($string);
        //var_dump($arr_json[0]->rows);
        $enable_form=$solovalor;
        $count_compare=0;
        $form=0;
        $value=0;
        $arr=array();
        foreach($arr_json as $indhoja=>$hoja){

            foreach ($hoja->rows as $indiceF => $fila) {
                
                foreach ($fila->columns as $col => $columna) {
                
                    // echo "$indiceF ,";
                    // echo "$col ";
                    $vars=get_object_vars($columna);

                    if(empty($vars)){
                        // echo "celda vacia";
                    }else{
                        $arr[$count_compare]['Hoj']=$indhoja;
                        $arr[$count_compare]['Fil']=$indiceF;
                        $arr[$count_compare]['Col']=$col;
                        if(isset($columna->value)){                     
                            $arr[$count_compare]['Val']=$columna->value;
                            $value++;
                        }
                        if(isset($columna->formula)){
                            $arr[$count_compare]['Form']= $columna->formula;
                            $form++;
                        }
                        $count_compare++;
                    }
                    // echo "<br>";

                }
            // echo "<br>";
            }
        //var_dump($arr);
        }
        $aciValor=0;
        $aciFormulas=0;
        $conv=new Convertir();
 

        if(!empty($arr)){
        foreach($arr as $id => $cell){
        //var_dump($cell);
            
                        //$filafix=$cell['Fil']+1;
                        //$colfix=num_to_letter($cell['Col']);

                        if($enable_form==1){
//                            echo "entro a enable form uno<br>";

                                                //echo $arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->value." ==".$cell['Val']."?<br>"	;
                                if(isset($arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->value)){  //existe valor en la celda a evaluar?
                                        
                                        if($arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->value==$cell['Val']){ //el valor de las celdas es igual?

                                                if(isset($arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->formula)){
                                                       // echo $arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->formula." ==".$cell['Form']."?<br>"	;
                                                       
                                                        if(strcasecmp(trim($arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->formula),trim($cell['Form']))==0){
                                                                //ECHO "SON IGUALES<br>";
                                                                $aciValor++;
                                                                $aciFormulas++;		
                                                        }
                                                }else{
                                                                $aciValor++;
                                                }

                                        }
                                }
                        }else if($enable_form==2){
                                echo "entro a enable form dos";

                        if(isset($arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->formula)){
                            
                            $string=$arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->formula;
                            if($conv->convertirSumasConsecutivas($string)){
                                $string = preg_replace("/([A-Za-z]{1}[0-9]+[\+,]{1})+([A-Za-z]{1}[0-9]){1}/", $conv->convertirSumasConsecutivas($string), $string);
                            }
                            $pattern=$cell['Form'];
                            if($conv->convertirSumasConsecutivas($pattern)){
                                $pattern = preg_replace("/([A-Za-z]{1}[0-9]+[\+,]{1})+([A-Za-z]{1}[0-9]){1}/", $conv->convertirSumasConsecutivas($pattern), $pattern);
                            }
                                echo "String ".$string."Pattern ".$pattern;
//                                echo $arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->formula." ==".$cell['Form']."?<br>"	;
//                                echo $conv->convertirSumasConsecutivas($arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->formula)."<br />";
//                                echo $cell['Form']."<br />";

                        if(strcasecmp(trim($string),trim($pattern))==0){

                            $aciFormulas++;        
                        }
                    }
                }else{
                                        //echo $arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->value." ==".$cell['Val']."?<br>"	;
                                if(isset($arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->value)){  //existe valor en la celda a evaluar?	
                                        if($arr_json2[$cell['Hoj']]->rows[$cell['Fil']]->columns[$cell['Col']]->value==$cell['Val']){ //el valor de las celdas es igual?

                                                $aciValor++;		
                                        }
                                }
                        }
        }
//                echo "Valor ".$value." ";
//        echo $aciValor."<br>";
//                echo "Formula ".$form." ";
//        echo $aciFormulas."<br>";
                if($enable_form==0&&$aciValor>=$value&&count($arr)>0){
                    return true;
        }else if($enable_form==1&&$aciValor>=$value&&$aciFormulas>=$form&&count($arr)>0){
                    return true;

        }else if($enable_form==2&&$aciFormulas>=$form&&count($arr)>0){
                    return true;

        }else{

                    return false;

        }
        }else{

                    return false;
    }
    }

    /**
     * Normalise a UTf-8 string to FORM_C, avoiding the pitfalls in PHP's
     * normalizer_normalize function.
     * @param string $string the input string.
     * @return string the normalised string.
     */
    protected static function safe_normalize($string) {
        if (!$string) {
            return '';
        }

        if (!function_exists('normalizer_normalize')) {
            return $string;
        }

        $normalised = normalizer_normalize($string, Normalizer::FORM_C);
        if (!$normalised) {
            // An error occurred in normalizer_normalize, but we have no idea what.
            debugging('Failed to normalise string: ' . $string, DEBUG_DEVELOPER);
            return $string; // Return the original string, since it is the best we have.
        }

        return $normalised;
    }

    public function get_correct_response() {
        $response = parent::get_correct_response();
        if ($response) {
            $response['answer'] = $this->clean_response($response['answer']);
        }
        return $response;
    }

    public function clean_response($answer) {
        // Break the string on non-escaped asterisks.
        $bits = preg_split('/(?<!\\\\)\*/', $answer);

        // Unescape *s in the bits.
        $cleanbits = array();
        foreach ($bits as $bit) {
            $cleanbits[] = str_replace('\*', '*', $bit);
        }

        // Put it back together with spaces to look nice.
        return trim(implode(' ', $cleanbits));
    }

    public function check_file_access($qa, $options, $component, $filearea,
            $args, $forcedownload) {
        if ($component == 'question' && $filearea == 'answerfeedback') {
            $currentanswer = $qa->get_last_qt_var('answer');
            $answer = $this->get_matching_answer(array('answer' => $currentanswer));
            $answerid = reset($args); // itemid is answer id.
            return $options->feedback && $answer && $answerid == $answer->id;

        } else if ($component == 'question' && $filearea == 'hint') {
            return $this->check_hint_file_access($qa, $options, $args);

        } else {
            return parent::check_file_access($qa, $options, $component, $filearea,
                    $args, $forcedownload);
        }
    }
}
class question_many_matching_answers_strategy implements question_grading_strategy {
    /**
     * @var question_response_answer_comparer (presumably also a
     * {@link question_definition}) the question we are doing the grading for.
     */
    public $question;

    /**
     * @param question_response_answer_comparer $question (presumably also a
     * {@link question_definition}) the question we are doing the grading for.
     */
    public function __construct(question_response_answer_comparer $question) {
        $this->question = $question;
        
    }
public function grade(array $response) {
        $total=0;
        $parcial=0;
        
        //var_dump($this->gradingstrategy->question->answers);
        foreach ($this->question->answers as $aid => $answer) {
            
            $total+= $answer->fraction;
           // var_dump($this->question->compare_response_with_answer($response, $answer));
            if ($this->question->compare_response_with_answer($response, $answer)) {
                
                $parcial+= $answer->fraction;
            }
        }
        
        if($parcial!=0){
            $answer->parcial= $parcial;
            $answer->total= $total;
            $answer->fraction =$parcial/$total;
               return $answer;
        }else{
               return null;
        }
    }


    public function get_correct_answer() {
        foreach ($this->question->get_answers() as $answer) {
            $state = question_state::graded_state_for_fraction($answer->fraction);
            if ($state == question_state::$gradedright) {
                return $answer;
            }
        }
        return null;
    }
}
class Convertir{
	private $regexLetras="/[a-z]{1}/";
	private $regexNumeros="/[0-9]+/";
	private $regexSumas="/[a-z]{1}[0-9]+/";

	function convertirSumasConsecutivas($string){
		$regexSumasConsecutivas="/(sum\()?([A-Za-z]{1}[0-9]+[\+,]{1})+([A-Za-z]{1}[0-9]){1}(\))?/";

		if(preg_match($regexSumasConsecutivas,$string)){
			return $this->cumpleParaConvertir($string);
		}else{
			return false;
		}
	}


	public function cumpleParaConvertir($string){
		$letra="";
		$numero="";
		$patron=2;//sera 0 para letras y uno para numeros 
		$resultado=false;
		$sumas=array();
		$cumple="";
		$i=0;

		preg_match_all($this->regexSumas,strtolower($string),$sumas);

		$sumas=$sumas[0];
		sort($sumas,SORT_STRING);

		foreach ($sumas as $suma) {
			$suma=strtolower($suma);

			if($i==0){
				preg_match($this->regexLetras, $suma, $coincidencia);
				$letra=$coincidencia[0];
				preg_match($this->regexNumeros, $suma, $coincidencia);
				$numero=$coincidencia[0];
			}elseif($i==1){
				preg_match($this->regexLetras, $suma, $coincidencia);
				if($letra==$coincidencia[0]){
					$patron=0;
				}
				preg_match($this->regexNumeros, $suma, $coincidencia);
				if($numero==$coincidencia[0]){
					$patron=1;
				}
				$cumple=$this->cumplePatronConvertir($numero,$letra,$patron,$suma,$i);
				if(!$cumple)
					return false;
			}else{
				$cumple=$this->cumplePatronConvertir($numero,$letra,$patron,$suma,$i);
				if(!$cumple)
					return false;
			}
			$i++;
		}
		return "{$sumas[0]}:$cumple";
	}

	public function cumplePatronConvertir($numero,$letra,$patron,$suma,$vuelta){
		switch($patron){
			//Letras es el patron
			case 0:
				preg_match($this->regexLetras, $suma, $coincidencia);
				if($letra==$coincidencia[0]){
					preg_match($this->regexNumeros, $suma, $coincidencia);
					if(intval ($numero)+$vuelta==intval ($coincidencia[0])){
						return $suma;
					}else{
						return false;
					}
				}else{
					return false;
				}
				break;
			//Numeros son el patron
			case 1:
				preg_match($this->regexNumeros, $suma, $coincidencia);
				if($numero==$coincidencia[0]){
					preg_match($this->regexLetras, $suma, $coincidencia);
					if(ord ($letra)+$vuelta==ord ($coincidencia[0])){
						return $suma;
					}else{
						return false;
					}
				}else{
					return false;
				}
				break;
			default:
				return false;
		}
		return false;
	}
}