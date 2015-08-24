(function (global, $, nv, d3) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Visualization.Choropleth requires FS');
    }

    var FS = global.FS;

    var Choropleth = FS.Visualization.Component.extend({

        init: function (options) {
            this._super(options);

            this.cLevels = 9;
            this.cellWidth = 30; // Width of color legend cell
            this.cbarWidth = this.cellWidth*this.cLevels;
            this.cbarHeight = 15;  // Height of color legend
        },

        render: function() {
            var _this = this;

            $('#'+this.container).html('<div class="dataViewFetching"></div>');

            this.map = {};

            $.ajax({
                url: this.api_host,
                type: 'GET',
                dataType: 'json',
                data: this.getPreparedQuery()
            }).done(function( data ) {

                $('#'+_this.container).empty();

                var colorPalette = colorbrewer['Blues'][_this.cLevels];//.reverse();

                var mapValues = {};
                var amounts = [];
                for (var i = 0,len=data.length; i < len; i++) {
                    var stateCode = data[i].x.substr(0,2).replace(/[^a-zA-Z]/g, '');
                    if ( stateCode && parseInt(data[i].y) ) {
                        mapValues[stateCode] = parseInt(data[i].y);
                        amounts.push(parseInt(data[i].y));
                    }
                }

                var scale = d3.scale.quantize()
                    .domain(d3.extent(amounts))
                    .range(colorPalette);
                var colors = {};
                $.each(mapValues,function(k,v){
                    colors[k] = scale(v);
                });

                // Create map
                _this.map = new Datamap({
                    element: document.getElementById(_this.container),
                    scope: 'usa',
                    fills: {defaultFill: '#ffffff'},
                    data: mapValues,
                    responsive: true,
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
                _this.buildGradient(colorPalette, _this.container + '_amountGradient');

                // draw colorbar
                var cbar = d3.select('#' + _this.container + ' .datamap').append('g')
                    .attr('id', 'colorBar')
                    .attr('class', 'colorbar');

                cbar.append('rect')
                    .attr('class', 'gradientRect')
                    .attr('width', _this.cbarWidth)
                    .attr('height', _this.cbarHeight)
                    .style('fill', 'url(#amountGradient)');

                cbar.append('text')
                    .attr('class', 'colorbar colorBarMinText')
                    .attr('x', 0)
                    .attr('y', _this.cbarHeight + 15)
                    .attr('dx', 0)
                    .attr('dy', 0)
                    .attr('text-anchor', 'start');

                cbar.append('text')
                    .attr('class', 'colorbar colorBarMaxText')
                    .attr('x', _this.cbarWidth)
                    .attr('y', _this.cbarHeight + 15)
                    .attr('dx', 0)
                    .attr('dy', 0)
                    .attr('text-anchor', 'end');

                //cbar.attr('transform', 'translate(10, 0)');  // Shift to center

                d3.select('#' + _this.container + ' .gradientRect').style('fill', 'url(#'+_this.container+'_amountGradient)');
                d3.select('#' + _this.container + ' .colorBarMinText').text('0');

                var formatValue = d3.format('.1s');
                d3.select('#' + _this.container + ' .colorBarMaxText').text('Over '+ formatValue(d3.max(amounts)).replace('G', 'B'));

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
            d3.select('#' + this.container + ' .datamap')
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


