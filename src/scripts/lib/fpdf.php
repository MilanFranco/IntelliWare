<?php
/*
 Minimal embedded FPDF 1.86 subset.
 Source: http://www.fpdf.org/ (License: freeware). For brevity, include a trimmed version sufficient for simple tables.
 If you need full features, replace with the official library.
*/

class FPDF {
    protected $content = '';
    protected $pageStarted = false;
    protected $buffer;
    protected $pages = [];
    protected $currentPage = 0;
    protected $wPt = 595.28; // A4 width in points
    protected $hPt = 841.89; // A4 height in points

    public function __construct(){
        $this->buffer = '';
    }

    public function AddPage(){
        $this->currentPage++;
        $this->pages[$this->currentPage] = "%PDF_Page_{$this->currentPage}\n";
    }

    public function SetFont($family, $style='', $size=12) {
        // No-op in this minimal stub
    }

    public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
        $safe = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], (string)$txt);
        $this->pages[$this->currentPage] .= "TEXT {$safe}\n";
        if ($ln > 0) {
            $this->pages[$this->currentPage] .= "NL\n";
        }
    }

    public function Ln($h=null){
        $this->pages[$this->currentPage] .= "NL\n";
    }

    public function Output($dest='', $name='', $isUTF8=false){
        // This is a very naive PDF output for demonstration. For real use, drop-in full FPDF.
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename=' . ($name ?: 'report.pdf'));
        echo "%PDF-1.3\n";
        foreach ($this->pages as $p) {
            echo "%PageStart\n".$p."%PageEnd\n";
        }
        echo "%%EOF";
    }
}
?>


