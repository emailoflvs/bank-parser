<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            /*height: 100vh;*/
            margin: 0;
        }

        .full-height {
            /*height: 100vh;*/
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 44px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .m-b-md {
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .date {
            margin-bottom: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                {{--                <a href="{{ url('/home') }}">Home</a>--}}
                <a href="{{ url('/logout') }}"
                   onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ url('/logout') }}"
                      method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            Котировки:
        </div>


        @isset ($days)
            <div class="form-group row">
                Импортировано котировок за {{ $days }} дней
                <br>
            </div>
        @endisset

        @isset ($valuteId)
            <div class="form-group row">
                Импортировано динамики котировок {{ $valuteId }} за период {{ $parsingFrom }}-{{ $parsingTo }}
                <br>
            </div>
        @endisset

        <form method="GET" action="/">
            {{ csrf_field() }}
            <div class="form-group row">

                Выберите дату для котировки:
                <input type="date" id="parsingDate" name="parsingDate" class="form-control mtarih"/>
                {{--                до:--}}
                {{--                <input type="date" id="parsingTo" name="parsingTo" class="form-control mtarih"/>--}}

                <button type="submit" class="btn btn-primary">
                    Показать
                </button>
            </div>
        </form>

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">

                @isset ($table)

                    @isset ($date)
                        <div class="date">
                            Данные за {{ $date }}:
                        </div>
                    @endisset

                    <table>
                        @foreach ($table as $tr)
                            <tr>
                                <td align="left">{{ $tr['name'] }} </td>
                                <td>{{ $tr['value'] }} </td>
                            </tr>
                        @endforeach
                    </table>
                @endisset


            </div>
        </div>


        {{--        <div class="links">--}}
        {{--            <a href="https://laravel.com/docs">Docs</a>--}}
        {{--            <a href="https://laracasts.com">Laracasts</a>--}}
        {{--            <a href="https://laravel-news.com">News</a>--}}
        {{--            <a href="https://blog.laravel.com">Blog</a>--}}
        {{--            <a href="https://nova.laravel.com">Nova</a>--}}
        {{--            <a href="https://forge.laravel.com">Forge</a>--}}
        {{--            <a href="https://vapor.laravel.com">Vapor</a>--}}
        {{--            <a href="https://github.com/laravel/laravel">GitHub</a>--}}
        {{--        </div>--}}

    </div>

</div>
</body>
</html>




















