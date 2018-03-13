<?php
    session_start();
    if(isset($_SESSION['userid']) ){
        header("location: index.php");
    }

    
    require "CommonFiles/connection.php";
    require "CommonFiles/CommonConstants.php";
    require("CommonFiles/ImpDatesXMLData.php");


    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userid']) && isset($_POST['pswrd']) ){
        //require 'CommonFiles/connection.php';
        $sql="select * from User where u_name='".$_POST['userid']."' and pwd='".md5($_POST['pswrd'])."'";
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result) == 1){
            $rowUser=mysqli_fetch_array($result,MYSQLI_ASSOC);
            $_SESSION['userid']=$_POST['userid'];
            if($rowUser['Type']=="A"){
                $_SESSION['Alogin']=1;
                header("location: AdminHome.php"); //head to specified location
            }
            else{
                header("location: index.php");
            }
            
        }
        else{
            echo '<script>alert("Incorrect user ID or password provided");</script>';
        }
    }
    

?>

<!DOCTYPE html> <!-- for HTML 5 -->
<html>
<head>
	<title>Login</title>
    <?php include("CommonFiles/CommonHead.php"); ?>
	<style>
	form,h1 {
	    margin : auto;
	    text-align: center;
	} 
	
     
     #troubleLogin:hover{
         color: #007ab8;
     }
	</style>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".button-collapse").sideNav();
            $.ajax({
                'url':'RSS.php',
                'type': 'GET',
                'dataType': 'xml',
                'success': function(xmlResponse){
                    let items = $(xmlResponse).find("item");
                    $(items).each(function(){                        
                        let element =   {
                                            title: $(this).find("title").text(),
                                            desc: $(this).find("description").text(),
                                            img: $(this).find("StoryImage").text(),
                                            link: $(this).find("link").text(),
                                            updated_Date: $(this).find("updatedAt").text()
                                        };
                        generateCard(element);
                    });
                    
                }
            })
        });
        
        function generateCard(element){
            var cardRoot = document.createElement("div");
            //cardRoot.classList = "col s12 m6";

            cardRoot.innerHTML =    '<div class="row"  style="width:90%;  height:auto; >\
                                        <div class="col s12 m6">\
                                        <div class="card blue-grey darken-1">\
                                            <div class="card-content white-text">\
                                            <span class="card-title">'+element.title+'</span>\
                                            </div>\
                                            <div class="card-action">\
                                            <a target="_blank" href="'+element.link+'">Read Full article</a>\
                                            </div>\
                                            </div>\
                                            </div>\
                                        </div>';
            document.getElementById("containerRSS").appendChild(cardRoot);
        }
    </script>
</head>

<body>

    <?php include "CommonFiles/Menu.php"?>
    <script>
        $(document).ready(function () {
            $("#MenuLoginLI").addClass("active");
            $("#SideMenuLoginLI").addClass("active");
            $("#modalFP").modal({ complete: function() { $('.loginDetails').removeAttr('disabled'); $('#ForgotPswdEmail').attr('disabled','disabled'); } });
        });
    </script>
    
    
    <!-- <div class="" style="float:left; width: 50%; "> -->

		<div class="row">
        
			<form class="col s12 m6" action="" method="post" autocomplete="off">
		        <h1 style="margin-top: 75px;">  ACCOUNT LOGIN</h1>
				<div class="column" style="margin: auto;">
					<div class="input-field col s12 m6">
						<i class="material-icons prefix">account_circle</i>
						<input id="userid" type="text" name="userid" class="loginDetails" required autocomplete="off"/>
						<label for="userid">ID</label>
					</div>
					<div class="input-field col s12 m6">
						<i class="material-icons prefix">vpn_key</i>
						<input id="pswrd" type="password" name="pswrd" class="loginDetails" required autocomplete="off"/>
						<label for="pswrd">Password</label>
					</div>
				</div>

				<button class="btn waves-effect waves-light" type="submit" name="action" style="margin: auto">Login
				<i class="material-icons right">send</i>
				</button>
    
				<br/>
				<br/>
                <a class="waves-effect waves-light btn modal-trigger"  href="#modalFP" onclick=" $('.loginDetails').attr('disabled','disabled'); $('#ForgotPswdEmail').removeAttr('disabled');  $('#modalFP').modal('open');" style="margin-bottom:50px;">forgot password?
                </a>

                
                <!--
                <a href = "forgotPwdRaj.php" id="troubleLogin">
 
				forgot password?

				</a>
                
                -->
				
				
            
                
			</form>
            <div class="col s12 m6">
                <div id="containerRSS" style="width:90%; height:80vh; overflow-y:scroll;"  class="row"></div>
            </div>
		</div>  
        <!-- Modal Structure -->
        <div id="modalFP" class="modal">
                    <div class="modal-content center-align">
                        
                        <form style="margin: 0 auto;" method="post" action="" class="col s12"> 
                            <h4>ENTER YOUR EMAIL ID</h4><br>
                            <input id="ForgotPswdEmail" type="text" name="email" style="text-align: center" required disabled>
                            <button class="btn waves-effect waves-light" type="submit" name="action" style="margin: auto" value="ResetPwd">SUBMIT</button>
                        </form>
                    </div>
                </div>    
	<!-- </div> -->

    
    
    <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']))
            {
                
                $email = $_POST['email'];
                $u_name = str_replace('@somaiya.edu','',$email);

                //now generate the new password 
                $arr = array('!','@','#','$','%','^','&','*','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9');
    
                $ran = "";
                //$email = "example.ex";
                for($i = 0 ; $i<10;$i++)
                {
                    $ran = $ran.$arr[mt_rand(0,(sizeof($arr)-1))]; //random function
                }
                $sql = "UPDATE User SET pwd = '".md5($ran)."' WHERE u_name = '".$u_name."'";
                if(mysqli_query($conn,$sql))
                {
                    echo '<script>alert("CODE sent via email! Don\'t share it with anyone. Please change the password first");</script>';
                    $content='<!DOCTYPE html>'.
                                                '<html lang="en" xmlns="http://www.w3.org/1999/xhtml">'.
                                                '<head>'.                                    
                                                '</head>'.
                                                '<body>'.
                                                    '<div style="margin: auto;">'.
                                                        '<p>Dear User,</p>'.
                                                        '<p>Your password has been reset. Your One-Time-Password is '.$ran.'</p>'.
                                                        '<p>Use this and change your password first using the link below:</p>'.
                                                        '<p><span style=""><a href="http://'.$_SERVER['HTTP_HOST'].'/ResetPwdRaj.php" target="_blank"><span>Click here</span></a></span></p>'.
                                                    '</div>'.
                                                '</body>'.
                                                '</html>';
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    // mail($u_name."@somaiya.edu","Audit Course Allottment Webapp Password Reset!",$content,$headers);
                    //echo '<script>window.location = "/ResetPwdRaj.php";</script>'; //echo 'Done!';
                }
                //mysqli_query($conn,$sql);
                            //echo (sizeof($arr)-1);
            }
        ?>

        <!--
        <nav>
            <div class="nav-wrapper">
                <a href="#!" class="brand-logo">Logo</a>
                <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="sass.html">Sass</a></li>
                    <li><a href="badges.html">Components</a></li>
                    <li><a href="collapsible.html">Javascript</a></li>
                    <li><a href="mobile.html">Mobile</a></li>
                </ul>
                <ul class="side-nav" id="mobile-demo">
                    <li><a href="sass.html">Sass</a></li>
                    <li><a href="badges.html">Components</a></li>
                    <li><a href="collapsible.html">Javascript</a></li>
                    <li><a href="mobile.html">Mobile</a></li>
                </ul>
            </div>
        </nav>
        -->
    </body>
</html>