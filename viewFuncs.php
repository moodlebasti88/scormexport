<?php
/*
 * Created on 24.05.2013
 *
 *@author Bastian Rosenfelder
 */

function send_download($file)
{
    $basename = basename($file);
    $length   = sprintf("%u", filesize($file));

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $basename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Connection: Keep-Alive');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . $length);

    set_time_limit(0);
    readfile($file);
    
    return true;
}



/**
 * Summary of createManifestHeader
 * 
 * <p>defines the used xml and scorm version</p>
 * 
 * @param object $manifestWriter object needed to write the imsmanifest file
 * @param string $xmlVersion used xml version
 * @param string $schema used xml schema. Normally ADL SCORM
 * @param string $schemaversion scorm version e.g. 1.2 
 * @return bool true if operation was successful
 */
function createManifestHeader(&$manifestWriter,$xmlVersion,$schema,$schemaversion)
{
    $manifestWriter->setXmlVersion($xmlVersion);
    $manifestWriter->openTag('manifest','identifier="courseManifest" version="1.1" xmlns:adlcp="http://www.adlnet.org/xsd/adlcp_rootv1p2"');
    $manifestWriter->openTag('metadata');
    $manifestWriter->addSchema($schema,$schemaversion);
    $manifestWriter->closeTag('metadata');

    return true;

}


/**
 * Summary of getInstanceId
 * 
 * <p>returns the instance id for the given lesson</p>
 * 
 * @param object $course course object which contains the lesson
 * @param object $lesson lesson object
 * @return int $instanceid instance id for the given lesson
 */
function getInstanceId($course,$name)
{
    $info = get_fast_modinfo($course);
    $cms = $info->get_cms();

    foreach($cms as $cm)
    {
        if(isset($cm->name))
        {
            if(!strcmp($cm->name,$name))
            {
                $instanceid = $cm->context->instanceid;
                return $instanceid;
            }
        }
    }

}


/**
 * Summary of createIndexPage
 * 
 * <p>creates the start page for each lesson or test</p>
 * 
 * @return $content string containing the html code including javascript
 */
function createIndexPage()
{
    $jsContent = 'loadPage();
	              var studentName = "!";
	              var lmsStudentName = doLMSGetValue("cmi.core.student_name");
	
	              if (lmsStudentName  != "")
	              {
	                studentName = " " + lmsStudentName +   "!";
	              }
	
	             document.write(studentName);';
    
    $html = new htmlWriter();
    
    $html->openTag('html');
    $html->openTag('head');
    $html->addJsFile('text/javascript','../assets/apiWrapper12.js');
    $html->addJsFile('text/javascript','../assets/scoFunctions.js');
    $html->closeTag('head');
    
    $html->openTag('body','onunload = "return unloadPage(\'completed\');"');
    $html->openTag('p');
    $html->addString(get_string('welcome_string','block_scormexport'));
    $html->closeTag('p');
    $html->addString('<script language = "javascript">'.$jsContent.'</script>');
    
    
    
    $html->closeTag('body');
    $html->closeTag('html');
    
    $content = $html->getHtmlString();
    
    return $content;
    
    
}

/**
 * Summary of addHeader
 * 
 * <p>writes the parent data to the manifest file</p>
 * 
 * @param object $manifestWriter adds content to the manifest file
 * @param string $parentPrefix e.g. I_Lesson_ or I_Test_
 * @param int $id identifies a learning module
 * @param string $name name of the learning module
 */
function addHeader(&$manifestWriter,$parentPrefix,$id,$name)
{
  $manifestWriter->addItem($parentPrefix.$id,$name);
  $manifestWriter->setTitle($name);   
}

/**
 * Summary of addFooter
 * 
 * <p>closes the parent data in the manifest</p>
 * 
 * @param string $name name of the learning module
 * @param object $manifestWriter adds content to the manifest file
 */
function addFooter($name,&$manifestWriter)
{
    $manifestWriter->closeTag('item');
    $manifestWriter->addScoResource($name,'webcontent','sco','sco/sco_'.$name.'.html');
    $manifestWriter->addFile('sco/sco_'.$name.'.html','sco');
    $manifestWriter->closeResource('sco'); 
}


/**
 * Summary of createScormContent
 * 
 * <p>creates all needed javascript files</p>
 * <p>writes common learning module data to the manifest file</p>
 * 
 * @param object $context needed to access files
 * @param object $manifestWriter writes content to the manifest file
 * @param object $zip ZipArchive instance
 * @param string $version used scorm version
 * @param object $scormObject whether scormLesson or scormTest instance
 * @param object $fs needed to access files
 * 
 * @return bool true if operation was successful
 * 
 */
function createScormContent(&$context,&$manifestWriter,&$zip,$version,&$scormObject,&$fs)
{
     static $exists = false; 
    
     $parentPrefix = $scormObject->getParentPrefix();
     $id = $scormObject->getId();
     $name = $scormObject->getName();
     $timeLimit = $scormObject->hasTimeLimit();
     
     $firstpage = null;
     
     $indexPage = createIndexPage();
     $zip->addFile($indexPage,'sco/sco_'.$name.'.html');
     
     if(!$exists)
     {
         $jsFiles = $scormObject->getHeaderInformation($version);
         
         /*create javascript files and add them to a zip archive*/
         $zip->addFile($jsFiles['wrapper'],'assets/apiWrapper12.js');
         $zip->addFile($jsFiles['scoFuncs'],'assets/scoFunctions.js');
         $zip->addFile($jsFiles['quizFuncs'],'assets/quizFunctions.js');
         $zip->addFile($jsFiles['testFuncs'],'assets/testFunctions.js'); 
         
         $exists = true;
     }
    
     /*time limit activated?*/
     if($timeLimit == true)
     {
        $formattedTime = $scormObject->getFormattedMaxtime();
        
        $manifestWriter->setMaxtime($formattedTime);
        $manifestWriter->setTimeLimitAction('exit,message');
     }
     

     /*check if there are dependencies to other lessons*/
     if(method_exists($scormObject,'getDependencies'))
     {
        $id = $scormObject->getDependencies();
        $manifestWriter->addPrerequisites($parentPrefix.$id);
     }

    return true;

}

/**
 * Summary of writeQuizContent
 * 
 * <p>iterates through all questions ans checks the question type</p>
 * <p>calls functions to create the sco's and write the manifest file</p>
 * 
 * @param string $scormVersion used scorm version
 * @param object $quiz quiz object. contains all questions for a quiz
 * @param object $manifestWriter writes the manifest file
 * @param object $fs needed to access files
 * @param object $context needed to access files
 * @param object $zip ZipArchive object
 */
function writeQuizContent($scormVersion,&$quiz,&$manifestWriter,&$fs,&$context,&$zip)
{
    /*must be called before load_questions()*/
    $quiz->preload_questions();
    $quiz->load_questions();
    
    $questions = $quiz->get_questions();
    $typeFuncs = new typeFuncs();
    
    /*iterate through all questions*/
    foreach($questions as $question)
    {
        
        switch($question->qtype)
        {
            case 'multichoice':
                $typeFuncs->addHeader();
                $htmlString = $typeFuncs->addMultChoiceQuestion($question->name,$question->questiontext,$question->maxmark,$question->options);
                $typeFuncs->addManifestEntries($question->id,$question->name,$htmlString,$manifestWriter,$zip,$question->contextid,$fs,'question',array('answer','questiontext'));
                $typeFuncs->resetString();
                break;
            
            case 'numerical':
                $typeFuncs->addHeader();
                $htmlString = $typeFuncs->addNumericalQuestion($question->name,$question->questiontext,$question->maxmark,$question->options);
                $typeFuncs->addManifestEntries($question->id,$question->name,$htmlString,$manifestWriter,$zip,$question->contextid,$fs,'question',array('questiontext'));
                $typeFuncs->resetString();
                break;
                
            case 'shortanswer':
                $typeFuncs->addHeader();
                $htmlString = $typeFuncs->addShortanswerQuestion($question->name,$question->questiontext,$question->maxmark,$question->options);
                $typeFuncs->addManifestEntries($question->id,$question->name,$htmlString,$manifestWriter,$zip,$question->contextid,$fs,'question',array('questiontext'));
                $typeFuncs->resetString();
                break;
                
            case 'truefalse':
                $typeFuncs->addHeader();
                $htmlString = $typeFuncs->addTrueFalseQuestion($question->name,$question->questiontext,$question->maxmark,$question->options);
                $typeFuncs->addManifestEntries($question->id,$question->name,$htmlString,$manifestWriter,$zip,$question->contextid,$fs,'question',array('answer','questiontext'));
                $typeFuncs->resetString();
                break;
                
            case 'essay':
                $typeFuncs->addHeader();
                $htmlString = $typeFuncs->addEssayQuestion($question->name,$question->questiontext,$question->maxmark);
                $typeFuncs->addManifestEntries($question->id,$question->name,$htmlString,$manifestWriter,$zip,$question->contextid,$fs,'question',array('questiontext'));
                $typeFuncs->resetString();
                break;
            
            case 'match':
                $typeFuncs->addHeader();
                $htmlString = $typeFuncs->addMatchingQuestion($question->name,$question->questiontext,$question->maxmark,$question->options);
                $typeFuncs->addManifestEntries($question->id,$question->name,$htmlString,$manifestWriter,$zip,$question->contextid,$fs,'question',array('questiontext'));
                $typeFuncs->resetString();
                break;
                
                
                
            default:
                /*calculated questions cannot be exported. value interval cannot be accessed*/
                break;
                
                
        }
    }
    
    
    
}


function writeTextPageContent($scormVersion,$textPageRecord,&$manifestWriter,&$fs,&$context,&$zip)
{
    $newContent = str_replace('@@PLUGINFILE@@','../assets',$textPageRecord->content);

    $html = new htmlWriter();
    
    $html->openTag('html');
    $html->openTag('head');
    $html->addString('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
    $html->addJsFile('text/javascript','../assets/apiWrapper12.js');
    $html->addJsFile('text/javascript','../assets/scoFunctions.js');
    $html->closeTag('head');
    $html->openTag('body','onload = "loadPage();" onunload = "return unloadPage(\'completed\');"');
    $html->addString($newContent);
    $html->closeTag('body');


    $html->closeTag('html');

    $htmlString = $html->getHtmlString();

    $zip->addFile($htmlString,'sco/sco_textpage_'.$textPageRecord->id.'.html');


    /*add new sco*/
    $manifestWriter->addItem('I_TEXTPAGE_'.$textPageRecord->id,'R_TEXTPAGE_SCO_'.$textPageRecord->id);
    $manifestWriter->setTitle($textPageRecord->name);
    $manifestWriter->closeTag('item');
    $manifestWriter->addScoResource('R_TEXTPAGE_SCO_'.$textPageRecord->id,'webcontent','sco','sco/sco_textpage_'.$textPageRecord->id.'.html');

    

    $ret = add_available_files($fs,$context->id,'mod_page','content',null,$zip,$manifestWriter);

    $manifestWriter->addFile('sco/sco_textpage_'.$textPageRecord->id.'.html','sco');
    $manifestWriter->closeResource('sco');

    return true;
    
}

/**
 * Summary of writeLessonContent
 * 
 * <p>iterates through all lesson pages</p>
 * <p>checks the type of the lesson page and calls functions to create the sco's</p>
 * 
 * @param int $firstpage identifier of the first lesson page
 * @param string $version used scormVersion
 * @param object $lesson lesson object. contains all lesson pages
 * @param object $manifestWriter writes the manifest file
 * @param object $fs needed to access files
 * @param object $context needed to access files
 * @param object $zip ZipArchive object
 * @return bool true if operation was successful
 */
function writeLessonContent($firstpage,$version,&$lesson,&$manifestWriter,&$fs,&$context,&$zip)
{
    
    /*initialize nextPage with the id of the first page*/
    $nextPage = $firstpage;
    $pageFuncs = new pageFuncs($version);
    
    do
    {

        $page = $lesson->load_page($nextPage);

        /*get identifier of the page e.g. content = 20 */
        $pageType = $page->get_typeid();

        switch($pageType)
        {
            case CONTENT:       
                $pageFuncs->addContentPage($page,$lesson,$manifestWriter,$fs,$context,$zip);
                break;

            case MULT_CHOICE:
                
                $pageFuncs->addMultChoicePage($page,$lesson,$manifestWriter,$fs,$context,$zip); 
                break;

            case NUMERIC:
                $pageFuncs->addNumericPage($page,$lesson,$manifestWriter,$fs,$context,$zip);
                break;

            case TRUE_FALSE:
                $pageFuncs->addTrueFalsePage($page,$lesson,$manifestWriter,$fs,$context,$zip);
                break;

            case SHORTANSWER:
                $pageFuncs->addShortanswerPage($page,$lesson,$manifestWriter,$fs,$context,$zip);
                break;

            case ASSIGNMENT:
                $pageFuncs->addAssignmentPage($page,$lesson,$manifestWriter,$fs,$context,$zip);
                break;
            
            case CONTENT_END:
                $pageFuncs->addEndPage($page,$lesson,$manifestWriter,$fs,$context,$zip);
                break;
            
            case ESSAY:
                $pageFuncs->addEssayPage($page,$lesson,$manifestWriter,$fs,$context,$zip);
                break;
            
            default:
                break;

        }


        $nextPage = $page->nextpageid;
    }
    while($nextPage != 0);
    
    return true;
    
}




/**
 * Summary of createDropdownMenu
 * 
 * <p>creates a dropdown menu</p>
 * 
 * @param array $array contains all possible answers
 * @param $index
 * @param string $answer correct answer
 * @return string $dropdown string containing the html code for the dropdown menu
 */
function createDropdownMenu(&$array,$index,$answer)
{
    $options = array();
    foreach($array as $entry)
    {
        array_push($options,$entry->properties()->response);
    }

    $dropdown = '<select id="'.$answer.'" size="1">';
    for($i=0; $i<sizeof($options); $i++)
    {
        $dropdown .= '<option value="'.$answer.'">'.$options[$i].'</option>';

    }
    $dropdown .= '</select>';
    return $dropdown;

}

function add_available_files(&$fs,$contextid,$component,$filearea,$id,&$zip,&$manifestWriter)
{
    $files = null;
    
    if($id != null)
        $files = $fs->get_area_files($contextid,$component,$filearea,$id);
    else
        $files = $fs->get_area_files($contextid,$component,$filearea);
    
    foreach($files as $file)
    {
        
        if(!strcmp($file->get_filename(),'.'))
            continue;

        
        $zip->addFile($file->get_content(),'assets/'.$file->get_filename());


        /*add assets*/
        $manifestWriter->addAssetResource('R_A'.$file->get_id(),'webcontent','asset');
        $manifestWriter->addFile('assets/'.$file->get_filename(),'asset');
        $manifestWriter->closeResource('asset');

        /*add dependency to the corresponding sco*/
        $manifestWriter->addDependency('R_A'.$file->get_id());


    }
    
    return true;
}



















?>
