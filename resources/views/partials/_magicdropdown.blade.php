<style>
    .changewidth{
        /*display: none;*/
        min-width: 100%;
        white-space: nowrap;
        word-wrap: break-word;
        background: #b397ac;
    }


    .dropdown-li{
        padding: 10px 0px 10px 0px;

    }

    .navbar-default .navbar-nav .open .dropdown-menu>li>a {
        color: white;
    }


</style>
@if (Auth::user()->magic_name!=null||Auth::user()->magic_name!="")
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Magic <span class="caret"></span></a>
        <ul class="dropdown-menu dropdown-menu-left changewidth" >
            <li class="dropdown-li"><a href="/game/magic/match">Log a Match</a> </li>
            <li class="dropdown-li"><a href="/game/magic">Stats</a> </li>
            <li class="dropdown-li"><a href="{!! route("blog.post.category","magic") !!}">Articles</a> </li>
            <li class="dropdown-li"><a href="/dice">Dice</a></li>
            <li class="dropdown-li"><a href="/hypergeometic-calculator">Hypergeometric Calculator</a> </li>
            <li class="dropdown-li"><a href="/life-counter">Life Counter</a> </li>
            <li class="dropdown-li"><a href="/magic-decks">Decks</a> </li>
        </ul>
    </li>

@else
    <li onclick="openmagicname()"><a href="#" >Magic</a></li>
@endif
