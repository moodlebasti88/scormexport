<?php

class quizFuncs
{
    private $content = null;
    
    /**
     * Summary of quizFuncs
     * 
     * <p>constructor of quizFuncs</p>
     * 
     * @param string $init initialization value; '' by default
     */
    function quizFuncs($init = '')
    {
        $this->content = $init;
    }
    
    /**
     * Summary of addQuizFunctions
     * 
     * <p>adds all needed javascript functions for the lesson tests</p>
     * 
     */
    function addQuizFunctions()
    {
        $this->content .= "
                           function sendEssayInput()
                           {
                  
                            doLMSSetValue(\"cmi.interactions.0.type\",\"fill-in\")
                            var input = document.getElementsByName(\"user_eingabe\");
                    
                            doLMSSetValue(\"cmi.comments\",input[0].value);
                            return unloadPage(\"completed\");
                  
                           }
                           
                           function checkAssignResult()
                           {
                            doLMSSetValue(\"cmi.interactions.0.type\",\"matching\");
                  
                            var allAnswers = document.getElementsByName(\"answers\");
                            var allScores = document.getElementsByName(\"scores\");

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
                            doLMSSetValue(\"cmi.core.score.raw\",allAnswers.length.toString());
                            return unloadPage(\"completed\");

                          }
                          
                          function checkTrueFalseResult()
                          {
                            doLMSSetValue(\"cmi.interactions.0.type\",\"true-false\");
                            var radioObj = document.getElementsByName(\"option\");


                            for(var i=0; i<radioObj.length; i++)
                            {
                                if (radioObj[i].checked == true)
                                {
                                    var intVal = parseInt(radioObj[i].value); 
                                    if(intVal >= 1)
                                    {
                                        doLMSSetValue(\"cmi.interactions.0.result\",\"correct\");
                                        doLMSSetValue(\"cmi.core.score.raw\",radioObj[i].value);
                                        return unloadPage(\"completed\");
                                   
                                    }
                                    else
                                    {
                                        doLMSSetValue(\"cmi.interactions.0.result\",\"wrong\");
                                        doLMSSetValue(\"cmi.core.score.raw\",radioObj[i].value);
                                        return unloadPage(\"incomplete\");
                                    }
                                }

                            }

                        }
                        
                        function checkShortAnswerResult()
                        {

                            doLMSSetValue(\"cmi.interactions.0.type\",\"numeric\");
                            var givenAnswer = document.getElementsByName(\"answer\");

                            var correctAnswers = document.getElementsByName(\"correctAnswers\");
                            var correctScores = document.getElementsByName(\"correctScores\");

                            var wrongAnswers = document.getElementsByName(\"wrongAnswers\");
                            var wrongScores = document.getElementsByName(\"wrongScores\");

                            var stringAnswer = givenAnswer[0].value.toString();

                            for(var i=0; i<correctAnswers.length; i++)
                            {

                                if ((stringAnswer.localeCompare(correctAnswers[i].value))==0)
                                {
                                    doLMSSetValue(\"cmi.interactions.0.result\",\"correct\");
                                    doLMSSetValue(\"cmi.core.score.raw\",correctScores[i].value);
                                    return unloadPage(\"completed\");
                                }

                            }
                            for(var i=0; i<wrongAnswers.length; i++)
                            {

                                if ((stringAnswer.localeCompare(wrongAnswers[i].value))==0)
                                {
                                    doLMSSetValue(\"cmi.interactions.0.result\",\"wrong\");
                                    doLMSSetValue(\"cmi.core.score.raw\",wrongScores[i].value);
                                    return unloadPage(\"incomplete\");
                                }

                            }

                            doLMSSetValue(\"cmi.interactions.0.result\",\"wrong\");
                            doLMSSetValue(\"cmi.core.score.raw\",\"0\");
                            return unloadPage(\"incomplete\");


                        }
                        
                        function checkNumericResult()
                        {

                            doLMSSetValue(\"cmi.interactions.0.type\",\"numeric\");
                            var givenAnswer = document.getElementsByName(\"answer\");

                            var correctAnswers = document.getElementsByName(\"correctAnswers\");
                            var correctScores = document.getElementsByName(\"correctScores\");

                            var wrongAnswers = document.getElementsByName(\"wrongAnswers\");
                            var wrongScores = document.getElementsByName(\"wrongScores\");

                            var stringAnswer = givenAnswer[0].value.toString();

                            for(var i=0; i<correctAnswers.length; i++)
                            {

                                if ((stringAnswer.localeCompare(correctAnswers[i].value))==0)
                                {
                                    doLMSSetValue(\"cmi.interactions.0.result\",\"correct\");
                                    doLMSSetValue(\"cmi.core.score.raw\",correctScores[i].value);
                                    return unloadPage(\"completed\");
                                }

                            }
                            for(var i=0; i<wrongAnswers.length; i++)
                            {

                                if ((stringAnswer.localeCompare(wrongAnswers[i].value))==0)
                                {
                                    doLMSSetValue(\"cmi.interactions.0.result\",\"wrong\");
                                    doLMSSetValue(\"cmi.core.score.raw\",wrongScores[i].value);
                                    return unloadPage(\"incomplete\");
                                }

                            }

                            doLMSSetValue(\"cmi.interactions.0.result\",\"wrong\");
                            doLMSSetValue(\"cmi.core.score.raw\",\"0\");
                            return unloadPage(\"incomplete\");


                        }
                        
                        function checkMultChoiceResult()
                        {
                            doLMSSetValue(\"cmi.interactions.0.type\",\"choice\");

                            var responses = document.getElementsByName(\"option\");
                            var results = document.getElementsByName(\"answers\");

                            for(var i=0; i<responses.length; i++)
                            {
                                var intResult = parseInt(results[i].value);
                                if (responses[i].checked == true && intResult > 0)
                                {
                                    doLMSSetValue(\"cmi.interactions.0.result\",\"correct\");
                                    doLMSSetValue(\"cmi.core.score.raw\",results[i].value);
                                    return unloadPage(\"completed\");

                                }
                                if (responses[i].checked == true && intResult == 0)
                                {
                                    doLMSSetValue(\"cmi.interactions.0.result\",\"wrong\");
                                    doLMSSetValue(\"cmi.core.score.raw\",results[i].value);
                                    return unloadPage(\"incomplete\");
                                }

                            }

                    }
                    
                    ";   
    }
    
    /**
     * Summary of getQuizFunctionsAsString
     * 
     * <p>returns all javascript functions as string</p>
     * 
     * @return string $this->content
     */
    public function getQuizFunctionsAsString()
    {
        return $this->content;   
    }
    
}



?>