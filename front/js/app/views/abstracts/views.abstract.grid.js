Views.Abstract.Grid = Views.Abstract.View.extend({
	
	_grid: null,
		
	_default_settings: {
		datatype: 'json',
		sortname: 'id',
	    sortorder: 'desc',
	    viewrecords: true,
	    gridview: true,
	    autowidth:true,
	    autoencode: true,
	    hidegrid: false,
	    rowNum: 10,
	    pager: '#default-pager',
	    height: '100%'
	},
	
	initialize: function(){
		Views.Abstract.View.prototype.initialize.apply(this, arguments);
		this.render();
	},
	
	render: function(){
		var settings = _.clone(this._default_settings);
		
		this._grid = this.$el.jqGrid(_.extend(settings, this._getGridSettings()));
	},
	
	_getGridSettings: function(){
		return {};
	}
});