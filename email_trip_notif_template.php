<?php

// this file holds the templated content for the trip notification email 

$emailHead = '<!DOCTYPE html "-//w3c//dtd xhtml 1.0 transitional //en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!--[if gte mso 9]><xml>
     <o:OfficeDocumentSettings>
      <o:AllowPNG/>
      <o:PixelsPerInch>96</o:PixelsPerInch>
     </o:OfficeDocumentSettings>
    </xml><![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
    <title>Pilots N Paws Trip Notification</title>
</head>' ;

$emailBody = 
  '<body style="width: 100% !important;min-width: 100%;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100% !important;
  margin: 0;padding: 0;background-color: #FFFFFF">
  
  <div style="display: none; font-size: 0px; line-height: 0px; max-height: 0px; max-width: 0px; width: 0px; opacity: 0; overflow: hidden;">
    A rescue has posted a new transport request near you. 
  </div>
  
  <style id="media-query">
    /* Client-specific Styles & Reset */
    #outlook a {
        padding: 0;
    }

    /* .ExternalClass applies to Outlook.com (the artist formerly known as Hotmail) */
    .ExternalClass {
        width: 100%;
    }

    .ExternalClass,
    .ExternalClass p,
    .ExternalClass span,
    .ExternalClass font,
    .ExternalClass td,
    .ExternalClass div {
        line-height: 100%;
    }

    #backgroundTable {
        margin: 0;
        padding: 0;
        width: 100% !important;
        line-height: 100% !important;
    }

    /* Buttons */
    .button a {
        display: inline-block;
        text-decoration: none;
        -webkit-text-size-adjust: none;
        text-align: center;
    }

    .button a div {
        text-align: center !important;
    }

    /* Outlook First */
    body.outlook p {
        display: inline !important;
    }

    /*  Media Queries */
@media only screen and (max-width: 575px) {
  table[class="body"] img {
    height: auto !important;
    width: 100% !important; }
  table[class="body"] img.fullwidth {
    max-width: 100% !important; }
  table[class="body"] center {
    min-width: 0 !important; }
  table[class="body"] .container {
    width: 95% !important; }
  table[class="body"] .row {
    width: 100% !important;
    display: block !important; }
  table[class="body"] .wrapper {
    display: block !important;
    padding-right: 0 !important; }
  table[class="body"] .columns, table[class="body"] .column {
    table-layout: fixed !important;
    float: none !important;
    width: 100% !important;
    padding-right: 0px !important;
    padding-left: 0px !important;
    display: block !important; }
  table[class="body"] .wrapper.first .columns, table[class="body"] .wrapper.first .column {
    display: table !important; }
  table[class="body"] table.columns td, table[class="body"] table.column td, .col {
    width: 100% !important; }
  table[class="body"] table.columns td.expander {
    width: 1px !important; }
  table[class="body"] .right-text-pad, table[class="body"] .text-pad-right {
    padding-left: 10px !important; }
  table[class="body"] .left-text-pad, table[class="body"] .text-pad-left {
    padding-right: 10px !important; }
  table[class="body"] .hide-for-small, table[class="body"] .show-for-desktop {
    display: none !important; }
  table[class="body"] .show-for-small, table[class="body"] .hide-for-desktop {
    display: inherit !important; }
  .mixed-two-up .col {
    width: 100% !important; } }
 @media screen and (max-width: 575px) {
      div[class="col"] {
          width: 100% !important;
      }
    }

    @media screen and (min-width: 501px) {
      table[class="container"] {
          width: 575px !important;
      }
    }
  </style>
  <table class="body" style="border-spacing: 0;border-collapse: collapse;vertical-align: top;height: 100%;width: 100%;table-layout: fixed" cellpadding="0" cellspacing="0" width="100%" border="0">
      <tbody><tr style="vertical-align: top">
          <td class="center" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;text-align: center;background-color: #FFFFFF" align="center" valign="top">

              <table style="border-spacing: 0;border-collapse: collapse;vertical-align: top;background-color: #D9D9D9" cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
                <tbody><tr style="vertical-align: top">
                  <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" width="100%">
                    <!--[if gte mso 9]>
                    <table id="outlookholder" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td>
                    <![endif]-->
                    <!--[if (IE)]>
                    <table width="500" align="center" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                    <![endif]-->
                    <table class="container" style="border-spacing: 0;border-collapse: collapse;vertical-align: top;max-width: 575px;margin: 0 auto;text-align: inherit" cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
                    <tbody><tr style="vertical-align: top"><td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" width="100%"><table class="block-grid" style="border-spacing: 0;border-collapse: collapse;vertical-align: top;width: 100%;max-width: 575px;color: #333;background-color: transparent" cellpadding="0" cellspacing="0" width="100%" bgcolor="transparent"><tbody><tr style="vertical-align: top">
                    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;text-align: center;font-size: 0">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" bgcolor="transparent" cellpadding="0" cellspacing="0" border="0"><tr><![endif]-->
                    <!--[if (gte mso 9)|(IE)]><td valign="top" width="500"><![endif]--><div class="col num12" style="display: inline-block;vertical-align: top;width: 100%">
                    <table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
                    <tbody><tr style="vertical-align: top">
                    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;background-color: transparent;padding-top: 5px;padding-right: 0px;padding-bottom: 5px;padding-left: 0px;border-top: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-left: 0px solid transparent"><table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr style="vertical-align: top">
    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-top: 5px;padding-right: 10px;padding-bottom: 10px;padding-left: 10px">
        <div style="color:#888888;line-height:120%;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;">            
        	<div style="font-size:14px;line-height:14px;color:#888888;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;text-align:left;">
          <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"><span style="font-size:12px; line-height: 13px;">
          This message is intended for <a style="color:#0000FF" href="mailto:{notif_userEmail}">{notif_userEmail}</a>&nbsp;/ forum username: 
          <a clicktracking="off" href="https://pilotsnpaws.org/forum/memberlist.php?mode=viewprofile&u={notif_userId}">{notif_userName}</a> 
          </span><br></p></div>
        </div>
    </td>
  </tr>
</tbody></table>
</td></tr></tbody></table></div><!--[if (gte mso 9)|(IE)]></td><![endif]--><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr></tbody></table></td></tr></tbody></table>
                    <!--[if mso]>
                    </td></tr></table>
                    <![endif]-->
                    <!--[if (IE)]>
                    </td></tr></table>
                    <![endif]-->
                  </td>
                </tr>
              </tbody></table>
              <table style="border-spacing: 0;border-collapse: collapse;vertical-align: top;background-color: transparent" cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
                <tbody><tr style="vertical-align: top">
                  <td style="word-brtopeak: break-word;border-collapse: collapse !important;vertical-align: top" width="100%">
                    <!--[if gte mso 9]>
                    <table id="outlookholder" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td>
                    <![endif]-->
                    <!--[if (IE)]>
                    <table width="500" align="center" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                    <![endif]-->
                    <table class="container" style="border-spacing: 0;border-collapse: collapse;vertical-align: top;max-width: 575px;margin: 0 auto;text-align: inherit" cellpadding="0" cellspacing="0" align="center" width="100%" border="0"><tbody><tr style="vertical-align: top">
                    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" width="100%">
                     <table class="block-grid mixed-two-up" style="border-spacing: 0;border-collapse: collapse;vertical-align: top;width: 100%;max-width: 575px;color: #333;background-color: transparent" cellpadding="0" cellspacing="0" width="100%" bgcolor="transparent">
                    <tbody><tr style="vertical-align: top"><td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;text-align: center;font-size: 0"><!--[if (gte mso 9)|(IE)]><table width="100%" align="center" bgcolor="transparent" cellpadding="0" cellspacing="0" border="0"><tr><![endif]--><!--[if (gte mso 9)|(IE)]><td valign="top" width="167"><![endif]--><div class="col num4" style="display: inline-block;vertical-align: top;text-align: center;width: 167px"><table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" align="center" width="100%" border="0"><tbody><tr style="vertical-align: top"><td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;background-color: transparent;padding-top: 15px;padding-right: 10px;padding-bottom: 15px;padding-left: 10px;border-top: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-left: 0px solid transparent"><table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" width="100%" border="0">
    <tbody><tr style="vertical-align: top">
        <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;width: 100%;padding-top: 0px;padding-right: 0px;padding-bottom: 0px;padding-left: 0px" align="right">
            <div style="font-size:14px" align="right">

                <img class="right" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;
                clear: both;display: block;border: 0;height: auto;line-height: 100%;width: 66px;max-width: 66px" align="right" border="0" 
                src="https://pilotsnpaws.org/notif/images/pnplogo1.png" alt="Pilots N Paws Logo" width="66">
            </div>
        </td>
    </tr>
</tbody></table>
</td></tr></tbody></table></div><!--[if (gte mso 9)|(IE)]></td><![endif]-->
<!--[if (gte mso 9)|(IE)]><td valign="top" width="333"><![endif]-->
<div class="col num8" style="display: inline-block;vertical-align: top;text-align: center;width: 333px">
<table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" align="center" width="100%" border="0"><tbody><tr style="vertical-align: top"><td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;background-color: transparent;padding-top: 15px;padding-right: 0px;padding-bottom: 15px;padding-left: 0px;border-top: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-left: 0px solid transparent"><table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr style="vertical-align: top">
    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-top: 5px;padding-right: 10px;padding-bottom: 5px;padding-left: 10px">
        <div style="color:#555555;line-height:120%;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;">            
        	<div style="font-size:14px;line-height:14px;font-family:inherit;color:#555555;text-align:left;">
            <p style="margin: 0;font-size: 14px;line-height: 17px">
              <span style="font-size: 24px; line-height: 28px;">
                Pilots N Paws
              </span>
              <br>
            </p>
          </div>
        </div>
    </td>
  </tr>
</tbody></table>
<table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr style="vertical-align: top">
    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-top: 0px;padding-right: 10px;padding-bottom: 0px;padding-left: 10px">
        <div style="color:#555555;line-height:120%;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;">            
        	<div style="font-size:14px;line-height:14px;color:#555555;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;text-align:left;">
            <p style="margin: 0;font-size: 14px;line-height: 17px">
              <span style="font-size: 20px; line-height: 24px;">
                <strong>
                  <span style="line-height: 24px; font-size: 20px;">
                    New transport notification
                  </span>
                </strong>
              </span>
            </p>
          </div>
        </div>
    </td>
  </tr>
</tbody></table>
</td></tr></tbody></table></div><!--[if (gte mso 9)|(IE)]></td><![endif]-->
<!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr></tbody></table></td></tr></tbody></table>
                    <!--[if mso]>
                    </td></tr></table>
                    <![endif]-->
                    <!--[if (IE)]>
                    </td></tr></table>
                    <![endif]-->
                  </td>
                </tr>
              </tbody></table>
              <table style="border-spacing: 0;border-collapse: collapse;vertical-align: top;background-color: transparent" cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
                <tbody><tr style="vertical-align: top">
                  <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" width="100%">
                    <!--[if gte mso 9]>
                    <table id="outlookholder" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td>
                    <![endif]-->
                    <!--[if (IE)]>
                    <table width="500" align="center" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                    <![endif]-->
                    <table class="container" style="border-spacing: 0;border-collapse: collapse;vertical-align: top;max-width: 575px;margin: 0 auto;text-align: inherit" cellpadding="0" cellspacing="0" align="center" width="100%" border="0"><tbody><tr style="vertical-align: top"><td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" width="100%"><table class="block-grid" style="border-spacing: 0;border-collapse: collapse;vertical-align: top;width: 100%;max-width: 575px;color: #333;background-color: transparent" cellpadding="0" cellspacing="0" width="100%" bgcolor="transparent"><tbody><tr style="vertical-align: top"><td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;text-align: center;font-size: 0"><!--[if (gte mso 9)|(IE)]><table width="100%" align="center" bgcolor="transparent" cellpadding="0" cellspacing="0" border="0"><tr><![endif]--><!--[if (gte mso 9)|(IE)]><td valign="top" width="500"><![endif]--><div class="col num12" style="display: inline-block;vertical-align: top;width: 100%"><table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" align="center" width="100%" border="0"><tbody><tr style="vertical-align: top"><td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;background-color: transparent;padding-top: 10px;padding-right: 0px;padding-bottom: 10px;padding-left: 0px;border-top: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-left: 0px solid transparent"><table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr style="vertical-align: top">
    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-top: 5px;padding-right: 10px;padding-bottom: 5px;padding-left: 10px">
        <div style="color:#777777;line-height:120%;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;">            
            <div style="font-size:14px;line-height:14px;color:#777777;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;text-align:left;">
              <p style="margin: 0;font-size:16px;line-height: 17px">
                <span style="font-size:18px; line-height: 19px;">
                  <a href="{notif_topicUrlPrefix}{notif_topicId}" 
                  target="_blank">{notif_topicFromToText}</a>
                </span>
              </p>
              <p style="margin: 0;font-size: 16px;line-height: 17px">
                <span style="font-size:14px; line-height: 14px;">
                  Trip title: {notif_topicTitle}
                </span>
              </p>
            </div>
        </div>
    </td>
  </tr>
</tbody></table>
<table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr style="vertical-align: top">
    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-top: 5px;padding-right: 10px;padding-bottom: 10px;padding-left: 10px">
        <div style="color:#555555;line-height:120%;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;">            
        	<div style="font-size:14px;line-height:14px;color:#555555;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;text-align:left;">
            <span style="font-size:14px; line-height: 14px;">
              Weight/breed: {notif_topicWeight}
            </span>
            <br>
            <span style="font-size:14px; line-height: 14px;">
              Trip distance: {notif_topicDistance} miles
            </span>
            <br>
            <span style="font-size:14px; line-height: 14px;">
              You are based {notif_userDistSend} miles from the sender and {notif_userDistRec} miles from the destination.
              <br>
              To fly this entire trip solo would take {notif_UserTotalDist} miles. 
            </span>
          <br>
          <p style="margin: 0;font-size: 14px;line-height: 16px"><span style="font-size:14px; line-height: 14px;">&nbsp;</span>
          <br>
          </p>
          <p style="margin: 0;font-size:14px;line-height: 14px"><span style="font-size:14px; line-height: 14px;">
            To view the request on the forum: 
            <a style="color:#0000FF;text-decoration: underline; font-size:14px; line-height: 14px;" href="{notif_topicUrlPrefix}{notif_topicId}" target="_blank">{notif_topicUrlPrefix}{notif_topicId}</a>
          </span></p>
          <p style="margin: 0;font-size:14px;line-height: 14px">&nbsp;<br></p><p style="margin: 0;font-size:14px;line-height: 14px">
          Click <a style="color:#0000FF;text-decoration: underline; font-size:14px; line-height: 14px;"  clicktracking="off" href="{notif_mapUrlPrefix}{notif_topicId}" 
            target="_blank">here to view this request on a&nbsp;map.</a>&nbsp;
          </p>
            <p style="margin: 0;font-size:14px;line-height: 14px"><br data-mce-bogus="1"></p><p style="margin: 0;font-size:14px;line-height: 14px">
            To reply to this trip request, please use the links above and do&nbsp;not reply to this email.
            Replies to this email are not sent back to the trip requestor.
            <br>
            </p>
          </div>
        </div>
    </td>
  </tr>
</tbody></table>
<table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr style="vertical-align: top">
    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-top: 15px;padding-right: 10px;padding-bottom: 10px;padding-left: 10px">
        <div style="color:#808080;line-height:120%;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;">            
        	<div style="font-size:14px;line-height:14px;color:#808080;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;text-align:left;">
            <p style="margin: 0;font-size: 14px;line-height: 17px">
            <span style="font-size: 14px; line-height: 16px;">
              You are receiving this email because you are registered as a volunteer pilot on the pilotsnpaws.org forum.
              &nbsp;Your user profile settings have matched this new transport request.&nbsp;
            </span>
            <br>
              <span style="font-size: 14px; line-height: 16px;">
                Your settings currently will notify you of trips coming within {notif_userFlyingDistance} miles of {notif_userHomeAirport} airport. 
                To change these settings, please click <a style="color:#0000FF;text-decoration: underline; font-size: 14px; line-height: 16px;" href="{notif_forumUcpUrl}" target="_blank">here</a>.&nbsp;</span><br><br><span style="font-size: 14px; line-height: 16px;">If you believe you received this message in error, please let us know by replying to this email, or posting on the <a style="color:#0000FF;text-decoration: underline; font-size: 14px; line-height: 16px;" clicktracking="off" href="{notif_forumTechUrl}&utm_term=techSupport" target="_blank">Technical Support forum</a>.&nbsp;</span></p><p style="margin: 0;font-size: 14px;line-height: 16px"><span style="font-size: 14px; line-height: 16px;">&nbsp;</span><br></p><p style="margin: 0;font-size: 14px;line-height: 16px"><span style="font-size: 14px; line-height: 16px;">
                To unsubscribe from these notifications, reply to this email with the word UNSUBSCRIBE at the top, or set your <i>Distance willing to fly one way</i> to 0
              <a style="color:#0000FF;text-decoration: underline; font-size: 14px; line-height: 16px;" clicktracking="off" href="{notif_forumUcpUrl}&utm_term=setDistance" target="_blank">here</a>
              </span>
            </p>
          </div>
        </div>
    </td>
  </tr>
</tbody></table>
</td></tr></tbody></table></div><!--[if (gte mso 9)|(IE)]></td><![endif]-->
<!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr></tbody></table></td></tr></tbody></table>
                    <!--[if mso]>
                    </td></tr></table>
                    <![endif]-->
                    <!--[if (IE)]>
                    </td></tr></table>
                    <![endif]-->
                  </td>
                </tr>
              </tbody></table>
              <table style="border-spacing: 0;border-collapse: collapse;vertical-align: top;background-color: #444444" cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
                <tbody><tr style="vertical-align: top">
                  <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" width="100%">
                    <!--[if gte mso 9]>
                    <table id="outlookholder" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td>
                    <![endif]-->
                    <!--[if (IE)]>
                    <table width="500" align="center" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                    <![endif]-->
                    <table class="container" style="border-spacing: 0;border-collapse: collapse;vertical-align: top;max-width: 575px;margin: 0 auto;text-align: inherit" cellpadding="0" cellspacing="0" align="center" width="100%" border="0"><tbody><tr style="vertical-align: top"><td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" width="100%"><table class="block-grid" style="border-spacing: 0;border-collapse: collapse;vertical-align: top;width: 100%;max-width: 575px;color: #333;background-color: transparent" cellpadding="0" cellspacing="0" width="100%" bgcolor="transparent"><tbody><tr style="vertical-align: top"><td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;text-align: center;font-size: 0"><!--[if (gte mso 9)|(IE)]><table width="100%" align="center" bgcolor="transparent" cellpadding="0" cellspacing="0" border="0"><tr><![endif]--><!--[if (gte mso 9)|(IE)]><td valign="top" width="500"><![endif]--><div class="col num12" style="display: inline-block;vertical-align: top;width: 100%"><table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" align="center" width="100%" border="0"><tbody><tr style="vertical-align: top">
                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;background-color: transparent;padding-top: 5px;
                        padding-right: 0px;padding-bottom: 5px;padding-left: 0px;border-top: 0px solid transparent;border-right: 0px solid transparent;
                        border-bottom: 0px solid transparent;border-left: 0px solid transparent">
<table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody><tr style="vertical-align: top">
    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" align="center" valign="top">
      <table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" border="0" cellspacing="0" cellpadding="0">
        <tbody><tr style="vertical-align: top">
          <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;text-align: center;
            padding-top: 10px;padding-right: 10px;padding-bottom: 10px;padding-left: 10px;max-width: 213px" align="center" valign="top">

            <!--[if (gte mso 9)|(IE)]>
            <table width="223" align="left" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left">
            <![endif]-->
            <table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" width="100%" align="left" cellpadding="0" cellspacing="0" border="0">
              <tbody><tr style="vertical-align: top">
                <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" align="left" valign="middle">
                  <table style="border-spacing: 0;border-collapse: collapse;vertical-align: top;padding: 0 10px 5px 0" align="left" border="0" cellspacing="0" cellpadding="0" height="37">
                      <tbody><tr style="vertical-align: top">
                          <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" width="42" align="left" valign="middle">
                            <a href="https://www.facebook.com/pilotsnpawsfanpage" title="Facebook" target="_blank">
                                <img style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block;border: none;height: auto;line-height: 100%;max-width: 32px !important" src="https://pilotsnpaws.org/notif/images/facebook.png" alt="Facebook" title="Facebook" width="32">
                            </a>
                          </td>
                      </tr>
                  </tbody></table>
                  <table style="border-spacing: 0;border-collapse: collapse;vertical-align: top;padding: 0 10px 5px 0" align="left" border="0" cellspacing="0" cellpadding="0" height="37">
                      <tbody><tr style="vertical-align: top">
                          <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" width="42" align="left" valign="middle">
                            <a href="https://twitter.com/PilotsNPaws" title="Twitter" target="_blank">
                                <img style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block;border: none;height: auto;line-height: 100%;max-width: 32px !important" src="https://pilotsnpaws.org/notif/images/twitter.png" alt="Twitter" title="Twitter" width="32">
                            </a>
                          </td>
                      </tr>
                  </tbody></table>
                  <table style="border-spacing: 0;border-collapse: collapse;vertical-align: top;padding: 0 10px 5px 0" align="left" border="0" cellspacing="0" cellpadding="0" height="37">
                      <tbody><tr style="vertical-align: top">
                          <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" width="42" align="left" valign="middle">
                            <a href="https://www.instagram.com/pilotsnpaws/" title="Instagram" target="_blank">
                                <img style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block;border: none;height: auto;line-height: 100%;max-width: 32px !important" src="https://pilotsnpaws.org/notif/images/instagram.png" alt="Instagram" title="Instagram" width="32">
                            </a>
                          </td>
                      </tr>
                  </tbody></table>
                  <table style="border-spacing: 0;border-collapse: collapse;vertical-align: top;padding: 0 10px 5px 0" align="left" border="0" cellspacing="0" cellpadding="0" height="37">
                      <tbody><tr style="vertical-align: top">
                          <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top" width="42" align="left" valign="middle">
                            <a href="https://www.youtube.com/user/PilotsNPaws" title="YouTube" target="_blank">
                                <img style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block;border: none;height: auto;line-height: 100%;max-width: 32px !important" src="https://pilotsnpaws.org/notif/images/youtube.png" alt="YouTube" title="YouTube" width="32">
                            </a>
                          </td>
                      </tr>
                  </tbody></table>

                </td>
              </tr>
            </tbody></table>
            <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
            </table>
            <![endif]-->
          </td>
        </tr>
      </tbody></table>
    </td>
  </tr>
</tbody></table><table style="border-spacing: 0;border-collapse: collapse;vertical-align: top" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr style="vertical-align: top">
  </tr>
</tbody></table>
</td></tr></tbody></table></div><!--[if (gte mso 9)|(IE)]></td><![endif]--><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr></tbody></table></td></tr></tbody></table>
                    <!--[if mso]>
                    </td></tr></table>
                    <![endif]-->
                    <!--[if (IE)]>
                    </td></tr></table>
                    <![endif]-->
                  </td>
                </tr>
              </tbody></table>
          </td>
      </tr>
  </tbody></table>


</body>' ;

?>