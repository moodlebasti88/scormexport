<?php

class typeFuncs
{
    
    private $htmlString;
    
    /**
     * Summary of testFuncs
     * 
     * <p>constructor of class testFuncs</p>
     */
    function testFuncs()
    {
        $this->htmlString = '';
    }
    
    /**
     * Summary of addHeader
     * 
     * <p>adds the head area to a html file</p>
     * 
     * @return none
     */
    public function addHeader()
    {
        $html = new htmlWriter();
        
        $html->openTag('html');
        $html->openTag('head');
        $html->addString('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
        $html->addJsFile('text/javascript','../assets/apiWrapper12.js');
        $html->addJsFile('text/javascript','../assets/scoFunctions.js'); 
        $html->addJsFile('text/javascript','../assets/testFunctions.js');
        $html->closeTag('head');
        
        $this->htmlString .= $html->getHtmlString();
        
        
    }
    
    /**
     * Summary of resetString
     * 
     * <p>deletes the html string</p>
     * 
     * @return none
     * 
     */
    public function resetString()
    {
        $this->htmlString = '';   
    }
    
    /**
     * Summary of addMultChoiceQuestion
     * 
     * <p>adds the html code for a multiple choice question</p>
     * 
     * @param string $name name of the question
     * @param string $questiontext the question
     * @param float $maxmark maximum of points that can be achieved
     * @param array[] $options answer possibilities
     * 
     * @return string $htmlString string containing the generated html code
     */
    public function addMultChoiceQuestion($name,$questiontext,$maxmark,&$options)
    {
        $newContent = str_replace('@@PLUGINFILE@@','../assets',$questiontext);

        
        $search = array('<p>','</p>','@@PLUGINFILE@@');
        $replace = array('','','../assets');
        
        $html = new htmlWriter();
        
        $html->openTag('body','onload = "loadPage();"');
        $html->addString($newContent);
        $html->addForm('choice','');
        
        $html->addInputTag('hidden','maxScore',$maxmark);
        
        foreach($options->answers as $answer)
        {
            $html->addInputTag('checkbox','option',$answer->fraction);
            $html->addString(str_replace($search,$replace,$answer->answer)); 
            $html->addNewline();
        }
        
        $html->addNewline();
        
        
        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'checkMultChoiceTest();');
        
        $html->closeTag('form');
        $html->closeTag('body');
        $html->closeTag('html');
        
        $this->htmlString .= $html->getHtmlString();
        
        $htmlString = $this->htmlString;
        
        return $htmlString;
        
           
    }
    
    /**
     * Summary of addNumericalQuestion
     * 
     * <p>generates the html code for a numerical question</p>
     * 
     * @param string $name name of the numerical question
     * @param string $questiontext the question itself
     * @param float $maxmark maximum score that can be achieved
     * @param array[] $options array containing answer possibilities
     * 
     * @return string $htmlString string containing the generated html code
     */
    public function addNumericalQuestion($name,$questiontext,$maxmark,&$options)
    {
        $newContent = str_replace('@@PLUGINFILE@@','../assets',$questiontext);

        
        $html = new htmlWriter();
        
        $html->openTag('body','onload = "loadPage();"');
        $html->addString($newContent);
        $html->addForm('choice','');
        
        $html->addInputTag('hidden','maxScore',$maxmark);
        
        
        foreach($options->answers as $answer)
        {
            $html->addInputTag('hidden','tolerance',$answer->tolerance);
            $html->addInputTag('hidden','fraction',$answer->fraction);
            $html->addInputTag('hidden','answers',$answer->answer);
            
        }
        
        $html->addInputTag('number','result','');
        
        $html->addNewline();
        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'checkNumericalTest();');
        
        $html->closeTag('form');
        $html->closeTag('body');
        $html->closeTag('html');
        
        $this->htmlString .= $html->getHtmlString();
        
        $htmlString = $this->htmlString;
        
        return $htmlString;
        
    }
    
    /**
     * Summary of addShortanswerQuestion
     * 
     * <p>generates the html code for a shortanswer question</p>
     * 
     * @param string $name name of the shortanswer question
     * @param string $questiontext the question itself
     * @param float $maxmark maximum score that can be achieved
     * @param array[] $options array containing the answer possibilities
     * 
     * @return string $htmlString string containing the generated html code
     */
    public function addShortanswerQuestion($name,$questiontext,$maxmark,&$options)
    {
        $newContent = str_replace('@@PLUGINFILE@@','../assets',$questiontext);

        
        $html = new htmlWriter();
        
        $html->openTag('body','onload = "loadPage();"');
        $html->addString($newContent);
        $html->addForm('choice','');
        
        $html->addInputTag('hidden','maxScore',$maxmark);
        
        foreach($options->answers as $answer)
        {
            $html->addInputTag('hidden','answers',$answer->answer);
            $html->addInputTag('hidden','fractions',$answer->fraction);
            
        }
        
        $html->addInputTag('text','result','');
        $html->addNewline();
        
        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'checkShortanswerTest();');
        
        $html->closeTag('form');
        $html->closeTag('body');
        $html->closeTag('html');
        
        $this->htmlString .= $html->getHtmlString();
        
        $htmlString = $this->htmlString;
        
        return $htmlString;
            
           
        
    }
    
    /**
     * Summary of addTrueFalseQuestion
     * 
     * <p>returns the code for a true false question</p>
     * 
     * @param string $name name of the question
     * @param string $questiontext the question
     * @param float $maxmark maximum score that can be achieved
     * @param array[] $options answer possibilities
     * 
     * @return string $htmlString string containing the generated html code
     */
    public function addTrueFalseQuestion($name,$questiontext,$maxmark,$options)
    {
        $newContent = str_replace('@@PLUGINFILE@@','../assets',$questiontext);

        
        $search = array('<p>','</p>','@@PLUGINFILE@@');
        $replace = array('','','../assets');
        
        $html = new htmlWriter();
        
        $html->openTag('body','onload = "loadPage();"');
        $html->addString($newContent);
        $html->addForm('choice','');
        
        $html->addInputTag('hidden','maxScore',$maxmark);
        
        foreach($options->answers as $answer)
        {
            $html->addInputTag('radio','fractions',$answer->fraction);
            $html->addString(str_replace($search,$replace,$answer->answer));
        }
        
        $html->addNewline();
        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'checkTrueFalseTest();');
        
        
        $html->closeTag('form');
        $html->closeTag('body');
        $html->closeTag('html');
        
        $this->htmlString .= $html->getHtmlString();
        
        $htmlString = $this->htmlString;
        
        return $htmlString;
        
    }
    
    /**
     * Summary of addEssayQuestion
     * 
     * <p>returns the html code for a essay question</p>
     * 
     * @param string $name name of the question
     * @param string $questiontext the question
     * @param float $maxmark maximum score that can be achieved
     * 
     * @return string $htmlString string containing the generated html code
     */
    public function addEssayQuestion($name,$questiontext,$maxmark)
    {
        $newContent = str_replace('@@PLUGINFILE@@','../assets',$questiontext);
   
        $html = new htmlWriter();
        
        $html->openTag('body','onload = "loadPage();"');
        $html->addString($newContent);
        $html->addForm('choice','');
        
        $html->addInputTag('hidden','maxScore',$maxmark);
        
        $html->addString('<textarea name="user_eingabe" cols="50" rows="10"></textarea>');
        $html->addNewline();
        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'checkEssayTest();');
        
        $html->closeTag('form');
        $html->closeTag('body');
        $html->closeTag('html');
        
        $this->htmlString .= $html->getHtmlString();
        
        $htmlString = $this->htmlString;
        
        return $htmlString;
        
    }
    
    /**
     * Summary of addMatchingQuestion
     * 
     * <p>returns the html code for a matching question</p>
     * 
     * @param string $name name of the question
     * @param string $questiontext the question
     * @param float $maxmark maximum score that can be achieved
     * @param array[] $options answer possibilities
     * 
     * @return string $htmlString string containing the generated html code
     */
    public function addMatchingQuestion($name,$questiontext,$maxmark,$options)
    {
        $possibleAnswers = array();
        $search = array('<p>','</p>');
        
        $newContent = str_replace('@@PLUGINFILE@@','../assets',$questiontext);

        
        $html = new htmlWriter();
        
        $html->openTag('body','onload = "loadPage();"');
        $html->addString($newContent);
        $html->addForm('choice','');
        
        $html->addInputTag('hidden','maxScore',$maxmark);
        
        foreach($options->subquestions as $answer)
        {
            array_push($possibleAnswers,$answer->answertext);   
        }
        
        foreach($options->subquestions as $answer)
        {
            $html->addString(str_replace($search,'',$answer->questiontext));
            $html->createDropdown($possibleAnswers,$answer->answertext);
            $html->addInputTag('hidden','answers',$answer->answertext);
            $html->addNewline();
        }
        
        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'checkMatchingTest();');
        
        
        $html->closeTag('form');
        $html->closeTag('body');
        $html->closeTag('html');
        
        $this->htmlString .= $html->getHtmlString();
        
        $htmlString = $this->htmlString;
        
        return $htmlString;
           
        
    }
    
    /**
     * Summary of addManifestEntries
     * 
     * <p>adds item and sco content to the manifest file</p>
     * 
     * @param int $id id of the test
     * @param string $name name of the test
     * @param string $htmlString string containing the html code for the sco
     * @param object $manifestWriter writes the manifest file
     * @param object $zip Zip Archive instance
     */
    public function addManifestEntries($id,$name,$htmlString,&$manifestWriter,&$zip,$contextid,&$fs,$component = null,$filearea = null)
    {
        $zip->addFile($htmlString,'sco/sco_test_'.$id.'.html');

        $manifestWriter->addItem('I_TEST_'.$id,'R_TEST_SCO_'.$id);
        $manifestWriter->setTitle($name);
        $manifestWriter->closeTag('item');
        
        $manifestWriter->addScoResource('R_TEST_SCO_'.$id,'webcontent','sco','sco/sco_test_'.$id.'.html');
        $manifestWriter->addFile('sco/sco_test_'.$id.'.html','sco');
        
        if(is_array($filearea))
        {
            for($i= 0; $i<sizeof($filearea); $i++)
            {
                add_available_files($fs,$contextid,$component,$filearea[$i],$id,$zip,$manifestWriter);        
            }
        }
        
        
        $manifestWriter->closeResource('sco');
           
    }
    
    
}

?>