<?php

include '../fpdf.php';

class ReportPdf extends FPDF
{
    function header()
    {
        $this->Image('../images/st-logoblack.png', 20, 10, 20, 20);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(276, 5, "Open Tickets", 0, 0, 'C');
        $this->Ln();
        $this->SetFont('Times', '', 12);
        $this->Cell(276, 10, "testtesttesttest", 0, 1, 'C');
        $this->Cell(0, 0, date('Y-m-d H:i:s'), 0, 1, 'R');
        $this->Ln(15);
    }

    function footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 10, "Page " . $this->PageNo() . "/{nb}", 0, 0, 'C');
    }

    function headerTable()
    {
        $this->SetFont('Times', 'B', 12);
        $this->Cell(30, 10, "Number", 1, 0, 'C');
        $this->Cell(43, 10, "Last Update", 1, 0, 'C');
        $this->Cell(140, 10, "Subject", 1, 0, 'C');
        $this->Cell(43, 10, "From", 1, 0, 'C');
        $this->Cell(20, 10, "Priority", 1, 0, 'C');
        $this->Ln();
    }

    function data($data)
    {
        $this->SetFont('Times', '', 12);
        foreach ($data as $row) {
            switch ($row['priority_id']) {
                case 1:
                    $priority = 'Low';
                    break;
                case 2:
                    $priority = 'Normal';
                    break;
                case 3:
                    $priority = 'High';
                    break;
                case 4:
                    $priority = 'Emergency';
                    break;
            }
            $this->Cell(30, 10, $row['number'], 1, 0,'C');
            $this->Cell(43, 10, $row['updated'], 1, 0, 'L');
            $this->Cell(140, 10, $row['subject'], 1, 0, 'L');            
            $this->Cell(43, 10, $row['name'], 1, 0, 'L');
            $this->Cell(20, 10, $priority, 1, 0, 'L');
            $this->Ln();
        }
    }
}

?>