<h3>Nombre de parties et jeux possédés selon votre évaluation des jeux</h3>
<div style="width: calc(100% - 20px);">
    <canvas id="playsByRating"></canvas>

    <div id="plays-by-rating-legend" class="legend"></div>
    <script>
        window.chartData['playsByRating'] = {
            labels: [{!! $graphs['playsByRating']['labels'] !!}],
            datasets: [
                {
                    label: "Nombre de parties",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "#D3B78F",
                    pointColor: "#BA9E75",
                    pointStrokeColor: "#fff",
                    data: [{!! $graphs['playsByRating']['serie1'] !!}]
                },
                {
                    label: "Nombre de jeux possédés",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "#61799A",
                    pointColor: "#576271",
                    pointStrokeColor: "#fff",
                    data: [{!! $graphs['playsByRating']['serie2'] !!}]
                }
            ]
        };
        window.chartOptions['playsByRating'] = {
            bezierCurveTension : 0.3,
            pointDotRadius : 6
        };
        var playByRatingCtx = document.getElementById("playsByRating").getContext("2d");
        window.chartInstance['playsByRating'] = new Chart(playByRatingCtx).Line(window.chartData['playsByRating'], window.chartOptions['playsByRating']);

        $('#plays-by-rating-legend').append(window.chartInstance['playsByRating'].generateLegend());
    </script>
</div>