<?php
    
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

    $registerMail = '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Verificacion de correo electronico</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <style type="text/css">
            * {
                font-family: sans-serif;
            }
            button {
                width: 400px;
                height: 40px;
                border: solid 1px #1c2331;
                background: #1c2331;
                color: white;
                font-weight: bold;
            }
        </style>
    </head>
    <body style="margin: 0;padding: 0;">
    
        <table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
            <tr>
                <td align="center" bgcolor="#1c2331" style="color: white;">
                    <h1>PhoneShop</h1>
                </td>
            </tr>
            <tr style="font-size: 0; line-height: 0;height: 20px;">
                <td>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td align="center">
                    <h2>
                        Verificacion de correo electronico
                    </h2>
                </td>
            </tr>
            <tr>
                <td align="center" style="font-size: 20px;">
                    <p>
                        Para completar el registro a PhoneShop Retro Funk debes 
                        de verificar el correo electronico.
                    </p>
                </td>
            </tr>
            <tr style="font-size: 0; line-height: 0;height: 30px;">
                <td>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td align="center">
                    <a href="'.$direction.'verification?activationCode='.$activationCode.'">
                        <button>
                            <b>Verificar correo electronico</b>
                        </button>
                    </a>
                </td>
            </tr>
            <tr style="font-size: 0; line-height: 0;height: 20px;">
                <td>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td align="center">
                    <a href="'.$direction.'verification?activationCode='.$activationCode.'" style="text-decoration: none;color: #000;">O puedes verificar tu correo <b>desde aqui.</b></a>
                </td>
            </tr>
            <tr style="font-size: 0; line-height: 0;height: 30px;">
                <td>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td align="center" bgcolor="#1c2331" style="padding: 10px 20px 10px 20px;color: white;">
                    <h4>© PhoneShop 2018 - 2019</h4>
                </td>
            </tr>
        </table>
    
    </body>
    </html>';

?>