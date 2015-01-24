<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blocks extends CI_Controller{

    public function view(){}//to have options for freezing and generating
    public function create()
    {   
        if(!$this->session->userdata('UserID') || ($this->session->userdata('UserType') != 'system' && $this->session->userdata('UserType') != 'section'))
        {
            redirect(site_url().'main', 'location');
            return;
        }

        $Year = $this->input->post('Year');
        $Room = $this->input->post('Room');
        $Department = $this->input->post('Department');
        $data = array('errorMessage' => '');
        
        if(strlen($Room) > 0)
        {
            $error = 0;
            if(!is_numeric($Room))
            {
                $error = 1;
                $data['errorMessage'] .= "Room number should be numeric<br />";
            }
            else
            {
                $this->load->model('block');
                $blockID = $this->block->fetchBlockID($Department, $Year, $Room);
                if($blockID != 0)
                {
                    $error = 1;
                    $data['errorMessage'] .= "Block already exists<br />";
                }
            }
            if($error == 0)
            {
                $this->block->createBlock($Department, $Year, $Room);
                header("Refresh:2; URL=".site_url()."main");
                $d = array ( 'message' => "Block created",
                                'image' => "blocks.gif");
                $this->load->view('displayPrompt_view', $d);
//                echo "Block created";
            }
            else
            {
                $this->load->model('user');
                if($this->session->userdata('UserType')=='section')
                    $data['dept'] = $this->user->fetchAcademicDepartments($this->session->userdata('Section'));
                else
                    $data['dept'] = $this->user->fetchAcademicDepartments();

                $this->load->view('newblock_view', $data);
            }

        }
        else
        {
            $this->load->model('user');
            if($this->session->userdata('UserType')=='section')
                $data['dept'] = $this->user->fetchAcademicDepartments($this->session->userdata('Section'));
            else
                $data['dept'] = $this->user->fetchAcademicDepartments();

            $this->load->view('newblock_view', $data);
        }
    }
    public function generateSet()
    {
        if(!$this->session->userdata('UserID') || ($this->session->userdata('UserType') != 'section'))
        {
            redirect(site_url().'main', 'location');
            return;
        }
        $data = array('errorMessage' => "");

        $this->load->model('block');      
        $this->load->model('user');
        
        $Year = $this->input->post('Year');
        $Room = $this->input->post('Room');
        $cr = $this->input->post('cr');
        
        if($this->user->checkStudentYear($cr, $Year) == 0)
        {
            $data['errorMessage'] = 'There seems to be some error in the data you entered, please check your data';
            $this->load->view('newset_view', $data);
            return;
        }
        
        $net = $this->input->post('net');
        $Department = $this->input->post('Department');
        
        if($this->session->userdata('UserType')=='section')
            $data['dept'] = $this->user->fetchAcademicDepartments($this->session->userdata('Section'));
        else
            $data['dept'] = $this->user->fetchAcademicDepartments();
        
        if(isset($Year))
        {
            $maxYear = $this->block->academicDeptYearLimit($Department);
            if(intval($Year) > $maxYear)
            {
                $data['errorMessage'] = 'There seems to be some error in the data you entered, please check your data';
                $this->load->view('newset_view', $data);
                return;
            }
        }

        if(strlen($cr)*strlen($Room)*strlen($net) != 0)
        {
            $error = 0;

            if(!$this->block->checkRoom($Room))
            {
                $data['errorMessage'] .= "Room Number incorrect<br />";
                $error = 1;
            }

            if(!is_numeric($net))
            {
                $data['errorMessage'] .= "Numeric value for number of codes is required<br />";
                $error = 1;
            }

            if(!is_numeric($Room))
            {
                $data['errorMessage'] .= "Numeric value for room is required<br />";
                $error = 2;
            }

            if($this->user->fetchUserID($cr) == 0 || !$this->user->isCR($cr))
            {
                $data['errorMessage'] .= "Username of CR is incorrect <br />";
                $error = 3;
            }

            if(!$this->user->checkDept($cr, $Department))
            {
                $data['errorMessage'] .= "Department and username do not match <br />";
                $error = 3;
            }
            $blockID;
            $blockSetID;
            if($error == 0)
            {
                $blockID = $this->block->fetchBlockID($Department, $Year, $Room); //needs to be checked
                $blockSetID = $this->block->fetchBlockSetsID($blockID);

                if($blockID == 0)
                {
                    $data['errorMessage'] .= "There seems to be some error in the data you entered, please check your data <br />";
                    $error = 3;
                }

                if($blockSetID != 0)
                {
                    $data['department'] = $Department;
                    $data['year'] = $Year;
                    $data['room'] = $Room;
                    $data['blockID'] = $blockID;
                    $data['blockSetID'] = $blockSetID;
                    $this->load->view('freeze_view', $data);
                    $error = 4;
                    return;
                }           

            }
            if($error == 0 )
            {
                $blockSetData = $this->block->generate($this->user->fetchUserID($cr), $net, $blockID);
                $blockSetID = $blockSetData->blockSetID;
                $data['codes'] = $blockSetData->codes;
                $data['blockSetID'] = $blockSetID;
                $data['blockID'] = $blockID;
                $data['limit'] = $blockSetData->limit;

                $this->load->view('codes_view',$data);
            }
            else
            {
                
                $this->load->view('newset_view', $data);
            } 
        }
        else
        {
            $data['errorMessage'] = "Please fill in all the details<br/>";
            
            $this->load->view('newset_view', $data);
        }
    }
    public function freeze($blockID, $blockSetID)
    {
        
        $this->load->model('block');
        if($this->block->freeze($blockSetID))
        {
            $d = array ( 'message' => "Set ".$blockID."/".$blockSetID." has been frozen<br />".'<a href="".site_url()."blocks/generateSet">Click to request new set</a>',
                                'image' => "codes.gif");
            $this->load->view('displayPrompt_view', $d);

        }
        else
        {
            $d = array ( 'message' => "There seems to be some error in the data you entered, please check your data");
            $this->load->view('displayPrompt_view', $d);
//            echo "An error occured. Please try again.";

            redirect(site_url().'blocks/generateSet', 'location');
        }
    }
    
    function getUnusedCodes()
    {
        if($email = $this->input->post('email'))
        {
            $this->load->model('user');
            if($this->user->userExists($email) && $this->user->isCR($email))
            {
                if($this->user->getSection($this->session->userdata('Department')) === $this->user->getSection($this->user->userDept($email)))
                {
                    $UID = $this->user->fetchUserID($email);
                    $data['userDetails'] = $this->user->userDetails($UID);
                    $this->load->model('block');
                    $data['codes'] = $this->block->validCodesUnderCR($UID);
                }
                else
                {
                    $data['err'] = "No such CR exists in this Section";
                }
                
            }
            else
                $data['err'] = "No such CR exists!";
            $this->load->view('unusedCodes_view', $data);
        }
        else
        $this->load->view('unusedCodes_view');
        
    }
}