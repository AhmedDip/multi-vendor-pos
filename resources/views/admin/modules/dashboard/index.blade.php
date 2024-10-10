@extends('admin.layouts.app')

@section('content')
 <div class="body-card dashboard">
   <div class="row">
      <div class="mb-4 col-md-6">
         <div class="custom-form-group">
             <label for="shop_id"><i class="fas fa-store"></i> {{ __('Shop') }}</label>
             {{ html()->select('shop_id', $shops)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder('Select Shop') }}
             <x-validation-error :errors="$errors->first('shop_id')" />
         </div>
      </div>
     
      <div class="mt-4 row"style="display:none">
         <div class="col-md-4 offset-md-4" id="totalShopCard" style="display: none;">
            <div class="card">
               <div class="text-center card-body">
                     <h5 class="card-title">@lang('Total Shop')</h5>
                     <p class="card-text">{{ $shop }}</p>
               </div>
            </div>
         </div>
      </div>
      {{-- <div class="mt-4 col-md-6">
         
         <div class="col-12 d-flex justify-content-end">
            <div>
               <form action="{{ route('dashboard') }}" method="GET" class="d-flex">
                  <div class="input-group">
                      <select name="duration" class="form-select">
                          <option value="today" @if(request('duration') == "today") selected @endif>Today</option>
                          <option value="last-7-days" @if(request('duration') == "last-7-days") selected @endif>Last 7 Days</option>
                          <option value="this-month" @if(request('duration') == "this-month") selected @endif>This Month</option>
                          <option value="last-month" @if(request('duration') == "last-month") selected @endif>Last Month</option>
                          <option value="this-year" @if(request('duration') == "this-year") selected @endif>This Year</option>
                          <option value="last-year" @if(request('duration') == "last-year") selected @endif>Last Year</option>
                      </select>
                      <!-- Optionally, you can include a date picker for custom date range selection -->
                      <input type="date" id="custom-date-picker" name="custom_date" class="form-control" style="display: none;">
                      <button type="submit" class="btn btn-secondary">Get</button>
                  </div>
              </form>
              
            </div>
         </div>
     </div> --}}
   </div>
   
   <div>
      <h5 class="breadcrumb-item">Quick Links</h5>
      <div>
         <div class="mx-0 my-4 quick-link-card"> 
            <div class="row">
               {{-- products --}}
               <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                  <div class="overflow-hidden card product-card">
                     <a class="gap-2 card-body d-flex align-items-center justify-content-between" href="{{ route('product.index') }}">
                        <h5 class="card-title">
                           Products
                           <div>
                              <small id="total_product">
                              {{ $totalProduct }} Products
                              </small>
                           </div>
                           
                        </h5>
                        <div> 
                           <div class="main-card-icon product"> 
                              <div class="border avatar avatar-lg bg-product-transparent border-product border-opacity-10">
                                 <div class="avatar avatar-sm svg-white"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M216,64H176a48,48,0,0,0-96,0H40A16,16,0,0,0,24,80V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V80A16,16,0,0,0,216,64ZM128,32a32,32,0,0,1,32,32H96A32,32,0,0,1,128,32Zm88,168H40V80H80V96a8,8,0,0,0,16,0V80h64V96a8,8,0,0,0,16,0V80h40Z"></path>
                                    </svg> 
                                 </div> 
                              </div>
                           </div> 
                        </div>
                     </a>
                  </div>
               </div>
               {{-- appointment --}}
               <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                  <div class="overflow-hidden card appointment-card">
                     <a class="gap-2 card-body d-flex align-items-center justify-content-between" href="{{ route('appointment.index') }}">
                        <h5 class="card-title">
                           Appointment
                           <div>
                              <small id="total_appointment">
                                 {{$totalAppointment}} Appointments
                              </small>
                           </div>
                           
                        </h5>
                        <div> 
                           <div class="main-card-icon appointment"> 
                              <div class="border avatar avatar-lg bg-appointment-transparent border-appointment border-opacity-10">
                                 <div class="avatar avatar-sm svg-white"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M216,72H56a8,8,0,0,1,0-16H192a8,8,0,0,0,0-16H56A24,24,0,0,0,32,64V192a24,24,0,0,0,24,24H216a16,16,0,0,0,16-16V88A16,16,0,0,0,216,72Zm0,128H56a8,8,0,0,1-8-8V86.63A23.84,23.84,0,0,0,56,88H216Zm-48-60a12,12,0,1,1,12,12A12,12,0,0,1,168,140Z"></path></svg>
                                 </div> 
                              </div>
                           </div> 
                        </div>
                     </a>
                  </div>
               </div>
               {{-- custromer --}}
               <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                  <div class="overflow-hidden card customer-card">
                     <a class="gap-2 card-body d-flex align-items-center justify-content-between" href="{{ route('customer.index') }}">
                        <h5 class="card-title">
                           Customers
                           <div>
                              <small id="total_customer">
                                 {{$totalCustomer}} Customers
                              </small>
                           </div>
                           
                        </h5>
                        <div> 
                           <div class="main-card-icon customer"> 
                              <div class="border avatar avatar-lg bg-customer-transparent border-customer border-opacity-10">
                                 <div class="avatar avatar-sm svg-white"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><circle cx="84" cy="108" r="52" opacity="0.2"></circle><path d="M10.23,200a88,88,0,0,1,147.54,0" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><path d="M172,160a87.93,87.93,0,0,1,73.77,40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><circle cx="84" cy="108" r="52" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle><path d="M152.69,59.7A52,52,0,1,1,172,160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path></svg>
                                 </div> 
                              </div>
                           </div> 
                        </div>
                     </a>
                  </div>
               </div>
               {{-- Expense --}}
               <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                  <div class="overflow-hidden card expense-card">
                     <a class="gap-2 card-body d-flex align-items-center justify-content-between" href="{{ route('expense.index') }}">
                        <h5 class="card-title">
                           Expenses
                           <div>
                              <small id="total_expense">
                                 {{$totalExpense}} Expenses
                              </small>
                           <div>
                           
                        </h5>
                        <div> 
                           <div class="main-card-icon expense"> 
                              <div class="border avatar avatar-lg bg-expense-transparent border-expense border-opacity-10">
                                 <div class="avatar avatar-sm svg-white"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M200,168a48.05,48.05,0,0,1-48,48H136v16a8,8,0,0,1-16,0V216H104a48.05,48.05,0,0,1-48-48,8,8,0,0,1,16,0,32,32,0,0,0,32,32h48a32,32,0,0,0,0-64H112a48,48,0,0,1,0-96h8V24a8,8,0,0,1,16,0V40h8a48.05,48.05,0,0,1,48,48,8,8,0,0,1-16,0,32,32,0,0,0-32-32H112a32,32,0,0,0,0,64h40A48.05,48.05,0,0,1,200,168Z"></path></svg>
                                 </div> 
                              </div>
                           </div> 
                        </div>
                     </a>
                  </div>
               </div>
               {{-- order --}}
               <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                  <div class="overflow-hidden card order-card">
                     <a class="gap-2 card-body d-flex align-items-center justify-content-between" href="{{ route('order.index') }}">
                        <h5 class="card-title">
                           Orders
                           <div>
                              <small id="total_order">
                                 {{$totalOrder}} Orders
                              </small>
                           </div>
                           
                        </h5>
                        <div> 
                           <div class="main-card-icon order"> 
                              <div class="border avatar avatar-lg bg-order-transparent border-order border-opacity-10">
                                 <div class="avatar avatar-sm svg-white"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M62.55,144H188.1a16,16,0,0,0,15.74-13.14L216,64H48Z" opacity="0.2"></path><path d="M180,184H83.17a16,16,0,0,1-15.74-13.14L41.92,30.57A8,8,0,0,0,34.05,24H16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><circle cx="84" cy="204" r="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle><circle cx="180" cy="204" r="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle><path d="M62.55,144H188.1a16,16,0,0,0,15.74-13.14L216,64H48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path></svg> 
                                 </div> 
                              </div>
                           </div> 
                        </div>
                     </a>
                  </div>
               </div>
               {{-- top product --}}
               <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                  <div class="overflow-hidden card top-product-card">
                     <a class="gap-2 card-body d-flex align-items-center justify-content-between" href="{{ route('product.index',['order_by' => 'desc', 'order_by_column' => 'sold']) }}">
                        <h5 class="card-title">
                           Top Products
                           <div>
                              <small id="total_top_product">
                                 {{$totalTopProduct}} Products
                              </small>
                           </div>
                           
                        </h5>
                        <div> 
                           <div class="main-card-icon topProduct"> 
                              <div class="border avatar avatar-lg bg-topProduct-transparent border-topProduct border-opacity-10">
                                 <div class="avatar avatar-sm svg-white"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><circle cx="128" cy="96" r="48" opacity="0.2"></circle><circle cx="128" cy="96" r="80" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle><circle cx="128" cy="96" r="48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle><polyline points="176 160 176 240 127.99 216 80 240 80 160.01" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline></svg>
                                 </div> 
                              </div>
                           </div> 
                        </div>
                     </a>
                  </div>
               </div>
               
               
            </div>
         </div> 
         
         
         
        
         {{-- 2nd portion of quick link  --}}
         <div class="my-4 mx-md-3 row calculation-part">
            {{-- total order --}}
            <div class="col-lg-3 col-sm-6 col-12"> 
               <div class="overflow-hidden card custom-card total-order-card">
                  <div class="p-4 card-body">
                     <span class="mb-3 d-block">Total Order</span>
                     <h4 class="mb-2 fw-medium" id="total_order_block">{{$totalOrder}}</h4>
                     {{-- <span class="fs-12">
                        This Month
                        <span class="text-success fs-12 fw-medium ms-2 d-inline-block">
                           <i class="ri-arrow-up-line me-1"></i>
                           2.45%
                        </span> 
                     </span>  --}}
                     <span class="calculation-cards-icon svg-white text-fixed-white"> 
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M62.55,144H188.1a16,16,0,0,0,15.74-13.14L216,64H48Z" opacity="0.2"></path><path d="M180,184H83.17a16,16,0,0,1-15.74-13.14L41.92,30.57A8,8,0,0,0,34.05,24H16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><circle cx="84" cy="204" r="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle><circle cx="180" cy="204" r="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle><path d="M62.55,144H188.1a16,16,0,0,0,15.74-13.14L216,64H48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path></svg>
                     </span> 
                  </div>
               </div> 
            </div>
            {{-- total sold --}}
            <div class="col-lg-3 col-sm-6 col-12"> 
               <div class="overflow-hidden card custom-card total-sold-card">
                  <div class="p-4 card-body">
                     <span class="mb-3 d-block">Total Sold</span>
                     {{-- <h4 class="mb-2 fw-medium"> 123</h4> --}}
                     <h4 class="mb-2 fw-medium" id="total_sold">
                        {{$totalSold}}
                     </h4>
                     
                     {{-- <span class="fs-12">
                        This Month
                        <span class="text-success fs-12 fw-medium ms-2 d-inline-block">
                           <i class="ri-arrow-up-line me-1"></i>
                           2.45%
                        </span> 
                     </span>  --}}
                     <span class="calculation-cards-icon svg-white text-fixed-white"> 
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M62.55,144H188.1a16,16,0,0,0,15.74-13.14L216,64H48Z" opacity="0.2"></path><path d="M180,184H83.17a16,16,0,0,1-15.74-13.14L41.92,30.57A8,8,0,0,0,34.05,24H16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><circle cx="84" cy="204" r="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle><circle cx="180" cy="204" r="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle><path d="M62.55,144H188.1a16,16,0,0,0,15.74-13.14L216,64H48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path></svg>
                        
                     </span> 
                  </div>
               </div> 
            </div>
            {{-- total discount --}}
            <div class="col-lg-3 col-sm-6 col-12"> 
               <div class="overflow-hidden card custom-card total-discount-card">
                  <div class="p-4 card-body">
                     <span class="mb-3 d-block">Total Discount ( Tk )</span>
                     <h4 class="mb-2 fw-medium" id="total_discount">
                        {{$totalDiscount}}
                        {{-- {{ $totalDiscount }} --}}
                     </h4>
                     {{-- <span class="fs-12">
                        This Month
                        <span class="text-success fs-12 fw-medium ms-2 d-inline-block" id="par">
                           <i class="ri-arrow-up-line me-1"></i> 2.45%
                        </span> 
                     </span>  --}}
                     <span class="calculation-cards-icon svg-white text-fixed-white"> 
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="percent" class="svg-inline--fa fa-percent text-[1.5rem]" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M374.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-320 320c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l320-320zM128 128A64 64 0 1 0 0 128a64 64 0 1 0 128 0zM384 384a64 64 0 1 0 -128 0 64 64 0 1 0 128 0z"></path></svg>
                     </span> 
                  </div>
               </div> 
            </div>
            {{-- Net  Amount --}}
            <div class="col-lg-3 col-sm-6 col-12"> 
               <div class="overflow-hidden card custom-card total-net-amount-card">
                  <div class="p-4 card-body">
                     <span class="mb-3 d-block">Net Amount ( Tk )</span>
                     <h4 class="mb-2 fw-medium" id="total_net_amount">
                        {{-- {{$totalNetAmount}} --}}
                        {{ isset($totalNetAmount) ? $totalNetAmount : 0 }}
                            
                        
                     </h4>
                     {{-- <span class="fs-12">
                        This Month
                        <span class="text-success fs-12 fw-medium ms-2 d-inline-block">
                           <i class="ri-arrow-up-line me-1"></i>
                           2.45%
                        </span> 
                     </span>  --}}
                     <span class="calculation-cards-icon svg-white text-fixed-white"> 
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M200,168a48.05,48.05,0,0,1-48,48H136v16a8,8,0,0,1-16,0V216H104a48.05,48.05,0,0,1-48-48,8,8,0,0,1,16,0,32,32,0,0,0,32,32h48a32,32,0,0,0,0-64H112a48,48,0,0,1,0-96h8V24a8,8,0,0,1,16,0V40h8a48.05,48.05,0,0,1,48,48,8,8,0,0,1-16,0,32,32,0,0,0-32-32H112a32,32,0,0,0,0,64h40A48.05,48.05,0,0,1,200,168Z"></path></svg>
                     </span> 
                  </div>
               </div> 
            </div>
            
         </div>
      </div>
   </div>
   
   {{-- chart part --}}
   <div class="mt-8 mb-4 col-md-12 chart-part">
       {{-- Top Customer Chart chart --}}
       <div class="mt-4 row" style="position:relative;">
         <h4>Top Customer</h4>
         <div class="mt-3 d-flex justify-content-between align-items-center">
            <p class="card-text chart-text">Customer By Highest Order</p>
         </div>
         <div id="top-customer" class="mt-4">
         </div>
      </div>
      
      {{-- sales By Date chart --}}
      <div class="mt-4 row" style="position:relative;">
         <h4>Sales By Date</h4>
         <div class="mt-3 d-flex justify-content-between align-items-center">
            <p class="card-text chart-text">Product Sales In you shop </p>
         </div>
         <div id="sales-by-date" class="mt-4">
         </div>
      </div>
      
      {{-- Top product chart --}}
      <div class="mt-4 row" style="position:relative;">
         <h4>Top Products</h4>
         <div class="mt-3 d-flex justify-content-between align-items-center">
            <p class="card-text chart-text">Top sales Products</p>
         </div>
         <div id="top-product" class="mt-4">
         </div>
      </div>
      
      {{-- Payable & Owing --}}
      {{-- <div class="mt-4 row payable-owing">
         <h4 class="mb-4">Payable & Owing</h4> --}}
            {{-- Invoices payable to you --}}
            
         {{-- <div class="col-md-6">
            <h6>Invoices payable to you</h6>
            <table class="table table-hover">
               <tbody> 
                  <tr>
                     <td class="text-primary">Coming Due</td>
                     <td>$300.00</td>
                  </tr>
                  <tr>
                     <td>1-30 days overdue</td>
                     <td>$1,732.72</td>
                  </tr>
                  <tr>
                     <td>31-60 days overdue</td>
                     <td>$0.00</td>
                  </tr>
                  <tr>
                     <td>61-90 days overdue</td>
                     <td>$0.00</td>
                  </tr>
                  <tr>
                     <td> > 90 days overdue</td>
                     <td>$118,078.88</td>
                  </tr>
               </tbody>
            </table>
         </div> --}}
            
         {{-- Bills You Owe --}}
         {{-- <div class="col-md-6">
            <h6>Bills you owe</h6>
            <table class="table table-hover">
               
               <tbody>
                  <tr>
                     <td class="text-primary">Coming Due</td>
                     <td>$0.00</td>
                  </tr>
                  <tr>
                     <td>1-30 days overdue</td>
                     <td>$0.00</td>
                  </tr>
                  <tr>
                     <td>31-60 days overdue</td>
                     <td>$0.00</td>
                  </tr>
                  <tr>
                     <td>61-90 days overdue</td>
                     <td>$0.00</td>
                  </tr>
                  <tr>
                     <td> > 90 days overdue</td>
                     <td>$1,999.98</td>
                  </tr>
               </tbody>
            </table>
         </div> --}}
      
      {{-- </div> --}}
    
      
      {{-- Net Income --}}
      {{-- <div class="mt-4 row">
         <h4>Net Income</h4>
            
         <div class="mt-3">
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th>Fiscal Year <i class="fa-regular fa-circle-question"></i></th>
                     <th>Previous</th>
                     <th>Current</th>
                  </tr>
               </thead>
               <tbody> 
                  <tr>
                     <td>Income</td>
                     <td class="text-primary">$0.00</td>
                     <td class="text-primary">$3,258.06</td>
                  </tr>
                  <tr>
                     <td>Expense</td>
                     <td class="text-primary">$0.00</td>
                     <td class="text-primary">$0.00</td>
                  </tr>
                  <tr>
                     <td>Net Income</td>
                     <td class="text-primary">$0.00</td>
                     <td class="text-primary">$3,258.06</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div> --}}
      
      
    
   </div>
   
@endsection



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
    
      // $('form').submit(function(event) {
      //    event.preventDefault(); // Prevent default form submission
      //    var formData = $(this).serialize(); // Serialize form data

      //    // AJAX call to update dashboard elements and charts
      //    $.ajax({
      //          url: $(this).attr('action'), // Use form action URL
      //          type: 'GET',
      //          data: formData, // Send serialized form data
      //          success: function(data) {
      //             // Update dashboard elements and charts based on returned data
      //             updateDashboardCards(data.shop_id); // Example function, update as needed
      //             updateTopCustomersChart(data.shop_id); // Example function, update as needed
      //             updateDatewiseSalesChart(data.shop_id); // Example function, update as needed
      //             updateTopProductsChart(data.shop_id); // Example function, update as needed
      //          },
      //          error: function() {
      //             console.log('Error retrieving data');
      //          }
      //    });
      // });
        function toggleTotalShopCard() {
            if ($('#shop_id').val() === '') {
                $('#totalShopCard').show();
            } else {
                $('#totalShopCard').hide();
            }
        }

        toggleTotalShopCard();

        $('#shop_id').change(function() {
            toggleTotalShopCard();
            updateDashboardCards($('#shop_id').val());
            updateTopCustomersChart($('#shop_id').val());
            updateTopProductsChart($('#shop_id').val());
            updateDatewiseSalesChart($('#shop_id').val());
            
        });
        
        
         //  quick links Card 
        function updateDashboardCards(shopId) {
            $.ajax({
                url: '{{ route("dashboard.update") }}', 
                type: 'GET',
                data: {
                    shop_id: shopId
                },
                success: function(data) {
                    // console.log("Data received: ", data);
                    $('#total_order').text(data.totalOrder + ' ' + 'Orders');
                    $('#total_customer').text(data.totalCustomer + ' ' + 'Customers');
                  //   $('#total_product').text(data.product + ' ' + 'products');
                    $('#total_product').text(data.totalProduct + ' ' + 'Products');
                    $('#total_top_product').text(data.totalTopProduct + ' ' + 'Products');
                    $('#total_appointment').text(data.totalAppointment + ' ' + 'Appointments');
                    $('#total_expense').text(data.totalExpense + ' ' + 'Expenses');
                    
                    
                    $('#total_order_block').text(data.totalOrder);
                    $('#total_discount').text(data.totalDiscount);
                    //   $('#total_net_amount').text(data.totalNetAmount);
                    $('#total_net_amount').text((data.totalNetAmount !== null && data.totalNetAmount !== undefined) ? data.totalNetAmount : '0');
                    $('#total_sold').text(data.totalSold);
                    
                    
                },
                error: function() {
                    console.log('Error retrieving data');
                }
            });
        }
        
         //   top Customer
        function updateTopCustomersChart(shopId) {
            $.ajax({
                url: '/top-customers', 
                type: 'GET',
                data: {
                    shop_id: shopId
                },
                success: function(data) {
                  var options = {
                     series: [{ 
                        name: 'Order',
                        data: data.orderCount 
                     }],
                     chart: {
                        type: 'bar',
                        height: 350
                     },
                     plotOptions: {
                        bar: {
                              horizontal: false,
                              borderRadius: 4,
                              columnWidth: '20px'
                        }
                     },
                     dataLabels: {
                        enabled: false
                     },
                     xaxis: {
                        categories: data.customerNames,
                        title: {
                              text: 'Top Customers Name',
                              style: { color: '#656565' }
                        }
                     },
                     yaxis: {
                        title: {
                              text: 'Total Order',
                              style: { color: '#656565' }
                        }
                     }
                  };
                  
                  if(window.topCustomerChart){
                     window.topCustomerChart.destroy();
                  }
                  window.topCustomerChart = new ApexCharts(document.querySelector("#top-customer"), options);
                  window.topCustomerChart.render();
                  
                     
                },
                error: function() {
                    console.log('Error retrieving Top Customer data');
                }
            });
        }
        updateTopCustomersChart();//for initial top customer before selecting shop
        
       //   Date wise Sales
        function updateDatewiseSalesChart(shopId) {
            $.ajax({
                url: '/date-wise-sale', 
                type: 'GET',
                data: {
                    shop_id: shopId
                },
                success: function(data) {
                  var options = {
                     series: [{ 
                        name: 'Total Sale',
                        data: data.totalSale 
                     }],
                     chart: {
                        type: 'bar',
                        height: 350
                     },
                     plotOptions: {
                        bar: {
                              horizontal: false,
                              borderRadius: 4,
                              columnWidth: '20px'
                        }
                     },
                     dataLabels: {
                        enabled: false
                     },
                     xaxis: {
                        categories: data.dateValue,
                        title: {
                              text: 'Datewise Sale',
                              style: { color: '#656565' }
                        }
                     },
                     yaxis: {
                        title: {
                              text: 'Total Sale (tk)',
                              style: { color: '#656565' }
                        }
                     }
                  };
                  
                  if(window.datewiseSaleChart){
                     window.datewiseSaleChart.destroy();
                  }
                  window.datewiseSaleChart = new ApexCharts(document.querySelector("#sales-by-date"), options);
                  window.datewiseSaleChart.render();
                  
                     
                },
                error: function() {
                    console.log('Error retrieving Datewise Sale data');
                }
            });
        }
        updateDatewiseSalesChart();//for initial Datewise Sale before selecting shop
        
        
       //   top Product Chart
        function updateTopProductsChart(shopId) {
            $.ajax({
                url: '/top-products', 
                type: 'GET',
                data: {
                    shop_id: shopId
                },
                success: function(data) {
                  var options = {
                     series: [{ 
                        name: 'Sold',
                        data: data.totalSold 
                     }],
                     chart: {
                        type: 'bar',
                        height: 350
                     },
                     plotOptions: {
                        bar: {
                           horizontal: false,
                           borderRadius: 2,
                           columnWidth:'20px',
                           colors: {
                                 ranges: [{
                                    from: 0,
                                    to: 100,
                                    color: '#546DFE'
                                 }]
                           }
                        
                        }
                     },
                     dataLabels: {
                        enabled: false
                     },
                     xaxis: {
                        categories: data.productNames,
                        title: {
                              text: 'Top Productrs Name',
                              style: { color: '#656565' }
                        }
                     },
                     yaxis: {
                        title: {
                              text: 'Total Sold',
                              style: { color: '#656565' }
                        }
                     }
                  };
                  
                  if(window.topProductChart){
                     window.topProductChart.destroy();
                  }
                  window.topProductChart = new ApexCharts(document.querySelector("#top-product"), options);
                  window.topProductChart.render();
                  
                     
                },
                error: function() {
                    console.log('Error retrieving Top Customer data');
                }
            });
        }
        updateTopProductsChart();//for initial top Products before selecting shop
      
    });
</script>


@endpush



