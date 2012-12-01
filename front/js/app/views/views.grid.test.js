$(function(){
	Views.Grid.Test = Views.Grid.Table.extend({
		el: $('#test-bb-table'),
		
		url: Resources.modules_list,
			
		getCellSettings: function(){
			return {
				id: {
					label: 'ID',
				},
				
				title: {
					label: 'Тайтл',
					sortable: false
				},
				
				status: {
					label: 'Статус',
					formatter: function(value){
						if (value == 1){
							return '<span style="color:green">Активен</span>';
						}
						
						return '<span style="color:red">Не активен</span>';
					}
				},
				
				order: {
					label: 'Порядок'
				}
				
			}
		},
		
		_setControlsClass: function(){
			return Views.Admin.GridControls;
		}
	});
});