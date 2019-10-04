(function ($){
  $(function (){

    var select = $("select.js-selectize:not(.active)");
    select.each(function (index, element){
      var $this = $(element);

      $this.addClass('active');
      load_select($this);
    });

  });
})(jQuery);

function load_select($this){
  var data = $this.data();

  $.ajax({
    url: "/netcat/modules/notice/admin/index.php",
    type: 'POST',
    async: false,
    data: {
      controller: 'select',
      action: 'list',
      dataType: 'json',
      type: data.options.load.type,
      value: data.options.load.value
    },
    success: function (response){
      data.select.options = response.options;
      data.select.items = response.items;
      data.select.labelField = 'name';
      data.select.valueField = 'id';
      data.select.searchField = ['name'];

      console.log(response);

      if(data.options.load.value && !response.items[data.options.load.value]){
        if(Array.isArray(data.options.load.value)){
          for($i = 0; $i < data.options.load.value.length; $i++){
            data.select.items.push({id: data.options.load.value[$i], name: data.options.load.value[$i]});
            data.select.options.push({id: data.options.load.value[$i], name: data.options.load.value[$i]});
          }
        }else{
          data.select.items.push({id: data.options.load.value, name: data.options.load.value});
          data.select.options.push({id: data.options.load.value, name: data.options.load.value});
        }
      }

      $this.selectize(data.select);
    }
  });
}