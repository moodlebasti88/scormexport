<?php

class errorLog
{
    private $errormsg;
    
    function errorLog()
    {
        $this->errormsg = '';   
    }
    
    public function writeSyslog($string,$type,$time)
    {
        $formattedTime = date('d m Y H:i:s   ',$time);
        $logfile_location = get_config('scormexport','logfile_location');
        
        switch($type)
        {
            case 'error':
                $handle = fopen($logfile_location.'error.log','w+');
                fwrite($handle,$formattedTime.$string);
                fclose($handle);
                break;
                
            case 'warning':    
                $handle = fopen($logfile_location.'warning.log','w+');
                fwrite($handle,$formattedTime.$string);
                fclose($handle);
                break;
                
            case 'info':
                $handle = fopen($logfile_location.'info.log','w+');
                fwrite($handle,$formattedTime.$string);
                fclose($handle);
                break;
                
            default:
                break;
                
        }
        
           
    }
    
    public function getErrorMsg()
    {
        return $this->errormsg;   
    }
    
    
}

?>