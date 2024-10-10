@extends('emails.layout.mail')
@section('content')
<table align="center" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td style="padding: 40px 20px;">
            <h1 style="font-size: 24px; margin-bottom: 20px; text-align: center;">Reset Password Link for {{$data['name']}}</h1>
            <p style="font-size: 16px; margin-bottom: 30px; text-align: center;">Click the button below to reset your password.</p>
            <p style="background-color: #FF3C00; padding: 10px; border-radius: 15px; font-size: 16px; width: 200px; text-align:center; margin:auto;">
                <a href="{{$data['url']}}"
                 style="text-decoration: none;  color: white;">
                    Reset Password
                </a>
            </p>

        </td>
    </tr>
</table>
@endsection