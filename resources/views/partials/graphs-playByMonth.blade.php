<h3>Nombre de parties par mois</h3>
<div style="width: calc(100% - 20px);">
    <button id="plays-by-month-previous-months" class="btn btn-primary">Mois précédent</button>
    <button id="plays-by-month-next-months" class="btn btn-primary">Mois suivant</button>

    <canvas id="plays-by-month" width="400" height="100"></canvas>

    <input type="hidden" id="plays-by-month-page" value="1">
    <input type="hidden" id="plays-by-month-href" value="{{ url('ajaxPlayByMonth/' . $GLOBALS['parameters']['general']['username']) }}">

    <div id="plays-by-month-legend" class="legend"></div>
    <script>
        window.playByMonthChartData = {
            labels: '',
            datasets: [
                {
                    label: "Nombre de parties",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "#D3B78F",
                    pointColor: "#BA9E75",
                    pointStrokeColor: "#fff",
                    data: ''
                },
                {
                    label: "Nombre de jeux joués",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "#61799A",
                    pointColor: "#576271",
                    pointStrokeColor: "#fff",
                    data: ''
                },
                {
                    label: "Nombre de nouveaux jeux essayés",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "#B7C3D3",
                    pointColor: "#A0AAB7",
                    pointStrokeColor: "#fff",
                    data: ''
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