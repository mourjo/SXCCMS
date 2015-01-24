<!doctype HTML>
<html lang="en">
    
    <head>
        <title>Associated</title>
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
            <div class="row page-header">
                <div class="span12" align="right">
                    <img src="<?php echo site_url();?>images/events.gif">
                </div>
            </div>
        <?php
            
                echo "<div class='row'>";
                echo "<div class='span5'>";
                echo "<h2>".$eName['EventName']."</h2>";            
                echo "<b>Event Details:</b><br/>";

                echo "Organised by <em>".$details['Department']."</em> department<br/>";
                echo "From ".substr($details['StartDate'],8,2)."/".substr($details['StartDate'],5,2)."/".substr($details['StartDate'],0,4)." to ";
                echo substr($details['EndDate'],8,2)."/".substr($details['EndDate'],5,2)."/".substr($details['EndDate'],0,4);
                echo "<br/>Credit Awarded in ".$details['Unit']." Unit <br/> ";
                echo "</div>";
                
                if($eName["Open"]=='1')
                {
                    echo "<div class='span6'>";
                    echo "<input class='btn btn-large btn-success offset3' type='button' value='Update' onclick='javascript:document.forms[1].submit();'/>";
                    echo "<a class='btn btn-large btn-danger pull-right' href='".site_url()."events/close/".$EID."'>Close Event</a>";
                    echo "</div>";
                    
                }
                else
                {
                    echo "<div class='span7' style='margin-bottom:5px'>";
                    echo "<input class='btn btn-large btn-success pull-right' type='button' value='Update' onclick='javascript:document.forms[1].submit();'/>";
                    echo "</div>";
                    echo "<div class='span7'><span class='alert pull-right'>This event has been closed</span></div>";
                }
            
            echo "<div class='span12'><br/><b>Available Roles:</b><br/></div><table class='table table-condensed table-bordered'><tr><td>Role Name</td><td>Credit to be awarded</td></tr>";
            foreach($roles as $r)
            {
                echo "<tr><td>".$r['Role']."</td><td>".$r['Credit']."</td></tr>";
            }
            echo "</table>";
            
            if($count==0)
            {
                echo "No submissions";
            }
            else
            {
                echo "<br/><b>Students interested:</b>";
                
                echo "<form method='POST' action='".site_url()."events/viewAssociated/".$EID."'>";
                echo "<table class='table'>";
                echo "<tr><td>Name</td><td>Deapartment of Student</td><td>Roll Number</td><td>Role</td><td>Status</td><td>Accredit</td></tr>";
                $arr = array('accepted', 'submitted', 'rejected', 'attended');

                    for($i=0; $i < $count; $i++)
                    {
                        
                        echo "<tr>";                    
                        echo "<td>".$associated[$i]['FirstName']." ".$associated[$i]['LastName'];//FirstName, LastName, Role, State
                        echo "<td>".$associated[$i]['Department']."</td>";
                        echo "<td>".$associated[$i]['Roll']."</td>";
                       
                        
                        if($associated[$i]['State']!='accredited')
                        {
                            echo "<td><select name='Role".$i."'>";
                            foreach($roles as $r)
                            {
                                if($associated[$i]['Role']==$r['Role'])
                                    echo "<option selected='selected'>".$r['Role']."</option>";
                                else
                                    echo "<option>".$r['Role']."</option>";
                            }

                            echo "</select></td><td><select name='State".$i."'>";
                            foreach ($arr as $j)
                            {
                                if($associated[$i]['State']==$j)
                                {
                                    echo "<option value='".$j."' selected='selected'>".ucfirst($j)."</option>";
                                }
                                else
                                    echo "<option value='".$j."'>".ucfirst($j)."</option>";
                            }
                            echo "</select></td>";
                            echo "<td><input type='checkbox' value=1 name='accredit".$i."'></td>";
                            echo "<td><input type='hidden' name='UID".$i."' value='".$associated[$i]['UserID']."'></td>";
                        }
                        else
                        {
                            echo "<td>".ucfirst($associated[$i]['Role'])."</td>";
                            echo "<td>Accredited</td>";
                        }
                        
                    }

                    echo "</table>";
                    echo "<input type='hidden' name= 'posted' value='1'/>";
                    
                echo "</form>";?>
                <div class="row" style="text-align: center; margin-bottom: 17px; font-size: 15px;">
                <?php 
                    echo $links;
                ?>
                </div>
                <?php
            }
            //push buttons up
            
                
        ?>
        </div>
        <script src="<?php echo site_url();?>js/jquery.js"></script>
        <script src="<?php echo site_url();?>js/bootstrap.min.js"></script>
    </body>
</html>