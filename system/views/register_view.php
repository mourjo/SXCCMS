<!doctype HTML>
<html lang="en">
    <head>
        <title>New User Registration</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="<?php echo site_url();?>css/site.css" />
       
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <script type="text/javascript">
            
            function checkLength(name, id)
            {
                if(document.getElementsByName(name)[0].value.length < 6)
                {
                    document.getElementById(id).setAttribute('class', 'control-group error');
                }
                else
                {
                    document.getElementById(id).setAttribute('class', 'control-group success');
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
                            <a href=""></a>
                        </li>
                        <li class="">
                            <a href=""></a>
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
                        <?php if($this->session->userdata('UserID')){?>
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
                        </li><?php }?>
                    </ul>
                    
                </div>
            </div>
        </div>

    <body>
        <div class="container">
            
            <div class="row page-header">
                <div class="span12" align="right">
                    <img src="<?php echo site_url();?>images/register.gif" />
                </div>
            </div>
            <div >
                <p>
                    <?php
                        echo "<div class='alert'>".$errorMessage."</div>";
                        $url = site_url()."accounts/register/".$type;
                    ?>
                </p>
                <form class="well form-horizontal" method="POST" action="<?php echo $url;?>">
                    <input type ="hidden" name="modify" value="<?php echo $modify;?>"/>
                    <fieldset>
                        <?php 
                        if($this->session->userdata('UserType') != 'system' && $this->session->userdata('UserType') != 'section')
                        {
                            if($modify ==0)
                            echo '<div class="control-group"><label class="control-label" for="Code">Registration Code:</label><div class="controls"><input type="text" id="Code" name="Code" /></div></div>';
                        }
                        if($modify == 0)
                        {
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="FirstName">First Name:</label>
                                <div class="controls">
                                    <input type="text" id="FirstName" name="FirstName" value="<?php if (isset($_POST['FirstName'])) echo $_POST['FirstName'];?>"/>
                                </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="LastName">Last Name:</label>
                                <div class="controls">
                                    <input type="text" id="LastName" name="LastName" value="<?php if (isset($_POST['LastName'])) echo $_POST['LastName'];?>"/>
                                </div>
                        </div>
                        <?php
                        }?>
                        <div class="control-group">
                            <label class="control-label" for="Email">Email:</label>
                                <div class="controls">
                                    <input type="text" id="Email" name="Email" value="<?php if (isset($_POST['Email'])) echo $_POST['Email'];?>"/>
                                </div>
                        </div>
                        <?php
                        if($modify == 0)
                        {
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="Roll">Roll:</label>
                                <div class="controls">
                                    <input type="text" id="Roll" name="Roll" value="<?php if (isset($_POST['Roll'])) echo $_POST['Roll'];?>"/>
                                </div>
                        </div>
                        <?php
                        }
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="Room">Room:</label>
                                <div class="controls">
                                    <input type="text" id="Room" name="Room" value="<?php if (isset($_POST['Room'])) echo $_POST['Room'];?>"/>
                                </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="Department">Department:</label>
                            <div class="controls">
                                <select name="Department" id="Department">
                                <?php
                                    foreach($dept as $row)
                                    {   
                                        if (isset($_POST['Department']) && $_POST['Department'] == $row['Code'])
                                            echo "<option selected='selected'>".$row['Code']."</option>";
                                        else
                                            echo "<option>".$row['Code']."</option>";
                                    }
                                ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group"><!--might want to reduce width of drop-downs -->
                            <label class="control-label" for="Year">Year:</label>
                            <div class="controls">   
                                <select name="Year" id="Year">
                                    <?php
                                    for($i=1;$i <= 5;$i++)
                                    {   
                                        if (isset($_POST['Year']) && $_POST['Year'] == $i)
                                            echo "<option selected='selected'>".$i."</option>";
                                        else
                                            echo "<option>".$i."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        if($modify==0)
                        {?>
                            <div class="control-group" id="passCtrl">
                                <label class="control-label" for="Password1">Password:</label>
                                <div class="controls">
                                    <input onkeydown="checkLength('Password1', 'passCtrl')" type="password" id="Password1" name="Password1" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="Password">Confirm Password:</label>
                                <div class="controls">
                                    <input type="password" id="Password" name="Password"/>
                                </div>
                            </div>
                        <?php
                        }?>
                    </fieldset>
                    <a class ="btn btn-small btn-warning" href="<?php echo site_url();?>"><i class="icon-chevron-left"></i>  Cancel</a>
                    <input type="submit" class="btn btn-large btn-primary offset2" value="Register" />
                </form>
               
            </div>
        </div>
        <script src="<?php echo site_url();?>js/jquery.js"></script>
        <script src="<?php echo site_url();?>js/bootstrap.min.js"></script>
    </body>
</html>