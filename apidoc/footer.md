###  Response

##### Success
	HTTP/1.1 200 OK
    {
		
    }
    
##### Server Error
	HTTP/1.1 500 Server Error
    {
		
    }
    
##### Parameter Error
	HTTP/1.1 400 Parameter Error
    {
		"errorCode":"00" ,
		"errorMessage":"parameter error"
    }	
    
##### Duplication Error
	HTTP/1.1 400 Duplication Error
    {
		"errorCode":"01" ,
		"errorMessage":"duplication"
    }	
    
##### Not Exists Error
	HTTP/1.1 400 Not Exists Error
    {
		"errorCode":"02" ,
		"errorMessage":"not exists"
    }	
    
##### Invalid Error
	HTTP/1.1 400 Invalid Error
    {
		"errorCode":"03" ,
		"errorMessage":"invalid"
    }	
##### AccessToken Error
	HTTP/1.1 400 AccessToken Error
    {
		"errorCode":"04" ,
		"errorMessage":"accesstoken error"
    }	

##### Exists Error
	HTTP/1.1 400 Exists Error
    {
		"errorCode":"05" ,
		"errorMessage":"exists"
    }	
    
<br/>    