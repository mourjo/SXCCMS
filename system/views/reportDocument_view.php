<?php
$this->load->library('fpdf/fpdf');

class PDF extends FPDF
{	
    function Header()
    {
        // Logo
        $this->Image('images/150yrsLogo.gif',12,12,23);
        
        $this->SetFont('Times','B',12);
        // Move to the right
        $this->Cell(25);
        // Title
        $this->Cell(35,17,'St. Xavier\'s (Autonomous) College,',0,0,'L');
        // Line break
        $this->Ln(5);
        $this->SetFont('Times','',12);
        $this->Cell(25);
        $this->Cell(35,17,'30 Mother Teresa Sarani,',0,0,'L');
        
        $this->Ln(5);
        $this->SetFont('Times','',12);
        $this->Cell(25);
        $this->Cell(35,17,'Kolkata 700016',0,0,'L');
        $this->Ln(5);
       
        
        $this->Ln(25);

    }
    function Footer()
    {
        $this->SetY(-15);        
        $this->SetFont('Times','I',8);        
        $this->Cell(0,10,'St. Xavier\'s College(Autonomous), Kolkata',0,0,'L');        
        
        $this->SetFont('Times','I',8);        
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');	
    }
    function Table($header, $data, $w, $cellHeight, $certify = 0, $ID = 0)
    {
        if($ID !=0)
        {
            $this->SetFont('Times','I',8);
            $this->Cell(1);
            $this->Cell(0,0,"Certificate Code: ".$ID,0,0,'R');
            $this->Ln(15);
        }
        
        if($certify != 0 )
        {
            $this->SetFont('Times','B',12);        
            $this->Cell(0,0,$certify[0],0,0,'C'); 
            $this->Ln(5);
            
            if(isset($certify[1]))
            {
                $this->SetFont('Times','I',9);        
                $this->Cell(0,15,$certify[1],0,0,'L'); 
                $this->Ln();
            }
        }
        
        $n = count($header);
        $this->SetFont('Arial','B',10);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C');
        $this->Ln();

        $this->SetFont('Arial','',10);

        $cLine=0;
        for($i=0;$i<count($data);)
        {
            $cLine++;
            for($j = 0; $j < $n; $j++)
            {
                $this->Cell($w[$j],$cellHeight,$data[$i++],1,0,'C');

            }
            if($cLine > 7)
            {
                $cLine = 0;
                $this->AddPage();
                if($ID !=0)
                {
                    $this->SetFont('Times','I',8);
                    $this->Cell(1);
                    $this->Cell(0,0,"Certificate Code: ".$ID,0,0,'R');
                    $this->Ln(15);
                }
                for($k=0;$k<count($header);$k++)
                $this->Cell($w[$k],7,$header[$k],1,0,'C');
            }

            $this->Ln();
        }

        $this->Cell(array_sum($w),0,'','T');
        
        if($certify != 0 )
        {
            if(isset($certify[2]))
            {
                $this->Ln(15);
                $this->SetFont('Times','B',12);        
                $this->Cell(0,0,$certify[2],0,0,'L'); 
            }
            
            
            if(isset($certify[3]))
            {
              
                $this->Cell(0,0,$certify[3],0,0,'R'); 
            }
            $this->Ln();
        }
    }

}

$pdf = new PDF();
$d = array(); 
$d_count=0;
$cellHeight = 26;
if(isset($POSTED['dept']) && $POSTED['year'] == 0 && $POSTED['roll'] == 0 )
{
    $header = array('Name of Student', 'Roll Number', 'Year', 'Normal Credits', 'Social Credits');
    $w = array(40, 35, 20, 45, 40);
    $cellHeight = 6;
    $certify[0] = "The credits awarded to students of department ".$POSTED['dept']." are:";
    if(count($credits)>0)
    {
        $c = -1;
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
        for($i = 0; $i<=$c; $i++ )
        {
            $d[$d_count++] = $data['name'][$i];
            $d[$d_count++] = $data['Roll'][$i];
            $d[$d_count++] = $data['Year'][$i];
            $d[$d_count++] = $data['credits'][$i]['Amount']." hrs";
            $d[$d_count++] = $data['credits'][$i]['Social']." hrs";
        }
    }
}
else if(isset($POSTED['dept']) && $POSTED['year'] != 0 && $POSTED['roll'] == 0)
{
    $header = array('Name of Student', 'Roll Number', 'Year', 'Normal Credits', 'Social Credits');
    $w = array(40, 35, 20, 45, 40);
    $cellHeight = 6;
    $certify[0] = "The credits awarded to students of Year ".$POSTED['year']." of department ".$POSTED['dept']." are:";
    if(count($credits)>0)
    {
        $c = -1;
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
        for($i = 0; $i<=$c; $i++ )
        {
            $d[$d_count++] = $data['name'][$i];
            $d[$d_count++] = $data['Roll'][$i];
            $d[$d_count++] = $data['Year'][$i];
            $d[$d_count++] = $data['credits'][$i]['Amount'];
            $d[$d_count++] = $data['credits'][$i]['Social'];
        }
        
    }
}
else if(isset($POSTED['dept']))
{
    
    $w = array(50, 35, 30, 35, 35);
    $header = array('Event', 'Credits awarded', 'Department', 'Social Credit?','Department Seal');
    
    $certify[0] = "RECORD OF NON-ACADEMIC CREDIT HOURS/ACTIVITIES";
    $certify[1] = "This is to certify that ".$credits[0]['FirstName']." ".$credits[0]['LastName'].", Roll-".$POSTED['roll']." of ".$POSTED['dept'].", Year-".$POSTED['year']." has secured the following credits:";
    $certify[2] = "Student's Signature:__________________";
    $certify[3] = "Vice-Principal's Signature:__________________";
    for($i = 0; $i<count($credits); $i++ )
    {
        $d[$d_count++] = $credits[$i]['EventName'];
        $d[$d_count++] = $credits[$i]['Amount']." ".($credits[$i]['Amount'] > 1 ? ($credits[$i]['Unit'] == 'atomic' ? 'credits' : 'hours'):($credits[$i]['Unit'] == 'atomic' ? 'credit' : 'hour'));
        $d[$d_count++] = $credits[$i]['Department'];
        $d[$d_count++] = $credits[$i]['Social']=='0'?'No':'Yes';
        $d[$d_count++] = " ";
    }
}

$pdf->AliasNbPages();

$pdf->AddPage();
if(isset($ID))
    $pdf->Table($header,$d, $w, $cellHeight, $certify, $ID);
else
    $pdf->Table($header,$d, $w, $cellHeight, $certify);

$pdf->Output();