<?php

/**
 * fuInvoice.
 * User: Jonas
 * Date: 22.09.2015
 * Time: 22.44
 * InvoicePDF.php
 */

namespace fuInvoice\PDF;

class InvoicePDF extends PDF
{
    const FONT      = 'helvetica';
    const FONT_SIZE = 10;

    /** @var  string path to logo to use for invoice */
    private $logo;

    /** @var  array */
    private $invoiceData;

    public function __construct()
    {
        $this->invoiceData = array();
        parent::__construct();

    }

    /**
     * append data to pdf page
     */
    protected function addPdfPages()
    {
        $this->tcpdf->SetFont(self::FONT, '', self::FONT_SIZE);

        $this->tcpdf->AddPage();

        if($this->logo !== null && file_exists($this->logo))
        {
            $this->tcpdf->Image($this->logo, 15, 15, 0, 15, null, '', '', true, 150, '', false, false, 0, false, false, false);
        }
        else
        {
            echo 'no logo';//throws error
            //todo: handle no image found
        }

        $this->appendLeftColumn();
        $this->appendRightColumn();
        $this->appendInvoiceMessage();
        $this->appendInvoiceLines();
        $this->appendFooter();

    }


    /**
     * Append left column to invoice, containing sender/receiver data to identify receiver adr
     */
    private function appendLeftColumn()
    {

        $left_column = '<style>';
            $left_column .= 'div.customerRecieverBox {color:#222222;padding:0;margin:0 !important;}';
            $left_column .= 'div.customerRecieverName{color:#222222;font-size: 20pt;margin-bottom:0px;}';
            $left_column .= 'div.customerAddressBox{color:#222222;}';
        $left_column .= '</style>';

        $left_column .= '<div class="customerRecieverBox">';
            $left_column .= '<div class="customerRecieverName">';
                $left_column .= $this->invoiceData['receiver']['name'];
            $left_column .= '</div>';

            $left_column .= '<div class="customerAddressBox">';
                $left_column .= $this->invoiceData['receiver']['address'];
                $left_column .= '<br>';
                $left_column .= $this->invoiceData['receiver']['zip'] . ' ' . $this->invoiceData['receiver']['location'];
            $left_column .= '</div>';

        $left_column .= '</div>';

        $this->tcpdf->MultiCell(80, 5, $left_column, 0, 'J', 0, 1, 15, 35, '', '', true, true);
    }

    /**
     * Append right column to invoice consisting of sender/receiver data
     * todo: support multilanguage
     */
    private function appendRightColumn()
    {//todo: append isMVA to db? append to account number

        $right_column = '<style>';
            $right_column .= 'div.headlineContainer {text-align: right;color: #222222;font-size: 24pt;font-weight:normal;width:50%;border-bottom:1px solid #eaeaea;}';
            $right_column .= 'div.customerSenderBox{color:#222;border-bottom:1px solid #eaeaea;}';
            $right_column .= 'div.customerSenderName{color:#222;font-size: 20pt;margin-bottom:0px;}';
            $right_column .= 'div.blockContainer{border-bottom:1px solid #eaeaea;font-size: 9pt;}';
        $right_column .= '</style>';

        $right_column .= '<div class="headlineContainer">';
            $right_column .= $this->getInvoiceHeader();
        $right_column .= '</div>';

        $right_column .= '<div class="customerSenderBox">';
            $right_column .= '<div class="customerSenderName">';
                $right_column .= $this->invoiceData['sender']['name'];
            $right_column .= '</div>';

            $right_column.='<div>';
                $right_column.='Foretaksregisteret '.$this->invoiceData['sender']['orgnumber'].' '.$this->invoiceData['sender']['isMVA'];
            $right_column.='</div>';

            $right_column.='<div>';
                $right_column.=$this->invoiceData['sender']['address'];
                $right_column.='<br>';
                $right_column.=$this->invoiceData['sender']['zip'].' '.$this->invoiceData['sender']['location'];
            $right_column.='</div>';

        $right_column .= '</div>';

        $right_column.='<div class="blockContainer">';
            $right_column.='<table width="100%">';
                $right_column.='<tr>';
                    $right_column.='<td>'.$this->invoiceData['sender']['mail'].'</td>';
                    $right_column.='<td>'.$this->invoiceData['sender']['webpage'].'</td>';
                $right_column.='</tr>';

                $right_column.='<tr>';
                    $right_column.='<td>Vår ref.:<br>'.$this->invoiceData['sender']['ref'].'</td>';
                    $right_column.='<td>Deres ref.:<br>'.$this->invoiceData['receiver']['ref'].'</td>';
                $right_column.='</tr>';

            $right_column.='</table>';
        $right_column.='</div>';

        $right_column.='<div class="blockContainer">';
            $right_column.='<table width="100%">';
                $right_column.='<tr>';
                    $right_column.='<td>Fakturadato:</td>';
                    $right_column.='<td>'.$this->invoiceData['time_sent'].'</td>';
                    $right_column.='<td>Fakturanr.:</td>';
                    $right_column.='<td>'.$this->invoiceData['invoice_id'].'</td>';
                $right_column.='</tr>';

                $right_column.='<tr>';
                    $right_column.='<td>Forfallsdato:</td>';
                    $right_column.='<td>'.$this->invoiceData['time_due_date'].'</td>';

                    $right_column.='<td>Til konto:</td>';
                    $right_column.='<td>'.$this->invoiceData['bank_account_number'].'</td>';
                $right_column.='</tr>';
            $right_column.='</table>';
        $right_column.='</div>';


        $this->tcpdf->MultiCell(90, 50, $right_column, 0, 'J', 0, 1, 105, 20, '', '', true, true);

    }

    /**
     * Append any message specified to invoice
     */
    private function appendInvoiceMessage()
    {
        $addCredit = null;
        //$addCredit=($isCreditNote)?"<div class='invoiceMessage' style='margin:30px 0px;'>$creditText</div>":"";

        $invoiceMSG = <<<EOD
            <style>
            .invoiceMessage {
              border:1px solid #eaeaea;
              padding: 8px;
            }
            </style>

            <div class='invoiceMessage' style='margin:30px 0px;'>
            {$this->invoiceData['description']}</div>

            $addCredit
EOD;

        $this->tcpdf->writeHTML($invoiceMSG, true, false, true, false, '');
    }

    /**
     * Appends registered invoice line to pdf
     * returns false if no lines are added to invoice
     * todo: support multilanguage
     * @return bool
     */
    private function appendInvoiceLines()
    {
        if(!array_key_exists('lines', $this->invoiceData))
            return false;

        $tbl='<style>';
            $tbl.='table{color:#222;}';
            $tbl.='table.items th{font-weight: bold;border-bottom:1px solid #eaeaea;}';
            $tbl.='table.items td{border-bottom:1px solid #eaeaea;}';
        $tbl.='</style>';
        
        $tbl.='<table class="items" width="100%" border="0" cellpadding="2">';
            //header
            $tbl.='<thead>';
                $tbl.='<tr>';
                    $tbl.='<th width="230" align="left">Beskrivelse</th>';
                    $tbl.='<th width="120" align="left">Pris</th>';
                    $tbl.='<th width="80" align="left">Antall</th>';
                    $tbl.='<th width="80" align="left">MVA</th>';
                    $tbl.='<th width="120" align="left">Beløp</th>';
                $tbl.='</tr>';
            $tbl.='</thead>';

                foreach($this->invoiceData['lines'] as $line)
                {
                    $price=$line['price'];
                    $sum=$line['sum'];

                    $tbl.='<tr>';
                        $tbl.='<td width="230">'.$line['name'].'</td>';
                        $tbl.='<td width="120">'.$price.'</td>';
                        $tbl.='<td width="80">'.$line['quantity'].'</td>';
                        $tbl.='<td width="80">'.$line['mva'].'</td>';
                        $tbl.='<td width="120">'.$sum.'</td>';
                    $tbl.='</tr>';
                }


        $tbl.='</table>';
        $this->tcpdf->writeHTML($tbl, true, false, true, false, '');

        return true;
    }

    /**
     * Append footer sum to invoice
     */
    private function appendFooter()
    {
        $paySum    = null;
        $invoiceAN = null;
        $kidnr     = null;
        $tbl       = <<<EOD
          <table border="0" cellpadding="2">
            <tbody>
              <tr>
                <td width="100">Forfall:</td>
                <td width="100">{$this->invoiceData['time_due_date']}</td>
              </tr>

              <tr>
                <td>SUM: </td>
                <td>$paySum</td>
              </tr>

              <tr>
                <td>Til konto:</td>
                <td>$invoiceAN</td>
              </tr>

              <tr>
                <td>KID:</td>
                <td>$kidnr</td>
              </tr>
            </tbody>
          </table>
EOD;


        $this->tcpdf->writeHTML($tbl, true, false, true, false, '');
    }

    /**
     * Retrieve invoice header text
     * todo: support multilanguage
     * @return string
     */
    private function getInvoiceHeader()
    {
        switch($this->invoiceData['idinvoice_type'])
        {
            case 'invoice':
                return 'Faktura';

            case 'credit':
                return 'Kreditnota';

            case 'reminder':
                return 'Purring';

            case 'dept':
                return 'Inkasso';

            default:
            case 'draft':
                return 'Utkast!';
        }
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo    = $logo;
    }

    /**
     * @return array
     */
    public function getInvoiceData()
    {
        return $this->invoiceData;
    }

    /**
     * @param array $invoiceData
     */
    public function setInvoiceData(array $invoiceData)
    {
        $this->invoiceData = $invoiceData;
    }


}