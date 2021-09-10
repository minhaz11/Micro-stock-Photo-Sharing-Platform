@extends($activeTemplate.'layouts.user.master')

@section('content')
          @if (auth()->user()->subscription)
          <div class="row mt-10">
            <div class="col-xl-4 col-sm-6 mb-30">
              <div class="d-widget-two color--one d-flex flex-wrap">
                <div class="col-8">
                  <span class="caption">@lang('Today\'s download count')</span>
                  <h4 class="currency-amount">{{$dailyCount}}</h4>
                </div>
                <div class="col-4">
                  <div class="icon ml-auto">
                    <i class="las la-cloud-download-alt"></i>
                  </div>
                </div>
              </div><!-- d-widget-two end -->
            </div>
            <div class="col-xl-4 col-sm-6 mb-30">
              <div class="d-widget-two color--two d-flex flex-wrap">
                <div class="col-8">
                  <span class="caption">@lang('This Month download Count')</span>
                  <h4 class="currency-amount">{{$monthlyCount}}</h4>
                </div>
                <div class="col-4">
                  <div class="icon ml-auto">
                    <i class="las la-cloud-download-alt"></i>
                  </div>
                </div>
              </div><!-- d-widget-two end -->
            </div>
            <div class="col-xl-4 col-sm-6 mb-30">
              <div class="d-widget-two color--three d-flex flex-wrap">
                <div class="col-8">
                  <span class="caption">@lang('Yearly Download Count')</span>
                  <h4 class="currency-amount">{{$yearlyCount}}</h4>
                </div>
                <div class="col-4">
                  <div class="icon ml-auto">
                    <i class="las la-cloud-download-alt"></i>
                  </div>
                </div>
              </div><!-- d-widget-two end -->
            </div>
          @endif


            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h6>{{ trans($page_title) }}</h6>
                </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table cmn--table white-space-nowrap">
                    <thead>
                      <tr>
                        <th>@lang('Photo')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Resolution')</th>
                        <th>@lang('Type')</th>
                        <th>@lang('Likes')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($downloads->unique('image_id') as $item)
                        <tr>
                            <td data-label="@lang('Photo')">
                              <div class="photo has-link">
                                <div class="thumb"><img src="{{imageUrl(@$item->image->image_thumb)}}" alt="image"></div>
                                <p>
                                  @if ($item->image->title)
                                    <a href="{{route('image.details',[$item->image->id, slug($item->image->title)])}}">{{$item->image->title}}</a>
                                  @else
                                      @lang('removed')
                                  @endif
                                  
                                  </p>
                              </div>
                            </td>
                            <td data-label="@lang('date')">{{$item->date}}</td>
                            <td data-label="@lang('Resolution')">{{$item->resolution}}</td>
                            <td data-label="@lang('Type')">{{$item->type}}</td>
                            @if($item->image)
                             <td data-label="@lang('Likes')"><span class="badge badge-warning">@lang(number_format_short($item->image->likes->count()))</span></td>
                            @else
                              <td data-label="@lang('Likes')"><span class="badge badge-warning">0</span></td>
                           @endif
                          <td class="text-center" data-label="@lang('Status')"><span class="badge {{@$item->image->status==1 ?'badge-success':'badge-warning'}}">{{@$item->image->status==1 ? 'active':'deactive'}}</span></td>
                            <td>
                                <div class="photo-details">
                                    <a href="{{getImage('assets/contributor/watermark/'.@$item->image->image_name)}}"  data-rel="lightcase" class="icon-btn btn-primary"><i class="fas fa-eye text-white"></i></a>
                                </div>
                            </td>
                          </tr>
                        @empty
                        <tr><td colspan="12" class="text-center">{{$empty_message}}</td></tr>
                        @endforelse
                    </tbody>
                        
                  </table>
                </div>
              </div>
              {{paginateLinks($downloads,'')}}
            </div>
          </div>
        </div>
@stop

@push('style')
    
<style>
.photo-details {
  
    text-align: right;
   
    }
</style>

@endpush
