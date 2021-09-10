@extends($activeTemplate.'layouts.user.master')
@php
    $auth = auth()->user();
@endphp

@section('content')
        <div class="row mt-10">
            <div class="col-lg-12">
                <div class="card mb-30 p-0">
                  <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h6 class="mt-3">{{ trans($page_title) }}</h6>
                      <form class="subscribe-form search__field mr-0" action="{{route('contributor.follower.search')}}" method="GET">
                        @csrf
                            <input type="text" name="search" id="subscribe-field" placeholder="{{trans('Search by username')}}"   class="form-control" autocomplete="off" value="{{$search??''}}">
                              <button type="submit" class="subscribe-btn"><i class="las la-search"></i></button>
                        </form>
                            
                      </div>
                </div>
              
                <div class="card-body">
                  <div class="table-responsive" id="user">
                    <table class="table cmn--table white-space-nowrap">
                      <thead>
                        <tr>
                          <th>@lang('User Photo')</th>
                          <th>@lang('Joined')</th>
                          <th>@lang('Country')</th>
                          <th>@lang('Action')</th>
                        </tr>
                      </thead>
                      <tbody>
                          @forelse ($followerUsers as $user)
                          <tr>
                              <td data-label="@lang('User Photo')">
                                <div class="photo has-link">
                                  <div class="thumb"><img src="{{getImage('assets/user/profile/'.$user->image,'100x100')}}" alt="image">
                                    
                                  </div>
                                <p>{{$user->username}}</p>
                                </div>
                              </td>
                              <td data-label="@lang('Joined')">{{diffForHumans( $user->created_at)}}</td>
                              <td data-label="@lang('Country')">{{$user->address->country??'N/A'}}</td>
                              <td data-label="@lang('Action')">
                              <a href="{{route('contributor.follower.remove',[$user->id,$auth->id])}}" class="icon-btn bg-danger w-auto text-white px-2">{{trans('Remove')}}</a>
                              </td>
                            </tr>
                            @empty
                           <tr>
                              <td colspan="12" class="text-center">{{$empty_message}}</td>
                          </tr>
                          @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              {{paginateLinks($followerUsers)}}
            </div>
          </div>
          </div>

    
@stop

@push('style')
    
<style>
.photo-details {
    text-align: right;
    
    }
    select option {
    background-color: #f1eeef;
}

#con{
    display: none
}
</style>

@endpush

@push('script')
    
 <script>
   'use strict'
    $(document).on('click','.follow',function(){
    if($(this).text()=='Unfollow'){
        $(this).text('{{trans("Follow")}}')
    } else {
    $(this).text('{{trans("Unfollow")}}')
    }

    var url = '{{route("follow")}}'
    var data = {
      follower : $(this).data('follower'),
      followed: $(this).data('followed')
    }
    axios.post(url,data, {
    headers: {
      'Content-Type': 'application/json',
    }
 
  })
  
  .then(function (response) {
    iziToast.success({message:response.data.success, position: "topRight"})

  })
  .catch(function (error) {
    console.log(error);
  })

  })

</script>

@endpush
