<!doctype HTML>
<html lang="en">
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="<?php echo site_url();?>css/site.css" />

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
            
            <div class="row page-header" >
                <div class="span12" align="right">
                   <img src="<?php echo site_url();?>images/codes.gif"/>    
                </div>
            </div>
            
            <div class="row span8">
                <?php
                    if(isset($err))
                        echo $err;
                    if(isset($codes))
                    {
                        echo "<h2>".$userDetails[0]['FirstName']." ".$userDetails[0]['LastName']."</h2>";
                        echo "<div style='font-style: italic;'>Roll: ".$userDetails[0]['Roll']."<br/>";
                        echo "Department: ".$userDetails[0]['Department']."<br/>";
                        echo "Year: ".$userDetails[0]['Year']."<br/></div>";
                        if(count($codes) == 0 || $codes==0)
                            echo "<br/><b>No unused Codes</b>";
                        else
                        {
                            echo "<br/><b>The Unused Codes:</b> <ul class='offset1'>";
                            for($i = 0; $i < count($codes); $i++)
                            {
                                echo "<li>";
                                echo $codes[$i];
                                echo "</li>";
                            }
                            echo "</ul><br/><br/><a class='btn btn-warning' href='". site_url()."blocks/getUnusedCodes'> &lt;Back</a>
";
                        }
                    }
                    else
                    {
                ?>
                <form method="POST" action="<?php echo site_url();?>blocks/getUnusedCodes">
                    Enter CR username:&nbsp;&nbsp;<input type="text" name="email"/><br/>
                    <input class="btn btn-success" value='Go' type="submit"/>
                </form>
                <?php
                    }
                    ?>
            </div>
            
        </div>
        <script src="<?php echo site_url();?>js/jquery.js"></script>
        <script src="<?php echo site_url();?>js/bootstrap-dropdown.js"></script>
    </body>
</html>