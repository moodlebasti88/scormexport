<?php
/*
 * Created on 20.05.2013
 *
 * @author Bastian Rosenfelder
 * @class block_scormexport display the block
 */

 class block_scormexport extends block_list
 {
    /**
     * Summary of init
     * 
     * <p>first called function</p>
     */
    public function init()
    {
        $this->title = get_string('scormexport', 'block_scormexport');

    }

    /**
     * Summary of get_content
     * 
     * <p>defines the block content</p>
     * 
     * @return stdClass $this->content
     */
    public function get_content()
    {

        global $COURSE, $CFG;
        
        
            if($this->content !== null)
            {
                return $this->content;
            }

            $this->content = new stdClass;
            $this->content->items = array();
            $this->content->icons = array();
            $this->content->footer = get_string('block_description','block_scormexport');

            $url = new moodle_url($CFG->wwwroot.'/blocks/scormexport/view.php',array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
            $this->content->items[] = html_writer::link($url,get_string('export','block_scormexport'));


            return $this->content;
     

    }

    /**
     * Summary of applicable_formats
     * 
     * <p>block should be visible in courses</p>
     * 
     * @return array[] containing the visibility settings
     */
    public function applicable_formats()
    {
        return array(
            'site-index' => false,
            'course-view' => true,
            'course-view-social' => true,
            'mod' => false,
            'mod-quiz' => false);

    }
    
    function has_config() 
    {
        return true;
    }



 }


?>
