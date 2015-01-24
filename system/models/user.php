<?php
class User extends CI_Model
{  
    function login($username, $password)
    {
        $sql = "SELECT * FROM users WHERE Email=".$this->db->escape($username);
        $query = $this->db->query($sql);
        //the account exists...  
        if($query->num_rows() > 0)
        {
            $result = $query->row();//only a single row SHOULD be returned

            //password has matched
            if($password == $result->Password)
            {
                if($result->Status == 1)//status is OK!!
                {
                    $this->UserID = $result->UserID;
                    $this->UserType = $result->UserType;
                    $this->FirstName = $result->FirstName;
                    $this->LastName = $result->LastName;
                    $this->Department = $result->Department;
                    $this->Roll = $result->Roll;
                    $this->Year = $result->Year;
                    $this->errorCode = 0;
                }
                else//accoutn frozen >:(
                {
                    $this->errorCode = 1011;
                }
            }
            else//acount exists but password is incorrect
            {
                $this->errorCode = 1013;
            }
        }
        else//username or password invalid
        {
            $this->errorCode = 1012;
        }
        
        return $this;
    }//end of login()
	
    /*--- checkEmail ---*/
    function fetchUserID($email)
    {
        $sql = "SELECT UserID FROM users WHERE Email=".$this->db->escape($email);
        $query = $this->db->query($sql);

        if($query->num_rows() > 0)
            return $query->row()->UserID;
        else
            return 0;
    }
    
    function getUserDetailsByRoll($dept, $Year, $roll)
    {
        $sql = "SELECT FirstName, LastName FROM users WHERE Department=".$this->db->escape($dept)." AND Year=".$this->db->escape($Year)." AND Roll=".$this->db->escape($roll);
    
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
            $query = $query->result_array();
            return $query[0];
        }
        else
            return -1;
    }
    //end of checkEmail()
    
    /*--- register---*/
    function register($data, $type)
    {
        $this->UserID = time().substr(microtime(),2,8);
        $this->UserType = $type;
        $this->error=false;

        $sql = "SELECT UserID FROM users WHERE Email=".$this->db->escape($data['Email']);
        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0)
            $this->error=true;
        else
        {    
            if($type == 'dept' || $type == 'system' || $type == 'section')
                $data['Year'] = 0;
            $sql = "INSERT INTO users VALUES(".
                    $this->UserID.",".
                    $this->db->escape($type).",".
                    $this->db->escape($data['FirstName']).",".
                    $this->db->escape($data['LastName']).",".
                    $this->db->escape($data['Department']).",".
                    $this->db->escape($data['Year']).",".
                    $data['Roll'].",".
                    $this->db->escape($data['Email']).",".
                    $this->db->escape($data['Password']).",1)";

            $this->db->query($sql);
        }

        
        if($type == 'student')
        {
            $sql = "UPDATE codesetsinfo set UserID=".$this->UserID." where Code=".$data['Code'];
            $this->db->query($sql);
        }
        
        return $this;
    }//end of register()
    
    /*--- fetchDepartments ---*/
    function fetchAcademicDepartments($section='0')
    {
        if ($section == '0')
            $sql = "SELECT Code FROM departments WHERE type='academic'";
        else
            $sql = "SELECT Code FROM departments WHERE type='academic' AND Section=".$this->db->escape($section);
        $query = $this->db->query($sql);

        return $query->result_array();
    }
    
    function fetchAllDepartments($section='0')
    {
        if ($section == '0')
            $sql = "SELECT Code FROM departments WHERE Code<>".$this->db->escape($this->session->userdata('Department'));
        else
            $sql = "SELECT Code FROM departments WHERE Section=".$this->db->escape($section)." AND CODE<>".$this->db->escape($this->session->userdata('Department'));
        $query = $this->db->query($sql);

        return $query->result_array();
    }    
    
    function revokeCR($uid)
    {
        $sql = "UPDATE users SET UserType='student' WHERE UserID=".$this->db->escape($uid);
        $this->db->query($sql);
    }
    
    function modifyCR($Department, $Year, $Room, $Email)
    {
        $sql = "SELECT UserID FROM users WHERE Email=".$this->db->escape($Email);
        $query = $this->db->query($sql);
        
        $UID = $query->result_array();
        
        $sql = "UPDATE blocksinfo SET CRID=NULL WHERE CRID=".$this->db->escape($UID[0]['UserID']);
        $query = $this->db->query($sql);
        
        $sql = "UPDATE blocksinfo SET CRID=".$this->db->escape($UID[0]['UserID'])." WHERE Department=".$this->db->escape($Department)." AND Year=".$this->db->escape($Year)." AND Room=".$this->db->escape($Room);
        $query = $this->db->query($sql);
    }
    
    function makeCR($d, $y, $r, $email)
    {
        $sql = "UPDATE users SET UserType='cr' WHERE Email=".$this->db->escape($email);
        $this->db->query($sql);
        
        $sql = "SELECT UserID FROM users WHERE Email=".$this->db->escape($email);
        $UID = $this->db->query($sql);
        $UID = $UID->result_array();
        
        
        $sql = "UPDATE blocksinfo SET CRID=".$this->db->escape($UID[0]['UserID'])." WHERE Department=".$this->db->escape($d)." AND Year=".$this->db->escape($y)." AND Room=".$this->db->escape($r);
        $this->db->query($sql);
    }
    
    function hasCR($dept, $year, $room)
    {
        $sql = "SELECT UserID, FirstName, LastName FROM blocksinfo, users WHERE UserID=CRID AND users.Department=".$this->db->escape($dept)." AND blocksinfo.Year=".$this->db->escape($year)."AND Room=".$this->db->escape($room);

        $query = $this->db->query($sql);
        
        $arr = $query->result_array();
        return $arr;
    }
    
    function userDept($email)
    {
        $sql = "SELECT Department from users where Email=".$this->db->escape($email);
        
        $query = $this->db->query($sql);
        
        $query = $query->result_array();
        
        return $query[0]['Department'];
    }
    
    function checkDept($username, $department)
    {
        $sql = "SELECT Email from users where Department=".$this->db->escape($department)." and Email=".$this->db->escape($username);
        
        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0)
            return true;
        else
            return false;
    }
    
    function isCR($username)
    {
        $sql = "SELECT UserType from users where UserType='cr' and Email=".$this->db->escape($username);
        
        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0)
            return true;
        else
            return false;
    }
    
    //end of fetchDepartments()
    function changePassword($UserID, $oldPass, $newPass)
    {
        $sql = "SELECT UserID FROM users WHERE UserID=".$UserID." AND Password=".$this->db->escape($oldPass);
        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0)
        {
             $sql = "UPDATE users SET Password=".$this->db->escape($newPass)." WHERE UserID=".$UserID;
             $query = $this->db->query($sql);
             return true;
        }
        return false;
    }
    function countUsers($Section)
    {
        $sql = "SELECT count(*) as num FROM users, departments WHERE users.Department = departments.Code";
        
        if($Section != 'na')
            $sql .= " AND Section=".$this->db->escape($Section);
        
        $query = $this->db->query($sql);
        $c = $query->result_array();
        $c = $c[0]['num'];
        return $c;
    }
    function allUsers($p, $Section)
    {
        
        $sql = "SELECT * FROM users, departments WHERE users.Department = departments.Code";
          
        if($Section != 'na')
            $sql .= " AND Section=".$this->db->escape($Section);
        
        $sql .= " limit ".$p.", 10";
                
        $query = $this->db->query($sql);
        $users = $query->result_array();
        
        return $users;
    }
    
    function getCredits($UID)
    {
        $sql = 'SELECT Unit, Amount, Department, EventName, Social FROM credits WHERE UserID='.$this->db->escape($UID);
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }
    
    function getCreditsByDept($dept, $year=-1, $roll=-1)
    {
        if($year == -1)
            $sql = "SELECT users.UserID, FirstName, LastName, Roll, Year, EventName, Unit, Amount, credits.Department, Social FROM credits,users WHERE users.UserID=credits.UserID AND users.Department=".$this->db->escape($dept)." ORDER BY credits.Department";
        else if($roll == -1)
            $sql = "SELECT users.UserID, FirstName, LastName, Roll, Year, EventName, Unit, Amount, credits.Department, Social FROM credits,users WHERE users.UserID=credits.UserID AND users.Department=".$this->db->escape($dept)." AND users.Year=".$this->db->escape($year)." ORDER BY credits.Department";
        else
            $sql = "SELECT users.UserID, FirstName, LastName, Roll, Year, EventName, Unit, Amount, credits.Department, Social FROM credits,users WHERE users.UserID=credits.UserID AND users.Department=".$this->db->escape($dept)." AND users.Year=".$this->db->escape($year)." AND Roll=".$this->db->escape($roll)." ORDER BY credits.Department";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function searchUsers($keyword)
    {
        $sql = "SELECT UserID, FirstName, LastName from users WHERE UserType != 'dept' AND UserType != 'system' AND (FirstName like '%".$keyword."%' OR LastName like '%".$keyword."%')";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }
    
    function search($keywords)
    {
        $sql = "";
        $c = count($keywords);
        for($i=0; $i < $c-1; $i++)
        $sql = "(SELECT UserID, FirstName, LastName from users WHERE UserType != 'system' AND (((UserType='student' OR UserType='cr') AND (FirstName like '%".$keywords[$i]."%' OR LastName like '%".$keywords[$i]."%')) OR (UserType='dept' AND (FirstName like '%".$keywords[$i]."%' OR LastName like '%".$keywords[$i]."%' OR Department like '%".$keywords[$i]."%') )))UNION".$sql;
        $sql = $sql."(SELECT UserID, FirstName, LastName from users WHERE UserType != 'system' AND (((UserType='student' OR UserType='cr') AND (FirstName like '%".$keywords[$i]."%' OR LastName like '%".$keywords[$i]."%')) OR (UserType='dept' AND (FirstName like '%".$keywords[$i]."%' OR LastName like '%".$keywords[$i]."%' OR Department like '%".$keywords[$i]."%') )))";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    function userExists($Email)
    {
        $sql = "SELECT UserID from users WHERE Email=".$this->db->escape($Email);
        $query = $this->db->query($sql);
        if($query->num_rows>0)
            return true;
        return false;
    }
    function userDetails($UID)
    {
        $sql = "SELECT UserID, UserType, FirstName, LastName, Department, Year, Roll, Email, Status from users WHERE UserID=".$this->db->escape($UID);
        $query = $this->db->query($sql);
        
        $arr = $query->result_array();
        
        if(count($arr) != 0 && $arr[0]['UserType'] == 'dept')
        {
            $sql = "SELECT * from events WHERE open='1' AND Department=".$this->db->escape($arr[0]['Department']);
            $query = $this->db->query($sql);
            $arr['events'] = $query->result_array();
        }
        return $arr;
    }  
    function getSection($department)
    {
        $sql = "SELECT Section FROM departments WHERE Code=".$this->db->escape($department);
        $query = $this->db->query($sql);
        $query = $query->result_array();
        
        return $query[0]['Section'];
    }
    
    function incrementAll()
    {
        $section = $this->session->userdata('Section');
        $sql = "UPDATE users,departments SET Year=Year+1 WHERE  users.Department=departments.Code AND Section=".$this->db->escape($section)." AND UserType in ('student','cr')";
        
        $this->db->query($sql);
    }
    
    function checkStudentYear($username, $Year)
    {
        $sql = "SELECT UserID FROM users WHERE Email=".$this->db->escape($username)." AND Year=".$this->db->escape($Year);
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
            return 1;
        return 0;
    }
    
    function getMaxDeptYear($dept)
    {
        $sql = "SELECT Years FROM departments WHERE Code=".$this->db->escape($dept);
        $query = $this->db->query($sql);
        $query = $query->result_array();
        return $query[0]['Years'];
    }
    
    function getRollsOfDept($dept, $year)
    {
        $sql = "SELECT Roll FROM users WHERE Department=".$this->db->escape($dept)." AND Year=".$this->db->escape($year);
        $query = $this->db->query($sql);
        return $query->result_array();
        
    }
    function issueReport($UID)
    {
        $ID = time().substr(microtime(),2,8);

        $sql = "SELECT UserID FROM reportissuedinfo WHERE UserID=".$this->db->escape($UID);
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql = "UPDATE reportissuedinfo SET ReportID=".$this->db->escape($ID)." WHERE UserID=".$this->db->escape($UID);
            $this->db->query($sql);
        }
        else
        {
            $sql = "INSERT INTO reportissuedinfo VALUES (".$this->db->escape($UID).", ".$this->db->escape($ID).")";
            $this->db->query($sql);
        }
        
        return $ID;
    }
    function reportDetailsByID($ID)
    {
        $sql = "SELECT UserID FROM reportissuedinfo WHERE ReportID=".$this->db->escape($ID);
        $query = $this->db->query($sql);
        $query1 = $query->row();
        if($query->num_rows() == 0 )
            return 0;
        else
        {
            $data['credits'] = $this->getCredits ($query1->UserID);
            $data['userDetails'] =  $this->userDetails ($query1->UserID);
            return $data;
        }
        
    }
}
/* End of file user.php */
/* Location: ./system/models/user.php */