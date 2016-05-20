$(function() {

    if(is_page('stats')) {
        // Table less time since last played
        $("#table-less-time-previous").click(function (event) {
            var self = $(this);
            getReplaceTable(self);
        });
        // Table more time since last played
        $("#table-most-time-previous").click(function (event) {
            var self = $(this);
            getReplaceTable(self);
        });

        // Chart plays by month
        $("#plays-by-month-previous-months").click(function (event) {
            var self = $(this);
            var arrayData = getAjaxArrayData(self);

            $('#plays-by-month').replaceWith('<canvas id="plays-by-month" width="400" height="100"></canvas>');

            window.playByMonthChartData['labels'] = arrayData['labels'].split(',');
            window.playByMonthChartData['datasets'][0].data = arrayData['serie1'].split(',');
            window.playByMonthChartData['datasets'][1].data = arrayData['serie2'].split(',');
            window.playByMonthChartData['datasets'][2].data = arrayData['serie3'].split(',');

            var playByMonthChart = new Chart($('#plays-by-month').get(0).getContext("2d")).Line(window.playByMonthChartData, window.playByMonthOptions);

            setPlayByMonthHolder(playByMonthChart, parseInt(self.data('page')));
        });
        function setPlayByMonthHolder(chart, page) {
            document.getElementById('plays-by-month').onclick = function (evt) {
                var activePoints = chart.getPointsAtEvent(evt);
                ajaxLinkTo('ajaxPlayByMonthGetUrl', page, activePoints);
            }
        }

        setPlayByMonthHolder(playByMonthChart, 2);

        // Chart most played
        $("#chart-most-played-previous-games").click(function (event) {
            var self = $(this);
            var arrayData = getAjaxArrayData(self);

            $('#chart-most-played').replaceWith('<canvas id="chart-most-played" width="400" height="200"></canvas>');

            window.mostPlayedChartData['labels'] = arrayData['labels'].split(',');
            window.mostPlayedChartData['datasets'][0].data = arrayData['serie1'].split(',');

            var mostPlayedChart = new Chart($('#chart-most-played').get(0).getContext("2d")).Bar(window.mostPlayedChartData, window.mostPlayedOptions);

            setMostPlayedHolder(mostPlayedChart, parseInt(self.data('page')));
        });

        function setMostPlayedHolder(chart, page) {
            document.getElementById('chart-most-played').onclick = function (evt) {
                var activePoints = chart.getBarsAtEvent(evt);
                ajaxLinkTo('ajaxMostPlayedGetUrl', page, activePoints);
            }
        }

        setMostPlayedHolder(mostPlayedChart, 2);

        // Chart acquisition by month
        // Seulement si l'utilisateur a accès
        if ($('#chart-acquisitionByMonth').length) {
            $("#chart-acquisition-previous-month").click(function (event) {
                var self = $(this);
                var arrayData = getAjaxArrayData(self);

                $('#chart-acquisitionByMonth').replaceWith('<canvas id="chart-acquisitionByMonth" width="400" height="100"></canvas>');

                window.acquisitionByMonthChartData['labels'] = arrayData['labels'].split(',');
                window.acquisitionByMonthChartData['datasets'][0].data = arrayData['serie1'].split(',');

                var acquisitionByMonthChart = new Chart($('#chart-acquisitionByMonth').get(0).getContext("2d")).Bar(window.acquisitionByMonthChartData, window.acquisitionByMonthOptions);

                setAcquisitionByMonthHolder(acquisitionByMonthChart, parseInt(self.data('page')));
            });

            function setAcquisitionByMonthHolder(chart) {
                document.getElementById('chart-acquisitionByMonth').onclick = function (evt) {
                    var activePoints = chart.getBarsAtEvent(evt);
                    ajaxLinkTo('ajaxAcquisitionByMonthGetUrl', page, activePoints);
                }
            }

            setAcquisitionByMonthHolder(acquisitionByMonthChart, 2);
        }

        // Utility functions
        function getAjaxArrayData(obj) {
            var arrayData = null;

            $.ajax({
                url: obj.data('href') + '/' + obj.data('page'),
                async: false
            }).done(function (ajaxReturn) {

                obj.data('page', parseInt(obj.data('page')) + 1);

                arrayData = JSON.parse(ajaxReturn);
            });

            return arrayData;
        }
        function getReplaceTable(obj) {
            $.ajax({
                url: obj.data('href') + '/' + obj.data('page')
            }).done(function (ajaxReturn) {
                obj.data('page', parseInt(obj.data('page')) + 1);
                $(obj.data('replace')).html(ajaxReturn);
            });
        }
        function ajaxLinkTo(route, page, activePoints) {
            $.ajax({
                url: '/' + route + '/' + $("#username").val() + '/' + (page - 1) + '/' + activePoints[0]['label']
            }).done(function (ajaxReturn) {
                window.open(ajaxReturn);
            });
        }
    }

});