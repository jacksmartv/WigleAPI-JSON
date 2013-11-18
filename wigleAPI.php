<?php
/**********************************
wigleAPI with CURL by Jack Pelorus
[ jackpelorus@gmail.com ]
for Localizar-t 
Created (25-10-2013) 
Last Modif (31-10-2013)
***********************************/
session_start();
include("simple_html_dom.php");

class wigleAPI { 
    /**********************************
          Parsing DATA TABLE  to JSON
    ***********************************/
    function parseJsonTable($dataTo){
        //return $dataTo;
        $parseDOM = str_get_html($dataTo);
        $tableHead = array();
        $tableData = array(); //$parseDOM->find('table td.launchinner table tr.search td')->plaintext;
        foreach ($parseDOM->find('table td.launchinner table tr.useruploadhead th') as $h) {
            $tableHead[] =  $h->innertext;
        }
        foreach($parseDOM->find('table td.launchinner table tr.search td') as $e){
             $tableData[] =  $e->innertext;
        }
        $resultData = array(); 
            $listData = array_combine($tableHead , $tableData);
            $resultData[] = $listData;
        return $resultData;
        //return $resultParsed;
    }//end of parseJsonTable
    /**********************************
          Parsing DATA TABLE to Array
    ***********************************/
    function parseArrayTable($dataTo){
        $resultData = array();
        $resultParsed = explode("\n",$dataTo);
        $columName = explode("~",$resultParsed[0]);        
        for($i=1;$i<count($resultParsed);$i++){
            $lineData = explode("~", $resultParsed[$i]);
            $listData = array_combine($columName, array_values($lineData));
            $resultData[] = $listData;
        }//end for
        return $resultData;
    }//end of parseArray
    /**********************************
          Parsing DATA to JSON
    ***********************************/
    function parseJson($dataTo){
        $resultData = array();
        $resultParsed = explode("\n",$dataTo);
        $columName = explode("~",$resultParsed[0]);        
        for($i=1;$i<count($resultParsed);$i++){
            $lineData = explode("~", $resultParsed[$i]);
            $listData = array_combine($columName, array_values($lineData));
            $resultData[] = $listData;
        }//end for
        return $resultData ;
        //return $resultData;
    }//end of parseJson
    /**********************************
          Parsing DATA to Array
    ***********************************/
    function parseArray($dataTo){
        $resultData = array();
        $resultParsed = explode("\n",$dataTo);
        $columName = explode("~",$resultParsed[0]);        
        for($i=1;$i<count($resultParsed);$i++){
            $lineData = explode("~", $resultParsed[$i]);
            $listData = array_combine($columName, array_values($lineData));
            $resultData[] = $listData;
        }//end for
        return $resultData;
    }//end of parseArray
    /**********************************
    Parsing headers for login Response
    ***********************************/
    function http_parse_headers($header){  
      $retVal = array();
      $fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
      foreach( $fields as $field ) 
      {
        if(preg_match('/([^:]+): (.+)/m', $field, $match)) 
        {
          $match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower(trim($match[1])));
          if(isset($retVal[$match[1]]))           
            $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
           else        
            $retVal[$match[1]] = trim($match[2]);     
        }
      }
      return $retVal;
    } //end http_parse_headers
    /**********************************
            Wigle Login by CURL 
    ***********************************/
    function wigle_login($user,$pass){     
        $ch = curl_init();
        // set the target url
        curl_setopt($ch, CURLOPT_URL,"https://wigle.net/gps/gps/main/login");
        // how many parameters to post
        curl_setopt($ch, CURLOPT_POST, 3);
        // post parameters
        curl_setopt($ch, CURLOPT_POSTFIELDS,"credential_0=".$user."&credential_1=".$pass."&noexpire=off");      
        curl_setopt ($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLINFO_HEADER_OUT, 1);     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
        curl_setopt($ch, CURLOPT_REFERER, 'https://www.wigle.net/');
        $result = curl_exec($ch);              
        curl_close ($ch); 
        return $result;
    }//end wigle_login
    /**********************************
          Wigle Logout by Cookie 
    ***********************************/
    function wigle_logout(){
        unset($_SESSION["wigle_cookie"]);
        return "logout";
    }
    /**********************************
        Get Wigle Cookie Login 
    ***********************************/
    function getWigleCookie($user,$pass,$noexpire=false) {   
        $cookie = $this->wigle_login($user,$pass); 
        $headers = $this->http_parse_headers($cookie); 
        if (!isset($headers['Set-Cookie']) || empty($headers['Set-Cookie'])) 
          return false;        
        $_SESSION["wigle_cookie"] = $headers['Set-Cookie'];
          return true;
    }//end wigle Cookie
    /**********************************
    Search by Rango LONG - LAT in Wigle
    ***********************************/
    function searchWIGLE($longrange1,$longrange2,$latrange1,$latrange2,$lastupdt,$netid,$reponseType){
        $simple = "simple=true";
        $ch = curl_init();
        // set the target url
        //echo "https://wigle.net/gpsopen/gps/GPSDB/confirmquery/?variance=0.010&longrange2=".$longrange2."&longrange1=".$longrange1."&latrange2=".$latrange2."&latrange1=".$latrange1."&netid=".$netid."&ssid=&".$simple;
        curl_setopt($ch, CURLOPT_URL,"https://wigle.net/gpsopen/gps/GPSDB/confirmquery/"); //?longrange1=".$longrange1."&longrange2=".$longrange2."&latrange1".$latrange1."&latrange2".$latrange2."&".$simple);
        curl_setopt($ch, CURLOPT_COOKIE, $_SESSION["wigle_cookie"]);
        // how many parameters to post
        curl_setopt($ch, CURLOPT_POST, 12);
        // post parameters
        //curl_setopt($ch, CURLOPT_POSTFIELDS,"addresscode=&statecode=&zipcode=&variance=0.010&longrange2=".$longrange2."&longrange1=".$longrange1."&latrange2=".$latrange2."&latrange1=".$latrange1."&netid=".$netid."&ssid=&".$simple);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"variance=0.010&longrange2=".$longrange2."&longrange1=".$longrange1."&latrange2=".$latrange2."&latrange1=".$latrange1."&netid=".$netid."&ssid=&".$simple);
        curl_setopt ($ch, CURLOPT_HEADER, 0);   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
        curl_setopt($ch, CURLOPT_REFERER, 'https://www.wigle.net/');
        $result = curl_exec($ch);               
        curl_close ($ch);
        if($reponseType == 'json'){
          return $this->parseJson($result);
        }//end response type json
       
        if($reponseType == 'array'){  
          return $this->parseArray($result);
        }//end type response array
    }//end searchWigle
    /**********************************
      Search by MAC Address in Wigle
    ***********************************/
    function searchWIGLEbyMAC($netid, $responseType){
        //echo $netid;
        $simple = "simple=true";
        $Query = "Query=Query";
        $ch = curl_init();
        // set the target url https://wigle.net/gpsopen/gps/GPSDB/confirmlocquery?netid=00:30:ab:07:ad:bf&simple=true
        //echo "https://wigle.net/gpsopen/gps/GPSDB/confirmlocquery/?netid=".$netid."&".$simple;
        //curl_setopt($ch, CURLOPT_URL,"https://wigle.net/gpsopen/gps/GPSDB/confirmlocquery"); //?longrange1=".$longrange1."&longrange2=".$longrange2."&latrange1".$latrange1."&latrange2".$latrange2."&".$simple);
        curl_setopt($ch, CURLOPT_URL,"https://wigle.net/gps/gps/main/confirmquery/");
        curl_setopt($ch, CURLOPT_COOKIE, $_SESSION["wigle_cookie"]);
        // how many parameters to post
        curl_setopt($ch, CURLOPT_POST, 2);
        // post parameters
        curl_setopt($ch, CURLOPT_POSTFIELDS, "netid=".$netid."&".$Query);
        curl_setopt($ch, CURLOPT_HEADER, 1);   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
        curl_setopt($ch, CURLOPT_REFERER, 'https://www.wigle.net/');
        $result = curl_exec($ch);               
        curl_close ($ch);
        //print($result);
        //return $result;
        
        if($responseType == 'json'){
          return $this->parseJsonTable($result);        
        }//end response type json
       
        if($responseType == 'array'){  
          return $this->parseArrayTable($result);
        }//end type response array  
        //return $result;
     }//end searchWiglebyMAC

     /**********************************
        Insert Wigle DATA in DB not finish
    ***********************************/
    function insertData($dataInsert){
        // initial database stuff 
        $host = "localhost"; 
        $user = "user"; 
        $pass = "pass"; 
        $db = "dabase_name";

        $connection = mssql_connect($host, $user, $pass) or die ("Unable to connect to DB!"); 
        mssql_select_db($db) or die ("Unable to select database!");

        if($connection){
          echo "Database Conectada!!!";
          $dataDecoded = json_decode($dataInsert);
          for ($i=0; $i < count($dataDecoded); $i++) { 
            $obj = $dataDecoded[$i];
          $query = "INSERT INTO tbl_wigle_cache (
            wc_netid,
            wc_ssid,
            wc_comment,
            wc_name, 
            wc_type, 
            wc_freenet, 
            wc_paynet, 
            wc_firsttime, 
            wc_flags, 
            wc_wep, 
            wc_trilat, 
            wc_trilong, 
            wc_dhcp, 
            wc_lastupdt, 
            wc_channel, 
            wc_bcinterval, 
            wc_qos, 
            wc_active) 
              VALUES(
                '$obj->netid',
                '$obj->ssid',
                '$obj->comment',
                '$obj->name',
                '$obj->type',
                '$obj->freenet',
                '$obj->paynet',
                '$obj->firsttime',
                '$obj->flags',
                '$obj->wep',
                '$obj->trilat',
                '$obj->trilong',
                '$obj->dhcp',
                '$obj->lastupdt',
                '$obj->channel',
                '$obj->bcinterval',
                '$obj->qos',
                '$obj->active')";
            //echo $query;
            mssql_query($query) or die('Insert DATA REJECTED'); 
          }//end for insert
        }//end for test connection
        mssql_close($connection);
        echo "ready importacion men!!";
    }//end function of connect and insert data

}//end class wigleAPI
/***********************
    REST Response
 **********************/
if(isset($_REQUEST)){
$dataRequest = $_REQUEST;
$miWigle = new wigleAPI();
$loginState = array();
error_reporting(0);
if($dataRequest['login'] == "login"){
    $miWigle->getWigleCookie($dataRequest['user'], $dataRequest['pass']);
        if(isset($_SESSION["wigle_cookie"])){
            header('Content-type: application/json');
            $loginState['loginState'] = $dataRequest['user'];
            print(json_encode($loginState));
            
        }else{
            header('Content-type: application/json');
            $loginState['loginState'] = "ERROR";
            print(json_encode($loginState));
        }
    }else if($dataRequest['login'] == "logout"){
        $resLogout = $miWigle->wigle_logout();
        header('Content-type: application/json');
        $loginState['loginState'] = $resLogout;
        print(json_encode($loginState));
    }else if($dataRequest['typeSearch'] == "range" && isset($_SESSION["wigle_cookie"])){
        //add search stuff
        $responseSearch = $miWigle->searchWIGLE($dataRequest['longrange1'],$dataRequest['longrange2'],$dataRequest['latrange1'],$dataRequest['latrange2'],$dataRequest['lastupdt'],$dataRequest['netid'],$dataRequest['responseType']);
       
        $loginState['loginState'] = "OK";
        $loginState['responseSearch'] = $responseSearch;
        header('Content-type: application/json');
        print(json_encode($loginState));
    }else if($dataRequest['typeSearch'] == "mac" && isset($_SESSION["wigle_cookie"])){
        //add search MAC stuff
        $responseSearch = $miWigle->searchWIGLEbyMAC($dataRequest['netid'],$dataRequest['responseType']);
        $loginState['loginState'] = "OK";
        $loginState['responseSearch'] = $responseSearch;
        header('Content-type: application/json');
        print(json_encode($loginState));
    }else{
        header('Content-type: application/json');
        $loginState['loginState'] = "ERROR";
        print(json_encode($loginState));
    }
}else{
    header('Content-type: application/json');
    $loginState['loginState'] = "ERROR";
    print(json_encode($loginState));
}//end request rest response
?>