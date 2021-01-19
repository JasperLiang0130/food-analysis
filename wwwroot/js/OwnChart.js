class OwnChart{

    constructor(myChart, model, numOfSets = 1, isStacked = false){
        //Global options
        Chart.platform.disableCSSInjection = true;
        Chart.defaults.global.defaultFontFamily = 'Lato';
        Chart.defaults.global.defaultFontSize = 16;
        Chart.defaults.global.defaultFontColor = '#777';

        var opt = {
                scales: {
                    xAxes: [{ stacked: false }],
                    yAxes: [{
                        ticks: {
                            display: false,
                            beginAtZero: true
                        }
                    },
                    {
                        stacked: false,
                        display: false
                    }
                    ]
                }
                };
                
        if(model == 'pie'){
            opt = {};
        }

        this.chart = new Chart(myChart, {
            // The type of chart we want to create
            type: model,
            // The data for our dataset
            data: {
                labels: [], //x axis
                datasets: [{
                    label: 'dataset1', //display value of each column
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
    setTitles(titles){
        var len = this.chart.data.datasets.length;
        for (let index = 0; index < len; index++) {
            this.chart.data.datasets[index].label = titles[index];
        }
        this.chart.update();
    }

    //xlabels is array
    setXAxis(xlabels){
        this.chart.data.labels = xlabels;
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
            console.log(Object.values(arr[index]));
            this.chart.data.datasets[index].data = Object.values(arr[index]);
        }
        this.chart.update();

    }

}