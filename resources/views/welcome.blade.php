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
            font-size: 24px;
            padding-top: 50px;
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
            Импорт котировок за кол-во дней:
        </div>

        {!! Form::open(['route' => 'xml_daily', 'method' => 'get']) !!}
        {{ csrf_field() }}

        Количество последних дней для импорта котировок

        {!! Form::text('days', '', ['class' => 'form-control']) !!}

        {{ Form::submit('Импортировать', ['class' => 'form-control']) }}

        {!! Form::close() !!}

        @isset ($days)
            <div class="form-group row">
                Импортировано котировок за {{ $days }} дней
                <br>
            </div>
        @endisset


        <div class="content">
            <div class="title m-b-md">
                Импорт котировок за период:
            </div>

            {!! Form::open(['route' => 'xml_dynamic', 'method' => 'get']) !!}
            {{ csrf_field() }}


            Выберите период:
            {{ Form::date('parsingFrom', \Carbon\Carbon::now()) }}

            {{ Form::date('parsingTo', \Carbon\Carbon::now()) }}

            {{ Form::submit('Импортировать', ['class' => 'form-control']) }}

            {!! Form::close() !!}

            @isset ($parsingFrom)
                <div class="form-group row">
                    Импортированы котировки за период:
                    {{ $parsingFrom }}-{{ $parsingTo }}
                    <br>
                    Код валюты:{{ $valuteId }}
                    <br>
                </div>
            @endisset
            @isset ($parsingError)
                <div class="form-group row">
                    {{ $parsingError }}
                    <br>
                </div>
            @endisset

            <div class="title m-b-md">
                Данными по валютам:
            </div>

            {!! Form::open(['route' => 'show_table', 'method' => 'get']) !!}
            {{ csrf_field() }}

            Выберите дату:
            {{ Form::date('parsingDate', \Carbon\Carbon::now()) }}

            {{--        {{ Form::date('test', \Carbon\CarbonPeriod::between('2018-10-30 13:26:19', '2019-10-30 13:26:19')) }}--}}

            {{ Form::submit('Показать', ['class' => 'form-control']) }}

            {!! Form::close() !!}


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

        </div>

    </div>
</body>
</html>




















