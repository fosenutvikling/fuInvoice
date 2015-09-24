<?php
/**
 * Project:        fuInvoice
 * Description:    Invoice backend for web-applications
 * License:        Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
 * http://creativecommons.org/licenses/by-nc-sa/4.0/
 * File:            Settings.php
 * File purpose:    Settings file for fuInvoice
 * Creator:        Fosen Utvikling AS
 * Contact:        post at fosen-utvikling dot as
 * Developers:        Jonas Kirkemyr
 *                    Robert Andresen
 */
namespace fuInvoice;


class Settings
{
    //mail settings
    const MAIL_HOST        = 'yourmailhost';//smtp host to use
    const MAIL_USERNAME    = 'username';//smtp username
    const MAIL_PASSWORD    = 'password';//smptp password
    const MAIL_SMTP_AUTH   = true;//enable smtp authentication
    const MAIL_SMTP_SECURE = 'tls';//encryption to use (ssl also supported)
    const MAIL_PORT        = 587; //tcp port
    const MAIL_HTML        = true;
}