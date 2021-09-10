@extends($activeTemplate.'layouts.user.master')

@section('content')
        <div class="row mt-10">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                            <div class="mr-auto p-2 mt-3"><h6>{{ trans($page_title) }}</h6></div>
                            <div class="p-2">
                            <a href="{{route('user.collection.all')}}"  class="cmn-btn btn-large mr-2" >
                                    <i class="fas fa-backward mr-2"></i>@lang('Back')
                                </a>
                                <button type="button"  class="cmn-btn btn-large mr-2" data-toggle="modal" data-target="#exampleModal">
                                  <i class="fas fa-pen mr-2"></i>@lang('Update')
                                </button>

                                <a href="javascript:void(0)" data-route="{{route('user.collection.remove',$collection->id)}}"  class="cmn-btn btn-large mr-2 delete" >
                                    <i class="fas fa-trash mr-2"></i>@lang('Delete this collection')
                                </a>
                               
                            </div>
                          
                      </div>
                </div>
                <div class="card-body p-0">
                    <section class="pt-60 pb-120 ">
                        <div class="container">
                          <div class="row justify-content-center">
                            <div class="col-lg-8">
                              <div class="section-header text-center">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="container" id="photo-gallery">
                          <div class="row justify-content-center photo-gallery" >
                           @forelse ($collection->images as $item)
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6" >
                             <div class="photo-card has-link">
                             <button title="@lang('Remove from collection')" class="photo-card__close" data-imageId="{{$item->id}}" data-collId="{{$collection->id}}"><i class="las la-times"></i></button>
                             <a href="{{route('image.details',[$item->id,slug($item->title)])}}" target="_blank" class="item-link"></a>
                             <img src="{{imageUrl($image->image_thumb)}}" alt="image" class="photo-card__thumb">
                               <div class="photo-card__content">
                                 <div class="author">
                                   
                                   <a href="{{route('user.collection.images',$item->id)}}" class="name">{{__($item->title)}}</a>
                                 </div>
                                 <ul class="action-area">
                                   <li>
                                     <i class="lar la-heart"></i> 
                                   <span class="total-count">{{number_format_short($item->likes->count())}}</span>
                                   </li>
                                   <li>
                                     <i class="las la-download"></i>
                                   <span class="total-count">{{number_format_short($item->downloads->count())}}</span>
                                   </li>
                                 </ul>
                               </div>
                             </div><!-- photo-card css start -->
                           </div>
                               
                           @empty
                           <div class="row mt-50">
                            <div class="col-lg-12 text-center">
                            <p>{{$empty_message}}</p>
                            </div>
                          </div>
                           @endforelse
                            
                           
                          </div>
                        
                        </div>
                      </section>
                </div>
                <div class="card-footer py-1">
                    
                </div>
              </div><!-- card end -->
            </div>
          </div>
    
          <!-- Button trigger modal -->
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
                    <form class="photo-upload-form" method="POST" action="{{route('user.collection.update',$collection->id)}}" enctype="multipart/form-data">
                        @csrf  
                        <div class="form-group">
                         
                            <div class="image-upload">
                              <div class="thumb">
                                  <div class="avatar-preview">
                                      <div class="profilePicPreview" style="background-image: url({{ getImage('assets/collection/cover/'. $collection->image,'350x350') }})">
                                          <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                      </div>
                                  </div>
                                  <div class="avatar-edit">
                                      <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                      <label for="profilePicUpload1" class="cmn-btn  d-flex justify-content-center">@lang('Collection cover')</label>
                                      <small class="mt-2 text-facebook">@lang('Supported files:') <b>@lang('jpeg, jpg.')</b> @lang('Image will be resized into 400x400px') </small>
                                  </div>
                              </div>
                          </div>
                         
                        </div>
                        <div class="form-group">
                          <label>@lang('Title')</label>
                      <input type="text" placeholder="{{trans('Collection name')}}" name="title" class="form-control" required value="{{$collection->title}}">
                      </div>
                        <div class="form-group">
                            <label>@lang('Description')</label>
                        <textarea  placeholder="{{trans('Description')}}" name="description" class="form-control" required>{{$collection->description}}</textarea>
                        </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="border-btn" data-dismiss="modal">@lang('Close')</button>
                  <button type="submit" class="cmn-btn">@lang('Update')</button>
                </div>
            </form>
              </div>
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
                            
                            <i class="las la-exclamation-circle text-danger display-2 mb-15"></i>
                            <h4 class="text--secondary mb-15">@lang('Are You Sure Want to Delete This?')</h4>
    
                        </div>
                    <div class="modal-footer justify-content-center">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('close')</button>
                      <button type="submit"  class="btn btn-danger del">@lang('Delete')</button>
                    </div>
                    
                    </form>
              </div>
            </div>
        </div>
        
@stop

@push('script')
    
<script>
  'use strict'

$(document).on('click','.photo-card__close',function(){
   var url = '{{route("user.collection.image.remove")}}'
          var data = {
            imageId : $(this).data('imageid'),
            collId : $(this).data('collid'),
          }
          axios.post(url,data, {
          headers: {
            'Content-Type': 'application/json',
          }
      
        })
        .then(function (response) {
          $("#photo-gallery").load(location.href + " #photo-gallery");
          iziToast.success({message:response.data.success, position: "topRight"})

        })
        .catch(function (error) {
          console.log(error);
        })
})


  $('.delete').on('click',function(){
        var route = $(this).data('route')
        var modal = $('#deleteModal');
        modal.find('form').attr('action',route)
        modal.modal('show');
    })

   function proPicURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var preview = $(input).parents('.thumb').find('.profilePicPreview');
            $(preview).css('background-image', 'url(' + e.target.result + ')');
            $(preview).addClass('has-image');
            $(preview).hide();
            $(preview).fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(".profilePicUpload").on('change', function () {
    proPicURL(this);
});

$(".remove-image").on('click', function () {
    $(this).parents(".profilePicPreview").css('background-image', 'none');
    $(this).parents(".profilePicPreview").removeClass('has-image');
    $(this).parents(".thumb").find('input[type=file]').val('');
});

$("form").on("change", ".file-upload-field", function(){ 
  $(this).parent(".file-upload-wrapper").attr("data-text",         $(this).val().replace(/.*(\/|\\)/, '') );
});
</script>

@endpush