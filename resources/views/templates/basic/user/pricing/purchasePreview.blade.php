@extends($activeTemplate.'layouts.user.master')

@section('content')
   
<div class="row justify-content-center">
           
    <div class="col-xl-10 col-lg-10 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>@lang('Purchase Preview')</h5>
            </div>
            <form action="{{route('user.purchase.subscription.confirm')}}" method="POST">
                @csrf
                <input type="hidden" value="{{$plan->id}}" name="planid">
                <ul class="list-group text-center">
                    <li class="list-group-item"><strong>@lang('Subscription Name : ')</strong><strong class="text-success">{{$plan->name}}</strong> </li>
                    <li class="list-group-item"><strong>@lang('Daily Download Limit : ')</strong><strong class="text-success">{{$plan->daily_limit}} </strong></li>
                    <li class="list-group-item"><strong>@lang('Monthly Download Limit : ')</strong><strong class="text-success">{{$plan->monthly_limit}} </strong></li>
                    <li class="list-group-item"><strong>@lang('Yearly Download Limit : ')</strong><strong class="text-success">{{$plan->yearly_limit}}</strong> </li>
                    <li class="list-group-item"><strong>@lang('Subscription Price : ')</strong><strong class="text-success">{{getAmount($plan->price)}} {{$general->cur_text}}</strong></li>
                  
                    <li class="list-group-item"><strong>@lang('Total Payable : ')</strong><strong class="text-success">{{getAmount($plan->price)}} {{$general->cur_text}}</strong></li>
                  
                    <li class="list-group-item">@lang("NB : Remember") <strong class="text-danger">{{getAmount($plan->price)}} {{$general->cur_text}}</strong> @lang('will be deducted from your balance. Your current balance is ') <strong class="text-success">{{getAmount(auth()->user()->balance)}} {{$general->cur_text}}</strong> </li>
                  
                    <li class="list-group-item"><button type="submit" class="cmn-btn btn-block btn-lg">@lang('Confirm')</button></li>
                   
                </ul>
            </form>
        </div>
    </div>


    </div>
    <!-- /.row -->
  
   <!-- /modal -->
  
  
 
@endsection

