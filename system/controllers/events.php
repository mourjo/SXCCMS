<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Events extends CI_Controller {//roles to be dne

        public function index($p=1)
        {
            $this->load->model('event');
            $total = $this->event->countAll();
            
            $perPage = 10;
            
            $pageLimit = $total > $perPage ? ceil($total/$perPage) : 1;
            if($p>$pageLimit || $p<1)
            {
                show_404('page');
                return;
            }
            
            $p = ($p-1) * $perPage;
            
            $events = $this->event->all($p, $perPage);
            
            $data = array('events' => $events);
            $this->load->library('pagination');

            $config['base_url'] = site_url().'events/index/';
            $config['total_rows'] = $total;
            $config['per_page'] = $perPage; 
            $config['use_page_numbers'] = TRUE;
            $config['use_page_numbers'] = TRUE;
            
            
            

            $this->pagination->initialize($config); 
            $data['links'] = $this->pagination->create_links();
           
            $this->load->view('events/all_view', $data);
        }
	public function create()
        {
            
            if(!$this->session->userdata('UserID') || ($this->session->userdata('UserType') != 'section' && $this->session->userdata('UserType') != 'system' && $this->session->userdata('UserType') != 'dept'))
            {
                redirect(site_url().'main', 'location');
                return;
            }
            $this->load->helper('array');
            
            $arr = $this->input->post();
           
            $POSTED = elements(array('eName', 'dd' ,'mm', 'yy', 'dd1', 'mm1', 'yy1', 'social', 'eDetails','creditType','roleCount', 'choice'), $arr);
            $POSTED['dept'] = $this->session->userdata('Department');
            $POSTED['social'] = $POSTED['social'] == '1' ? 1 : 0;
            $POSTED['creditType'] = $POSTED['creditType'] == 1 ? 'hours' : 'atomic';
            $POSTED['stdt'] = $POSTED['yy'].'-'.$POSTED['mm'].'-'.$POSTED['dd'];
            $POSTED['edt'] = $POSTED['yy1'].'-'.$POSTED['mm1'].'-'.$POSTED['dd1'];
            
            if($POSTED['eName'])
            {
                $i = 1;
                $roles = array();
                $credits = array();
                $visible = array();               
                $i = 0;
                $j = 0;
                while($i<=$POSTED['roleCount'])
                {
                    $index = "role".$i;
                    if(isset($arr[$index]))
                    {
                        $roles[$j] = $arr[$index];
                        $index = "credit".$i;
                        $credits[$j] = $arr[$index];
                        $index = "check".$i;
                        $arr[$index] = isset($arr[$index]) ? 1 : 0;
                        $visible[$j] = $arr[$index];
                        $j++;
                    }
                    
                    $i++;
                }
                
                $POSTED['roleCount'] = $j;
                
                $POSTED['roles'] = $roles;
                $POSTED['credits'] = $credits;
                $POSTED['visible'] = $visible;
                
                $this->load->model('event');
                if($POSTED['choice']=='open')
                    $this->event->create($POSTED,0);
                else
                    $this->event->create($POSTED,1);
                header("Refresh:2; URL=".site_url()."events/view");
                $d = array ( 'message' => "Event created",
                                'image' => "events.gif");
                $this->load->view('displayPrompt_view', $d);
            }
            else
            {
                $data['save'] = 1;
                $this->load->view('events/create_view', $data);
                
            }
        }
        
	public function view($p=1)
        {
            if(!$this->session->userdata('UserID')) 
            {
                redirect(site_url(), 'location');
                return;
            }
            $userType = $this->session->userdata("UserType");
            if($userType == 'dept' || $userType == 'system' || $userType == 'section')
            {
                $perPage = 10;
                $this->load->model('event');
                
                $total = $this->event->countDeptEvents($this->session->userdata("Department"));
                
                
                $pageLimit = $total > $perPage ? ceil($total/$perPage) : 1;
                if($p>$pageLimit || $p<1)
                {
                    show_404('page');
                    return;
                }
                $p = ($p-1) * $perPage;
                $events = $this->event->details($this->session->userdata("Department"), $p, $perPage);

                $data = array('events'=>$events);
                
                $this->load->library('pagination');

                $config['base_url'] = site_url().'events/view/';
                $config['total_rows'] = $total;
                $config['per_page'] = $perPage;
                $config['use_page_numbers'] = TRUE;
                $config['use_page_numbers'] = TRUE;
                

                $this->pagination->initialize($config); 
                $data['links'] = $this->pagination->create_links();
                
                $this->load->view('events/event_view',$data);
            }
            else
            {
                redirect(site_url().'events', 'location');
                return;
            }
            
        }
        
	public function delete($EID)
        {
            if(!$this->session->userdata('UserID') || $this->session->userdata("UserType") == 'student' || !$EID) 
            {
                redirect(site_url().'main', 'location');
                return;
            }
            $this->load->model('event');
            $this->event->delete($EID);
            header("Refresh:2; URL=".site_url()."events/view");
            $d = array ( 'message' => "Event deleted",
                                'image' => "events.gif");
                $this->load->view('displayPrompt_view', $d);

        }
        
	public function edit($EID)
        {
            if(!$this->session->userdata('UserID') || $this->session->userdata("UserType") == 'student'|| $this->session->userdata("UserType") == 'cr' || !$EID) 
            {
                redirect(site_url().'main', 'location');
                return;
            }
            $this->load->model('event');
            $getDept = $this->event->getDepartment($EID);
            $status = $this->event->getStatus($EID);

            if($getDept['Department'] != $this->session->userdata('Department'))
            {
                redirect(site_url().'main', 'location');
                return;
            }
            $data = $this->event->getRoles($EID);
            $data['cType'] = $data['details'][0]['Unit'] == 'atomic' ? 0 : 1;
            
            $this->session->set_userdata('EID',$EID);
            
            
            $this->load->helper('array');
            
            $arr = $this->input->post();
          
            
            
            $POSTED = elements(array('eName', 'dd' ,'mm', 'yy', 'dd1', 'mm1', 'yy1', 'social', 'eDetails','creditType', 'roleCount', 'credit0', 'choice'), $arr);
            $data['save'] = $status == -1 ? 1 : 0;
            if($POSTED['eName'])
            {
                $POSTED['dept'] = $this->session->userdata('Department');
                $POSTED['social'] = $POSTED['social'] == '1' ? 1 : 0;
                if(isset($arr) && count($arr)>0)
                $data['cType'] = $arr['creditType'] == '1' ? 1 : 0;
                $POSTED['creditType'] = $POSTED['creditType'] == '1' ? 'hours' : 'atomic';
                $POSTED['stdt'] = $POSTED['yy'].'-'.$POSTED['mm'].'-'.$POSTED['dd'];
                $POSTED['edt'] = $POSTED['yy1'].'-'.$POSTED['mm1'].'-'.$POSTED['dd1'];
                $POSTED['EID'] = $this->session->userdata('EID');
                $data['EID'] = $this->session->userdata('EID');
                
                
                
                $this->session->unset_userdata('EID');
                
                $i = 0;
                $j = 0;
                $roles = array();
                $credits = array();
                $visible = array();
                
                while($i<=$POSTED['roleCount'])
                {
                    $index = "role".$i;
                    if(isset($arr[$index]))
                    {
                        $roles[$j] = $arr[$index];
                        $index = "credit".$i;
                        $credits[$j] = $arr[$index];
                        $index = "check".$i;
                        $arr[$index] = isset($arr[$index]) ? 1 : 0;
                        $visible[$j] = $arr[$index];
                        $j++;
                    }
                    
                    $i++;
                }
                
                $POSTED['roleCount'] = $j;
                $POSTED['roles'] = $roles;
                $POSTED['credits'] = $credits;
                $POSTED['visible'] = $visible;
                
                $this->load->model('event');
                if($POSTED['choice']=='open')
                {
                    if($status == -1)
                        $this->event->create($POSTED,12);
                    else
                    $this->event->create($POSTED,10);
                }
                else
                    $this->event->create($POSTED,11);
                
                header("Refresh:2; URL=".site_url()."events/view");
                $d = array ( 'message' => "Event updated",
                                'image' => "events.gif");
                $this->load->view('displayPrompt_view', $d);

            }
            else
            {
                $data['EID'] = $EID;
                if($status==-1)
                    $this->load->view('events/saved_view',$data);
                else
                    $this->load->view('events/create_view',$data);
            }
            
        }
        
        
        
	public function viewById($EID=-1){}
	public function register($EID=-1)
        {
            if(!$this->session->userdata('UserID') || $this->session->userdata("UserType") == 'system'|| $this->session->userdata("UserType") == 'dept' || $EID==-1) 
            {
                redirect(site_url().'main', 'location');
                return;
            }
            $this->load->model('event');
            if ($this->event->isRegistered($EID, $this->session->userdata('UserID')) == 1)
            {
                header("Refresh:2; URL=".site_url()."events/");
                $d = array ('message' => "You are Already Registered, Check Other Events Attend",
                            'image' => "events.gif");
                $this->load->view('displayPrompt_view', $d);

                return;
            }
            $this->load->helper("array");
            $arr = $this->input->post();
            if(isset($arr['Posted']))
            {
                if(isset($arr['roleSelect']))
                {
                    $a = $this->input->post('roleSelect');
                    $this->event->register($EID, $this->session->userdata('UserID'), $a);
                    
                    header("Refresh:2; URL=".site_url()."events/");
                    $d = array ( 'message' => "You have been registered for this event",
                                'image' => "events.gif");
                    $this->load->view('displayPrompt_view', $d);

                }
            }
            else
            {
                
                $data = $this->event->getPublicDetails($EID);
                
                
                $this->load->view('events/register_view',$data);
            }
        }
	public function close($EID)
        {

            if(!$this->session->userdata('UserID') || $this->session->userdata("UserType") == 'student'|| $this->session->userdata("UserType") == 'cr' || !$EID) 
            {
                redirect(site_url().'main', 'location');
                return;
            }
            $this->load->model('event');
            $getDept = $this->event->getDepartment($EID);
            
            if($getDept['Department'] != $this->session->userdata('Department'))
            {
                redirect(site_url().'main', 'location');
                return;
            }
            
            $this->event->close($EID);
            header("Refresh:2; URL=".site_url()."events/view");
                            $d = array ( 'message' => "Event closed",
                                'image' => "events.gif");
            $this->load->view('displayPrompt_view', $d);

            
        }
        
        public function open($EID)
        {
  
            if(!$this->session->userdata('UserID') || $this->session->userdata("UserType") == 'student'|| $this->session->userdata("UserType") == 'cr' || !$EID) 
            {
                redirect(site_url().'main', 'location');
                return;
            }
            $this->load->model('event');
            $getDept = $this->event->getDepartment($EID);
            
            if($getDept['Department'] != $this->session->userdata('Department'))
            {
                redirect(site_url().'main', 'location');
                return;
            }
            
            $this->event->open($EID);
            header("Refresh:2; URL=".site_url()."events/view");
            
                            $d = array ( 'message' => "Event open",
                                'image' => "events.gif");
            $this->load->view('displayPrompt_view', $d);

            
        }
        
	public function viewAssociated($EID=-1, $p=1)
        {
            if(!$this->session->userdata('UserID') || $this->session->userdata("UserType") == 'student'|| $this->session->userdata("UserType") == 'cr' || $EID==-1) 
            {
                redirect(site_url(), 'location');
                return;
            }
            $this->load->model('event');
            $getDept = $this->event->getDepartment($EID);

            if($getDept['Department'] != $this->session->userdata('Department'))
            {
                redirect(site_url(), 'location');
                return;
            }
            
            
            $this->load->helper('array');
            $POSTED = $this->input->post();
            if(isset($POSTED['posted']))
            {
                $count = count($POSTED);
                for($i=0; $i<$count; $i++)
                {
                    $roleIndex = "Role".$i;
                    $stateIndex = "State".$i;
                    $UIDIndex = "UID".$i;
                    $accIndex = "accredit".$i;
                    if(isset($POSTED[$roleIndex]))
                    {

                        if(isset($POSTED[$accIndex]))
                        {
                            $this->event->updateState($EID, $POSTED[$roleIndex], 'accredited', $POSTED[$UIDIndex]);

                            $this->accredit($EID,$POSTED[$UIDIndex],$POSTED[$roleIndex]);
                        }
                        else
                        $this->event->updateState($EID, $POSTED[$roleIndex], $POSTED[$stateIndex], $POSTED[$UIDIndex]);

                    }
                   
                    
                }

                header("Refresh:2; URL=".site_url()."events/view");
                
                $d = array ( 'message' => "Event updated",
                    'image' => "events.gif");
                $this->load->view('displayPrompt_view', $d);

            }
            else
            {
                
                $total = $this->event->countAssociated($EID);
                $perPage = 10;

                $pageLimit = $total > $perPage ? ceil($total/$perPage) : 1;
                if($p>$pageLimit || $p<1)
                {
                    show_404('page');
                    return;
                }

                $p = ($p-1) * $perPage;
                $assoc = $this->event->getAssociated($EID, $p, $perPage);
                $eName = $this->event->geteventNameAndStatus($EID);
                $data = array('associated' => $assoc,
                                'count' => count($assoc),
                                'eName' => $eName);
                
                $this->load->library('pagination');

                
                $data['EID'] = $EID;
                $det = $this->event->getRoles($EID);
                $data['roles'] = $det['roles'];
                $data['details'] = $det['details'][0];
                $config['uri_segment'] = 4;
                
                $config['base_url'] = site_url().'events/viewAssociated/'.$EID;
                $config['total_rows'] = $total;
                $config['per_page'] = $perPage;
                $config['use_page_numbers'] = TRUE;
                
                
                
                $this->pagination->initialize($config); 
                $data['links'] = $this->pagination->create_links();

                //$this->accredit('2323');
                $this->load->view('events/associated_view', $data);
            }
        }
	public function accredit($EID=-1, $UID, $role)
        {
            if(!$this->session->userdata('UserID') || $this->session->userdata("UserType") == 'student'|| $this->session->userdata("UserType") == 'cr' || $EID==-1) 
            {
                redirect(site_url().'main', 'location');
                return;
            }
            $this->load->model('event');
            $getDept = $this->event->getDepartment($EID);

            if($getDept['Department'] != $this->session->userdata('Department'))
            {
                redirect(site_url().'main', 'location');
                return;
            }
            $this->event->accredit($EID, $UID, $role);
        }
        function profile($EID=-1)
        {
            if($EID==-1) 
            {
                redirect(site_url(), 'location');
                return;
            }
            $this->load->model("event");
            $data = $this->event->getPublicDetails($EID);          
            
            $this->load->view('events/register_view',$data);
        }
}