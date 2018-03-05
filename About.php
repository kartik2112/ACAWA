<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php include("CommonFiles/CommonHead.php"); ?>
        <title>About Us</title>
        <style>
            .parallax-container{
                height: 400px;
            }
        </style>
    </head>
    <body >
        <?php include "CommonFiles/Menu.php"?>
        <script>
            $(document).ready(function () {
                $('.parallax').parallax();
                $("#MenuAboutLI").addClass("active");
                $("#SideMenuAboutLI").addClass("active");
            });
        </script>

        <div class="parallax-container" style="margin-top: -50px;">
            <div class="parallax"><img src="images/CommonPics/parallax31.jpg" alt="parallaxImg1"></div>
        </div>
        <div class="section white">
            <div class="row container">
                <h2 class="header">Audit Course Allotment</h2>
                <p class="black-text text-darken-3 lighten-3">This website aims at easing the work of the administrator and the student.</p>
                <p class="grey-text text-darken-3 lighten-3">It provides a one-stop solution for the students to view the teacher, syllabus, capacity of each audit course for their semester. It also enables the user to know the time left to submit his choices.</p>
                <p class="grey-text text-darken-3 lighten-3">It will also prove to be of great help to the administrator who can manage the course details like the syllabus file and add or remove courses very easily. It allows the admin to easily import data from excel files.</p>
                <p class="grey-text text-darken-3 lighten-3">We provide you with a very responsive and beautiful design to interact with the website.</p>
                <h5 class="blue-text text-darken-3 lighten-3" style="line-height: 50px">With regards,<br/>Kartik and Raj</h5>
            </div>
        </div>
        <div class="parallax-container">
            <div class="parallax"><img src="images/CommonPics/parallax42.jpg" alt="parallaxImg2"/></div>
        </div>
        <div class="section white">
            <div class="row container">
                <h4 class="blue-text text-darken-3 lighten-3" style="line-height: 60px">Web Technology Project 2016<br/>Roll Nos. 1411104, 1411113</h4>
            </div>
        </div>
      
    </body>
</html>
