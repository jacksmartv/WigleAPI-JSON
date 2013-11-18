WigleAPI-JSON
=============

WigleAPI-JSON in PHP with JSON Response

Installation
------------
		
		Clone repo
		add folder to web root dir of your web server
		and call this URL to view the test GUI
		http://your_domain/wigleAPI

Use
------------
	
	

	The wigleAPI.php is the Core class API.
	you can request this API directly by POST or GET
	and get the response in JSON or ARRAY

	### wigleAPI.php entry point of API
	### Login 
		
		* login = login
		* user = your_user_name_wigle
		* pass = your_pass_wigle
	
	### Logout

		* login = logout
	
	### Serach by Range 
		* longrange1 
		* latrange1
		* longrange2
		* latrange2
	### Search by MAC Address
		
		* netid = MAC Address		
	
	### Display Results

	*  JSON RESULT PAGE
	*[jackpelorus.com.ar] jackpelorus.com.ar
	
Test GUI integrated
-------------------
	
	http://your_domain/wigleAPI