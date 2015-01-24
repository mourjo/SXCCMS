<!doctype HTML>
<html lang="en">
    <head>
        <title>Reports</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="<?php echo site_url();?>css/site.css" />

        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script type="text/javascript">
        function reload(ch)
        {
            if(ch==1)
            {
                if(document.data.Department.value != "none")
                {
                    
                    document.data.Year.value = "none";
                    document.data.Roll.value = "none";
                    document.data.submit();
                }
            }
            if(ch==2)
            {
                if(document.data.Department.value != "none")
                {
                    document.data.Roll.value = "none";
                    document.data.submit();
                }

            }
            if(ch==3)
            {
                if(document.data.Department.value != "none")
                {
                    document.data.submit();
                }

            }
        }
        
        </script>
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
                    <img src="<?php echo site_url()?>images/report.gif"/>
                   
                    </div>
            </div>
            <div class="span11">
                <form name="data" method="POST">
                    <div class="row center">
                        <div class="span3">
                            Department: <select class="span2" name="Department" onChange="reload(1);">
                                <option value='none'>Select Dept.</option>

                                <?php
                                    
                                    for($i=0;$i<count($dept);$i++)
                                    {
                                        if(isset($DepartmentSelected) && $DepartmentSelected == $dept[$i]['Code'] )
                                            echo "<option selected=selected value='".$dept[$i]['Code']."'>".$dept[$i]['Code']."</option>";
                                        else
                                            echo "<option value='".$dept[$i]['Code']."'>".$dept[$i]['Code']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="span3">
                            Year: <select class="span2" name="Year" onChange="reload(2);">
                                <option value="none">Select Year</option>
                                <?php
                                    if(isset($year))
                                    {    
                                        for($i=1; $i <= $year; $i++)
                                        {
                                            if(isset($YearSelected) && $YearSelected == $i )
                                                echo "<option selected=selected value='".$i."'>".$i."</option>";
                                            else
                                                echo "<option value='".$i."'>".$i."</option>";

                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        
                        <div class="span3">
                            Roll: <select class="span2" name="Roll" onChange="reload(3);">
                                <option value="none">Select Roll No.</option>
                                <?php
                                    if(isset($roll) && count($roll)>0)
                                    {    
                                        for($i=0; $i < count($roll); $i++)
                                        {
                                            if(isset($RollSelected) && $RollSelected == $roll[$i]['Roll'] )
                                                echo "<option selected=selected value='".$roll[$i]['Roll']."'>".$roll[$i]['Roll']."</option>";
                                            else
                                                echo "<option value='".$roll[$i]['Roll']."'>".$roll[$i]['Roll']."</option>";

                                        }
                                    }
                                    else echo "<option value='none'>No data</option>"
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="well" style="margin-top: 10px">
                    <?php
                    $flag = 1;
                        if (isset($title))
                            echo "<p><strong>".$title."</strong></p>";
                        if(isset($credits) && count($credits)>0 && !(isset($roll) && count($roll)>0))//if deptSelected
                        {

                            echo "<table class='table table-condensed'>
                            <thead>
                                <tr>
                                    <th>Name of Student</th> 
                                    <th>Roll Number</th>
                                    <th>Year</th>
                                    <th>Normal Credits</th>

                                    <th>Social Credits</th>
                                </tr>
                            </thead>";

                            for($i = 0; $i < count($credits); $i++)
                                echo "<tr><td>"
                                    .$name[$i]
                                    ."</td><td>"
                                    .$Roll[$i]
                                    ."</td><td>"
                                    .$Year[$i]
                                    ."</td><td>"
                                    .($credits[$i]['Amount'] >= 30 ? intval($credits[$i]['Amount']/30)." atomic unit and ".($credits[$i]['Amount']-intval($credits[$i]['Amount']/30)*30) :$credits[$i]['Amount'])." hrs"
                                    ."</td><td>"
                                    .$credits[$i]['Social']
                                    ."</td></tr>";


                            echo "</table>";
                            
                        }
                        else if(isset($credits) && count($credits)>0 && isset($roll) && count($roll)>0)//either year or roll selected
                        {
                            if(isset($RollSelected))
                            {
                                echo "<table class='table table-condensed'>
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Amount of credits awarded</th>
                                        <th>Department</th>
                                        <th>Social Credit?</th>
                                    </tr>
                                </thead>";
                                //FirstName, LastName, Roll, EventName, Unit, Amount, credits.Department Social
                            for($i = 0; $i < count($credits); $i++)
                                    echo "<tr><td>".$credits[$i]['EventName']."</td><td>".$credits[$i]['Amount']." ".($credits[$i]['Amount']>1?($credits[$i]['Unit'] =='atomic'?'atomic units':'hours'):($credits[$i]['Unit'] =='atomic'?'atomic unit':'hour'))."</td><td>".$credits[$i]['Department']."</td><td>".($credits[$i]['Social']==0?"No":"Yes")."</td></tr>";
                            echo "</table>";
                                
                            }
                            else if(isset($YearSelected))
                            {
                                echo "<table class='table table-condensed'>
                                <thead>
                                    <tr>
                                        <th>Name of Student</th> 
                                        <th>Roll Number</th>
                                        <th>Year</th>
                                        <th>Amount of credits awarded</th>
                                        
                                        <th>Social Credits</th>
                                    </tr>
                                </thead>";
                           
                                for($i = 0; $i < count($credits); $i++)
                                    echo "<tr><td>"
                                        .$name[$i]
                                        ."</td><td>"
                                        .$Roll[$i]
                                        ."</td><td>"
                                        .$Year[$i]
                                        ."</td><td>"
                                        .($credits[$i]['Amount'] >= 30 ? intval($credits[$i]['Amount']/30)." atomic unit and ".($credits[$i]['Amount']-intval($credits[$i]['Amount']/30)*30) :$credits[$i]['Amount'])." hrs"
                                        ."</td><td>"
                                        .$credits[$i]['Social']
                                        ."</td></tr></table>";

                            }
                            
                        }                        
                        else if (!(isset($roll) && count($roll)>0)||(isset($RollSelected) && $RollSelected=='none') || (!isset($RollSelected)))
                        {
                            echo "No result to display";
                            $flag = 0;
                        }
                        if($flag!=0)
                        {
                            echo "<form method='POST' action='".site_url()."admin/printReport'>";
                            echo "<input type='hidden' value='".(isset($DepartmentSelected) ? $DepartmentSelected : 0)."' name = 'dept'/>";
                            echo "<input type='hidden' value='".(isset($RollSelected) ? $RollSelected : 0)."' name = 'roll'/>";
                            echo "<input type='hidden' value='".(isset($YearSelected) ? $YearSelected : 0)."' name = 'year'/>";
                            echo "<input target='blank' type='submit' value='Print'/></form>";
                        }
                    ?>
                </div>
            </div>
        </div>
        <script src="<?php echo site_url();?>js/jquery.js"></script>
        <script src="<?php echo site_url();?>js/bootstrap-dropdown.js"></script>
    </body>
</html>