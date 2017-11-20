
<li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> {{Auth::user()->name}} <span class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu-left changewidth" >
                  @if (Auth::user()->role==1||Auth::user()->role==3||Auth::user()->role==4)
                       <li class="dropdown-li"><a href="{{route("admin.post.index")}}">Manage Post</a></li>
                       <li class="dropdown-li"><a href="{!! route("post-category.index") !!}">Post Category</a> </li>
                       <li class="dropdown-li"><a href="{!! route("post-tag.index") !!}">Post Tag</a> </li>
                  @endif

                  @if (Auth::user()->role==1||Auth::user()->role==4)
                    <li class="dropdown-li"><a href="{{route("admin.users.index")}}"><span class="fa fa-users"></span>&nbsp; Manage User</a></li>
                    <li class="dropdown-li"><a href="{{route("admin.slider.index")}}">Manage Slider</a></li>
                    <li class="dropdown-li"><a href="{{route("admin.message.index")}}"><i class="fa fa-envelope-o"></i>&nbsp;Messages</a></li>
                    <li class="dropdown-li" ><a href="{{route("format.index")}}">Manage Format</a></li>
                    <li class="dropdown-li" ><a href="{{route("admin.deck.index")}}">Manage Deck</a></li>
                    <li class="dropdown-li" ><a href="{{route("admin.deck.unverified")}}">User added Deck</a></li>
                      <li class="dropdown-li"><a href="{!! route("season.index") !!}">Manage Season </a> </li>
                @endif
               @if (Auth::user()->role==4)

                   <li class="dropdown-li"><a href="{{route("view.user.admin")}}">See admins</a></li>
                   <li class="dropdown-li"><a href="{{route("send.news")}}">Send News</a></li>
                          <li class="dropdown-li"><a href="{{route("subscription.all")}}">Manage Subscription</a> </li>
               @endif

                    <li class="dropdown-li"><a href="{{route("user.profile.setting")}}"><i class="fa fa-cog"></i> Settings</a></li>
                    <li>
                                         <a href="{{ route('logout') }}"
                                             onclick="event.preventDefault();
                                                      document.getElementById('logout-form').submit();">
                                             Logout
                                         </a>

                                         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                             {{ csrf_field() }}
                                         </form>
                                     </li>

                </ul>
              </li>
