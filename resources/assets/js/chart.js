$(function() {

    if(is_page('stats')) {
        // Table less time since last played
        $("#table-less-time-previous").click(function (event) {
            getReplaceTable($(this));
        });
        // Table more time since last played
        $("#table-most-time-previous").click(function (event) {
            getReplaceTable($(this));
        });

        // Chart plays by month
        $("#plays-by-month-previous-months").click(function (event) {
            getAjaxArrayData('plays-by-month', 'fillGraphPlaysByMonth', 'nextPage');
        });
        $("#plays-by-month-next-months").click(function (event) {
            getAjaxArrayData('plays-by-month', 'fillGraphPlaysByMonth', 'previousPage');
        });
        function setPlayByMonthHolder(chart, page) {
            document.getElementById('plays-by-month').onclick = function (evt) {
                ajaxLinkTo('ajaxPlayByMonthGetUrl', page, chart.getPointsAtEvent(evt));
            }
        }
        window.fillGraphPlaysByMonth = function(page, arrayData) {
            $('#plays-by-month').replaceWith('<canvas id="plays-by-month" width="400" height="100"></canvas>');

            window.playByMonthChartData['labels'] = arrayData['labels'];
            window.playByMonthChartData['datasets'][0].data = arrayData['serie1'];
            window.playByMonthChartData['datasets'][1].data = arrayData['serie2'];
            window.playByMonthChartData['datasets'][2].data = arrayData['serie3'];

            disableEnableButtonPage($("#plays-by-month-next-months"), page);

            setPlayByMonthHolder(
                new Chart($('#plays-by-month').get(0).getContext("2d")).Line(window.playByMonthChartData, window.playByMonthOptions),
                page
            );
        };
        setPlayByMonthHolder(playByMonthChart, 1);
        getAjaxArrayData('plays-by-month', 'fillGraphPlaysByMonth', 'currentPage');

        // Chart most played
        $("#most-played-previous-games").click(function (event) {
            getAjaxArrayData('most-played', 'fillGraphMostPlayed', 'nextPage');
        });
        $("#most-played-next-games").click(function (event) {
            getAjaxArrayData('most-played', 'fillGraphMostPlayed', 'previousPage');
        });
        function setMostPlayedHolder(chart, page) {
            document.getElementById('chart-most-played').onclick = function (evt) {
                ajaxLinkTo('ajaxMostPlayedGetUrl', page, chart.getBarsAtEvent(evt));
            }
        }
        window.fillGraphMostPlayed = function(page, arrayData) {
            $('#chart-most-played').replaceWith('<canvas id="chart-most-played" width="400" height="200"></canvas>');

            window.mostPlayedChartData['labels'] = arrayData['labels'];
            window.mostPlayedChartData['datasets'][0].data = arrayData['serie1'];

            disableEnableButtonPage($("#most-played-next-games"), page);

            setMostPlayedHolder(
                new Chart($('#chart-most-played').get(0).getContext("2d")).Bar(window.mostPlayedChartData, window.mostPlayedOptions),
                page
            );
        };
        setMostPlayedHolder(mostPlayedChart, 1);
        getAjaxArrayData('most-played', 'fillGraphMostPlayed', 'currentPage');

        // Chart acquisition by month
        // Seulement si l'utilisateur a accès
        if ($('#chart-acquisitionByMonth').length) {
            $("#acquisition-previous-month").click(function (event) {
                getAjaxArrayData('acquisition', 'fillGraphAcquisitionByMonth', 'nextPage');
            });
            $("#acquisition-next-month").click(function (event) {
                getAjaxArrayData('acquisition', 'fillGraphAcquisitionByMonth', 'previousPage');
            });
            function setAcquisitionByMonthHolder(chart) {
                document.getElementById('chart-acquisitionByMonth').onclick = function (evt) {
                    ajaxLinkTo('ajaxAcquisitionByMonthGetUrl', page, hart.getBarsAtEvent(evt));
                }
            }
            window.fillGraphAcquisitionByMonth = function(page, arrayData) {
                $('#chart-acquisitionByMonth').replaceWith('<canvas id="chart-acquisitionByMonth" width="400" height="100"></canvas>');

                window.acquisitionByMonthChartData['labels'] = arrayData['labels'];
                window.acquisitionByMonthChartData['datasets'][0].data = arrayData['serie1'];

                disableEnableButtonPage($("#acquisition-next-month"), page);

                setAcquisitionByMonthHolder(
                    new Chart($('#chart-acquisitionByMonth').get(0).getContext("2d")).Bar(window.acquisitionByMonthChartData, window.acquisitionByMonthOptions),
                    page
                );
            };
            setAcquisitionByMonthHolder(acquisitionByMonthChart, 2);
            getAjaxArrayData('acquisition', 'fillGraphAcquisitionByMonth', 'currentPage');
        }


        // Utility functions
        function getAjaxArrayData(key, callbackFunc, mode) {
            var arrayData = null;

            var currentPage = parseInt($('#' + key + '-page').val());
            var href = $('#' + key + '-href').val();
            var pageToDisplay = 0;

            if(mode == 'nextPage') {
                pageToDisplay = currentPage + 1;
            } else if(mode == 'previousPage') {
                pageToDisplay = currentPage - 1;
            } else {
                pageToDisplay = currentPage;
            }
            
            $.ajax({
                url: href + '/' + pageToDisplay,
            }).done(function (ajaxReturn) {

                $('#' + key + '-page').val(pageToDisplay);

                arrayData = JSON.parse(ajaxReturn);

                window[callbackFunc](pageToDisplay, arrayData);
            });

            return arrayData;
        }
        function disableEnableButtonPage(btn, page) {
            if(page == 1) {
                btn.prop('disabled', true);
            } else {
                btn.prop('disabled', false);
            }
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
                url: '/' + route + '/' + $("#username").val() + '/' + page + '/' + activePoints[0]['label']
            }).done(function (ajaxReturn) {
                window.open(ajaxReturn);
            });
        }
    }

});