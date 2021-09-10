@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Image')</th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($categories as $category)

                            <tr>
                                <td data-label="@lang('Image')">
                                    <div class="user">
                                        <div class="thumb"><img src="
                                            {{getImage('assets/images/category/'.$category->image,'100x100')}}" alt="image">
                                        </div>

                                    </div>
                                </td>

                               <td data-label="@lang('Name')" class="font-weight-bold">{{$category->name}}</td>

                            <td data-label="@lang('Status')" class=""><span class="badge {{$category->status==1?'badge--success':'badge--warning'}}">{{$category->status==1? trans('active'):trans('deactive')}}</span></td>

                                <td data-label="@lang('Action')">
                                    <a href="javascript:void(0)" class="icon-btn edit" data-toggle="tooltip" title="" data-original-title="@lang('Edit')"
                                    data-name ="{{$category->name}}"
                                    data-image ="{{getImage('assets/images/category/'.$category->image,'250x250')}}"
                                    data-status ="{{$category->status}}"
                                    data-route = "{{route('admin.category.update',$category->id)}}"
                                    >
                                        <i class="las la-edit text--shadow"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ $empty_message }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                 {{$categories->links('admin.partials.paginate')}}
                </div>
            </div><!-- card end -->
        </div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg--primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Add New Category')</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{route('admin.category.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
          <div class="form-group">
            <label for="exampleInputEmail1">@lang('Category Name')</label>
            <input type="text" class="form-control" name="name"  placeholder="Enter category name">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">@lang('Image')</label>
            <div class="image-upload">
                <div class="thumb">
                    <div class="avatar-preview">
                        <div class="profilePicPreview" style="background-image: url({{getImage('','250x250')}})">
                            <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="avatar-edit">
                        <input type="file" class="profilePicUpload " name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                        <label for="profilePicUpload1" class="bg--primary">@lang('Upload Image')</label>
                        <small class="mt-2 text-facebook">@lang('Supported files:') <b>@lang('jpeg, jpg.')</b> @lang('Image will be resized into 200x250') </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
            @lang('Status'):
                <label class="switch">
                    <input type="checkbox" name="status" id="checkbox">
                    <div class="slider round"></div>
                </label>
          </li>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
        <button type="submit" class="btn btn--primary">@lang('Save')</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!--edit modal-->

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg--primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Edit Category')</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST" enctype="multipart/form-data" id="edit">
            @csrf
          <div class="form-group">
            <label for="exampleInputEmail1">@lang('Category Name')</label>
            <input type="text" class="form-control" name="name" id="name"  placeholder="Enter category name">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">@lang('Image')</label>
            <div class="image-upload">
                <div class="thumb">
                    <div class="avatar-preview">
                        <div class="profilePicPreview" id="image">
                            <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="avatar-edit">
                        <input type="file" class="profilePicUpload " name="image" id="a" accept=".png, .jpg, .jpeg">
                        <label for="a" class="bg--primary">@lang('Upload Image')</label>
                        <small class="mt-2 text-facebook">@lang('Supported files:') <b>@lang('jpeg, jpg.')</b> @lang('Image will be resized into 200x250') </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
             @lang('Status'):
                <label class="switch">
                    <input type="checkbox" name="status" id="status" id="checkbox">
                    <div class="slider round"></div>
                </label>
          </li>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
        <button type="submit" class="btn btn--primary">@lang('Save')</button>
      </div>
    </form>
    </div>
  </div>
</div>

    </div>
@endsection



@push('breadcrumb-plugins')

<button type="button" class="btn btn--primary mr-5" data-toggle="modal" data-target="#addModal">
<i class="fas fa-plus"></i>   @lang(' Add new Category')
  </button>

<form action="{{route('admin.category.search')}}" method="GET" class="form-inline float-sm-right bg--white ">
    <div class="input-group has_append">
        <input type="text" name="search" class="form-control" placeholder="@lang('Search by name')" value="" autocomplete="off">
        <div class="input-group-append">
            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>
@endpush

@push('script')
 <script>
        'use strict';
        (function ($) {
            var modal = $('#editModal');
            $('.edit').on('click',function(){
            $('#name').val($(this).data('name'));
            var image = $(this).data('image');
            $('#image').css("background-image","url(" + image + ")");
            var status = $(this).data('status');
            if(status == 1){
              $("#status").attr("checked", "checked");
            }
            var route = $(this).data('route');
            $('#edit').attr('action',route);

            modal.modal('show');
         });
        })(jQuery);
 </script>

@endpush
