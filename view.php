<?php
/*
 * Created on 21.05.2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


require_once('../../config.php');
require_once('globals.php');
require_once('scormexport_form.php');
require_once('sqlQuerys.php');
require_once('html/htmlWriter.php');
require_once('export.php');
require_once('js/wrapper/apiWrapper12.php');
require_once('js/scoFuncs.php');
require_once('js/quizFuncs.php');
require_once('js/testFuncs.php');
require_once('lesson/pageFuncs.php');
require_once('quiz/typeFuncs.php');
require_once('scorm/scormData.php');
require_once('scorm/scormLesson.php');
require_once('scorm/scormTest.php');
require_once('viewFuncs.php');
require_once('../../mod/lesson/locallib.php');
require_once('../../mod/quiz/locallib.php');
require_once('error.php');
require_once('manifestWriter.php');
require_once('zipstream.php');



global $DB, $OUTPUT, $PAGE;

$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);

/*Next look for optional variables*/
$id = optional_param('id', 0, PARAM_INT);
$lessonNames[] = optional_param_array('lessonNames',array(),PARAM_TEXT);
$testNames[] = optional_param_array('testNames',array(),PARAM_TEXT);
$textPageNames[] = optional_param_array('textpageNames',array(),PARAM_TEXT);
$scormVersion = optional_param('scormVersion','',PARAM_TEXT);

/*check if course exists*/
$course = validateCourse($courseid);
$errHandler = new errorLog();
if (!$course)
{
    print_error('invalidcourse', 'block_scormexport', $courseid);
    $errHandler->writeSyslog('invalid course'.CRLF,'error',time());
}

/*checks that the user is logged in*/
require_login($course);


$PAGE->set_url('/blocks/scormexport/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_scormexport'));


$lessons = getLessonsByCourse($courseid);
$quizzes = getQuizzesByCourse($courseid);
$textpages = getTextPagesByCourse($courseid);


$results = array('lessons' =>$lessons,
                 'quizzes' =>$quizzes,
                 'textpages' => $textpages);

/*draws the user interface*/
$scormform = new scormexport_form(null,$results);

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;

$scormform->set_data($toform);


if($scormform->is_cancelled())
{
    /*cancelled forms redirect to the course main page*/
    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($courseurl);
}
else if ($fromform = $scormform->get_data())
{
    /*writes the imsmanifest file*/
    $manifestWriter = new manifestWriter();
  
    /*our package interchange file (pif)*/
    $zip = new ZipStream($course->shortname.'.zip');

    
    /*add directories for resources*/
    $zip->addDirectory('assets');
    $zip->addDirectory('sco');
   
   

    createManifestHeader($manifestWriter,XML_VERSION,SCORM_SCHEMA,'1.2');
    
    $manifestWriter->openTag('organizations','default = "'.$course->shortname.'"');
    $manifestWriter->openTag('organization','identifier ="'.$course->shortname.'"');

    if(isset($lessonNames))
    {
        foreach($lessonNames as $lessonName)
        {
            exportLesson($lessonName,$zip,$course,$manifestWriter,'1.2');   
        }
    }  
    if(isset($testNames))
    {
        foreach($testNames as $testName)
        {
            exportTest($testName,$zip,$course,$manifestWriter,'1.2');
        }
    }
    if(isset($textPageNames))
    {
        foreach($textPageNames as $textPageName)
        {
            exportTextpage($textPageName,$zip,$course,$manifestWriter,'1.2');
        }
    }
   
    $manifestWriter->closeTag('organization');
    $manifestWriter->closeTag('organizations');
    $manifestWriter->endManifest();
    
    $xmlString = $manifestWriter->getXmlAsString();
    $validation = simplexml_load_string($xmlString);
    
    /*if the xml string is not well formed-> view error message and create error log entry*/
    if($validation === FALSE)
    {
       
        print_r($xmlString);
    }
    else
    {
        $zip->addFile($xmlString,MANIFEST_NAME);
    }
    $zip->finalize();
    

}
else
{

    $site = get_site();
    echo $OUTPUT->header();
    $scormform->display();
    echo $OUTPUT->footer();

}




?>
