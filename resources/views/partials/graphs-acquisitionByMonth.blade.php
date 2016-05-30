<h3>Nombre de jeux et extensions acquis par mois</h3>
<div style="width: calc(100% - 20px);">
    <button id="acquisition-previous-month" class="btn btn-primary">Mois précédents</button>
    <button id="acquisition-next-month" class="btn btn-primary">Mois suivants</button>

    <canvas id="chart-acquisitionByMonth" width="400" height="100"></canvas>

    <input type="hidden" id="acquisition-page" value="1">
    <input type="hidden" id="acquisition-href" value="{{ url('ajaxAcquisitionPrevious/' . $GLOBALS['parameters']['general']['username']) }}">

    <script>
        window.acquisitionByMonthChartData = {
            labels: '',
            datasets: [
                {
                    label: "Nombre de jeux acquis",
                    fillColor: "#B7C3D3",
                    strokeColor: "#A0AAB7",
                    data: ''
                }
            ]
        };
        window.acquisitionByMonthOptions = {
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
        var acquisitionByMonthCtx = document.getElementById("chart-acquisitionByMonth").getContext("2d");
        var acquisitionByMonthChart = new Chart(acquisitionByMonthCtx).Bar(window.acquisitionByMonthChartData, window.acquisitionByMonthOptions);
    </script>
</div>