<h3>Nombre de parties par mois</h3>
<div style="width: calc(100% - 20px);">
    <button class="btn btn-primary graph-previous">Mois précédents</button>
    <button class="btn btn-primary graph-next">Mois suivants</button>

    <canvas class="graph-handler" id="playByMonth"></canvas>

    <input type="hidden" class="type" value="line">
    <input type="hidden" class="page" value="1">
    <input type="hidden" class="href" value="{{ url('ajaxPlayByMonth/' . $GLOBALS['parameters']['general']['username']) }}">
    <input type="hidden" class="href-url" value="{{ url('ajaxPlayByMonthGetUrl/' . $GLOBALS['parameters']['general']['username']) }}">

    <div id="plays-by-month-legend" class="legend"></div>
    <script>
        window.chartData['playByMonth'] = {
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
        window.chartOptions['playByMonth'] = {
            bezierCurveTension : 0.3,
            pointDotRadius : 6
        };
        var playByMonthCtx = document.getElementById("playByMonth").getContext("2d");
        window.chartInstance['playByMonth'] = new Chart(playByMonthCtx).Line(window.chartData['playByMonth'], window.chartOptions['playByMonth']);

        $('#plays-by-month-legend').append(window.chartInstance['playByMonth'].generateLegend());
    </script>
</div>