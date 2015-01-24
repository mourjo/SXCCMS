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
                    <img src="<?php echo site_url();?>images/report.gif"/>';
                </div>
            </div>
            <div class="row" style="margin-top: 10px">
                <?php 
                if(isset($code) && ($code == '0' || $code == '-1'))
                {
                    if($code == '-1')
                    {?>
                        <div class="alert">
                            No record found!
                        </div>
                    <?php
                    }?>
                    
                <form name="reportDetails" method="POST" action="<?php echo site_url();?>admin/verifyReport">
                    
                    <div class="span4">
                        Code:&nbsp&nbsp<input type="text" placeholder="Enter the code" name="code"/><br/>
                        <input type="submit" class="btn btn-info"/>
                    </div>
                    
                        
                </form>
                <?php
                }else {?>
            
            <div class="row">
                <div class="span5" style="line-height: 200%;">
                    <h2><?php echo $name;?></h2>
                    <p style="font-style: italic">
                        <?php
                            echo "Department: ".$dept."<br/>";
                            echo "Year: ".$year."<br/>";
                            echo "Roll Number: ".$roll."<br/>";
                        ?>
                    </p>
                    
                
                    Total Credits: 
                    <?php 
                        if ($creditSum > 150)
                        {
                            $x = intval($creditSum/30);
                            $y = $creditSum - $x*30;
                            echo "<strong> ".$x." atomic credits and ".$y." hrs</strong>"; 
                        }
                        else
                        {
                            for($i = $creditSum; $i >= 30;$i -= 30)
                            {
                                echo '<a href="#" rel="tooltip" title="1 Star is 30 hrs"><i class="icon-star" style="font-size: 30px;"></i></a>';
                            }
                            if($i>0 && $creditSum > 30)
                                echo " <strong>and ".$i." hrs</strong>"; 
                            else
                                echo " <strong> ".$i." hrs</strong>"; 
                        }
                        ?><br/>
                    Total Social Credits: <?php echo "<strong>".$socialSum." hrs</strong><br/>"; 
                    if($socialFull == 1)
                    {
                        echo '<div class="alert alert-success">';
                            echo "Compulsory Social Credit obtained.";
                        echo '</div>';
                    }
                    else
                    {
                        echo '<div class="alert alert-info">';
                            echo "Compulsory Social Credit <strong>not yet obtained.</strong><br/>";
                        echo '</div>';
                    }?>
                </div>
            </div>
            
            <div class="row" align="center" style="margin-top: 30px;">
                <?php    
                if(count($credits)==0)
                {
                    echo "<h3>No credit has been awarded yet.</h3><br/>";
                    return;
                }
                else
                {
                    echo "<table class='table'>";
                
                    echo  " <thead>
                            <tr>
                                <th>Event</th>
                                <th>Department</th>
                                <th>Credit Awarded</th>
                            </tr>
                        </thead>";
                    for($i = 0; $i<count($credits); $i++)
                    {
                        echo "<tr>";
                            echo "<td>".$credits[$i]['EventName']."</td>";
                            echo "<td>".$credits[$i]['Department']."</td>";
                            echo "<td>".$credits[$i]['Amount']." ";
                            echo $credits[$i]['Amount']>1?($credits[$i]['Unit'] =='atomic'?'atomic units':'hours'):($credits[$i]['Unit'] =='atomic'?'atomic unit':'hour')."</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </div>
                <?php
                }?>
            </div>
        </div>
        <script src="<?php echo site_url();?>js/jquery.js"></script>
        <script src="<?php echo site_url();?>js/bootstrap-dropdown.js"></script>
    </body>
</html>