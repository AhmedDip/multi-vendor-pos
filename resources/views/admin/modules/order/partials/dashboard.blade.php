<style>
    .text-decoration-none {
        text-decoration: none;
    }
</style>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <a class="text-decoration-none" href="{{ route('order.index', ['status' => '']) }}">
                <div class="card" style="">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('All Orders') }}</h5>
                        <p class="card-text">{{ $orderCounts['all'] }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a class="text-decoration-none" href="{{ route('order.index', ['status' => App\Models\Order::STATUS_PENDING]) }}">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Pending') }}</h5>
                        <p class="card-text">{{ $orderCounts['pending'] }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a class="text-decoration-none" href="{{ route('order.index', ['status' => App\Models\Order::STATUS_COMPLETED]) }}">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Completed') }}</h5>
                        <p class="card-text">{{ $orderCounts['completed'] }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a class="text-decoration-none" href="{{ route('order.index', ['status' => App\Models\Order::STATUS_CANCELED]) }}">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Canceled') }}</h5>
                        <p class="card-text">{{ $orderCounts['canceled'] }}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

   
</div>