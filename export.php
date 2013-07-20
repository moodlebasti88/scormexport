<?php

/**
 * Summary of exportLesson
 * 
 * <p>iterates through every selected lesson and calls functions to export the content</p>
 * 
 * @param string $lessonName name of the lesson
 * @param object $zip ZipArchive instance
 * @param array[] $course array containing information about the course
 * @param object $manifestWriter writes the manifest file
 * @param string $scormVersion used scorm version
 * @return bool true if operation was successful
 */
function exportLesson(&$lessonName,&$zip,&$course,&$manifestWriter,$scormVersion)
{
    global $DB;
    
    for($i = 0; $i<sizeof($lessonName); $i++)
    {

        if(strcmp($lessonName[$i],'unchecked'))
        {
            $logWriter = new errorLog();

            /*fetches the lesson record from the database and creates a lesson object*/
            $lessonrecord = $DB->get_record('lesson', array('id' => $lessonName[$i]));
            if($lessonrecord === false)
            {
                $logWriter->writeSyslog('lesson record with id '.$lessonName[$i].' does not exist'.CRLF,'warning',time());   
                continue;
            }
                
            
            $lesson = new lesson($lessonrecord);

            /*if there are no pages -> check the next lesson*/
            if(!($lesson->has_pages()))
                continue;

            $fs = get_file_storage();

            $firstpageid = $lesson->firstpageid;

            $instanceid = getInstanceId($course,$lesson->properties()->name);
            $context = get_context_instance(CONTEXT_MODULE,$instanceid);

            $scormLesson = new scormLesson();
            
            $scormLesson->setParentPrefix('I_Lesson_');
            $scormLesson->setFirstPage($firstpageid);
            $scormLesson->setName($lesson->properties()->name);
            $scormLesson->setId($lesson->properties()->id);
            $scormLesson->setTimeLimit($lesson->properties()->timed);
            $scormLesson->setMaxtime($lesson->properties()->maxtime);
            $scormLesson->setDependencies($lesson->properties()->dependency);
            $scormLesson->addLessonObject($lesson);
           
            
            addHeader($manifestWriter,'I_Lesson_',$lesson->properties()->id,$lesson->properties()->name);
            $files = createScormContent($context,$manifestWriter,$zip,$scormVersion,$scormLesson,$fs);
            writeLessonContent($firstpageid,$scormVersion,$lesson,$manifestWriter,$fs,$context,$zip);
            addFooter($lesson->properties()->name,$manifestWriter);

        }


    }
    
    return true;
    
}

/**
 * Summary of exportTest
 * 
 * <p>iterates through every selected quiz and calls functions to export the content</p>
 * 
 * @param string $testName name of the test
 * @param object $zip ZipArchive instance
 * @param array[] $course array containing information about the course
 * @param object $manifestWriter writes the manifest file
 * @param string $scormVersion used scorm version
 */
function exportTest($testName,$zip,$course,$manifestWriter,$scormVersion)
{
    global $DB;
    
    $logWriter = new errorLog();
    
    for($i=0; $i<sizeof($testName); $i++)
    {
        if(strcmp($testName[$i],'unchecked'))
        {
            $quizRecord = getQuizRecordById($testName[$i]);
            if($quizRecord === false)
            {
                $logWriter->writeSyslog('quiz record with id '.$testName[$i].' does not exist'.CRLF,'warning',time());
                continue;   
            }
             
            $instanceId = getInstanceId($course,$quizRecord->name);
            $context = get_context_instance(CONTEXT_MODULE,$instanceId);
            
            $cm = get_coursemodule_from_instance('quiz',$quizRecord->id);
            $quiz = new quiz($quizRecord,$cm,$course);
      
            
            if(!$quiz->has_questions())
                continue;
           
            $fs = get_file_storage();
            
            $scormTest = new scormTest();
           
            $scormTest->setParentPrefix('I_Test_');
            $scormTest->setName($quiz->get_quiz_name());
            
            
            $scormTest->setId($quiz->get_quizid());
            
            if($quiz->get_quiz()->timelimit > 0)
            {
                $scormTest->setTimeLimit(1);   
                $scormTest->setMaxtime($quiz->get_quiz()->timelimit);
            }
            
           
            
            
            addHeader($manifestWriter,'I_Test_',$quiz->get_quizid(),$quiz->get_quiz_name());
            $files = createScormContent($context,$manifestWriter,$zip,$scormVersion,$scormTest,$fs);
            writeQuizContent($scormVersion,$quiz,$manifestWriter,$fs,$context,$zip);
            addFooter($quiz->get_quiz_name(),$manifestWriter);
           
        }
    }
    
}

function exportTextpage($textPageName,&$zip,$course,&$manifestWriter,$scormVersion)
{
    for($i = 0; $i<sizeof($textPageName); $i++)
    {

        if(strcmp($textPageName[$i],'unchecked'))
        {
            $fs = get_file_storage();
           
            
            $textPageRecord = getTextPageById($textPageName[$i]);
            
            $instanceId = getInstanceId($course,$textPageRecord->name);
            $context = get_context_instance(CONTEXT_MODULE,$instanceId);
       
            writeTextPageContent($scormVersion,$textPageRecord,$manifestWriter,$fs,$context,$zip);
          
            
        }
        
        
    }
    
}

?>