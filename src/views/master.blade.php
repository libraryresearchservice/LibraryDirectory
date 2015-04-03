<!doctype html>
<html class="no-js" lang="en"><head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Directory of Colorado Libraries</title>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="<?php echo url() ?>/js/main.js"></script> 
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,300|Open+Sans:400,300|Roboto:400,300' rel='stylesheet' type='text/css'>
    <link href="<?php echo url() ?>/css/main.min.css" rel="stylesheet">
</head>
<body>
	
    <!-- master -->
    <div id="page-header" >
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <a href="http://www.cde.state.co.us/"><img src="<?php echo url() ?>/img/cdelogo.png" alt="Colorado Department of Education" class="page-header-banner"></a>
                    <h1 class="page-header-banner-alt"><a href="http://www.cde.state.co.us/">CDE - Colorado Department of Education</a></h1>
                </div>
                <div class="col-md-6 text-right header-links">
                    <p><a href="http://www.cde.state.co.us/siteindex">Site Index</a> | <a href="http://www.cde.state.co.us/offices">CDE Offices</a> | <a href="http://www.cde.state.co.us/cdestaffdirectory">Staff Directory</a></p>
                </div>
            </div>
            <ul id="navbar" class="clearfix">
                <li class="nav1">&nbsp;</li>
                <li class="nav2">&nbsp;</li>
                <li class="nav3">&nbsp;</li>
                <li class="nav4">&nbsp;</li>
            </ul>
            
        </div>
    </div><!-- page-header -->
    
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" id="main-breadcrumbs">
                    <li><a href="http://www.cde.state.co.us/">CDE Home</a></li>
                    <li><a href="http://www.cde.state.co.us/cdelib">Colorado State Library</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /main breadcrumbs -->

    <div class="container">
    	<div id="menu">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="pull-left"><a href="<?php echo url() ?>">Directory of Colorado Libraries</a></h1>
                    <ul class="nav nav-pills pull-right">
                        <li<?php echo Request::segment(1) == null || Request::segment(1) == 'library' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>">Libraries</a></li>
                        <li<?php echo Request::segment(1) == 'map' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/map">Map</a></li>
                        <li<?php echo Request::segment(1) == 'about' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/about">About</a></li>
                        <?php
						if ( userIsLoggedIn() ) {
							?>
                            <li<?php echo Request::segment(1) == 'contact' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/contact">Contacts</a></li>
                            <?php	
						}
						if ( userIsAdmin() ) {
							?>  
							<li<?php echo Request::segment(1) == 'admin' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/admin">Admin</a></li>
							<?php
						}
						if ( userIsLoggedIn() ) {
							?>
							<li><a href="<?php echo url() ?>/logout">Sign Out</a></li>
							<?php
						}
						?>
                    </ul>
                </div>
            </div>
        </div><!-- /menu -->
    </div> 
    <div class="container" id="main-wrap">
		<?php
		if ( userIsAdmin() && isset($haveEdits) && Auth::user()->edit_notifications && $haveEdits ) {
			?>
            <div class="alert alert-info">
            	Public user submitted changes are <a href="<?php echo url() ?>/admin/public-edits">waiting to be reviewed</a>
            </div>
            <?php	
		}
		?>
		@yield('content')
	</div><!-- /main-wrap -->
	<div id="page-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <strong>Stay Connected&nbsp;&nbsp;&nbsp;</strong> 
                    <a href="http://www.twitter.com/codepted/"><img src="<?php echo url() ?>/img/connect-twitter-icon.gif" alt="" border="0"></a>&nbsp;&nbsp;&nbsp;
                    <a href="http://www.facebook.com/codepted/"><img src="<?php echo url() ?>/img/connect-facebook-icon.gif" alt="" border="0"></a>&nbsp;&nbsp;&nbsp;
                    <a href="http://www.youtube.com/codeptofed/"><img src="<?php echo url() ?>/img/connect-youtube-icon.gif" alt="" border="0"></a>&nbsp;&nbsp;&nbsp;
                    <a href="http://www.linkedin.com/company/colorado-department-of-education/"><img src="<?php echo url() ?>/img/connect-linkedin-icon.gif" alt="" border="0"></a>&nbsp;&nbsp;&nbsp;
                    <a href="http://www.cde.state.co.us/communications#publications"><img src="<?php echo url() ?>/img/connect-email-icon.gif" alt="" border="0"></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Quick Links</strong></p>
                    <div class="col-md-3">
                        <ul>
                            <li><a href="http://www.colorado.gov/">Colorado.gov</a></li>
                            <li><a href="http://www.cde.state.co.us/offices">Offices</a></li>
                            <li><a href="http://www.cde.state.co.us/cdestaffdirectory">Our Staff</a></li>
                            <li><a href="http://www.cde.state.co.us/communications/newsreleases">News</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul>
                            <li><a href="http://www.cde.state.co.us/cdemgmt/hr/jobs">Jobs</a></li>
                            <li><a href="http://www.cde.state.co.us/siteindex">Site Index</a></li>
                            <li><a href="http://www.schoolview.org/">SchoolView</a></li>
                            <li><a href="http://www.cde.state.co.us/cde_calendar">Calendar</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul>
                            <li><a href="http://www.cde.state.co.us/cdeboard/">State Board</a></li>
                            <li><a href="http://www.cde.state.co.us/cdecomm/">Commissioner</a></li>
                            <li><a href="http://www.cde.state.co.us/cdecomm/aboutcde">About CDE</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <p><strong>Contact Us</strong></p>
                    <div>
                        <div class="col-md-6">
                            <p>Colorado Dept. of Education<br>
                            201 East Colfax Ave.<br>Denver, CO 80203<br> 
                            Phone: 303-866-6600<br> Fax: 303-830-0793<br>
                            <a href="http://www.cde.state.co.us/contact_cde">Contact CDE</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>Hours: <br> 
                            Mon - Fri 8 a.m. to 5 p.m.
                            </p>
                            <p>
                            <a href="http://www.cde.state.co.us/cdeprof">Educator Licensing <br>Location &amp; Hours</a>
                            </p>
                        </div>
                    </div>  
                    <div class="col-md-12">
                        <p id="copyright">Copyright &copy; 1999-<?php echo date('Y', time()) ?> Colorado Department of Education.<br>
                        All rights reserved.<br>
                        <a href="http://www.cde.state.co.us/titleix">Title IX</a>. <a href="http://www.cde.state.co.us/accessibility">Accessibility</a>. <a href="http://www.cde.state.co.us/disclaimer">Disclaimer</a>. <a href="http://www.cde.state.co.us/privacy">Privacy</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
	</div><!-- /page-footer -->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-17248991-2', 'auto');
		ga('send', 'pageview');
    </script>
</body>
</html>