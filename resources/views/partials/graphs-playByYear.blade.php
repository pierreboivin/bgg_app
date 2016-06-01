<h3>Nombre de parties par années</h3>
<div style="width: calc(100% - 20px);">
    <button id="plays-by-year-previous-year" class="btn btn-primary">Années précédentes</button>
    <button id="plays-by-year-next-year" class="btn btn-primary">Années suivantes</button>

    <canvas id="plays-by-year" width="400" height="100"></canvas>

    <input type="hidden" id="plays-by-year-page" value="1">
    <input type="hidden" id="plays-by-year-href" value="{{ url('ajaxPlayByYear/' . $GLOBALS['parameters']['general']['username']) }}">

    <div id="plays-by-year-legend" class="legend"></div>
    <script>
        window.playByYearChartData = {
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
        window.playByYearOptions = {
            bezierCurveTension : 0.3,
            pointDotRadius : 6
        };
        var playByYearCtx = document.getElementById("plays-by-year").getContext("2d");
        var playByYearChart = new Chart(playByYearCtx).Line(window.playByYearChartData, window.playByYearOptions);

        $('#plays-by-year-legend').append(playByYearChart.generateLegend());
    </script>
</div>