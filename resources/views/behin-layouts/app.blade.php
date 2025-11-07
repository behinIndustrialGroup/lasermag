<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <link rel="manifest" href="{{ url('manifest.json') . '?' . config('app.version') }}">

    <title>@yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/plugins/font-awesome/css/font-awesome.min.css') . '?' . config('app.version') }}">
    <!-- Ionicons -->
    {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
    <!-- Theme style -->
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/dist/css/adminlte.min.css') . '?' . config('app.version') }}">
    <!-- Date Picker -->
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/plugins/datepicker/datepicker3.css') . '?' . config('app.version') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/plugins/daterangepicker/daterangepicker-bs3.css') . '?' . config('app.version') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') . '?' . config('app.version') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- bootstrap rtl -->
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/dist/css/bootstrap-rtl.min.css') . '?' . config('app.version') }}">
    <!-- template rtl version -->
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/dist/css/custom-style.css') . '?' . config('app.version') }}">
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/dist/css/custom.css') . '?' . config('app.version') }}">

    {{-- <link rel="stylesheet" href="{{ url('behin/behin-dist/dist/css/custom.css')  . '?' . config('app.version') }}"> --}}
    <link rel="stylesheet" type="text/css"
        href="{{ url('behin/behin-dist/plugins/datatables/dataTables.bootstrap4.css') . '?' . config('app.version') }}" />
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/dist/css/dropzone.min.css') . '?' . config('app.version') }}">
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/plugins/toastr/toastr.min.css') . '?' . config('app.version') }}">
    {{-- <link rel="stylesheet" href="{{ Url('behin/behin-dist/dist/css/persian-datepicker-0.4.5.min.css')  . '?' . config('app.version') }}" /> --}}
    {{-- <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ url('behin/behin-dist/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('behin/behin-dist/persian-date-picker/persian-datepicker.css') }}">
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/plugins/mapp/css/mapp.min.css') . '?' . config('app.version') }}">
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/plugins/mapp/css/fa/style.css') . '?' . config('app.version') }}">
    <link rel="stylesheet" href="{{ url('behin/behin-dist/plugins/timepicker/jquery.timepicker.min.css') }}">
    <!-- Material Icons اضافه -->
    <link href="{{ url('behin/behin-dist/css/all.min.css') . '?' . config('app.version') }}" rel="stylesheet">

    <!-- استایل سفارشی متریال -->
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Source Sans Pro', sans-serif;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .btn {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .main-header.navbar {
            background-color: #1976d2 !important;
        }

        .main-sidebar {
            border-top-right-radius: 12px;
            background-color: #212121 !important;
        }

        .main-footer {
            background-color: #fff;
            border-top: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }

        /* جدول‌ها کمی متریال‌تر */
        table.dataTable {
            border-radius: 8px;
            overflow: hidden;
        }

        table.dataTable thead {
            background-color: #eeeeee;
        }

        .more-text {
            display: none;
        }

        .show-more-btn {
            cursor: pointer;
            color: #1976d2;
            font-size: 14px;
            vertical-align: middle;
        }

        .show-more-btn:hover {
            text-decoration: underline;
        }
    </style>
    @yield('style')

    <script src="{{ url('behin/behin-dist/plugins/jquery/jquery.min.js') . '?' . config('app.version') }}"></script>
    <script
        src="{{ url('behin/behin-dist/plugins/datatables/jquery.dataTables.js') . '?' . config('app.version') }}">
    </script>
    <script
        src="{{ url('behin/behin-dist/plugins/datatables/dataTables.bootstrap4.js') . '?' . config('app.version') }}">
    </script>
    <script src="{{ url('behin/behin-dist/persian-date-picker/persian-date.js') . '?' . config('app.version') }}">
    </script>
    <script
        src="{{ url('behin/behin-dist/persian-date-picker/persian-datepicker.js') . '?' . config('app.version') }}">
    </script>


    <script src="{{ url('behin/behin-dist/plugins/mapp/js/mapp.env.js') . '?' . config('app.version') }}"></script>

    <script>
        window.appUrl = "{{ env('APP_URL') }}";
    </script>
    <script src="{{ url('behin/behin-js/ajax.js') . '?' . config('app.version') }}"></script>
    <script src="{{ url('behin/behin-js/dataTable.js') . '?' . config('app.version') }}"></script>
    <script src="{{ url('behin/behin-js/dropzone.js') . '?' . config('app.version') }}"></script>
    <script src="{{ url('behin/behin-dist/plugins/timepicker/jquery.timepicker.min.js') }}"></script>
    <script
        src="{{ url('behin/behin-dist/plugins/autonumeric/autoNumeric.min.js') . '?' . config('app.version') }}">
    </script>



    @yield('script_in_head')

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        @include('behin-layouts.header')

        @include('behin-layouts.main-sidebar')
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    @php
                        $disableBackBtn = true;
                    @endphp
                    @if (!isset($disableBackBtn))
                        <div class="card">
                            <div class="card-header">
                                <a href="javascript:history.back()" class="btn btn-outline-primary float-left">
                                    <i class="fa fa-arrow-left"></i> {{ trans('fields.Back') }}
                                </a>
                            </div>
                        </div>
                    @endisset
                </div>
            <div class="container-fluid p-2">
                @yield('content')
            </div>

        </section>
    </div>


    <footer class="main-footer">
    @include('behin-layouts.mobile-navigation')

        {{-- <strong> &copy; 2018 <a href="http://github.com/hesammousavi/">حسام موسوی</a>.</strong> --}}
    </footer>

    <aside class="control-sidebar control-sidebar-dark">
    </aside>
</div>

<script
    src="{{ url('behin/behin-dist/plugins/bootstrap/js/bootstrap.bundle.min.js') . '?' . config('app.version') }}">
</script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> --}}
<script src="{{ url('behin/behin-dist/plugins/knob/jquery.knob.js') . '?' . config('app.version') }}"></script>
<script
    src="{{ url('behin/behin-dist/plugins/daterangepicker/daterangepicker.js') . '?' . config('app.version') }}">
</script>
<script
    src="{{ url('behin/behin-dist/plugins/datepicker/bootstrap-datepicker.js') . '?' . config('app.version') }}">
</script>
<script
    src="{{ url('behin/behin-dist/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') . '?' . config('app.version') }}">
</script>
<script src="{{ url('behin/behin-dist/dist/js/adminlte.js') . '?' . config('app.version') }}"></script>
<script src="{{ url('behin/behin-dist/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('behin/behin-dist/plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ url('behin/behin-dist/plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ url('behin/behin-dist/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ url('behin/behin-dist/plugins/mapp/js/mapp.min.js') . '?' . config('app.version') }}"></script>
<script src="{{ url('behin/behin-dist/plugins/toastr/toastr.min.js') . '?' . config('app.version') }}"></script>

<script>
    function logout() {
        window.location = "{{ route('logout') }}"
    }
</script>



<script>
    initial_view()

    function initial_view() {
        $('.select2').select2();
        $('.select2').css('width', '100%')
        $(".persian-date").persianDatepicker({
            viewMode: 'day',
            initialValue: false,
            format: 'YYYY-MM-DD',
            initialValueType: 'persian',
            calendar: {
                persian: {
                    leapYearMode: 'astronomical',
                    locale: 'fa'
                }
            }
        });
        $('.timepicker').timepicker({
            timeFormat: 'HH:mm', // فرمت 24 ساعته
            interval: 1, // نمایش با فاصله 5 دقیقه‌ای
            minTime: '00:00',
            maxTime: '23:55',
            dynamic: true,
            dropdown: true,
            scrollbar: true
        });
        AutoNumeric.multiple('.formatted-digit', {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalPlaces: 0,
            unformatOnSubmit: true
        });

        $('table tbody td').each(function() {
            let $cell = $(this);
            let originalHtml = $cell.html();
            console.log(originalHtml)
            let textOnly = $cell.text().trim();

            // اگر شامل دکمه یا اسپن بود، هیچی تغییر نده
            if (originalHtml.includes('button') || originalHtml.includes('span') || originalHtml.includes('a')) {
                return;
            }

            if (textOnly.length > 25) {
                let shortText = textOnly.substr(0, 25) ;

                $cell.html(`
            <span class="short-text">${shortText}</span>
            <span class="full-text" style="display:none;">${originalHtml}</span>
            <button class="toggle-btn show-more-btn material-icons" style="border:none;background:none;cursor:pointer;">more_horiz</button>
        `);
            }
        });

        // هندل کلیک روی نمایش بیشتر/کمتر
        $(document).on('click', '.toggle-btn', function() {
            let $cell = $(this).closest('td');
            $cell.find('.short-text, .full-text').toggle();
            $(this).text($(this).text() === 'more_horiz' ? 'expand_less' : 'more_horiz');
        });


    }
</script>

<script src="{{ url('behin/behin-js/loader.js') . '?' . config('app.version') }}"></script>
<script src="{{ url('behin/behin-js/scripts.js') . '?' . config('app.version') }}"></script>
@yield('script')
</div>


</body>

</html>
