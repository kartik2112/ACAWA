<?php 
    session_start();
    if(!isset($_SESSION['userid'])){
        //This will ridirect to login if user is not logged in
        //userid session var will be set if user is logged in
        header("location: login.php");
    }
    
?>

<?php
    require "CommonFiles/connection.php";
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Change Password</title>
        <?php include("CommonFiles/CommonHead.php");?>
        <script>
            $(document).ready(function(){
                $(".newPwdss").change(function(){
                    console.log("Triggered");
                    if($("#cnpwd").val() != "" && $("#npwd").val() != "" && $("#npwd").val() != $("#cnpwd").val()){
                        $(".newPwdss").addClass("invalid");
                        alert("Passwords don't match!");
                    }
                    else{
                        $(".newPwdss").removeClass("invalid");
                    }
                });
            });
        </script>
    </head>
    
    <body>
        <?php include "CommonFiles/Menu.php"; ?>
        <div class="card-panel red accent-2" style="text-align: center" >CHANGE CURRENT PASSWORD</div>
        <form method="post" action="" style="width:400px; margin:0 auto;">
                Current password : <br> <input  style="text-align: center" name="cpwd" type="password" required><br>
                New password     : <br> <input style="text-align: center" id="npwd" name="npwd" class="newPwdss" type="password" required><br>
                Confirm password : <br> <input style="text-align: center" id="cnpwd" name="cnpwd" class="newPwdss" type="password" required><br>
                <button class="btn waves-effect waves-light" type="submit" name="action" style="margin: auto">SUBMIT</button>
        </form>
 
        <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cpwd'])  && isset($_POST['npwd'])  && isset($_POST['cnpwd']) )
                {
                    $sql="select * from User where u_name='".$_SESSION['userid']."' and pwd='".md5($_POST['cpwd'])."'";
                    $result=mysqli_query($conn,$sql);
                    if(mysqli_num_rows($result) == 1)
                    {
                        $rowUser=mysqli_fetch_array($result,MYSQLI_ASSOC);
                
                        if($_POST['npwd'] == $_POST['cnpwd'])
                        {
                            $sqlUpdate = "UPDATE User SET pwd = '".md5($_POST['npwd'])."' WHERE u_name = '".$_SESSION['userid']."'"; 
                            if(mysqli_query($conn,$sqlUpdate)){
                                echo '<script>alert("password changed successfully! Please login using new password");</script>';
                                echo '<script>window.location = "/ACAWA/logout.php";</script>';
                            }   
                        }
                     }
                     else{
                         echo '<script>alert("Current password incorrect!");</script>';
                     }
                }
        ?>
    </body>
</html>
