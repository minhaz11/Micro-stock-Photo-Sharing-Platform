@extends($activeTemplate.'layouts.user.master')

@section('content')
   
        <div class="row justify-content-center mt-2">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h6>{{ trans($page_title) }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm">
                        <table class="table cmn--table">
                            <thead class="base--bg text-white">
                            <tr>
                                <th scope="col">@lang('Date')</th>
                                <th scope="col">@lang('Image Title')</th>
                                <th scope="col">@lang('Amount')</th>
                               
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($logs) >0)
                                @foreach($logs as $k => $data)
                               
                                    <tr>
                                        <td data-label="#@lang('Date')">{{$data->date}}</td>
                                        <td data-label="@lang('Image Title')">{{ $data->image->title }}</td>
                                        <td data-label="@lang('Amount')">
                                            <strong>{{getAmount($data->amount)}} {{$general->cur_text}}</strong>
                                        </td>
                                       

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="100%" class="text-center"> @lang($empty_message)!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                {{paginateLinks($logs)}}
            </div>
        </div>
    </div>


   
  
@endsection



