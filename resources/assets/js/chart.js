$(function() {

    if(is_page('stats')) {
        // Chart plays by month
        $("#plays-by-month-previous-months").click(function (event) {
            event.preventDefault();

            $.ajax({
                url: $(this).data('href') + '/' + $("#plays-by-month-previous-months").attr('data-page')
            }).done(function (ajaxReturn) {

                $("#plays-by-month-previous-months").attr('data-page', parseInt($("#plays-by-month-previous-months").attr('data-page')) + 1);

                var arrayData = JSON.parse(ajaxReturn);

                $('#plays-by-month').replaceWith('<canvas id="plays-by-month" width="400" height="100"></canvas>');

                window.playByMonthChartData['labels'] = arrayData['labels'].split(',');
                window.playByMonthChartData['datasets'][0].data = arrayData['serie1'].split(',');
                window.playByMonthChartData['datasets'][1].data = arrayData['serie2'].split(',');
                window.playByMonthChartData['datasets'][2].data = arrayData['serie3'].split(',');

                var playByMonthChart = new Chart($('#plays-by-month').get(0).getContext("2d")).Line(window.playByMonthChartData, window.playByMonthOptions);

                setPlayByMonthHolder(playByMonthChart);
            });

            return false;
        });
        function setPlayByMonthHolder(chart) {
            document.getElementById('plays-by-month').onclick = function (evt) {
                var activePoints = chart.getPointsAtEvent(evt);

                $.ajax({
                    url: '/ajaxPlayByMonthGetUrl/' + $("#username").val() + '/' + (parseInt($("#plays-by-month-previous-months").attr('data-page')) - 1) + '/' + activePoints[0]['label']
                }).done(function (ajaxReturn) {
                    window.open(ajaxReturn);
                });
            }
        }

        setPlayByMonthHolder(playByMonthChart);

        // Chart most played
        $("#chart-most-played-previous-games").click(function (event) {
            event.preventDefault();

            $.ajax({
                url: $(this).data('href') + '/' + $("#chart-most-played-previous-games").attr('data-page')
            }).done(function (ajaxReturn) {

                $("#chart-most-played-previous-games").attr('data-page', parseInt($("#chart-most-played-previous-games").attr('data-page')) + 1);

                var arrayData = JSON.parse(ajaxReturn);

                $('#chart-most-played').replaceWith('<canvas id="chart-most-played" width="400" height="200"></canvas>');

                window.mostPlayedChartData['labels'] = arrayData['labels'].split(',');
                window.mostPlayedChartData['datasets'][0].data = arrayData['serie1'].split(',');

                var mostPlayedChart = new Chart($('#chart-most-played').get(0).getContext("2d")).Bar(window.mostPlayedChartData, window.mostPlayedOptions);

                setMostPlayedHolder(mostPlayedChart);
            });

            return false;
        });

        function setMostPlayedHolder(chart) {
            document.getElementById('chart-most-played').onclick = function (evt) {
                var activePoints = chart.getBarsAtEvent(evt);

                $.ajax({
                    url: '/ajaxMostPlayedGetUrl/' + $("#username").val() + '/' + (parseInt($("#chart-most-played-previous-games").attr('data-page')) - 1) + '/' + activePoints[0]['label']
                }).done(function (ajaxReturn) {
                    window.open(ajaxReturn);
                });
            }
        }

        setMostPlayedHolder(mostPlayedChart);

        // Chart acquisition by month
        $("#chart-acquisition-previous-month").click(function (event) {
            event.preventDefault();

            $.ajax({
                url: $(this).data('href') + '/' + $("#chart-acquisition-previous-month").attr('data-page')
            }).done(function (ajaxReturn) {

                $("#chart-acquisition-previous-month").attr('data-page', parseInt($("#chart-acquisition-previous-month").attr('data-page')) + 1);

                var arrayData = JSON.parse(ajaxReturn);

                $('#chart-acquisitionByMonth').replaceWith('<canvas id="chart-acquisitionByMonth" width="400" height="100"></canvas>');

                window.acquisitionByMonthChartData['labels'] = arrayData['labels'].split(',');
                window.acquisitionByMonthChartData['datasets'][0].data = arrayData['serie1'].split(',');

                var acquisitionByMonthChart = new Chart($('#chart-acquisitionByMonth').get(0).getContext("2d")).Bar(window.acquisitionByMonthChartData, window.acquisitionByMonthOptions);

                setAcquisitionByMonthHolder(acquisitionByMonthChart);
            });

            return false;
        });

        function setAcquisitionByMonthHolder(chart) {
            document.getElementById('chart-acquisitionByMonth').onclick = function (evt) {
                var activePoints = chart.getBarsAtEvent(evt);

                $.ajax({
                    url: '/ajaxAcquisitionByMonthGetUrl/' + $("#username").val() + '/' + (parseInt($("#chart-acquisition-previous-month").attr('data-page')) - 1) + '/' + activePoints[0]['label']
                }).done(function (ajaxReturn) {
                    window.open(ajaxReturn);
                });
            }
        }

        setAcquisitionByMonthHolder(acquisitionByMonthChart);
    }

});