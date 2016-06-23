window.chartData = {};
window.chartOptions = {};
window.chartInstance = {};
window.fillGraph = {};

$(function() {
    if(is_page('stats')) {

        // Buttons table more
        $(".table-more-button").click(function (event) {
            getReplaceTable($(this));
        });

        // Graphs
        $.each($('.graph-handler'), function(key, value) {
            var parentObj = $(this).parent();
            var getUrlAjax = parentObj.find('.href-url').val();
            var idGraph = $(this).attr('id');
            var type = parentObj.find('.type').val();

            $(this).parent().find('.graph-previous').click(function (event) {
                getAjaxArrayData(idGraph, 'nextPage');
            });
            $(this).parent().find('.graph-next').click(function (event) {
                getAjaxArrayData(idGraph, 'previousPage');
            });

            window.fillGraph[idGraph] = function(page, arrayData) {
                $('#' + idGraph).replaceWith('<canvas class="graph-handler" id="' + idGraph + '"></canvas>');

                window.chartData[idGraph]['labels'] = arrayData['labels'];
                for(var i = 0; i < window.chartData[idGraph]['datasets'].length; i++) {
                    window.chartData[idGraph]['datasets'][i].data = arrayData['serie' + (i+1)];
                }

                disableEnableButtonPage(parentObj.find('.graph-next'), page);

                var chart = null;
                if(type == 'line') {
                    chart = new Chart($('#' + idGraph).get(0).getContext("2d")).Line(window.chartData[idGraph], window.chartOptions[idGraph]);
                } else if(type == 'bar') {
                    chart = new Chart($('#' + idGraph).get(0).getContext("2d")).Bar(window.chartData[idGraph], window.chartOptions[idGraph]);
                }

                document.getElementById(idGraph).onclick = function (evt) {
                    ajaxLinkTo(getUrlAjax, page, chart.getPointsAtEvent(evt));
                };
            };

            getAjaxArrayData(idGraph, 'currentPage');
        });

        // Utility functions
        function getAjaxArrayData(key, mode) {
            var arrayData = null;

            var obj = $('#' + key);
            var currentPage = parseInt(obj.parent().find('.page').val());
            var href = obj.parent().find('.href').val();
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

                obj.parent().find('.page').val(pageToDisplay);

                arrayData = JSON.parse(ajaxReturn);

                window.fillGraph[key](pageToDisplay, arrayData);
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
                url: route + '/' + page + '/' + activePoints[0]['label']
            }).done(function (ajaxReturn) {
                window.open(ajaxReturn);
            });
        }
    }

});