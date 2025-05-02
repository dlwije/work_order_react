<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @unlessrole('Technician')
        <li class="nav-item">
            <a class="nav-link" target="_blank" href="https://wocrm.orions360.com" role="button" data-toggle="tooltip" data-placement="top" title="Click to Navigate to the Lead CRM">
                <i class="fas fa-user-clock"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link"  href="https://pos.orions360.com/admin/auto-login?d={{ session()->get('enc_data') }}" target="_blank"><i class="fas fa-cash-register"></i></a>
        </li>
        @endunlessrole
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge" id="notify_count"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notify_drop_list">
            <!-- this space will fill by JS -->
        </div>
      </li>
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="{{asset('img/default_user_img.png')}}" class="user-image img-circle elevation-2" alt="User Image">
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- Users image -->
                <li class="user-header bg-primary">
                    <img src="{{asset('img/default_user_img.png')}}" class="img-circle elevation-2" alt="User Image">

                    <p>
                        {{ Auth::user()->name }}
{{--                        <small>Member since Nov. 2012</small>--}}
                    </p>
                </li>
                <!-- Menu Body -->
                {{--<li class="user-body">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="#">Followers</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Sales</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Friends</a>
                        </div>
                    </div>
                    <!-- /.row -->
                </li>--}}
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
{{--                    <a href="{{route('logout')}}" class="btn btn-default btn-flat float-right">Sign out</a>--}}
                    <a class="btn btn-default btn-flat float-right" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>

    </ul>
</nav>
<!-- /.navbar -->
@push('scripts')
    <script>
        $(document).ready(function () {
            setUnreadNotifications();

            setInterval(function () { checkUnreadNotifications(); },5000);
        });

        function getUnreadNotifications() {
            try{
                return $.ajax({
                    url: '{{route('unreadNotify')}}',
                    crossDomain: true,async: true,dataType: 'json'
                });
            }catch (e) { console.log(e); }
        }
        function setUnreadNotifications() {
            //call once when page load
            getUnreadNotifications().done(function (result) { setNotifyList(result); })
        }
        function checkUnreadNotifications() {
            //this will call recursively
            getUnreadNotifications().done(function (result) { setNotifyList(result) })
        }
        function setNotifyList(j_res){

            let strNotify = '<span class="dropdown-item dropdown-header" id="notify_ins_count">0</span>';
            let notify_count = j_res['data']['dataCount'];
            $('#notify_count').html(notify_count)

            setTimeout(function () { $('#notify_ins_count').html(notify_count+' Notification (s)') }, 100);

            $.each(j_res['data']['data'], function (i, item) {
                strNotify += '<div class="dropdown-divider"></div>';
                strNotify += '<a href="javascript:0" onclick="updateReadState('+item.id+',)" class="dropdown-item">';
                strNotify += item.notify_body;
                strNotify += '<span class="float-right text-success text-sm">Mark read</span>';
                strNotify += '</a>';
            });

            strNotify += '<div class="dropdown-divider"></div>';
            let state = "all";
            let quote_t = `'${state}'`;
            let null_st = null;
            if(notify_count > 0) {
                strNotify += '<a href="javascript:0" onclick="updateReadState(' + null_st + ',' + quote_t + ')" class="dropdown-item dropdown-footer">Mark All Read</a>';
                $('#notify_drop_list').html(strNotify)
            }
        }

        function updateReadState(notify_id, state=null) {
            let read_st = '';
            if(state == 'all') read_st = 'all';
            $.ajax({
                url: '{{route('markReadNotify')}}',
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": notify_id,
                    "state": read_st,
                },
                beforeSend: (jqXHR, settings) => { StartLoading(); },
                success: (responseData) => {
                    StopLoading();
                    setUnreadNotifications();
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    StopLoading();

                    if (jqXHR.status == 403) {
                        sweetAlertMsg('Permission Error', 'Users does not have the right permissions.', 'warning');
                    } else {
                        var errors = jqXHR.responseJSON.message;

                        var errorList = "<ul>";

                        $.each(errors, function (i, error) {
                            errorList += '<li class="text-left text-danger">' + error + '</li>';
                        })

                        errorList += "</ul>"

                        sweetAlertMsg('Form validation Error', errorList, 'warning');
                    }
                }
            });
        }

        function submitLogin() {

            $.ajax({
                url: 'http://localhost:8081/advance_pos_345/admin/auth/login',
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                // dataType: 'json',
                crossDomain: true,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "identity": "{{ session()->get('email') }}",
                    "password": "{{ session()->get('increment') }}",
                },
                beforeSend: (jqXHR, settings) => { StartLoading(); },
                success: (responseData) => {
                    StopLoading();
                    console.log(responseData);
                    // setUnreadNotifications();
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    StopLoading();

                    if (jqXHR.status == 403) {
                        sweetAlertMsg('Permission Error', 'Users does not have the right permissions.', 'warning');
                    } else {
                        var errors = jqXHR.responseJSON.message;

                        var errorList = "<ul>";

                        $.each(errors, function (i, error) {
                            errorList += '<li class="text-left text-danger">' + error + '</li>';
                        })

                        errorList += "</ul>"

                        sweetAlertMsg('Form validation Error', errorList, 'warning');
                    }
                }
            });
        }

    </script>
@endpush
