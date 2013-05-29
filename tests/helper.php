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
 * Test helpers for the type_calc_sheet question type.
 *
 * @package    qtype_type_calc_sheet
 * @copyright  2012 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Test helper class for the type_calc_sheet question type.
 *
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_type_calc_sheet_test_helper extends question_test_helper {
    public function get_test_questions() {
        return array('frogtoad', 'frogonly', 'escapedwildcards');
    }

    /**
     * Makes a type_calc_sheet question with correct ansewer 'frog', partially
     * correct answer 'toad' and defaultmark 1. This question also has a
     * '*' match anything answer.
     * @return qtype_type_calc_sheet_question
     */
    public function make_type_calc_sheet_question_frogtoad() {
        question_bank::load_question_definition_classes('type_calc_sheet');
        $sa = new qtype_type_calc_sheet_question();
        test_question_maker::initialise_a_question($sa);
        $sa->name = 'Short answer question';
        $sa->questiontext = 'Name an amphibian: __________';
        $sa->generalfeedback = 'Generalfeedback: frog or toad would have been OK.';
        $sa->usecase = false;
        $sa->answers = array(
            13 => new question_answer(13, 'frog', 1.0, 'Frog is a very good answer.', FORMAT_HTML),
            14 => new question_answer(14, 'toad', 0.8, 'Toad is an OK good answer.', FORMAT_HTML),
            15 => new question_answer(15, '*', 0.0, 'That is a bad answer.', FORMAT_HTML),
        );
        $sa->qtype = question_bank::get_qtype('type_calc_sheet');

        return $sa;
    }

    /**
     * Gets the question data for a type_calc_sheet question with with correct
     * ansewer 'frog', partially correct answer 'toad' and defaultmark 1.
     * This question also has a '*' match anything answer.
     * @return stdClass
     */
    public function get_type_calc_sheet_question_data_frogtoad() {
        $qdata = new stdClass();
        test_question_maker::initialise_question_data($qdata);

        $qdata->qtype = 'type_calc_sheet';
        $qdata->name = 'Short answer question';
        $qdata->questiontext = 'Name an amphibian: __________';
        $qdata->generalfeedback = 'Generalfeedback: frog or toad would have been OK.';

        $qdata->options = new stdClass();
        $qdata->options->usecase = false;
        $qdata->options->answers = array(
            13 => new question_answer(13, 'frog', 1.0, 'Frog is a very good answer.', FORMAT_HTML),
            14 => new question_answer(14, 'toad', 0.8, 'Toad is an OK good answer.', FORMAT_HTML),
            15 => new question_answer(15, '*', 0.0, 'That is a bad answer.', FORMAT_HTML),
        );

        return $qdata;
    }

    /**
     * Makes a type_calc_sheet question with just the correct ansewer 'frog', and
     * no other answer matching.
     * @return qtype_type_calc_sheet_question
     */
    public function make_type_calc_sheet_question_frogonly() {
        question_bank::load_question_definition_classes('type_calc_sheet');
        $sa = new qtype_type_calc_sheet_question();
        test_question_maker::initialise_a_question($sa);
        $sa->name = 'Short answer question';
        $sa->questiontext = 'Name the best amphibian: __________';
        $sa->generalfeedback = 'Generalfeedback: you should have said frog.';
        $sa->usecase = false;
        $sa->answers = array(
            13 => new question_answer(13, 'frog', 1.0, 'Frog is right.', FORMAT_HTML),
        );
        $sa->qtype = question_bank::get_qtype('type_calc_sheet');

        return $sa;
    }

    /**
     * Gets the question data for a type_calc_sheet questionwith just the correct
     * ansewer 'frog', and no other answer matching.
     * @return stdClass
     */
    public function get_type_calc_sheet_question_data_frogonly() {
        $qdata = new stdClass();
        test_question_maker::initialise_question_data($qdata);

        $qdata->qtype = 'type_calc_sheet';
        $qdata->name = 'Short answer question';
        $qdata->questiontext = 'Name the best amphibian: __________';
        $qdata->generalfeedback = 'Generalfeedback: you should have said frog.';

        $qdata->options = new stdClass();
        $qdata->options->usecase = false;
        $qdata->options->answers = array(
            13 => new question_answer(13, 'frog', 1.0, 'Frog is right.', FORMAT_HTML),
        );

        return $qdata;
    }

    /**
     * Makes a type_calc_sheet question with just the correct ansewer 'frog', and
     * no other answer matching.
     * @return qtype_type_calc_sheet_question
     */
    public function make_type_calc_sheet_question_escapedwildcards() {
        question_bank::load_question_definition_classes('type_calc_sheet');
        $sa = new qtype_type_calc_sheet_question();
        test_question_maker::initialise_a_question($sa);
        $sa->name = 'Question with escaped * in the answer.';
        $sa->questiontext = 'How to you write x times y in C? __________';
        $sa->generalfeedback = 'In C, this expression is written x * y.';
        $sa->usecase = false;
        $sa->answers = array(
            13 => new question_answer(13, '*x\*y*', 1.0, 'Well done.', FORMAT_HTML),
        );
        $sa->qtype = question_bank::get_qtype('type_calc_sheet');

        return $sa;
    }
}
