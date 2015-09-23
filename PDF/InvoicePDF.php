<?php

/**
Project:		fuInvoice
Description:	Invoice backend for web-applications

License:		Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
http://creativecommons.org/licenses/by-nc-sa/4.0/

File:			InvoicePDF.php
File purpose:	Create invoice from invoice data

Creator:		Fosen Utvikling AS
Contact:		post at fosen-utvikling dot as

Developers:		Jonas Kirkemyr
                Robert Andresen
 */


namespace fuInvoice\PDF;

use NumberFormatter;

class InvoicePDF extends PDF
{
    const FONT      = 'helvetica';
    const FONT_SIZE = 10;

    public static $LOCALE='no_utf8';
    public static $LOCALE_CURRENCY='NOK';

    public static $DATE_FORMAT='d-m-Y';

    /** @var  string path to logo to use for invoice */
    private $logo;

    /** @var  array */
    private $invoiceData;

    /** @var  NumberFormatter */
    private $moneyFormatter;

    public function __construct()
    {
        $this->invoiceData = array();
        $this->moneyFormatter=new NumberFormatter(self::$LOCALE,NumberFormatter::CURRENCY);
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
          //  echo 'no logo';//throws error
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
    {
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
                $right_column.='Foretaksregisteret '.$this->invoiceData['sender']['orgnumber'];
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
                    $right_column.='<td>'.$this->invoiceData['data']['time']['sent'].'</td>';
                    $right_column.='<td>Fakturanr.:</td>';
                    $right_column.='<td>'.$this->invoiceData['data']['invoice_number'].'</td>';
                $right_column.='</tr>';

                $right_column.='<tr>';
                    $right_column.='<td>Forfallsdato:</td>';
                    $right_column.='<td>'.$this->invoiceData['data']['time']['due_date'].'</td>';

                    $right_column.='<td>Til konto:</td>';
                    $right_column.='<td>'.$this->invoiceData['data']['bank_account_number'].'</td>';
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
            {$this->invoiceData['data']['description']}</div>

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
        
        $tbl.='<table class="items" width="100%" border="0" cellpadding="5">';
            //header
            $tbl.='<thead>';
                $tbl.='<tr>';
                    $tbl.='<th width="70" align="left">Produkt</th>';
                    $tbl.='<th width="200" align="left">Beskrivelse</th>';
                    $tbl.='<th width="100" align="right">Pris</th>';
                    $tbl.='<th width="80" align="right">Antall</th>';
                    $tbl.='<th width="80" align="right">MVA</th>';
                    $tbl.='<th width="120" align="right">Beløp</th>';
                $tbl.='</tr>';
            $tbl.='</thead>';

                foreach($this->invoiceData['lines'] as $line)
                {
                    $price=$this->moneyFormatter->formatCurrency($line['price'],self::$LOCALE_CURRENCY);
                    $sum=$this->moneyFormatter->formatCurrency($line['sum_price_incl_vat'],self::$LOCALE_CURRENCY);


                    $tbl.='<tr>';
                        $tbl.='<td width="70" align="right">'.$line['app']['product_id'].'</td>';
                        $tbl.='<td width="200" align="left">'.$line['description'].'</td>';
                        $tbl.='<td width="100" align="right">'.$price.'</td>';
                        $tbl.='<td width="80" align="right">'.$line['quantity'].'</td>';
                        $tbl.='<td width="80" align="right">'.$line['vat'].'</td>';
                        $tbl.='<td width="120" align="right">'.$sum.'</td>';
                    $tbl.='</tr>';
                }


        $tbl.='</table>';

        $tbl.='<br><br>';

        $netAmount=(double)$this->invoiceData['data']['sum']['total'];
        $payAmount=$this->moneyFormatter->parseCurrency($this->invoiceData['data']['sum']['total_incl_vat'],self::$LOCALE_CURRENCY);
        $vatAmount=$payAmount-$netAmount;

        $netAmount=$this->moneyFormatter->formatCurrency($netAmount,self::$LOCALE_CURRENCY);
        $payAmount=$this->moneyFormatter->formatCurrency($payAmount,self::$LOCALE_CURRENCY);
        $vatAmount=$this->moneyFormatter->formatCurrency($vatAmount,self::$LOCALE_CURRENCY);

        $tbl.='<table border="0" cellpadding="2">';
            $tbl.='<tbody>';
                $tbl.='<tr>';
                    $tbl.='<td width="430" colspan="4"></td>';
                    $tbl.='<td width="100">Nettobeløp</td>';
                    $tbl.='<td width="100" align="right">'.$netAmount.'</td>';
                $tbl.='</tr>';

                $tbl.='<tr>';
                    $tbl.='<td width="430" colspan="4"></td>';
                    $tbl.='<td width="100">MVA</td>';
                    $tbl.='<td width="100" align="right">'.$vatAmount.'</td>';
                $tbl.='</tr>';

                $tbl.='<tr>';
                    $tbl.='<td width="430" colspan="4"></td>';
                    $tbl.='<td width="100">Å betale</td>';
                    $tbl.='<td width="100" align="right">'.$payAmount.'</td>';
                $tbl.='</tr>';
            $tbl.='</tbody>';
        $tbl.='</table>';

    $this->tcpdf->writeHTML($tbl, true, false, true, false, '');

        return true;
    }

    /**
     * Append footer sum to invoice
     */
    private function appendFooter()
    {
        $paySum    = $this->invoiceData['data']['sum']['total_incl_vat'];
        $invoiceAN = $this->invoiceData['data']['bank_account_number'];
        $kidnr     = $this->invoiceData['data']['kid'];
        $tbl       = <<<EOD
          <table border="0" cellpadding="2">
            <tbody>
              <tr>
                <td width="100">Forfall:</td>
                <td width="100">{$this->invoiceData['data']['time']['due_date']}</td>
              </tr>

              <tr>
                <td>Sum: </td>
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
        switch($this->invoiceData['data']['type'])
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

        $timeSent=new \DateTime($this->invoiceData['data']['time']['sent']);
        $this->invoiceData['data']['time']['sent']=$timeSent->format(self::$DATE_FORMAT);

        $timeDue=new \DateTime($this->invoiceData['data']['time']['due_date']);
        $this->invoiceData['data']['time']['due_date']=$timeDue->format(self::$DATE_FORMAT);


        $this->invoiceData['data']['sum']['total_incl_vat']=
            $this->moneyFormatter->formatCurrency(
                $this->invoiceData['data']['sum']['total_incl_vat'],
                self::$LOCALE_CURRENCY);
    }


}