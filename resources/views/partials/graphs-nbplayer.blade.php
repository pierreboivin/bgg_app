<h3>Nombre de joueur</h3>
<div style="width: calc(100% - 20px);">
    <canvas id="chart-nb-player" width="400" height="100"></canvas>
    <script>
        window.nbPlayerChartData = {
            labels: [{!! $graphs['nbPlayer']['labels'] !!}],
            datasets: [
                {
                    label: "Nombre de joueur",
                    fillColor: "#B7C3D3",
                    strokeColor: "#A0AAB7",
                    data: [{!! $graphs['nbPlayer']['serie1'] !!}]
                }
            ]
        };
        window.nbPlayerOptions = {
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
                        ctx.fillText(bar.value, bar.x, bar.y - 5);
                    });
                })
            }
        };
        var nbPlayerCtx = document.getElementById("chart-nb-player").getContext("2d");
        var nbPlayerChart = new Chart(nbPlayerCtx).Bar(window.nbPlayerChartData, window.nbPlayerOptions);
    </script>
</div>