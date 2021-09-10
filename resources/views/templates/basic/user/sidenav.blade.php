
<div class="col-lg-3 col-xxl-2">
    <div class="author-widget">
      <div class="close__sidenav d-lg-none">
        <i class="las la-times"></i>
      </div>
      <div class="author-widget__wave">

      </div>
      <div class="author-widget__thumb">
        <img src="{{getImage('assets/user/profile/'.auth()->user()->image,'400x400')}}" alt="image">
        @if (auth()->user()->myBadge)
        <span class="author-badge">
          <img src="{{getImage('assets/images/badge/'.auth()->user()->myBadge->badge->icon)}}" data-toggle="tooltip" data-placement="bottom" title="{{auth()->user()->myBadge->badge->name.' author'}}">
        </span>
        @endif
      </div>
      <ul class="author-widget__list">
      <li><a class="{{activeMenu('user.home',2)}}" href="{{route('user.home')}}"><i class="las la-th-large"></i> @lang('Dashboard')</a></li>

      <li class="author-widget-menu-has-child {{activeMenu(['user.deposit','user.deposit.confirm','user.deposit.preview','user.deposit.history'],1)}}">
        <a class="{{activeMenu(['user.deposit','user.deposit.confirm','user.deposit.preview','user.deposit.history'],2)}}" href="javascript:void(0)"><i class="las la-wallet"></i> @lang('Deposits')</a>
          <ul class="author-widget-submenu">
            <li><a href="{{route('user.deposit')}}" class="{{activeMenu('user.deposit',2)}}"><i class="menu-icon las la-dot-circle"></i>@lang('Deposit methods')</a></li>
            <li><a href="{{route('user.deposit.history')}}" class="{{activeMenu('user.deposit.history',2)}}"><i class="menu-icon las la-dot-circle"></i> @lang('Logs')</a></li>
           
          </ul>
        </li>
      
        <li><a href="{{route('user.subscriptions')}}" class="{{activeMenu('user.subscriptions',2)}}"><i class="lab la-telegram"></i> @lang('Price Plans')</a></li>
    
      <li><a class="{{activeMenu(['user.collection.all','user.collection.images'],2)}}" href="{{route('user.collection.all')}}"><i class="fas fa-folder-plus mr-2"></i> @lang('Collections')</a></li>
     
    
     @if (auth()->user()->con_flag == 1)
      <li class="author-widget-menu-has-child {{activeMenu(['contributor.image.search','contributor.image.all','contributor.image.approved','contributor.image.rejected','contributor.image.banned','contributor.image.edit'],1)}}">
        <a class="{{activeMenu(['contributor.image.search','contributor.image.all','contributor.image.approved','contributor.image.rejected','contributor.image.banned','contributor.image.edit'],2)}}" href="javascript:void(0)"><i class="las la-image"></i> @lang('Manage Photos')</a>
        <ul class="author-widget-submenu">
          <li><a  href="{{route('contributor.image.all')}}" class="{{activeMenu('contributor.image.all',2)}}"><i class="las la-dot-circle"></i>@lang('Pending Photos')</a></li>
          <li><a  href="{{route('contributor.image.approved')}}" class="{{activeMenu('contributor.image.approved',2)}}"><i class="las la-dot-circle"></i>@lang('Approved Photos')</a></li>
          <li><a  href="{{route('contributor.image.rejected')}}" class="{{activeMenu('contributor.image.rejected',2)}}"><i class="las la-dot-circle"></i>@lang('Rejected Photos')</a></li>
          <li><a  href="{{route('contributor.image.banned')}}" class="{{activeMenu('contributor.image.banned',2)}}"><i class="las la-dot-circle"></i>@lang('Banned Photos')</a></li>
        </ul>
      </li>
     @endif
    
      <li><a class="{{activeMenu('user.download.all',2)}}" href="{{route('user.download.all')}}"><i class="las la-cloud-download-alt"></i> @lang('My Downloads')</a></li>
    
      <li><a class="{{activeMenu(['user.following.all','user.follwing.search'],2)}}" href="{{route('user.following.all')}}"><i class="las la-check-double"></i>  @lang('Following')</a></li>
    
      <li><a class="{{activeMenu(['contributor.follower.all','contributor.follower.search'],2)}}" href="{{route('contributor.follower.all')}}"><i class="las la-user-friends"></i> @lang('Followers')</a></li>
     
      <li><a class="{{activeMenu('user.liked.all',2)}}" href="{{route('user.liked.all')}}"><i class="las la-heart"></i>  @lang('Liked Photos')</a></li>
    
      @if (auth()->user()->con_flag == 1)
      
      <li><a class="{{activeMenu('user.earning.logs',2)}}" href="{{route('user.earning.logs')}}"><i class="las la-hand-holding-usd"></i>  @lang('Earning Logs')</a></li>

      <li class="author-widget-menu-has-child {{activeMenu(['user.withdraw','user.withdraw.preview','user.withdraw.history'],1)}}">
        <a class="{{activeMenu(['user.withdraw','user.withdraw.preview','user.withdraw.history'],2)}}" href="javascript:void(0)" ><i class="las la-file-invoice-dollar"></i> @lang('Widthdraw')</a>
        <ul class="author-widget-submenu">
          <li><a  href="{{route('user.withdraw')}}" class="{{activeMenu('user.withdraw',2)}}"><i class="las la-dot-circle"></i>@lang('Widthdraw Methods')</a></li>
          <li><a href="{{route('user.withdraw.history')}}" class="{{activeMenu('user.withdraw.history',2)}}"><i class="las la-dot-circle"></i>  @lang('Logs')</a></li>
         
        </ul>
      </li>
     
     @endif

    <li class="author-widget-menu-has-child {{activeMenu(['user.referred.users','user.referral.commissions'],1)}}">
      <a class="{{activeMenu(['user.referred.users','user.referral.commissions'],2)}}" href="javascript:void(0)" ><i class="las la-users"></i> @lang('Referrals')</a>
      <ul class="author-widget-submenu">
        <li><a  href="{{route('user.referred.users')}}" class="{{activeMenu('user.referred.users',2)}}"><i class="las la-dot-circle"></i>@lang('Referred Users')</a></li>
        <li><a href="{{route('user.referral.commissions')}}" class="{{activeMenu('user.referral.commissions',2)}}"><i class="las la-dot-circle"></i>  @lang('Referral Commission')</a></li>
       
      </ul>
    </li>


    <li><a href="{{route('user.trx')}}" class="{{activeMenu('user.trx',2)}}"><i class="las la-exchange-alt"></i> @lang('Transaction logs')</a></li>


    <li class="author-widget-menu-has-child {{activeMenu(['ticket','user.profile','user.password','user.twofactor'],1)}}">
      <a class="{{activeMenu(['ticket','user.profile','user.password','user.twofactor'],2)}}" href="javascript:void(0)" ><i class="las la-user-circle"></i> @lang('Account')</a>
      <ul class="author-widget-submenu">
        <li><a href="{{route('ticket')}}" class="{{activeMenu('ticket',2)}}"><i class="las la-ticket-alt"></i>@lang('Support Ticket')</a></li>

        <li><a href="{{route('user.profile')}}" class="{{activeMenu('user.profile',2)}}"><i class="las la-user-cog"></i>@lang(' Profile Settings')</a></li>
     
        <li><a href="{{route('user.password')}}" class="{{activeMenu('user.password',2)}}"><i class="las la-key"></i>@lang('Change Password')</a></li>
  
        <li><a href="{{route('user.twofactor')}}" class="{{activeMenu('user.twofactor',2)}}"><i class="las la-lock"></i>@lang('2FA Security')</a></li>
       
      </ul>
    </li>
    
     
      <li><a href="{{route('user.logout')}}" class="{{activeMenu('user.logout',2)}}"><i class="las la-sign-out-alt"></i>@lang('Logout')</a></li>
     
      </ul>

    </div><!-- author-widget end -->
  </div>

  @push('script')
  <script>
    $('li.author-widget-menu-has-child>a, ul.author-widget-submenu>li.author-widget-menu-has-child>a').on('click', function(e) {
      var element = $(this).parent('li');
      if (element.hasClass('open')) {
          element.removeClass('open');
          element.find('li').removeClass('open');
          element.find('ul').slideUp(500,"swing");
      }
      else {
     
        element.addClass('open');
      
        element.children('ul').slideDown(800,"swing");
        element.siblings('li').children('ul').slideUp(800,"swing");
        element.siblings('li').removeClass('open');
        element.siblings('li').find('li').removeClass('open');
        element.siblings('li').find('ul').slideUp(1000,"swing");
       
      }
    });
  </script>
    
  @endpush



