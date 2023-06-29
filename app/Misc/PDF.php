<?php

namespace App\Misc;

use TCPDF;

class PDF extends TCPDF
{
    public function Header(): void
    {
        $this->SetY(15);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 0, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }


    public function Footer(): void
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('TIMES', 'B', 11);
        $this->SetTextColor(0, 0, 255);
        // Page number
        $myspace = '                           ';
        $this->Cell(0, 0, 'M 0722 221 221'.$myspace.' 0718 222 222 '.$myspace.' 0733 937 945 '.$myspace.'0723 266 449 (sms only)', 0, true, 'L', 0, '', 0);
        $this->Cell(0, 0, 'E info@strathmore.ac.ke '.$myspace.$myspace.$myspace.'W www.strathmore.ac.ke', 0, false, 'L', 0, '', 0);
    }
}
