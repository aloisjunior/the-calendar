<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}

    <title>Enube</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">


    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">

</head>
<body>
<div id="app">
    <v-app id="enubeapp">
            <v-main>
                <v-container fluid fill-height>

                    <transition name="slide-fade">

                        <v-layout justify-center>

                            <frontend></frontend>
                        </v-layout>
                    </transition>
                    </v-container>

            </v-main>

    </v-app>
</div>
</body>
<script src="js/app.js"></script>
</html>
