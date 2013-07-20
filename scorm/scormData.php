<?php

abstract class scormData
{
   
    abstract protected function setTitle($title);
    abstract protected function setParentPrefix($parentPrefix);
    abstract protected function setId($moduleId);
    abstract protected function setName($name);
    abstract protected function setTimeLimit($timeLimit);
    abstract protected function setMaxtime($maxtime);
   
    /**
     * Summary of getHeaderInformation
     * 
     * <p>returns an array with all needed javascript files</p>
     * 
     * @param string $scormVersion used scorm version
     * @return array[] $strings array containing javascript files as strings
     */
    public function getHeaderInformation($scormVersion)
    {
        if(!strcmp($scormVersion,'1.2'))
        {
            /*create api wrapper*/
            $apiWrapper = new apiWrapper12();
            $apiWrapper->addWrapperFunctions();
            $wrapperContent = $apiWrapper->getWrapperFileAsString();

            /*needed to check the page status*/
            $scoFuncs = new scoFuncs();
            $scoFuncs->addScoFunctions();
            $scoFuncsContent = $scoFuncs->getScoFuncsFileAsString();
            
            /*needed to analyze the test data*/
            $quizFuncs = new quizFuncs();
            $quizFuncs->addQuizFunctions();
            $quizFuncsContent = $quizFuncs->getQuizFunctionsAsString();
            
            $testFuncs = new testFuncs();
            $testFuncs->addTestFunctions();
            $testFuncsContent = $testFuncs->getTestFunctionsAsString();
                
        }
        
        $strings = array('wrapper' => $wrapperContent, 'scoFuncs' =>$scoFuncsContent, 'quizFuncs' => $quizFuncsContent, 'testFuncs' =>$testFuncsContent);
        $ref = &$strings;
            
        return $ref;
    }
    
    
}

?>