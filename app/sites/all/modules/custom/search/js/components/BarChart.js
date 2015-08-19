(function (global, $, nv, d3) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Util.DateFormat requires FS');
    }

    var FS = global.FS;

    var BarChart = FS.Class.extend({

        init: function (options) {
            this.options = options;
            $.extend(this, options);

        },

        setSearchQuery: function ( searchQuery ) {
            this.query['$where'] = searchQuery;
        },

        render: function() {
            var _this = this;

            this.chart = nv.models.discreteBarChart()
                .x(function(d) { return d.label })
                .y(function(d) { return d.value })
                .showValues(true)
                .showYAxis(false)
                .showXAxis(false);

            this.chart.tooltip.enabled(true);

            this.chart.yAxis.tickFormat(function(d) {
                return FS.Util.NumberFormat.getCurrency(d,0);
            });

            var formatValue = d3.format('.3s');
            this.chart.valueFormat(function(d){
                return formatValue(d).replace('G', 'B');
            });

            nv.addGraph(function() {
                _this.updateData();
                nv.utils.windowResize(_this.chart.update);
                return _this.chart;
            });

        },

        updateData: function () {
            var _this = this;

            $.ajax({
                url: this.api_host,
                type: 'GET',
                dataType: 'json',
                data: this.query
            }).done(function( data ) {
                var chartValues = [];
                for (var i = 0,len=data.length; i < len; i++) {
                    chartValues.push({
                        label: data[i].x,
                        value: parseInt(data[i].y)
                    });
                }

                var chartData = [{
                    key: 'Sum',
                    values: chartValues
                }];

                d3.select(_this.options.container)
                    .datum(chartData)
                    .transition()
                    .duration(500)
                    .call(_this.chart);

                if ( typeof _this.doneCallback === 'function' ) {
                    _this.doneCallback();
                }

            }).fail(function(){
                if ( typeof _this.failCallback === 'function' ) {
                    _this.failCallback();
                }
            });



        }

    });

    FS.Visualization.BarChart = BarChart;

})(typeof window === 'undefined' ? this : window, jQuery, nv, d3);


