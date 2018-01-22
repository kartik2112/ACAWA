<?php 
    require "CommonFiles/connection.php";
    /*
    $servername="localhost";
    $username="root";
    //$password="kkksss333";
    $password="kjsce";
    //$dbname="dbACAWA";
    $dbname="dbACAWAR"; 
    $conn=mysqli_connect($servername,$username,$password,$dbname);
    if(mysqli_connect_error()){
        die("Cannot access db ".mysqli_error($conn));
}*/
?>





<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php
            include("CommonFiles/CommonHead.php"); 
        ?>
        <title>New Password</title>
    </head>
    <body>
        <div class="card-panel red accent-2" style="text-align: center" >RESET PASSWORD</div>
            <form class="col s6" action="" method="post">
			    <div class="column" style="width: 50%;margin: auto;">
				    <div class="input-field col s6">
					
					    <input id="email" type="text" name="email" class="" required/>
					    <label style="text-align: center " for="email">EMAIL ID</label>
				    </div>
				    <br/>
				    <div class="input-field col s6">
					
					    <input id="code" type="password" name="code" class="" required/>
					    <label style="text-align: center " for="code">ONE TIME PASSWORD</label>
				    </div>
                    <br/>
				    <div class="input-field col s6">
					
					    <input id="npwd" type="password" name="npwd" class="" required/>
					    <label style="text-align: center" for="npwd">NEW PASSWORD</label>
				    </div>
                    <br/>
				    <div class="input-field col s6">
					
					    <input id="cnpwd" type="password" name="cnpwd" class="" required/>
					    <label style="text-align: center " for="cnpwd">CONFIRM PASSWORD</label>
				    </div>
			    </div>
                <button class="btn waves-effect red accent-2" type="submit" name="action" style="margin: auto">SUBMIT</button>
            </form>
        <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['code'])  && isset($_POST['npwd'])  && isset($_POST['cnpwd']) )
                {
                //require 'CommonFiles/connection.php';
                    $email = $_POST['email'];
                    $u_name = str_replace('@somaiya.edu','',$email);
                    //echo $u_name;
                    
                    $sql="select * from User where u_name='".$u_name."' and pwd='".md5($_POST['code'])."'";
                    $result=mysqli_query($conn,$sql);
                    //echo ' first if';
                    if(mysqli_num_rows($result) == 1)
                    {
                        //echo 'second if';
                        $rowUser=mysqli_fetch_array($result,MYSQLI_ASSOC);
                
                        if($_POST['npwd'] == $_POST['cnpwd'])
                        {
                            //echo 'third if';
                            $sqlUpdate = "UPDATE User SET pwd = '".md5($_POST['npwd'])."' WHERE u_name = '".$u_name."'"; 
                            if(mysqli_query($conn,$sqlUpdate)){
                                echo '<script>alert("password changed successfully! Please login using new password");</script>';

                                echo '<script>window.location = "/login.php";</script>';
                                //header("location : login.php");  before header call no content should be present in either html or echoed through php
                                //echo '';
                            }
                            
                        }
                        else{
                            echo '<script>alert("Passwords don\'t match!");</script>';
                        }
                        
                     }
                     
                }  
                else{
                    echo '<script>alert("Incorrect email id or password!");</script>';
                } 
            
            
        ?>
    </body>
</html>

