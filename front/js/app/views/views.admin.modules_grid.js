$(function(){
	Views.Admin.ModulesGrid = Views.Abstract.Grid.extend({
		
		el: $('#default-grid'),
		
		_getGridSettings: function(){
			return {
				url: Resources.modules_list,
			    colNames:['ID','Title', 'Description'],
			    colModel :[ 
			      {name: 'id', index: 'id', width: 20, resizable: false, align: 'center'}, 
			      {name: 'title', index: 'title'}, 
			      {name: 'description', index: 'description'},
			    ],
			    caption: 'Доступные модули',
			    multiselect: true,
			  }
		}
	});
});