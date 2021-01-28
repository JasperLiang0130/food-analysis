class OwnChart{

    constructor(myChart, model, numOfSets = 1, isStacked = false){
        //Global options
        Chart.platform.disableCSSInjection = true;
        Chart.defaults.global.defaultFontFamily = 'Lato';
        Chart.defaults.global.defaultFontSize = 16;
        Chart.defaults.global.defaultFontColor = '#777';
        Chart.plugins.register({
            id: 'labels'
        });
        Chart.defaults.global.plugins.labels = {
            render: 'value' //for chartjs-plugin-labels extension
        };

        var opt = {
                scales: {
                    xAxes: [{ stacked: false }],
                    yAxes: [
                    {
                        // ticks: {
                        //     beginAtZero: true
                        // }
                    },
                    {
                        stacked: false,
                        display: false
                    }
                    ]
                },
                legend: {
                    display: true,
                    position : 'top'
                },
                title: {
                    display: false,
                    text: ''
                },
                plugins: {
                    labels: false
                }
            };

                
        if(model == ('pie' || 'doughnut')){
            opt = {
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: false,
                    text: ''
                },
                plugins: {
                    labels: {
                        render: 'percentage',
                        fontStyle: 'bold',
                        fontColor: '#fff',
                        precision: 2
                    }
                }
            };
        }


        this.chart = new Chart(myChart, {
            // The type of chart we want to create
            type: model,
            // The data for our dataset
            data: {
                labels: [], //x axis
                datasets: [{
                    label: 'This data', //display value of each column
                    fill: true,
                    borderColor: [],
                    backgroundColor: [],
                    data: []
                }
            ]
            },
            // Configuration options go here
            options: opt
        });

        if(isStacked == true){
            this.chart.options.scales.xAxes[0].stacked = true;
            this.chart.options.scales.yAxes[0].stacked = true;
        }

        if (model != ('pie' || 'doughnut')) {
            this.chart.options.legend.display = false;
        }

        for (let index = 1; index < numOfSets; index++) {
            var newDataset = {
                label: 'dataset'+(index+1), //display value of each column
                borderColor: [],
                backgroundColor: [],
                data: []
            }
            this.chart.config.data.datasets.push(newDataset);
        }
        this.chart.update();
        
    }

    //titles is array
    setDatasetLabels(labels){
        var len = this.chart.data.datasets.length;
        for (let index = 0; index < len; index++) {
            this.chart.data.datasets[index].label = labels[index];
        }
        this.chart.update();
    }

    setDisplayData(bool){
        this.chart.options.plugins.labels = bool;
        this.chart.update();
    }

    setDisplayDataPercentage(){
        this.chart.options.plugins.labels = {
            render : 'percentage',
            precision : 2
        }
        this.chart.update();
    }

    setUnderneathFill(bool){
        var len = this.chart.data.datasets.length;
        for (let index = 0; index < len; index++) {
            this.chart.data.datasets[index].fill = bool;
        }
        this.chart.update();
    }

    setLegendPos(pos){
        this.chart.options.legend.position = pos;
        this.chart.update();
    }

    setTitle(title){
        this.chart.options.title.display = true;
        this.chart.options.title.text = title;
        this.chart.update();
    }

    setDisplayLegend(bool){
        this.chart.options.legend.display =bool;
        this.chart.update();
    }

    //xlabels is array
    setXAxis(xlabels){
        this.chart.data.labels = xlabels;
        this.chart.update();
    }

    //bdColor is array or 2d array
    setBorderColor(bdColor){
        var len = this.chart.data.datasets.length;
        for (let index = 0; index < len; index++) {
            this.chart.data.datasets[index].borderColor = bdColor[index];
        }
        this.chart.update();
    }

    //bgColor is array or 2d array
    setBackgroundColor(bgColor){
        var len = this.chart.data.datasets.length;
        for (let index = 0; index < len; index++) {
            this.chart.data.datasets[index].backgroundColor = bgColor[index];
        }
        this.chart.update();
    }

    //arr format: []
    updatedChart(arr) {
        // console.log(arr);

        var len = this.chart.data.datasets.length;
        for (let index = 0; index < len; index++) {
            // console.log(Object.values(arr[index]));
            this.chart.data.datasets[index].data = Object.values(arr[index]);
        }
        this.chart.update();

    }

}

