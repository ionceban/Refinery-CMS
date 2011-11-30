<?php
if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
    header('Location: index.php');
}
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="/favicon.ico">
        <!--[if lt IE 9]>
                <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
                <![endif]-->
        <!--[if IE 8]>
                <link type="text/css" rel="stylesheet" href="css/ie8.css"/>
                <![endif]-->
        <!--[if IE 7]>
                <link type="text/css" rel="stylesheet" href="css/ie7.css"/>
                <![endif]-->
        <title>Refinery CMS</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.14.custom.css" />
        <script type="text/javascript" src="js/jquery/jquery.js"></script>
        <script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
        <script type="text/javascript">
			
            $(document).ready(function(){  
		
                // Overlay Init
                $( "#overlay" ).dialog({
                    // dialogClass: "main-login-dialog",
                    autoOpen: true,
                    width: "970",
                    draggable: false,
                    resizable: false,
                    opacity: '0.2',
                    position: ['center',70],
                    modal: false,
                    zIndex: 3010
                });
                
                // show clear input icon
                $('input.login-input').focus(function(){
                    $(this).parent().find('.clear-input').show();
                    
                });

                $('.clear-input').click(function(){
                    $(this).parent().find('input').val('');
                    $(this).hide();
                    return false;
                });



            });		
        </script>

    </head>
    <body class="login-page">
        <div id="container">
            <header id="header">
                <h1 id="logo">
                    <a href="#">the refinery</a>
                </h1>
                <section id="top-bar">
                    <nav>
                        <ul>
                            <li>
                                <a href="http://therefinerycreative.com/pipeline/">portofolio admin</a>
                            </li>
                        </ul>
                    </nav><!-- main-navigation -->
                </section><!-- end top-bar -->
            </header><!-- end header -->
            <section id="main">
                <div id="login-container">
                    <img class="login-logo" src="images/login-logo.jpg" alt="the refinery" />
                </div><!-- end Login Container -->

                <div id="overlay" class="login-overlay">
                    <div id="overlay-content">
                        <header>
                            <h2>please login</h2>
                        </header>
                        <div id="login-form">
                            <form action="index.php" method="POST">
                                <fieldset>
                                    <label for="username">username:</label>
                                    <input name="username" class="login-input" type="text" id="username" />    
                                    <a class="clear-input" href="#">clear input</a>
                                </fieldset>
                                <fieldset>
                                    <label for="password">password:</label>
                                    <input class="login-input" name="password" type="password" id="password" /> 
                                    <a class="clear-input" href="#">clear input</a>
                                </fieldset>

                                <div class="login-buttons">
                                    <input type="submit" value="login" />
                                    <input type="button" value="cancel" />
                                </div>
                                <div class="login-notification-message">
                                    <p class="login-error">invalid username or password.</p>
                                </div>
                            </form>
                        </div><!-- end Login Form -->
                    </div><!-- end overlay-content -->
                </div><!-- end overlay -->

            </section>

        </div><!-- end container -->
    </body>
</html>
