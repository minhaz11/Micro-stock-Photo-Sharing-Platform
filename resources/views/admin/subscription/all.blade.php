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
                <th scope="col">@lang('Price')</th>
                <th scope="col">@lang('Daily Download Limit')</th>
                <th scope="col">@lang('Monthly Download Limit')</th>
                <th scope="col">@lang('Yearly Download Limit')</th>
                <th scope="col">@lang('Facilities')</th>
                <th scope="col">@lang('Status')</th>
                <th scope="col">@lang('Action')</th>
              </tr>
            </thead>
            <tbody>
              @forelse($subscriptions as $subscription)
              <tr>
                <td data-label="@lang('Icon')">
                <i class="{{$subscription->icon}}"></i>
               </td>
                <td data-label="@lang('Name')"><span class="text--small badge font-weight-normal badge--success">{{$subscription->name}}</span>
                </td>
                <td data-label="@lang('Price')"><span class="badge badge-pill bg--warning">{{$general->cur_sym}} {{getAmount($subscription->price,2)}}</span></td>
               
                <td data-label="@lang('Daily Download Limit')">{{$subscription->daily_limit}}</td>
                <td data-label="@lang('Monthly Download Limit')">{{$subscription->monthly_limit}}</td>
                <td data-label="@lang('Yearly Download Limit')">{{$subscription->yearly_limit}}</td>
                <td data-label="@lang('Facilities')"><button class="icon-btn facility" data-toggle="modal" data-target="#facility"  data-facility = "{{json_encode($subscription->facilities)}}"><i class="las la-eye text--shadow"></i></button></td>
                <td data-label="@lang('Status')">
                  @if ($subscription->status == 1)
                  <span class="text--small badge font-weight-normal badge--success">@lang('active')</span>
                  @else
                  <span class="text--small badge font-weight-normal badge--warning">@lang('inactive')</span>
                  @endif
                </td>
                <td data-label="@lang('Action')">
              
                  <a href="javascript:void(0)" class="icon-btn edit"  data-toggle="modal" data-target="#editModal"
                    data-id="{{$subscription->id}}"
                    data-name="{{$subscription->name}}"
                    data-icon = "{{$subscription->icon}}"
                    data-price = "{{getAmount($subscription->price,2)}}"
                    data-dl = "{{$subscription->daily_limit}}"
                    data-ml = "{{$subscription->monthly_limit}}"
                    data-yl = "{{$subscription->yearly_limit}}"
                    data-status = "{{$subscription->status}}"
                    data-facility = "{{json_encode($subscription->facilities)}}"
                  >
                    <i class="las la-edit text--shadow"></i>
                  </a>

                </td>
              </tr>
              @empty
              <tr>
                <td class="text-muted text-center" colspan="100%">@lang('No subscription plan')</td>
              </tr>
              @endforelse

            </tbody>
          </table><!-- table end -->
        </div>
      </div>
     
    </div><!-- card end -->
  </div>
<!-- add modal-->
  <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg--primary">
          <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Create Subscriptions')</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('admin.subscription.create')}}" method="POST">
          @csrf
          <div class="modal-body">

            <div class="form-group">
              <label class="font-weight-bold">@lang('Icon') </label>
              <div class="input-group has_append">
                <input type="text" class="form-control icon-name" name="icon" placeholder="@lang('Icon')">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary iconPicker" data-icon="fas fa-home"
                    role="iconpicker"></button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="font-weight-bold" for="exampleInputEmail1">@lang('Name')</label>
              <input type="text" class="form-control" name="name" placeholder="Enter name">

            </div>
            <div class="form-group">
              <label class="font-weight-bold" for="exampleInputEmail1">@lang('Price')</label>
              <input type="number" class="form-control" name="price" placeholder="Enter price">

            </div>
            <div class="form-group">
              <label class="font-weight-bold" for="exampleInputPassword1">@lang('Daily Download Limit')</label>
              <input type="number" class="form-control" name="daily_limit" placeholder="@lang('Daily Download Limit')">
            </div>
              <div class="form-group">
                <label class="font-weight-bold" for="exampleInputnumber1">@lang('Monthly Download Limit')</label>
                <input type="number" class="form-control" name="monthly_limit" placeholder="@lang('Monthly Download Limit')">
               </div>
                <div class="form-group">
                  <label class="font-weight-bold" for="exampleInputnumber1">@lang('Yearly Download Limit')</label>
                  <input type="number" class="form-control" name="yearly_limit" placeholder="@lang('Yearly Download Limit')">
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

                <label class="font-weight-bold" for="exampleInputnumber1">@lang('Facilities')</label>
                <div class="form-group d-flex justify-content-between">
                  <input type="text" class="form-control mr-1" name="facilities[]" placeholder="">

                </div>
                <div class="append">

                </div>
                <button type="button" class="btn btn-success mt-2 w-100" id="add">@lang('Add more fields')</button>

              </div>


           
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
              <button type="type" class="btn btn--primary">@lang('Create')</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- edit modal-->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg--primary">
          <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Edit Subscriptions')</h5>
          <button type="button" class="close cl" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('admin.subscription.update')}}" method="POST">
          @csrf
          <div class="modal-body">
            <input type="hidden" name="id" value="">
            <div class="form-group">
              <label class="font-weight-bold">@lang('Icon')</label>
              <div class="input-group has_append">
                <input type="text" class="form-control icon-name" name="icon" placeholder="@lang('Icon')">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary iconPicker" data-icon="fas fa-home"
                    role="iconpicker"></button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="font-weight-bold" for="exampleInputEmail1">@lang('Name')</label>
              <input type="text" class="form-control" name="name" placeholder="Enter name">

            </div>
            <div class="form-group">
              <label class="font-weight-bold" for="exampleInputEmail1">@lang('Price')</label>
              <input type="number" class="form-control" name="price" placeholder="Enter price">

            </div>
            <div class="form-group">
              <label class="font-weight-bold" for="exampleInputPassword1">@lang('Daily Download Limit')</label>
              <input type="number" class="form-control" name="daily_limit" placeholder="@lang('Daily Download Limit')">
            </div>
              <div class="form-group">
                <label class="font-weight-bold" for="exampleInputnumber1">@lang('Monthly Download Limit')</label>
                <input type="number" class="form-control" name="monthly_limit" placeholder="@lang('Monthly Download Limit')">
              </div>
                <div class="form-group">
                  <label class="font-weight-bold" for="exampleInputnumber1">@lang('Yearly Download Limit')</label>
                  <input type="number" class="form-control" name="yearly_limit" placeholder="@lang('Yearly Download Limit')">
                </div>

                <div class="form-group">
                  
                  <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                  @lang('Status'):
                      <label class="switch">
                          <input type="checkbox" name="status">
                          <div class="slider round"></div>
                      </label>
                   </li>    
                  </div>

                <label class="font-weight-bold" for="exampleInputnumber1">@lang('Facilities')</label>
                
                <div class="append">

                </div>
                <button type="button" class="btn btn-success mt-2 w-100" id="add">@lang('Add more fields')</button>

              </div>


            <div class="modal-footer">
              <button type="button" class="btn btn-secondary cl" data-dismiss="modal">@lang('Close')</button>
              <button type="type" class="btn btn--primary">@lang('Update')</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>

 <!-- facilities modal-->

 <div class="modal fade" id="facility" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg--primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Facilities')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="list-group" id="list"></div>
       </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      </div>     

    </div>
  </div>
</div>
  @endsection



  @push('breadcrumb-plugins')
  <button type="button" class="btn btn--primary" data-toggle="modal" data-target="#addModal">
    <i class="las la-plus text--shadow"></i> @lang('Create New')
  </button>
  @endpush

  @push('script')

  <script>
    'use strict';

    (function ($) {

      $(document).on('click', '#add', function () {
        
        var element = `
            <div class="form-group d-flex justify-content-between">
                <input type="text" class="form-control mr-1" name="facilities[]" placeholder="">
                <button type="button" class="icon-btn btn--danger  text-center text-nowrap remove"><i class="las la-minus-circle"></i></button>
            </div>`;

        $('.append').append(element);
      })

      $(document).on('click', '.remove', function () {
        $(this).parent('.form-group').remove()
      })

      $('#exampleModal').on('shown.bs.modal', function (e) {
        $(document).off('focusin.modal');
      });
      $('#editModal').on('shown.bs.modal', function (e) {
        $(document).off('focusin.modal');
      });


    

      $(document).on('click','.cl',function(){
        $('.append').empty()
      })


      $(document).on('click','.edit',function(){
        $('.append').empty()
        var id = $(this).data('id')
        var name = $(this).data('name')
        var icon = $(this).data('icon')
        var price = $(this).data('price')
        var dl = $(this).data('dl')
        var ml = $(this).data('ml')
        var yl = $(this).data('yl')
        var status = $(this).data('status')
        var facilities = $(this).data('facility')
        var modal = $('#editModal');

        modal.find('input[name=id]').val(id)
        modal.find('input[name=icon]').val(icon)
        modal.find('input[name=name]').val(name)
        modal.find('input[name=price]').val(price)
        modal.find('input[name=daily_limit]').val(dl)
        modal.find('input[name=monthly_limit]').val(ml)
        modal.find('input[name=yearly_limit]').val(yl)
        if(status == 1){
          
         modal.find('input[name=status]').attr('checked',true)
        }

        
        var element = `
            <div class="form-group d-flex justify-content-between">
                <input type="text" class="form-control mr-1" name="facilities[]" placeholder="">
                <button type="button" class="icon-btn btn--danger  text-center text-nowrap remove"><i class="las la-minus-circle"></i></button>
            </div>`;

        $.each(facilities,function(key,item){
          $('.append').append(

            `
            <div class="form-group d-flex justify-content-between">
                <input type="text" class="form-control mr-1" name="facilities[]" value="${item}" placeholder="">
                <button type="button" class="icon-btn btn--danger  text-center text-nowrap remove"><i class="las la-minus-circle"></i></button>
            </div>`

          );
        }) 

      })

    $(document).on('click','.facility',function(){
      $('#list').empty()
      var facility = $(this).data('facility')
     
      var output = ``;
      
      $.each(facility,function(key,item){
          ++key
           output +=`<li class="list-group-item">${key}. ${item}</li>` 
      })
     
      $('#list').html(output);
     
      
  })

  $('.iconPicker').iconpicker({
        align: 'center', // Only in div tag
        arrowClass: 'btn-danger',
        arrowPrevIconClass: 'fas fa-angle-left',
        arrowNextIconClass: 'fas fa-angle-right',
        cols: 10,
        footer: true,
        header: true,
        icon: 'fas fa-bomb',
        iconset: 'fontawesome5',
        labelHeader: '{0} of {1} pages',
        labelFooter: '{0} - {1} of {2} icons',
        placement: 'bottom', // Only in button tag
        rows: 5,
        search: false,
        searchText: 'Search icon',
        selectedClass: 'btn--success',
        unselectedClass: '',
        selected: false,
        defaultValue: false,
      }).on('change', function (e) {
        $(this).parent().siblings('.icon-name').val(e.icon);
      });

})(jQuery)

  </script>
  @endpush

  @push('script-lib')
  <script src="{{ asset('assets/admin/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
  @endpush
  @push('style-lib')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-iconpicker.min.css') }}">
  @endpush