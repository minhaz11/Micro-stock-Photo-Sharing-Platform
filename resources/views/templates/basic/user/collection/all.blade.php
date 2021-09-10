@extends($activeTemplate.'layouts.user.master')

@section('content')
        <div class="row mt-10">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header p-1 p-sm-2">
                    <div class="d-flex flex-wrap">
                        <div class="mr-auto p-2 mt-3"><h6>{{ trans($page_title) }}</h6></div>
                          <div class="p-2">
                            <button type="button"  class="cmn-btn btn-large" data-toggle="modal" data-target="#exampleModal">
                                <i class="fas fa-folder-plus mr-2"></i>@lang('Create')
                              </button>
                          </div>
                        <div class="p-2">                             
                          <form class="subscribe-form search__field" action="{{route('user.collection.search')}}" method="GET">
                                @csrf
                                <input type="text" name="search" id="subscribe-field" placeholder="{{trans('Search by collection title')}}" class="form-control" autocomplete="off"  value="{{$value??''}}">
                                <button type="submit" class="subscribe-btn"><i class="las la-search"></i></button>
                          </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <section class="pt-60 pb-60">
                        <div class="container">
                          <div class="row justify-content-center photo-gallery">
                           @forelse ($collections as $item)
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                             <div class="photo-card has-link active">
                             <a href="{{route('user.collection.images',$item->id)}}" class="item-link"></a>
                             <img src="{{getImage('assets/collection/cover/'.$item->image,'150x150')}}" alt="image" class="photo-card__thumb">
                               <div class="photo-card__content">
                                 <div class="author">
                                   
                                   <a href="{{route('user.collection.images',$item->id)}}" class="name">{{__($item->title)}}</a>
                                 </div>
                                
                               </div>
                             </div><!-- photo-card css start -->
                           </div>
                           @empty
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mt-5">
                             <h4>@lang('No Collections')</h4>
                           </div>
                           @endforelse
                          </div>
                          @if (!$collections->isEmpty())
                          <div class="row">
                            <div class="col-lg-12 text-center">
                             {{$collections->links()}}
                            </div>
                          </div>
                          @endif
                        </div>
                      </section>
                </div>
               
              </div><!-- card end -->
            </div>
          </div>
    
          <!-- Button trigger modal -->
          
          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header base--bg">
                  <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Create New Collection')</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <form class="photo-upload-form" method="POST" action="{{route('collection.store')}}" enctype="multipart/form-data">
                        @csrf  
                        <div class="form-group">
                          <div class="photo-upload-area">
                            <div class="file-upload">
                              <div class="image-upload-wrap">
                                <input class="file-upload-input" name="photo" type='file' onchange="readURL(this);" accept="image/*"  required/>
                                <div class="drag-text">
                                  <p class="title">@lang('Drag and drop a file or select add Image')</p>
                                </div>
                              </div>
                              <div class="file-upload-content">
                                <img class="file-upload-image" src="#" alt="your image" />
                                <div class="image-title-wrap mt-4">
                                  <button type="button" onclick="removeUpload()" class="remove-image">@lang('Remove') </button>
                                </div>
                              </div>
                              <button class="border-btn w-100 mt-3" type="button" onclick="$('.file-upload-input').trigger( 'click' )">@lang('Add collection Cover')</button>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label>@lang('Title')</label>
                      <input type="text" placeholder="{{trans('Collection name')}}" name="title" class="form-control" required value="{{old('title')}}">
                      </div>
                        <div class="form-group">
                            <label>@lang('Description')</label>
                        <textarea  placeholder="{{trans('Description')}}" name="description" class="form-control" required>{{old('description')}}</textarea>
                        </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="border-btn" data-dismiss="modal">@lang('Close')</button>
                  <button type="submit" class="cmn-btn">@lang('Create')</button>
                </div>
            </form>
              </div>
            </div>
        </div>
        
@stop

@push('script')
<script>
  // image upload js
  'use strict'
  function readURL(input) {
    if (input.files && input.files[0]) {

      var reader = new FileReader();

      reader.onload = function(e) {
        $('.image-upload-wrap').hide();

        $('.file-upload-image').attr('src', e.target.result);
        $('.file-upload-content').show();

        $('.image-title').html(input.files[0].name);
      };

      reader.readAsDataURL(input.files[0]);

    } else {
      removeUpload();
    }
  }

  function removeUpload() {
    $('.file-upload-input').replaceWith($('.file-upload-input').clone());
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
  }
  $('.image-upload-wrap').bind('dragover', function () {
      $('.image-upload-wrap').addClass('image-dropping');
    });
    $('.image-upload-wrap').bind('dragleave', function () {
      $('.image-upload-wrap').removeClass('image-dropping');
  });

  // select 2 
  $(".select2-auto-tokenize").select2({
    tags: true,
    tokenSeparators: [',']
  });

</script>
@endpush
@push('style')
  
<style>
.photo-upload-area{
    padding: 1px 14px;
}
.photo-card .author .name {
    font-size: 18px;
    color: #ffffff;
    margin-left: 5px;
}
.photo-card.active::before {
    opacity: 0.45;
}
</style>

@endpush