<h3>Nombre de parties totales par jour de la semaine</h3>
<div style="width: calc(100% - 20px);">
    <canvas id="plays-by-dayweek" width="400" height="100"></canvas>
    <div id="plays-by-dayweek-legend" class="legend"></div>
    <script>
        window.playByDayWeekChartData = {
            labels: [{!! $graphs['byDayWeek']['labels'] !!}],
            datasets: [
                {
                    label: "Parties totales",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "#D3B78F",
                    pointColor: "#BA9E75",
                    pointStrokeColor: "#fff",
                    data: [{!! $graphs['byDayWeek']['serie1'] !!}]
                }
            ]
        };
        window.playByDayWeekOptions = {
            barValueSpacing : 10,
            showTooltips: false,
            onAnimationComplete: function () {
                var ctx = this.chart.ctx;
                ctx.font = this.scale.font;
                ctx.fillStyle = this.scale.textColor;
                ctx.textAlign = "center";
                ctx.textBaseline = "bottom";

                this.datasets.forEach(function (dataset) {
                    dataset.bars.forEach(function (bar) {
                        ctx.fillText(bar.value, bar.x, bar.y);
                    });
                })
            }
        };
        var playByDayWeekCtx = document.getElementById("plays-by-dayweek").getContext("2d");
        var playByDayWeekChart = new Chart(playByDayWeekCtx).Bar(window.playByDayWeekChartData, window.playByDayWeekOptions);
    </script>
</div>