<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'TCPDF/tcpdf.php';

// Extend the TCPDF class to create custom Header and Footer
class Pdf extends TCPDF {
    function __construct()
    {
        parent::__construct();
    }
    //Page header
    //public function Header() {
        // Logo
        //$this->SetY(10);
        //$this->SetFont('helvetica', 'B', 15);
        // Title
        //$this->Cell(0, 20, 'Judul', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    //}

    // Page footer
    //public function Footer() {
        // Position at 15 mm from bottom
        //$this->SetY(-20);
        // Set font
        //$this->SetFont('times', '',8);
        // Page number

        //$html = <<<EOD
        //<font size="+1"><span><strong>Footer Example</span></strong></font>
        //<hr />
        //FooterText
//EOD;


        //$this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        // print a block of text using Write()
        //$this->Write(-60, $FooterText , '', 0, 'C', true, 0, false, false, 12);
    //}
}


?>
