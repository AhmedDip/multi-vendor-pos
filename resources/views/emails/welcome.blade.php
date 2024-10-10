@extends('emails.layout.mail')
@section('content')
    <table align="center" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="padding: 40px 40px; text-align: center;">
                <h1 style="text-align: center;">Dear {{$data['name']}}</h1>
                <br>
                <div style="padding: 40px 40px; text-align: center;">
                    Thank you for registering to POS. You can now log into you account 
                    and complete your profile.
                </div>

                <h1 style="text-align: center">Thank you for choosing POS.</h1> 
                We look forward to serving you!                
            </td>
        </tr>
    </table>
@endsection
