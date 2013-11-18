<!DOCTYPE html>
<html>
<head>
 <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
 <title>Wigle API Localizar-T 2013</title>
 <link rel="stylesheet" type="text/css" href="./lib/bootstrap.min.css">
 <script type="application/javascript" src="./lib/jquery-1.10.2.min.js"></script>
 <script type="application/javascript" src="./lib/serialize-json.jquery.js"></script>
 <script type="application/javascript">

  function login(userData){
    console.log(userData);
    $.ajax({ 
      type:"POST",
      url:window.location.href+"wigleAPI.php",
      data:userData,
      complete: function(data, textStatus, jqXHR){
          //console.log(userData.user+"useruseruser");
        var myuser = data.responseJSON;
          //console.log(myuser)
        if(myuser.loginState == userData.user){
          //console.log(data);
          $('#login').append('<input type="button" value="Logout" id="buttonlogout" onclick="logout();return false;" class="btn btn-large">');
          $('#loginForm').css('display','none');
          $('#query').css('display','block');
        }else{
          console.log(data+"Error");
        }
      },
      dataType:"json"
      });
  }

  function logout(){
      $.ajax({ 
      type:"POST",
      url:window.location.href+"wigleAPI.php",
      data:{"login":"logout"},
      complete: function(data, textStatus, jqXHR){
          //console.log(userData.user+"useruseruser");
        var mylogout = data.responseJSON;
          //console.log(myuser)
        if(mylogout.loginState == "logout"){
          //console.log(data);
          $('#buttonlogout').remove();
          $('#loginForm').css('display','block');
          $('#query').css('display','none');
        }else{
          console.log(data+"Error");
        }
      },
      dataType:"json"
      });
  }
  

  function search(searchData){
    console.log(searchData);
    $('#queryString').text(searchData.netid);
    

    $.ajax({ 
      type:"POST",
      url:window.location.href+"wigleAPI.php",
      data:searchData,
      complete: function(data, textStatus, jqXHR){
          var mySearch = data.responseJSON;
       if(mySearch.loginState == "OK"){
          $('#results').css('display','block');
          $('#resultsData').html('');
          console.log(mySearch.responseSearch[0]);
          $.each(mySearch.responseSearch[0], function (i, fb) {
            $('#resultsData').append('<h2>'+ i +'------'+fb+'</h2> <br />');
          });
        }else{
          console.log(data+"Error");
        }
      },
      dataType:"json"
      });
  }
  //function initialize Web Interface Wigle API
  $(function() {
      $('#query').css('display','none');
      $('#results').css('display','none');
      $('#buttonlogin').click(function(){
          var userData = $('#loginForm').serializeJSON();
          login(userData);
      });
      $('#submitQuery').click(function(){
          var searchData = $('#formQuery').serializeJSON();
          search(searchData);
      });
      $('#submitQueryMac').click(function(){
          var searchData = $('#formMACQuery').serializeJSON();
          search(searchData);
      });
  });//end initialize Web Interface Wigle API
 </script>
 </head>
<body>
  <div id="outer" class="container">
  <div id="top"></div>
  <div id="inner">
   <div id="header" class="page-header"><h1>Wigle API</h1></div>
   <div id="navigation">
    <!--<ul>
     <li class="current"><a href="#">By Search</a></li>
     <li><a href="#">By Name</a></li>
     <li><a href="#">By MAC</a></li>
    </ul>-->
   </div>
   <div id="content">
    <div id="login">
     
      <fieldset>
        <form id="loginForm" name="loginForm" action="" method="GET" class="form-horizontal" >
          <div class="form-group">
            <label class="col-sm-2 control-label">User:</label>
              <div class="col-sm-10">
                <input type="text" name="user" id="user" size="10" maxlength="10" class="form-control"><br /> 
              </div>
            <label class="col-sm-2 control-label">Password:</label>
            <div class="col-sm-10">
              <input type="password" name="pass" id="pass" size="10" maxlength="32" class="form-control"><br /><br />
            </div>
            <input type="hidden" value="login" name="login" id="login">
            <input type="checkbox" name="noexpire" class="checkbox"> Don\'t expire auth cookie 
            <input type="button" value="Login" id="buttonlogin" class="btn btn-default"><br />
            </div>
        </form>
      </fieldset>
    </div>

      <div id="query">
        <h2>Ingrese Datos</h2>
        <fieldset> <h2>By GPS cords.</h2>
          <form id="formQuery" name="formQuery" action="" method="GET" class="form-inline">
            <label>Longitud X </label><input type="text" id="longrange1" name="longrange1" class="input-medium">
            <label>Longitud Y </label><input type="text" id="longrange2" name="longrange2" class="input-medium">
            <label>Latitud X </label><input type="text" id="latrange1" name="latrange1" class="input-medium">
            <label>Latitud Y </label><input type="text" id="latrange2" name="latrange2" class="input-medium">
            <label>Fecha Updt </label><input type="text" id="lastupdt" name="lastupdt" class="input-medium">
            <!--<label>MAC Address</label><input type="text" id="netid" name="netid">-->
            <input type="hidden" value="range" name="typeSearch">
            <label>JSON </label><input type="checkbox" value="json" id="responseType" name="responseType" class="checkbox">
            <label>Array </label><input type="checkbox" value="array" id="responseType" name="responseType" class="checkbox">
            <input type="button" id="submitQuery" name="submitQuery" value="Query" class="btn btn-large">
          </form>
        </fieldset>
          
        <fieldset>
          <form id="formMACQuery" name="formMACQuery" action="" method="GET" class="form-inline">
            <label>MAC Address </label><input type="text" id="netid" name="netid" class="input-medium">
            <label>JSON </label><input type="checkbox" value="json" id="responseType" name="responseType" class="checkbox">
            <label>Array </label><input type="checkbox" value="array" id="responseType" name="responseType" class="checkbox">
            <input type="hidden" value="mac" name="typeSearch">
            <input type="button" id="submitQueryMac" value="Query MAC" class="btn btn-large">
          </form>
        </fieldset>
        <h2>Query Parsed</h2>
        <div id="queryString" style="width:500px;height:50px;border: 1px solid grey; color:blue;"></div>
    </div>
   	<div id="results">
		  <h2>Results:</h2></br>
      <div id="resultsData"></div>
    </div>
  </div>
    <div id="footer"><a href="#top">Top of page</a></div>
  </div>
  <div id="bottom"></div>
</div>
</body>
</html>