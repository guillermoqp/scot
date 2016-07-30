function Unique(select_class,no_select_value){
	this.select_class = select_class;//select class is the class of the drop down lists(<select> tags) that have to be exclusive
	this.no_select_value = no_select_value;//value that is present in the drop down list by default
	this.list_values = Array.apply(null, new Array($(this.select_class).length)).map(String.prototype.valueOf,this.no_select_value);
	
	var _self = this;
	$(select_class).change(function(){
		var prev_val = _self.list_values[$(_self.select_class).index($(this))];
		_self.list_values[$(_self.select_class).index($(this))] = $(this).val();
		_self.enableoption(_self.select_class,prev_val);
		_self.removeoption(_self.select_class,$(this).val(),$(_self.select_class).index($(this)));
	});
}

Unique.prototype.removeoption = function(classname,value,index){
	if(value==this.no_select_value)return;
	var arr = $(classname);
	for(var i=0;i<arr.length;i++){
		if(i==index)continue;
		$(arr[i]).children('option[value="'+value+'"]').attr('disabled',true);
	}
}

Unique.prototype.enableoption = function(classname,value){
	var arr = $(classname);
	for(var i=0;i<arr.length;i++){
		$(arr[i]).children('option[value="'+value+'"]').removeAttr('disabled');
	}
}
