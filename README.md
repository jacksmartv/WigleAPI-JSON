WigleAPI-JSON
=============

WigleAPI-JSON in PHP with JSON Response

Installation
------------
	1.  Clone repo
	2.  add folder to web root dir of your web server
	3.  and call this URL to view the test GUI "http://your_domain/wigleAPI"

Use
------------

	The wigleAPI.php is the Core class API.
	you can request this API directly by POST or GET
	with AJAX or CURL and get the response in 
	JSON or ARRAY format

**wigleAPI.php entry point of API**

Methods
-------
**Login**

- login (string) = login
- user (string) = your_user_name_wigle
- pass (string) = your_pass_wigle

**Example login by GET Request**(unsecure but available)

	http://your_domain/wigleAPI.php?login=login&user=your_user&pass=your_pass
	
**Logout**

- login (string) = logout

**Example logout by GET Request**(unsecure but available)

	http://your_domain/wigleAPI.php?login=logout

**Search by Range**
		
- longrange1   (string)  = Minimum longitude for square of area being queried.	-89.54321
- latrange1    (string)  = Minimum latitude for square of area being queried.	41.12345
- longrange2   (string)  = Maximum longitude for square of area being queried.	-89.12345
- latrange2    (string)  = Maximum latitude for square of area being queried.	41.54321
- typeSearch   (string)  = { mac or range }
- responseType (string)  = { json or array }

**Search by MAC Address**
		
-  netid (string) = MAC Address		
- typeSearch   (string)  = { mac or range }
- responseType (string)  = { json or array }

**Example Search by MAC Address by GET Request**(unsecure but available)

	http://your_domain/wigleAPI.php?netid=0A:2C:EF:3D:25:1B&typeSearch=mac&responseType=json


**Display Results**

- [ jackpelorus.com.ar See in Action !!! ] (http://jackpelorus.com.ar/wigleAPI)
	
	
Test GUI integrated
-------------------

**http://your_domain/wigleAPI**
