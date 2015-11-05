<h3>Parties par mois</h3>
<div style="width: calc(100% - 20px);">
    <button id="plays-by-month-previous-months" data-page="2" data-href="{{ url('ajaxPlayByMonthPrevious/' . $GLOBALS['parameters']['general']['username']) }}" class="btn btn-primary">Mois précédent</button>
    <canvas id="plays-by-month" width="400" height="100"></canvas>
    <div id="plays-by-month-legend" class="legend"></div>
    <script>
        window.playByMonthChartData = {
            labels: [{!! $graphs['byMonth']['labels'] !!}],
            datasets: [
                {
                    label: "Parties totales",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "#D3B78F",
                    pointColor: "#BA9E75",
                    pointStrokeColor: "#fff",
                    data: [{!! $graphs['byMonth']['serie1'] !!}]
                },
                {
                    label: "Jeux différents",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "#61799A",
                    pointColor: "#576271",
                    pointStrokeColor: "#fff",
                    data: [{!! $graphs['byMonth']['serie2'] !!}]
                },
                {
                    label: "Nouveaux jeux essayés",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "#B7C3D3",
                    pointColor: "#A0AAB7",
                    pointStrokeColor: "#fff",
                    data: [{!! $graphs['byMonth']['serie3'] !!}]
                }
            ]
        };
        window.playByMonthOptions = {
            bezierCurveTension : 0.3,
            pointDotRadius : 6
        };
        var playByMonthCtx = document.getElementById("plays-by-month").getContext("2d");
        var playByMonthChart = new Chart(playByMonthCtx).Line(window.playByMonthChartData, window.playByMonthOptions);

        $('#plays-by-month-legend').append(playByMonthChart.generateLegend());
    </script>
</div>