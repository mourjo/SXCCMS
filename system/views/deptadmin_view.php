<!doctype HTML>
<html lang="en">
    <head>
        <title>Home</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="<?php echo site_url();?>css/site.css">
        
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    
    
    <body class="has-navbar">
        
        <!-- navbar -->
         <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    
                    <?php
                        echo "<a class='brand' href='".site_url()."'>".$this->session->userdata('FirstName')." ".$this->session->userdata('LastName')."</a>\n";
                    ?>
                    <ul class="nav">
                        
                        <li class="">
                            
                        </li>
                        <li class="">
                           
                        </li>
                        <li class="">
                           
                        </li>
                    </ul>
                    
                    <form class="navbar-search pull-left" action="<?php echo site_url();?>main/search">
                        <input type="text" class=" span2" placeholder="Search" name="search">
                    </form>
                    
                    <ul class="nav pull-right">
                        <li>
                            <a href="<?php echo site_url();?>">
                                <i class="icon-home" style="font-size: 30px;"></i>
                            </a>
                        </li>
                        <li class="divider-vertical"></li>
                        <li class="dropdown" id="options">
                            <a href="#options" class="dropdown-toggle" data-toggle="dropdown">
                                Account
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo site_url().'accounts/changePassword';?>">Change Password</a>                    
                                </li>
                                <li>
                                    <a href="<?php echo site_url().'accounts/logout';?>">Logout</a> 
                                </li>
                            </ul>
                        </li>
                    </ul>
                    
                </div>
            </div>
        </div>
        <!--navbar end-->
        
        <div class="container">
            <div class="row page-header" >
                <div class="span12" align="right">
                    <img src="<?php echo site_url();?>images/department.gif" />
                </div>
                <h2>Welcome</h2>
            </div>
            
            <div class="row">
                <div class="span8">
                    <div class="row  link-group">

                        <div class="span4 icon-container">

                            <a href="<?php echo site_url();?>events/create">
                                <i class="icon-beaker icon-large"></i>
                                Create an event
                            </a>
                        </div>

                        <div class="span4 icon-container">

                            <a href="<?php echo site_url();?>events/view">
                                <i class="icon-wrench icon-large"></i>
                                Manage events
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="span3">
                    
                    <div class="span3 well" style="line-height: 200%;">
                        <h3 class="pull-right center">Popular Department Events</h3><br>
                        <?php
                            if(count($events) > 0)
                            {
                                for($i = 0;$i <  count($events);$i++)

                                    echo "<a href='".site_url()."events/viewAssociated/".$events[$i]['EventID']."'>".$events[$i]['EventName']."</a><br>";
                            }
                            else
                            {
                                echo "No event currently open";
                            }
                            
                        ?>
                    </div>
                </div>
            </div>
           
        </div>
        <script src="<?php echo site_url();?>js/jquery.js"></script>
        <script src="<?php echo site_url();?>js/bootstrap-dropdown.js"></script>
    </body>
</html>