<?php
/*
 * Created on 05.06.2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class apiWrapper12
 {

    private $content =  null;


    /**
     * Summary of apiWrapper12
     * 
     * <p>constructor of the api wrapper</p>
     * 
     * @param $init
     */
    function apiWrapper12($init = '')
    {
        $this->content = $init;

    }

    /**
     * Summary of addWrapperFunctions
     * 
     * <p>contains scorm api wrapper functions for scorm 1.2</p>
     * 
     */
    public function addWrapperFunctions()
    {
        $this->content .= 'var _Debug = false;  // set this to false to turn debugging off
                                                // and get rid of those annoying alert boxes.

                            // Define exception/error codes
                            var _NoError = 0;
                            var _GeneralException = 101;
                            var _ServerBusy = 102;
                            var _InvalidArgumentError = 201;
                            var _ElementCannotHaveChildren = 202;
                            var _ElementIsNotAnArray = 203;
                            var _NotInitialized = 301;
                            var _NotImplementedError = 401;
                            var _InvalidSetValue = 402;
                            var _ElementIsReadOnly = 403;
                            var _ElementIsWriteOnly = 404;
                            var _IncorrectDataType = 405;


                            // local variable definitions
                            var apiHandle = null;
                            var API = null;
                            var findAPITries = 0;

                            function doLMSInitialize()
                            {
                                var api = getAPIHandle();
                                if (api == null)
                                {
                                    alert("Unable to locate the LMS API Implementation.\nLMSInitialize was not successful.");
                                    return "false";
                                }

                                var result = api.LMSInitialize("");

                                if (result.toString() != "true")
                                {
                                    var err = ErrorHandler();
                                }

                                return result.toString();
                            }

                            function doLMSFinish()
                            {
                                var api = getAPIHandle();
                                if (api == null)
                                {
                                    alert("Unable to locate the LMS API Implementation.\nLMSFinish was not successful.");
                                    return "false";
                                }
                                else
                                {
                                    // call the LMSFinish function that should be implemented by the API

                                    var result = api.LMSFinish("");
                                    if (result.toString() != "true")
                                    {
                                        var err = ErrorHandler();
                                    }

                                }

                                return result.toString();
                            }

                            function doLMSGetValue(name)
                            {
                                var api = getAPIHandle();
                                if (api == null)
                                {
                                    alert("Unable to locate the LMS API Implementation.\nLMSGetValue was not successful.");
                                    return "";
                                }
                                else
                                {
                                    var value = api.LMSGetValue(name);
                                    var errCode = api.LMSGetLastError().toString();
                                    if (errCode != _NoError)
                                    {
                                        // an error was encountered so display the error description
                                        var errDescription = api.LMSGetErrorString(errCode);
                                        alert("LMSGetValue("+name+") failed. \n"+ errDescription);
                                        return "";
                                    }
                                    else
                                    {

                                        return value.toString();
                                    }
                                }
                            }

                            function doLMSSetValue(name, value)
                            {
                                var api = getAPIHandle();
                                if (api == null)
                                {
                                    alert("Unable to locate the LMS API Implementation.\nLMSSetValue was not successful.");
                                    return;
                                }
                                else
                                {
                                    var result = api.LMSSetValue(name, value);
                                    if (result.toString() != "true")
                                    {
                                        var err = ErrorHandler();
                                    }
                                }

                                return;
                            }

                            function doLMSCommit()
                            {
                                var api = getAPIHandle();
                                if (api == null)
                                {
                                    alert("Unable to locate the LMS API Implementation.\nLMSCommit was not successful.");
                                    return "false";
                                }
                                else
                                {
                                    var result = api.LMSCommit("");
                                    if (result != "true")
                                    {
                                        var err = ErrorHandler();
                                    }
                                }

                                return result.toString();
                            }

                            function doLMSGetLastError()
                            {
                                var api = getAPIHandle();
                                if (api == null)
                                {
                                    alert("Unable to locate the LMS API Implementation.\nLMSGetLastError was not successful.");
                                    //since we can get the error code from the LMS, return a general error
                                    return _GeneralError;
                                }

                                return api.LMSGetLastError().toString();
                            }

                            function doLMSGetErrorString(errorCode)
                            {
                                var api = getAPIHandle();
                                if (api == null)
                                {
                                    alert("Unable to locate the LMS API Implementation.\nLMSGetErrorString was not successful.");
                                }

                                return api.LMSGetErrorString(errorCode).toString();
                            }

                            function doLMSGetDiagnostic(errorCode)
                            {
                                var api = getAPIHandle();
                                if (api == null)
                                {
                                    alert("Unable to locate the LMS API Implementation.\nLMSGetDiagnostic was not successful.");
                                }

                                return api.LMSGetDiagnostic(errorCode).toString();
                            }

                            function LMSIsInitialized()
                            {
                                // there is no direct method for determining if the LMS API is initialized
                                // for example an LMSIsInitialized function defined on the API so we try
                                // a simple LMSGetValue and trap for the LMS Not Initialized Error

                                var api = getAPIHandle();
                                if (api == null)
                                {
                                    alert("Unable to locate the LMS API Implementation.\nLMSIsInitialized() failed.");
                                    return false;
                                }
                                else
                                {
                                    var value = api.LMSGetValue("cmi.core.student_name");
                                    var errCode = api.LMSGetLastError().toString();
                                    if (errCode == _NotInitialized)
                                    {
                                        return false;
                                    }
                                    else
                                    {
                                        return true;
                                    }
                                }
                            }

                            function ErrorHandler()
                            {
                                var api = getAPIHandle();
                                if (api == null)
                                {
                                    alert("Unable to locate the LMS API Implementation.\nCannot determine LMS error code.");
                                    return;
                                }

                                // check for errors caused by or from the LMS
                                var errCode = api.LMSGetLastError().toString();
                                if (errCode != _NoError)
                                {
                                    // an error was encountered so display the error description
                                    var errDescription = api.LMSGetErrorString(errCode);

                                    if (_Debug == true)
                                    {
                                        errDescription += "\n";
                                        errDescription += api.LMSGetDiagnostic(null);
                                        // by passing null to LMSGetDiagnostic, we get any available diagnostics
                                        // on the previous error.
                                    }

                                    alert(errDescription);
                                }

                                return errCode;
                            }



                            function getAPIHandle()
                            {
                                if (apiHandle == null)
                                {
                                    apiHandle = getAPI();
                                }

                                return apiHandle;
                            }

                            function findAPI(win)
                            {
                                while ((win.API == null) && (win.parent != null) && (win.parent != win))
                                {
                                    findAPITries++;
                                    // Note: 7 is an arbitrary number, but should be more than sufficient
                                    if (findAPITries > 7)
                                    {
                                        alert("Error finding API -- too deeply nested.");
                                        return null;
                                    }

                                    win = win.parent;

                                }
                                return win.API;
                            }


                            function getAPI()
                            {
                                var theAPI = findAPI(window);
                                if ((theAPI == null) && (window.opener != null) && (typeof(window.opener) != "undefined"))
                                {
                                    theAPI = findAPI(window.opener);
                                }
                                if (theAPI == null)
                                {
                                    alert("Unable to find an API adapter");
                                }
                                return theAPI
                            }';

    }

    /**
     * Summary of getWrapperFileAsString
     * 
     * <p>returns all defined wrapper functions as string</p>
     * 
     * @return string $this->content
     */
    public function getWrapperFileAsString()
    {
        return $this->content;

    }

 }


?>
