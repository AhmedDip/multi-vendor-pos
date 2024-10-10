@extends('emails.layout.mail')
@section('content')
<table align="center" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td style="padding: 40px 40px; text-align: center;">
            <h1 style="text-align: center;">Dear Valued User</h1>
            <br>
            <div style="padding: 40px 40px; text-align: center;">
                Your Order (number XXXXXX) has been placed successfully and we will let
                you know once the package is on it's way. Check the order using the
                tracking link below.

                <p style="background-color: #FF3C00; padding: 10px; border-radius: 15px; font-size: 16px; width: 200px; text-align:center; margin:20px 80px;">
                    <a href="" style="text-decoration: none;  color: white;">
                        Track My Order
                    </a>
                </p>
            </div>

            <h1 style="text-align: center">Thank you for choosing POS.</h1>
            We look forward to serving you!
        </td>
    </tr>
</table>
@endsection