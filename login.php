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
</head>

<body style = "">

    <?php include "CommonFiles/Menu.php"?>
    <script>
        $(document).ready(function () {
            $("#MenuLoginLI").addClass("active");
            $("#SideMenuLoginLI").addClass("active");
        });
    </script>
    <div class="container">
		<h1 style="margin-top: 75px;">  ACCOUNT LOGIN</h1>

		<div>
        
			<form class="col s6" action="" method="post">
				<div class="column" style="width: 50%;margin: auto;">
					<div class="input-field col s6">
						<i class="material-icons prefix">account_circle</i>
						<input id="userid" type="text" name="userid" class="loginDetails" required/>
						<label for="userid">ID</label>
					</div>
					<br/>
					<div class="input-field col s6">
						<i class="material-icons prefix">vpn_key</i>
						<input id="pswrd" type="password" name="pswrd" class="loginDetails" required/>
						<label for="pswrd">Password</label>
					</div>
				</div>

				<button class="btn waves-effect waves-light" type="submit" name="action" style="margin: auto">Login
				<i class="material-icons right">send</i>
				</button>
    
				<br/>
				<br/>
                <a class="waves-effect waves-light btn modal-trigger"  href="#modalFP" onclick=" $('.loginDetails').attr('disabled','disabled'); $('#ForgotPswdEmail').removeAttr('disabled');  $('#modalFP').openModal( { complete: function() { $('.loginDetails').removeAttr('disabled'); $('#ForgotPswdEmail').attr('disabled','disabled'); } });">forgot password?
                </a>

                    <!-- Modal Structure -->
                    <div id="modalFP" class="modal">
                        <div class="modal-content center-align">
                            
                            <form style="width: 400px;margin: 0 auto;" method="post" action=""> 
                                <h4>ENTER YOUR EMAIL ID</h4><br>
                                <input id="ForgotPswdEmail" type="text" name="email" style="text-align: center" required disabled>
                                <button class="btn waves-effect waves-light" type="submit" name="action" style="margin: auto" value="ResetPwd">SUBMIT</button>
                            </form>
                        </div>
                    </div>
                <!--
                <a href = "forgotPwdRaj.php" id="troubleLogin">
 
				forgot password?

				</a>
                
                -->
				
				
            
			</form>
		</div>
	</div>
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

                    mail($u_name."@somaiya.edu","Audit Course Allottment Webapp Password Reset!",$content,$headers);
                    //echo '<script>window.location = "/ResetPwdRaj.php";</script>'; //echo 'Done!';
                }
                //mysqli_query($conn,$sql);
                            //echo (sizeof($arr)-1);
            }
        ?>

</body>
</html>