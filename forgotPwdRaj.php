<?php
    require 'CommonFiles/connection.php';
?>

<!DOCTYPE html>
<style style="alignment-adjust: middle" style="vertical-align: middle"></style>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php include("CommonFiles/CommonHead.php"); ?>
        <title>Forgot password</title>
    </head>
    
     <body>
        <?php include "CommonFiles/Menu.php"?>
        <div class="container">
            <a class="waves-effect waves-light btn modal-trigger" style="margin: auto;"  href="#modal1" onclick="  $('#modal1').openModal();">forgot password?
            </a>

                        <!-- Modal Structure -->
                        <div id="modal1" class="modal">
                            <div class="modal-content center-align">
                            
                                <form style="width: 400px;margin: 0 auto;" method="post" action=""> 
                                      <h4>ENTER YOUR EMAIL ID</h4><br>
                                    <input type="text" name="email" style="text-align: center">
                                    <button class="btn waves-effect waves-light" type="submit" name="action" style="margin: auto">SUBMIT</button>
                                </form>
                            </div>
                        </div>
             <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) )
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
                        $headers .="From:ACAWA Admin \r\n";

                        mail($u_name."@somaiya.edu","Password Reset!",$content,$headers);
                        //echo '<script>window.location = "/ResetPwdRaj.php";</script>';
                    }
                }
            ?>
        </div>
    </body>
</html>
