@extends($activeTemplate.'layouts.frontend')
@php
    $follower = $image->user->follower($image->user->id);
@endphp

@section('content')
@include($activeTemplate.'partials.breadcrumb')
<div class="pt-120 pb-120">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="photo-details-thumb">
            <img src="{{imageUrl($image->image_name)}}" alt="image">
            <a href="{{imageUrl($image->image_name)}}" data-rel="lightcase" class="image-view"></a>
          </div>
          <div class="photo-details-meta mt-4">
            <div class="left">
              <div class="author mr-2 has-link">
                <a href="{{route('con.profile',$image->user->id)}}" class="item-link"></a>
                <div class="thumb position-relative">
                  <img src="{{getImage('assets/user/profile/'.$image->user->image)}}" alt="image">
                  @if ($image->user->myBadge)
                  <span class="author-badge style--two">
                    <img src="{{getImage('assets/images/badge/'.$image->user->myBadge->badge->icon)}}" data-toggle="tooltip" data-placement="bottom" title="{{$image->user->myBadge->badge->name.' author'}}">
                  </span>
                  @endif
                </div>
                <div class="content" id="content">
                <h6 class="name"><a href="{{route('con.profile',$image->user->id)}}">{{$image->user->username}}</a>
                
                </h6>
                   <span id="count">{{number_format_short($follower)}} {{$follower > 1 ?'Followers':'Follower'}}</span>
                </div>
              </div>
              <div class="follow-box" id="follow-box">
              
              @if (auth()->guest())
              <a href="{{route('user.login')}}" class="cmn-btn btn-sm ml-md-4 mt-md-0 mt-3 follow"><i class="las la-plus"></i> @lang('Follow')</a>
              @else
                  @if ($image->user->id != $user->id)
                  @if (!$user->followRemoved($user->id,$image->user->id))
                    @if ($user::follow($user->id,$image->user->id))
                    <a href="javascript:void(0)" title="{{trans('click to unfollow')}}" class="border-btn btn-sm ml-md-4 mt-md-0 mt-3 follow" data-follower="{{$user->id}}" data-followed="{{$image->user->id}}"><i class="las la-check-double"></i>  @lang('Following')</a>
                    @else
                    <a href="javascript:void(0)" class="cmn-btn btn-sm ml-md-4 mt-md-0 mt-3 follow" data-follower="{{$user->id}}" data-followed="{{$image->user->id}}"><i class="las la-plus"></i> @lang('Follow')</a>
                    @endif
                  @endif
                  @endif
              @endif
           
              </div>
            </div>
            <div class="right">
              <ul class="image-share-list justify-content-lg-end">
                <li class="caption">@lang('Share') :</li>
                <li><a href="https://www.facebook.com/sharer/sharer.php?u={{urlencode(url()->current()) }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="https://twitter.com/intent/tweet?text={{slug($image->title)}}%0A{{ url()->current() }}" target="_blank"><i class="fab fa-twitter"></i></a></li>
              <li><a href="http://pinterest.com/pin/create/button/?url={{url()->current()}}&description={{slug($image->title)}}" target="_blank"><i class="fab fa-pinterest-p"></i></a></li>
                <li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{urlencode(url()->current()) }}" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>

              </ul>
            </div>
          </div>
          <div class="image-description">
            <b class="d-block mb-2">@lang('Description')</b>
            <p class="description-text">
              {{$image->description}}
             </p>

             <b class="d-block mb-2 mt-3">@lang('Tags')</b>
             <ul class="image-tags-list d-flex flex-wrap">
               @foreach ($image->tags as $tag)
                <li>{{$tag}}</li>  
               @endforeach
              
             </ul>
          </div>
        </div>
        <div class="col-lg-4 pl-lg-4 pl-xl-5 mt-lg-0 mt-5">
          <div class="sidebar">
            <div class="widget">
              <h6 class="widget__title">@lang('Download')</h6>
              <form class="image-size-form" action="{{route('download')}}" method="GET" id="form">
                @csrf
              <input type="hidden" name="track" value="{{$image->track_id}}">
            
               <div class="text-center">
                <button type="submit" class="cmn-btn w-100 "><i class="fas fa-cloud-download-alt mr-2"></i> @lang('Download')</button>
              </div>
  
              </form>
            </div><!-- widget end -->
            <div class="widget">
              <ul class="stat-list">
                <li>
                  <p class="stat-amount">@lang(number_format_short($image->views->count()))</p>
                  <span class="caption">@lang('Views')</span>
                </li>
                <li>
                  <p class="stat-amount" id="like-count">@lang(number_format_short($image->likes->count()))</p>
                  <span class="caption">@lang('Likes')</span>
                </li>
                <li>
                  <p class="stat-amount">@lang(number_format_short($image->downloads->count()))</p>
                  <span class="caption">@lang('Downloads')</span>
                </li>
              </ul>
              <div class="image-action-meta mt-4">
               
             
               <div class="left py-1" id="left">
               @if ($user)
                  @if ($user->likes()->where('image_id', $image->id)->first())
                  <a href="javascript:void(0)" class="border-btn btn-sm liked like" data-img="{{$image->id}}"><i class="far fa-heart"></i></a>
                  @else
                  <a href="javascript:void(0)" class="border-btn btn-sm like" data-img="{{$image->id}}" ><i class="far fa-heart"></i></a>
                  @endif
                  @else
                  <a href="{{route('user.login')}}" class="border-btn btn-sm"><i class="far fa-heart"></i></a>
                  <a title="Report this photo" href="{{route('user.login')}}" class="border-btn btn-sm"><i class="lab la-font-awesome-flag"></i></a>
                  @endif
                
                  @auth
                  @if ($user->id != $image->user->id)
                    <a href="javascript:void(0)" title="Report this photo" class="border-btn btn-sm" data-toggle="modal" data-target="#exampleModal"><i class="lab la-font-awesome-flag"></i></a>
                  @endif
                @endauth
                  
              </div>
               
                <div class="right py-1">
                @if ($user)
                @if ($image->user->id != $user->id)
                <a href="#0" class="border-btn btn-sm collect" id="collectOpenBtn"><i class="fas fa-folder-plus mr-2"></i> @lang('Collect')</a>
                @endif
                <button type="button" class="border-btn btn-sm" id="commentOpenBtn" title="Comments"><i class="far fa-comments"></i></button>
                
                @else
                <a href="{{route('user.login')}}" class="border-btn btn-sm"><i class="fas fa-folder-plus mr-2"></i> @lang('Collect')</a>
                <a href="{{route('user.login')}}" class="border-btn btn-sm"><i class="far fa-comments"></i></a>
                @endif

                </div>
              </div>
            </div><!-- widget end -->
            <div class="widget licence-widget">
              <h6 class="title">@lang('Item Information')</h6>
              <div class="form-group">
                <label for="radio-2" class="custom-radio list-group-item d-flex justify-content-between align-items-center">
                  <span>
                    <span class="caption">@lang('Value')</span>
                  </span>
                <span class="value">{{$image->premium==1?'Premium':'Free'}}</span>
                </label>  
              </div>
              <div class="form-group">
                <label for="radio-2" class="custom-radio list-group-item d-flex justify-content-between align-items-center">
                  <span>
                    <span class="caption">@lang('Published on')</span>
                  </span>
                <span class="value">{{showDateTime($image->created_at,'d M, Y')}}</span>
                </label>  
              </div>
              <div class="form-group">
                <label for="radio-2" class="custom-radio list-group-item d-flex justify-content-between align-items-center">
                  <span>
                    <span class="caption">@lang('Photo Type')</span>
                  </span>
                <span class="value">{{$image->extension}}</span>
                </label>  
              </div>
              <div class="form-group">
                <label for="radio-2" class="custom-radio list-group-item d-flex justify-content-between align-items-center">
                  <span>
                    <span class="caption">@lang('Original Resolution')</span>
                  </span>
                <span class="value">{{$image->resolution}}</span>
                </label>  
              </div>
              <div class="form-group">
                <label for="radio-2" class="custom-radio list-group-item d-flex justify-content-between align-items-center">
                  <span>
                    <span class="caption">@lang('Category')</span>
                  </span>
                <a href="{{route('image.category',[$image->category->id, slug($image->category->name)])}}"><span class="value">{{$image->category->name}}</span> </a>
                </label>  
              </div>
              <div class="form-group">
                <label for="radio-2" class="custom-radio list-group-item d-flex justify-content-between align-items-center">
                  <span>
                    <span class="caption">@lang('Attribution')</span>
                  </span>
                   <span class="value">{{$image->premium==1?'Not Required':'Required'}}</span> 
                </label>  
              </div>

            </div><!-- widget end --> 
          </div><!-- sidebar end -->
        </div>
      </div>
    </div>
  </div>
  <!-- photo details end -->

  <!-- photo comment sidebar start -->
  <div id="photo-comment-sidebar">
    <button type="button" id="commentCloseBtn"><i class="las la-times"></i></button>
    <h4 class="mb-3">@lang('Leave a Comment')</h4>
    <form class="comment-form">
      <div class="row">
      <input type="hidden" name="imageId" id="imageId" value="{{$image->id}}">
     
        <div class="col-lg-12 form-group">
        <textarea name="comment" id="comment-message" placeholder="{{trans('Write Comment')}}" class="form-control"></textarea>
        </div>
        <div class="col-lg-12">
          <button type="button" class="cmn-btn commentBtn">@lang('post Comment')</button>
        </div>
      </div>
    </form>
 
    <div class="comments-area" id="comments-area">
      <h3 class="title">@lang(number_format_short($image->comments->count()).' comments')</h3>
      <ul class="comments-list">
        @foreach ($image->comments as $comment)
        <li class="single-comment">
          <div class="single-comment__thumb">
           
            <img src="{{getImage('assets/user/profile/'.$comment->user->image)}}" alt="image" id="cmnt-img" width="70px" height="70px">
          </div>
          <div class="single-comment__content">
          <h6 class="name">{{$comment->user->firstname.' '.$comment->user->lastname}}
          
             @if ($comment->user->id == $image->user->id)
              <span><small>(@lang('Contributor'))</small></span>
             @endif 
           </h6> 
            <span class="date">@lang(showDateTime($comment->created_at))</span>
            <p>{{$comment->comment}}</p>
          </div>
        </li><!-- single-review end -->
        @endforeach

      </ul>
    </div><!-- comments-area end -->
  </div>
 
 @if ($user)
 <div id="collect-comment-sidebar">
  <button type="button" id="collectCloseBtn"><i class="las la-times"></i></button>
  <h4 class="mb-3">@lang('Add to Collection')</h4>
  <form class="comment-form">
    <div class="light" id="light">
      @foreach ($user->collections as $key => $collection)
      @php
          $collectionImages =  \App\CollectionImage::whereImageId($image->id)->whereCollectionId($collection->id)->first();
          
          if( !empty( $collectionImages ) ) {
              $checked = 'checked="checked"';
              } else {
                $checked = null;
              }
      @endphp
        <label>
        <input type="checkbox" name="light{{$key}}" class="checked" value="{{$collection->id}}" {{$checked}} data-img="{{$image->id}}">
        <span class="design"></span>
        <span class="text">@lang( $collection->title)</span>
        </label>
      @endforeach
    </div>
  </form>
 <hr>
 
 <div class="collect-create mt-5">
  <h4 class="mb-3">@lang('Create new Collection')</h4>
  <form class="photo-upload-form" enctype="multipart/form-data">
   <div class="form-group">
        <label>@lang('Title')</label>
    <input type="text" placeholder="Collection name" name="title" class="form-control" id="title" required="" value="" autocomplete="off">
    </div>
    <div class="form-group">
        <label>@lang('Description')</label>
    <textarea placeholder="Description" name="description" class="form-control" id="desc" required=""></textarea>
    </div>

    <div class="form-group">
     <button type="button" class="cmn-btn" id="collect-create"> @lang('Create')</button>
  </div>

 </form>
 </div>
</div>
 @endif
  <!-- photo comment sidebar end -->

  <!-- photo section start -->
  <section class="pt-60 pb-120 section--bg">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="section-header text-center">
            <h2 class="section-title">@lang('Related Photos')</h2>
          </div>
        </div>
      </div>
    </div>
    @if ($images->count() > 1)
    <div class="container-fluid">
      
      <div class="row justify-content-center photo-gallery">
        @foreach($images as $img)
            @if ($img->id != $image->id)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
              <div class="photo-card has-link">
                @if ($img->premium==1)
                <span class="photo-card__badge"><i title="Premium" data-toggle="tooltip" class="las la-crown"></i></span>
                @endif
              <a href="{{route('image.details',[$img->id,slug($img->title)])}}" class="item-link"></a>
              <img src="{{imageUrl($image->image_thumb)}}" alt="image" class="photo-card__thumb">
                <div class="photo-card__content">
                  <div class="author">
                  <img src="{{getImage('assets/user/profile/'.$img->user->image)}}" alt="image" lazy="loading">
                  <a href="#0" class="name">{{$img->user->username}}</a>
                  </div>
                  <ul class="action-area">
                    <li>
                      <i class="lar la-heart"></i> 
                      <span class="total-count">@lang(number_format_short($img->likes->count()))</span>
                    </li>
                    <li>
                      <i class="las la-download"></i>
                      <span class="total-count">@lang(number_format_short($img->downloads->count()))</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            @endif
        @endforeach

      </div>
      <div class="row mt-50">
        <div class="col-lg-12 text-center">
        <a href="{{route('image.category',[$image->category->id, slug($image->category->name)])}}" class="cmn-btn">@lang('view more')</a>
        </div>
      </div>
    </div>
    @else
    <h6 class="text-center">@lang('No related photos found!!')</h6>
    @endif

    
</section>
@auth

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header base--bg">
        <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Report This Image')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    
      <div class="modal-body">
        <div class="light">
        <form action="{{route('user.report')}}" method="POST">
          @csrf
          <input type="hidden" name="userId" value="{{$user->id}}">
          <input type="hidden" name="imageId" value="{{$image->id}}">
          <label>
            <input type="checkbox" name="report[]" class="checked" value="18+ Content">
            <span class="design"></span>
            <span class="text">@lang('18+ Content')</span>
          </label>
           
          <label>
            <input type="checkbox" name="report[]" class="checked" value="Much brutality Content">
            <span class="design"></span>
            <span class="text">@lang('Much brutality Content')</span>
          </label>
          <label>
            <input type="checkbox" name="report[]" class="checked" value="Copyright issue">
            <span class="design"></span>
            <span class="text">@lang('Copyright issue')</span>
          </label>
         
          <label>
            <input type="checkbox" name="other" class="checked" id="other"  value="Other">
            <span class="design"></span>
            <span class="text">@lang('Other')</span>
          </label>

            <textarea  class="form-control" id="textarea" cols="30" rows="5" name="otherReason"></textarea>
      
      <div class="modal-footer">
        <button type="button" class="btn border-btn" data-dismiss="modal">@lang('Close')</button>
        <button type="submit" class="btn cmn-btn">@lang('Report')</button>
      </div>
    </form>

  </div>
    </div>
  </div>
</div>
</div>
@endauth

 
  </div>                    
  
@endsection

@push('style')
    
<style>
 .level{
   
   font-size: 30px
 }
  .nav-tabs .nav-item .nav-link {
    border:none;
    border-bottom: 2px solid transparent;
  }
  .nav-tabs .nav-item .nav-link.active {
    border-color: lightgrey;
 }

  #textarea{
    display: none;
  }
  #cmnt-img{
    
    border-radius: 50%
  
  }
  .thumb img{
    border-radius: 50%
  }
    .checkbox {
        width: 100%;
        margin: 15px auto;
        position: relative;
        display: block;
    }

    .checkbox input[type="checkbox"] {
        width: auto;
        opacity: 0.00000001;
        position: absolute;
        left: 0;
        margin-left: -20px;
    }
    .checkbox label {
        position: relative;
    }
    .checkbox label:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        margin: 4px;
        width: 22px;
        height: 22px;
        transition: transform 0.28s ease;
        border-radius: 3px;
        border: 2px solid #DE4463;
    }
    .checkbox label:after {
      content: '';
        display: block;
        width: 10px;
        height: 5px;
        border-bottom: 2px solid #DE4463;
        border-left: 2px solid #DE4463;
        -webkit-transform: rotate(-45deg) scale(0);
        transform: rotate(-45deg) scale(0);
        transition: transform ease 0.25s;
        will-change: transform;
        position: absolute;
        top: 12px;
        left: 10px;
    }
    .checkbox input[type="checkbox"]:checked ~ label::before {
        color: #DE4463;
    }

    .checkbox input[type="checkbox"]:checked ~ label::after {
        -webkit-transform: rotate(-45deg) scale(1);
        transform: rotate(-45deg) scale(1);
    }

    .checkbox label {
        min-height: 29px;
        display: block;
        padding-left: 40px;
        margin-bottom: 0;
        font-weight: normal;
        cursor: pointer;
        vertical-align: sub;
    }
    .checkbox label span {
        position: absolute;
        top: 50%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
    }
    .checkbox input[type="checkbox"]:focus + label::before {
        outline: 0;
    }

    .liked{
      background-color: #DE4463;
      color: white
    }
   
    
  .checked {
    opacity: 0;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: -1;
  }
  
  .checked:checked+.design::before {
    opacity: 1;
    transform: scale(.6);
  }
  /* other states */

  .checked:hover+.design,
  .checked:focus+.design {
    border: 1px solid var(--primary);
  }
  
  .checked:hover+.design:before,
  .checked:focus+.design:before {
    background: var(--primary);
  }
  
  .checked:hover~.text {
    color: var(--primary);
  }
  
  .checked:focus+.design::after,
  .checked:active+.design::after {
    opacity: .1;
    transform: scale(2.6);
  }

    
</style>

@endpush

@push('script')

  <script>
    'use strict';
  
      $('#collectOpenBtn').on('click', function(){
          $('#collect-comment-sidebar').addClass('active');
      });


    $('#collectCloseBtn').on('click', function(){
      
        $('#collect-comment-sidebar').removeClass('active');
      
    });

  //like
  $(document).on('click','.like',function(){
          var url = '{{route("like")}}'
          var data = {
            imageId : $(this).data('img'),
          }
          axios.post(url,data, {
          headers: {
            'Content-Type': 'application/json',
            'key': '12345'
          }
      
        })
        .then(function (response) {
          $("#left").load(location.href + " #left");
          $("#like-count").load(location.href + " #like-count");
          iziToast.success({message:response.data.success, position: "topRight"})

        })
       

    })


  //store image in collection
    $(document).on('click','.checked',function(){
          var url = '{{route("collect.store")}}'
          var data = {
            collectionId : $(this).val(),
            imageId:$(this).data('img')
          }
          axios.post(url,data, {
          headers: {
            'Content-Type': 'application/json',
            'key': '12345'
          }
      
        })
        .then(function (response) {
          $("#light").load(location.href + " #light");

          iziToast.success({message:response.data.success, position: "topRight"})

        })
        .catch(function (error) {
          console.log(error);
        })
    })

  // collection create
    $(document).on('click','#collect-create',function(){

        var url = '{{route("collection.store")}}'
        var data = {
          title : $('#title').val(),
          description: $('#desc').val()
        }
        axios.post(url,data, {
        headers: {
          'Content-Type': 'application/json',
          'key': '12345'
        }
      
        })
        .then(function (response) {
          $("#light").load(location.href + " #light");
          iziToast.success({message:response.data.success, position: "topRight"})

        })
        .catch(function (error) {
          console.log(error);
        })
    })

    //follow 
    $(document).on('click','.follow',function(){
        var url = '{{route("follow")}}'
        var data = {
          follower : $(this).data('follower'),
          followed: $(this).data('followed')
        }
        axios.post(url,data, {
        headers: {
          'Content-Type': 'application/json',
          'key': '12345'
        }
    
      })
      
      .then(function (response) {
        $("#follow-box").load(location.href + " #follow-box");
        $("#count").load(location.href + " #count");
        iziToast.success({message:response.data.success, position: "topRight"})

      })
      .catch(function (error) {
        console.log(error);
      })

    })


  //comment
  $(document).on('click','.commentBtn',function(){

      var url = '{{route("comment")}}'
      var data = {
        comment : $('#comment-message').val(),
        image_id: $('#imageId').val()
      }
      axios.post(url,data, {
      headers: {
        'Content-Type': 'application/json',
        'key': '12345'
      }

      })
      .then(function (response) {
      $("#comments-area").load(location.href + " #comments-area");
      $('#comment-message').val('');
      iziToast.success({message:response.data.success, position: "topRight"})

      })
      .catch(function (error) {
      console.log(error);
      })
  })


    $(document).on('change','#other',function(){
    
        if ($(this).is(":checked"))
        {
          $('#textarea').slideDown().css('display','block')
        } else{
          $('#textarea').css('display','none')
    
        }    
  })


  </script>

@endpush


@push('additionalSeo')
  @includeif($activeTemplate.'partials.additionalSeo',['image'=>$image])
@endpush