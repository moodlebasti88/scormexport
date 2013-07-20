<?php

class scormLesson extends scormData
{
    private $lesson;
    private $moduleType;
    private $title;
    private $parentPrefix;
    private $moduleId;
    private $name;
    private $timeLimit;
    private $maxtime;
    private $dependency;
    private $firstpage;
    
    /**
     * Summary of scormLesson
     * 
     * <p>constructor of scormLesson</p>
     */
    function scormLesson()
    {
        $this->moduleType = 'lesson';
    }
    
    /**
     * Summary of setTitle
     * 
     * <p>sets the title of the lesson</p>
     * 
     * @param string $title title of the lesson
     * 
     * @return none
     */
    public function setTitle($title)
    {
        $this->title = $title;   
    }
    
    /**
     * Summary of setTimeLimit
     * 
     * <p>if there is a timeLimit set $timeLimit to true</p>
     * 
     * @param int  $timeLimit 1 if timeLimit is activated; 0 if not
     * 
     * @return none
     */
    public function setTimeLimit($timeLimit)
    {
        if($timeLimit == 1)
            $this->timeLimit = true;
        else
            $this->timeLimit = false;
    }
    
    /**
     * Summary of setParentPrefix
     * 
     * <p>sets the parent prefix. e.g. I_LESSON</p>
     * 
     * @param string $parentPrefix prefix string
     * 
     * @return none
     */
    public function setParentPrefix($parentPrefix)
    {
        $this->parentPrefix = $parentPrefix;    
    }
    
    /**
     * Summary of setId
     * 
     * <p>sets the id of the lesson</p>
     * 
     * @param int $moduleId usually the primary key from the lesson table
     * 
     * @return none
     */
    public function setId($moduleId)
    {
        $this->moduleId = $moduleId;   
    }
    
    /**
     * Summary of setMaxtime
     * 
     * <p>sets the maxtime for the lesson (in minutes)</p>
     * 
     * @param int $maxtime number of minutes which are allowed for the lesson
     * 
     * @return none
     */
    public function setMaxtime($maxtime)
    {
        $this->maxtime = $maxtime;       
    }
    
    /**
     * Summary of setDependencies
     * 
     * <p>sets a dependency to another lesson</p>
     * 
     * @param string $dependency identifier of a lesson
     * 
     * @return none
     */
    public function setDependencies($dependency)
    {
        $this->dependency = $dependency;
    }
    
    /**
     * Summary of setName
     * 
     * <p>sets the name for the lesson</p>
     * 
     * @param string $name name of the lesson
     * 
     * @return none
     */
    public function setName($name)
    {
        $this->name = $name;    
    }
    
    /**
     * Summary of setFirstPage
     * 
     * <p>sets the id of the first lesson page</p>
     * 
     * @param int $firstpageid id of the first lesson page
     */
    public function setFirstPage($firstpageid)
    {
        $this->firstpage = $firstpageid;
    }
    
    // check if needed
    public function addLessonObject(&$lesson)
    {
        $this->lesson = $lesson;
    }
    
    /**
     * Summary of getTitle
     * 
     * <p>returns the title of the lesson</p>
     * 
     * @return string $this->title 
     */
    public function getTitle()
    {
        return $this->title;   
    }
    
    /**
     * Summary of getFormattedMaxtime
     * 
     * <p>returns the maxtime as formatted time string</p>
     * 
     * @return string $formattedTime
     */
    public function getFormattedMaxtime()
    {
        $maxTime = $this->maxtime;
        $hours = floor($maxTime/60);
        $minutes = $maxTime%60;
        $seconds = '00';

        $formattedTime = sprintf("%02d:%02d:%02d", $hours, $minutes,$seconds);   
        return $formattedTime;
    }
    
    /**
     * Summary of getParentPrefix
     * 
     * <p>returns the parent prefix</p>
     * 
     * @return string $this->parentPrefix
     */
    public function getParentPrefix()
    {
        return $this->parentPrefix;    
    }
    
    /**
     * Summary of getDependencies
     * 
     * <p>returns the dependencies to other lessons</p>
     * 
     * @return string $this->dependency
     */
    public function getDependencies()
    {
        return $this->dependency;    
    }
    
    /**
     * Summary of getId
     * 
     * <p>returns the lesson id</p>
     * 
     * @return int $this->moduleId
     */
    public function getId()
    {
        return $this->moduleId;   
    }
    
    /**
     * Summary of getFirstPage
     * 
     * <p>returns the id of the first lesson page</p>
     * 
     * @return int $this->firstpage
     */
    public function getFirstPage()
    {
        return $this->firstpage;   
    }
    
    /**
     * Summary of getName
     * 
     * <p>returns the name of the lesson</p>
     * 
     * @return string $this->name
     */
    public function getName()
    {
        return $this->name;    
    }
    
    //check if needed
    public function getObject()
    {
        return $this->lesson;
    }
    
    /**
     * Summary of hasTimeLimit
     * 
     * <p>returns true if time limit is activated</p>
     * 
     * @return bool $this->timeLimit
     */
    public function hasTimeLimit()
    {
        return $this->timeLimit;   
    }
    
    /**
     * Summary of getModuleType
     * 
     * <p>returns the type of the learning module</p>
     * 
     * @return string $this->moduleType
     */
    public function getModuleType()
    {
        return $this->moduleType;    
    }
    
}

?>