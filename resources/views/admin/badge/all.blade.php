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
                                <th scope="col">@lang('Icon')</th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Download Milestone')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($badges as $badge)
                            <tr>
                              <td data-label="@lang('Icon')">
                                <div class="user">
                                    <div class="thumb"><img src="
                                        {{getImage('assets/images/badge/'.$badge->icon,'100x100')}}" alt="image">
                                    </div>
                                   
                                </div>
                            </td>
                            <td data-label="@lang('Name')">{{$badge->name}}</td>
                            <td data-label="@lang('Download Milestone')"><span class="badge badge-pill bg--warning">{{$badge->download_milestone}}</span></td>
                            <td data-label="@lang('Status')">
                                @if ($badge->status == 1)
                                <span class="text--small badge font-weight-normal badge--success">@lang('active')</span>
                                @else
                                <span class="text--small badge font-weight-normal badge--warning">@lang('inactive')</span>
                                @endif
                             </td>
                            
                                <td data-label="@lang('Action')">
                                  <a href="javascript:void(0)" class="icon-btn btn--primary edit mr-2" data-toggle="modal" data-target="#editModal"
                                  data-icon="{{getImage('assets/images/badge/'.$badge->icon,'150x150')}}"
                                  data-id="{{$badge->id}}"
                                  data-name="{{$badge->name}}"
                                  data-status="{{$badge->status}}"
                                  data-milestone="{{$badge->download_milestone}}"
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
            </div><!-- card end -->
        </div>
    </div>

 
    
    <!--add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg--primary">
            <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Create Badge')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <form action="{{route('admin.badge.create')}}" method="POST" enctype="multipart/form-data">
            @csrf 
            <div class="form-group">
              <label for="exampleInputEmail1">@lang('Icon')</label>
              <div class="image-upload">
                  <div class="thumb">
                      <div class="avatar-preview">
                          <div class="profilePicPreview" style="background-image: url({{getImage('assets/','150x150')}})">
                              <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                          </div>
                      </div>
                      <div class="avatar-edit">
                          <input type="file" class="profilePicUpload " name="icon" id="profilePicUpload1" accept=".png">
                          <label for="profilePicUpload1" class="bg--primary">@lang('Upload Image')</label>
                          <small class="mt-2 text-facebook">@lang('Supported files:') <b>@lang('jpeg, jpg.')</b> @lang('Icon will be resized into 150x150') </small>
                      </div>
                  </div>
              </div>
          </div>
              <div class="form-group">
                <label>@lang('Name')</label>
                <input type="text" name="name" class="form-control" >
              </div>
              <div class="form-group">
                <label>@lang('Download Mile Stone')</label>
                <input type="number" name="milestone" class="form-control">
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
            <button type="submit" class="btn btn--primary">@lang('Create')</button>
          </div>
        </form>
        </div>
      </div>
    </div>
    

    <!--edit--->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg--primary">
            <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Edit Badge')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <form action="{{route('admin.badge.update')}}" method="POST" enctype="multipart/form-data">
            @csrf 
            <input type="hidden" name="id" value="">
            <div class="form-group">
              <label for="exampleInputEmail1">@lang('Icon')</label>
              <div class="image-upload">
                  <div class="thumb">
                      <div class="avatar-preview">
                          <div class="profilePicPreview">
                              <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                          </div>
                      </div>
                      <div class="avatar-edit">
                          <input type="file" class="profilePicUpload " name="icon" id="profilePicUpload2" accept=".png">
                          <label for="profilePicUpload2" class="bg--primary">@lang('Upload Image')</label>
                          <small class="mt-2 text-facebook">@lang('Supported files:') <b>@lang('jpeg, jpg.')</b> @lang('Icon will be resized into 150x150') </small>
                      </div>
                  </div>
              </div>
            </div>
              <div class="form-group">
                <label>@lang('Name')</label>
                <input type="text" name="name" class="form-control" >
              </div>
              <div class="form-group">
                <label>@lang('Download Mile Stone')</label>
                <input type="number" name="milestone" class="form-control">
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
            <button type="submit" class="btn btn--primary">@lang('Update')</button>
          </div>
        </form>
        </div>
      </div>
    </div>
@endsection



@push('breadcrumb-plugins')
<button type="button" class="btn btn--primary" data-toggle="modal" data-target="#addModal">
  <i class="las la-plus"></i> @lang(' Create New')
</button>
@endpush

@push('script')
    
<script>
  'use strict'
   $('.edit').on('click',function(){
     var modal = $('#editModal');
     var icon = $(this).data('icon')
     var name = $(this).data('name')
     var status = $(this).data('status')
     var milestone = $(this).data('milestone')
     var id = $(this).data('id')
     var img = 'url('+icon+')'
     modal.find(".profilePicPreview").css('background-image',img)
     modal.find("input[name=name]").val(name)
     modal.find("input[name=milestone]").val(milestone)
     modal.find("input[name=id]").val(id)
     if(status == 1){
          modal.find('input[name=status]').attr('checked',true)
     }
 
   }) 


</script>

@endpush

