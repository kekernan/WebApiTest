 <?php
 
   // Enter the MAP Server URL be sure to not include http:// or https:// 
   // For example your_url.com exlcude the http:// 
   $MAPSUrl = "evisions1.cc.lehigh.edu:8081"; // use your_url.com
    
   // Enter MAPS username and password
   $username = "";
   $password = "";
    
   // Enter the datablock number, you can find it in Argos webviewer
   $datablock = $_GET['db'];

   // Enter the title of the web page.
   // This title will be displayed.
   $site_title = "Argos Web Viewer API";
   
   // Hash variable is used to pass parameter through the API. 
   // Only a edit box variable named incoming_hash_txt can receive the hash variable.
   $hash = $_GET['id'];    
   
   // First call to get the Session ID
   $Sessionurl = "https://" . $MAPSUrl . "/mw/Session.Setup?Version=4.3&JSONData={\"Mapplets\":[{\"Guid\":\"B052A35E-DC3B-4283-B732-7BEE3B095C5E\",\"Version\":\"4.3\"}]}";

   // Initialize curl object and setting options
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $Sessionurl);
   curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

   // Execute cURL and decodes the returned JSON
   // Store contents of the file in memory
   $json = json_decode(curl_exec($ch));
   
   // Separate the Session ID 
   $sessionId = $json->data->SessionId;
    
   // Second call to Authenticate
   $AuthURL = "https://".$MAPSUrl."/mw/Session.Authenticate?username=".$username."&password=".$password."&sessionid=".$sessionId;
   
   // Initialize curl object and setting options
   // and executes
   $ah = curl_init();
   curl_setopt($ah, CURLOPT_URL, $AuthURL );
   curl_setopt($ah, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
   curl_setopt($ah, CURLOPT_FOLLOWLOCATION, true);
   curl_setopt($ah, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ah, CURLOPT_RETURNTRANSFER, true);
   curl_exec($ah);
   
   //Third call to get the token 
   $TokenURL = "https://".$MAPSUrl."/mw/Session.SecurityToken.Create?sessionid=".$sessionId;
   
   // Initialize curl object and setting options
   $gt = curl_init();
   curl_setopt($gt, CURLOPT_URL, $TokenURL);
   curl_setopt($gt, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
   curl_setopt($gt, CURLOPT_FOLLOWLOCATION, true);
   curl_setopt($gt, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($gt, CURLOPT_RETURNTRANSFER, true);
   
   // Execute cURL and decodes the returned JSON
   // Store contents of the file in memory
   $json = json_decode(curl_exec($gt));
   
   // Separate the Token 
   $token = $json->data->Token;

   // Close all cURL sessions
   curl_close($ch);
   curl_close($ah);
   curl_close($gt);

   //Now that we have the token we can go to our page. Note the Hash value was passed to the incoming_hash_txt edit box 
   //$url =  "https://".$MAPSUrl."/argos/awv/#DataBlock=".$datablock."&Token=".$token."&Username=".$username."&Hash=".$hash."&RunBar=false";
   $url =  "https://".$MAPSUrl."/argos/awv/#DataBlock=".$datablock."&Hash=".$hash."&RunBar=false";

   //Display URL link
   header('Location: '.$url);


 ?> 
 



