<!doctype HTML>
<html lang="en">
    <head>
        <title>Section Administrator</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="<?php echo site_url();?>css/site.css" />

        <!--[if lte IE 9]>
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
                        <li class="divider-vertical">
                        </li>
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
        
        
        <div class="container">
            <div class="row">
                <div class="row page-header">
                    <div class="span12" align="right">
                        <img src="<?php echo site_url();?>images/home.gif" />
                    </div>
                    <h2>Welcome</h2>
                </div>                
            </div>       
            
            <div class="row" style="margin-left:50px;">
                <div class="span12">
                    <div class="row link-group">

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
                        
                        <div class="span4 icon-container">
                            <a href="<?php echo site_url();?>admin/createCR">
                                <i class="icon-user icon-large"></i>
                                Create new CR
                            </a>
                        </div>

                    </div>
                    <div class="row  link-group">
                        <div class="span4 icon-container">
                            <a href="<?php echo site_url();?>blocks/generateSet">
                                <i class="icon-tag icon-large"></i>
                                Generate Codes
                            </a>
                        </div>
                        
                        <div class="span4 icon-container">
                            <a href="<?php echo site_url();?>admin/generateReport">
                                <i class="icon-credit-card icon-large"></i>
                                View Reports
                            </a>
                        </div>
                        
                        <div class="span4 icon-container">

                            <a href="<?php echo site_url();?>admin/verifyReport">
                                <i class="icon-filter icon-large"></i>
                                Verify Report
                            </a>
                        </div>
                    </div>

                    <div class="row  link-group">
                         <div class="span4 icon-container">
                            <a href="<?php echo site_url();?>admin/createDepartment">
                                <i class="icon-briefcase icon-large"></i>
                                Create Department
                            </a>
                        </div>

                        <div class="span4 icon-container">
                            <a href="<?php echo site_url();?>admin/deleteDepartment">
                                <i class="icon-remove icon-large"></i>
                                Delete Department
                            </a>
                        </div>
                        <div class="span4 icon-container">
                            <a href="<?php echo site_url();?>blocks/getUnusedCodes">
                                <i class="icon-list icon-large"></i>
                                Unused Codes
                            </a>
                        </div>
                        
                    </div>

                    <div class="row  link-group">
                        <div class="span4 icon-container">
                            <a href="<?php echo site_url();?>accounts/viewUsers">
                                <i class="icon-group icon-large"></i>
                                View users
                            </a>                            
                        </div>
                       <div class="span4 icon-container">
                            <a href="<?php echo site_url();?>blocks/create">
                                <i class="icon-tasks icon-large"></i>
                                Create Block
                            </a>
                        </div>
                        <div class="span4 icon-container">
                            <a href="<?php echo site_url();?>accounts/incrementYear">
                                <i class="icon-table icon-large"></i>
                                Increment Year
                            </a>
                        </div>                        
                    </div>
                </div>                
            </div>
            
            <div class="row center" style="font-family: Arial;margin-top: 70px;margin-bottom: 20px;">
                <a href="<?php echo site_url();?>main/about/">About</a>
                -
                <a href="">&copy;St. Xavier's College(Autonomous), Kolkata</a>
                -
                <a href="">Blog</a>
            </div>            
        </div>
        
        <script src="<?php echo site_url();?>js/jquery.js"></script>
        <script src="<?php echo site_url();?>js/bootstrap.min.js"></script>
    </body>
</html>