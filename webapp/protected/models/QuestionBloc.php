<?php

/**
 * classe embarquée Question, définit les objets Question dans les questionnaires
 * @author matthieu
 *
 */
class QuestionBloc extends EMongoEmbeddedDocument {

    public $id;
    public $title;
    public $title_fr;
    public $questions;
    
    /**
     * parent group if setted.
     * @var type 
     */
    public $parent_group;
    



    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array(
                'id,title, parent_group',
                'required'
        ));
    }

    /**
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'id',
            'title' => 'titre',
            'title_fr' => 'titre',
            'parent_group' => 'Groupe parent'
        );
    }


}
