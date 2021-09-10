@extends($activeTemplate.'layouts.user.master')

@section('content')
        <!-- inner hero start -->
                <div class="row mb-none-30">

                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two color--two d-flex flex-wrap">
                        <div class="col-8">
                          <span class="caption">@lang('Balance')</span>
                          <h4 class="currency-amount">{{$general->cur_sym}} {{getAmount(auth()->user()->balance)}}</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                            <a href="javacript:void(0)"><i class="las la-wallet"></i></a>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>

                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two color--three d-flex flex-wrap">
                        <div class="col-8">
                         <a href="{{route('user.deposit.history')}}"> <span class="caption">@lang('Total Deposit')</span></a>
                        <h4 class="currency-amount">{{$general->cur_sym}} {{getAmount(auth()->user()->deposits->sum('amount'))}}</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                           <i class="las la-wallet"></i>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>
                  @if ($user->con_flag==1)
                  <div class="col-xl-4 col-sm-6 mb-30">
                    <div class="d-widget-two color--three d-flex flex-wrap">
                      <div class="col-8">
                        <a href="{{route('user.earning.logs')}}">  <span class="caption">@lang('Total Earnings')</span></a>
                      <h4 class="currency-amount">{{$general->cur_sym}} {{getAmount($data['totalEarnings'])}}</h4>
                      </div>
                      <div class="col-4">
                        <div class="icon ml-auto">
                          <i class="las la-wallet"></i>
                        </div>
                      </div>
                    </div><!-- d-widget-two end -->
                  </div>
                  @endif
                  @if ($user->con_flag==1)
                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two color--three d-flex flex-wrap">
                        <div class="col-8">
                         <a href="{{route('user.withdraw.history')}}"><span class="caption">@lang('Total Widthdraw')</span></a>
                        <h4 class="currency-amount">{{$general->cur_sym}} {{getAmount($data['totalWithdraw'])}}</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                            <i class="las la-wallet"></i>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>
                    @endif
                 
                    @if ($user->con_flag==1)
                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two d-flex flex-wrap">
                        <div class="col-8">
                          <span class="caption">@lang('Photos Got liked (Total)')</span>
                        <h4 class="currency-amount">{{$data['likes']}}</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                            <i class="las la-heart"></i>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>
                   @endif
                   
                   @if ($user->con_flag == 1)
                   <div class="col-xl-4 col-sm-6 mb-30">
                    <div class="d-widget-two color--five d-flex flex-wrap">
                      <div class="col-8">
                        <span class="caption">@lang('Photos Got Downloaded (Total)')</span>
                        <h4 class="currency-amount">@lang( $data['totalDownloads'])</h4>
                      </div>
                      <div class="col-4">
                        <div class="icon ml-auto">
                          <i class="las la-cloud-download-alt"></i>
                        </div>
                      </div>
                    </div><!-- d-widget-two end -->
                  </div>
                   @endif
                 
                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two color--six d-flex flex-wrap">
                        <div class="col-8">
                          <a href="{{route('contributor.follower.all')}}"><span class="caption">@lang('Total Follower')</span></a>
                          <h4 class="currency-amount">@lang( $data['totalFollower'])</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                            <i class="las la-user-friends"></i>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>


                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two color--six d-flex flex-wrap">
                        <div class="col-8">
                          <a href="{{route('user.following.all')}}"><span class="caption">@lang('Total Following')</span></a>
                          <h4 class="currency-amount">@lang( $data['totalFollows'])</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                            <i class="las la-user-friends"></i>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>
                  

                    @if ($user->con_flag==1)
                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two color--four d-flex flex-wrap">
                        <div class="col-8">
                          <a href="{{route('contributor.image.approved')}}"><span class="caption">@lang('Total Approved Photos')</span></a>
                          <h4 class="currency-amount">@lang( $data['totalPhotos'])</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                            <i class="las la-image"></i>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two color--four d-flex flex-wrap">
                        <div class="col-8">
                         <a href="{{route('contributor.image.all')}}"> <span class="caption">@lang('Total Pending Photos')</span></a>
                          <h4 class="currency-amount">@lang( $data['totalPendingPhotos'])</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                            <i class="las la-image"></i>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two color--five d-flex flex-wrap">
                        <div class="col-8">
                          <a href="{{route('contributor.image.banned')}}"> <span class="caption">@lang('Total Banned Photos')</span></a>
                          <h4 class="currency-amount">@lang( $data['totalBannedPhotos'])</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                            <i class="las la-image"></i>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two color--three d-flex flex-wrap">
                        <div class="col-8">
                          <span class="caption">@lang('Today\'s Earning')</span>
                          <h4 class="currency-amount">@lang( getAmount($data['todaysEarning']))</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                            <i class="las la-hand-holding-usd"></i>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>
                    @endif
                    @if ($user->con_flag==0)
                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two color--four d-flex flex-wrap">
                        <div class="col-8">
                          <span class="caption">@lang('Total Collections')</span>
                          <h4 class="currency-amount">@lang( $data['totalCollection'])</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                            <i class="las la-image"></i>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                      <div class="d-widget-two color--three d-flex flex-wrap">
                        <div class="col-8">
                          <a href="{{route('user.referred.users')}}"><span class="caption">@lang('Total Referrals')</span></a>
                          <h4 class="currency-amount">@lang( $data['referredUsers'])</h4>
                        </div>
                        <div class="col-4">
                          <div class="icon ml-auto">
                            <i class="las la-users"></i>
                          </div>
                        </div>
                      </div><!-- d-widget-two end -->
                    </div>
                    @endif
                  </div><!-- row end -->


                 <div class="row mt-50">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body ">
                        <h6>@lang('Your Referral Link')</h6><span><small>@lang('Refer someone through this link to get '.$general->ref_bonus.' % bonus in each subscription they purchase.')</small></span>

                        <div class="input-group mt-2">
                          <input type="text" id="p1" class="form-control" value="{{url('/register/'.$user->username)}}" disabled>
                          <div class="input-group-append">
                            <button class="btn btn-outline-secondary copy"  data-clipboard-text="{{url('/register/'.$user->username)}}">@lang('Copy')</button>
                          </div>
                        </div>
                      </div>
                    </div><!-- card end -->
                  </div>
                 
                </div>

                  @if ($user->con_flag==1)
                  <div class="row mt-50">
                    <div class="col-lg-6">
                      <div class="card">
                        <div class="card-body">
                          <h6>@lang('Total downloads Of this year')</h6>
                          <div id="sale-bar-chart"> </div>
                        </div>
                      </div><!-- card end -->
                    </div>
                    <div class="col-lg-6">
                      <div class="card">
                        <div class="card-body">
                          <h6>@lang('Total Earnings Of this year')</h6>
                          <div id="earn-bar-chart"> </div>
                        </div>
                      </div><!-- card end -->
                    </div>
                  </div>

                  <div class="row mt-50">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-header">
                            <h6>@lang('Latest Approved Photos')</h6>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
                            <table class="table cmn--table white-space-nowrap">
                              <thead>
                                <tr>
                                  <th>@lang('Photo')</th>
                                  <th>@lang('Date')</th>
                                  <th>@lang('Action')</th>
                                </tr>
                              </thead>
                              <tbody>
                                  @forelse ($approveImages as $image)
                                  <tr>
                                      <td>
                                        <div class="photo has-link">
                                          <a href="#0" class="item-link"></a>
                                          <div class="thumb"><img src="{{imageUrl($image->image_name,'100x100')}}" alt="image"></div>
                                        <p>{{$image->title}}</p>
                                        </div>
                                      </td>
                                      <td>{{showDateTime($image->date)}}</td>
                                      
                                      <td>
                                        <a href="javascript:void(0)" data-route="{{route('contributor.image.remove',$image->id)}}" class="icon-btn bg-danger delete"><i class="fas fa-trash-alt text-white"></i></a>
                                      </td>
                                    </tr>
                                  @empty
                                  <tr ><td colspan="12" class="text-center">@lang('No Approved photos')</td></tr>
                                  @endforelse
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div><!-- card end -->
                    </div>
                  </div>

                      <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                           <button type="button" class="close ml-auto m-3" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                           </button>
                                <form action="" method="POST">
                                    @csrf
                                    <div class="modal-body text-center">

                                        <i class="las la-exclamation-circle text-danger f-size  mb-15"></i>
                                        <h3 class="text--secondary mb-15">@lang('Are You Sure Want to Delete This?')</h3>
                
                                    </div>
                                <div class="modal-footer justify-content-center">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('close')</button>
                                  <button type="submit"  class="btn btn-danger del">@lang('Delete')</button>
                                </div>
                                
                                </form>
                          </div>
                        </div>
                    </div>
                  @endif<!-- row end -->
                <!-- row end -->
                

          <!-- dashboard section end -->
@endsection
@push('script-lib')

<script src="{{asset($activeTemplateTrue.'js/clipboard.min.js')}}"></script>
@endpush

@push('script')
@if ($user->con_flag==1)
<script>

  'use strict'
    // apex-bar-chart js 
    var options = {
      series: [{
      name: 'Total download',
      data: @json($report['downloadTotal']->flatten())
    }],
      chart: {
      type: 'bar',
      height: 350,
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '15%',
        endingShape: 'rounded'
      },
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
    xaxis: {
      categories: @json($report['downloadMonths']->flatten()),
    },
   
    fill: {
      opacity: 1
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return "$ " + val + " thousands"
        }
      }
    }
    };

    var chart = new ApexCharts(document.querySelector("#sale-bar-chart"), options);
    chart.render();
   
   
   //earnings
    var options = {
      series: [{
      name: 'Total Earnings',
      data: @json($report['earnings']->flatten())
    }],
      chart: {
      type: 'bar',
      height: 350,
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '15%',
        endingShape: 'rounded'
      },
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
    xaxis: {
      categories: @json($report['earning_month']->flatten()),
    },
   
    fill: {
      opacity: 1
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return "$ " + val + " thousands"
        }
      }
    }
    };

    var chart = new ApexCharts(document.querySelector("#earn-bar-chart"), options);
    chart.render();

 
</script>
@endif

  <script>
    'use strict'
      var clipboard = new ClipboardJS('.copy');

      clipboard.on('success', function(e) {
        notify('success','Referral link copied')
      });

    
      $('.copy').on('click',function(){
        $(this).text('copied')
      })


     $('.delete').on('click',function(){
        var route = $(this).data('route')
        var modal = $('#deleteModal');
        modal.find('form').attr('action',route)
        modal.modal('show');

    })
  </script>
@endpush

@push('style')
  
    <style>
      .f-size{
        font-size: 100px;
      }
    </style>

@endpush