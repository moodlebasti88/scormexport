<?php
/*
 *
 * <p>draws the user interface</p>
 * 
 * @author Bastian Rosenfelder
 * 
 */

 require_once("{$CFG->libdir}/formslib.php");


 class scormexport_form extends moodleform
 {


    function definition()
    {
        global $CFG;

        $mform = $this->_form;
       
        /*_customdata saves data which is passed to the moodleform constructor*/
        $allQuizzes = $this->_customdata['quizzes'];
        $allLessons = $this->_customdata['lessons'];
        $allTextpages = $this->_customdata['textpages'];
        
        $mform->addElement('select', 'scormVersion', 'scormVersion', array('1.2','2004'));
        
        $mform->addElement('header','displayLessons',get_string('lessonBox','block_scormexport'));
        foreach($allLessons as $lesson)
        {
            /*create all lesson checkboxes and add them to group 1*/
            $mform->addElement('advcheckbox','lessonNames[]', $lesson->name,null,array('group' => 1), array('unchecked',$lesson->id));
        }
        
        /*add checkbox controller for group 1 (select/deselect all)*/
        $this->add_checkbox_controller(1);
        
        $mform->addElement('header','displayTests',get_string('testBox','block_scormexport'));
        foreach($allQuizzes as $quiz)
        {
            
            $mform->addElement('advcheckbox','testNames[]', $quiz->name,null,array('group' =>2), array('unchecked',$quiz->id));
        }
        
        $this->add_checkbox_controller(2);
        
        
        
        $mform->addElement('header','displayTextpages',get_string('testpageBox','block_scormexport'));
        foreach($allTextpages as $textpage)
        {
            
            $mform->addElement('advcheckbox','textpageNames[]', $textpage->name,null,array('group' =>3), array('unchecked',$textpage->id));
        }
        
        $this->add_checkbox_controller(3);

       
        /*must be added to the form. Otherwise the redirection to the block page wont work*/
        $mform->addElement('hidden', 'blockid');
        $mform->addElement('hidden', 'courseid');
        

        $this->add_action_buttons(true,get_string('export','block_scormexport'));

    }


 }


?>
