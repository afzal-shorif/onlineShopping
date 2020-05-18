
		</div>
        <div class="container-fluid bg-dark mt-4 text-white">
            <div class="row">
                <div class="container">
                    <div class="row pt-4">
                        <div class="col border-right border-white text-center">
                           <h3><?= strtoupper($site_title);?></h3>
                            <ul class="footer_ul">
                                <li class="social_media"><a href="<?= $facebook?>"><span class="fa fa-facebook" style="background-color: #0f62b7;"></span></a></li>
                                <li class="social_media"><a href="<?= $twitter?>"><span class="fa fa-twitter" style="background-color: #41ade2;;"></span></a></li>
                                <li class="social_media"><a href="<?= $instagram?>"><span class="fa fa-instagram" style="background-color: #bc2a8d;"></span></a></li>
                            </ul>
                        </div>
                        <div class="col border-right border-white text-center">
                            <ul class="footer_ul">
                                <li class=""><a href="">About us</a></li>
                                <li class=""><a href="">Branches & Pickup Points</a></li>
                                <li class=""><a href="">Warranty</a></li>
                            </ul>
                        </div>
                        <div class="col text-center">
                            <ul class="footer_ul">
                                <li class="" > <a href="feedback.php"style="">Feedback</a></li>
                                <li class=""><a href="">Payment Method</a></li>
                                <li class=""><a href="">Terms & Conditions</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-3 pb-1 mt-4">
                <div class="col text-center">
                    <p><?= $site_title;?> &copy; 2020 All Rights Reserved. Designed by <a href="index.php" style="color: white;"><?= $site_title;?></a> Ltd.</p>
                </div>
            </div>
        </div>
        <!-- bootstrap scripts-->
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <script>
			/// session_value is use to identify a unique user
            /// php session_id gives a unique session_id for all current user
            /// php session_id function use to to identify user

			var session_value;          /// contain the user session id
			
			session_value = "<?php echo session_id();?>";

			//setTimeout(setSessionValue, 1000);
			
		</script>
            <!-- script for ajax request -->
		<script src="assets/script/script.js"></script>
	</body>
</html>