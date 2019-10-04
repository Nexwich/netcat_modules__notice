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
      data.select.searchField = ['name', 'email'];
      data.select.render = {
        item: function (item, escape){
          var name = item.name;
          return '<div>' +
            '<div class="name">' + escape(name) + '</div>' +
            '</div>';
        },
        option: function (item, escape){
          var name = item.full_name;
          return '<div>' +
            '<div class="name">' + escape(name) + '</div>' +
            '</div>';
        }
      };

      if(data.options.load.value){
        if(Array.isArray(data.options.load.value)){
          for($i = 0; $i < data.options.load.value.length; $i++){
            data.select.items.push({id: data.options.load.value[$i], name: data.options.load.value[$i]});
            data.select.options.push({id: data.options.load.value[$i], name: data.options.load.value[$i]});
          }
        }else{
          data.select.items.push({id: data.options.load.value[$i], name: data.options.load.value[$i]});
          data.select.options.push({id: data.options.load.value[$i], name: data.options.load.value[$i]});
        }
      }

      var $select = $this.selectize(data.select);

      // fetch the instance
      var selectize = $select[0].selectize;
    }
  });
}