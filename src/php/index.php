<?php
////////////////////////////////////////////////////////////////////////////////
//BOCA Online Contest Administrator
//    Copyright (C) 2003-2012 by BOCA Development Team (bocasystem@gmail.com)
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
////////////////////////////////////////////////////////////////////////////////
// Last modified 05/aug/2012 by cassio@ime.usp.br

ob_start();
header ("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-Type: text/html; charset=utf-8");
session_start();
$_SESSION["loc"] = dirname($_SERVER['PHP_SELF']);
if($_SESSION["loc"]=="/") $_SESSION["loc"] = "";
$_SESSION["locr"] = dirname(__FILE__);
if($_SESSION["locr"]=="/") $_SESSION["locr"] = "";

require_once("globals.php");
require_once("db.php");

if (!isset($_GET["name"])) {
	if (ValidSession())
	  DBLogOut($_SESSION["usertable"]["contestnumber"], 
		   $_SESSION["usertable"]["usersitenumber"], $_SESSION["usertable"]["usernumber"],
		   $_SESSION["usertable"]["username"]=='admin');
	session_unset();
	session_destroy();
	session_start();
	$_SESSION["loc"] = dirname($_SERVER['PHP_SELF']);
	if($_SESSION["loc"]=="/") $_SESSION["loc"] = "";
	$_SESSION["locr"] = dirname(__FILE__);
	if($_SESSION["locr"]=="/") $_SESSION["locr"] = "";
}
if(isset($_GET["getsessionid"])) {
	echo session_id();
	exit;
}
ob_end_flush();

require_once('version.php');

?>
<title>BOCA Online Contest Administrator <?php echo $BOCAVERSION; ?> - Login</title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet href="Css.php" type="text/css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
<script language="JavaScript" src="sha256.js"></script>
<script language="JavaScript">
function computeHASH()
{
	var userHASH, passHASH;
	userHASH = document.form1.name.value;
	passHASH = js_myhash(js_myhash(document.form1.password.value)+'<?php echo session_id(); ?>');
	document.form1.name.value = '';
	document.form1.password.value = '                                                                                 ';
	document.location = 'index.php?name='+userHASH+'&password='+passHASH;
}
</script>
<?php
if(function_exists("globalconf") && function_exists("sanitizeVariables")) {
  if(isset($_GET["name"]) && $_GET["name"] != "" ) {
	$name = $_GET["name"];
	$password = $_GET["password"];
	$usertable = DBLogIn($name, $password);
	if(!$usertable) {
		ForceLoad("index.php");
	}
	else {
		if(($ct = DBContestInfo($_SESSION["usertable"]["contestnumber"])) == null)
			ForceLoad("index.php");
		if($ct["contestlocalsite"]==$ct["contestmainsite"]) $main=true; else $main=false;
		if(isset($_GET['action']) && $_GET['action'] == 'transfer') {
			echo "TRANSFER OK";
		} else {
			if($main && $_SESSION["usertable"]["usertype"] == 'site') {
				MSGError('Direct login of this user is not allowed');
				unset($_SESSION["usertable"]);
				ForceLoad("index.php");
				exit;
			}
			echo "<script language=\"JavaScript\">\n";
			echo "document.location='" . $_SESSION["usertable"]["usertype"] . "/index.php';\n";
			echo "</script>\n";
		}
		exit;
	}
  }
} else {
  echo "<script language=\"JavaScript\">\n";
  echo "alert('Unable to load config files. Possible file permission problem in the BOCA directory.');\n";
  echo "</script>\n";
}
?>
</head>
<body onload="document.form1.name.focus()">
<section id="login" class="section d-flex justify-content-center align-items-center">
        <div class="container h-90-vh">
            <div class="row h-100 box-s-container justify-content-center">
                <div class="col-12 col-sm-10 col-md-7 col-lg-8 col-lg-8 d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 text-l-center text-center">
                                <h4>Iniciar sesión</h4>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12 col-lg-9">
                                <form name="form1" action="javascript:computeHASH()" id="formLogin">
                                    <div class="form-group">
                                        <label for="formEmail"></label>
                                        <input type="text" name="name" class="form-control form-line" id="formLoginUser"
                                            placeholder="Usuario">
                                    </div>
                                    <div class="form-group">
                                        <label for="formPassword"></label>
                                        <input type="password" class="form-control form-line" name="password" id="formLoginPassword"
                                            placeholder="Contraseña">
                                    </div>
                                    <div class="form-group d-flex justify-content-center">
                                        <input type="submit" name="Submit" class="btn btn-primary btn-boca-login" id="formLoginButton"
                                            value="Iniciar">
                                    </div
                                </form>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-50">
                            <div class="col-12 text-center">
                                <p class="recuperar">¿Has olvidado tu contraseña? <span>recuperar</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none d-md-block col-md-5 col-lg-4 col-lg-4 bg-dark-blue">
                    <div class="container">
                        <div class="row mt-50">
                            <div class="col-12 text-center">
                                <h3 class="text-white">BOCA</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-white">¡Si no tienes cuentas puedes registrarte fácilmente!</p>
                            </div>
                        </div>
                    </div>
                    <div class="img-blue">
                    </div>
                    <div class="img-people"></div>

                </div>
            </div>
        </div>
    </section>

