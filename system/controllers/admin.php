<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function createDepartment()
        {
            //change view if section
            if(!$this->session->userdata('UserID') || ($this->session->userdata('UserType') != 'system' && $this->session->userdata('UserType') != 'section'))
            {
                redirect(site_url().'main', 'location');
                return;
            }
            
            $this->load->helper('array');
            $data = array(
                    'err' => "");
            
            $this->load->model("block");
            $arr = $this->input->post();
            $POSTED = elements(array('year', 'Email', 'dname', 'dcode', 'dsection', 'Password1', 'Password', 'type', 'room1', 'room2', 'room3', 'room4', 'room5'), $arr);
            if(!($POSTED['Email']  && $POSTED['dname'] && $POSTED['dcode'] && $POSTED['Password1'] && $POSTED['Password1']))
            {
                $data['err'] = "Please Input Data For All Fields";
                $this->load->view('createDepartment_view',$data);
                return;
            }
            $POSTED['year'] = isset($POSTED['year']) ? intval($POSTED['year']) : 0;
            $isDept = $POSTED['type'] == '1' ? "dept" : ($POSTED['type'] == '2' ? "section" : "dept");
            

            $POSTED['type'] = $POSTED['type']=='1'?"academic":($POSTED['type']=='2'?"admin":"nonacademic");
            $err = false;
            if($POSTED['dname'])
            {
                if($this->block->departmentExists($POSTED['dcode'], 'na', $POSTED['dname']) == 1)
                {
                    if ($POSTED['type']=='nonacademic')
                        $POSTED['dsection'] = 'na';
                    $this->block->createDepartment($POSTED['dname'], $POSTED['dcode'], $POSTED['type'], $POSTED['dsection'], $POSTED['year']);
                    $dt = array(
                        'FirstName' => $POSTED['dname'],
                        'LastName' => 'Department',
                        'Department' => $POSTED['dcode'],
                        'Roll' => 0,
                        'Email' => $POSTED['Email'],
                        'Password' => $POSTED['Password']
                        );                    

                    $this->load->model('user');                    
                    $res = $this->user->register($dt, $isDept);
                    if($res->error)
                    {
                        $err = true;
                        $this->block->removeDepartment($POSTED['dcode']);
                        $data['err'] = "Username already exists. Please select another username.";
                        $this->load->view('createDepartment_view',$data);
                    }
                    else
                    {
                        if($POSTED['room1'])
                        {
                            $bockID = $this->block->createBlock($POSTED['dcode'],1,$POSTED['room1']);

                            if($POSTED['room2'])
                            {
                                $bockID = $this->block->createBlock($POSTED['dcode'],2,$POSTED['room2']);

                                if($POSTED['room3'])
                                {
                                    $bockID = $this->block->createBlock($POSTED['dcode'],3,$POSTED['room3']);

                                    if($POSTED['room4'])
                                    {
                                        $bockID = $this->block->createBlock($POSTED['dcode'],4,$POSTED['room4']);

                                        if($POSTED['room5'])
                                        {
                                            $bockID = $this->block->createBlock($POSTED['dcode'],5,$POSTED['room5']);

                                        }
                                    }
                                }
                            }
                        }
                    }            
                }
                else
                {
                    if($POSTED['dcode'] && $POSTED['type'])
                    {
                        $data['err']="Please Enter Proper Details";
                        $err = true;
                        
                    }
                    $this->load->view('createDepartment_view',$data);
                }
                if(!$err && $POSTED['dname'])
                {
                    header("Refresh:2; URL=".site_url());
                    $d = array ( 'message' => "Department created",
                                'image' => "department.gif");
                    $this->load->view('displayPrompt_view', $d);
                }
            }
            else
            {
                $this->load->view('createDepartment_view',$data);
            }           
        }
        public function generateReport()
        {
            if(!$this->session->userdata('UserID') || ($this->session->userdata('UserType') != 'section'))
            {
                redirect(site_url().'main', 'location');
                return;
            }
            $this->load->model('user');
            $data['dept'] = $this->user->fetchAcademicDepartments($this->session->userdata('Section'));
            $this->load->helper('array');
            $POSTED = $this->input->post();
            
            if($POSTED['Department'] && $POSTED['Year']=="none" && $POSTED['Roll']=="none")
            {
                $data['year'] = $this->user->getMaxDeptYear($POSTED['Department']);
                $data['DepartmentSelected'] = $POSTED['Department'];
                $credits = $this->user->getCreditsByDept($POSTED['Department']);
                
                $c=-1;
                if(count($credits)>0)
                {
                    for($i=0; $i < count($credits); $i++)
                    {
                        if($credits[$i]['UserID'] == -1)
                            continue;
                        else
                        {
                            $c++;
                            $data['credits'][$c]['Social'] = 0;
                            $data['credits'][$c]['Amount'] = 0;
                            $data['name'][$c] = $credits[$i]['FirstName']." ".$credits[$i]['LastName'];
                            $data['Roll'][$c] = $credits[$i]['Roll'];
                            $data['Year'][$c] = $credits[$i]['Year'];

                            if($credits[$i]['Social'] == 1)
                                $data['credits'][$c]['Social'] = $credits[$i]['Unit'] == 'atomic' ? intval($credits[$i]['Amount'])*30 : intval($credits[$i]['Amount']);
                            else
                                $data['credits'][$c]['Amount'] = $credits[$i]['Unit'] == 'atomic' ? intval($credits[$i]['Amount'])*30 : intval($credits[$i]['Amount']);

                        }
                        for($j=$i+1; $j < count($credits); $j++)
                        {
                            if($credits[$i]['UserID'] == $credits[$j]['UserID'] && $credits[$j]['UserID'] != -1)
                            {
                                if($credits[$j]['Social'] == 1)
                                    $data['credits'][$c]['Social'] = intval($data['credits'][$c]['Social']) + ($credits[$j]['Unit'] == 'atomic' ? intval($credits[$j]['Amount'])*30 : intval($credits[$j]['Amount']));
                                else
                                    $data['credits'][$c]['Amount'] = intval($data['credits'][$c]['Amount']) + ($credits[$j]['Unit'] == 'atomic' ? intval($credits[$j]['Amount'])*30 : intval($credits[$j]['Amount']));
                                $credits[$j]['UserID'] = -1;
                                
                            }
                        }
                    }
                    
                    $data['title'] = "<p style='font-size: 17px;'>Consolidated Credit Report of Department ".ucfirst($POSTED['Department']).":</p>";
                }
            }
            else if($POSTED['Department'] && $POSTED['Year'] != "none" && $POSTED['Roll']=="none")
            {
                $data['year'] = $this->user->getMaxDeptYear($POSTED['Department']);
                $data['DepartmentSelected'] = $POSTED['Department'];
                $data['YearSelected'] = $POSTED['Year'];
                $data['Year'] = $POSTED['Year'];
                $data['roll'] = $this->user->getRollsOfDept($POSTED['Department'],$POSTED['Year']);
                $credits = $this->user->getCreditsByDept($POSTED['Department'], $POSTED['Year']);
                $c=-1;
                if(count($credits)>0)
                {
                    for($i=0; $i < count($credits); $i++)
                    {
                        if($credits[$i]['UserID'] == -1)
                            continue;
                        else
                        {
                            $c++;
                            $data['credits'][$c]['Social'] = 0;
                            $data['credits'][$c]['Amount'] = 0;
                            $data['name'][$c] = $credits[$i]['FirstName']." ".$credits[$i]['LastName'];
                            $data['Roll'][$c] = $credits[$i]['Roll'];
                            $data['Year'][$c] = $credits[$i]['Year'];


                            if($credits[$i]['Social'] == 1)
                                $data['credits'][$c]['Social'] = $credits[$i]['Unit'] == 'atomic' ? intval($credits[$i]['Amount'])*30 : intval($credits[$i]['Amount']);
                            else
                                $data['credits'][$c]['Amount'] = $credits[$i]['Unit'] == 'atomic' ? intval($credits[$i]['Amount'])*30 : intval($credits[$i]['Amount']);

                        }
                        for($j=$i+1; $j < count($credits); $j++)
                        {
                            if($credits[$i]['UserID'] == $credits[$j]['UserID'] && $credits[$j]['UserID'] != -1)
                            {
                                if($credits[$j]['Social'] == 1)
                                    $data['credits'][$c]['Social'] = intval($data['credits'][$c]['Social']) + ($credits[$j]['Unit'] == 'atomic' ? intval($credits[$j]['Amount'])*30 : intval($credits[$j]['Amount']));
                                else
                                    $data['credits'][$c]['Amount'] = intval($data['credits'][$c]['Amount']) + ($credits[$j]['Unit'] == 'atomic' ? intval($credits[$j]['Amount'])*30 : intval($credits[$j]['Amount']));
                                $credits[$j]['UserID'] = -1;
                                
                            }
                        }
                        
                        
                    }
                    
                    $data['title'] = "<p style='font-size: 17px;'>Consolidated Credit Report of Year ".$data['Year']." of Department ".ucfirst($POSTED['Department']).":</p>";
                }
            }
            else if($POSTED['Department'] && $POSTED['Year'] != "none" && $POSTED['Roll']!="none")
            {
                $data['year'] = $this->user->getMaxDeptYear($POSTED['Department']);
                $data['DepartmentSelected'] = $POSTED['Department'];
                $data['YearSelected'] = $POSTED['Year'];
                $data['Year'] = $POSTED['Year'];
                $data['RollSelected'] = $POSTED['Roll'];
                $data['roll'] = $this->user->getRollsOfDept($POSTED['Department'],$POSTED['Year']);
                
                
                $data['credits'] = $this->user->getCreditsByDept($POSTED['Department'],$POSTED['Year'],$POSTED['Roll']);
                if(count($data['credits'])>0)
                    $data['title'] = "<p style='font-size: 17px;'>Credit Report of Student</p> <br> Name: ".$data['credits'][0]['FirstName']." ".$data['credits'][0]['LastName']."<br>Roll: ".$POSTED['Roll']."<br>Year ".$data['Year']."<br>Department ".ucfirst($POSTED['Department']);
                else
                {
                    $details = $this->user->getUserDetailsByRoll($POSTED['Department'], $POSTED['Year'], $POSTED['Roll']);
                    $data['title'] = "<p style='font-size: 17px;'>Credit Report of Student</p> Name: ".$details['FirstName']." ".$details['LastName']."<br>Department: ".$POSTED['Department']."<br>Roll: ".$POSTED['Roll']."<br>Year:".$POSTED['Year']."<p><i>No credit awarded</i></p>";
                }
            }
            
            
            $this->load->view('generateReport_view', $data);
            return;
        }
        public function createCR()
        {
            if(!$this->session->userdata('UserID') || ($this->session->userdata('UserType') != 'section'))
            {
                redirect(site_url().'main', 'location');
                return;
            }
            
            $data['choice'] = 1;
            $this->load->view('CRcreate_view', $data);
            return;
        }
        
        public function deleteDepartment()
        {
            if(!$this->session->userdata('UserID') || ($this->session->userdata('UserType') != 'system' && $this->session->userdata('UserType') != 'section'))
            {
                redirect(site_url().'main', 'location');
                return;
            }
            
           $data = array(
                'err' => "");
           
            $dcode = $this->input->post('Dcode');
            $this->load->model('block');
            if($dcode)
            {     
                $this->block->removeDepartment($dcode);
                header("Refresh:2; URL=".site_url()."");
                $d = array ( 'message' => "Department deleted",
                        'image' => "department.gif");
                $this->load->view('displayPrompt_view', $d);           
            }
            else
            {
                $this->load->model('user');
                if($this->session->userdata('UserType')=='section')
                    $data['dept'] = $this->user->fetchAllDepartments($this->session->userdata('Section'));
                else
                    $data['dept'] = $this->user->fetchAllDepartments();
                
                $this->load->view('removeDept_view',$data);
            }
        }
        
        function printReport()
        {

            $this->load->helper('array');
            $POSTED = $this->input->post();
            $this->load->model('user');
            $flag = true;
            if($POSTED['dept'] && $POSTED['year'] != 0 && $POSTED['roll'] != 0 )
            {
            $credits = $this->user->getCreditsByDept($POSTED['dept'], $POSTED['year'], $POSTED['roll']);
            $data['credits'] = $credits;
            $data['POSTED'] = $POSTED; 
            }
            else if($POSTED['dept'] && $POSTED['year'] != 0)
            {
            $credits = $this->user->getCreditsByDept($POSTED['dept'], $POSTED['year']);
            $data['credits'] = $credits;

            $data['POSTED'] = $POSTED;
            }
            else if($POSTED['dept'])
            {
            $credits = $this->user->getCreditsByDept($POSTED['dept']);
            $data['credits'] = $credits;

            $data['POSTED'] = $POSTED;
            }
            else
                $flag = false;

            if(!$flag || count($credits)==0)
            {
                redirect(site_url().'admin/generateReport', 'location');
                return;
            }        
            $this->load->view('reportDocument_view',$data);
        }

        
        public function verifyReport()
        {
            if($this->input->post('code'))
            {
                $code = $this->input->post('code');
                $this->load->model('user');
                
                $creditsData = $this->user->reportDetailsByID($code);
                $credits = $creditsData['credits'];
                
                $creditSum = 0;
                $socialSum = 0;
                for($i = 0; $i < count($credits); $i++)
                {
                    if($credits[$i]['Unit'] == 'atomic')
                        $creditSum += intval($credits[$i]['Amount']) * 30;
                    else
                        $creditSum += intval($credits[$i]['Amount']);

                    if($credits[$i]['Social'] == '1')
                    {
                        if($credits[$i]['Unit'] == 'atomic')
                            $socialSum += intval($credits[$i]['Amount']) * 30;
                        else
                            $socialSum += intval($credits[$i]['Amount']);
                    }

                }
                $socialFull = $socialSum >= 30 ? 1 :  0;
                $data = array (
                        'credits' => $credits,  
                        'socialSum' => $socialSum,
                        'creditSum' => $creditSum,
                        'socialFull' => $socialFull,
                        'name' => $creditsData['userDetails'][0]['FirstName']." ".$creditsData['userDetails'][0]['LastName'],
                        'dept' => $creditsData['userDetails'][0]['Department'],
                        'roll' => $creditsData['userDetails'][0]['Roll'],
                        'year' => $creditsData['userDetails'][0]['Year']
                );

                if($credits == 0)
                {
                    $code = '-1';
                    
                }
                
            }
            else            
                $code = '0';
            $data['code'] = $code;
            $this->load->view('verifyReport_view', $data);
        }
        
	public function editDepartment(){}
	public function viewRegistry(){}
	public function editRegistry(){}
	public function viewActionLog(){}
	public function viewErrorLog(){}
}