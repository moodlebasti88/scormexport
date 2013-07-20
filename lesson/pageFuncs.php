<?php

class pageFuncs
{
    private $scormVersion = null;
    
    /**
     * Summary of pageFuncs
     * 
     * <p>constructor of pageFuncs</p>
     * 
     * @param string $version used scorm Version; 1.2 by default
     */
    function pageFuncs($version = '1.2')
    {
        $this->scormVersion = $version;   
    }
    
    /**
     * Summary of addContentPage
     * 
     * <p>adds the html code for a content lesson page and writes the corresponding manifest entries</p>
     * 
     * @param object $page lesson page object
     * @param object $lesson lesson object. contains all lesson pages
     * @param object $manifestWriter writes the xml manifest file
     * @param object $fs needed to access files
     * @param object $context needed to access files
     * @param object $zip Zip Archive instance
     * 
     * @return bool true if operation was successful
     */
    public function addContentPage(&$page,&$lesson,&$manifestWriter,&$fs,&$context,&$zip)
    {
        /*replace PLUGINFILE urls*/
        $newContent = str_replace('@@PLUGINFILE@@','../assets',$page->properties()->contents);

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

        $zip->addFile($htmlString,'sco/sco_lesson_'.$page->properties()->id.'.html');


        /*add new sco*/
        $manifestWriter->addItem('I_LESSON_'.$page->properties()->id,'R_LESSON_SCO_'.$page->properties()->id);
        $manifestWriter->setTitle($page->properties()->title);
        $manifestWriter->closeTag('item');
        $manifestWriter->addScoResource('R_LESSON_SCO_'.$page->properties()->id,'webcontent','sco','sco/sco_lesson_'.$page->properties()->id.'.html');

     

        $ret = add_available_files($fs,$context->id,'mod_lesson','page_contents',$page->properties()->id,$zip,$manifestWriter);

        $manifestWriter->addFile('sco/sco_lesson_'.$page->properties()->id.'.html','sco');
        $manifestWriter->closeResource('sco');

        return true;

    }
    
    /**
     * Summary of addMultChoicePage
     * 
     * <p>adds the html code for a multiple choice sco and writes the corresponding manifest entries</p>
     * 
     * @param object $page lesson page object
     * @param object $lesson contains all lesson pages
     * @param object $manifestWriter writes the manifest file
     * @param object $fs needed to access files
     * @param object $context needed to access files
     * @param object $zip Zip Archive instance
     */
    public function addMultChoicePage(&$page,&$lesson,&$manifestWriter,&$fs,&$context,&$zip)
    {

        $answers = $page->get_answers();

        $html = new htmlWriter();

        $newContent = str_replace('@@PLUGINFILE@@','../assets',$page->properties()->contents);

        $html->openTag('html');
        $html->openTag('head');
        $html->addString('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
        $html->addJsFile('text/javascript','../assets/apiWrapper12.js');
        $html->addJsFile('text/javascript','../assets/scoFunctions.js');
        $html->addJsFile('text/javascript','../assets/quizFunctions.js');
        
        $html->closeTag('head');

        $html->openTag('body','onload = "loadPage();"');
        $html->addString($newContent);
        $html->addForm('choice','');

        foreach($answers as $answer)
        {
            $html->addInputTag('radio','option',$answer->properties()->response);
            $html->addInputTag('hidden','answers',$answer->properties()->score);

            $html->addString($answer->properties()->answer);

        }

        $html->addNewline();
        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'checkMultChoiceResult();');
        $html->addNewline();


        $html->closeTag('form');



        $html->closeTag('body');
        $html->closeTag('html');


        $htmlString = $html->getHtmlString();
        $zip->addFile($htmlString,'sco/sco_lesson_'.$page->properties()->id.'.html');

        $manifestWriter->addItem('I_LESSON_'.$page->properties()->id,'R_LESSON_SCO_'.$page->properties()->id);
        $manifestWriter->setTitle($page->properties()->title);
        $manifestWriter->closeTag('item');

        $manifestWriter->addScoResource('R_LESSON_SCO_'.$page->properties()->id,'webcontent','sco','sco/sco_lesson_'.$page->properties()->id.'.html');
        $manifestWriter->addFile('sco/sco_lesson_'.$page->properties()->id.'.html','sco');

        $ret = add_available_files($fs,$context->id,'mod_lesson','page_contents',$page->properties()->id,$zip,$manifestWriter);
        
        $manifestWriter->closeResource('sco');


    }
    
    /**
     * Summary of addNumericPage
     * 
     * <p>adds the html code for a numeric page and writes the corresponding manifest entries</p>
     * 
     * @param object $page lesson page object
     * @param object $lesson contains all lesson pages
     * @param object $manifestWriter writes the manifest file
     * @param object $fs needed to access files
     * @param object $context needed to access files
     * @param object $zip Zip Archive instance
     */
    public function addNumericPage(&$page,&$lesson,&$manifestWriter,&$fs,&$context,&$zip)
    {

        $answers = $page->get_answers();

        $html = new htmlWriter();

        $newContent = str_replace('@@PLUGINFILE@@','../assets',$page->properties()->contents);
        
        $html->openTag('html');
        $html->openTag('head');
        $html->addString('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
        $html->addJsFile('text/javascript','../assets/apiWrapper12.js');
        $html->addJsFile('text/javascript','../assets/scoFunctions.js');
        $html->addJsFile('text/javascript','../assets/quizFunctions.js');
        

        $html->closeTag('head');

        $html->openTag('body','onload = "loadPage();"');
        $html->addString($newContent);
        $html->addNewline();

        $html->addForm('choice','');
        $html->openTag('p');
        $html->addString('Ihre Antwort');
        $html->addInputTag('number','answer','');
        $html->closeTag('p');

        foreach($answers as $answer)
        {
            if($answer->properties()->score >= 1)
            {

                $html->addInputTag('hidden','correctAnswers',$answer->properties()->answer);
                $html->addInputTag('hidden','correctScores',$answer->properties()->score);
            }
            else
            {
                $html->addInputTag('hidden','wrongAnswers',$answer->properties()->answer);
                $html->addInputTag('hidden','wrongScores',$answer->properties()->score);
            }
        }

        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'checkNumericResult();');



        $html->closeTag('form');
        $html->closeTag('body');
        $html->closeTag('html');

        
        $htmlString = $html->getHtmlString();
        $zip->addFile($htmlString,'sco/sco_lesson_'.$page->properties()->id.'.html');

        $manifestWriter->addItem('I_LESSON_'.$page->properties()->id,'R_LESSON_SCO_'.$page->properties()->id);
        $manifestWriter->setTitle($page->properties()->title);
        $manifestWriter->closeTag('item');

        $manifestWriter->addScoResource('R_LESSON_SCO_'.$page->properties()->id,'webcontent','sco','sco/sco_lesson_'.$page->properties()->id.'.html');
        $manifestWriter->addFile('sco/sco_lesson_'.$page->properties()->id.'.html','sco');
        
        $ret = add_available_files($fs,$context->id,'mod_lesson','page_contents',$page->properties()->id,$zip,$manifestWriter);
        
        $manifestWriter->closeResource('sco');


    }
    
    /**
     * Summary of addTrueFalsePage
     * 
     * <p>adds the html code for a true false page and writes the corresponding content to the manifest</p>
     * 
     * @param object $page lesson page object
     * @param object $lesson lesson object. contains all lesson pages
     * @param object $manifestWriter writes the manifest file
     * @param object $fs needed to access files
     * @param object $context needed to access files
     * @param object $zip ZipArchive instance
     */
    public function addTrueFalsePage(&$page,&$lesson,&$manifestWriter,&$fs,&$context,&$zip)
    {
        $answers = $page->get_answers();

        $html = new htmlWriter();

        $newContent = str_replace('@@PLUGINFILE@@','../assets',$page->properties()->contents);

        $html->openTag('html');
        $html->openTag('head');
        $html->addString('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
        $html->addJsFile('text/javascript','../assets/apiWrapper12.js');
        $html->addJsFile('text/javascript','../assets/scoFunctions.js');
        $html->addJsFile('text/javascript','../assets/quizFunctions.js');
        

        $html->closeTag('head');

        $html->openTag('body','onload = "loadPage();"');
        $html->addString($newContent);
        $html->addNewline();

        $html->addForm('choice','');

        foreach($answers as $answer)
        {
            $html->addInputTag('radio','option',$answer->properties()->score);
            $html->addString($answer->properties()->answer);

        }
        $html->addNewline();
        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'checkTrueFalseResult();');


        $html->closeTag('form');
        $html->closeTag('body');
        $html->closeTag('html');

        
        $htmlString = $html->getHtmlString();
        $zip->addFile($htmlString,'sco/sco_lesson_'.$page->properties()->id.'.html');

        $manifestWriter->addItem('I_LESSON_'.$page->properties()->id,'R_LESSON_SCO_'.$page->properties()->id);
        $manifestWriter->setTitle($page->properties()->title);
        $manifestWriter->closeTag('item');

     

        $manifestWriter->addScoResource('R_LESSON_SCO_'.$page->properties()->id,'webcontent','sco','sco/sco_lesson_'.$page->properties()->id.'.html');
        $manifestWriter->addFile('sco/sco_lesson_'.$page->properties()->id.'.html','sco');
        
        $ret = add_available_files($fs,$context->id,'mod_lesson','page_contents',$page->properties()->id,$zip,$manifestWriter);
        
        $manifestWriter->closeResource('sco');



    }
    
    /**
     * Summary of addShortanswerPage
     * 
     * <p>adds the html code for a shortanswer page and writes the corresponding manifest entries</p>
     * 
     * @param object $page lesson page object
     * @param object $lesson lesson object. contains all lesson pages
     * @param object $manifestWriter writes the manifest file
     * @param object $fs needed to access files
     * @param object $context needed to access files
     * @param object $zip ZipArchive instance
     */
    public function addShortanswerPage(&$page,&$lesson,&$manifestWriter,&$fs,&$context,&$zip)
    {
        $answers = $page->get_answers();

        $html = new htmlWriter();

        $newContent = str_replace('@@PLUGINFILE@@','../assets',$page->properties()->contents);


        $html->openTag('html');
        $html->openTag('head');
        $html->addString('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
        $html->addJsFile('text/javascript','../assets/apiWrapper12.js');
        $html->addJsFile('text/javascript','../assets/scoFunctions.js');
        $html->addJsFile('text/javascript','../assets/quizFunctions.js');
        

        $html->closeTag('head');

        $html->openTag('body','onload = "loadPage();"');
        $html->addString($newContent);
        $html->addNewline();

        $html->addForm('choice','');
        $html->openTag('p');
        $html->addString('Ihre Antwort');
        $html->addInputTag('text','answer','');
        $html->closeTag('p');

        foreach($answers as $answer)
        {
            if($answer->properties()->score >= 1)
            {

                $html->addInputTag('hidden','correctAnswers',$answer->properties()->answer);
                $html->addInputTag('hidden','correctScores',$answer->properties()->score);
            }
            else
            {
                $html->addInputTag('hidden','wrongAnswers',$answer->properties()->answer);
                $html->addInputTag('hidden','wrongScores',$answer->properties()->score);
            }
        }

        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'checkShortAnswerResult();');



        $html->closeTag('form');
        $html->closeTag('body');
        $html->closeTag('html');

        $htmlString = $html->getHtmlString();
        $zip->addFile($htmlString,'sco/sco_lesson_'.$page->properties()->id.'.html');

        $manifestWriter->addItem('I_LESSON_'.$page->properties()->id,'R_LESSON_SCO_'.$page->properties()->id);
        $manifestWriter->setTitle($page->properties()->title);
        $manifestWriter->closeTag('item');

        $manifestWriter->addScoResource('R_LESSON_SCO_'.$page->properties()->id,'webcontent','sco','sco/sco_lesson_'.$page->properties()->id.'.html');
        $manifestWriter->addFile('sco/sco_lesson_'.$page->properties()->id.'.html','sco');
        
        $ret = add_available_files($fs,$context->id,'mod_lesson','page_contents',$page->properties()->id,$zip,$manifestWriter);
        
        $manifestWriter->closeResource('sco');
        
    }
    
    /**
     * Summary of addAssignmentPage
     * 
     * <p>adds the html code for a assignment page ans writes the corresponding content to the manifest</p>
     * 
     * @param object $page lesson page object
     * @param object $lesson contains all lesson pages
     * @param object $manifestWriter writes the manifest file
     * @param object $fs needed to access files
     * @param object $context needed to access files
     * @param object $zip ZipArchive instance
     */
    public function addAssignmentPage(&$page,&$lesson,&$manifestWriter,&$fs,&$context,&$zip)
    {

        $answers = $page->get_answers();

        $html = new htmlWriter();

        $newContent = str_replace('@@PLUGINFILE@@','../assets',$page->properties()->contents);


        $html->openTag('html');
        $html->openTag('head');
        $html->addString('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
        $html->addJsFile('text/javascript','../assets/apiWrapper12.js');
        $html->addJsFile('text/javascript','../assets/scoFunctions.js');
        $html->addJsFile('text/javascript','../assets/quizFunctions.js');
        

        $html->closeTag('head');

        $html->openTag('body','onload = "loadPage();"');
        $html->addString($newContent);
        $html->addNewline();

        $html->addForm('choice','');

        foreach($answers as $answer)
        {
            if(!isset($answer->properties()->response))
                continue;

            $html->openTag('p');
            $html->addString($answer->properties()->answer);
            $html->addInputTag('hidden','answers',$answer->properties()->response);
            $html->addInputTag('hidden','scores',$answer->properties()->score);
            $dropdownMenu = createDropdownMenu($answers,'properties()->response',$answer->properties()->response);
            $html->addString($dropdownMenu);
            $html->closeTag('p');

        }


        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'checkAssignResult();');

        $html->closeTag('form');
        $html->closeTag('body');
        $html->closeTag('html');


        $htmlString = $html->getHtmlString();
        $zip->addFile($htmlString,'sco/sco_lesson_'.$page->properties()->id.'.html');

        $manifestWriter->addItem('I_LESSON_'.$page->properties()->id,'R_LESSON_SCO_'.$page->properties()->id);
        $manifestWriter->setTitle($page->properties()->title);
        $manifestWriter->closeTag('item');

        $manifestWriter->addScoResource('R_LESSON_SCO_'.$page->properties()->id,'webcontent','sco','sco/sco_lesson_'.$page->properties()->id.'.html');
        $manifestWriter->addFile('sco/sco_lesson_'.$page->properties()->id.'.html','sco');
  
        $ret = add_available_files($fs,$context->id,'mod_lesson','page_contents',$page->properties()->id,$zip,$manifestWriter);
        
        $manifestWriter->closeResource('sco');
        
    }
    
    /**
     * Summary of addEndPage
     * 
     * <p>adds a end page (last content page)</p>
     * 
     * @param object $page lesson page object
     * @param object $lesson lesson object. contains all lesson pages
     * @param object $manifestWriter writes the manifest file
     * @param object $fs needed to access files
     * @param object $context needed to access files
     * @param object $zip ZipArchive instance
     */
    public function addEndPage(&$page,&$lesson,&$manifestWriter,&$fs,&$context,&$zip)
    {
        $newContent = str_replace('@@PLUGINFILE@@','../assets',$page->properties()->contents);

        $html = new htmlWriter();
        $html->openTag('html');
        $html->openTag('head');
        $html->addString('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
        $html->addJsFile('text/javascript','../assets/apiWrapper12.js');
        $html->addJsFile('text/javascript','../assets/scoFunctions.js');
        $html->closeTag('head');
        $html->openTag('body','onload = "loadPage();" onunload = "return unloadPage(\'completed\');"');
        $html->openTag('p');
        $html->addString($newContent);
        $html->closeTag('p');
        $html->closeTag('body');


        $html->closeTag('html');

        $htmlString = $html->getHtmlString();

        $zip->addFile($htmlString,'sco/sco_lesson_'.$page->properties()->id.'.html');


        /*add new sco*/
        $manifestWriter->addItem('I_LESSON_'.$page->properties()->id,'R_LESSON_SCO_'.$page->properties()->id);
        $manifestWriter->setTitle($page->properties()->title);
        $manifestWriter->closeTag('item');
        $manifestWriter->addScoResource('R_LESSON_SCO_'.$page->properties()->id,'webcontent','sco','sco/sco_lesson_'.$page->properties()->id.'.html');

        $ret = add_available_files($fs,$context->id,'mod_lesson','page_contents',$page->properties()->id,$zip,$manifestWriter);
      
        $manifestWriter->addFile('sco/sco_lesson_'.$page->properties()->id.'.html','sco');
        $manifestWriter->closeResource('sco');

       
    }
    
    /**
     * Summary of addEssayPage
     * 
     * <p>writes the html code for a essay page and writes the corresponding manifest entries</p>
     * 
     * @param object $page lesson page object 
     * @param object $lesson lesson object. contains all lesson pages
     * @param object $manifestWriter writes the manifest file
     * @param object $fs needed to access files
     * @param object $context needed to access files
     * @param object $zip ZipArchive instance
     * @return
     */
    public function addEssayPage(&$page,&$lesson,&$manifestWriter,&$fs,&$context,&$zip)
    {
        
       
        $html = new htmlWriter();
        
        $html->openTag('html');
        
        $html->openTag('head');
        $html->addString('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
        $html->addJsFile('text/javascript','../assets/apiWrapper12.js');
        $html->addJsFile('text/javascript','../assets/scoFunctions.js');
        $html->addJsFile('text/javascript','../assets/quizFunctions.js');
        $html->closeTag('head');
        
        $html->openTag('body','onload = "loadPage();"');
        $html->openTag('p');
        $html->addString($page->properties()->title);
        $html->closeTag('p');
        
        $html->addForm('choice','');
        $html->addString('<textarea name="user_eingabe" cols="50" rows="10"></textarea>');
        $html->addNewline();
        $html->addInputTag('button','submit',get_string('buttonname_submit','block_scormexport'),'sendEssayInput()');
        $html->closeTag('form');
        $html->closeTag('body');


        $html->closeTag('html');
        
        $htmlString = $html->getHtmlString();
        
        $zip->addFile($htmlString,'sco/sco_lesson_'.$page->properties()->id.'.html');


        /*add new sco*/
        $manifestWriter->addItem('I_LESSON_'.$page->properties()->id,'R_LESSON_SCO_'.$page->properties()->id);
        $manifestWriter->setTitle($page->properties()->title);
        $manifestWriter->closeTag('item');
        
        $manifestWriter->addScoResource('R_LESSON_SCO_'.$page->properties()->id,'webcontent','sco','sco/sco_lesson_'.$page->properties()->id.'.html');
        $manifestWriter->addFile('sco/sco_lesson_'.$page->properties()->id.'.html','sco');
        $manifestWriter->closeResource('sco');
        
        return true;
        
    }
   
}



?>