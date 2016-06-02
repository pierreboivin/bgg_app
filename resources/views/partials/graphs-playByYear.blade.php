<h3>Nombre de parties par années</h3>
<div style="width: calc(100% - 20px);">
    <button class="btn btn-primary graph-previous">Années précédentes</button>
    <button class="btn btn-primary graph-next">Années suivantes</button>

    <canvas class="graph-handler" id="playsByYear" width="400" height="100"></canvas>

    <input type="hidden" class="type" value="line">
    <input type="hidden" class="width" value="400">
    <input type="hidden" class="height" value="100">
    <input type="hidden" class="page" value="1">
    <input type="hidden" class="href" value="{{ url('ajaxPlayByYear/' . $GLOBALS['parameters']['general']['username']) }}">
    <input type="hidden" class="href-url" value="{{ url('ajaxPlayByYearGetUrl/' . $GLOBALS['parameters']['general']['username']) }}">

    <div id="plays-by-year-legend" class="legend"></div>
    <script>
        window.chartData['playsByYear'] = {
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
        window.chartOptions['playsByYear'] = {
            bezierCurveTension : 0.3,
            pointDotRadius : 6
        };
        var playByYearCtx = document.getElementById("plays-by-year").getContext("2d");
        window.chartInstance['playsByYear'] = new Chart(playByYearCtx).Line(window.chartData['playsByYear'], window.chartOptions['playsByYear']);

        $('#plays-by-year-legend').append(window.chartData['playsByYear'].generateLegend());
    </script>
</div>