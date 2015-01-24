<!doctype HTML>
<html lang="en">
    
    <head>
        <title>Create New Department</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="<?php echo site_url();?>css/site.css" />
       
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->    
        <script type="text/javascript">//src="<?php echo site_url();?>js/newDepartment.js" 
            var c=0;
            function hide()
            {

                if(document.createDept.type.value != "1")
                {
                    document.getElementById("years").style.display="none";
                    document.getElementById("yearsPrompt").style.display="none";
                    if(c!=0)
                    {
                        parent = document.getElementById("myTable");
                        var i = 1;
                        while(i<=c)
                        {
                            var t = "tr"+i;
                            parent.removeChild(document.getElementById(t));
                            i++;
                        }
                        c=0;
                    }
                }
                else
                {
                    document.getElementById("years").style.display="block";
                    document.getElementById("yearsPrompt").style.display="block";
                }
            }
            function validate()
            {
                var err = new Array();
                var count = 0; 
                if(document.getElementsByName("dname")[0].value.length == 0)
                {
                    err[count++] = "Department Name";
                }
                if(document.getElementsByName("dcode")[0].value.length == 0)
                {
                    err[count++] = "Department Code";
                }
                if(document.getElementsByName("Email")[0].value.length == 0)
                {
                    err[count++] = "Email";
                }
                else
                {
                    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                    var address = document.getElementsByName("Email")[0].value;
                    //var address = document.forms[form1].elements[Email].value
                    if(reg.test(address) == false) 
                    { 
                        err[count++] = "Enter Valid Email Address";
                    }
                }
                if(document.getElementsByName("type")[0].value == "-1")
                {
                    err[count++] = "Department Type";
                }
                 if(document.getElementsByName("dsection")[0].value == "-1")
                {
                    err[count++] = "Department Section";
                }
                if(document.getElementsByName("type")[0].value == "1" && document.getElementById("years").value.length == 0)
                {
                    err[count++] = "Years";
                }


                var yr = 1;
                while(yr<=c)
                {
                    var textName;

                    textName = "room"+yr;

                    if(document.getElementsByName(textName)[0].value.length==0)
                    {
                        err[count++] = "Room number of Year "+yr;

                    }
                    yr++;

                }
                if(document.getElementsByName("Password1")[0].value.length == 0 || (document.getElementsByName("Password1")[0].value != document.getElementsByName("Password")[0].value))
                {
                    err[count++] = "Check Password";
                }
                if(isNaN(document.getElementById("years").value))
                {
                    err[count++] = "Years has to be a numeric value";
                }
                if(document.getElementsByName("type")[0].value==1 && (document.getElementById("years").value>5||document.getElementById("years").value<1))
                {
                    err[count++] = "Years should be in the range 1-5";
                }
                if(count==0)
                {
                    document.createDept.submit();
                }
                else
                {
                    var disp = "Enter the following:\n\n";
                    for(var i=0; i<count;i++)
                    {
                        disp = disp  + err[i] + "\n";
                    }
                    alert(disp);
                }

            }
            function newDeptYear()
            {
                var no = document.getElementById("years").value;
                if(no)
                {
                    if(no>5 || no<1)
                    {
                        alert("Years have to be in the range 1-5");
                        document.getElementById("years").focus;
                    }
                    else
                    {
                        if(c!=0)
                        {
                            parent = document.getElementById("myTable");
                            var i = 1;
                            while(i<=c)
                            {
                                var t = "tr"+i;
                                parent.removeChild(document.getElementById(t));
                                i++;
                            }
                        }
                        c=no;
                        parent = document.getElementById("myTable");

                        var yr = 1;
                        while(yr<=c)
                        {                    

                            prompt = "roomPrompt"+yr;
                            textName = "room"+yr;
                            x = "<td id=\""+prompt+"\"> Enter Room Number for Year "+yr+": </td>";
                            x = x + "<td> <input type=\"text\" name=\""+textName+"\"/></td>";
                            newRow = document.createElement("tr");
                            t = "tr"+yr;
                            newRow.setAttribute('id',t);
                            newRow.innerHTML = x;
                            parent.appendChild(newRow);

                            yr++;
                        }
                    }
                }
                else if(c!=0)
                {
                    parent = document.getElementById("myTable");
                    var i = 1;
                    while(i<=c)
                    {
                        var t = "tr"+i;
                        parent.removeChild(document.getElementById(t));
                        i++;
                    }
                    c=0;
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
            <div class="row page-header">
                <div class="span12" align="right">
                    <img src="<?php echo site_url();?>images/department.gif" />
                </div>
            </div>
            <div class="row alert">
            <?php
            
                echo $err;
            ?>
            </div>
            <div class="row well">
                <form name="createDept" name="create" method="POST" action="<?php echo site_url();?>admin/createDepartment" id="form1">
                    <table id="myTable">
                        <tr><td>Department Name:</td><td><input type="text" name="dname" id="dname"></td></tr>
                        <tr><td>Department Code:</td><td><input type="text" placeholder="Eg.: XYZA" name="dcode"></td></tr>
                        <?php
                            if($this->session->userdata('UserType')=='section')
                            {
                                //echo $this->session->userdata('Section')=='bsc'?'BSc':$this->session->userdata('Section')=='ba'?'BA':$this->session->userdata('Section')=='msc'?'BSc':'l';
                            
                                
                                echo "\n<input type='hidden' name='dsection' value='".$this->session->userdata('Section')."'/>";
                                  
                            }
                            else{?>
                        <tr><td>Department Section:</td>
                            <td>
                                <select name="dsection">
                                    <option value="-1">Select Section</option>
                                    <option value="ba">BA</option>
                                    <option value="bsc">BSc</option>
                                    <option value="msc">MSc</option>
                                    <option value="bComM">BCom(M)</option>
                                    <option value="bComE">BCom(E)</option>
                                    <option value="bba">BBA</option>
                                    <option value="bed">BEd</option>
                                    <option value="0">N/A</option>
                                </select>
                            </td>
                        </tr>
                                    <?php
                                }?>
                            
                        <tr><td>Admin Username: </td><td> <input type="text" placeholder="Eg.: admin@sxccal.edu" name="Email"></td></tr>   
                        <tr><td> Password: </td><td> <input type="password" name="Password1" /></td></tr>
                        <tr><td> Confirm Password: </td><td> <input type="password" name="Password" /></td></tr>
                        <tr><td>Type:</td>
                            <td><select name="type" onChange="hide();" >
                                    <option value="-1">Select Department Type</option>
                                    <option value="1">Academic</option>
                                    <option value="0">Non-Academic</option>
                                    <?php if($this->session->userdata('UserType')=='system'){?>
                                    <option value="2">Section</option>
                                    <?php
                                    }?>
                            </select></td>
                        </tr>

                        <tr>
                            <td id="yearsPrompt" style="display: none">Number of Years:</td>
                            <td ><input type="text" style="display: none" placeholder="Input number of Years" name="year" id="years"  onkeyup="newDeptYear();" /></td>
                        </tr>


                    </table>
                    <input class="btn btn-success btn-large offset2" type="button" onClick="validate();" value="Create"/>
                    <br/>
                    <a class="btn btn-warning" href="<?php echo site_url();?>main">Cancel</a>
                </form>
            </div>
            
        </div>
        <script src="<?php echo site_url();?>js/jquery.js"></script>
        <script src="<?php echo site_url();?>js/bootstrap.min.js"></script>
    </body>
    
</html>
