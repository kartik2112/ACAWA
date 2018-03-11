<?php
    // session_start();
    require_once "CommonFiles/connection.php";
    require_once "CommonFiles/CommonConstants.php";
    require_once "CommonFiles/ImpDatesXMLData.php";

    if( strtotime(date("Y-m-d")) < strtotime($startDateFetched) ){
        //Phase 1
        $phase=1;
    }
    else if( (strtotime($startDateFetched) <= strtotime(date("Y-m-d")) && ( strtotime(date("Y-m-d")) < strtotime($endDateFetched) ) ) ){
        //Phase 2
        $phase=2;
    }
    else{
        //Phase 3
        $phase=3;
    }

    if(isset($_SESSION['userid'])){
        $sqlMenuUser="select Semester,Roll_number from User where u_name='".$_SESSION['userid']."'";
        $resultMenuUser=mysqli_query($conn,$sqlMenuUser);
        $rowUser=mysqli_fetch_array($resultMenuUser,MYSQLI_ASSOC);


        $sqlCheckChoicesSubmitted="select * from AC".$rowUser['Semester']." where Roll_number=".$rowUser['Roll_number'];
        $resultCheckChoicesSubmitted=mysqli_query($conn,$sqlCheckChoicesSubmitted);
    
        if(mysqli_num_rows($resultCheckChoicesSubmitted)==0){
            $rowCheckChoicesSubmitted=mysqli_fetch_array($resultCheckChoicesSubmitted,MYSQLI_ASSOC);
            $notSubmittedFlag=TRUE;
        }
        else{
            $notSubmittedFlag=FALSE;
        }
    }
    
    
    
?>
<div class="navbar-fixed" style="z-index: 1000;">
    <nav>
        <div class="nav-wrapper">
            <a href="index.php" class="brand-logo" style="margin-left: 20px">Audit Course Allotment</a>
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                    <?php
                        if(!isset($_SESSION['userid'])){
                            //If user is not logged in
                            echo '<li id="MenuLoginLI"><a id="MenuLogin" class="tooltipped" data-position="bottom" data-delay="100" data-tooltip="Log in here" href="login.php" >LOGIN</a></li>';
                        }
                        else{
                    ?>
                    
                    <?php
                                if(!isset($_SESSION['Alogin'])){
                                    //Non Admin logged in
                                    echo '<li id="MenuHomeLI"><a id="MenuHome" class="tooltipped" data-position="bottom" data-delay="100" data-tooltip="Back to first page" href="index.php">HOME</a></li>';
                                    echo '<li id="MenuFormLI"><a id="MenuForm" class="tooltipped" data-position="bottom" data-delay="100" data-tooltip="Select your Audit Course" href="AuditCourseMakeChoices.php" >FILL FORM';
                                    if($phase==2 && $notSubmittedFlag==TRUE){
                                        //If user has not submitted his choices add exclamation mark
                                        echo '<span class="badge" style="color:white;font-size:30px;font-weight:bold;">&#x26a0;</span>';
                                    }
                                    echo '</a></li>';
                                    echo '<li id="MenuCourseLI"><a id="MenuCourse" class="tooltipped" data-position="bottom" data-delay="100" data-tooltip="Check out the course details" href="CourseDetails.php" >COURSE DETAILS</a></li>';
                                    if(($phase==2 && $notSubmittedFlag==FALSE) || $phase==3){
                                        //Show results tab if user has submitted his choices
                                        echo '<li id="MenuResultsLI"><a id="MenuResults" class="tooltipped" data-position="bottom" data-delay="100" data-tooltip="Check out the results" href="Results.php">RESULTS';
                                        if($phase==3){
                                            //If user has not submitted his choices add exclamation mark
                                            echo '<span class="badge" style="color:white;font-size:30px;font-weight:bold;">&#x26a0;</span>';
                                        }
                                        echo '</a></li>  ';
                                    }
                            
                                }
                                else{
                                    //Admin logged in
                                    echo '<li id="MenuAdminHomeLI"><a id="MenuAdminHome" class="tooltipped" data-position="bottom" data-delay="100" data-tooltip="Admin\'s Home" href="AdminHome.php">ADMIN HOME</a></li>';                            
                                    echo '<li id="MenuAllottmentLI"><a id="MenuAllottment" class="tooltipped" data-position="bottom" data-delay="100" data-tooltip="Check out the allottment statistics" href="Allottment.php">ALLOTTMENT STATS';
                                    if($phase==3 && $sortingPerformedStatus=="FALSE"){
                                        //If user has not submitted his choices add exclamation mark
                                        echo '<span class="badge" style="color:white;font-size:30px;font-weight:bold;">&#x26a0;</span>';
                                    }
                                    echo '</a></li>';
                                }
                        }
                    ?>
                    
                                    
                    <li id="MenuAboutLI"><a id="MenuAbout" class="tooltipped" data-position="bottom" data-delay="100" data-tooltip="Get to know us" href="About.php" >ABOUT US</a></li>           
                    <?php
                        if(isset($_SESSION['userid'])){  
                            echo '<li><a class="dropdown-button" href="#!" data-activates="dropdownlist"><i class="material-icons right">arrow_drop_down</i></a></li>';
                        }
                    ?>
                    
            </ul>
            <ul class="side-nav" id="mobile-demo">
                    <?php
                        if(!isset($_SESSION['userid'])){
                            //If user is not logged in
                            echo '<li id="SideMenuLoginLI"><a id="SideMenuLogin" class="tooltipped" data-position="right" data-delay="500" data-tooltip="Log in here" href="login.php">LOGIN</a></li>';
                        }
                        else{
                    ?>
                        <li id="SideMenuHomeLI"><a id="SideMenuHome" class="tooltipped" data-position="right" data-delay="500" data-tooltip="Back to first page" href="index.php">HOME</a></li>     
                        <?php
                                if(!isset($_SESSION['Alogin'])){
                                    //If non Admin has logged in
                                    echo '<li id="SideMenuFormLI"><a id="SideMenuForm" class="tooltipped" data-position="right" data-delay="500" data-tooltip="Select your Audit Course" href="AuditCourseMakeChoices.php">FILL FORM';
                                    if($phase==2 && $notSubmittedFlag==TRUE){
                                        //If user has not submitted his choices show exclamation mark
                                        echo '<span class="badge" style="color:#ee6e73;font-size:30px;font-weight:bold;">&#x26a0;</span>';
                                    }
                                    echo '</a></li>';
                                    echo '<li id="SideMenuCourseLI"><a id="SideMenuCourse" class="tooltipped" data-position="right" data-delay="500" data-tooltip="Check out the course details" href="CourseDetails.php">COURSE DETAILS</a></li>';
                                    if(($phase==2 && $notSubmittedFlag==FALSE) || $phase==3){
                                        echo '<li id="SideMenuResultsLI"><a id="SideMenuResults" class="tooltipped" data-position="right" data-delay="500" data-tooltip="Check out the results" href="Results.php">RESULTS';
                                        if($phase==3){
                                            //If user has not submitted his choices add exclamation mark
                                            echo '<span class="badge" style="color:#ee6e73;font-size:30px;font-weight:bold;">&#x26a0;</span>';
                                        }
                                        echo '</a></li>';
                                    }
                                }
                                else{
                                    echo '<li id="SideMenuAdminHomeLI"><a id="SideMenuAdminHome" class="tooltipped" data-position="right" data-delay="500" data-tooltip="Admin\'s Home" href="AdminHome.php">ADMIN HOME</a></li>';
                                    echo '<li id="SideMenuAllottmentLI"><a id="SideMenuAllottment" class="tooltipped" data-position="right" data-delay="500" data-tooltip="Check out the allottment statistics" href="Allottment.php">ALLOTTMENT STATS';
                                    if($phase==3 && $sortingPerformedStatus=="FALSE"){
                                        //If user has not submitted his choices add exclamation mark
                                        echo '<span class="badge" style="color:#ee6e73;font-size:30px;font-weight:bold;">&#x26a0;</span>';
                                    }
                                    echo '</a></li>';                                    
                                    echo '<li id="SideMenuModifySubjLI"><a id="SideMenuModifySubj" class="tooltipped" data-position="right" data-delay="500" data-tooltip="Modify the Subjects from here" href="ModifySubjects.php">MODIFY SUBJECTS</a></li>';
                                    echo '<li id="SideMenuImportLI"><a id="SideMenuModifySubj" class="tooltipped" data-position="right" data-delay="500" data-tooltip="Use this to import any data via excel sheet" href="ImportData.php">IMPORT DETAILS</a></li>';
                                }

                                echo '<li id="SideMenuChangePwdLI"><a id="SideMenuChangePwd" class="tooltipped" data-position="bottom" data-delay="100" data-tooltip="Log out" href="ChangePasswordRaj.php" >CHANGE PASSWORD</a></li>';
                                echo '<li id="SideMenuLogoutLI"><a id="SideMenuLogout" class="tooltipped" data-position="bottom" data-delay="100" data-tooltip="Log out" href="Logout.php" >LOGOUT</a></li>';
                        ?>
                        
                    <?php
                        }
                    ?>
                
                    <li id="SideMenuAboutLI"><a id="SideMenuAbout" class="tooltipped" data-position="bottom" data-delay="100" data-tooltip="Get to know us" href="About.php" >ABOUT US</a></li>           
                    
            </ul>
        </div>
    </nav>
    
    
    <?php
        if(isset($_SESSION['userid'])){  
            echo '<ul id="dropdownlist" class="dropdown-content" style="margin-top: 64px;">';              
            if(isset($_SESSION['Alogin'])){                
                echo '<li><a href="ModifySubjects.php">Modify Subject</a></li>';
                echo '<li class="divider"></li>';
                echo '<li><a href="ViewStudentsList.php">View Students List</a></li>';
                echo '<li class="divider"></li>';
                echo '<li><a href="ChangePasswordRaj.php">Change Password</a></li>';
                echo '<li class="divider"></li>';
            }
            echo      '<li><a href="logout.php">Logout</a></li>';
            echo      '<li class="divider"></li>';
            echo '</ul>';
        }
    ?> 
         
</div>


<script>
    $(document).ready(function () {
        $(".button-collapse").sideNav();
        $(".dropdown-button").dropdown();
    });
</script>
<br/>
<br/>