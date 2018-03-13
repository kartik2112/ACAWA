<!--Flip Clock Section-->
            <link rel="stylesheet" href="FlipClock-master/compiled/flipclock.css">
	        <script src="FlipClock-master/compiled/flipclock.js"></script>	
            <script type="text/javascript">
                var clock;
                $(document).ready(function () {
                    // Grab the current date
                    var currentDate = new Date("<?php echo date('Y/m/d H:i:s'); ?>");
                    var futureDate = new Date("<?php echo $endDateFetched; ?>" );
                    futureDate.setHours(0,0,0,0); 
                    // Calculate the difference in seconds between the future and current date
                    var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
                    // Instantiate a coutdown FlipClock
                    clock = $('#deadline_countdown').FlipClock(diff, {
                        clockFace: 'DailyCounter',
                        countdown: true,
                        callbacks: {
                            stop: function () {
                                var timeForReload=parseInt(Math.random()*1000);
                                $('#deadlineOverMessage').html('Refreshing Page in '+timeForReload/1000+' seconds...');
                                window.setTimeout(function(){
                                    $("#PopupOutsideDiv").fadeIn(200);
                                    $(".container").addClass("BlurredBGForPopup");
                                    $(".navbar-fixed").addClass("BlurredBGForPopup");
                                },timeForReload/2);
                        
                                window.setTimeout(function(){
                                    window.location.reload(true);
                                },timeForReload);
                        
                            }
                        }
                    });
                });
	        </script>