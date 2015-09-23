<?php

/**
Project:		fuInvoice
Description:	Invoice backend for web-applications

License:		Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
http://creativecommons.org/licenses/by-nc-sa/4.0/

File:			PDF.php
File purpose:	General PDF class for setting configuration for the pdf file
                File needs to be inherited and implement any abstract methods

Creator:		Fosen Utvikling AS
Contact:		post at fosen-utvikling dot as

Developers:		Jonas Kirkemyr
                Robert Andresen
 */

namespace fuInvoice\PDF;

use TCPDF;

abstract class PDF
{
    /** @var  TCPDF instance */
    protected $tcpdf;

    /** PDF Author Config */
    /** @var string */
    public static $author;

    /** @var  string */
    public static $title;

    /** @var  string */
    public static $subject;

    /** @var  string */
    public static $keywords;

    /** @var  string output filename */
    public static $filename;


    public function __construct()
    {
        $this->initPDFconfig();
    }

    /**
     * Initialize tcpdf config
     * Access static variables for setting pdf data
     */
    private function initPDFconfig()
    {
        $this->tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->tcpdf->SetCreator(PDF_CREATOR);

        //set config for pdf to be generated
        $this->tcpdf->SetAuthor(self::$author);
        $this->tcpdf->SetTitle(self::$title);
        $this->tcpdf->SetSubject(self::$subject);
        $this->tcpdf->SetKeywords(self::$keywords);

        $this->tcpdf->setPrintHeader(false);
        $this->tcpdf->setPrintFooter(false);

        //header & footer fonts
        $this->tcpdf->setHeaderFont(array(PDF_FONT_NAME_MAIN,
                                          '',
                                          PDF_FONT_SIZE_MAIN));
        $this->tcpdf->setFooterFont(array(PDF_FONT_NAME_DATA,
                                          '',
                                          PDF_FONT_SIZE_DATA));
        //default font to (if font specified (@see SetFont) not supported)
        $this->tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //margins
        $this->tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->tcpdf->setHeaderMargin(PDF_MARGIN_HEADER);
        $this->tcpdf->setFooterMargin(PDF_MARGIN_FOOTER);

        //auto page breaks
        $this->tcpdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        //image scale factor
        $this->tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    }

    abstract protected function addPdfPages();

    public function generate()
    {
        $this->addPdfPages();
    }

    /**
     * Outputs PDF as string
     * used for e-mail sending
     */
    public function toString()
    {
        return $this->tcpdf->Output(self::$filename,'S');
    }

    /**
     * Generate PDF and output to string
     */
    public function toPDF()
    {
        $this->tcpdf->Output(self::$filename,'I');
    }


}