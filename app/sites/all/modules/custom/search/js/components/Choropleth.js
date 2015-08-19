(function (global, $, nv, d3) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Util.DateFormat requires FS');
    }

    var FS = global.FS;

    var Choropleth = FS.Class.extend({

        init: function (options) {
            $.extend(this, options);

            this.cLevels = 9;
            this.cellWidth = 30; // Width of color legend cell
            this.cbarWidth = this.cellWidth*this.cLevels;
            this.cbarHeight = 15;  // Height of color legend

        },

        setSearchQuery: function ( searchQuery ) {
            if ( typeof searchQuery !== 'undefined' ) {
                this.query['$where'] = searchQuery + ' AND ' + this.query['$where'];
            }
            return this;
        },

        render: function() {
            var _this = this;

            $('#'+this.container).empty();

            this.map = {};

            $.ajax({
                url: this.api_host,
                type: 'GET',
                dataType: 'json',
                data: this.query
            }).done(function( data ) {

                var mapValues = {};
                var amounts = [];
                for (var i = 0,len=data.length; i < len; i++) {
                    mapValues[data[i].x.substr(0,2)] = parseInt(data[i].y);
                    amounts.push(data[i].y);
                }

                var scale = d3.scale.quantize()
                    .domain(d3.extent(amounts))
                    .range(colorbrewer['Blues'][_this.cLevels].reverse());
                var colors = {};
                for (var i = 0,len=data.length; i < len; i++) {
                    colors[data[i].x.substr(0,2)] = scale(parseInt(data[i].y));
                }

                //console.log(mapValues);
                //console.log(colors);

                // Create map
                _this.map = new Datamap({
                    element: document.getElementById(_this.container),
                    scope: 'usa',
                    height: 400,
                    width: 600,
                    fills: {defaultFill: '#ffffff'},
                    data: mapValues,
                    geographyConfig: {
                        borderWidth: 1,
                        borderColor: '#999999',
                        popupOnHover: true,
                        highlightOnHover: false,
                        highlightFillColor: '#bbaa99',
                        highlightBorderColor: '#999999',
                        highlightBorderWidth: 2,
                        popupTemplate: _this.getStatePopup
                    }
                });

                // build gradient
                _this.buildGradient(colorbrewer['Blues'][_this.cLevels].reverse(), 'amountGradient');

                // draw colorbar
                var visWidth = document.getElementById(_this.container).offsetWidth/2;

                var cbar = d3.select('.datamap').append('g')
                    .attr('id', 'colorBar')
                    .attr('class', 'colorbar');

                cbar.append('rect')
                    .attr('id', 'gradientRect')
                    .attr('width', _this.cbarWidth)
                    .attr('height', _this.cbarHeight)
                    .style('fill', 'url(#amountGradient)');

                cbar.append('text')
                    .attr('id', 'colorBarMinText')
                    .attr('class', 'colorbar')
                    .attr('x', 0)
                    .attr('y', _this.cbarHeight + 15)
                    .attr('dx', 0)
                    .attr('dy', 0)
                    .attr('text-anchor', 'start');

                cbar.append('text')
                    .attr('id', 'colorBarMaxText')
                    .attr('class', 'colorbar')
                    .attr('x', _this.cbarWidth)
                    .attr('y', _this.cbarHeight + 15)
                    .attr('dx', 0)
                    .attr('dy', 0)
                    .attr('text-anchor', 'end');

                cbar.attr('transform', 'translate(' + (visWidth-_this.cbarWidth)/2.0 + ', 30)');  // Shift to center

                d3.select('#gradientRect').style('fill', 'url(#amountGradient)');
                d3.select('#colorBarMinText').text('0');

                var formatValue = d3.format('.1s');
                d3.select('#colorBarMaxText').text('Over '+ formatValue(d3.max(amounts)).replace('G', 'B'));

                _this.map.updateChoropleth(colors);

                if ( typeof _this.doneCallback === 'function' ) {
                    _this.doneCallback();
                }

            }).fail(function(){
                if ( typeof _this.failCallback === 'function' ) {
                    _this.failCallback();
                }
            });

        },

        getStatePopup: function (geography, data) {
            var template = '<div class="hoverinfo">'
                + '<div class="hover-state-title" align="center">'
                + '  <strong>' + geography.properties.name + '</strong>'
                + '</div>'
                + '<div class="hover-state-stats">'
                + 'Amount: <strong>' + FS.Util.NumberFormat.getCurrency(data,0) + '</strong>'
                + '</div>'
                + '</div>';
            return template;
        },

        buildGradient: function(palette, gradientId) {
            var _this = this;
            d3.select('.datamap')
                .append('linearGradient')
                .attr('id', gradientId)
                .attr("gradientUnits", "userSpaceOnUse")
                .attr("x1", 0)
                .attr("y1", 0)
                .attr("x2", this.cbarWidth)
                .attr("y2", 0)
                .selectAll('stop')
                .data(palette)
                .enter()
                .append('stop')
                .attr('offset', function(d, i) {return i/(_this.cLevels-1)*100.0 + '%'; })
                .attr('stop-color', function(d) {return d; });
        }

    });

    FS.Visualization.Choropleth = Choropleth;

})(typeof window === 'undefined' ? this : window, jQuery, nv, d3);


