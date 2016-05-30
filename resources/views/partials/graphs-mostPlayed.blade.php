<h3>Jeux les plus joués selon le nombre de parties</h3>
<div style="width: calc(100% - 20px);">
    <button id="most-played-previous-games" class="btn btn-primary">Jeux précédents</button>
    <button id="most-played-next-games" class="btn btn-primary">Jeux suivants</button>

    <canvas id="chart-most-played" width="400" height="200"></canvas>

    <input type="hidden" id="most-played-page" value="1">
    <input type="hidden" id="most-played-href" value="{{ url('ajaxMostPlayedPrevious/' . $GLOBALS['parameters']['general']['username']) }}">

    <script>
        window.mostPlayedChartData = {
            labels: '',
            datasets: [
                {
                    label: "Parties jouées",
                    fillColor: "#B7C3D3",
                    strokeColor: "#A0AAB7",
                    data: ''
                }
            ]
        };
        window.mostPlayedOptions = {
            barValueSpacing : 10,
            showTooltips: false,
            onAnimationComplete: function () {
                var ctx = this.chart.ctx;
                ctx.font = this.scale.font;
                ctx.fillStyle = this.scale.textColor
                ctx.textAlign = "center";
                ctx.textBaseline = "bottom";

                this.datasets.forEach(function (dataset) {
                    dataset.bars.forEach(function (bar) {
                        ctx.fillText(bar.value, bar.x, bar.y);
                    });
                })
            }
        };
        var mostPlayedCtx = document.getElementById("chart-most-played").getContext("2d");
        var mostPlayedChart = new Chart(mostPlayedCtx).Bar(window.mostPlayedChartData, window.mostPlayedOptions);
    </script>
</div>