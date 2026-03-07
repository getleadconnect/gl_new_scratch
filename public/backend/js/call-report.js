function populateBarChart(incoming, outgoing) {
  if (window.chartBar !== undefined) {
    window.chartBar.destroy()
  };
  var ctx = document.getElementById("myChart");
  chartBar = new Chart(ctx, {
    type: 'bar',
    data: {
      datasets: [{
        label: 'Calls',
        data: [
          { x: 'Incoming', y: incoming },
          { x: 'Outgoing', y: outgoing }
        ],
        backgroundColor: [
          '#F94144',
          '#F3722C',
        ]
      }]
    },
    options: {
      barThickness: 5,
    },
  });
  return chartBar;
}

function populatePieChart(incoming, missed) {
  if (window.myPie !== undefined) {
    window.myPie.destroy()
  };
  const ctxp = document.getElementById('chart-area').getContext('2d');
  const data = {
    "Attented": incoming,
    "Unattended": missed,
  }
  const config = {
    type: 'pie',
    data: {
      datasets: [{
        data: Object.values(data),
        backgroundColor: [
          '#90BE6D',
          '#F1394E',
        ],
      }],
      //   labels: Object.keys(data)
    },
    options: {
      responsive: false
    }
  };
  myPie = new Chart(ctxp, config);
  return myPie;
}
function randomScalingFactor() {
  return Math.round(Math.random() * 100);
};

function destroyChart() {
  if (window.myChart !== undefined) {
    myChart.destroy();
  };
} 