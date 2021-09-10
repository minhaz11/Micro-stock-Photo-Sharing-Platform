@extends('reviewer.layouts.app')

@section('panel')

    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Created by')</th>
                                <th scope="col">@lang('Reason')</th>
                                <th scope="col">@lang('Created at')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($reasons as $reason)
                            <tr>
                                @if ($reason->reviewer)
                               <td data-label="@lang('Created by')">{{$reason->reviewer->username}}</td>
                                @else
                                <td data-label="@lang('Reason')">
                                   @lang('admin')
                                </td>
                                @endif
                           
                                <td data-label="@lang('Reason')">{{$reason->reason}}</td>
                               <td data-label="@lang('Created at')">{{showDateTime($reason->created_at,'d, M, Y')}}</td>
                                <td data-label="@lang('Action')">
                                <a href="javascript:void(0)" class="icon-btn mr-2 edit"  title="Edit"  data-toggle="modal" data-target="#editModal" data-route="{{route('reviewer.predefined.reasons.update',$reason->id)}}" data-reason ="{{$reason->reason}}">
                                        <i class="las la-edit text--shadow"></i>
                                    </a>
                                    <a href="javascript:void(0)" data-route="{{route('reviewer.predefined.reasons.remove',$reason->id)}}" class="icon-btn btn--danger delete" data-toggle="tooltip" title="" data-original-title="Details">
                                        <i class="las la-trash text--shadow"></i>
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

{{-- edit modal --}}

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">@lang('Create a reason')</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form action="" method="POST">
           @csrf
            <div class="form-group">
              <label>@lang('Reason')</label>
            <input type="text" class="form-control" name="reason"   placeholder="{{trans('Write down your reason')}}">
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


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">@lang('Create a reason')</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <form action="{{route('reviewer.predefined.reasons.add')}}" method="POST">
               @csrf
                <div class="form-group">
                  <label>@lang('Reason')</label>
                <input type="text" class="form-control" name="reason"  placeholder="{{trans('Write down your reason')}}">
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
@endsection



@push('breadcrumb-plugins')
    <button type="button" class="btn btn--primary" data-toggle="modal" data-target="#exampleModal">
     <i class="las la-plus"></i> @lang('Create')
    </button>
     
@endpush

@push('script')
    <script>
        'use strict';
        $(document).on('click','.edit',function(){
            var reason = $(this).data('reason')
            var route = $(this).data('route')
            $('#editModal').find('form').attr('action',route);
            $('#editModal').find('input[name=reason]').val(reason);
        })

        $('.delete').on('click',function(){
          var route = $(this).data('route')
          var modal = $('#deleteModal');
          modal.find('form').attr('action',route)
          modal.modal('show');
         })
    </script>

@endpush