<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Email</title>

    <style>
        * {
            font-family: sans-serif;
        }
        body{
            font-size: 14px;
        }
        .btn:hover{
            cursor: pointer;
        }

        .text-center {
            text-align: center;
        }

        .align-center {
            text-align: center;
            vertical-align: middle;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            border: 1px solid rgba(174, 182, 228, 0.322);
            border-radius: 10px 0 10px 0;
        }

        .container table {
            width: 100%;
            padding-top: 16px;
        }

        .logo-tr table {
            text-align: center;
            width: 100%;
        }

        .logo-tr table img {
            width: 150px;
            margin: 0 auto;
        }

        .category-tr table {
            width: 100%;
        }


        .delivery-details-tr .details-list tr td:last-child {
            color: #3f3f3f;
            font-size: 16px;
            font-style: normal;
            line-height: 22px;
            font-weight: 400;
            font-family: "Roboto", "Arial", sans-serif;
            padding-bottom: 8px;
            text-align: left;
            vertical-align: top;
        }

        .footer-tr > td,
        .need-help-tr > td,
        .order-details-tr > td,
        .checkout-tr > td {
            padding-right: 20px;
            padding-left: 20px;
        }

        .table-bordered{
            border-collapse: collapse;
            width: fit-content;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
            padding: 5px 10px;

        }
    </style>
</head>
<body>
<table class="container">
    <tbody>
       <tr>
           <td style="margin: 50px 0">
               @yield('content')
           </td>
       </tr>
    
    <tr style="text-align: center; background-color:rgb(248, 244, 244);">
        <td colspan="4">
            <h4 style="margin-bottom: 0">With Thanks</h4>
            <p style="margin-bottom:10; text-align:center">Pos</p>
            <p style="margin:10px 10px; text-align:center">If you have any questions or need assistance, feel free to reach out 
            to our support team at pos@email.com or call us at 0000000000. 
            </p> 
        </td>
    </tr>
    <tr style="text-align: center">
        <td colspan="4"> <small style="color: #ff0000e0">This is an automatically generated e-mail from our subscription list.
                Please do not reply to this e-mail.</small>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
