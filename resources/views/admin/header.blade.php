<header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    <div class="header-left">
                        
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="javascript:void();" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            
                            
                        @if(Auth::user()->user_photo != '')
                                                <img src="{{ url('/') }}/public/storage/users/{{ Auth::user()->user_photo }}"  class="user-avatar rounded-circle" alt="{{ Auth::user()->name }}"/>@else <img src="{{ url('/') }}/public/img/no-user.png"  class="user-avatar rounded-circle" alt="{{ Auth::user()->name }}"/>  @endif
                        
                        </a>
<div class="user-menu dropdown-menu">
                            <a class="nav-link" href="{{ url('/admin/edit-profile') }}"><i class="fa fa-user"></i> My Profile</a>
                            @if(in_array('settings',$avilable))
                            <a class="nav-link" href="{{ url('/admin/general-settings') }}"><i class="fa fa-cog"></i> Settings</a>
                            @endif
                            <a class="nav-link" href="{{ url('/logout') }}"><i class="fa fa-power-off"></i> Logout</a>
                        </div>
                        </div>

                    

                </div>
            </div>

        </header>
                    