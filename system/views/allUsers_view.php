<!doctype HTML>
<html lang="en">
    <head>
        <title>All Users in the System</title>
       
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
        <div class="container">
            <h2>All users in the system:</h2><br/>
            <table class="table table-bordered" style="font-size: 12">

                <tr>
                    <td>
                        <?php

                            echo '<b>UserID<b/>';
                        ?>
                    </td>
                    <td>
                        <?php
                            echo '<b>UserType<b/>';
                        ?>
                    </td>
                    <td>
                        <?php
                            echo '<b>FirstName<b/>';
                        ?>
                    </td>
                    <td>
                        <?php
                            echo '<b>LastName<b/>';
                        ?>
                    </td>
                    <td>
                        <?php
                            echo '<b>Department<b/>';
                        ?>
                    </td>
                    <td>
                        <?php
                            echo '<b>Roll<b/>';
                        ?>
                    </td>
                    <td>
                        <?php
                            echo '<b>Username<b/>';
                        ?>
                    </td>
                    <td>
                        <?php
                            echo '<b>Status of the account<b/>';
                        ?>
                    </td>

                </tr>

                <?php
                foreach($users as $x)
                {?>
                <tr>
                    <td>
                        <?php
                            echo $x['UserID'];
                        ?>
                    </td>
                    <td>
                        <?php
                            if($x['UserType'] == 'system')
                                echo "System Administrator";
                            if($x['UserType'] == 'section')
                                echo "Section Administrator";                            
                            if($x['UserType'] == 'student')
                                echo "Student";
                            if($x['UserType'] == 'dept')
                                echo "Department Administrator";
                            if($x['UserType'] == 'cr')
                                echo "CR";
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $x['FirstName'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $x['LastName'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $x['Department'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $x['Roll'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $x['Email'];
                        ?>
                    </td>
                    <td>
                        <?php
                            if($x['Status']==1)
                                echo "Normal";
                            else
                                echo "Frozen";
                        ?>
                    </td>

                </tr>
                <?php
                }?>
            </table>
            <div style="text-align:center; margin-bottom:17px; font-size: 15px">
                    <?php
                echo $links;?>
            </div>
        </div>
    </body>
    <script src="<?php echo site_url();?>js/jquery.js"></script>
        <script src="<?php echo site_url();?>js/bootstrap-dropdown.js"></script>
</html>