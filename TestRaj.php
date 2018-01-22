<?php
    $servername="localhost";
    $username="root";
    //$password="kkksss333";
    $password="kjsce";
    //$dbname="dbACAWA";
    $dbname="dbACAWAR"; 
    $conn=mysqli_connect($servername,$username,$password,$dbname);
?>

<?php
    include("CommonFiles/CommonHead.php"); 
?>

<?php
    
/*
    //get thhe total number of submissions
    
    $sqlDemo="select * from Demo";
    $resultDemo=mysqli_query($conn,$sqlDemo);
    $num_rows = mysqli_num_rows($resultDemo);
    echo $num_rows.'<br>';
    

    //for the array
    $sqlSub = "select * from Subject";
    $resultSub = mysqli_query($conn,$sqlSub);

    $count = array();
    while($Sub =mysqli_fetch_array($resultSub,MYSQLI_ASSOC))
    {
        echo $Sub['Subj_ID'].'<br>';
        $count[$Sub['Subj_ID']] = $Sub['Capacity'];
        echo $count[$Sub['Subj_ID']].'<br>';
    }
    
    $sqlUpdate = "UPDATE Demo where Roll_number=1411104 SET allotedChoice = 1";
    $Update = mysqli_fetch_

    //decrement working
    /*
    while($count['1']>0)
    {
        echo $count['1'].'<br>';
        $count['1']--;
    }
    */
?>

<?php
    
    //update query
    /*
    $sql = "UPDATE User SET pwd = 'something' WHERE Roll_number = 1411104";
    

    if(mysqli_query($conn,$sql))
    {
        echo'changed';
    }
    else
    {
        echo 'error';
    }
    */
?>

<?php
    //code for trimming

    /*
    $email = "example.ex@somaiya.edu";
    $u_name = str_replace('@somaiya.edu','',$email);
    */
?>

<?php
    //Random number generation
    /*
    $arr = array('!','@','#','$','%','^','&','*','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9');
    
    $ran = "";
    for($i = 0 ; $i<10;$i++)
    {
        $ran = $ran.$arr[mt_rand(0,69)]; //random function
    }
    
    echo $ran;
    //echo $arr[$ran];
    */
?>

<?php
    
    /*
    $arr = array('!','@','#','$','%','^','&','*','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9');
    
    $ran = "";
    $email = "example.ex";
    for($i = 0 ; $i<10;$i++)
    {
        $ran = $ran.$arr[mt_rand(0,69)]; //random function
    }
    $sql = "UPDATE User SET pwd = '".$ran."' WHERE u_name = '".$email."'";
    mysqli_query($conn,$sql);
    */
?>

<?php
    /*
    $email = "example.ex@somaiya.edu";
    $u_name = str_replace('@somaiya.edu','',$email);
    */
?>

<?php
    /*
    $sql = " SELECT * FROM User WHERE u_name = 'raj.p'";
    $result = mysqli_fetch_array(mysqli_query($conn,$sql));
    echo $result['Name'];
    */
?>

<!--
<div class="row" style="width:800px; margin:0 auto;">

    <form class="col s12" method="post" action="">
      <div class="row" style="width:800px; margin:0 auto;">
        <div class="input-field col s6">
          <input id="CURRENT PASSWORD" type="password" class="validate">
          <label for="Current passwordfafaaadsadas">First Name</label>
        </div>
       </div>
        
       <div  class="row" style="width:800px; margin:0 auto;">
          <div class="input-field col s6">
            <input id="NEW PASSWORD" type="password" class="validate">
            <label for="New password">Last Name</label>
          </div>
       </div>
      
      <div class="row" style="width:800px; margin:0 auto;">
        <div class="input-field col s6">
          <input id="CONFIRM NEW PASSWORD" type="password" class="validate">
          <label for="Confirm password">Password</label>
        </div>
      </div>

      <div class="row" style="width:800px; margin:0 auto;">
        <div class="input-field col s6">
          <input id ="submit" type="submit" name="CHANGE">
          <label for="submit">Submit</label>
        </div>
      </div>
        
    </form>
  </div>

-->

<!--
<!DOCTYPE html>
<style style="alignment-adjust: middle" style="vertical-align: middle"></style>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body style="width: 400px; margin:0 auto;  ">
        
        <a class="waves-effect waves-light btn modal-trigger"  href="#modalFP" onclick="  $('#modalFP').openModal();">forgot password?
        </a>

                    <?php //Modal Structure?>
                    <div id="modalFP" class="modal">
                        <div class="modal-content center-align">
                            
                            <form style="width: 400px;margin: 0 auto;" method="post" action=""> 
                                  <h4>ENTER YOUR EMAIL ID</h4><br>
                                <input type="text" name="email" style="text-align: center">
                                <button class="btn waves-effect waves-light" type="submit" name="action" style="margin: auto">SUBMIT</button>
                            </form>
                            

                        </div>
                    </div>
         <?php
             /*
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

                    echo '<script>window.location = "/ResetPwdRaj.php";</script>'; //echo 'Done!';
                }
                //mysqli_query($conn,$sql);
                            //echo (sizeof($arr)-1);
                        }
                        */
        ?>
          
    </body>
</html>



-->

<!--
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>New password Password</title>
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
        
        <?php/*
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['code'])  && isset($_POST['npwd'])  && isset($_POST['cnpwd']) )
                {
                //require 'CommonFiles/connection.php';
                    $email = $_POST['email'];
                    $u_name = str_replace('@somaiya.edu','',$email);
                    echo $u_name;
                    
                    $sql="select * from User where u_name='".$u_name."' and pwd='".md5($_POST['code'])."'";
                    $result=mysqli_query($conn,$sql);
                    echo ' first if';
                    if(mysqli_num_rows($result) == 1)
                    {
                        echo 'second if';
                        $rowUser=mysqli_fetch_array($result,MYSQLI_ASSOC);
                
                        if($_POST['npwd'] == $_POST['cnpwd'])
                        {
                            echo 'third if';
                            $sqlUpdate = "UPDATE User SET pwd = '".md5($_POST['npwd'])."' WHERE u_name = '".$u_name."'"; 
                            if(mysqli_query($conn,$sqlUpdate)){
                                echo '<script>alert("password changed successfully! Please login using new password");</script>';

                                echo '<script>window.location = "/login.php";</script>';
                                //header("location : login.php");  before header call no content should be present in either html or echoed through php
                                //echo '';
                            }
                            
                        }
                        
                     }
                     
                }   
            
            */
        ?>
    </body>
</html>




-->


<?php
    session_start();
    if(!isset($_SESSION['userid']) ){
        header("location: login.php");
    }
    if(isset($_SESSION['Alogin'])){
        header("location: AdminHome.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>User Home</title>
                
        <style>
            .parallax-container{
                height: 400px;
            }
        </style>
</head>

<body style="background-image: url('books.jpg');">
    
        <?php include "CommonFiles/Menu.php"?>
    
   

        <script>
            $(document).ready(function () {
                $('select').material_select();
                $("#MenuHomeLI").addClass("active");
                $("#SideMenuHomeLI").addClass("active");
            });
        </script>
    <?php
        //raj ka code


        if(isset($_SESSION['userid']))
        {
            $sql = " SELECT * FROM User WHERE u_name = '".$_SESSION['userid']."'";
    
            $result = mysqli_fetch_array(mysqli_query($conn,$sql));
            //echo $result['Name'];
            echo '<h3 style="text-align: left;display: inline-block;">Welcome,'.$result['Name'].'</h3>';
        }
        //kartik put this part in admin home
        /*
        if(isset($_SESSION['Alogin'])
        {
            $sql = " SELECT * FROM User WHERE u_name = '".$_SESSION['Alogin']."'";
    
            $result = mysqli_fetch_array(mysqli_query($conn,$sql));
            echo '<h3 style="text-align: left;display: inline-block;">Welcome,'.$result['Name'].'</h3>';
        }
        */
    ?>

        

        <div class="row">
            <div class="col s12" style="">
                <div class="card large z-depth-2">
                    <div class="card-image">
                        <img src="images/UserProfilePix/1411113.jpg" alt="User Photo">
                        <span class="card-title">User Name(Kartik Shenoy)</span>
                    </div>
                    <div class="card-content">
                        <p>
                            SEM : V
                            <BR>BRANCH : COMPUTER
                            <BR>ROLL NUMBER : 1411104
                        </p>
                    </div>
                    <div class="card-action">
                        <a href="#">Edit Profile</a>
                    </div>
                </div>
            </div>
            
            <div class="col s12">
                <div class="card horizontal large z-depth-2">
                    <div class="card-image">
                        <img src="images/CommonPics/perma-glory.jpg">
                    </div>
                    <div class="card-stacked">
                        <div class="card-content">
                            <p>If you have not yet filled your audit course form, please fill it using the following link.</p>
                        </div>
                        <div class="card-action">
                            <a href="AuditCourseMakeChoices.php">Click here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card large z-depth-2">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator" src="images/CommonPics/victory.jpg">
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4">Results Declared<i class="material-icons right">more_vert</i></span>
                        <p><a href="Results.php">Click here to check all results</a></p>
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">You have been alloted an audit course<i class="material-icons right">close</i></span>
                        <p>You have been alloted the Audit Course - "Psychology".</p>
                        <p>Congratulations!</p>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>


