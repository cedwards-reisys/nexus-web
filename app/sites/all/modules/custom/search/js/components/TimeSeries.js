(function (global, $, d3) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Visualization.TimeSeries requires FS');
    }

    var FS = global.FS;

    var TimeSeries = FS.Visualization.Component.extend({

        init: function (options) {
            this._super(options);
        },

        render: function() {
            var _this = this;

            $(this.container).empty();

            this.chart = {};

            $.ajax({
                url: this.api_host,
                type: 'GET',
                dataType: 'json',
                data: this.getPreparedQuery()
            }).done(function( data ) {

                _this.chart = _this.getChart(data);

                if ( typeof _this.doneCallback === 'function' ) {
                    _this.doneCallback();
                }

            }).fail(function(){
                if ( typeof _this.failCallback === 'function' ) {
                    _this.failCallback();
                }
            });
        },

        getChart: function ( data ) {

            var margin = {top: 10, right: 10, bottom: 100, left: 50},
                margin2 = {top: 430, right: 10, bottom: 20, left: 50},
                width = $(this.container).parent().parent().width() - margin.left - margin.right,
                height = 500 - margin.top - margin.bottom,
                height2 = 500 - margin2.top - margin2.bottom;

            var parseDate = d3.time.format.iso.parse;

            for (var i=0,len=data.length; i<len; i++) {
                data[i].date = parseDate(data[i].date);
                data[i].amount = parseInt(data[i].amount);
            }

            var x = d3.time.scale().range([0, width]),
                x2 = d3.time.scale().range([0, width]),
                y = d3.scale.linear().range([height, 0]),
                y2 = d3.scale.linear().range([height2, 0]);

            var formatYAxisTick = d3.format('.3s');

            var xAxis = d3.svg.axis()
                .scale(x)
                .orient("bottom")
                //.ticks(d3.time.months, 1)
                //.tickFormat(d3.time.format('%b %y'))
                .tickSize(6)
                .tickPadding(8);

            var yAxis = d3.svg.axis()
                .scale(y)
                .orient("left")
                .tickFormat(function(d){
                    return formatYAxisTick(d).replace('G', 'B');
                });

            var xAxis2 = d3.svg.axis()
                .scale(x2)
                .orient("bottom");

            var brush = d3.svg.brush()
                .x(x2)
                .on("brush", function() {
                    x.domain(brush.empty() ? x2.domain() : brush.extent());
                    focus.select(".area").attr("d", area);
                    focus.select(".x.axis").call(xAxis);
                });

            var area = d3.svg.area()
                .interpolate("monotone")
                .x(function(d) { return x(d.date); })
                .y0(height)
                .y1(function(d) { return y(d.amount); });

            var area2 = d3.svg.area()
                .interpolate("monotone")
                .x(function(d) { return x2(d.date); })
                .y0(height2)
                .y1(function(d) { return y2(d.amount); });

            var svg = d3.select(this.container)
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom);

            svg.append("defs").append("clipPath")
                .attr("id", "clip")
                .append("rect")
                .attr("width", width)
                .attr("height", height);

            var focus = svg.append("g")
                .attr("class", "focus")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

            var context = svg.append("g")
                .attr("class", "context")
                .attr("transform", "translate(" + margin2.left + "," + margin2.top + ")");

            x.domain(d3.extent(data.map(function(d) { return d.date; })));
            y.domain([0, d3.max(data.map(function(d) { return d.amount; }))]);
            x2.domain(x.domain());
            y2.domain(y.domain());

            focus.append("path")
                .datum(data)
                .attr("class", "area")
                .attr("d", area);

            focus.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis);

            focus.append("g")
                .attr("class", "y axis")
                .call(yAxis);

            context.append("path")
                .datum(data)
                .attr("class", "area")
                .attr("d", area2);

            context.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height2 + ")")
                .call(xAxis2);

            context.append("g")
                .attr("class", "x brush")
                .call(brush)
                .selectAll("rect")
                .attr("y", -6)
                .attr("height", height2 + 7);

            return svg;
        }

    });

    FS.Visualization.TimeSeries = TimeSeries;

})(typeof window === 'undefined' ? this : window, jQuery, d3);


