<?php

class scormTest extends scormData
{
    private $moduleType;
    private $title;
    private $parentPrefix;
    private $moduleId;
    private $name;
    private $timeLimit;
    private $maxtime;
            
    /**
     * Summary of scormTest
     * 
     * <p>constructor of scormTest</p>
     */
    function scormTest()
    {
        $this->moduleType = 'test';   
    }
    
    /**
     * Summary of setTitle
     * 
     * <p>sets the title for a test</p>
     * 
     * @param string $title test title
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
     * <p>sets the time limit for a test</p>
     * 
     * @param int $timeLimit 1 if test has a time limit; 0 if not
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
     * <p>sets the prefix for a test. needed for the manifest file</p>
     * 
     * @param string $parentPrefix e.g. I_Test or I_Lesson
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
     * <p>sets the id for a test. usually the database id</p>
     * 
     * @param int $moduleId unique identifier for each test
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
     * <p>sets the maxtime (in seconds)</p>
     * 
     * @param int $maxtime maximum time for the test
     */
    public function setMaxtime($maxtime)
    {
        $this->maxtime = $maxtime;       
    }
    
    /**
     * Summary of setName
     * 
     * <p>sets the name for the test</p>
     * 
     * @param string $name name of the test
     */
    public function setName($name)
    {
        $this->name = $name;    
    }
    
    /**
     * Summary of getTitle
     * 
     * <p>returns the title for a specific test</p>
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
     * <p>returns the maxtime as formatted string</p>
     * 
     * @return $formattedTime maxtime in hh:mm:ss form
     */
    public function getFormattedMaxtime()
    {
        $maxTime = $this->maxtime;
        
        $formattedTime = gmdate("H:i:s",$maxTime);
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
     * Summary of getId
     * 
     * <p>returns the id for the test</p>
     * 
     * @return int $this->moduleId
     */
    public function getId()
    {
        return $this->moduleId;   
    }
    
    /**
     * Summary of getName
     * 
     * <p>returns the name of the test</p>
     * 
     * @return string $this->name 
     */
    public function getName()
    {
        return $this->name;    
    }
    
    /**
     * Summary of hasTimeLimit
     * 
     * <p>returns true if the test has a time limit</p>
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