<?php

/**
 * embedded class question-answer, store question id and ansswer filled
 * @author nmalservet
 *
 */
class AnswerQuestion extends EMongoEmbeddedDocument {

    public $id;
    public $label;
    public $label_fr;
    /**
     * type of the question. Values authorized :
     * input, date , radio, checkbox, text, image
     * @var type
     */
    public $type;
    /*
     * css style applied to the label.
     */
    public $style;

    /**
     * values if question type is radio
     */
    public $values;

    /**
     * values if question type is radio and french setted
     */
    public $values_fr;

    /**
     * value of the answer
     * @var type
     */
    public $answer;

    /**
     * columns if type is array
     * @var type
     */
    //public $columns;

    /**
     * rows if type is array
     * @var type
     */
    //public $rows;

    /**
     * comment on the top of the question
     * @var type
     */
    public $precomment;

    /**
     * comment on the top of the question
     * @var type
     */
    public $precomment_fr;
    
    public $fiche_id;


    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array(
                'label, answer',
                'required'
        ));
    }

    /**
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'label' => 'question',
            'answer' => 'answer',
        );
    }
    
    public function attributeExportedSqlLabels()
    {
        return array(
            'id' => 'id',
            'label_fr' => 'label_fr',
            'type' => 'type',
            'values' => 'values',
            'answer' => 'answer',
            'precomment_fr' => 'precomment_fr',
            'fiche_id' => 'fiche_id'
        );
    }

    /**
     * copy attributes of question answer-question.
     * @param type $question
     */
    public function copy($question) {
        $this->id = $question->id;
        $this->label = $question->label;
        $this->label_fr = $question->label_fr;
        $this->type = $question->type;
        $this->style = $question->style;
        $this->values = $question->values;
        $this->values_fr = $question->values_fr;
        $this->precomment = $question->precomment;
        $this->precomment_fr = $question->precomment_fr;
    }

    /**
     * set the value of an answer
     * @param type $val
     */
    public function setAnswer($val) {
        $this->answer = $val;
    }
    public function setAnswerNumerique($val) {
        $this->answer = new MongoInt32($val);
    }
    
    public function setAnswerDate($val) {
        $this->answer = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, $val);
    }
    
    /**
     * get literal value to display into a flat grid (csv, xls)
     *  input => values
     *  date => values
     *  radio => checkbox, text, image
     *
     */
    public function getLiteralAnswer(){
        $result=$this->answer;
       // if($this->type=='radio')
        //    $result
        return $result;
    }

}

