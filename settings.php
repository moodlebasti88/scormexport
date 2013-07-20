<?php


$settings->add(new admin_setting_configtext('scormexport/logfile_location', new lang_string('default_logfile_location', 'block_scormexport'),
        new lang_string('default_logfile_desc', 'block_scormexport'),'C:/xampp/htdocs/moodle/blocks/scormexport/download/', PARAM_TEXT));

?>