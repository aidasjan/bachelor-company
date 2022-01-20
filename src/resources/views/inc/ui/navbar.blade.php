<nav class="navbar navbar-expand-md navbar-dark navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src='{{asset('img/wmp-logo.svg')}}' height='50px'>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                            <a class="nav-link text-uppercase" style='color: #fff;' href="{{route('index')}}">{{ __('main.home') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-uppercase" style="color:#fff" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ __('main.products') }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <?php $categories = App\Category::orderBy('position')->get(); ?>
                            @foreach ($categories as $category)
                                <a class="dropdown-item" href="{{ url($category->getDisplayUrl()) }}">{{$category->name}}</a>
                            @endforeach
                        </div>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link text-uppercase" style='color: #fff;' href="{{ route('login') }}">{{ __('main.login') }}</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-uppercase" style="color:#fff" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ App::getLocale() }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ url('language/en') }}">EN (english)</a>
                                <a class="dropdown-item" href="{{ url('language/ru') }}">RU (русский)</a>
                            </div>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" style="color:#fff" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item" href="{{route('dashboard')}}">{{ __('main.dashboard') }}</a>

                                @if (Auth::user()->isClient())
                                    <a class="dropdown-item" href="{{ action('OrdersController@store') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('new-order-form').submit();">
                                        {{ __('main.make_order') }}
                                    </a>
                                @endif

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('main.logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <form id="new-order-form" action="{{ action('OrdersController@store') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-uppercase" style="color:#fff" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ App::getLocale() }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('language/en') }}">EN (english)</a>
                                <a class="dropdown-item" href="{{ url('language/ru') }}">RU (русский)</a>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
</nav>