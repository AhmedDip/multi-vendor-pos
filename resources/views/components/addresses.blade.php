<div class="col-md-12 mt-4">
    <div class="row justify-content-center">

        @isset($addresses)
        @forelse($addresses as $address)
        <div class="col-md-6">
            <fieldset>
                <legend>Address {{$loop->iteration}} </legend>
                <table class="table table-striped table-hover table-bordered mt-3" style="font-size: 13px">
                    <tbody>
                        <tr>
                            <th>Address</th>
                            <td>{{$address['address']}}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{$address['city']}}</td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td>{{$address['state']}}</td>
                        </tr>
                        <tr>
                            <th>Zip</th>
                            <td>{{$address['zip']}}</td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <td>{{$address['country']}}</td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
        </div>
        @empty
        <table class="table table-striped table-hover table-bordered mt-3" style="font-size: 13px">
            <tbody>
                <tr>
                    <td class="text-danger text-center" role="alert" colspan="2">
                        <legend>No Address Found</legend>
                    </td>
                </tr>
            </tbody>
        </table>
        @endforelse
        @endisset
    </div>
</div>