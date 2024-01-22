<!DOCTYPE html>
<html lang="en-GB">

<head>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= config('app.name') ?> - Two Factor Authentication</title>
    <style type="text/css">
        body,
        td {
            font-size: 13px
        }

        a:link,
        a:active {
            color: #1155CC;
            text-decoration: none
        }

        a:hover {
            text-decoration: underline;
            cursor: pointer
        }

        a:visited {
            color: #6611CC
        }

        img {
            border: 0px
        }

        pre {
            white-space: pre;
            white-space: -moz-pre-wrap;
            white-space: -o-pre-wrap;
            white-space: pre-wrap;
            word-wrap: break-word;
            max-width: 800px;
            overflow: auto;
        }

        .logo {
            left: -7px;
            position: relative;
        }
    </style>
</head>

<body>
    <div class="bodycontainer">
        <div class="maincontent">
            <table width=100% cellpadding=0 cellspacing=0 border=0 class="message">
                <tr>
                    <td colspan=2>
                        <table width=100% cellpadding=12 cellspacing=0 border=0>
                            <tr>
                                <td>
                                    <div style="overflow: hidden;">
                                        <font size=-1><u></u>
                                            <div
                                                style="margin:0;font-size:14px;font-weight:400;line-height:24px;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen,Ubuntu,Cantarell,Fira Sans,Droid Sans,Helvetica,Arial,sans-serif">
                                                <div
                                                    style="width:600px;padding:60px 60px 32px;margin:24px auto;box-sizing:border-box;border:1px solid #ededed">
                                                    <header style="margin-bottom:32px">
                                                        <img src="{{ asset('assets/images/logo.png') }}"
                                                            alt="<?= config('app.name') ?> Logo" style="width:142px" width="142"> 
                                                    </header>
                                                    <section style="color:#212121">
                                                        <p>Your two step verification code is <?= $msg ?></p>
                                                    </section>
                                                </div>
                                            </div>
                                        </font>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
