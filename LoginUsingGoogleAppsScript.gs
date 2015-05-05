/*
This is a sample Google Apps Script for logging into a WSO2 Identity Server Protected Resource. It retrieves the SAML Assertions from the WSO2 Identity Server and then uses the SAML Response to make HTTP GET/POST requests.

*/
function WSO2SAMLLogin() {
  
  var USERNAME = "user@domain.com";
  var PASSWORD = "password";
  var RESOURCE_URL = "https://app.domain.com/home.jsp";
  var WSO2_IS_URL = "https://is.domain.com/commonauth";
  
  var response = UrlFetchApp.fetch(RESOURCE_URL);
  
  if (response.getResponseCode()==200) {
    
    
    var sessionDataKey = response.getContentText().split("sessionDataKey\" value='")[1].split("'/>")[0];
    var headers = {
      'password': PASSWORD,
      'username': USERNAME,
      'sessionDataKey': sessionDataKey
    };
    
    var options = {
      "method": "POST",
      'payload': headers
      
    }
    
    var response = UrlFetchApp.fetch(WSO2_IS_URL, options);
    
    var SAMLResponse = response.getContentText().split("'SAMLResponse' value='")[1].split("'>")[0];
    
    options = {
      "method": "POST",
      "payload": {
        "SAMLResponse": SAMLResponse
      }
    }
    var response = UrlFetchApp.fetch(RESOURCE_URL, options);
	
	  // Use response as needed
  }
  else{
    Logger.log("Connection failed");
  }
  
  
}
