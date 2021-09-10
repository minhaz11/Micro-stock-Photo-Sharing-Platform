@extends('admin.layouts.app')

@section('panel')
    @if($general->sys_version)
        <div class="row">
            <div class="col-md-12">

                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title"> @lang('New Version Available')
                            <button
                                class="btn btn--dark float-right">@lang('Version') {{json_decode($general->sys_version)->version}}</button>
                        </h3>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-dark">@lang('What is the Update ?')</h5>
                        <p>
                        <pre class="font-20">{{@json_decode($general->sys_version)->details}}</pre>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--primary b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$widget['total_users']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Users')</span>
                    </div>
                    <a href="{{route('admin.users.all')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>

                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--info b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$data['contributor']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Contributors')</span>
                    </div>
                    <a href="{{route('admin.users.contributor.all')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>

                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--success b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$widget['totalReviewer']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Reviewers')</span>
                    </div>
                    <a href="{{route('admin.reviewer.all')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--purple b-radius--10 box-shadow ">
                <div class="icon">
                    <i class="fas fa-images"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$data['totalFreePhoto']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Free Photos')</span>
                    </div>
                    <a href="{{route('admin.image.free.all')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--teal b-radius--10 box-shadow ">
                <div class="icon">
                    <i class="fas fa-images"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$data['totalPremiumPhoto']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Premium Photos')</span>
                    </div>
                    <a href="{{route('admin.image.premium.all')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--dark b-radius--10 box-shadow ">
                <div class="icon">
                    <i class="fas fa-angle-double-right"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$data['activeCategory']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Active Category')</span>
                    </div>
                    <a href="{{route('admin.category.all')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--brown b-radius--10 box-shadow ">
                <div class="icon">
                    <i class="far fa-paper-plane"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$data['totalSubscription']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Subscriptions')</span>
                    </div>
                    <a href="{{route('admin.subscription.all')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--indigo b-radius--10 box-shadow ">
                <div class="icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$data['totalBadge']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Active Badges')</span>
                    </div>
                    <a href="{{route('admin.badges.all')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--20 b-radius--10 box-shadow ">
                <div class="icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$widget['totalFreeDownloads']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Free Downloads')</span>
                    </div>
                    <a href="{{route('admin.image.downloads')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--19 b-radius--10 box-shadow ">
                <div class="icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$widget['totalPremiumDownloads']}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Premium Downloads')</span>
                    </div>
                    <a href="{{route('admin.image.downloads')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->


        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--deep-purple b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount"> {{$data['pendingTicket']}} </span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Pending Tickets')</span>
                    </div>
                    <a href="{{route('admin.ticket.pending')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--gray b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount"> {{$data['totalTrx']}} </span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Transaction')</span>
                    </div>
                    <a href="{{route('admin.report.transaction')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--3 b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fa fa-wallet"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$paymentWithdraw['withdraw_method']}}</span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Withdraw Method')</span>
                    </div>
                    <a href="{{route('admin.withdraw.method.index')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--pink b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fa fa-hand-holding-usd"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{getAmount($paymentWithdraw['total_withdraw_amount'])}}</span>
                        <span class="currency-sign">{{trans($general->cur_text)}}</span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Withdraw')</span>
                    </div>
                    <a href="{{route('admin.withdraw.approved')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--1 b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fa fa-money-bill-alt"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{getAmount($paymentWithdraw['total_withdraw_charge'])}} </span>
                        <span class="currency-sign">{{trans($general->cur_text)}}</span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Withdraw Charge')</span>
                    </div>

                    <a href="{{route('admin.withdraw.approved')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--light-blue b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fa fa-spinner"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$paymentWithdraw['total_withdraw_pending']}}</span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Withdraw Pending')</span>
                    </div>
                    <a href="{{route('admin.withdraw.pending')}}"
                       class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>

    </div><!-- row end-->


    <div class="row mt-50 mb-none-30">
        <div class="col-xl-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Monthly  Deposit & Withdraw  Report')</h5>
                    <div id="apex-bar-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-30">
            <div class="row mb-none-30">
                <div class="col-lg-6 col-sm-6 mb-30">
                    <div class="widget-three box--shadow2 b-radius--5 bg--white">
                        <div class="widget-three__icon b-radius--rounded bg--primary box--shadow2">
                            <i class="las la-wallet "></i>
                        </div>
                        <div class="widget-three__content">
                            <h2 class="numbers">{{$payment['payment_method']}}</h2>
                            <p class="text--small">@lang('Total Payment Method')</p>
                        </div>
                    </div><!-- widget-two end -->
                </div>
                <div class="col-lg-6 col-sm-6 mb-30">
                    <div class="widget-three box--shadow2 b-radius--5 bg--white">
                        <div class="widget-three__icon b-radius--rounded bg--pink  box--shadow2">
                            <i class="las la-money-bill "></i>
                        </div>
                        <div class="widget-three__content">
                            <h2 class="numbers">{{getAmount($payment['total_deposit_amount'])}} {{trans($general->cur_text)}}</h2>
                            <p class="text--small">@lang('Total Deposit')</p>
                        </div>
                    </div><!-- widget-two end -->
                </div>
                <div class="col-lg-6 col-sm-6 mb-30">
                    <div class="widget-three box--shadow2 b-radius--5 bg--white">
                        <div class="widget-three__icon b-radius--rounded bg--teal box--shadow2">
                            <i class="las la-money-check"></i>
                        </div>
                        <div class="widget-three__content">
                            <h2 class="numbers">{{getAmount($payment['total_deposit_charge'])}} {{trans($general->cur_text)}}</h2>
                            <p class="text--small">@lang('Total Deposit Charge')</p>
                        </div>
                    </div><!-- widget-two end -->
                </div>
                <div class="col-lg-6 col-sm-6 mb-30">
                    <div class="widget-three box--shadow2 b-radius--5 bg--white">
                        <div class="widget-three__icon b-radius--rounded bg--green  box--shadow2">
                            <i class="las la-money-bill-wave "></i>
                        </div>
                        <div class="widget-three__content">
                            <h2 class="numbers">{{$payment['total_deposit_pending']}}</h2>
                            <p class="text--small">@lang('Pending Deposit')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row mb-none-30 mt-5">
        <div class="col-xl-12 mb-30">
            <div class="card ">
                <div class="card-header">
                    <h6 class="card-title mb-0">@lang('New User list')</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('User')</th>
                                <th scope="col">@lang('Username')</th>
                                <th scope="col">@lang('Email')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($latestUser as $user)
                                <tr>
                                    <td data-label="@lang('User')">
                                        <div class="user">
                                            <div class="thumb"><img
                                                    src="{{ getImage('assets/user/profile/'. $user->image,'350x300')}}"
                                                    alt="image"></div>
                                            <span class="name">{{$user->fullname}}</span>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Username')"><a
                                            href="{{ route('admin.users.detail', $user->id) }}">{{ $user->username }}</a>
                                    </td>
                                    <td data-label="@lang('Email')">{{ $user->email }}</td>
                                    <td data-label="@lang('Action')">
                                        <a href="{{ route('admin.users.detail', $user->id) }}" class="icon-btn"
                                           data-toggle="tooltip" title="" data-original-title="@lang('Details')">
                                            <i class="las la-desktop text--shadow"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">@lang('User Not Found')</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row mb-none-30 mt-5">
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Browser')</h5>
                    <canvas id="userBrowserChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By OS')</h5>
                    <canvas id="userOsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Country')</h5>
                    <canvas id="userCountryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script-lib')
    <script src="{{asset('assets/admin/js/vendor/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/vendor/chart.js.2.8.0.js')}}"></script>
@endpush

@push('script')

    <script>
        "use strict";
        // apex-bar-chart js
        var options = {
            series: [{
                name: 'Total Deposit',
                data: [
                    @foreach($report['months'] as $month)
                        {{ getAmount(@$depositsMonth->where('months',$month)->first()->depositAmount) }},
                    @endforeach
                ]
            }, {
                name: 'Total Withdraw',
                data: [
                    @foreach($report['months'] as $month)
                        {{ getAmount(@$withdrawalMonth->where('months',$month)->first()->withdrawAmount) }},
                    @endforeach
                ]
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
                    columnWidth: '50%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: true
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: @json($report['months']->flatten()),
            },
            yaxis: {
                title: {
                    text: "{{__($general->cur_sym)}}",
                    style: {
                        color: '#7c97bb'
                    }
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                },
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "{{__($general->cur_sym)}}" + val + " "
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#apex-bar-chart"), options);
        chart.render();


    </script>




    <script>

        var ctx = document.getElementById('userBrowserChart');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_browser_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_browser_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675',
                        '#6c5ce7',
                        '#ffa62b',
                        '#ffeaa7',
                        '#D980FA',
                        '#fccbcb',
                        '#45aaf2',
                        '#05dfd7',
                        '#FF00F6',
                        '#1e90ff',
                        '#2ed573',
                        '#eccc68',
                        '#ff5200',
                        '#cd84f1',
                        '#7efff5',
                        '#7158e2',
                        '#fff200',
                        '#ff9ff3',
                        '#08ffc8',
                        '#3742fa',
                        '#1089ff',
                        '#70FF61',
                        '#bf9fee',
                        '#574b90'
                    ],
                    borderColor: [
                        'rgba(231, 80, 90, 0.75)'
                    ],
                    borderWidth: 0,

                }]
            },
            options: {
                aspectRatio: 1,
                responsive: true,
                maintainAspectRatio: true,
                elements: {
                    line: {
                        tension: 0 // disables bezier curves
                    }
                },
                scales: {
                    xAxes: [{
                        display: false
                    }],
                    yAxes: [{
                        display: false
                    }]
                },
                legend: {
                    display: false,
                }
            }
        });


        var ctx = document.getElementById('userOsChart');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_os_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_os_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675',
                        '#6c5ce7',
                        '#ffa62b',
                        '#ffeaa7',
                        '#D980FA',
                        '#fccbcb',
                        '#45aaf2',
                        '#05dfd7',
                        '#FF00F6',
                        '#1e90ff',
                        '#2ed573',
                        '#eccc68',
                        '#ff5200',
                        '#cd84f1',
                        '#7efff5',
                        '#7158e2',
                        '#fff200',
                        '#ff9ff3',
                        '#08ffc8',
                        '#3742fa',
                        '#1089ff',
                        '#70FF61',
                        '#bf9fee',
                        '#574b90'
                    ],
                    borderColor: [
                        'rgba(0, 0, 0, 0.05)'
                    ],
                    borderWidth: 0,

                }]
            },
            options: {
                aspectRatio: 1,
                responsive: true,
                elements: {
                    line: {
                        tension: 0 // disables bezier curves
                    }
                },
                scales: {
                    xAxes: [{
                        display: false
                    }],
                    yAxes: [{
                        display: false
                    }]
                },
                legend: {
                    display: false,
                }
            },
        });


        // Donut chart
        var ctx = document.getElementById('userCountryChart');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_country_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_country_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675',
                        '#6c5ce7',
                        '#ffa62b',
                        '#ffeaa7',
                        '#D980FA',
                        '#fccbcb',
                        '#45aaf2',
                        '#05dfd7',
                        '#FF00F6',
                        '#1e90ff',
                        '#2ed573',
                        '#eccc68',
                        '#ff5200',
                        '#cd84f1',
                        '#7efff5',
                        '#7158e2',
                        '#fff200',
                        '#ff9ff3',
                        '#08ffc8',
                        '#3742fa',
                        '#1089ff',
                        '#70FF61',
                        '#bf9fee',
                        '#574b90'
                    ],
                    borderColor: [
                        'rgba(231, 80, 90, 0.75)'
                    ],
                    borderWidth: 0,

                }]
            },
            options: {
                aspectRatio: 1,
                responsive: true,
                elements: {
                    line: {
                        tension: 0 // disables bezier curves
                    }
                },
                scales: {
                    xAxes: [{
                        display: false
                    }],
                    yAxes: [{
                        display: false
                    }]
                },
                legend: {
                    display: false,
                }
            }
        });
    </script>
@endpush
