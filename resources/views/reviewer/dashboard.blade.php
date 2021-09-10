@extends('reviewer.layouts.app')

@section('panel')
     @if($general->sys_version)
        <div class="row">
            <div class="col-md-12">

                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title"> @lang('New Version Available') <button class="btn btn--dark float-right">Version {{json_decode($general->sys_version)->version}}</button> </h3>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-dark">@lang('What is the Update ?')</h5>
                        <p><pre  class="font-20">{{@json_decode($general->sys_version)->details}}</pre></p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row mb-none-30">
        <div class="col-xl-4 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--gradi-1 b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$data['totalApproved']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Approved')</span>
                    </div>
                    
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xl-4 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--gradi-6 b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{ $data['totalRejected']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Rejected')</span>
                    </div>
                   
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--gradi-42 b-radius--10 box-shadow ">
                <div class="icon">
                    <i class="la la-envelope"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$data['totalReportReview']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Report Review')</span>
                    </div>

                   
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xl-4 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--gradi-21 b-radius--10 box-shadow ">
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{ $data['totalBanned']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Banned')</span>
                    </div>

                   
                </div>
            </div>
        </div><!-- dashboard-w1 end -->


        <div class="col-xl-4 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--gradi-44 b-radius--10 box-shadow" >
                <div class="icon">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                       
                    <span class="amount">{{  $data['totalPending']}}</span>
                      
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Pending Photos')</span>
                    </div>

               
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xl-4 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--gradi-7 b-radius--10 box-shadow" >
                <div class="icon">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                      
                        <span class="amount">{{ $data['totalPendingReport']}}</span>
                     
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Pending Report')</span>
                    </div>

               
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

    </div><!-- row end-->


    <div class="row mt-50 mb-none-30">
        <div class="col-xl-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Monthly  Approved Photos by You')</h5>
                    <div id="apex-bar-chart-app"> </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Monthly Photos Rejected by You')</h5>
                    <div id="apex-bar-chart-rej"> </div>
                </div>
            </div>
        </div>
       
    </div><!-- row end -->



@endsection

@push('script')

    <script src="{{asset('assets/admin/js/vendor/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/vendor/chart.js.2.8.0.js')}}"></script>


    <script>
        'use strict'
            // apex-bar-chart js
            var options = {
                series: [{
                    name: 'Total Approved',
                    data: @json($report['approved']->flatten())
                }],
                chart: {
                    type: 'bar',
                    height: 400,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '10%',
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
                    categories: @json($report['app_months']->flatten()),
                },
                yaxis: {
                    title: {
                        text: "pc's",
                        style: {
                            color: '#7c97bb'
                        }
                    }
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                },
                fill: {
                    opacity: 1
                },
               
            };

            var chart = new ApexCharts(document.querySelector("#apex-bar-chart-app"), options);
            chart.render();



    </script>
   
    <script>
        'use strict'
            // apex-bar-chart js
            var options = {
                series: [{
                    name: 'Total Rejected',
                    data: @json($report['rejected']->flatten())
                }],
                chart: {
                    type: 'bar',
                    height: 400,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '10%',
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
                    categories: @json($report['rej_months']->flatten()),
                },
                yaxis: {
                    title: {
                        text: "pc's",
                        style: {
                            color: '#7c97bb'
                        }
                    }
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                },
                fill: {
                    opacity: 1
                },
               
            };

            var chart = new ApexCharts(document.querySelector("#apex-bar-chart-rej"), options);
            chart.render();



    </script>

@endpush 
