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
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
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
                font-size: 84px;
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
            }
            .header {
                height: auto;
            }

            .top {
                height: 80px;
                width: 100%;
                background: antiquewhite;
            }
            .left_1{
                background: aquamarine;
                height: 150px;
                width: 20%;
            }

            .left_2{
                background: darkgoldenrod;
                height: 150px;
                width: 20%;
            }
            .right_1{
                height: 80px;
                width: 100px;
                background: burlywood;
                float: right;
                #margin-top: -100px;
                position: absolute;
            }


        </style>
    </head>
    <body>
        <div class="header">
            <div class="top">

            </div>
            <div class="left_1">
                <form action="/test" method="post">
                    <input type="text" name="" id="">
                    <input type="password" name="" id="">
                    <input type="submit" name="提交">
                </form>
            </div>

            <div class="right_1">



            </div>

            <div class="left_2">

            </div>
        </div>
    </body>
</html>
