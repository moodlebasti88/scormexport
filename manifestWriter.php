<?php
/*
 * <p>manifest Writer class definition</p>
 * Created on 27.05.2013
 *
 * @author Bastian Rosenfelder
 * @class manifestWriter writes the imsmanifest file
 *
 */
 class manifestWriter
 {
    /**
     *@var $xmlStruct contains the structure part
     */
    private $xmlStruct;


    /**
     * @var $xmlAssets string containing all assets
     */
    private $xmlAssets;


    /**
     *@var $xmlSco string containing the sco's
     */
    private $xmlSco;

    /**
     * @var $endManifest string containing the end tag
     */
    private $endManifest;



    /**
     * <p>constructor of the manifestWriter class</p>
     *
     * @param string $initValue init value for the xmlStruct
     * @return none
     */
    function manifestWriter($initValue = '')
    {
        $this->xmlStruct = $initValue;
        $this->xmlAssets = $initValue;
        $this->xmlSco = $initValue;
        $this->endManifest = $initValue;
    }

    /**
     * <p>defines the used xml version</p>
     *
     *@param string $version used version number
     *@return none
     *
     */
    public function setXmlVersion($version)
    {
        $this->xmlStruct .= '<?xml version="'.$version.'"?>';
    }


    /**
     * <p>opens a tag</p>
     *
     *@param string $tagName
     *@return none
     */
    public function openTag($tagName,$attr = null)
    {
        if($attr == null)
            $this->xmlStruct .= '<'.$tagName.'>';
        else
            $this->xmlStruct .= '<'.$tagName.' '.$attr.'>';

    }

    /**
     * <p>closes the tag specified by $tagName</p>
     *
     *@param string $tagName
     *@return  none
     */
    public function closeTag($tagName)
    {
        $this->xmlStruct .= '</'.$tagName.'>';

    }

    /**
     * <p>adds the schema and the schemaversion</p>
     *
     *@param string $schema
     *@param string $schemaversion
     *@return  none
     */
    public function addSchema($schema,$schemaversion)
    {
        $this->xmlStruct .= '<schema>'.$schema.'</schema>';
        $this->xmlStruct .= '<schemaversion>'.$schemaversion.'</schemaversion>';

    }

    /**
     * <p>adds a title</p>
     *
     *@param string $title
     *@return none
     */
    public function setTitle($title)
    {
        $this->xmlStruct .= '<title>'.$title.'</title>';
    }

   /**
    * Summary of setMaxtime
    * 
    * <p>defines the max time for a learning module (if time limit is activated)</p>
    * 
    * @param string $maxTime formatted time string. hh:mm:ss
    * @return none
    */
    public function setMaxtime($maxTime)
    {
        $this->xmlStruct .= '<adlcp:maxtimeallowed>'.$maxTime.'</adlcp:maxtimeallowed>';

    }
    
    /**
     * Summary of setTimeLimitAction
     * 
     * <p>defines the event that occurs if max time is over</p>
     * 
     * @param string $action exit,message by default. can be configured at globals.php
     * @return none
     */
    public function setTimeLimitAction($action)
    {
        $this->xmlStruct .= '<adlcp:timelimitaction>'.$action.'</adlcp:timelimitaction>';
    }
    
    /**
     * Summary of addPrerequisites
     * 
     * <p>adds a dependency to another lesson. e.g. lesson 1 must be completed before starting lesson 2</p>
     * 
     * @param string $dependency identifier of a lesson
     * 
     * @return none
     */
    public function addPrerequisites($dependency)
    {
        $this->xmlStruct .= '<adlcp:prerequisites type="aicc_script">'.$dependency.'</adlcp:prerequisites>';
    }
    
    /**
     * Summary of addScoResource
     * 
     * <p>adds a sco resource to the manifest</p>
     * 
     * @param string $identifier unique identifier for the sco
     * @param string $type describes the type of the resource. normally 'webcontent'
     * @param string $scormType asset or sco
     * @param string $href path to the sco
     * 
     * @return none
     */
    public function addScoResource($identifier,$type,$scormType,$href)
    {
        $this->xmlSco .= '<resource identifier="'.$identifier.'" type="'.$type.'" adlcp:scormtype="'.$scormType.'" href="'.$href.'">';

    }

    /**
     * Summary of addAssetResource
     * 
     * <p>adds a asset resource to the manifest</p>
     * 
     * @param string $identifier unique identifier for the Asset
     * @param string $type normally webcontent
     * @param string $scormType whether asset or sco
     * 
     * @return none
     */
    public function addAssetResource($identifier,$type,$scormType)
    {
        $this->xmlAssets .= '<resource identifier="'.$identifier.'" type="'.$type.'" adlcp:scormtype="'.$scormType.'">';

    }
    
    /**
     * Summary of addFile
     * 
     * <p>adds a filepath to the manifest file</p>
     * 
     * @param string $href path to the file
     * @param string $type whether asset or sco
     */
    public function addFile($href,$type)
    {
        if(!strcmp($type,'asset'))
           $this->xmlAssets .= '<file href="'.$href.'"/>';
        else
            $this->xmlSco .= '<file href="'.$href.'"/>';
    }
    
    /**
     * Summary of addDependency
     * 
     * <p>adds a dependency to a sco.Each asset the sco is referencing is a dependency</p>
     * 
     * @param string $identifierref identifier of a resource
     * 
     * @return none
     */
    public function addDependency($identifierref)
    {
        $this->xmlSco .= '<dependency identifierref="'.$identifierref.'"/>';

    }

    /**
     * Summary of closeResource
     * 
     * <p>closes a resource</p>
     * 
     * @param string $type whether asset or sco
     * 
     * @return none
     */
    public function closeResource($type)
    {
        if(!strcmp($type,'asset'))
        {
            $this->xmlAssets .= '</resource>';
        }
        else
        {
            $this->xmlSco .= '</resource>';
        }

    }
    
    /**
     * Summary of addItem
     * 
     * <p>adds an item tag to the manifest file</p>
     * 
     * @param string $identifier unique item identifier
     * @param string $identifierref identifier of a sco
     */
    public function addItem($identifier,$identifierref = null)
    {
       if($identifierref == null)
        $this->xmlStruct .= '<item identifier="'.$identifier.'">';
       else
        $this->xmlStruct .= '<item identifier="'.$identifier.'" identifierref="'.$identifierref.'">';
    }

    /**
     * Summary of getXmlAsString
     * 
     * <p>returns the entire xml document as string</p>
     * 
     * @return string xml document as string
     */
    public function getXmlAsString()
    {
        return $this->xmlStruct.'<resources>'.$this->xmlSco.$this->xmlAssets.'</resources>'.$this->endManifest;
    }

    /**
     * Summary of endManifest
     * 
     * <p>writes the end tag for the manifest file</p>
     * 
     * @return none
     */
    public function endManifest()
    {
        $this->endManifest .= '</manifest>';
    }







 }



?>
