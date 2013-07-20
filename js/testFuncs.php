<?php

class testFuncs
{
    /**
     * @var mixed $content holds all needed javascript functions as string
     */
    private $content = null;
    
    /**
     * Summary of testFuncs
     * 
     * <p>constructor of testFuncs</p>
     * 
     * @param string $init initialization value for content; '' by default
     */
    function testFuncs($init = '')
    {
        $this->content = $init;
    }
    
    /**
     * Summary of addTestFunctions
     * 
     * <p>adds the javascript functions for the test learning module</p>
     */
    public function addTestFunctions()
    {
        $this->content .= "
                          function checkMultChoiceTest()
                          {
                            doLMSSetValue(\"cmi.interactions.0.type\",\"choice\");
                            
                            
                            var maxScore = document.getElementsByName(\"maxScore\");
                            var options = document.getElementsByName(\"option\");
                            
                            var floatMaxScore = parseFloat(maxScore[0].value);
                            var totalScore = 0;
                            
                            
                            
                             for(var i=0; i<options.length; i++)
                             {
                                var floatResult = parseFloat(options[i].value);
                                var result = Math.round(floatMaxScore*floatResult);
                               
                                
                                if (options[i].checked == true && result > 0)
                                {
                                    totalScore = totalScore + result; 
                                }
                           
                            }
                            
                            
                            if(totalScore == floatMaxScore)
                            {
                               doLMSSetValue(\"cmi.interactions.0.result\",\"correct\");
                               doLMSSetValue(\"cmi.core.score.raw\",totalScore.toString());    
                               return unloadPage(\"completed\");
                            }
                            if(totalScore < floatMaxScore)
                            {
                                doLMSSetValue(\"cmi.interactions.0.result\",\"wrong\");
                                doLMSSetValue(\"cmi.core.score.raw\",totalScore.toString());
                                return unloadPage(\"incomplete\");
                            }
                          
                          }
                          
                          function checkNumericalTest()
                          {
                            doLMSSetValue(\"cmi.interactions.0.type\",\"numeric\");
                            
                            var maxScore = document.getElementsByName(\"maxScore\");
                            
                            var tolerance = document.getElementsByName(\"tolerance\");
                            var fraction = document.getElementsByName(\"fraction\");
                            var answers =  document.getElementsByName(\"answers\");                         
                            var givenAnswer = document.getElementsByName(\"result\");
                            
                            var floatResult = parseFloat(givenAnswer[0].value);
                            var totalScore = 0;
                            
                            for(var i=0; i<answers.length; i++)
                            {
                                var floatAnswer = parseFloat(answers[i].value);
                                var min = floatAnswer - parseFloat(tolerance[i].value);
                                var max = floatAnswer + parseFloat(tolerance[i].value);
                                
                                if(floatResult >= min && floatResult <= max)
                                {
                                    var score = Math.round(parseFloat(fraction[i].value) * parseFloat(maxScore[0].value));
                                    totalScore += score;
                                }
                            
                            }
                            
                            if(totalScore == parseFloat(maxScore[0].value))
                            {
                               doLMSSetValue(\"cmi.interactions.0.result\",\"correct\");
                               doLMSSetValue(\"cmi.core.score.raw\",totalScore.toString());    
                               return unloadPage(\"completed\");
                            }
                            if(totalScore < parseFloat(maxScore[0].value))
                            {
                                doLMSSetValue(\"cmi.interactions.0.result\",\"wrong\");
                                doLMSSetValue(\"cmi.core.score.raw\",totalScore.toString());
                                return unloadPage(\"incomplete\");
                            }
                           
                        }
                        
                        function checkShortanswerTest()
                        {
                            doLMSSetValue(\"cmi.interactions.0.type\",\"numeric\");
                        
                            var maxScore = document.getElementsByName(\"maxScore\");
                            
                            var fractions = document.getElementsByName(\"fractions\");
                            var answers = document.getElementsByName(\"answers\");
                            var givenAnswer = document.getElementsByName(\"result\");
                            
                            var totalScore = 0;
                            
                            for(var i=0; i<answers.length; i++)
                            {
                                if(givenAnswer[0].value.localeCompare(answers[i].value) == 0)
                                {
                                    var score = Math.round(parseFloat(maxScore[0].value) * parseFloat(fractions[i].value));
                                    totalScore += score;
                                }
                            
                            }
                            
                            if(totalScore == parseFloat(maxScore[0].value))
                            {
                               doLMSSetValue(\"cmi.interactions.0.result\",\"correct\");
                               doLMSSetValue(\"cmi.core.score.raw\",totalScore.toString());    
                               return unloadPage(\"completed\");
                            }
                            if(totalScore < parseFloat(maxScore[0].value))
                            {
                                doLMSSetValue(\"cmi.interactions.0.result\",\"wrong\");
                                doLMSSetValue(\"cmi.core.score.raw\",totalScore.toString());
                                return unloadPage(\"incomplete\");
                            }
                            
                            
                        
                        }
                        
                        function checkTrueFalseTest()
                        {
                            doLMSSetValue(\"cmi.interactions.0.type\",\"true-false\");
                            
                            var maxScore = document.getElementsByName(\"maxScore\");
                            var fractions = document.getElementsByName(\"fractions\");
                            
                            var totalScore = 0;
                            
                            for(var i=0; i<fractions.length; i++)
                            {
                                if(fractions[i].checked == true)
                                {
                                    var score = Math.round(parseFloat(maxScore[0].value) * parseFloat(fractions[i].value));
                                    totalScore += score;
                                }
                            }
                            
                            if(totalScore == parseFloat(maxScore[0].value))
                            {
                               doLMSSetValue(\"cmi.interactions.0.result\",\"correct\");
                               doLMSSetValue(\"cmi.core.score.raw\",totalScore.toString());    
                               return unloadPage(\"completed\");
                            }
                            if(totalScore < parseFloat(maxScore[0].value))
                            {
                                doLMSSetValue(\"cmi.interactions.0.result\",\"wrong\");
                                doLMSSetValue(\"cmi.core.score.raw\",totalScore.toString());
                                return unloadPage(\"incomplete\");
                            }
                        
                        }
                        
                        function checkMatchingTest()
                        {
                            doLMSSetValue(\"cmi.interactions.0.type\",\"matching\");
                  
                            var maxScore = document.getElementsByName(\"maxScore\");  
                            var allAnswers = document.getElementsByName(\"answers\");
                            
                            var intMaxScore = parseInt(maxScore[0].value);
                          
                            for(var i=0; i<allAnswers.length; i++)
                            {
                                var x=document.getElementById(allAnswers[i].value).selectedIndex;
                                var y=document.getElementById(allAnswers[i].value).options;
                                
                               
                                if(y[x].text.localeCompare(allAnswers[i].value) != 0)
                                {
                                    doLMSSetValue(\"cmi.interactions.0.result\",\"wrong\");
                                    doLMSSetValue(\"cmi.core.score.raw\",\"0\");
                                    return unloadPage(\"incomplete\");
                                }
                                
                            }
                            
                           
                            doLMSSetValue(\"cmi.interactions.0.result\",\"correct\");
                            doLMSSetValue(\"cmi.core.score.raw\",intMaxScore.toString());
                            return unloadPage(\"completed\");
                        
                        }
                        
                        function checkEssayTest()
                        {
                            var maxScore = document.getElementsByName(\"maxScore\");
                        
                            doLMSSetValue(\"cmi.interactions.0.type\",\"fill-in\")
                            var input = document.getElementsByName(\"user_eingabe\");
                    
                            doLMSSetValue(\"cmi.comments\",input[0].value);
                           
                            
                            return unloadPage(\"completed\");
                        }";   
    }
    
    /**
     * Summary of getTestFunctionsAsString
     * 
     * <p>returns all needed javascript functions for the test module as string</p>
     * 
     * @return string $this->content
     */
    public function getTestFunctionsAsString()
    {
        return $this->content;   
        
    }
    
    
}

?>