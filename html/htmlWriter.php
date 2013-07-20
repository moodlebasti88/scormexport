<?php
/*
 * Created on 01.06.2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class htmlWriter
{
    private $htmlString;

    /**
     * Summary of htmlWriter
     * 
     * <p>constructor of htmlWriter</p>
     */
    public function htmlWriter()
    {
        $this->htmlString = '';
    }

    /**
     * Summary of openTag
     * 
     * <p>opens a tag</p>
     * 
     * @param string $tagName name of the tag
     * @param string $attr optional parameter if the tag contains attributes
     */
    public function openTag($tagName,$attr='')
    {
        $this->htmlString .= '<'.$tagName.' '.$attr.'>';
    }

    /**
     * Summary of closeTag
     * 
     * <p>close a tag</p>
     * 
     * @param string $tagName name of the tag which should be closed
     */
    public function closeTag($tagName)
    {
        $this->htmlString .= '</'.$tagName.'>';
    }

    /**
     * Summary of addJsFile
     * 
     * <p>adds an external javascript file</p>
     * 
     * @param string $type type of the script language; in this case: javascript
     * @param string $src path to the external file
     */
    public function addJsFile($type,$src)
    {
        $this->htmlString .= '<script type="'.$type.'" src="'.$src.'"></script>';

    }

    /**
     * Summary of addString
     * 
     * <p>adds a string to the html document</p>
     * 
     * @param string $string string that should be added
     */
    public function addString($string)
    {
        $this->htmlString .= $string;

    }

    /**
     * Summary of addForm
     * 
     * <p>adds a formular to the html code</p>
     * 
     * @param string $formName name of the form
     * @param string $action event if formular is submitted
     */
    public function addForm($formName,$action)
    {
        $this->htmlString .= '<form name="'.$formName.'" action="'.$action.'">';
    }

    /**
     * Summary of addInputTag
     * 
     * <p>adds a new input element to a form</p>
     * 
     * @param string $type e.g. text,number,button...
     * @param string $name name of the input 
     * @param string $value content of the input element
     * @param string $onClick action if clicked
     */
    public function addInputTag($type,$name,$value,$onClick = null)
    {
        if($onClick == null)
            $this->htmlString .= '<input type="'.$type.'" name="'.$name.'" value="'.$value.'">';
        else
            $this->htmlString .= '<input type="'.$type.'" name="'.$name.'" value="'.$value.'" onclick="'.$onClick.'">';

    }

    /**
     * Summary of addNewline
     * 
     * <p>adds a newline</p>
     */
    public function addNewline()
    {
        $this->htmlString .= '<br/>';
    }
    
    /**
     * Summary of createDropdown
     * 
     * <p>creates a dropdown menu</p>
     * 
     * @param array[] $values list elements of the dropdown menu
     * @param string $answer in this case: correct answer; otherwise id and identifier
     */
    public function createDropdown($values,$answer)
    {
        $this->htmlString .= '<select id="'.$answer.'" size="1">';   
        
        for($i=0; $i<sizeof($values); $i++)
        {
            $this->htmlString .= '<option value="'.$answer.'">'.$values[$i].'</option>';
        }
        
        $this->htmlString .= '</select>';
    }

    /**
     * Summary of getHtmlString
     * 
     * <p>returns the html code as string</p>
     * 
     * @return string $this->htmlString
     */
    public function getHtmlString()
    {
        return $this->htmlString;
    }


}
?>
